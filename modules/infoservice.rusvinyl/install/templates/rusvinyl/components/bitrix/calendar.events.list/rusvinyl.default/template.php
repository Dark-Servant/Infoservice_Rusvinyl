<?
use Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();
?>
<div class="rusv-near-news-list">
    <div class="rusv-near-news-list-title"><?=Loc::getMessage('NEAR_LNEWS_TITLE')?></div>
    <div class="rusv-near-news-list-area"><?
    if ($arResult['ACCESS_DENIED']):
        echo Loc::getMessage('ECL_T_ACCESS_DENIED');

    elseif ($arResult['INACTIVE_FEATURE']):
        echo Loc::getMessage('ECL_T_INACTIVE_FEATURE');

    elseif (count($arResult['ITEMS']) == 0):
        echo Loc::getMessage('ECL_T_NO_ITEMS');

    else:
        foreach ($arResult['ITEMS'] as $arItem):
            $dateTime = \Bitrix\Main\Type\DateTime::createFromUserTime($arItem['DATE_FROM']);?>
            <a class="rusv-calendar-link" href="<?=$arItem['_DETAIL_URL']?>">
                <span class="rusv-news-date"><?=ltrim(FormatDate('d F', $dateTime), '0')?></span>
                <span class="rusv-news-name"><?=$arItem['NAME']?></span>
            </a><?
        endforeach;?>
        <a class="rusv-calendar-all-events-link" href="#"><?=Loc::getMessage('ALL_EVENT_LINK_TITLE')?></a><?
    endif;?>
    </div>
</div>