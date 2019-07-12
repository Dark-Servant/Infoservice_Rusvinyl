<?
use Bitrix\Main\{Localization\Loc, Type\DateTime};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();?>
<div class="rusv-news-detail">
    <div class="rusv-news-list-back">
        <a
            class="rusv-news-list-back-link"
            href="<?=$arResult['LIST_PAGE_URL']?>"><?=
                Loc::getMessage('RETURN_TO_LIST_LINK_TITLE', ['#NAME#' => $arResult['IBLOCK']['NAME']])
        ?></a>
    </div>
    <div class="rusv-news-detail-title">
        <span class="rusv-news-detail-title-value"><?=$arResult['NAME']?></span>
    </div>
    <div class="rusv-news-detail-date"><?
        $dateTimeValue = ltrim(FormatDate('d F Y', DateTime::createFromUserTime($arResult['TIMESTAMP_X'])->getTimestamp()), '0');?>
        <span class="rusv-news-detail-date-value"><?=$dateTimeValue?></span>
    </div><?
    $displayImg = is_array($arResult['DETAIL_PICTURE']) ? $arResult['DETAIL_PICTURE']['SRC']
                : SITE_TEMPLATE_PATH . '/images/news-default.svg';?>
    <div class="rusv-news-detail-image">
        <img
            class="rusv-news-detail-image-object"
            border="0"
            src="<?=$displayImg?>">
    </div>
    <div class="rusv-news-detail-text">
        <div class="rusv-news-detail-text-preview"><?=$arResult['PREVIEW_TEXT']?></div>
        <div class="rusv-news-detail-text-data"><?=$arResult['DETAIL_TEXT']?></div>
    </div>
</div>