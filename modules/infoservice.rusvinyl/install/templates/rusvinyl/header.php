<?
use Bitrix\Main\Localization\Loc;

?><!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
    <meta name="viewport" content="width=1135">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" /><?

    $APPLICATION->ShowHead(false);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/script.js');
    $APPLICATION->AddHeadScript('/local/node_modules/moment/min/moment-with-locales.min.js');
    $APPLICATION->SetAdditionalCSS('/local/node_modules/@fortawesome/fontawesome-free/css/all.min.css');
    CUtil::InitJSCore(['ajax' , 'popup', 'jquery']);?>
    <title><?$APPLICATION->ShowTitle()?></title>
</head>
<body><?
    if (($USER->IsAdmin()) && !defined("SKIP_SHOW_PANEL")):?>
    <div id="panel"><?
        $APPLICATION->ShowPanel();?>
    </div><?
    endif;?>
    <div class="rusv-head">
        <div class="rusv-head-logo">
            <a href="/">
                <img src="<?=SITE_TEMPLATE_PATH?>/images/logo.svg?<?=INFS_CURRENT_TIMESTAMP?>">
            </a>
        </div>
        <div class="rusv-head-date"><?=ltrim(strtolower(FormatDate('d F H:i:s', INFS_CURRENT_TIMESTAMP)), '0')?></div>
        <div class="rusv-head-incident-static">
            <span><?=Loc::getMessage('INCIDENT_STATIC_VALUE', ['#VALUE#' => INFS_INCIDENT_STATIC_EXAMPLE_VALUE])?></span>
        </div>
        <div class="rusv-head-search"><?
            $APPLICATION->IncludeComponent(
                'bitrix:search.form', 'rusvinyl.default'
            );?>
        </div>
        <div class="rusv-head-authorization"><?
            $APPLICATION->IncludeComponent(
                'bitrix:system.auth.form', 'rusvinyl.default'
            );?>
        </div>
    </div>
    <div class="rusv-menu-area">
        <div class="rusv-menu"><?
        $APPLICATION->IncludeComponent(
            'bitrix:menu', 'rusviny.horizontal_multilevel', 
            [
                'ROOT_MENU_TYPE' => 'top',
                'CHILD_MENU_TYPE' => 'left',
                'MAX_LEVEL' => '3',
                'USE_EXT' => 'Y'
            ]
        );?>
        </div>
    </div>
    <div class="rusv-body">
        <div class="rusv-body-left-part">
            <div class="rusv-body-next-event"><?
                $APPLICATION->IncludeComponent(
                    'bitrix:calendar.events.list', 'rusvinyl.default',
                    [
                        'CACHE_TIME' => '3600',
                        'CACHE_TYPE' => 'A',
                        'CALENDAR_TYPE' => 'events',
                        'DETAIL_URL' => '',
                        'EVENTS_COUNT' => '1',
                        'FUTURE_MONTH_COUNT' => '1',
                    ]
                );
            ?></div>
            <div class="rusv-body-left-menu"><?
                $APPLICATION->IncludeComponent(
                    'bitrix:menu', 'rusvinyl.default', 
                    [
                        'ROOT_MENU_TYPE' => 'main',
                        'USE_EXT' => 'Y'
                    ]
                );
            ?></div>
        </div>
        <div class="rusv-body-content">
            <div class="rusv-body-content-data">