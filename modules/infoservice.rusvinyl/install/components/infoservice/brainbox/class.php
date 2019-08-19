<?
use Bitrix\Main\{Localization\Loc, Loader, Config\Option};

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
                $filePath = CFile::GetPath($fileId);
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath))
                    $this->arResult['MAIN_IMAGE'] = $filePath;
            }

            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};