<?
use Bitrix\Main\{Localization\Loc, Loader};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class IBlockDetail extends \CBitrixComponent
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
                !intval($this->arParams['ELEMENT_ID'])
                || !($element = CIBlockElement::GetById($this->arParams['ELEMENT_ID'])->Fetch())
            ) throw new Exception(Loc::getMessage('ERROR_EMPTY_ELEMENT_ID'));
            
            $this->arResult['ELEMENT'] = $element;
            $this->arResult['OPTIONS'] = Infoservice\RusVinyl\Helpers\Options::getParams();

            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};



