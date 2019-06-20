<?
use Bitrix\Main\Localization\Loc;

// Основные константы
define('INFS_RUSVINYL_MODULE_ID', 'infoservice.rusvinyl');
define('INFS_RUSVINYL_OPTION_NAME', 'installed');

// Данные о версии модуля
require_once 'install/version.php';
foreach ($arModuleVersion as $key => $value) {
    define('INFS_RUSVINYL_' . $key, $value);
}