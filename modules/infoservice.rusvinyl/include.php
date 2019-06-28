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

define('INFS_RUSVINYL_HEADER_USER_LOGO_SCR', '/local/templates/rusvinyl/images/man.svg');
define('INFS_CURRENT_TIMESTAMP', time());
define('INFS_INCIDENT_STATIC_EXAMPLE_VALUE', '7 893');

define('INFS_RUSVINYL_IBLOCK_TYPE', 'rusvinyl');
define('INFS_RUSVINYL_IBLOCK_ANNOUNCEMENT', 'RUSV_ANNOUN');
define('INFS_RUSVINYL_IBLOCK_NEWS', 'RUSV_NEWS');
define('INFS_RUSVINYL_IBLOCK_POLL', 'RUSV_POLL');
define('INFS_RUSVINYL_IBLOCK_LEADER', 'RUSV_LEADER');

define('INFS_IBLOCK_NEWS_ELEMENT1', 'NEWS_ELEMENT1');
define('INFS_IBLOCK_NEWS_ELEMENT2', 'NEWS_ELEMENT2');
define('INFS_IBLOCK_NEWS_ELEMENT3', 'NEWS_ELEMENT3');