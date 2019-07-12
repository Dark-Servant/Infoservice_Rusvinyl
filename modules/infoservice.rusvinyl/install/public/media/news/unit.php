<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?$APPLICATION->IncludeComponent(
    'infoservice:iblock.detail', '', [
        'ELEMENT_ID' => $_REQUEST['ELEMENT_ID']
    ]
);?>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>