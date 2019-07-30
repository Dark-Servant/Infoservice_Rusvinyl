<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?$APPLICATION->IncludeComponent(
    'infoservice:iblock.detail', '', [
        'ELEMENT_ID' => $_REQUEST['ELEMENT_ID'],
        'BUTTON_COMPONENT' => 'infoservice:participate.buttons'
    ]
);?>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>