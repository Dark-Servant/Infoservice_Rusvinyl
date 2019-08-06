<?php
use Bitrix\Main\{Localization\Loc, Loader, EventManager, Config\Option};
use Bitrix\Highloadblock\{HighloadBlockTable, HighloadBlockLangTable};
use Bitrix\Iblock\PropertyTable;
use Infoservice\RusVinyl\Helpers\Options;

class infoservice_rusvinyl extends CModule
{
    public $MODULE_ID = 'infoservice.rusvinyl';
    public $MODULE_NAME;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_DESCRIPTION;

    protected $nameSpaceValue;
    protected $defaultSiteID;
    protected $definedContants;

    const ADMIN_GROUP_ID = 1;
    const ALL_USER_GROUP_ID = 2;
    const SIMPLE_USER_GROUP_ID = 3;
    const USER_ID = 1;
    
    /**
     * Опции, которые необходимо добавить в проект, сгруппированные по названию методов, которые их
     * добавят. "Ключ" - центральная часть имени метода, который будет вызван для добавления/удаления
     * опции. Для того, чтобы была инициализация опций или их обработка перед удалением, необходимо
     * создать методы init<"Ключ">Options и remove<"Ключ">Options. В каждой группе опции
     * "ключ" - название константы, которая хранит название опции, эта константа должны быть бъявлена в
     * файле include.php, под "значением" описываются данные для ее инициализации. Данные опций будут
     * сохранены в опциях модуля в параметре с именем указанным в константе INFS_RUSVINYL_OPTION_NAME,
     * каждый в своей группе
     */
    const OPTIONS = [
        /**
         * Данные для тематики групп. "Значение" отвечает за константу языка.
         * В опциях модуля сохранятся в группе SocNetSubjects как массив, где ключ
         * значение константы, указанной как "ключ", а значение ID тематики группы
         */
        'SocNetSubjects' => [],

        /**
         * настройки для создания HighloadBlock. В значении массив с ключами
         *     LANG_CODE - название языковой константы, под значением которой будет находиться
         *     название HighloadBlock, чтобы не видеть в списке значение из NAME.
         * Параметр LANG_CODE необязателен, поэтому в настройках может быть посто пустой массив.
         * NAME и TABLE_NAME будут получены из значения константы, название которой указано как "ключ"
         * В опциях модуля сохранятся в группе HighloadBlock как массив, где ключ
         * значение константы, указанной как "ключ", а значение ID highloadblock
         */
        'HighloadBlock' => [
            // HighloadBlock для хранения изъявивших желание участвовать
            // в элементах инфоблока "Участвовать"
            'INFS_HL_PARTICIPATE_USERS' => [
                'LANG_CODE' => 'HL_PARTICIPATE_USERS_TITLE'
            ],
        ],

        /**
         * Настройки для создания групп опросов. В "значении" обязательно надо указать LANG_CODE
         * с именем языковой константы, в которой хранится название группы.
         * За SYMBOLIC_NAME будет браться значение константы, название которой указано в "ключе"
         * Остальные параметры, кроме TITLE, ACTIVE и SYMBOLIC_NAME, такие же, как и при обычном
         * создании группы опросов
         */
        'VoteChannels' => [
            'INFS_RUSVINYL_SIMPLE_VOTE_CODE' => [
                'LANG_CODE' => 'SIMPLE_VOTE_TITLE'
            ],
            'INFS_RUSVINYL_COMPETITION_CODE' => [
                'LANG_CODE' => 'COMPETITION_VOTE_TITLE'
            ]
        ],

        /**
         * Настройки для создания типов инфоблока. В "значении" указываются параметры для создания типа инфоблока.
         * Обязательно нужен параметр LANG_CODE с именем языковой константы для названия
         */
        'IBlockTypes' => [
            'INFS_RUSVINYL_IBLOCK_TYPE' => [
                'LANG_CODE' => 'IBLOCK_TYPE_TITLE'
            ]
        ],

        /**
         * Настройки для создания инфоблоков. В "значении" указываются параметры для создания инфоблоков. Обязательно
         * нужны параметры LANG_CODE с именем языковой константы для названия и IBLOCK_TYPE_ID с именем константы, в
         * которой хранится код типа инфоблока.
         * В параметре PERMISSIONS указываются права доступа к инфоблоку, где "ключ" - идентификатор пользовательской
         * группы, а "значение" - код права доступа. По-умолчанию, для администраторов  установлен "полный доступ",
         * а для всех пользователей - "чтение".
         * Права доступа:
         *     E - добавление элементов инфоблока в публичной части;
         *     S - просмотр элементов и разделов в административной части;
         *     T - добавление элементов инфоблока в административной части; 
         *     R - чтение; 
         *     U - редактирование через документооборот; 
         *     W - запись; 
         *     X - полный доступ (запись + назначение прав доступа на данный инфоблок).
         * Права доступа можно указывать и для групп, созданных модулем, просто указав в "ключе" строковый
         * идентификатор константы, используемый в UserGroup
         */
        'IBlocks' => [
            // инфоблок "Новости"
            'INFS_RUSVINYL_IBLOCK_NEWS' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_NEWS_TITLE',
                'DETAIL_PAGE_URL' => '/media/news/#ID#/',
                'LIST_PAGE_URL' => '/media/news/',
            ],
            // инфоблок "Анонсы"
            'INFS_RUSVINYL_IBLOCK_ANNOUNCEMENT' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_ANNOUNCEMENT_TITLE',
                'DETAIL_PAGE_URL' => '/announ/#ID#/',
                'LIST_PAGE_URL' => '/announ/',
            ],
            // инфоблок "Лидер месяца"
            'INFS_RUSVINYL_IBLOCK_LEADER' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_LEADER_TITLE',
                'DETAIL_PAGE_URL' => '/leader/#ID#/',
                'LIST_PAGE_URL' => '/leader/',
            ],
            // инфоблок "Влог ген. директора"
            'INFS_RUSVINYL_IBLOCK_MASTERBLOG' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_MASTERBLOG_TITLE',
                'DETAIL_PAGE_URL' => '/media/masterblog/#ID#/',
                'LIST_PAGE_URL' => '/media/masterblog/',
            ],
            // инфоблок "Сказать "Спасибо"
            'INFS_RUSVINYL_IBLOCK_THANKS' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_THANKS_TITLE',
                'LIST_PAGE_URL' => '/pulse/thanks/',
                'BIZPROC' => 'Y'
            ],
            // инфоблок "Задать вопрос"
            'INFS_RUSVINYL_IBLOCK_QUESTION' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_QUESTION_TITLE',
                'LIST_PAGE_URL' => '/question/',
                'BIZPROC' => 'Y'
            ],
            // инфоблок "Объявления сотрудников"
            'INFS_RUSVINYL_IBLOCK_EMPLOYEE_ANNOUNCE' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_EMPLOYEE_ANNOUNCE_TITLE',
                'LIST_PAGE_URL' => '/useful/announcements/',
                'BIZPROC' => 'Y'
            ],
            // инфоблок "Участвовать"
            'INFS_RUSVINYL_IBLOCK_PARTICIPATE' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_PARTICIPATE_TITLE',
                'DETAIL_PAGE_URL' => '/participate/#ID#/',
                'LIST_PAGE_URL' => '/participate/',
                'BIZPROC' => 'Y'
            ],

            // Инфоблоки для "Сервисов"
            // инфоблок "Заявка в ServiceDesk"
            'INFS_IBLOCK_SERVICEDESK' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_SERVICEDESK_TITLE',
                'LIST_PAGE_URL' => '/services/servicedesk/',
                'BIZPROC' => 'Y'
            ],
            // инфоблок "Справка 2 НДФЛ"
            'INFS_IBLOCK_2_PIT' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_2_PIT_TITLE',
                'LIST_PAGE_URL' => '/services/2-pit/',
                'BIZPROC' => 'Y'
            ],
            // инфоблок "Копия трудовой книжки"
            'INFS_IBLOCK_SERVICERECORD' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_SERVICERECORD_TITLE',
                'LIST_PAGE_URL' => '/services/servicerecord/',
                'BIZPROC' => 'Y'
            ],
            // инфоблок "Справка на визу"
            'INFS_IBLOCK_VISA' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_VISA_TITLE',
                'LIST_PAGE_URL' => '/services/visa/',
                'BIZPROC' => 'Y'
            ],
            // инфоблок "Справка с места работы"
            'INFS_IBLOCK_REFERENCE' => [
                'IBLOCK_TYPE_ID' => 'INFS_RUSVINYL_IBLOCK_TYPE',
                'LANG_CODE' => 'IBLOCK_REFERENCE_TITLE',
                'LIST_PAGE_URL' => '/services/reference/',
                'BIZPROC' => 'Y'
            ],
        ],

        /**
         * настройки для создания пользовательских полей у созданных модулем HighloadBlock.
         * В значении массив с ключами
         *     HBLOCK_ID - название константы, под значением которой сохранено в опциях модуля ID HighloadBlock
         *     в группе HighloadBlock
         *     DATA - настройки пользовательского поля. ENTITY_ID и FIELD_NAME не указывать. Значение FIELD_NAME
         *     должно быть объявлено в include.php как константа с именем, указанным в HighloadFields как "ключ".
         *     В DATA можно указать LANG_CODE, который используется для указания кода языковой опции, где
         *     хранится название пользовательского поля.
         *     Указывать тип надо не в USER_TYPE_ID, в TYPE, это более сокращено. Остальные настройки такие же,
         *     какие надо передавать в Битриксе.
         *     Если указан тип vote, то важно, чтобы было указано в ['SETTINGS']['CHANNEL_ID'] навазние "ключа", под которым
         *     в настройках для VoteChannels указаны настройки группы опросов.
         *     Если указан тип iblock_element, то важно, чтобы было указано в ['SETTINGS']['IBLOCK_ID'] навазние "ключа", под которым
         *     в настройках для IBlocks указаны настройки инфоблока.
         *     Если указан тип enumeration, то в параметрах можно указать параметр LIST_VALUES как массив, каждый
         *     элемент которого представляет отдельное значения для списка, для каждого значения списка обязательно
         *     должен быть указан LANG_CODE с именем языковой константы, в которой хранится название значения,
         *     указаные элементы списка с одинаковыми значения будут созданы один раз. При наличии LANG_CODE у
         *     пользовательского поля параметр LANG_CODE для значений списка надо писать в ином виде, так как
         *     значение параметра у пользовательского поля будет использоваться как префикс, т.е. языковые константы
         *     для значений списка должны иметь названия, начинающиеся с названия языковой константы у их
         *     пользовательского поля, если такое имеется у него, и знаком подчеркивания после.
         *     После создания пользовательского поля его ID будет записан в опциях модуля в группе, в которой он был
         *     объявлен, т.е. для HighloadFields ID будет записан в опциях модуля в группе HighloadFields, в массиве
         *     под "ключом" ID.
         *     ID значений пользовательского поля типа "Список" так же будут сохранены в опциях модуля в данных своего
         *     пользовательского поля.
         *     Значения для SHOW_FILTER:
         *      N - не показывать
         *      I - точное совпадение
         *      E - маска
         *      S - подстрока
         */
        'HighloadFields' => [
            /**
             * Поле "Участник" для HighloadBlock, где хранятся участники для
             * элементов инфоблока "Участвовать"
             */
            'INFS_HL_PARTICIPATE_USER_FIELD' => [
                'HBLOCK_ID' => 'INFS_HL_PARTICIPATE_USERS',
                'DATA' => [
                    'LANG_CODE' => 'HL_PARTICIPATE_USER_FIELD',
                    'TYPE' => 'employee',
                    'MANDATORY' => 'Y',
                    'SHOW_IN_LIST' => 'Y',
                    'EDIT_IN_LIST' => 'Y',
                    'SETTINGS' => []
                ]
            ],
            /**
             * Поле "В чем участвовать" для HighloadBlock, где хранятся участники для
             * элементов инфоблока "Участвовать"
             */
            'INFS_HL_PARTICIPATE_ELEMENT_FIELD' => [
                'HBLOCK_ID' => 'INFS_HL_PARTICIPATE_USERS',
                'DATA' => [
                    'LANG_CODE' => 'HL_PARTICIPATE_ELEMENT_FIELD',
                    'TYPE' => 'iblock_element',
                    'MANDATORY' => 'Y',
                    'SHOW_IN_LIST' => 'Y',
                    'EDIT_IN_LIST' => 'Y',
                    'SETTINGS' => [
                        'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_PARTICIPATE'
                    ]
                ]
            ],
            /**
             * Поле "Дата подачи" для HighloadBlock, где хранятся участники для
             * элементов инфоблока "Участвовать"
             */
            'INFS_HL_PARTICIPATE_DATE_FIELD' => [
                'HBLOCK_ID' => 'INFS_HL_PARTICIPATE_USERS',
                'DATA' => [
                    'LANG_CODE' => 'HL_PARTICIPATE_DATE_FIELD',
                    'TYPE' => 'datetime',
                    'MANDATORY' => 'Y',
                    'SHOW_IN_LIST' => 'Y',
                    'EDIT_IN_LIST' => 'Y',
                    'SETTINGS' => []
                ]
            ],
            /**
             * Поле "Подтверждение" для HighloadBlock, где хранятся участники для
             * элементов инфоблока "Участвовать"
             */
            'INFS_HL_PARTICIPATE_CONFIRMATION_FIELD' => [
                'HBLOCK_ID' => 'INFS_HL_PARTICIPATE_USERS',
                'DATA' => [
                    'LANG_CODE' => 'HL_PARTICIPATE_CONFIRMATION_FIELD',
                    'TYPE' => 'enumeration',
                    'SHOW_IN_LIST' => 'Y',
                    'EDIT_IN_LIST' => 'Y',
                    'LIST_VALUES' => [
                        ['LANG_CODE' => 'YES'],
                        ['LANG_CODE' => 'NO'],
                    ]
                ]
            ],
        ],

        /**
         * Пользовательские поля для групп соц. сети. Значения хранят настройки пользовательского поля.
         * Для них действуют те же правила, как и для настроек в DATA для пользовательских полей в HighloadFields.
         * Значение FIELD_NAME должно быть объявлено в include.php как константа с именем, указанным в SocNetFields
         * как "ключ".
         */
        'SocNetFields' => [],

        /**
         * Пользовательские поля для пользователей. Значения хранят настройки пользовательского поля.
         * Для них действуют те же правила, как и для настроек в DATA для пользовательских полей в HighloadFields.
         * Значение FIELD_NAME должно быть объявлено в include.php как константа с именем, указанным в UserFields
         * как "ключ".
         */
        'UserFields' => [],

        /**
         * Настройки для создания пользовательских групп, "ключ" хранит название константы, которая должна
         * быть объявлена в файле include.php, а "значение" хранит параметры группы, важным из которых является
         * параметр LANG_CODE с наванием языковой константы, в которой хранится название группы. При отсутсвии
         * LANG_CODE параметр будет восприниматсья как группировка нескольких групп, которые уже должны быть созданы
         * до обработки это параметра, при группироваке надо указывать только "ключи", под которыми находятся настройки
         * для создания групп
         */
        'UserGroup' => [],

        /**
         * Настройки для создания свойств инфоблоков. В "значении" указываются параметры для создания свойств инфоблоков.
         * Обязательно нужны параметры LANG_CODE с именем языковой константы для названия и IBLOCK_ID с именем константы,
         * которая использоалась в IBlocks как "ключ", под которым хранятся настройки инфоблока
         */
        'IBlockProperties' => [
            // свойство "Кого поздравляют" для инфоблока "Сказать "Спасибо"
            'INFS_IB_THANKS_PR_RECIPIENT' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_THANKS',
                'LANG_CODE' => 'IBLOCK_THANKS_PROPERTY_RECIPIENT',
                'PROPERTY_TYPE' => 'S',
                'MULTIPLE' => 'Y',
                'USER_TYPE' => 'UserID',
            ],
            // свойство "Ответ" для инфоблока "Задать вопрос"
            'INFS_IB_QUESTION_PR_ANSWER_VALUE' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_QUESTION',
                'LANG_CODE' => 'IBLOCK_QUESTION_PROPERTY_ANSWER_VALUE',
                'DEFAULT_VALUE' => [
                    'TEXT' => '',
                    'TYPE' => 'HTML',
                ],
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'HTML',
            ],
            // свойство "Автор ответа" для инфоблока "Задать вопрос"
            'INFS_IB_QUESTION_PR_ANSWER_AUTHOR' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_QUESTION',
                'LANG_CODE' => 'IBLOCK_QUESTION_PROPERTY_ANSWER_AUTHOR',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'UserID',
            ],
            // свойство "Тема вопроса" для инфоблока "Задать вопрос"
            'INFS_IB_QUESTION_PR_THEME' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_QUESTION',
                'LANG_CODE' => 'IBLOCK_QUESTION_PROPERTY_THEME',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => ''
            ],
            // свойство "Текст вопроса" для инфоблока "Задать вопрос"
            'INFS_IB_QUESTION_PR_QUESTION_VALUE' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_QUESTION',
                'LANG_CODE' => 'IBLOCK_QUESTION_PROPERTY_QUESTION_VALUE',
                'DEFAULT_VALUE' => [
                    'TEXT' => '',
                    'TYPE' => 'HTML',
                ],
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'HTML',
            ],

            // свойство "Тема" для инфоблока "Объявления сотрудников"
            'INFS_IB_EMPLOYEE_ANNOUNCE_PR_THEME' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_EMPLOYEE_ANNOUNCE',
                'LANG_CODE' => 'IBLOCK_EMPLOYEE_ANNOUNCE_PROPERTY_THEME',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => ''
            ],

            // свойство "ID бизнес процесса при нажатии кнопки "Участвовать"
            // для инфоблока "Участвовать"
            'INFS_IB_PARTICIPATE_PR_SEND_DESIRE' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_PARTICIPATE',
                'LANG_CODE' => 'IBLOCK_PARTICIPATE_PROPERTY_SEND_DESIRE',
                'PROPERTY_TYPE' => 'N'
            ],
            // свойство "ID бизнес процесса на ответ пользователю"
            // для инфоблока "Участвовать"
            'INFS_IB_PARTICIPATE_PR_SEND_ANSWER' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_PARTICIPATE',
                'LANG_CODE' => 'IBLOCK_PARTICIPATE_PROPERTY_SEND_ANSWER',
                'PROPERTY_TYPE' => 'N'
            ],

            // свойство "Сотрудник" для инфоблока "Справка 2 НДФЛ"
            'INFS_IB_2_PIT_PR_EMPLOYEE' => [
                'IBLOCK_ID' => 'INFS_IBLOCK_2_PIT',
                'LANG_CODE' => 'IBLOCK_2_PIT_PROPERTY_EMPLOYEE',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'UserID',
            ],
            // свойство "От" для инфоблока "Справка 2 НДФЛ"
            'INFS_IB_2_PIT_PR_FROM' => [
                'IBLOCK_ID' => 'INFS_IBLOCK_2_PIT',
                'LANG_CODE' => 'IBLOCK_2_PIT_PROPERTY_FROM',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'DateTime'
            ],
            // свойство "До" для инфоблока "Справка 2 НДФЛ"
            'INFS_IB_2_PIT_PR_TO' => [
                'IBLOCK_ID' => 'INFS_IBLOCK_2_PIT',
                'LANG_CODE' => 'IBLOCK_2_PIT_PROPERTY_TO',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'DateTime'
            ],

            // свойство "Количество копий" для инфоблока "Копия трудовой книжки"
            'INFS_IB_SERVICERECORD_PR_COUNT' => [
                'IBLOCK_ID' => 'INFS_IBLOCK_SERVICERECORD',
                'LANG_CODE' => 'IBLOCK_SERVICERECORD_PROPERTY_COUNT',
                'PROPERTY_TYPE' => 'N',
                'USER_TYPE' => ''
            ],

            // свойство "Страна" для инфоблока "Справка на визу"
            'INFS_IB_VISA_PR_COUNTRY' => [
                'IBLOCK_ID' => 'INFS_IBLOCK_VISA',
                'LANG_CODE' => 'IBLOCK_VISA_PROPERTY_COUNTRY',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => ''
            ],
            // свойство "Дата первой поездки" для инфоблока "Справка на визу"
            'INFS_IB_VISA_PR_DATE' => [
                'IBLOCK_ID' => 'INFS_IBLOCK_VISA',
                'LANG_CODE' => 'IBLOCK_VISA_PROPERTY_DATE',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => 'DateTime'
            ],
            // свойство "Цель" для инфоблока "Справка на визу"
            'INFS_IB_VISA_PR_PURPOISE' => [
                'IBLOCK_ID' => 'INFS_IBLOCK_VISA',
                'LANG_CODE' => 'IBLOCK_VISA_PROPERTY_PURPOISE',
                'PROPERTY_TYPE' => 'L',
                'USER_TYPE' => '',
                'LIST_VALUES' => [
                    ['LANG_CODE' => 'TEST1'],
                    ['LANG_CODE' => 'TEST2'],
                ]
            ],
            // свойство "Данные паспорта" для инфоблока "Справка на визу"
            'INFS_IB_VISA_PR_PASSPORT' => [
                'IBLOCK_ID' => 'INFS_IBLOCK_VISA',
                'LANG_CODE' => 'IBLOCK_VISA_PROPERTY_PASSPORT',
                'PROPERTY_TYPE' => 'S',
                'USER_TYPE' => ''
            ],
            // свойство "Язык" для инфоблока "Справка на визу"
            'INFS_IB_VISA_PR_LANGUAGE' => [
                'IBLOCK_ID' => 'INFS_IBLOCK_VISA',
                'LANG_CODE' => 'IBLOCK_VISA_PROPERTY_LANGUAGE',
                'PROPERTY_TYPE' => 'L',
                'USER_TYPE' => '',
                'LIST_VALUES' => [
                    ['LANG_CODE' => 'EN'],
                    ['LANG_CODE' => 'RU'],
                ]
            ],

            // свойство "С указанием оклада" для инфоблока "Справка с места работы"
            'INFS_IB_REFERENCE_PR_SALARY' => [
                'IBLOCK_ID' => 'INFS_IBLOCK_REFERENCE',
                'LANG_CODE' => 'IBLOCK_REFERENCE_PROPERTY_SALARY',
                'PROPERTY_TYPE' => 'L',
                'USER_TYPE' => '',
                'LIST_VALUES' => [
                    ['LANG_CODE' => 'YES'],
                    ['LANG_CODE' => 'NO'],
                ]
            ],
        ],

        /**
         * Настройки для создания элементов инфоблоков. В "значении" указываются параметры для элементов инфоблоков.
         * Обязательно нужны параметры LANG_CODE с именем языковой константы для названия и IBLOCK_ID с именем константы,
         * которая использоалась в IBlocks как "ключ", под которым хранятся настройки инфоблока
         * Для указания подробного описания или краткого можно использовать DETAIL_LANG_CODE и PREVIEW_LANG_CODE
         * соответственно, в них указываются языковые константы, под которыми хранятся значения.
         * Для картинки к анонсу или детальной картинки можно использовать PREVIEW_PICTURE и DETAIL_PICTURE, в
         * которых указывается путь относительно папки install в модуле. Остальные параметры для создания элементов
         * такие же, как и в описании метода CIBlockElement::Add.
         */
        'IBlockElements' => [
            'INFS_IBLOCK_NEWS_ELEMENT1' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_NEWS',
                'LANG_CODE' => 'IBLOCK_NEWS_ELEMENT1_NAME',
                'PREVIEW_LANG_CODE' => 'IBLOCK_NEWS_ELEMENT1_PREVIEW',
                'DETAIL_LANG_CODE' => 'IBLOCK_NEWS_ELEMENT1_DETAIL',
                'PREVIEW_PICTURE' => 'images/ib_element_1_anon.png',
                'DETAIL_PICTURE' => 'images/ib_element_1_anon.png',
            ],
            'INFS_IBLOCK_NEWS_ELEMENT2' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_NEWS',
                'LANG_CODE' => 'IBLOCK_NEWS_ELEMENT2_NAME',
                'PREVIEW_LANG_CODE' => 'IBLOCK_NEWS_ELEMENT2_PREVIEW',
                'DETAIL_LANG_CODE' => 'IBLOCK_NEWS_ELEMENT2_DETAIL',
                'PREVIEW_PICTURE' => 'images/ib_element_2_anon.jpg',
                'DETAIL_PICTURE' => 'images/ib_element_2_anon.jpg',
            ],
            'INFS_IBLOCK_NEWS_ELEMENT3' => [
                'IBLOCK_ID' => 'INFS_RUSVINYL_IBLOCK_NEWS',
                'LANG_CODE' => 'IBLOCK_NEWS_ELEMENT3_NAME',
                'PREVIEW_LANG_CODE' => 'IBLOCK_NEWS_ELEMENT3_PREVIEW',
                'DETAIL_LANG_CODE' => 'IBLOCK_NEWS_ELEMENT3_DETAIL',
                'PREVIEW_PICTURE' => 'images/ib_element_3_anon.png',
                'DETAIL_PICTURE' => 'images/ib_element_3_anon.png',
            ]
        ],

        /**
         * Настройки для создания агентов, в "значении" указываются параметры, которые передаются
         * методу CAgent::AddAgent, с "ключами" как названия параметров
         */
        'Agents' => [],

        /**
         * Настройки для форумов. Обязательный параметр LANG_CODE с кодом языковой константы, в
         * которой хранится название форума. Для установки описания форума нужно использовать
         * DESCRIPTION_LANG_CODE с кодом языковой константы.
         * Для установки прав доступа пользовательским группам нужно использовать параметр PERMISSIONS.
         * По-умолчанию, выставляются права на "чтение" в группе "Все пользователи", "Ответ" для
         * зарегистрированных пользователей и "Полный доступ" для администраторов.
         * Текущие значения доступных прав доступа к форуму
         *      A - нет доступа
         *      E - чтение
         *      I - ответ
         *      M - новая тема
         *      Q - модерирование
         *      U - редактирование
         *      Y - полный доступ
         * Права доступа можно указывать и для групп, созданных модулем, просто указав в "ключе" строковый
         * идентификатор константы, используемый в UserGroup
         *
         * Остальные параметры для создания форума такие же, что принимает метод CForumNew::Add.
         * Значения для параметра ORDER_BY
         *      P - дата последнего сообщения
         *      T - тема сообщения
         *      N - количество ответов
         *      V - количество просмотров
         *      D - дата начала темы
         *      A - автор темы
         *
         * Значения для параметра ALLOW_UPLOAD
         *      N - нет
         *      Y - изображений
         *      F - файлов с указанными расширениями
         *      A - любых файлов
         */
        'Forums' => [
            'INFS_DETAIL_PAGE_FORUM' => [
                'LANG_CODE' => 'DETAIL_PAGE_FORUM_TITLE',
            ]
        ],

        /**
         * Пользовательские поля для блогов. Настройки такие же, как и при создании других
         * пользоательских полей.
         */
        'BlogFields' => [],
    ];

    // Правила обработки адресов
    const GROUP_ADDR_RULE = [
        /**
         * ЧПУ для новых пунктов меню группы, "ключ" - регулярное выражение,
         * "значение" - массив с параметрами
         *     FILE - путь к файлу
         *     PARAMS - параметры запроса
         * В значениях массива можно использовать константы модуля по их названию
         * как часть строки
         */
        '#^/media/(news|masterblog)/?(?:\?(\S*))?$#' => [
            'FILE' => '/local/public/media/news/index.php',
            'PARAMS' => 'ELEMENT_TYPE_ID=INFS_RUSVINYL_IBLOCK_PREFIX$1&$2'
        ],
        '#^/(announ|leader|participate)/?(?:\?(\S*))?$#' => [
            'FILE' => '/local/public/media/news/index.php',
            'PARAMS' => 'ELEMENT_TYPE_ID=INFS_RUSVINYL_IBLOCK_PREFIX$1&$2'
        ],
        '#^/(?:media/news|announ|leader|media/masterblog)/(\d+)/?(?:\?(\S*))?$#' => [
            'FILE' => '/local/public/media/news/unit.php',
            'PARAMS' => 'ELEMENT_ID=$1&$2'
        ],
        '#^/(?:pulse/poll|competition)/(\d+)/?(?:\?(\S*))?$#' => [
            'FILE' => '/pulse/poll/unit.php',
            'PARAMS' => 'VOTE_ID=$1&$2'
        ],
        '#^/participate/(\d+)/?(?:\?(\S*))?$#' => [
            'FILE' => '/local/public/participate/unit.php',
            'PARAMS' => 'ELEMENT_ID=$1&$2'
        ],
    ];

    /**
     * Настройки для создания новых пунктов меню. Каждое значение - массив, в котором указаны параметры
     *     ID - идентификатор пункта меню
     *     TEXT - код языковой константы с названием пункта меню
     *     LINK - ссылка
     */
    const BX24_MAIN_LEFT_MENU = [];

    /**
     * Описание обработчиков событий. Под "ключом" указывается название другого модуля, события которого
     * нужно обрабатывать, в "значении" указывается массив с навазниями классов этого модуля, которые
     * будут отвечать за обработку событий. Сам класс находится в папке lib модуля.
     * У названия класса не надо указывать пространство имен, кроме той части, что идет после
     * названий партнера и модуля. Для обработки конкретных событий эти классы должны иметь
     * статические и открытые методы с такими же названиями, что и события
     * Для создания обработчиков к конкретному highloadblock-у необходимо писать их названия
     * как <символьное имя highloadblock><название события>, например, для события OnAdd
     * у highloadblock с символьным именем Test такой обработчик должен называться TestOnAdd
     */
    const EVENTS_HANDLES = [
        'iblock' => [
            'EventHandles\\IBlockElementEventHandle'
        ],
        'highloadblock' => [
            'EventHandles\\HighloadBlockEventHandle'
        ]
    ];

    /**
     * Пути к файлам и папкам, что лежат в папке install модуля,  на которые необходимо создать символьные ссылки
     * относительно папки local. Игнорируются файлы из папки www. Символная ссылка будет созданна на последнюю часть
     * указанного пути, по остальным частям будут созданны папки, если их нет. При удалении модуля сивмольная ссылка
     * удалится, а затем и все папки, в которые она входит, если в них больше ничего нет.
     * Если при установке выяснится, что символьная ссылка на последнюю часть пути уже существует, или на ее месте
     * находится папа, или одна из непоследних частей пути не является папкой, то произойдет ошибка
     */
    const FILE_LINKS = [
        'components/infoservice/entity.frames', 'components/infoservice/iblock.list',
        'components/infoservice/iblock.detail', 'components/infoservice/vote.list',
        'components/infoservice/vote.detail', 'components/infoservice/participate.buttons',
        'components/infoservice/services', 'templates/rusvinyl', 'public/media/news',
        'public/participate', 
    ];

    /**
     * Аналогично FILE_LINKS, только для файлов в папке www, что лежит в папке install модуля. Указываются файлы и
     * папки, на которые надо создать символьные ссылки в корневой папке сайта, инорируются указания на все в
     * папках bitrix и local. В отличии от FILE_LINKS здесь можно указывать файлы и папки, которых нет в www модуля,
     * а так же при создании символьной ссылки в корневой папке сайта не будет ошибки, если на месте будет уже
     * существовать файл или папка. Уже существующие файлы или папки будут просто переименованы и запомнены,
     * благодаря чему при удалении модуля снова вернутся на свое место. Благодаря тому, что тут можно указывать
     * даже не существующие в папке www модуля данные, можно добиться переименования существующих в корне сайта
     * файлов или папок без необходимости создавать для этого пустой файл в папке www модуля
     */
    const WWW_FILES = [
        'index.php',
        // меню
        '.top.menu.php', '.top.menu_ext.php',
        '.left.menu.php', '.left.menu_ext.php',
        '.footer.menu_ext.php', '.main.menu_ext.php',
        'lang/ru/.top.menu.php', 'lang/ru/.top.menu_ext.php',
        'lang/ru/.left.menu.php', 'lang/ru/.left.menu_ext.php',
        'lang/ru/.footer.menu_ext.php', 'lang/ru/.main.menu_ext.php',
        // разделы
        'brain', 'company', 'competition', 'hr', 'media',
        'participate', 'phonebook', 'pulse', 'question',
        'services', 'useful',
    ];

    function __construct()
    {
        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');

        include_once __DIR__ . '/version.php';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->nameSpaceValue = preg_replace('/\.+/', '\\\\', ucwords($this->MODULE_ID, '.'));
        $this->defaultSiteID = CSite::GetDefSite();
    }

    /**
     * Создает группы, которые нужны, если их нет в системе, возвращает ID созданных групп.
     * Если группы есть, то возвращает их ID, новые группы не создаются
     * 
     * @param string $constName - название константы
     * @param string $optionValue - значение опции
     * @return integer
     */
    public function initSocNetSubjectsOptions(string $constName, string $optionValue)
    {
        if (!Loader::includeModule('socialnetwork')) return;

        $value = Loc::getMessage($optionValue);
        $socNetUnit = CSocNetGroupSubject::GetList(
                ['ID' => 'ASC'],
                ['SITE_ID' => $this->defaultSiteID, 'NAME' => $value]
            )->Fetch();
        if ($socNetUnit)
            return intval($socNetUnit['ID']);
        return CSocNetGroupSubject::Add(['NAME' => $value, 'SITE_ID' => 's1']);
    }

    /**
     * Создает нужный highloadblock, если его нет, иначе вызывает исключение.
     * Возвращает ID созданного highloadblock.
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return void|integer
     * @throws
     */
    public function initHighloadBlockOptions(string $constName, array $optionValue)
    {
        if (!Loader::includeModule('highloadblock')) return;

        $codeName = strtolower(constant($constName));
        $name = preg_replace_callback(
            '/(?:^|_)(\w)/',
            function($part) {
                return strtoupper($part[1]);
            },
            $codeName
        );
        $result = HighloadBlockTable::add(
            [
                'NAME' => $name,
                'TABLE_NAME' => preg_replace('/[^a-z\d]+/i', '', $codeName)
            ]
        );
        if (!$result->isSuccess(true))
            throw new Exception(
                Loc::getMessage('ERROR_HIGHLOAD_CREATING', ['NAME' => $optionName])
                . PHP_EOL . implode(PHP_EOL, $result->getErrorMessages())
            );
        $hlId = $result->GetId();
        if (
            !empty($optionValue['LANG_CODE'])
            && !empty($title = Loc::getMessage($optionValue['LANG_CODE']))
        ) HighloadBlockLangTable::add(['ID' => $hlId, 'LID' => LANGUAGE_ID, 'NAME' => $title]);

        return $hlId;
    }

    /**
     * Создание значений для пользовательского поля типа "Список"
     * 
     * @param int $fieldId - ID пользовательского поля
     * @param array $fieldValues - значения пользовательского поля
     * @param string $langCode - префикс к языковым константам для названий значений поля
     * @return array
     */
    protected function addListValues(int $fieldId, array $fieldValues, string $langCode)
    {
        $units = [];
        $values = [];
        $newN = 0;
        foreach ($fieldValues as $unit) {
            $value = Loc::getMessage(($langCode ? $langCode . '_' : '') . $unit['LANG_CODE']);
            if (empty($value)) continue;

            if (!in_array($value, $values)) {
                $units['n' . $newN] = ['VALUE' => $value]
                                    + array_filter($unit, function($key) {
                                                return !in_array(strtoupper($key), ['LANG_CODE', 'ID']);
                                            }, ARRAY_FILTER_USE_KEY);
                ++$newN;
            }

            $values[$unit['LANG_CODE']] = $value;
        }

        if (empty($units)) return [];

        (new CUserFieldEnum())->SetEnumValues($fieldId, $units);
        $ids = [];
        $savedUnits = CUserFieldEnum::GetList([], ['USER_FIELD_ID' => $fieldId]);
        while ($saved = $savedUnits->Fetch()) {
            foreach ($values as $key => $value) {
                if ($value != $saved['VALUE']) continue;

                $ids['VALUES'][] = intval($saved['ID']);
                $ids[$key . '_ID'] = intval($saved['ID']);
            }
        }
        return $ids;
    }

    /**
     * Добавляет новое пользовательское поле, прежде устанавливая дополнительные свойства поля,
     * которые не были указаны в переданных данных.
     * 
     * @param string $entityId - код поля
     * @param string $constName - название константы
     * @param array $fieldData - данные нового поля
     * @return array
     * @throws
     */
    public function addUserField(string $entityId, string $constName, array $fieldData) 
    {
        global $APPLICATION;

        $fields = [
                'ENTITY_ID' => $entityId,
                'FIELD_NAME' => constant($constName),
                'USER_TYPE_ID' => $fieldData['TYPE']
            ] + $fieldData + [
                'XML_ID' => '',
                'SORT' => 500,
                'MULTIPLE' => 'N',
                'MANDATORY' => 'N',
                'SHOW_FILTER' => 'N',
                'SHOW_IN_LIST' => 'N',
                'EDIT_IN_LIST' => 'N',
                'IS_SEARCHABLE' => 'N',
                'SETTINGS' => []
            ];
        if (!preg_match('/^uf_/i', $fields['FIELD_NAME']))
            throw new Exception(Loc::getMessage('ERROR_BAD_USER_FIELD_NAME', ['NAME' => $constName]));

        if (!empty($fields['LANG_CODE'])) {
            $langValue = Loc::getMessage($fields['LANG_CODE']);
            unset($fields['LANG_CODE']);
            foreach ([
                        'EDIT_FORM_LABEL', 'LIST_COLUMN_LABEL', 'LIST_FILTER_LABEL',
                        'ERROR_MESSAGE', 'HELP_MESSAGE'
                    ] as $labelUnit) {

                $fields[$labelUnit] = ['ru' => $langValue, 'en' => ''];
            }
        }
        if ($fieldData['TYPE'] == 'vote') {
            if (
                empty($fields['SETTINGS']['CHANNEL_ID'])
                || !defined($fields['SETTINGS']['CHANNEL_ID'])
                || empty($channelCode = constant($fields['SETTINGS']['CHANNEL_ID']))
                || empty($channelId = Options::getVoteChannels($channelCode))
            ) throw new Exception(Loc::getMessage('ERROR_BAD_USER_FIELD_VOTE_CHANNEL', ['NAME' => $constName]));
            $fields['SETTINGS']['CHANNEL_ID'] = $channelId;

        } elseif ($fieldData['TYPE'] == 'iblock_element') {
            if (
                empty($fields['SETTINGS']['IBLOCK_ID'])
                || !defined($fields['SETTINGS']['IBLOCK_ID'])
                || empty($iblockICode= constant($fields['SETTINGS']['IBLOCK_ID']))
                || empty($iblockId = Options::getIBlocks($iblockICode))
            ) throw new Exception(Loc::getMessage('ERROR_BAD_USER_FIELD_IBLOCK', ['NAME' => $constName]));
            $fields['SETTINGS']['IBLOCK_ID'] = $iblockId;

        } elseif (!in_array($fieldData['TYPE'], ['crm'])) {
            $fields['SETTINGS'] += [
                'DEFAULT_VALUE' => '',
                'SIZE' => '20',
                'ROWS' => '1',
                'MIN_LENGTH' => '0',
                'MAX_LENGTH' => '0',
                'REGEXP' => ''
            ];
        }

        $fieldEntity = new CUserTypeEntity();
        $fieldId = $fieldEntity->Add($fields);
        if (!$fieldId)
            throw new Exception(
                Loc::getMessage('ERROR_USERFIELD_CREATING', ['NAME' => $constName]) . PHP_EOL .
                $APPLICATION->GetException()->GetString()
            );
        
        $result = ['ID' => intval($fieldId)];
        if (($fieldData['TYPE'] == 'enumeration') && !empty($fieldData['LIST_VALUES']))
            $result += $this->addListValues($result['ID'], $fieldData['LIST_VALUES'], $fieldData['LANG_CODE'] ?: '');

        return $result;
    }

    /**
     * Создает пользовательские поля доя конкретных highloadblock,
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return mixed
     */
    public function initHighloadFieldsOptions(string $constName, array $optionValue) 
    {
        if (!defined($optionValue['HBLOCK_ID'])) return;

        $hlName = constant($optionValue['HBLOCK_ID']);
        if (empty($entityId = Options::getHighloadBlock($hlName)))
            return;

        $entityId = 'HLBLOCK_' . $entityId;
        return $this->addUserField($entityId, $constName, $optionValue['DATA']);
    }

    /**
     * Создание пользовательского поля для групп соц. сети
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return mixed
     */
    public function initSocNetFieldsOptions(string $constName, array $optionValue) 
    {
        return $this->addUserField('SONET_GROUP', $constName, $optionValue);
    }

    /**
     * Создание пользовательского поля для пользователей
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return mixed
     */
    public function initUserFieldsOptions(string $constName, array $optionValue) 
    {
        return $this->addUserField('USER', $constName, $optionValue);
    }

    /**
     * Создание пользовательских групп
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return array|integer|void
     * @throws
     */
    public function initUserGroupOptions(string $constName, array $optionValue)
    {
        if (!isset($optionValue['LANG_CODE'])) {
            if (!is_array($optionValue)) return;

            $groupIds = [];
            foreach ($optionValue as $constName) {
                if (!is_string($constName) || !defined($constName)
                    || !isset(static::OPTIONS['UserGroup'][$constName]))
                    continue;
                $constValue = constant($constName);
                if (!is_numeric($userGroupId = Options::getUserGroup($constValue)) || empty($userGroupId))
                    continue;

                $groupIds[] = $userGroupId;
            }
            return $groupIds;
        }

        $name = Loc::getMessage($optionValue['LANG_CODE']);
        if (empty($name)) return;

        $group = new CGroup;
        $fields = ['NAME' => $name, 'ACTIVE' => 'Y'] + array_filter($optionValue, function($param) {
            return !in_array($param, ['ID', 'LANG_CODE', 'USER_ID', 'STRING_ID']);
        });
        $groupId = $group->Add($fields);
        if (!empty($group->LAST_ERROR))
            throw new Exception(Loc::getMessage('ERROR_USER_GROUP_CREATING', ['NAME' => $constName])
                                . PHP_EOL . $group->LAST_ERROR);
        return $groupId;
    }

    /**
     * Проверяет наличие языковой константы и ее значение
     * 
     * @param $langCode - название языковой константы
     * @param string $prefixErrorCode - префикс к языковым конcтантам для ошибок без указания ERROR_
     * в начале, но который должен быть у самой константы
     * 
     * @param array $errorParams - дополнительные параметры для ошибок
     * @return string
     */
    protected static function checkLangCode($langCode, string $prefixErrorCode, array $errorParams = [])
    {
        if (!isset($langCode))
            throw new Exception(Loc::getMessage('ERROR_' . $prefixErrorCode . '_LANG', $errorParams));
        
        $value = Loc::getMessage($langCode);
        if (empty($value))
            throw new Exception(
                Loc::getMessage('ERROR_' . $prefixErrorCode . '_EMPTY_LANG', $errorParams + [
                        'LANG_CODE' => $langCode
                    ])
            );
        return $value;
    }

    /**
     * Создание типа инфоблока
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return void
     * @throws
     */
    public function initIBlockTypesOptions(string $constName, array $optionValue)
    {
        if (!Loader::includeModule('iblock')) return;

        $iblockTypeID = constant($constName);
        if (CIBlockType::GetList([], ['ID' => $iblockTypeID])->Fetch())
            return;

        $title = self::checkLangCode($optionValue['LANG_CODE'], 'IBLOCK_TYPE', ['TYPE' => $constName]);
        $data = ['ID' => $iblockTypeID, 'LANG' => ['RU' => ['NAME' => $title]]]
              + array_filter($optionValue, function($key) {
                    return !in_array($key, ['LANG_CODE']);
                }, ARRAY_FILTER_USE_KEY)
              + ['SECTIONS' => 'Y'];

        $list = new CIBlockType();
        if (!$list->Add($data))
            throw new Exception(
                Loc::getMessage('ERROR_IBLOCK_TYPE_CREATING', ['TYPE' => $constName])
                . PHP_EOL . $list->LAST_ERROR
            );
    }

    /**
     * Возвращает готовый массив с правами доступа с идентификаторами конкретных пользовательских групп.
     * В правах досупа могут указываться под "ключами" как идентификаторы существующих
     * в системе групп, так и строковые значения с именем константы для пользовательской группы,
     * созданной этим модулем
     * 
     * @param array $defaultPermissions - права доступа по-умолчанию
     * @param array $addPermissions - дополнительные права доступа
     * @return array
     */
    protected static function getGroupPermissions(array $defaultPermissions, array $addPermissions = [])
    {
        $permissionsDefault = $defaultPermissions;
        if (!empty($addPermissions))
            $permissionsDefault = array_merge($permissionsDefault, $addPermissions);

        $permissions = [];
        foreach ($permissionsDefault as $groupId => $accessValue) {
            if (is_numeric($groupId)) {
                $permissions[$groupId] = $accessValue;

            } elseif (
                is_string($groupId) && !empty($groupId)
                && defined($groupId) && !empty($groupId = constant($groupId))
                && !empty($groupId = Options::getUserGroup($groupId))
            ) {
                $permissions[$groupId] = $accessValue;
            }
        }

        return $permissions;
    }

    /**
     * Создание инфоблока
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return integer
     * @throws
     */
    public function initIBlocksOptions(string $constName, array $optionValue)
    {
        // Инфоблок может создаваться в другом типе инфоблока
        if (!Loader::includeModule('iblock')) return;

        $title = self::checkLangCode($optionValue['LANG_CODE'], 'IBLOCK', ['IBLOCK' => $constName]);
        $data = [
                    'ACTIVE' => 'Y',
                    'NAME' => $title,
                    'CODE' => constant($constName),
                    'IBLOCK_TYPE_ID' => constant($optionValue['IBLOCK_TYPE_ID']),
                    /**
                     * VERSION определяет способ хранения значений свойств элементов инфоблока
                     *     1 - в общей таблице
                     *     2 - в отдельной
                     * Но выбрано строго 2, так ка при работе с множественными значениями свойств
                     * инфоблока могут быть проблемы из-за того, что при запросе элементов через
                     * GetList на каждое значение свойства будет дан столько же раз тот же элемент
                     */
                    'VERSION' => 2
                ]
              + array_filter($optionValue, function($key) {
                    return !in_array($key, ['LANG_CODE', 'PERMISSIONS']);
                }, ARRAY_FILTER_USE_KEY)
              + [
                    'DETAIL_PAGE_URL' => '',
                    'LIST_PAGE_URL' => '',
                    'WORKFLOW' => 'N',
                    'BIZPROC' => 'N',
                    'SITE_ID' => $this->defaultSiteID
                ];

        $iblock = new CIBlock;
        $iblockId = $iblock->Add($data);
        if (!$iblockId)
            throw new Exception(
                Loc::getMessage('ERROR_IBLOCK_CREATING', ['IBLOCK' => $constName])
                . PHP_EOL . $iblock->LAST_ERROR
            );

        CIBlock::SetPermission(
            $iblockId,
            self::getGroupPermissions(
                [self::ADMIN_GROUP_ID => 'X', self::ALL_USER_GROUP_ID => 'R'],
                $optionValue['PERMISSIONS'] ?: []
            )
        );
        return $iblockId;
    }

    /**
     * Создание значений для свойства инфоблока типа "Список"
     * 
     * @param int $propertyId - ID свойства инфоблока
     * @param array $propertyValues - список значений
     * @param string $langCode - префикс к языковым константам для названий значений
     * @return array
     */
    protected function addIBlockPropertyListValues(int $propertyId, array $propertyValues, string $langCode)
    {
        $values = [];
        $ids = [];
        $list = new CIBlockPropertyEnum;
        foreach ($propertyValues as $unit) {
            $value = Loc::getMessage(($langCode ? $langCode . '_' : '') . $unit['LANG_CODE']);
            $lowerCaseValue = strtolower($value);
            if (empty($value) || in_array($lowerCaseValue, $values)) continue;

            $listUnitId = intval($list->Add(['PROPERTY_ID' => $propertyId, 'VALUE' => $value]));
            $ids['VALUES'][] = $listUnitId;
            $ids[$unit['LANG_CODE'] . '_ID'] = $listUnitId;
        }
        return $ids;
    }

    /**
     * Создание свойств инфоблока
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return integer
     * @throws
     */
    public function initIBlockPropertiesOptions(string $constName, array $optionValue)
    {
        $title = self::checkLangCode($optionValue['LANG_CODE'], 'IBLOCK_PROPERTY', ['PROPERTY' => $constName]);
        $iblockCode = constant($optionValue['IBLOCK_ID']);
        if (empty($iblockCode) || empty($iblockId = Options::getIBlocks($iblockCode)))
            throw new Exception(Loc::getMessage('ERROR_BAD_PROPERTY_IBLOCK', ['PROPERTY' => $constName]));

        $data = [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => $title,
                    'CODE' => constant($constName)
                ]
              + array_filter($optionValue, function($key) {
                    return !in_array($key, ['LANG_CODE', 'LIST_VALUES']);
                }, ARRAY_FILTER_USE_KEY);

        $property = new CIBlockProperty;
        $propertyId = $property->Add($data);
        if (!$propertyId)
            throw new Exception(
                Loc::getMessage('ERROR_IBLOCK_PROPERTY_CREATING', ['PROPERTY' => $constName])
                . PHP_EOL . $property->LAST_ERROR
            );

        $result = ['ID' => $propertyId];
        if (
            ($optionValue['PROPERTY_TYPE'] == 'L') && !$optionValue['USER_TYPE']
            && !empty($optionValue['LIST_VALUES'])
        ) $result += $this->addIBlockPropertyListValues(
                                    $result['ID'],
                                    $optionValue['LIST_VALUES'],
                                    $optionValue['LANG_CODE'] ?: ''
                                );
        return $result;
    }

    /**
     * Создание элемента инфоблока
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return integer
     * @throws
     */
    public function initIBlockElementsOptions(string $constName, array $optionValue)
    {
        $iblockCode = constant($optionValue['IBLOCK_ID']);
        if (empty($iblockCode) || empty($iblockId = Options::getIBlocks($iblockCode)))
            throw new Exception(Loc::getMessage('ERROR_BAD_ELEMENT_IBLOCK_ID', ['ELEMENT' => $constName]));

        $title = self::checkLangCode($optionValue['LANG_CODE'], 'IBLOCK_ELEMENT', ['ELEMENT' => $constName]);
        $data = [
                    'ACTIVE' => 'Y',
                    'NAME' => $title,
                    'IBLOCK_ID' => $iblockId
                ]
              + array_filter($optionValue, function($key) {
                    return !in_array($key, [
                                'LANG_CODE', 'PREVIEW_LANG_CODE',
                                'DETAIL_LANG_CODE', 'PREVIEW_TEXT', 'DETAIL_TEXT',
                                'DETAIL_PICTURE', 'PREVIEW_PICTURE'
                            ]);
                }, ARRAY_FILTER_USE_KEY)
              + [
                    'MODIFIED_BY' => self::USER_ID,
                    'IBLOCK_SECTION_ID' => false,
                    'PROPERTY_VALUES' => [],
                    'PREVIEW_TEXT' => empty($optionValue['PREVIEW_LANG_CODE']) ? ''
                                    : Loc::getMessage($optionValue['PREVIEW_LANG_CODE']),
                    'DETAIL_TEXT' => empty($optionValue['DETAIL_LANG_CODE']) ? ''
                                   : Loc::getMessage($optionValue['DETAIL_LANG_CODE']),
                ];

        foreach (['DETAIL_PICTURE', 'PREVIEW_PICTURE'] as $pictureCode) {
            if (empty($optionValue[$pictureCode])) continue;

            $picturePath = __DIR__ . '/' . ltrim($optionValue[$pictureCode], '/\/');
            if (!file_exists($picturePath)) continue;

            $data[$pictureCode] = \CFile::MakeFileArray($picturePath);
        }

        $element = new CIBlockElement;
        $elementId = $element->Add($data);
        if (!$elementId)
            throw new Exception(
                Loc::getMessage('ERROR_IBLOCK_ELEMENT_CREATING', ['ELEMENT' => $constName])
                . PHP_EOL . $element->LAST_ERROR
            );
        return $elementId;
    }

    
    /**
     * Создание агентов в системе
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return integer
     * @throws
     */
    public function initAgentsOptions(string $constName, array $optionValue)
    {
        foreach ($optionValue as $paramName => $paramData) {
            $$paramName = $paramData;
        }
        if (empty($name))
            throw new Exception(Loc::getMessage('ERROR_AGENT_EMPTY_NAME', ['AGENT' => $constName]));
            
        return \CAgent::AddAgent(
            $this->nameSpaceValue . '\\' . rtrim($name, ';') . ';',
            $this->MODULE_ID,
            $period ?? 'N',
            $interval ?? 60,
            $datecheck ?? '',
            $active ?? 'Y',
            $next_exec ?? '',
            $sort ?? 100,
            $user_id ?? self::USER_ID,
            $existError ?? false
        );
    }

    /**
     * Создание форумов в системе
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return integer
     * @throws
     */
    public function initForumsOptions(string $constName, array $optionValue)
    {
        if (!Loader::includeModule('forum')) return;

        $title = self::checkLangCode($optionValue['LANG_CODE'], 'FORUM_UNIT', ['FORUM' => $constName]);

        $data = [
                    'NAME' => $title,
                    'ACTIVE' => 'Y',
                ]
              + array_filter($optionValue, function($key) {
                    return !in_array($key, [
                                'LANG_CODE', 'DESCRIPTION', 'DESCRIPTION_LANG_CODE',
                                'PERMISSIONS'
                            ]);
                }, ARRAY_FILTER_USE_KEY)
              + [
                    'DESCRIPTION' => empty($optionValue['DESCRIPTION_LANG_CODE']) ? ''
                                   : Loc::getMessage($optionValue['DESCRIPTION_LANG_CODE']),
                    'FORUM_GROUP_ID' => 0,
                    'SITES' => [$this->defaultSiteID => '/'],
                    'ORDER_BY' => 'P',
                    'MODERATION' => 'N',
                    'INDEXATION' => 'Y',
                    'DEDUPLICATION' => 'Y',
                    'USE_CAPTCHA' => 'N',
                    'ALLOW_HTML' => 'N',
                    'ALLOW_ANCHOR' => 'Y',
                    'ALLOW_BIU' => 'Y',
                    'ALLOW_IMG' => 'Y',
                    'ALLOW_VIDEO' => 'Y',
                    'ALLOW_TABLE' => 'Y',
                    'ALLOW_LIST' => 'Y',
                    'ALLOW_QUOTE' => 'Y',
                    'ALLOW_CODE' => 'Y',
                    'ALLOW_ALIGN' => 'Y',
                    'ALLOW_FONT' => 'Y',
                    'ALLOW_SMILES' => 'Y',
                    'ALLOW_UPLOAD' => 'Y',
                    'ALLOW_UPLOAD_EXT' => 'N',
                    'ALLOW_TOPIC_TITLED' => 'N',
                    'ALLOW_NL2BR' => 'N',
                    'ALLOW_MOVE_TOPIC' => 'N',
                    'ALLOW_SIGNATURE' => 'N',
                ];

        $forumId = \CForumNew::Add($data);
        if (!$forumId)
            throw new Exception(Loc::getMessage('ERROR_FORUM_UNIT_CREATING', ['FORUM' => $constName]));

        \CForumNew::SetAccessPermissions(
            $forumId,
            self::getGroupPermissions(
                [
                    self::ADMIN_GROUP_ID => 'Y',
                    self::ALL_USER_GROUP_ID => 'E',
                    self::SIMPLE_USER_GROUP_ID => 'I',
                ],
                $optionValue['PERMISSIONS'] ?: []
            )
        );
        return $forumId;
    }

    /**
     * Создание групп опросов в системе
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return integer
     * @throws
     */
    public function initVoteChannelsOptions(string $constName, array $optionValue)
    {
        if (!Loader::includeModule('vote')) return;
 
        $title = self::checkLangCode($optionValue['LANG_CODE'], 'VOTE_GROUP_UNIT', ['VOTE_GROUP' => $constName]);
        $data = [
                    'TITLE' => $title,
                    'ACTIVE' => 'Y',
                    'SYMBOLIC_NAME' => constant($constName),
                ]
              + array_filter($optionValue, function($key) {
                    return !in_array($key, ['LANG_CODE']);
                }, ARRAY_FILTER_USE_KEY)
              + [
                    'USE_CAPTCHA' => 'N',
                    'HIDDEN' => 'N',
                    'SITE' => [$this->defaultSiteID]
                ];

        $channelId = \CVoteChannel::Add($data);
        if (!$channelId)
            throw new Exception(Loc::getMessage('ERROR_VOTE_GROUP_UNIT_CREATING', ['VOTE_GROUP' => $constName]));

        return $channelId;
    }

    /**
     * Создание пользовательского поля для блогов
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return mixed
     */
    public function initBlogFieldsOptions(string $constName, array $optionValue)
    {
        return $this->addUserField('BLOG_POST', $constName, $optionValue);
    }

    /**
     * Создание всех опций
     *
     * @return  void
     */
    public function initOptions() 
    {
        foreach (static::OPTIONS as $methodNameBody => $optionList) {
            $methodName = 'init' . $methodNameBody . 'Options';
            if (!method_exists($this, $methodName)) continue;

            foreach ($optionList as $constName => $optionValue) {
                if (!defined($constName)) return;

                $value = $this->$methodName($constName, $optionValue);
                if (!isset($value)) continue;
                $optionMethod = 'set' . $methodNameBody;
                Options::$optionMethod(constant($constName), $value);
            }
        }
    }

    /**
     * Добавление правил обработки адресов
     *
     * @return  void
     */
    public function addAddrRules() 
    {
        foreach (static::GROUP_ADDR_RULE as $rule => $data) {
            $filter = ['SITE_ID' => $this->defaultSiteID, 'CONDITION' => $rule];

            array_walk($data, function(&$value) {
                $value = strtr($value, $this->definedContants);
            });
            $fields = ['ID' => '', 'PATH' => $data['FILE'], 'RULE' => $data['PARAMS'], 'SORT' => 100];
            $rules = CUrlRewriter::GetList($filter, ['SORT' => 'ASC']);

            if (empty($rules)) {
                CUrlRewriter::Add($filter + $fields);

            } else {
                CUrlRewriter::Update($filter, $fields);
            }
        }
    }

    /**
     * Добавление новых пунктов меню в левое меню Битрикс24
     *
     * @return void
     */
    public function addBX24MenuLinks()
    {
        $paramCode = 'left_menu_items_to_all_' . $this->defaultSiteID;
        $menuItems = unserialize(Option::get('intranet', $paramCode, false, $this->defaultSiteID));
        if (!is_array($menuItems)) $menuItems = [];

        foreach (static::BX24_MAIN_LEFT_MENU as $menuItem) {
            $isAdded = false;
            foreach ($menuItems as $existItem) {
                if ($existItem['ID'] != $menuItem['ID']) continue;

                $isAdded = true;
                break;
            }

            if ($isAdded) continue;

            $menuItems[] = ['TEXT' => constant($menuItem['TEXT'])] + $menuItem;
        }
        Option::set('intranet', $paramCode, serialize($menuItems), $this->defaultSiteID);
    }

    /**
     * Регистрация обработчиков событий
     * 
     * @return void
     */
    public function initEventHandles()
    {
        $eventManager = EventManager::getInstance();
        $eventsHandles = [];
        foreach (static::EVENTS_HANDLES as $moduleName => $classNames) {
            foreach ($classNames as $className) {
                $classNameValue = $this->nameSpaceValue . '\\' . $className;
                if (!class_exists($classNameValue)) return;

                $registerModuleName = $moduleName == 'highloadblock' ? '' : $moduleName;
                $reflectionClass = new ReflectionClass($classNameValue);
                foreach ($reflectionClass->getMethods() as $method) {
                    if (!$method->isPublic() || !$method->isStatic()) continue;

                    $eventName = $method->getName();
                    $eventsHandles[$moduleName][$eventName][] = $className;
                    $eventManager->registerEventHandler(
                        $registerModuleName, $eventName, $this->MODULE_ID, $classNameValue, $eventName
                    );
                }
            }
        }
        Options::setEventsHandles($eventsHandles);
    }

    /**
     * Функция-генератор, по списку переданных файлов делает предобработку названия каждого файла
     * и возвращает  обработанное название файла, рарзделенный на части путь к файлу и его длину.
     * Благодаря второму параметру exclude, в котором указываются пути для исключений, можно отбросить
     * все переданные в списке файлы, путь к которым введен в эти пути для исключения
     * 
     * @param array $files - список файлов
     * @param array $exclude - пути для исключения файлов
     */
    protected static function getFileParts(array $files, array $exclude = [])
    {
        $excludeFiles = array_map(
            function($eFile) {
                $parts = preg_split('/[\\\\\/]+/', strtolower(trim($eFile , '\\/')));
                return ['count' => count($parts), 'path' => implode('/', $parts)];
            }, $exclude
        );

        foreach ($files as $moduleFile) {
            $fileTarget = strtolower(preg_replace('/[\\\\\/]+/', '/', trim($moduleFile , '\\/')));
            $fileParts = explode('/', $fileTarget);
            $filePartsSize = count($fileParts);
            if (
                count(array_filter(
                    $excludeFiles,
                    function($ePath) use($fileParts, $filePartsSize) {
                        return (
                            ($ePath['count'] <= $filePartsSize)
                            && (implode('/', array_slice($fileParts, 0, $ePath['count'])) == $ePath['path'])
                        );
                    }
                ))
            ) continue;
            yield ['target' => $fileTarget, 'parts' => $fileParts, 'count' => $filePartsSize];
        }
    }

    /**
     * Создание символьных ссылок в папке local
     * 
     * @return void
     */
    public function initFileLinks()
    {
        $fromPath = __DIR__ . '/';
        foreach (self::getFileParts(static::FILE_LINKS, ['www']) as $moduleFile) {
            if (!file_exists($fromPath . $moduleFile['target'])) continue;

            $lastPartNum = $moduleFile['count'] - 1;
            $result = $_SERVER['DOCUMENT_ROOT'] . '/local';
            foreach ($moduleFile['parts'] as $pathNum => $subPath) {
                $result .= '/' . $subPath;
                if (!file_exists($result)) {
                    if ($lastPartNum > $pathNum) {
                        mkdir($result);

                    } else {
                        symlink($fromPath . $moduleFile['target'], $result);
                    }

                } elseif (!is_dir($result) || is_link($result) || ($lastPartNum == $pathNum)) {
                    throw new Exception(Loc::getMessage('ERROR_LINK_CREATING', ['LINK' => $moduleFile['target']]));
                }
            }
        }
    }

    /**
     * Создание символьных ссылок в корне сайта, исключая папки bitrix и local,
     * оригинальные файлы сохраняются, при удалении модуля восстанавливаются
     * 
     * @return void
     */
    public function initWWWFiles()
    {
        foreach (self::getFileParts(static::WWW_FILES, ['bitrix', 'local']) as $moduleFile) {
            $lastPartNum = $moduleFile['count'] - 1;
            $result = '';

            foreach ($moduleFile['parts'] as $pathNum => $subPath) {
                $newResult = $result . '/' . $subPath;
                $fullPath = $_SERVER['DOCUMENT_ROOT'] . $newResult;
                if ($lastPartNum == $pathNum) {
                    if (file_exists($fullPath)) {
                        $savingFile = $newResult . '.' . date('YmdHis');
                        rename($fullPath, $_SERVER['DOCUMENT_ROOT'] . $savingFile);
                        Options::setWWWFiles($moduleFile['target'], $savingFile);
                    }
                    $fullTagerPath = __DIR__ . '/www/' . $moduleFile['target'];
                    if (file_exists($fullTagerPath)) symlink($fullTagerPath, $fullPath);

                } elseif (!file_exists($fullPath)) {
                    mkdir($fullPath);

                } elseif (!is_dir($fullPath) || is_link($fullPath)) {
                    throw new Exception(Loc::getMessage('ERROR_MAIN_LINK_CREATING', ['LINK' => $moduleFile['target']]));
                }
                $result = $newResult;
            }
        }
    }

    /**
     * Создает новую папку, если нее нет или выдает исключение, если уже есть обычный файл 
     * с таким же именем
     * 
     * @param string $folderPath - путь к нужной папке
     * @return string
     * @throws
     */
    protected static function createFolder(string $folderPath)
    {
        if (!file_exists($folderPath)) {
            mkdir($folderPath);

        } elseif (!is_dir($folderPath)) {
            throw new Exception(Loc::getMessage('ERROR_USER_LANG_RESULT', ['FILE' => $folderPath]));
        }
        return $folderPath;
    }

    /**
     * Читает содержимое указанной папки, отдает только папки. Функция-генератор
     * 
     * @param string $userLangPath - путь к папке
     * @return void
     */
    protected static function readUserLang(string $userLangPath)
    {
        $ulReader = opendir($userLangPath);
        while ($langCode = readdir($ulReader)) {
            $sourceLangPath = $userLangPath . '/' . $langCode;
            if (!is_dir($sourceLangPath) || ($langCode == '.') || ($langCode == '..'))
                continue;

            yield ['code' => $langCode, 'sourcePath' => $sourceLangPath];
        }
        closedir($ulReader);
    }

    /**
     * Сохраняет новые значения для конкретного файла портала и конкретных языковых констант
     * 
     * @param string $resultFile - путь, указывающий на конкретный файл
     * @param array $values - значения для языковых констант в конретных файлах
     * @return void
     */
    protected static function saveUserLangValues(string $resultFile, array $values)
    {
        file_put_contents($resultFile, '<?php' . PHP_EOL);
        foreach ($values as $fileName => $langValues) {
            foreach ($langValues as $langCodeConstant => $langValue) {
                file_put_contents($resultFile, 
                    '$MESS["' . $fileName . '"]' .
                         '["' . $langCodeConstant . '"] = "' . addslashes($langValue) . '";' . PHP_EOL,
                    FILE_APPEND
                );
            }
        }
    }

    /**
     * Устанавливает значения для языковых констант, которые используются
     * в разных местах портала
     *
     * @return void
     */
    public function addUserLangValues()
    {
        $sourceUserLangPath = __DIR__ . '/user_lang';
        if (!is_dir($sourceUserLangPath)) return;

        $resultUserLangPath = $_SERVER['DOCUMENT_ROOT'] . '/local';
        foreach (['php_interface', 'user_lang'] as $dirUnit) {
            $resultUserLangPath = self::createFolder($resultUserLangPath . '/' . $dirUnit);
        }

        foreach (self::readUserLang($sourceUserLangPath) as $langParams) {
            $resultLangPath = self::createFolder($resultUserLangPath . '/' . $langParams['code']);
            $langReader = opendir($langParams['sourcePath']);
            while ($langFileName = readdir($langReader)) {
                $sourceLangFile = $langParams['sourcePath'] . '/' . $langFileName;
                $resultLangFile = $resultLangPath . '/' . $langFileName;
                if (is_dir($sourceLangFile) || is_dir($resultLangFile)) continue;

                $MESS = [];
                if (file_exists($resultLangFile)) require $resultLangFile;
                require $sourceLangFile;

                self::saveUserLangValues($resultLangFile, $MESS);
            }
            closedir($langReader);
        }
    }

    /**
     * Выполняет конкретный sql-файл 
     * 
     * @param string $fileName - название SQL-файла
     * @return void
     */
    public function runSQLFile(string $fileName)
    {
        global $DB;
        $installFile = __DIR__ . '/db/' . $fileName . '.sql';
        if (file_exists($installFile))
            $DB->RunSqlBatch($installFile);
    }

    /**
     * Подключает модуль и сохраняет созданные им константы
     * 
     * @return void
     */
    protected function initDefinedContants()
    {
        /**
         * array_keys нужен, так как в array_filter функция isset дает
         * лишнии результаты
         */
        $this->definedContants = array_keys(get_defined_constants());

        Loader::IncludeModule($this->MODULE_ID);
        $this->definedContants = array_filter(
            get_defined_constants(),
            function($key) {
                return !in_array($key, $this->definedContants);
            }, ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Функция, вызываемая при установке модуля
     *
     * @return void
     */
    public function DoInstall() 
    {
        global $APPLICATION;
        RegisterModule($this->MODULE_ID);
        $this->initDefinedContants();

        Infoservice\RusVinyl\EventHandles\Employment::setBussy();
        try {
            $this->initOptions();
            $this->addAddrRules();
            $this->addBX24MenuLinks();
            $this->initEventHandles();
            $this->initFileLinks();
            $this->initWWWFiles();
            $this->addUserLangValues();
            $this->runSQLFile('install');
            Options::setConstants(array_keys($this->definedContants));
            Options::save();
            Infoservice\RusVinyl\EventHandles\Employment::setFree();
            $APPLICATION->IncludeAdminFile(Loc::getMessage('MODULE_WAS_INSTALLED'), __DIR__ . '/step1.php');

        } catch (Exception $error) {
            $this->removeAll();
            $_SESSION['MODULE_ERROR'] = $error->getMessage();
            Infoservice\RusVinyl\EventHandles\Employment::setFree();
            $APPLICATION->IncludeAdminFile(Loc::getMessage('MODULE_NOT_INSTALLED'), __DIR__ . '/error.php');
        }
    }

    /**
     * Удаление пользовательского поля
     * 
     * @param string $entityId - код поля
     * @param string $constName - название константы с символьным кодом поля
     * @return void
     */
    public function removeUserFields(string $entityId, string $constName) 
    {
        $entityField = new CUserTypeEntity();
        $userFields = CUserTypeEntity::GetList(
            [], ['ENTITY_ID' => $entityId, 'FIELD_NAME' =>  constant($constName)]
        );
        while ($field = $userFields->Fetch()) {
            $entityField->Delete($field['ID']);
        }
    }

    /**
     * Удаление highloadblock, созданного модулем при установке
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return void
     */
    public function removeHighloadBlockOptions(string $constName, array $optionValue) 
    {
        if (!Loader::includeModule('highloadblock')) return;

        $codeName = strtolower(constant($constName));
        $name = preg_replace_callback(
            '/(?:^|_)(\w)/',
            function($part) {
                return strtoupper($part[1]);
            },
            $codeName
        );
        $hlUnt = HighloadBlockTable::GetList(['filter' => ['NAME' => $name]])->Fetch();
        if (!$hlUnt) return;

        HighloadBlockTable::delete($hlUnt['ID']);
    }

    /**
     * Удаление пользовательского поля для групп соц. сети
     * 
     * @param string $constName - название константы
     * @return void
     */
    public function removeSocNetFieldsOptions(string $constName) 
    {
        $this->removeUserFields('SONET_GROUP', $constName);
    }

    /**
     * Удаление пользовательского поля для пользователей
     * 
     * @param string $constName - название константы
     * @return void
     */
    public function removeUserFieldsOptions(string $constName) 
    {
        $this->removeUserFields('USER', $constName);
    }

    /**
     * Удаление пользовательского поля для блогов
     * 
     * @param string $constName - название константы
     * @return void
     */
    public function removeBlogFieldsOptions(string $constName) 
    {
        $this->removeUserFields('BLOG_POST', $constName);
    }

    /**
     * Удаление пользовательской группы
     * 
     * @param $constName - название константы
     * @return void
     */
    public function removeUserGroupOptions(string $constName)
    {
        if (!defined($constName) || empty(static::OPTIONS['UserGroup'][$constName]))
            return;

        $groupIds = Options::getUserGroup(constant($constName));
        if (!is_array($groupIds)) $groupIds = [$groupIds];

        foreach ($groupIds as $groupId) {
            if (!$groupId || !CGroup::GetByID($groupId)->Fetch())
                continue;

            CGroup::Delete($groupId);
        }
    }

    /**
     * Удаление типа инфоблока
     * 
     * @param $constName - название константы
     * @return void
     */
    public function removeIBlockTypesOptions(string $constName)
    {
        if (!Loader::includeModule('iblock')) return;

        $iblockTypeID = constant($constName);
        $iblocks = CIBlock::GetList([], ['CHECK_PERMISSIONS' => 'N']);
        while ($iblock = $iblocks->Fetch()) {
            if ($iblock['IBLOCK_TYPE_ID'] != $iblockTypeID) continue;

            CIBlock::Delete($iblock['ID']);
        }

        CIBlockType::Delete($iblockTypeID);
    }

    /**
     * Удаление инфоблока. Метод нужен, не смотря на то, что при удалении типа инфоблока
     * удаляются и все его инфоблоки, но модуль можно заставить создавать инфоблоки в
     * других типах инфоблоков
     * 
     * @param $constName - название константы
     * @return void
     */
    public function removeIBlocksOptions(string $constName)
    {
        if (!Loader::includeModule('iblock')) return;

        $iblockCode = constant($constName);
        if (empty($iblockId = Options::getIBlocks($iblockCode)) || !is_numeric($iblockId))
            return;

        CIBlock::Delete($iblockId);
    }

    /**
     * Удаление агентов
     * 
     * @param $constName - название константы
     * @return void
     */
    public function removeAgentsOptions(string $constName)
    {
        $agentId = intval(Options::getAgents(constant($constName)));
        if (!$agentId) return;

        \CAgent::Delete($agentId);
    }

    /**
     * Удаление форумов
     * 
     * @param $constName - название константы
     * @return void
     */
    public function removeForumsOptions(string $constName)
    {
        if (!Loader::includeModule('forum')) return;

        $forumId = intval(Options::getForums(constant($constName)));
        if (!$forumId) return;

        \CForumNew::Delete($forumId);
    }

    /**
     * Удаление групп опросов
     * 
     * @param string $constName - название константы
     * @return integer
     * @throws
     */
    public function removeVoteChannelsOptions(string $constName)
    {
        if (!Loader::includeModule('vote')) return;

        $channelId = intval(Options::getVoteChannels(constant($constName)));
        if (!$channelId) return;

        \CVoteChannel::Delete($channelId);
    }

    /**
     * Удаление всех созданных модулем данных согласно прописанным настройкам в
     * OPTIONS
     * 
     * @return void
     */
    public function removeOptions() 
    {
        foreach (array_reverse(static::OPTIONS) as $methodNameBody => $optionList) {
            foreach ($optionList as $constName => $optionValue) {
                if (!defined($constName)) continue;

                $methodName = 'remove' . $methodNameBody . 'Options';
                if (!method_exists($this, $methodName)) continue;

                $this->$methodName($constName, $optionValue);
            }
        }
    }

    /**
     * Удаление правил обработки адресов
     * 
     * @return void
     */
    public function removeAddrRules() 
    {
        foreach (static::GROUP_ADDR_RULE as $rule => $data) {
            CUrlRewriter::Delete(['SITE_ID' => $this->defaultSiteID, 'CONDITION' => $rule]);
        }
    }

    /**
     * Удаление созданных пунктов для основного левого меню Битрикс24
     * 
     * @return void
     */
    public function removeBX24MenuLinks()
    {
        $paramCode = 'left_menu_items_to_all_' . $this->defaultSiteID;
        $menuItems = unserialize(Option::get('intranet', $paramCode, false, $this->defaultSiteID));
        if (!is_array($menuItems)) return;

        $menuItems = array_filter($menuItems, function($menuItem) {
            if (!is_array($menuItem)) return false;

            foreach (static::BX24_MAIN_LEFT_MENU as $item) {
                if ($item['ID'] == $menuItem['ID']) return false;
            }
            return true;
        });
        Option::set('intranet', $paramCode, serialize($menuItems), $this->defaultSiteID);
    }

    /**
     * Удаление всех зарегистрированных модулем обработчиков событий
     * 
     * @return void
     */
    public function removeEventHandles()
    {
        $eventManager = EventManager::getInstance();
        foreach (Options::getEventsHandles() as $moduleName => $eventList) {
            foreach (array_keys($eventList) as $eventName) {
                foreach (
                    $eventManager->findEventHandlers(
                        strtoupper($moduleName),
                        strtoupper($eventName),
                        ['TO_MODULE_ID' => $this->MODULE_ID]
                    ) as $handle) {

                        $eventManager->unRegisterEventHandler(
                            $moduleName, $eventName, $this->MODULE_ID, $handle['TO_CLASS'], $handle['TO_METHOD']
                        );
                }
            }
        }
    }

    /**
     * Удаляет файла, а затем папку, в которой он лежит, если в ней больше ничего нет,
     * после чего по такому же принципу удаляет все родительские папки до папки local
     * 
     * @param string $fileTarget - относительный путь к файлу
     * @param string $where - начальный путь к файлу
     * @return void
     */
    protected static function deleteEmptyPath(string $fileTarget, string $where)
    {
        $result = $where . $fileTarget;
        if (is_dir($result)) {
            rmdir($result);

        } else {
            @unlink($result);
        }

        $toDelete = true;
        for (; $toDelete && ($fileTarget = preg_replace('/\/?[^\/]+$/', '', $fileTarget)); ) {
            $result = $where . $fileTarget;
            $dUnit = opendir($result);
            while ($fUnit = readdir($dUnit)) {
                if (($fUnit == '.') || ($fUnit == '..')) continue;

                $toDelete = false;
                break;
            }
            closedir($dUnit);
            if ($toDelete) rmdir($result);
        }
    }

    /**
     * Удаляет файлы, которые были созданы модулем как символьная ссылка на такой же файл в модуле.
     * Вызывает callback-функцию, если она была передана, с обработанным названием файла
     * 
     * @param array $files - список файлов из папки модуля с установочным файлом index.php
     * @param string $from - относительный путь к подпапке из папки модуля с установочным файлом index.php, где
     * должны лежать указанные в $files файлы
     * @param string $where - путь относительно корня сайта, где будут проверяться и удаляться файлы
     * @return void
     */
    protected static function removeFiles(array $files, string $from, string $where, $callback = null)
    {
        $fromPath = __DIR__  . (trim($from) ? '/' : '') . trim($from) . '/';
        $wherePath = $_SERVER['DOCUMENT_ROOT'] . (trim($where) ? '/' : '') . trim($where) . '/';
        foreach ($files as $moduleFile) {
            $fileTarget = strtolower(preg_replace('/[\\\\\/]+/', '/', trim($moduleFile , '\\/')));
            if (file_exists($fromPath . $fileTarget) && is_link($wherePath . $fileTarget))
                self::deleteEmptyPath($fileTarget, $wherePath);

            if (is_callable($callback)) $callback($fileTarget);
        }
    }

    /**
     * Удаление всех созданных модулем символьных ссылок
     * 
     * @return void
     */
    public function removeFileLinks()
    {
        self::removeFiles(static::FILE_LINKS, '', 'local');
    }

    /**
     * Удаляет созданные модулем файлы в корневом каталоге портала, возвращает старые файлы
     * 
     * @return void
     */
    public function removeWWWFiles()
    {
        self::removeFiles(static::WWW_FILES, 'www', '', function($moduleFile) {
            if (empty($savedFile = Options::getWWWFiles($moduleFile))) return;

            $oldFileName = $_SERVER['DOCUMENT_ROOT'] . $savedFile;
            if (!file_exists($oldFileName)) return;

            rename($oldFileName, $_SERVER['DOCUMENT_ROOT'] . '/' . $moduleFile);
        });
    }

    /**
     * Удаление всех установленных значений для языковых констант в разных частях портала
     * 
     * @return void
     */
    public function removeUserLangValues()
    {
        $sourceUserLangPath = __DIR__ . '/user_lang';
        if (!is_dir($sourceUserLangPath)) return;

        foreach (self::readUserLang($sourceUserLangPath) as $langParams) {
            $resultLangPath = 'php_interface/user_lang/' . $langParams['code'];
            $langReader = opendir($langParams['sourcePath']);
            while ($langFileName = readdir($langReader)) {
                $sourceLangFile = $langParams['sourcePath'] . '/' . $langFileName;
                $resultLangFile = $_SERVER['DOCUMENT_ROOT'] . '/local/' . $resultLangPath . '/' . $langFileName;
                if (is_dir($sourceLangFile) || is_dir($resultLangFile)) continue;

                $MESS = [];
                if (file_exists($resultLangFile)) require $resultLangFile;
                $savedMESS = $MESS;

                $MESS = [];
                require $sourceLangFile;
                foreach ($MESS as $fileName => $langValues) {
                    foreach ($langValues as $langCodeConstant => $langValue) {
                        if (!isset($savedMESS[$fileName][$langCodeConstant])) continue;

                        unset($savedMESS[$fileName][$langCodeConstant]);
                        if (empty($savedMESS[$fileName])) unset($savedMESS[$fileName]);
                    }
                }

                if (empty($savedMESS)) {
                    self::deleteEmptyPath($resultLangPath . '/' . $langFileName, $_SERVER['DOCUMENT_ROOT'] . '/local/');

                } else {
                    self::saveUserLangValues($resultLangFile, $savedMESS);
                }
            }
            closedir($langReader);
        }
    }

    /**
     * Основной метод, очищающий систему от данных, созданных им
     * при установке
     * 
     * @return void
     */
    public function removeAll()
    {
        $this->removeBX24MenuLinks();
        $this->removeAddrRules();
        $this->removeOptions();
        $this->removeEventHandles();
        $this->removeWWWFiles();
        $this->removeFileLinks();
        $this->removeUserLangValues();
        $this->runSQLFile('uninstall');
        UnRegisterModule($this->MODULE_ID); // удаляем модуль
    }

    /**
     * Функция, вызываемая при удалении модуля
     *
     * @return void
     */
    public function DoUninstall() 
    {
        global $APPLICATION;
        Loader::IncludeModule($this->MODULE_ID);
        Infoservice\RusVinyl\EventHandles\Employment::setBussy();
        $this->definedContants = array_fill_keys(Options::getConstants(), '');
        array_walk($this->definedContants, function(&$value, $key) { $value = constant($key); });

        $this->removeAll();
        Option::delete($this->MODULE_ID, ['name' => INFS_RUSVINYL_OPTION_NAME]);
        Infoservice\RusVinyl\EventHandles\Employment::setFree();
        $APPLICATION->IncludeAdminFile(Loc::getMessage('MODULE_WAS_DELETED'), __DIR__ . '/unstep1.php');
    }

}
