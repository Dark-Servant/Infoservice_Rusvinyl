<?
use Bitrix\Main\{Localization\Loc, Type\DateTime};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

if ($arParams['SHOW_VIDEO']) {
    $APPLICATION->AddHeadScript('/local/node_modules/video.js/dist/video.min.js');
    $APPLICATION->SetAdditionalCSS('/local/node_modules/video.js/dist/video-js.min.css');
}?>
<div class="rusv-news-detail">
    <div class="rusv-back-url">
        <a
            class="rusv-back-url-link"
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
    if ($arParams['SHOW_VIDEO']):?>
    <div class="rusv-news-detail-video">
        <video class="rusv-news-detail-video-player video-js"
            controls autoplay preload="auto">
            <source src="<?=$arParams['SHOW_VIDEO']['path']?>" type="video/mp4"></source>
        </video>
    </div><?

    else:
    $displayImg = is_array($arResult['DETAIL_PICTURE']) ? $arResult['DETAIL_PICTURE']['SRC']
                : SITE_TEMPLATE_PATH . '/images/news-default.svg';?>
    <div class="rusv-news-detail-image">
        <img
            class="rusv-news-detail-image-object"
            border="0"
            src="<?=$displayImg?>">
    </div><?
    endif;?>
    <div class="rusv-news-detail-text">
        <div class="rusv-news-detail-text-preview"><?=$arResult['PREVIEW_TEXT']?></div>
        <div class="rusv-news-detail-text-data"><?=$arResult['DETAIL_TEXT']?></div>
    </div>
</div>