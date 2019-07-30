<?
// Основные константы
define('INFS_RUSVINYL_MODULE_ID', 'infoservice.rusvinyl');
define('INFS_RUSVINYL_OPTION_NAME', 'installed');

// Данные о версии модуля
require_once 'install/version.php';
foreach ($arModuleVersion as $key => $value) {
    define('INFS_RUSVINYL_' . $key, $value);
}

// Данные инфоблоков и их типов

//  Символьный код типа инфоболоков "РусВинил"
define('INFS_RUSVINYL_IBLOCK_TYPE', 'rusvinyl');

define('INFS_RUSVINYL_IBLOCK_PREFIX', 'rusv_');
// Символьный код инфоблока "Новости"
define('INFS_RUSVINYL_IBLOCK_ANNOUNCEMENT', INFS_RUSVINYL_IBLOCK_PREFIX . 'announ');
// Символьный код инфоблока "Анонсы"
define('INFS_RUSVINYL_IBLOCK_NEWS', INFS_RUSVINYL_IBLOCK_PREFIX . 'news');
// Символьный код инфоблока "Опросы"
define('INFS_RUSVINYL_IBLOCK_POLL', INFS_RUSVINYL_IBLOCK_PREFIX . 'poll');
// Символьный код инфоблока "Лидер месяца"
define('INFS_RUSVINYL_IBLOCK_LEADER', INFS_RUSVINYL_IBLOCK_PREFIX . 'leader');
// Символьный код инфоблока "Влог ген. директора"
define('INFS_RUSVINYL_IBLOCK_MASTERBLOG', INFS_RUSVINYL_IBLOCK_PREFIX . 'masterblog');
// Символьный код инфоблока "Сказать "Спасибо"
define('INFS_RUSVINYL_IBLOCK_THANKS', INFS_RUSVINYL_IBLOCK_PREFIX . 'thanks');
// Символьный код инфоблока "Задать вопрос"
define('INFS_RUSVINYL_IBLOCK_QUESTION', INFS_RUSVINYL_IBLOCK_PREFIX . 'question');

define('INFS_RUSVINYL_MAIN_PAGE_IBLOCKS', [
    INFS_RUSVINYL_IBLOCK_NEWS,
    INFS_RUSVINYL_IBLOCK_ANNOUNCEMENT,
    INFS_RUSVINYL_IBLOCK_POLL,
    INFS_RUSVINYL_IBLOCK_LEADER
]);

// Максимальное количество записей из инфоблока в блоках на главной странице
define('INFS_RUSVINYL_MAIN_PAGE_UNIT_MAX_COUNT', 3);

// Максимальное количество в строке блоков записей из инфоблока на главной странице
define('INFS_RUSVINYL_MAIN_PAGE_ROW_MAX_COUNT', 2);

/**
 * Максимальное количество элементов на одной странице при выводе их на общей
 * странице просмотра списка новостей
 */
define('INFS_RUSVINYL_NEW_LIST_FIRST_COUNT', 3);

// Элементы инфоблоков
define('INFS_IBLOCK_NEWS_ELEMENT1', 'NEWS_ELEMENT1');
define('INFS_IBLOCK_NEWS_ELEMENT2', 'NEWS_ELEMENT2');
define('INFS_IBLOCK_NEWS_ELEMENT3', 'NEWS_ELEMENT3');

// Свойства инфоблоков

// Свойства инфоблока "Сказать "Спасибо"
// "Кого поздравляют"
define('INFS_IB_THANKS_PR_RECIPIENT', 'RECIPIENT');
// Все свойства инфоблока "Сказать "Спасибо"
define('INFS_IB_THANKS_ALL_PROPERTIES', [INFS_IB_THANKS_PR_RECIPIENT]);
// Количество элементов на странице из инфоблока "Сказать "Спасибо"
define('INFS_IB_THANKS_PAGE_SIZE', 5);
/**
 * Максимальное количество символов для описания элементов
 * из инфоблока "Сказать "Спасибо"
 */
define('INFS_IB_THANKS_TEXT_LENGHT', 150);

// Свойства инфоблока "Задать вопрос"
// "Ответ"
define('INFS_IB_QUESTION_PR_ANSWER_VALUE', 'ANSWER_VALUE');
// "Автор ответа"
define('INFS_IB_QUESTION_PR_ANSWER_AUTHOR', 'ANSWER_AUTHOR');
// "Тема вопроса"
define('INFS_IB_QUESTION_PR_THEME', 'THEME');
// "Текст вопроса"
define('INFS_IB_QUESTION_PR_QUESTION_VALUE', 'QUESTION_VALUE');
// Все свойства инфоблока "Задать вопрос"
define('INFS_IB_QUESTION_ALL_PROPERTIES', [
    INFS_IB_QUESTION_PR_ANSWER_VALUE,
    INFS_IB_QUESTION_PR_ANSWER_AUTHOR,
    INFS_IB_QUESTION_PR_THEME,
    INFS_IB_QUESTION_PR_QUESTION_VALUE,
]);
// Количество элементов на странице из инфоблока "Задать вопрос"
define('INFS_IB_QUESTION_PAGE_SIZE', 5);
/**
 * Максимальное количество символов для описания элементов
 * из инфоблока "Задать вопрос"
 */
define('INFS_IB_QUESTION_TEXT_LENGHT', 150);

// Данные форумов

define('INFS_FORUM_PREFIX', 'RUSVINYL_');
// Форум для комментариев на детальной странице просмотра
define('INFS_DETAIL_PAGE_FORUM', 'DETAIL_PAGE');


// Данные опросов

// символьный код группы опросов "Русвинила"
define('INFS_RUSVINYL_SIMPLE_VOTE_CODE', 'RUSVINYL_SIMPLE_VOTE');
// символьный код группы конкурсов "Русвинила"
define('INFS_RUSVINYL_COMPETITION_CODE', 'RUSVINYL_COMPETITION_VOTE');

// Максимальное количество символов для описания любых опросов
define('INFS_RUSVINYL_VOTE_TEXT_LENGHT', 150);
// Количество элементов на странице для обычных опросов
define('INFS_RUSVINYL_SIMPLE_VOTE_GROUP_SIZE', 5);
// Количество элементов на странице для конкурсов
define('INFS_RUSVINYL_COMPETITION_GROUP_SIZE', 5);

// Ссылки на страницы со списками опросов
define('INFS_RUSVINYL_VOTE_LIST_URL', [
    INFS_RUSVINYL_SIMPLE_VOTE_CODE => '/pulse/poll/',
    INFS_RUSVINYL_COMPETITION_CODE => '/competition/',
]);

// Вспомогательные константы
define('INFS_RUSVINYL_HEADER_USER_LOGO_SCR', '/local/templates/rusvinyl/images/man.svg');
define('INFS_CURRENT_TIMESTAMP', time());
define('INFS_INCIDENT_STATIC_EXAMPLE_VALUE', '7 893');
define('INFS_USER_LINK', '/user/#ID#/');