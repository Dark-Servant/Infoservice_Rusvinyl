<?
use Bitrix\Main\{Localization\Loc, Loader, Config\Option};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class IBlockList extends \CBitrixComponent
{
    /**
     * Выполняет логику работы компонента
     * 
     * @return void|null - ничего не возвращает
     */
    public function executeComponent()
    {
        global $USER;

        try {
            Loader::includeModule('iblock');

            if (
                !trim(strval($this->arParams['ELEMENT_TYPE_CODE']))
                || !(
                    $iblock = CIBlock::GetList(
                        [], [
                            'CODE' => $this->arParams['ELEMENT_TYPE_CODE']
                        ]
                    )->Fetch()
                )
                || ($iblock['IBLOCK_TYPE_ID'] != INFS_RUSVINYL_IBLOCK_TYPE)
            ) throw new Exception(Loc::getMessage('ERROR_EMPTY_ELEMENT_TYPE_CODE'));

            $prefixLength = strlen(INFS_RUSVINYL_IBLOCK_PREFIX);
            $trueCode = strtolower($this->arParams['ELEMENT_TYPE_CODE']);
            if (
                (strlen($trueCode) <= $prefixLength)
                || (substr($trueCode, 0, $prefixLength) != INFS_RUSVINYL_IBLOCK_PREFIX)
            ) throw new Exception(Loc::getMessage('ERROR_BAD_IBLOCK_PREFIX'));

            $trueCode = substr($trueCode, $prefixLength);
            $this->arResult['MAINDATA'] = [
                'IBLOCK' => $iblock,
                'CODE' => $trueCode,
                'BUTTON_TITLE_CODE' => strtoupper($trueCode) . '_BUTTON_TITLE',
                'OPTIONS' => json_decode(
                                    Option::get(
                                        INFS_RUSVINYL_MODULE_ID,
                                        INFS_RUSVINYL_OPTION_NAME,
                                        false, SITE_ID
                                    ), true
                                )
            ];
            $this->arResult['CURRENT_USER_ID'] = $USER->GetId();

            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};



