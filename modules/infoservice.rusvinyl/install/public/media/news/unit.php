<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?$APPLICATION->IncludeComponent(
    'infoservice:iblock.detail', '', [
        'ELEMENT_ID' => $_REQUEST['ELEMENT_ID'],
        'DETAIL_PROPERTY_VIDEO' => 'VIDEO'
    ]
);?>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>