<?
use Bitrix\Main\{Localization\Loc, Loader};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class IBlockDetail extends \CBitrixComponent
{

    /**
     * Проверяет указан ли параметр для показа видео вместо изображения
     * на детальной странице. Проводит инициализацию, если указан и
     * данные параметра по видео действительны
     * 
     * @return void
     */
    protected function checkVideoShowing()
    {
        if (!$this->arParams['DETAIL_PROPERTY_VIDEO']) return;

        $fieldName = $this->arParams['DETAIL_PROPERTY_VIDEO'];
        if (empty($this->arResult['ELEMENT']['PROPERTIES'][$fieldName]['VALUE']))
            return;

        $videoData = $this->arResult['ELEMENT']['PROPERTIES'][$fieldName]['VALUE'];
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $videoData['path'])) return;                

        $this->arResult['SHOW_VIDEO'] = $videoData;
    }

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
                || !(
                    $element = CIBlockElement::GetList(
                                    [], ['ID' => $this->arParams['ELEMENT_ID']]
                                )->GetNextElement()
                )
            ) throw new Exception(Loc::getMessage('ERROR_EMPTY_ELEMENT_ID'));
            
            $this->arResult['ELEMENT'] = $element->fields
                                       + ['PROPERTIES' => $element->GetProperties()];
            $this->arResult['OPTIONS'] = Infoservice\RusVinyl\Helpers\Options::getParams();
            $this->checkVideoShowing();

            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};