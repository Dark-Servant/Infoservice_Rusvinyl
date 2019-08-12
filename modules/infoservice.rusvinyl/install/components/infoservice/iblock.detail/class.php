<?
use Bitrix\Main\{Localization\Loc, Loader};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class IBlockDetail extends \CBitrixComponent
{

    /**
     * Проверяет параметр для видео, возвращает его данные
     * или null если параметр не указан
     * 
     * @return array|null
     */
    protected function getVideoParam()
    {
        $fieldName = $this->arParams['DETAIL_PROPERTY_VIDEO'];
        if (!$fieldName) return;

        return $this->arResult['ELEMENT']['PROPERTIES'][$fieldName]['VALUE'] ?? null;
    }

    /**
     * Проверяет указан ли параметр для видео, существует ли для него
     * файл. Возвращает его значение, если все условия выполнены
     * 
     * @return array|null
     */
    protected function getVideoData()
    {
        $videoData = $this->getVideoParam();
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $videoData['path'])) return;

        return $videoData;
    }

    /**
     * Проверяет указан ли параметр для показа видео вместо изображения
     * на детальной странице. Проводит инициализацию, если указан и
     * данные параметра по видео действительны
     * 
     * @return void
     */
    protected function checkVideoShowing()
    {
        $videoData = $this->getVideoData();
        if (!$videoData) return;

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