<?
use \Bitrix\Main\Localization\Loc;

// обработчик запросов для страницы "Задать вопрос"
switch ($action) {

    case 'new': // создание нового вопроса
        if (!$currentUserId)
            throw new Exception(Loc::getMessage('ERROR_AUTH'));
        
        $questionText = trim($request->getPost('new-question-text'));
        $fields = [
            'ACTIVE' => 'N',
            'NAME' => trim($request->getPost('new-question-name')),
            'IBLOCK_ID' => $optionUnits['IBlocks'][INFS_RUSVINYL_IBLOCK_QUESTION],
            'DETAIL_TEXT' => $questionText,
            'PROPERTY_VALUES' => [
                INFS_IB_QUESTION_PR_QUESTION_VALUE => $questionText,
                INFS_IB_QUESTION_PR_THEME => trim($request->getPost('new-question-theme'))
            ]
        ];
        if (!empty($fileData = $request->getFile('new-question-file')))
            $fields['DETAIL_PICTURE'] = $fileData;

        $questionIBElement = new CIBlockElement;
        if (!($questionId = $questionIBElement->Add($fields)))
          throw new Exception($questionIBElement->LAST_ERROR);

        $answer['data'] = $questionId;
        break;

    case 'list': // получение списка вопросов на указанной странице
        /**
         * TO DO: Далее код совпадает пусть и не на 100%, но сильно с кодом
         * для такого же обработчика для пожеланий, желательно как-то 
         * объединить в общее 
         */
        $currentPage = intval($request->get('page')) ?: 1;
        $filter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $optionUnits['IBlocks'][INFS_RUSVINYL_IBLOCK_QUESTION]
        ];
        $answer['data'] = [
            'list' => [],
            'pages' => [
                'current' => $currentPage,
                'count' => ceil(intval(CIBlockElement::GetList([], $filter, [])) / INFS_IB_QUESTION_PAGE_SIZE)
            ]
        ];
        $questions = CIBlockElement::GetList(
                            ['ID' => 'DESC'], $filter, false,
                            [
                                'nPageSize' => INFS_IB_QUESTION_PAGE_SIZE,
                                'iNumPage' => $currentPage
                            ],
                            array_merge(
                                ['ID', 'NAME', 'DETAIL_TEXT', 'DETAIL_PICTURE'],
                                array_map(
                                    function($value) {
                                        return 'PROPERTY_' . $value;
                                    }, INFS_IB_QUESTION_ALL_PROPERTIES
                                )
                            )
                        );

        while ($question = $questions->Fetch()) {
            $unit = array_filter(
                        $question,
                        function($key) {
                            return !preg_match('/^~|PROPERTY/i', $key) || preg_match('/^PROPERTY\w+VALUE$/i', $key);
                        }, ARRAY_FILTER_USE_KEY
                    )
                  + ['MORE_DETAIL_TEXT' => false];

            foreach (INFS_IB_QUESTION_ALL_PROPERTIES as $propertyCode) {
                $unitKeyCode = 'PROPERTY_' . $propertyCode . '_VALUE';
                if (
                    in_array(
                        $propertyCode, [
                            INFS_IB_QUESTION_PR_ANSWER_VALUE,
                            INFS_IB_QUESTION_PR_QUESTION_VALUE
                        ]
                    )
                ) {
                    $unit['PROPERTIES'][$propertyCode] = $unit[$unitKeyCode]['TEXT'] ?: '';

                } else {
                    $unit['PROPERTIES'][$propertyCode] = $unit[$unitKeyCode];
                }
                unset($unit[$unitKeyCode]);
            }
            if (strlen($unit['DETAIL_TEXT']) > INFS_IB_QUESTION_TEXT_LENGHT)
                $unit['MORE_DETAIL_TEXT'] = true;

            $unit['DETAIL_TEXT'] = nl2br(Infoservice\RusVinyl\Helpers\StringWorker::setMaxNewLine(strip_tags($unit['DETAIL_TEXT'])));

            if (!empty($unit['DETAIL_PICTURE'])) {
                $unit['DETAIL_PICTURE'] = CFile::GetPath($unit['DETAIL_PICTURE']);
                $unit['MORE_DETAIL_TEXT'] = true;
            }

            $answer['data']['list'][] = $unit;
        }
        break;

    default:
        throw new Exception(Loc::getMessage('ERROR_BAD_ACTION'));
}