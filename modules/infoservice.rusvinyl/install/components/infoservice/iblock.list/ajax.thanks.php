<?
use \Bitrix\Main\Localization\Loc;

// обработчик запросов для страницы "Сказать "Спасибо"
switch ($action) {

    case 'new': // создание нового пожелания
        if (!$currentUserId)
            throw new Exception(Loc::getMessage('ERROR_AUTH'));
            
        $fields = [
            'ACTIVE' => 'N',
            'NAME' => trim($request->getPost('new-thanks-name')),
            'IBLOCK_ID' => $optionUnits['IBlocks'][INFS_RUSVINYL_IBLOCK_THANKS],
            'DETAIL_TEXT' => trim($request->getPost('new-thanks-text')),
            'PROPERTY_VALUES' => [
                INFS_IB_THANKS_PR_RECIPIENT => $request->getPost('new-thanks-user')
            ]
        ];
        if (!empty($fileData = $request->getFile('new-thanks-file')))
            $fields['DETAIL_PICTURE'] = $fileData;

        $thanksIBElement = new CIBlockElement;
        if (!($thanksId = $thanksIBElement->Add($fields)))
          throw new Exception($thanksIBElement->LAST_ERROR);

        $answer['data'] = $thanksId;
        break;

    case 'list': // получение списка пожеланий на указанной странице
        $currentPage = intval($request->get('page')) ?: 1;
        $filter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $optionUnits['IBlocks'][INFS_RUSVINYL_IBLOCK_THANKS]
        ];
        $answer['data'] = [
            'list' => [],
            'pages' => [
                'current' => $currentPage,
                'count' => ceil(intval(CIBlockElement::GetList([], $filter, [])) / INFS_IB_THANKS_PAGE_SIZE)
            ]
        ];
        $dbThanks = CIBlockElement::GetList(
                            ['ID' => 'DESC'], $filter, false,
                            [
                                'nPageSize' => INFS_IB_THANKS_PAGE_SIZE,
                                'iNumPage' => $currentPage
                            ],
                            array_merge(
                                ['ID', 'NAME', 'DETAIL_TEXT', 'DETAIL_PICTURE', 'CREATED_BY'],
                                array_map(
                                    function($value) {
                                        return 'PROPERTY_' . $value;
                                    }, INFS_IB_THANKS_ALL_PROPERTIES
                                )
                            )
                        );
        $userIds = [];
        $userProperties = [INFS_IB_THANKS_PR_RECIPIENT];
        while ($thanksUnit = $dbThanks->Fetch()) {
            $userIds[] = $thanksUnit['CREATED_BY'];

            $answer['data']['test'][] = $thanksUnit;

            $unit = array_filter(
                        $thanksUnit,
                        function($key) {
                            return !preg_match('/^~|PROPERTY/i', $key) || preg_match('/^PROPERTY\w+VALUE$/i', $key);
                        }, ARRAY_FILTER_USE_KEY
                    )
                  + ['MORE_DETAIL_TEXT' => false];
            foreach (INFS_IB_THANKS_ALL_PROPERTIES as $propertyCode) {
                $unitKeyCode = 'PROPERTY_' . $propertyCode . '_VALUE';

                if (in_array($propertyCode, $userProperties))
                    $userIds = array_merge(
                                    $userIds,
                                    is_array($unit[$unitKeyCode])
                                        ? $unit[$unitKeyCode]
                                        : [$unit[$unitKeyCode]]
                                );

                $unit['PROPERTIES'][$propertyCode] = $unit[$unitKeyCode];
                unset($unit[$unitKeyCode]);
            }
            if (strlen($unit['DETAIL_TEXT']) > INFS_IB_THANKS_TEXT_LENGHT)
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
            foreach ($answer['data']['list'] as &$thanksUnit) {
                $thanksUnit['FULL_NAME'] = $userData[$thanksUnit['CREATED_BY']]['FULL_NAME'];
                foreach ($userProperties as $userProperty) {
                    $userIds = is_array($thanksUnit['PROPERTIES'][$userProperty]) ? $thanksUnit['PROPERTIES'][$userProperty]
                             : [$thanksUnit['PROPERTIES'][$userProperty]];
                    $thanksUnit['PROPERTIES'][$userProperty] = [];
                    foreach ($userIds as $userId) {
                        $thanksUnit['PROPERTIES'][$userProperty][] = [
                            'ID' => $userId,
                            'FULL_NAME' => $userData[$userId]['FULL_NAME']
                        ];
                    }
                }
            }
        }
        break;

    default:
        throw new Exception(Loc::getMessage('ERROR_BAD_ACTION'));
}