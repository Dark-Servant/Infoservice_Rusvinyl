<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?$APPLICATION->IncludeComponent(
    'bitrix:calendar.grid', '', [
        'ALLOW_RES_MEETING' => 'Y',
        'ALLOW_SUPERPOSE' => 'Y',
        'CALENDAR_TYPE' => INFS_CALENDAR_TYPE_TRAINING_EVENT
    ]
);?>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>