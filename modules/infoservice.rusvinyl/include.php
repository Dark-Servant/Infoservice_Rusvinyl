<?
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
define('INFS_RUSVINYL_IBLOCK_PREFIX', 'rusv_');
define('INFS_RUSVINYL_IBLOCK_ANNOUNCEMENT', INFS_RUSVINYL_IBLOCK_PREFIX . 'announ');
define('INFS_RUSVINYL_IBLOCK_NEWS', INFS_RUSVINYL_IBLOCK_PREFIX . 'news');
define('INFS_RUSVINYL_IBLOCK_POLL', INFS_RUSVINYL_IBLOCK_PREFIX . 'poll');
define('INFS_RUSVINYL_IBLOCK_LEADER', INFS_RUSVINYL_IBLOCK_PREFIX . 'leader');
define('INFS_RUSVINYL_IBLOCK_MASTERBLOG', INFS_RUSVINYL_IBLOCK_PREFIX . 'masterblog');
define('INFS_RUSVINYL_MAIN_PAGE_IBLOCKS', [
    INFS_RUSVINYL_IBLOCK_NEWS,
    INFS_RUSVINYL_IBLOCK_ANNOUNCEMENT,
    INFS_RUSVINYL_IBLOCK_POLL,
    INFS_RUSVINYL_IBLOCK_LEADER
]);
define('INFS_RUSVINYL_MAIN_PAGE_UNIT_MAX_COUNT', 3);
define('INFS_RUSVINYL_MAIN_PAGE_ROW_MAX_COUNT', 2);

define('INFS_IBLOCK_NEWS_ELEMENT1', 'NEWS_ELEMENT1');
define('INFS_IBLOCK_NEWS_ELEMENT2', 'NEWS_ELEMENT2');
define('INFS_IBLOCK_NEWS_ELEMENT3', 'NEWS_ELEMENT3');