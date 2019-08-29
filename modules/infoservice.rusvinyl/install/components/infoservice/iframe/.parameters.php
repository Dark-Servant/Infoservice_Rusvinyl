<?
use Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    'GROUPS' => [
        'BASE' => [
            'NAME' => Loc::getMessage('BASE_GROUP_TITLE')
        ]
    ],
    'PARAMETERS' => [
        'URL' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('URL_PARAMETER_TITLE'),
            'TYPE' => 'STRING'
        ]
    ]
];
