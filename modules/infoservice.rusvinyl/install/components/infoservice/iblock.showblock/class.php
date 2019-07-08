<?
use Bitrix\Main\{Localization\Loc, Loader};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class IBlockShowblock extends \CBitrixComponent
{
    /**
     * Получает информацию об ID инфоблоков по переданным компоненту
     * символьным кодам
     * 
     * @return void
     */
    public function initIBlockIds()
    {
        if (
            empty($this->arParams['IBLOCK_CODES']) ||
            !is_array($this->arParams['IBLOCK_CODES'])
        ) throw new Exception(Loc::getMessage('ERROR_EMPTY_IBLOCK_CODES'));
        
        $iblockIds = array_fill_keys($this->arParams['IBLOCK_CODES'], 0);
        
        Loader::includeModule('iblock');
        $iblocks = CIBlock::GetList([], ['CODE' => $this->arParams['IBLOCK_CODES']]);
        while ($iblock = $iblocks->Fetch()) {
            $iblockIds[$iblock['CODE']] = $iblock['ID'];
        }

        $maxRowCount = $this->arParams['IBLOCK_ROW_COUNT'] < 1
                     ? 0 : $this->arParams['IBLOCK_ROW_COUNT'];
        $rowNumber = $rowCount = 0;
        foreach ($iblockIds as $iblockId) {
            if (CIBlockElement::GetList([], ['IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'])->Fetch())
                $this->arResult['IBLOCK_ROWS'][$rowNumber][] = $iblockId;

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
            $this->arResult['IBLOCK_ELEMENT_COUNT'] = $this->arParams['IBLOCK_ELEMENT_COUNT'] > 0
                                                    ? floor($this->arParams['IBLOCK_ELEMENT_COUNT'])
                                                    : IBLOCK_ELEMENT_COUNT;
            $this->initIBlockIds();
            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};



