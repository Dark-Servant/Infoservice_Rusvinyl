<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?$APPLICATION->IncludeComponent(
    'infoservice:entity.frames', '',
    [
        'FRAMES_CODES' => INFS_RUSVINYL_MAIN_PAGE_FRAMES,
        'FRAMES_ELEMENT_COUNT' => INFS_RUSVINYL_MAIN_PAGE_UNIT_MAX_COUNT,
        'FRAMES_ROW_COUNT' => INFS_RUSVINYL_MAIN_PAGE_ROW_MAX_COUNT
    ]
);?>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>