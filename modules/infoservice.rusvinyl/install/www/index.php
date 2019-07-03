<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?$APPLICATION->IncludeComponent(
    'infoservice:iblock.showblock', '',
    [
        'IBLOCK_CODES' => INFS_RUSVINYL_MAIN_PAGE_IBLOCKS,
        'IBLOCK_ELEMENT_COUNT' => INFS_RUSVINYL_MAIN_PAGE_UNIT_MAX_COUNT,
        'IBLOCK_ROW_COUNT' => INFS_RUSVINYL_MAIN_PAGE_ROW_MAX_COUNT
    ]
);?>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>