<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?$APPLICATION->IncludeComponent(
    'infoservice:iblock.list', '', [
        'ELEMENT_TYPE_CODE' => INFS_RUSVINYL_IBLOCK_QUESTION
    ]
);?>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>