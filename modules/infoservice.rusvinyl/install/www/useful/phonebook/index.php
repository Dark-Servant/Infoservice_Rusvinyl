<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?$APPLICATION->IncludeComponent(
    'infoservice:iframe', '', [
        'URL' => INFS_PHONEBOOK_IFRAME_URL
    ]
);?>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>