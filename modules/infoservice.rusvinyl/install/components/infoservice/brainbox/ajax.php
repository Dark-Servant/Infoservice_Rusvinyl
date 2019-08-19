<?
use \Bitrix\Main\{
    Application,
    Localization\Loc,
    Loader,
    Config\Option
};

define("NOT_CHECK_PERMISSIONS", true);
define("NEED_AUTH", false);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/bx_root.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$answer = ['result' => true];
$currentUserId = $USER->GetId();
$currentTime = time();
set_time_limit(0);

try {
    if (!$currentUserId)
        throw new Exception(Loc::getMessage('ERROR_AUTH'));

    $request = Application::getInstance()->getContext()->getRequest();
    $action = $request->get('action');

    switch ($action) {
        case 'new':
            if (!$USER->isAdmin())
                throw new Exception(Loc::getMessage('ERROR_ADMIN_RIGHTS'));
            
            $fileId = CFile::SaveFile($request->getFile('image'), INFS_RUSVINYL_MODULE_ID . '/' . INFS_RUSVINYL_OPTION_BRAINBOX_IMAGE);
            Option::set(INFS_RUSVINYL_MODULE_ID, INFS_RUSVINYL_OPTION_BRAINBOX_IMAGE, $fileId, SITE_ID);
            $answer['data'] = CFile::GetPath($fileId);
            break;

        default:
            throw new Exception(Loc::getMessage('ERROR_BAD_ACTION'));
    }

} catch (Exception $error) {
    $answer = array_merge($answer, ['result' => false, 'message' => $error->GetMessage()]);
}

header('Content-Type: application/json');
die(json_encode($answer));