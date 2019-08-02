<?
use \Bitrix\Main\Localization\Loc;

// обработчик запросов для страницы "Объявления сотрудников"
switch ($action) {

    case 'new': // создание нового объявления
        if (!$currentUserId)
            throw new Exception(Loc::getMessage('ERROR_AUTH'));
        
        $employeeAnnounceText = trim($request->getPost('new-employee-announce-text'));
        $fields = [
            'ACTIVE' => 'N',
            'NAME' => trim($request->getPost('new-employee-announce-name')),
            'IBLOCK_ID' => $optionUnits['IBlocks'][INFS_RUSVINYL_IBLOCK_EMPLOYEE_ANNOUNCE],
            'DETAIL_TEXT' => $employeeAnnounceText,
            'PROPERTY_VALUES' => [
                INFS_IB_EMPLOYEE_ANNOUNCE_PR_THEME => trim($request->getPost('new-employee-announce-theme'))
            ]
        ];

        $employeeAnnounceIBElement = new CIBlockElement;
        if (!($employeeAnnounceId = $employeeAnnounceIBElement->Add($fields)))
          throw new Exception($employeeAnnounceIBElement->LAST_ERROR);

        $answer['data'] = $employeeAnnounceId;
        break;

    case 'list': // получение списка Объявлений на указанной странице
        /**
         * TO DO: Далее код совпадает пусть и не на 100%, но сильно с кодом
         * для такого же обработчика для пожеланий, желательно как-то 
         * объединить в общее 
         */
        $currentPage = intval($request->get('page')) ?: 1;
        $filter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $optionUnits['IBlocks'][INFS_RUSVINYL_IBLOCK_EMPLOYEE_ANNOUNCE]
        ];
        $answer['data'] = [
            'list' => [],
            'pages' => [
                'current' => $currentPage,
                'count' => ceil(intval(CIBlockElement::GetList([], $filter, [])) / INFS_IB_EMPLOYEE_ANNOUNCE_PAGE_SIZE)
            ]
        ];
        $employeeAnnounces = CIBlockElement::GetList(
                            ['ID' => 'DESC'], $filter, false,
                            [
                                'nPageSize' => INFS_IB_EMPLOYEE_ANNOUNCE_PAGE_SIZE,
                                'iNumPage' => $currentPage
                            ],
                            array_merge(
                                ['ID', 'NAME', 'DETAIL_TEXT', 'DETAIL_PICTURE', 'CREATED_BY'],
                                array_map(
                                    function($value) {
                                        return 'PROPERTY_' . $value;
                                    }, INFS_IB_EMPLOYEE_ANNOUNCE_ALL_PROPERTIES
                                )
                            )
                        );

        $userIds = [];
        while ($employeeAnnounce = $employeeAnnounces->Fetch()) {
            $userIds[] = $employeeAnnounce['CREATED_BY'];
            $unit = array_filter(
                        $employeeAnnounce,
                        function($key) {
                            return !preg_match('/^~|PROPERTY/i', $key) || preg_match('/^PROPERTY\w+VALUE$/i', $key);
                        }, ARRAY_FILTER_USE_KEY
                    )
                  + ['MORE_DETAIL_TEXT' => false];

            foreach (INFS_IB_EMPLOYEE_ANNOUNCE_ALL_PROPERTIES as $propertyCode) {
                $unitKeyCode = 'PROPERTY_' . $propertyCode . '_VALUE';
                $unit['PROPERTIES'][$propertyCode] = $unit[$unitKeyCode];
                unset($unit[$unitKeyCode]);
            }
            if (strlen($unit['DETAIL_TEXT']) > INFS_IB_EMPLOYEE_ANNOUNCE_TEXT_LENGHT)
                $unit['MORE_DETAIL_TEXT'] = true;

            $unit['DETAIL_TEXT'] = nl2br(Infoservice\RusVinyl\Helpers\StringWorker::setMaxNewLine(strip_tags($unit['DETAIL_TEXT'])));

            if (!empty($unit['DETAIL_PICTURE'])) {
                $unit['DETAIL_PICTURE'] = CFile::GetPath($unit['DETAIL_PICTURE']);
                $unit['MORE_DETAIL_TEXT'] = true;
            }

            $answer['data']['list'][] = $unit;
        }
        if (!empty($userIds)) {
            $userData = [];
            $users = CUser::GetList($field = 'ID', $dir = 'ASC', ['ID' => implode(' | ', $userIds)]);
            while ($user = $users->Fetch()) {
                $user['FULL_NAME'] = trim($user['LAST_NAME'] . ' ' . $user['NAME'] . ' ' . $user['SECOND_NAME']) ?: $user['LOGIN'];
                $userData[$user['ID']] = $user;
            }
            foreach ($answer['data']['list'] as &$employeeAnnounce) {
                $employeeAnnounce['FULL_NAME'] = $userData[$employeeAnnounce['CREATED_BY']]['FULL_NAME'];
            }
        }
        break;

    default:
        throw new Exception(Loc::getMessage('ERROR_BAD_ACTION'));
}