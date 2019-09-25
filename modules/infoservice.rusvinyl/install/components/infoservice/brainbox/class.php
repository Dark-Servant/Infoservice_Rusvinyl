<?
use Bitrix\Main\Config\Option;

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class BraingBox extends \CBitrixComponent
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
            $this->arResult['currentUserId'] = $USER->GetId();
            $this->arResult['isAdmin'] = $USER->isAdmin();
            $fileId = Option::get(INFS_RUSVINYL_MODULE_ID, INFS_RUSVINYL_OPTION_BRAINBOX_IMAGE);

            if ($fileId) {
                $fileData = CFile::GetFileArray($fileId);
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $fileData['SRC']))
                    $this->arResult['MAIN_IMAGE'] = $fileData;
            }

            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};