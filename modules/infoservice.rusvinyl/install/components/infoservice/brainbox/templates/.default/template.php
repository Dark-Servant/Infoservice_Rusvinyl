<?
 $addClass = '';
if ($arResult['isAdmin']):
    $addClass = ' rusv-is-editabled';
    if (empty($arResult['MAIN_IMAGE']['SRC']))
        $addClass .= ' rusv-is-empty';?>
<label>
    <input type="file" class="rusv-branbox-main-file-input"><?
endif;?>
    <div
        class="rusv-branbox-main<?=$addClass?>"><?
        if (!empty($arResult['MAIN_IMAGE']['SRC'])):?>
        <img src="<?=$arResult['MAIN_IMAGE']['SRC']?>"><?
        endif;?>
    </div><?
if ($arResult['isAdmin']):?>
</label><?
endif;?>
<div class="rusv-branbox-menu">
<?$APPLICATION->IncludeComponent(
    'bitrix:menu', 'rusvinyl.default', 
    [
        'ROOT_MENU_TYPE' => 'brain',
        'USE_EXT' => 'Y'
    ]
);?>
</div>
<div class="rusv-branbox-slider">
<?$APPLICATION->IncludeComponent(
    'infoservice:entity.frames', '',
    [
        'SHOW_PREVIEW_TEXT' => 'Y',
        'FRAMES_CODES' => [['iblock', INFS_RUSVINYL_IBLOCK_LEADER]],
        'FRAMES_ELEMENT_COUNT' => INFS_RUSVINYL_MAIN_PAGE_UNIT_MAX_COUNT,
        'FRAMES_ROW_COUNT' => INFS_RUSVINYL_MAIN_PAGE_ROW_MAX_COUNT
    ]
);?>
</div>