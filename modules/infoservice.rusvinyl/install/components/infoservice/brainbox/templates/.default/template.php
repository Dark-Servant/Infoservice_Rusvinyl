<?
if ($arResult['isAdmin']):?>
<label>
    <input type="file" class="rusv-branbox-main-file-input"><?
endif;?>
    <div class="rusv-branbox-main<?=$arResult['isAdmin'] ? ' rusv-is-editabled' : ''?>">
        <img<?if ($arResult['MAIN_IMAGE']):?> src="<?=$arResult['MAIN_IMAGE'] . '?' . time()?>"<?endif;?>>
    </div><?
if ($arResult['isAdmin']):?>
</label><?
endif;?>
<div class="rusv-branbox-menu">
<?$APPLICATION->IncludeComponent(
    'bitrix:menu', 'rusvinyl.default', 
    [
        'ROOT_MENU_TYPE' => 'main',
        'USE_EXT' => 'Y'
    ]
);?>
</div>
<div class="rusv-branbox-slider">
<?$APPLICATION->IncludeComponent(
    'infoservice:entity.frames', '',
    [
        'FRAMES_CODES' => [['iblock', INFS_RUSVINYL_IBLOCK_LEADER]],
        'FRAMES_ELEMENT_COUNT' => INFS_RUSVINYL_MAIN_PAGE_UNIT_MAX_COUNT,
        'FRAMES_ROW_COUNT' => INFS_RUSVINYL_MAIN_PAGE_ROW_MAX_COUNT
    ]
);?>
</div>