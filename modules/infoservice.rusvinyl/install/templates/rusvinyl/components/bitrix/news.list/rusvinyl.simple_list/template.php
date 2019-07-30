<?
use Bitrix\Main\{Localization\Loc, Type\DateTime};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();?>
<div class="rusv-news-simple-list"><?
foreach ($arResult['ITEMS'] as $arItem):?>
    <div class="rusv-news-simple-list-item">
        <div class="rusv-news-simple-list-area"><?
            $previewImg = is_array($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE']['SRC']
                        : SITE_TEMPLATE_PATH . '/images/news-default.svg';?>
            <div class="rusv-news-simple-list-image">
                <img
                    class="rusv-news-simple-list-image-object"
                    border="0"
                    src="<?=$previewImg?>"
                    alt="<?=empty($arItem['PREVIEW_PICTURE']['ALT']) ? '' : $arItem['PREVIEW_PICTURE']['ALT']?>"
                    title="<?=empty($arItem['PREVIEW_PICTURE']['TITLE']) ? '' : $arItem['PREVIEW_PICTURE']['TITLE']?>">
            </div>
            <div class="rusv-news-simple-list-data">
                <div class="rusv-news-simple-list-date-time"><?
                    $dateTimeValue = FormatDate('d.m.Y', DateTime::createFromUserTime($arItem['TIMESTAMP_X'])->getTimestamp());?>
                    <span class="rusv-news-simple-list-date-time-value"><?=$dateTimeValue?></span>
                </div>
                <div class="rusv-news-simple-list-title">
                    <a class="rusv-news-simple-list-link" href="<?=$arItem['DETAIL_PAGE_URL']?>">
                        <span class="rusv-news-simple-list-link-value"><?=$arItem['NAME']?></span>
                    </a>
                </div>
                <div class="rusv-news-simple-list-preview-text"><?=$arItem['PREVIEW_TEXT'] ?: $arItem['DETAIL_TEXT']?></div>
            </div>
        </div>
        <div class="rusv-news-simple-list-data-bottom"></div>
    </div><?
endforeach;

if ($arResult['NAV_RESULT']->NavPageNomer && ($arResult['NAV_RESULT']->NavPageNomer < $arResult['NAV_RESULT']->NavPageCount)):?>
    <div class="rusv-news-simple-list-next-page"
         data-next-page="<?=($arResult['NAV_RESULT']->NavPageNomer + 1)?>">
         <i class="fas fa-spinner fa-spin"></i>
        <span class="rusv-news-simple-list-next-page-link"><?=Loc::getMessage('NEXT_PAGE_TITLE')?></span>
    </div><?
endif;?>
</div>
