<?
use \Bitrix\Main\{
    Application,
    Localization\Loc,
    Config\Option,
    Type\DateTime
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
    switch ($action) {
        case 'new':
            if (!$USER->isAdmin())
                throw new Exception(Loc::getMessage('ERROR_ADMIN_RIGHTS'));

            $lastTime = (new DateTime(date('Y-m-d'), 'Y-m-d'))->getTimestamp()
                      - intval($request->getPost('count')) * 86400;

            Option::set(INFS_RUSVINYL_MODULE_ID, INFS_RUSVINYL_OPTION_INCIDENT_NAME, $lastTime);
            $answer['data'] = $lastTime;
            break;

        default:
            throw new Exception(Loc::getMessage('ERROR_BAD_ACTION'));
    }
} catch (Exception $error) {
    $answer = array_merge($answer, ['result' => false, 'message' => $error->GetMessage()]);
}

header('Content-Type: application/json');
die(json_encode($answer));