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

Loader::includeModule('iblock');

$optionUnits = Infoservice\RusVinyl\Helpers\Options::getParams();

try {
    if (!$currentUserId)
        throw new Exception(Loc::getMessage('ERROR_AUTH'));

    $request = Application::getInstance()->getContext()->getRequest();
    $iblockCode = $request->get('code');
    if (empty($iblockCode))
        throw new Exception(Loc::getMessage('ERROR_EMPTY_AJAX_IBLOCK_CODE'));

    // У каждого сервиса должен быть свой ajax-обработчик в своем файле
    $ajaxFile = __DIR__ . '/ajax/' . $iblockCode . '.php';
    if (!file_exists($ajaxFile))
        throw new Exception(Loc::getMessage('ERROR_AJAX_FILE_NOT_EXISTS', ['CODE' => $iblockCode]));
    
    require $ajaxFile;
    /**
        В файле с обработчиком для конкретного сервиса должен лежать класс
        или он уже должен быть объявлен.        
        Класс находится в namespace c именем
        <Название партнера>\<Название модуля>\Ajax\<Название класса сервиса>.
        <Название партнера>\<Название модуля> будет получено из кода модуля
        <Название класса сервиса> будет получено из символьного кода инфоблока сервиса
     */
    $className = implode(
                    '\\', array_map(
                        function($part) {
                            return strtoupper($part[0]) . substr($part, 1);
                        },
                        preg_split('/[^a-z\d]+/', INFS_RUSVINYL_MODULE_ID)
                    )
                 )
               . '\\Ajax\\' . preg_replace_callback(
                    '/(?:^[^a-z]*|[^a-z\d]+)([a-z\d])/i',
                    function($part) {
                        return strtoupper($part[1]);
                    }, $iblockCode
                );
    /**
        Для каждого обработчика запроса в классе должен быть объявлен
        обработчик с таким же названием
     */
    $action = trim($request->get('action'));
    if ($action && method_exists($className, $action)) {
        $answer['data'] = (
                new $className(
                        $request, $optionUnits, $answer,
                        $currentUserId, $currentTime
                    )
            )->$action();

    } else {
        throw new Exception(Loc::getMessage('ERROR_BAD_ACTION'));
    }

} catch (Exception $error) {
    $answer = array_merge($answer, ['result' => false, 'message' => $error->GetMessage()]);
}

header('Content-Type: application/json');
die(json_encode($answer));