<?
use Bitrix\Main\{Localization\Loc, Loader};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class EntityFrames extends \CBitrixComponent
{
    const FRAMES_ELEMENT_COUNT = 5;

    /**
     * Генератор, возвращает по элементу из инфоблоков
     * 
     * @param $codes - символьные коды инфоблоков
     */
    public function getIBLOCKData(array $codes)
    {
        Loader::includeModule('iblock');

        $iblocks = CIBlock::GetList([], ['CODE' => $codes]);
        while ($iblock = $iblocks->Fetch()) {
            $iblockResult = [
                'ID' => $iblock['ID'],
                'NAME' => $iblock['NAME'],
                'LIST_PAGE_URL' => $iblock['LIST_PAGE_URL'],
                'ITEMS' => [],
            ];
            $elements = CIBlockElement::GetList(
                            ['ID' => 'DESC'], [
                                'IBLOCK_ID' => $iblock['ID'],
                                'ACTIVE' => 'Y'
                            ], false, [
                                'nPageSize' => $this->arResult['FRAMES_ELEMENT_COUNT'],
                                'iNumPage' => 0
                            ]
                        );
            while ($element = $elements->Fetch()) {
                $item = [
                    'ID' => $element['ID'],
                    'NAME' => $element['NAME'],
                    'PREVIEW_PICTURE' => $element['DETAIL_PICTURE'] ?: $element['PREVIEW_PICTURE'],
                    'DETAIL_PAGE_URL' => str_replace('#ID#', $element['ID'], $element['DETAIL_PAGE_URL']),
                    'PREVIEW_TEXT' => $element['DETAIL_TEXT'],
                ];

                if (!empty($item['PREVIEW_PICTURE'])) {
                    $item['PREVIEW_PICTURE'] = [
                        'SRC' => CFIle::GetPath($item['PREVIEW_PICTURE']),
                        'TITLE' => $item['NAME']
                    ];
                }
                $iblockResult['ITEMS'][] = $item;
            }
            if (!count($iblockResult['ITEMS'])) continue;

            yield [$iblock['CODE'] => $iblockResult];
        }
    }

    /**
     * Генератор, возвращает по элементу из группы опросов
     * 
     * @param $codes - символьные коды групп опросов
     */
    public function getVOTECHANNELData(array $codes)
    {
        Loader::includeModule('vote');

        $channels = CVoteChannel::GetList(
                        $field = 'ID', $dir = 'ASC',
                        [
                            'SYMBOLIC_NAME' => implode(' | ', $codes),
                            'ACTIVE' => 'Y'
                        ], $is_filtered
                    );
        while ($channel = $channels->Fetch()) {
            $votes = CVote::GetList(
                        $field = 'DATE_START', $dir = 'ASC',
                        [
                            'ACTIVE' => 'Y',
                            'CHANNEL_ID' => $channel['ID']
                        ], $is_filtered
                    );
            $channelResult = [
                'ID' => $channel['ID'],
                'NAME' => $channel['TITLE'],
                'LIST_PAGE_URL' => INFS_RUSVINYL_VOTE_LIST_URL[$channel['SYMBOLIC_NAME']],
                'ITEMS' => [],
            ];
            $maxCount = $this->arResult['FRAMES_ELEMENT_COUNT'];
            while ($vote = $votes->Fetch()) {
                $item = [
                    'ID' => $vote['ID'],
                    'NAME' => $vote['TITLE'],
                    'PREVIEW_PICTURE' => '',
                    'DETAIL_PAGE_URL' => $channelResult['LIST_PAGE_URL'] . $vote['ID'] . '/',
                    'PREVIEW_TEXT' => $vote['DESCRIPTION'],
                ];

                if (!empty($vote['IMAGE_ID'])) {
                    $item['PREVIEW_PICTURE'] = [
                        'SRC' => CFIle::GetPath($vote['IMAGE_ID']),
                        'TITLE' => $item['NAME']
                    ];
                }
                $channelResult['ITEMS'][] = $item;
                if (--$maxCount < 1) break;

            }
            if (!count($channelResult['ITEMS'])) continue;

            yield [$channel['SYMBOLIC_NAME'] => $channelResult];
        }

    }

    /**
     * Подготавливает данные по всем элементам любых сущностей
     * 
     * @return void
     */
    public function initFrames()
    {
        if (
            empty($this->arParams['FRAMES_CODES']) ||
            !is_array($this->arParams['FRAMES_CODES'])
        ) throw new Exception(Loc::getMessage('ERROR_EMPTY_FRAMES_CODES'));
        
        $frames = [];
        $codeGroups = [];
        foreach ($this->arParams['FRAMES_CODES'] as $code) {
            if (
                (count($code) < 2) || !strlen($codeGroup = trim($code[0]))
                || !strlen($codeValue = trim($code[1]))
            ) continue;

            $frames[] = [$codeGroup, $codeValue];
            $codeGroups[$codeGroup][$codeValue] = 0;
        }
        foreach ($codeGroups as $groupName => $codes) {
            $methodName = 'get' . strtoupper($groupName) . 'Data';


            if (!method_exists($this, $methodName)) continue;


            foreach ($this->$methodName(array_keys($codes)) as $unit) {

                $codeGroups[$groupName] = array_merge($codeGroups[$groupName], $unit);
            }
        }

        $maxRowCount = $this->arParams['FRAMES_ELEMENT_COUNT'] < 1
                     ? 0 : $this->arParams['FRAMES_ELEMENT_COUNT'];
        $rowNumber = $rowCount = 0;
        foreach ($frames as $frame) {
            if (empty($codeGroups[$frame[0]][$frame[1]])) continue;
            $this->arResult['ENTITY_ROWS'][$rowNumber][] = [$frame[0], $codeGroups[$frame[0]][$frame[1]]];

            if (++$rowCount >= $maxRowCount) {
                $rowCount = 0;
                ++$rowNumber;
            }
        }
    }

    /**
     * Выполняет логику работы компонента
     * 
     * @return void|null - ничего не возвращает
     */
    public function executeComponent()
    {
        try {
            // могут передать как строковый входной параметр, так и отрицательный числовой
            $this->arResult['FRAMES_ELEMENT_COUNT'] = $this->arParams['FRAMES_ELEMENT_COUNT'] > 0
                                                    ? floor($this->arParams['FRAMES_ELEMENT_COUNT'])
                                                    : self::FRAMES_ELEMENT_COUNT;
            $this->initFrames();
            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};



