<?
use \Bitrix\Main\{
    Application,
    Localization\Loc,
    Loader,
    Type\DateTime
};
use Bitrix\Highloadblock\HighloadBlockTable as HLT;

define("NOT_CHECK_PERMISSIONS", true);
define("NEED_AUTH", false);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/bx_root.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$answer = ['result' => true];
$currentUserId = $USER->GetId();
$currentTime = time();
set_time_limit(0);

Loader::includeModule('iblock');
Loader::includeModule('highloadblock');

$optionUnits = Infoservice\RusVinyl\Helpers\Options::getParams();

try {
    if (!$currentUserId)
        throw new Exception(Loc::getMessage('ERROR_AUTH'));

    $request = Application::getInstance()->getContext()->getRequest();
    $elementId = intval($request->getPost('elementId'));
    if (
        !$elementId || !($element = CIBlockElement::GetById($elementId)->Fetch())
        || ($element['IBLOCK_ID'] != $optionUnits['IBlocks'][INFS_RUSVINYL_IBLOCK_PARTICIPATE])
    ) throw new Exception(Loc::getMessage('ERROR_BAD_ELEMENT_ID'));

    $action = $request->get('action');
    switch ($action) {
        case 'setparticipation':
            $hlblock = HLT::getById($optionUnits['HighloadBlock'][INFS_HL_PARTICIPATE_USERS])->fetch();
            $hlblock = HLT::compileEntity($hlblock)->getDataClass();

            $filter = [
                INFS_HL_PARTICIPATE_USER_FIELD => $currentUserId,
                INFS_HL_PARTICIPATE_ELEMENT_FIELD => $elementId
            ];
            $status = $hlblock::GetList(['filter' => $filter])->Fetch();
            if (!$status)
                $hlblock::Add($filter + [INFS_HL_PARTICIPATE_DATE_FIELD => new DateTime()]);

            $answer['message'] = Loc::getMessage('YOU_ARE_PARTICIPANT');
            break;

        default:
            throw new Exception(Loc::getMessage('ERROR_BAD_ACTION'));
    }

} catch (Exception $error) {
    $answer = array_merge($answer, ['result' => false, 'message' => $error->GetMessage()]);
}

header('Content-Type: application/json');
die(json_encode($answer));