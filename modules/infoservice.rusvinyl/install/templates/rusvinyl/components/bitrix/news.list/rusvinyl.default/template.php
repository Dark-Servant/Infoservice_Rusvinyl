<?
if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/323.txt', print_r($arResult['ITEMS'], true), FILE_APPEND);

$itemCount = count($arResult['ITEMS']);
?>
<div class="news-list rusv-news-list" data-count="<?=$itemCount?>"><?
foreach ($arResult['ITEMS'] as $arNum => $arItem):?>
    <div class="news-item rusv-news-item<?=$arNum ? ' rusv-hidden' : ''?>" data-id="<?=$arItem['ID']?>"><?
        $previewImg = is_array($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE']['SRC']
                    : SITE_TEMPLATE_PATH . '/images/news-default.svg';?>
        <div class="rusv-news-item-image">
            <img
                class="preview_picture"
                border="0"
                src="<?=$previewImg?>"
                alt="<?=empty($arItem['PREVIEW_PICTURE']['ALT']) ? '' : $arItem['PREVIEW_PICTURE']['ALT']?>"
                title="<?=empty($arItem['PREVIEW_PICTURE']['TITLE']) ? '' : $arItem['PREVIEW_PICTURE']['TITLE']?>">
        </div>
        <div class="rusv-news-item-title">
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
        </div>
        <div class="rusv-news-item-preview-text">
            <span><?=$arItem['PREVIEW_TEXT'];?></span>
        </div>
    </div><?
endforeach;

if ($itemCount):?>
    <div class="rusv-news-list-pages"><?
    for ($pUnit = 0; $pUnit < $itemCount; ++$pUnit):?>
        <span
            class="rusv-news-list-page <?if (!$pUnit):?>rusv-selected<?endif;?>"
            data-id="<?=$arResult['ITEMS'][$pUnit]['ID']?>"></span><?
    endfor;?>
    </div><?
endif?>
</div>
