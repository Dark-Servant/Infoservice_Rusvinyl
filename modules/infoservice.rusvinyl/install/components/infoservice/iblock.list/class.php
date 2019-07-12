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
        try {
            Loader::includeModule('iblock');

            if (
                !trim(strval($this->arParams['ELEMENT_TYPE_CODE']))
                || !(
                    $iblock = CIBlock::GetList(
                        [], [
                            'CODE' => INFS_RUSVINYL_IBLOCK_PREFIX . $this->arParams['ELEMENT_TYPE_CODE']
                        ]
                    )->Fetch()
                )
            ) throw new Exception(Loc::getMessage('ERROR_EMPTY_ELEMENT_TYPE_CODE'));

            $this->arResult['MAINDATA'] = [
                'IBLOCK' => $iblock + [
                    'URL_CODE' => strtolower($this->arParams['ELEMENT_TYPE_CODE']),
                ],
                'BUTTON_TITLE_CODE' => strtoupper($this->arParams['ELEMENT_TYPE_CODE']) . '_BUTTON_TITLE',
                'OPTIONS' => json_decode(Option::get(INFS_RUSVINYL_MODULE_ID, INFS_RUSVINYL_OPTION_NAME, false, SITE_ID), true)
            ];

            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};



