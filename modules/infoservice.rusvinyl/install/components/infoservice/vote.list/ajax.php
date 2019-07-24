<?
use \Bitrix\Main\{
    Application,
    Localization\Loc,
    Loader
};

define("NOT_CHECK_PERMISSIONS", true);
define("NEED_AUTH", false);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/bx_root.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$answer = ['result' => true];
$currentUserId = $USER->GetId();
$currentTime = time();
set_time_limit(0);

Loader::includeModule('vote');

try {
    $request = Application::getInstance()->getContext()->getRequest();
    $action = $request->get('action');

    switch ($action) {

        case 'list': // получение списка опросов на указанной странице
            $channelId = intval($request->get('channelId'));
            if (
                !intval($channelId)
                || !CVoteChannel::GetById($channelId)->Fetch()
            ) throw new Exception(Loc::getMessage('ERROR_BAD_CHANNEL_ID'));
                
            $currentPage = intval($request->get('page')) ?: 1;
            $pageSize = intval($request->get('pageSize')) ?: 1;
            $answer['data'] = [
                'list' => [],
                'pages' => [
                    'current' => $currentPage,
                    'count' => 0
                ]
            ];
            $firstNumber = $pageSize * ($currentPage - 1);
            $lastNumber = $firstNumber + $pageSize;

            $currentNumber = 0;
            $votes = CVote::GetList(
                            $field = 'DATE_START', $dir = 'ASC',
                            [
                                'CHANNEL_ID' => $channelId,
                                'ACTIVE' => 'Y'
                            ], $is_filtered
                        );
            while ($vote = $votes->Fetch()) {
                if (
                    ($currentNumber >= $firstNumber)
                    && ($currentNumber < $lastNumber)
                ) {
                    $voteUnit = [
                        'ID' => $vote['ID'],
                        'NAME' => $vote['TITLE'],
                        'DETAIL_TEXT' => $vote['DESCRIPTION'],
                        'COUNTER' => $vote['COUNTER'],
                        'MORE_DETAIL_TEXT' => false
                    ];
                    $voteUnit['DETAIL_TEXT'] = nl2br(
                        Infoservice\RusVinyl\Helpers\StringWorker::setMaxNewLine(strip_tags($voteUnit['DETAIL_TEXT']))
                    );
                    if (strlen($voteUnit['DETAIL_TEXT']) > INFS_RUSVINYL_VOTE_TEXT_LENGHT)
                        $voteUnit['MORE_DETAIL_TEXT'] = true;

                    if (!empty($vote['IMAGE_ID'])) {
                        $voteUnit['DETAIL_PICTURE'] = CFile::GetPath($vote['IMAGE_ID']);
                        $voteUnit['MORE_DETAIL_TEXT'] = true;
                    }
                    $answer['data']['list'][] = $voteUnit;
                }
                ++$currentNumber;
            }
            $answer['data']['pages']['count'] = ceil($currentNumber / $pageSize);
            break;

        default:
            throw new Exception(Loc::getMessage('ERROR_BAD_ACTION'));
    }

} catch (Exception $error) {
    $answer = array_merge($answer, ['result' => false, 'message' => $error->GetMessage()]);
}

header('Content-Type: application/json');
die(json_encode($answer));