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

Loader::includeModule('iblock');

$optionUnits = json_decode(Option::get(INFS_RUSVINYL_MODULE_ID, INFS_RUSVINYL_OPTION_NAME, false, SITE_ID), true);

try {
    if (!$currentUserId)
        throw new Exception(Loc::getMessage('ERROR_AUTH'));

    $request = Application::getInstance()->getContext()->getRequest();
    $directionCode = $request->get('code');
    if (empty($directionCode))
        throw new Exception(Loc::getMessage('ERROR_EMPTY_AJAX_DIRECTION_CODE'));

    $codeHandleFile = __DIR__ . '/ajax.' . $directionCode . '.php';
    if (!file_exists($codeHandleFile))
        throw new Exception(Loc::getMessage('ERROR_AJAX_DIRECTION_FILE', ['CODE' => $directionCode]));
        
    $action = $request->get('action');
    require $codeHandleFile;

} catch (Exception $error) {
    $answer = array_merge($answer, ['result' => false, 'message' => $error->GetMessage()]);
}

header('Content-Type: application/json');
die(json_encode($answer));