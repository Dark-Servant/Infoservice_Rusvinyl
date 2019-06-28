<?php
use Bitrix\Main\{Localization\Loc, Loader, EventManager, Config\Option};
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\PropertyTable;

class infoservice_rusvinyl extends CModule
{
    public $MODULE_ID = 'infoservice.rusvinyl';
    public $MODULE_NAME;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_DESCRIPTION;

    protected $optionUnits = [];
    protected $nameSpaceValue;
    protected $defaultSiteID;

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
         *     NAME - кодовое имя HighloadBlock
         *     TABLE_NAME - название таблицы
         * В опциях модуля сохранятся в группе HighloadBlock как массив, где ключ
         * значение константы, указанной как "ключ", а значение ID highloadblock
         */
        'HighloadBlock' => [],

        /**
         * настройки для создания пользовательских полей у созданных модулем HighloadBlock.
         * В значении массив с ключами
         *     HLBLOCK_ID - название константы, под значением которой сохранено в опциях модуля ID HighloadBlock
         *     в группе HighloadBlock
         *     DATA - настройки пользовательского поля. ENTITY_ID и FIELD_NAME не указывать. Значение FIELD_NAME
         *     должно быть объявлено в include.php как константа с именем, указанным в HighloadFields как "ключ".
         *     В DATA можно указать LANG_CODE, который используется для указания кода языковой опции, где
         *     хранится название пользовательского поля.
         *     Указывать тип надо не в USER_TYPE_ID, в TYPE, это более сокращено. Остальные настройки такие же,
         *     какие надо передавать в Битриксе.
         *     Если указан тип enumeration, то в параметрах можно указать параметр LIST_VALUES как массив, каждый
         *     элемент которого представляет отдельное значения для списка, для каждого значения списка обязательно
         *     должен быть указан LANG_CODE с именем языковой константы, в которой хранится название значения,
         *     указаные элементы списка с одинаковыми значения будут созданы один раз. При наличии LANG_CODE у
         *     пользовательского поля этот параметр для значений списка надо писать в сокращенном виде, так как
         *     значение параметра у пользовательского поля будет использоваться как префикс, т.е. языковые константы
         *     для значений списка должны иметь названия, начинающиеся с названия языковой константы у их
         *     пользовательского поля. После создания пользовательского поля его ID записан в опциях модуля в группе,
         *     в которой он был объявлен, под именем, которое указано в константе, что испльзуется тут в группе
         *     как "ключ", ID значений пользовательского поля типа "Список" так же будут сохранены в опциях 
         *     в данных своего пользовательского поля
         *     Значения для SHOW_FILTER:
         *      N - не показывать
         *      I - точное совпадение
         *      E - маска
         *      S - подстрока
         */
        'HighloadFields' => [],

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
         * Настройки для создания типов инфоблока. В "значении" указываются параметры для создания типа инфоблока.
         * Обязательно нужен параметр LANG_CODE с именем языковой константы для названия
         */
        'IBlockTypes' => [],

        /**
         * Настройки для создания инфоблоков. В "значении" указываются параметры для создания инфоблоков. Обязательно
         * нужны параметры LANG_CODE с именем языковой константы для названия и IBLOCK_TYPE_ID с именем константы, в
         * которой хранится код типа инфоблока
         */
        'IBlocks' => [],

        /**
         * Настройки для создания свойств инфоблоков. В "значении" указываются параметры для создания свойств инфоблоков.
         * Обязательно нужны параметры LANG_CODE с именем языковой константы для названия и IBLOCK_ID с именем константы,
         * в которой указано значение кода, под которым хранится ID инфоблока в настройках модуля
         */
        'IBlockProperties' => [],

        /**
         * Настройки для создания элементов инфоблоков. В "значении" указываются параметры для элементов инфоблоков.
         * Обязательно нужны параметры LANG_CODE с именем языковой константы для названия и IBLOCK_ID с именем константы,
         * в которой указано значение кода, под которым хранится ID инфоблока в настройках модуля.
         * Для указания подробного описания или краткого можно использовать DETAIL_LANG_CODE и PREVIEW_LANG_CODE
         * соответственно, в них указываются языковые константы, под которыми хранятся значения.
         * Для картинки к анонсу или детальной картинки можно использовать PREVIEW_PICTURE и DETAIL_PICTURE, в
         * которых указывается путь относительно папки install в модуле. Остальные параметры для создания элементов
         * такие же, как и в описании метода CIBlockElement::Add.
         * "Ключ" необходимо указать, так как всё специально созданное модулем должно запоминаться в его опциях.
         */
        'IBlockElements' => [],

        /**
         * Настройки для создания агентов, в "значении" указываются параметры, которые передаются
         * методу CAgent::AddAgent, с "ключами" как названия параметров
         */
        'Agents' => []
    ];

    // Правила обработки адресов
    const GROUP_ADDR_RULE = [
        /**
         * ЧПУ для новых пунктов меню группы, "ключ" - регулярное выражение,
         * "значение" - массив с параметрами
         *     FILE - путь к файлу
         *     PARAMS - параметры запроса
         */
    ];

    /**
     * Настройки для создания новых пунктов меню. Каждое значение - массив, в котором указаны параметры
     *     ID - идентификатор пункта меню
     *     TEXT - код языковой константы с названием пункта меню
     *     LINK - ссылка
     */
    const BX24_MAIN_LEFT_MENU = [];

    /**
     * Описание обработчиков событий. Под "ключом" ранится название модуля, откуда идет событие, "значение"
     * хранит настройки для обработчиков событий указанного модуля, где
     *     "ключ" - название события;
     *     "значение" - массив, у которого в каждом элементе хранится информация о классе и методе, которые обрабатывают
     *     событие. Элемент с информаций об обработчике события представляет из себя массив, первый элемент это название
     *     класса, второй - название метода. Сам класс находится в папке lib модуля. У названия класса не надо указывать
     *     пространство имен
     */
    const EVENTS_HANDLES = [];

    /**
     * Пути к файлам и папкам, что лежат в папке install модуля,  на которые необходимо создать символьные ссылки
     * относительно папки local. Игнорируются файлы из папки www. Символная ссылка будет созданна на последнюю часть
     * указанного пути, по остальным частям будут созданны папки, если их нет. При удалении модуля сивмольная ссылка
     * удалится, а затем и все папки, в которые она входит, если в них больше ничего нет.
     * Если при установке выяснится, что символьная ссылка на последнюю часть пути уже существует, или на ее месте
     * находится папа, или одна из непоследних частей пути не является папкой, то произойдет ошибка
     */
    const FILE_LINKS = [];

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
    const WWW_FILES = [];

    const USER_ID = 1;

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
     * @return integer
     * @throws
     */
    public function initHighloadBlockOptions(string $constName, array $optionValue)
    {
        if (!Loader::includeModule('highloadblock'))
            return null;

        $result = HighloadBlockTable::add($optionValue);
        if (!$result->isSuccess(true))
            throw new Exception(
                Loc::getMessage('ERROR_HIGHLOAD_CREATING', ['NAME' => $optionName])
                . PHP_EOL . implode(PHP_EOL, $result->getErrorMessages())
            );

        return $result->GetId();
    }

    /**
     * Создание значений для пользовательского поля типа "Список"
     * 
     * @param int $fieldId - ID пользовательского поля
     * @param array $fieldValues - значения пользовательского
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
        if (!in_array($fields['TYPE'], ['crm']))
            $fields['SETTINGS'] += [
                'DEFAULT_VALUE' => '',
                'SIZE' => '20',
                'ROWS' => '1',
                'MIN_LENGTH' => '0',
                'MAX_LENGTH' => '0',
                'REGEXP' => ''
            ];

        $fieldEntity = new CUserTypeEntity();
        $fieldId = $fieldEntity->Add($fields);
        if (!$fieldId)
            throw new Exception(
                Loc::getMessage('ERROR_USERFIELD_CREATING', ['NAME' => $constName]) . PHP_EOL .
                $fieldEntity->LAST_ERROR
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
        if (empty($this->optionUnits['HighloadBlock'][$hlName]))
            return;

        $entityId = 'HLBLOCK_' . $this->optionUnits['HighloadBlock'][$hlName];
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
                if (!is_numeric($this->optionUnits['UserGroup'][$constValue])
                    || empty($this->optionUnits['UserGroup'][$constValue]))
                    continue;

                $groupIds[] = $this->optionUnits['UserGroup'][$constValue];
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
     * @param string $prefixErrorCode - префикс к языковым коснтантам для ошибок
     * @param array $errorParams - дополнительные параметры для ошибок
     * @return string
     */
    protected static function checkLangCode($langCode, string $prefixErrorCode, array $errorParams = [])
    {
        if (!isset($langCode))
            throw new Exception(Loc::getMessage($prefixErrorCode . '_LANG', $errorParams));
        
        $value = Loc::getMessage($langCode);
        if (empty($value))
            throw new Exception(
                Loc::getMessage($prefixErrorCode . '_EMPTY_LANG', $errorParams + [
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
        Loader::includeModule('iblock');

        $iblockTypeID = constant($constName);
        if (CIBlockType::GetList([], ['ID' => $iblockTypeID])->Fetch())
            return;

        $title = self::checkLangCode($optionValue['LANG_CODE'], 'ERROR_IBLOCK_TYPE', ['TYPE' => $constName]);
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
     * Создание инфоблока
     * 
     * @param string $constName - название константы
     * @param array $optionValue - значение опции
     * @return integer
     * @throws
     */
    public function initIBlocksOptions(string $constName, array $optionValue)
    {
        $title = self::checkLangCode($optionValue['LANG_CODE'], 'ERROR_IBLOCK', ['IBLOCK' => $constName]);
        $data = [
                    'ACTIVE' => 'Y',
                    'NAME' => $title,
                    'IBLOCK_TYPE_ID' => constant($optionValue['IBLOCK_TYPE_ID'])
                ]
              + array_filter($optionValue, function($key) {
                    return !in_array($key, ['LANG_CODE', 'IBLOCK_TYPE_ID']);
                }, ARRAY_FILTER_USE_KEY)
              + [
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

        return $iblockId;
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
        $title = self::checkLangCode($optionValue['LANG_CODE'], 'ERROR_IBLOCK_PROPERTY', ['PROPERTY' => $constName]);
        $iblockCode = constant($optionValue['IBLOCK_ID']);
        if (empty($iblockCode) || empty($this->optionUnits['IBlocks'][$iblockCode]))
            throw new Exception(Loc::getMessage('ERROR_BAD_PROPERTY_IBLOCK', ['PROPERTY' => $constName]));

        $data = [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $this->optionUnits['IBlocks'][$iblockCode],
                    'NAME' => $title,
                    'CODE' => constant($constName)
                ]
              + array_filter($optionValue, function($key) {
                    return !in_array($key, ['LANG_CODE', 'IBLOCK_ID']);
                }, ARRAY_FILTER_USE_KEY);

        $property = new CIBlockProperty;
        $propertyId = $property->Add($data);
        if (!$propertyId)
            throw new Exception(
                Loc::getMessage('ERROR_IBLOCK_PROPERTY_CREATING', ['PROPERTY' => $constName])
                . PHP_EOL . $property->LAST_ERROR
            );

        return $propertyId;
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
        if (empty($iblockCode) || empty($this->optionUnits['IBlocks'][$iblockCode]))
            throw new Exception(Loc::getMessage('ERROR_BAD_ELEMENT_IBLOCK_ID', ['ELEMENT' => $constName]));

        $title = self::checkLangCode($optionValue['LANG_CODE'], 'ERROR_IBLOCK_ELEMENT', ['ELEMENT' => $constName]);
        $data = [
                    'ACTIVE' => 'Y',
                    'NAME' => $title,
                    'IBLOCK_ID' => $this->optionUnits['IBlocks'][$iblockCode]
                ]
              + array_filter($optionValue, function($key) {
                    return !in_array($key, [
                                'LANG_CODE', 'IBLOCK_ID', 'PREVIEW_LANG_CODE',
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
     * Создание всех опций
     *
     * @return  void
     */
    public function initOptions() 
    {
        if (!Loader::includeModule('socialnetwork'))
            return;

        $this->optionUnits = [];
        foreach (static::OPTIONS as $methodNameBody => $optionList) {
            foreach ($optionList as $constName => $optionValue) {
                if (!defined($constName)) return;

                $methodName = 'init' . $methodNameBody . 'Options';
                if (!method_exists($this, $methodName)) continue;

                $value = $this->$methodName($constName, $optionValue);
                if (!isset($value)) continue;
                $this->optionUnits[$methodNameBody][constant($constName)] = $value;
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
        foreach (static::EVENTS_HANDLES as $moduleName => $eventList) {
            foreach ($eventList as $eventName => $handleList) {
                foreach ($handleList as $handle) {
                    list($className, $methodName) = $handle;

                    $eventManager->registerEventHandler(
                        $moduleName, $eventName, $this->MODULE_ID, $this->nameSpaceValue . '\\' . $className,
                        $methodName
                    );
                }
            }
        }
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
                        $this->optionUnits['WWW_FILES'][$moduleFile['target']] = $savingFile;
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
     * Устанавливает значения для языковых констант, которые используются в разных местах портала
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
     * Функция, вызываемая при установке модуля
     *
     * @return void
     */
    public function DoInstall() 
    {
        global $APPLICATION;
        RegisterModule($this->MODULE_ID);
        Loader::IncludeModule($this->MODULE_ID);
        $_SESSION[$this->MODULE_ID]['PROCESS'] = true;
        try {
            $this->initOptions();
            $this->addAddrRules();
            $this->addBX24MenuLinks();
            $this->initEventHandles();
            $this->initFileLinks();
            $this->initWWWFiles();
            $this->addUserLangValues();
            $this->runSQLFile('install');
            Option::set($this->MODULE_ID, INFS_RUSVINYL_OPTION_NAME, json_encode($this->optionUnits));
            $_SESSION[$this->MODULE_ID]['PROCESS'] = false;
            $APPLICATION->IncludeAdminFile(Loc::getMessage('MODULE_WAS_INSTALLED'), __DIR__ . '/step1.php');

        } catch (Exception $error) {
            $this->removeAll();
            $_SESSION['MODULE_ERROR'] = $error->getMessage();
            $_SESSION[$this->MODULE_ID]['PROCESS'] = false;
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

        $hlUnt = HighloadBlockTable::GetList(['filter' => ['NAME' => $optionValue['NAME']]])->Fetch();
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
     * Удаление пользовательской группы
     * 
     * @param $constName - название константы
     * @return void
     */
    public function removeUserGroupOptions(string $constName)
    {
        if (!defined($constName) || empty(static::OPTIONS['UserGroup'][$constName]))
            return;

        $groupIds = $this->optionUnits['UserGroup'][constant($constName)];
        if (!is_array($groupIds))
            $groupIds = [$groupIds];

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
        Loader::includeModule('iblock');

        $iblockTypeID = constant($constName);
        if (CIBlock::GetList([], ['TYPE' => $iblockTypeID])->Fetch())
            return;

        CIBlockType::Delete($iblockTypeID);
    }

    /**
     * Удаление инфоблока
     * 
     * @param $constName - название константы
     * @return void
     */
    public function removeIBlocksOptions(string $constName)
    {
        Loader::includeModule('iblock');

        $iblockCode = constant($constName);
        if (empty($this->optionUnits['IBlocks'][$iblockCode])
            || !is_numeric($this->optionUnits['IBlocks'][$iblockCode]))
            return;

        CIBlock::Delete($this->optionUnits['IBlocks'][$iblockCode]);
    }

    /**
     * Удаление агентов
     * 
     * @param $constName - название константы
     * @return void
     */
    public function removeAgentsOptions(string $constName)
    {
        $agentId = intval($this->optionUnits['Agents'][constant($constName)]);
        if (!$agentId) return;

        \CAgent::Delete($agentId);
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
        foreach (static::EVENTS_HANDLES as $moduleName => $eventList) {
            foreach ($eventList as $eventName => $handleList) {
                foreach ($handleList as $handle) {
                    list($className, $methodName) = $handle;

                    $eventManager->unRegisterEventHandler(
                        $moduleName, $eventName, $this->MODULE_ID, $this->nameSpaceValue . '\\' . $className,
                        $methodName
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
            if (empty($this->optionUnits['WWW_FILES'][$moduleFile])) return;

            $oldFileName = $_SERVER['DOCUMENT_ROOT'] . $this->optionUnits['WWW_FILES'][$moduleFile];
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
        $_SESSION[$this->MODULE_ID]['PROCESS'] = true;
        $this->optionUnits = json_decode(Option::get($this->MODULE_ID, INFS_RUSVINYL_OPTION_NAME, false, $this->defaultSiteID), true);
        $this->removeAll();
        Option::delete($this->MODULE_ID, ['name' => INFS_RUSVINYL_OPTION_NAME]);
        $_SESSION[$this->MODULE_ID]['PROCESS'] = false;
        $APPLICATION->IncludeAdminFile(Loc::getMessage('MODULE_WAS_DELETED'), __DIR__ . '/unstep1.php');
    }

}
