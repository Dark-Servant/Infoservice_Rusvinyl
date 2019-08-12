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
// Символьный код инфоблока "Объявления сотрудников"
define('INFS_RUSVINYL_IBLOCK_EMPLOYEE_ANNOUNCE', INFS_RUSVINYL_IBLOCK_PREFIX . 'employee_announce');
// Символьный код инфоблока "Участвовать"
define('INFS_RUSVINYL_IBLOCK_PARTICIPATE', INFS_RUSVINYL_IBLOCK_PREFIX . 'participate');

// Символьные коды для инфоблоков в "Сервисах"
// Символьный код инфоблока "Заявка в ServiceDesk"
define('INFS_IBLOCK_SERVICEDESK', INFS_RUSVINYL_IBLOCK_PREFIX . 'servicedesk');
// Символьный код инфоблока "Справка 2 НДФЛ"
define('INFS_IBLOCK_2_PIT', INFS_RUSVINYL_IBLOCK_PREFIX . '2_pit');
// Символьный код инфоблока "Копия трудовой книжки"
define('INFS_IBLOCK_SERVICERECORD', INFS_RUSVINYL_IBLOCK_PREFIX . 'servicerecord');
// Символьный код инфоблока "Справка на визу"
define('INFS_IBLOCK_VISA', INFS_RUSVINYL_IBLOCK_PREFIX . 'visa');
// Символьный код инфоблока "Справка с места работы"
define('INFS_IBLOCK_REFERENCE', INFS_RUSVINYL_IBLOCK_PREFIX . 'reference');
// Список всех символьных кодов тех инфоблоков, которые определены для списка "Сервисы"
define('INFS_IBLOCK_ALL_SERVICE_LIST', [
    INFS_IBLOCK_SERVICEDESK,
    INFS_IBLOCK_2_PIT,
    INFS_IBLOCK_SERVICERECORD,
    INFS_IBLOCK_VISA,
    INFS_IBLOCK_REFERENCE
]);

// Символьный код инфоблока "Видео архив"
define('INFS_RUSVINYL_IBLOCK_VIDEO', INFS_RUSVINYL_IBLOCK_PREFIX . 'video');

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

// Свойства инфоблока "Объявления сотрудников"
// "Тема"
define('INFS_IB_EMPLOYEE_ANNOUNCE_PR_THEME', 'THEME');
// Все свойства инфоблока "Объявления сотрудников"
define('INFS_IB_EMPLOYEE_ANNOUNCE_ALL_PROPERTIES', [INFS_IB_EMPLOYEE_ANNOUNCE_PR_THEME]);
// Количество элементов на странице из инфоблока "Объявления сотрудников"
define('INFS_IB_EMPLOYEE_ANNOUNCE_PAGE_SIZE', 5);
/**
 * Максимальное количество символов для описания элементов
 * из инфоблока "Объявления сотрудников"
 */
define('INFS_IB_EMPLOYEE_ANNOUNCE_TEXT_LENGHT', 150);

// Свойства инфоблока "Участвовать"
// "ID бизнес процесса при нажатии кнопки "Участвовать"
define('INFS_IB_PARTICIPATE_PR_SEND_DESIRE', 'BP_SEND_DESIRE');
// "ID бизнес процесса на ответ пользователю"
define('INFS_IB_PARTICIPATE_PR_SEND_ANSWER', 'BP_SEND_ANSWER');

// Свойства инфоблока "Справка 2 НДФЛ"
// "Сотрудник"
define('INFS_IB_2_PIT_PR_EMPLOYEE', 'EMPLOYEE');
// "От"
define('INFS_IB_2_PIT_PR_FROM', 'FROM');
// "До"
define('INFS_IB_2_PIT_PR_TO', 'TO');

// Свойства инфоблока "Копия трудовой книжки"
// "Количество копий"
define('INFS_IB_SERVICERECORD_PR_COUNT', 'COUNT');

// Свойства инфоблока "Справка на визу"
// "Страна"
define('INFS_IB_VISA_PR_COUNTRY', 'COUNTRY');
// "Дата первой поездки"
define('INFS_IB_VISA_PR_DATE', 'DATE');
// "Цель"
define('INFS_IB_VISA_PR_PURPOISE', 'PURPOISE');
// "Данные паспорта"
define('INFS_IB_VISA_PR_PASSPORT', 'PASSPORT');
// "Язык"
define('INFS_IB_VISA_PR_LANGUAGE', 'LANGUAGE');

// Свойства инфоблока "Справка с места работы"
// "С указанием оклада"
define('INFS_IB_REFERENCE_PR_SALARY', 'SALARY');

// Свойства инфоблока "Влог ген. директора"
// "Видео"
define('INFS_IB_MASTERBLOG_PR_VIDEO', 'VIDEO');

// Свойства инфоблока "Видео архив"
// "Видео"
define('INFS_IB_VIDEO_PR_VIDEO', 'VIDEO');

// Константы для Highload-блоков

// Highload-блок для хранения участников к элементам инфоблока "Участвовать"
define('INFS_HL_PARTICIPATE_USERS', 'PARTICIPATE_USERS');
/**
 * Поле "Участник" для HighloadBlock, где хранятся участники для
 * элементов инфоблока "Участвовать"
 */
define('INFS_HL_PARTICIPATE_USER_FIELD', 'UF_USER');
/**
 * Поле "В чем участвовать" для HighloadBlock, где хранятся участники для
 * элементов инфоблока "Участвовать"
 */
define('INFS_HL_PARTICIPATE_ELEMENT_FIELD', 'UF_ELEMENT');
/**
 * Поле "Дата подачи" для HighloadBlock, где хранятся участники для
 * элементов инфоблока "Участвовать"
 */
define('INFS_HL_PARTICIPATE_DATE_FIELD', 'UF_DATE');
/**
 * Поле "Подтверждение" для HighloadBlock, где хранятся участники для
 * элементов инфоблока "Участвовать"
 */
define('INFS_HL_PARTICIPATE_CONFIRMATION_FIELD', 'UF_CONFIRMATION');


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


// Типы календарей
define('INFS_CALENDAR_TYPE_PREFIX', strtoupper(preg_replace('/\W+/', '_', INFS_RUSVINYL_MODULE_ID)));
// тип календаря "Ближайшие события "Русвинила"
define('INFS_CALENDAR_TYPE_NEAR_EVENT', INFS_CALENDAR_TYPE_PREFIX . '_NEAR_EVENT');
// тип календаря "Календарь учебных мероприятий "Русвинила"
define('INFS_CALENDAR_TYPE_TRAINING_EVENT', INFS_CALENDAR_TYPE_PREFIX . '_TRAINING_EVENT');


// Другие константы

// какие элементы выводятся на главной странице
define('INFS_RUSVINYL_MAIN_PAGE_FRAMES', [
    ['iblock', INFS_RUSVINYL_IBLOCK_NEWS],
    ['iblock', INFS_RUSVINYL_IBLOCK_ANNOUNCEMENT],
    ['votechannel', INFS_RUSVINYL_SIMPLE_VOTE_CODE],
    ['iblock', INFS_RUSVINYL_IBLOCK_LEADER]
]);

// Максимальное количество записей в блоках на главной странице
define('INFS_RUSVINYL_MAIN_PAGE_UNIT_MAX_COUNT', 3);

// Максимальное количество в строке блоков записей на главной странице
define('INFS_RUSVINYL_MAIN_PAGE_ROW_MAX_COUNT', 2);

// Вспомогательные константы
define('INFS_RUSVINYL_HEADER_USER_LOGO_SCR', '/local/templates/rusvinyl/images/man.svg');
define('INFS_CURRENT_TIMESTAMP', time());
define('INFS_INCIDENT_STATIC_EXAMPLE_VALUE', '7 893');
define('INFS_USER_LINK', '/user/#ID#/');

// Ссылки на сторонние сайты
define('INFS_IDEAS_FUND_IFRAME_URL', 'https://app.powerbi.com/view?r=eyJrIjoiNzE3'
                                   . 'MmE2NzAtYmY5Ny00N2NhLTgzNjMtYTRiNzIyOWNiMGI'
                                   . '4IiwidCI6Ijc0MzYzODlkLWFlNGEtNDE0My1hMzZmLT'
                                   . 'dhZTJhNjJlMmM4YyIsImMiOjl9');
define('INFS_STATISTICS_IFRAME_URL', 'https://app.powerbi.com/view?r=eyJrIjoiODIx'
                                   . 'Njc4YmYtNTVlZS00OTU1LWIxMGUtZmQ2YmFjMzdiMzh'
                                   . 'mIiwidCI6Ijc0MzYzODlkLWFlNGEtNDE0My1hMzZmLT'
                                   . 'dhZTJhNjJlMmM4YyIsImMiOjl9');
