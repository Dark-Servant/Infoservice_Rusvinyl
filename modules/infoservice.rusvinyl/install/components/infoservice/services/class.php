<?
use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class Services extends \CBitrixComponent
{
    /**
     * Выполняет логику работы компонента
     * 
     * @return void|null - ничего не возвращает
     */
    public function executeComponent()
    {
        global $USER;
        if (!$USER->GetId()) return;

        Loader::includeModule('iblock');
        try {
            $iblockCodes = [];
            $iblocks = CIBlock::GetList([], ['CODE' => INFS_IBLOCK_ALL_SERVICE_LIST]);
            while ($iblock = $iblocks->Fetch()) {
                $iblockCodes[$iblock['ID']] = $iblock['CODE'];
                $this->arResult['IBLOCK'][$iblock['CODE']] = $iblock;
            }
            foreach (INFS_IBLOCK_ALL_SERVICE_LIST as $iblocKCode) {
                $properties = CIBlockProperty::GetList(
                                ['ID' => 'DESC'],
                                [
                                    'IBLOCK_CODE' => $iblocKCode,
                                    'PROPERTY_TYPE' => 'L'
                                ]
                            );

                while ($property = $properties->Fetch()) {
                    $values = [];                    
                    $dbValues = CIBlockPropertyEnum::GetList(
                                            ['ID' => 'ASC'],
                                            ['PROPERTY_ID' => $property['ID']]
                                        );
                    while ($value = $dbValues->Fetch()) {
                        $values[] = $value;
                    }
                    $iblockCode = $iblockCodes[$property['IBLOCK_ID']];
                    $this->arResult['IBLOCK'][$iblockCode]['PROPERTIES'][$property['CODE']] = $values;
                }
            }

            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};