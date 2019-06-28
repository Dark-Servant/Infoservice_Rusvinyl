<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
    <meta name="viewport" content="width=1135">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" /><?

    $APPLICATION->ShowHead(false);
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
        </div>
        <div class="rusv-head-date"></div>
        <div class="rusv-head-incident-static"></div>
        <div class="rusv-head-search"></div>
        <div class="rusv-head-authorization"></div>
    </div>
    <div class="rusv-menu"></div>
    <div class="rusv-body">
        <div class="rusv-body-left-part">
            <div class="rusv-body-next-event"></div>
            <div class="rusv-body-left-menu"></div>
        </div>
        <div class="rusv-body-content">
            <div class="rusv-body-content-data">