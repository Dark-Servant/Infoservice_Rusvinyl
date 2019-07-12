<?
use Bitrix\Main\Localization\Loc;

?>
<div class="rusv-iblock-list-control-buttons" data-main-data="<?=htmlspecialchars(json_encode($arResult['MAINDATA']))?>">
    <span class="rusv-button rusv-iblock-new-unit-button"><?=Loc::getMessage($arResult['MAINDATA']['BUTTON_TITLE_CODE'])?></span>
</div>
<div class="rusv-iblock-list" data-iblock-code="<?=$arResult['MAINDATA']['IBLOCK']['URL_CODE']?>"></div>
<div class="rusv-iblock-pages"></div><?

require __DIR__ . '/' . strtolower($arParams['ELEMENT_TYPE_CODE']) . '.php';?>

<script id="rusv-iblock-list-pages-template" type="text/x-handlebars-template">
    {{#each PAGES}}
    --><span
            class="rusv-iblock-list-page{{#if CURRENT}} rusv-iblock-list-current-page{{/if}}"
            data-number="{{NUMBER}}">{{NUMBER}}</span><!--
    {{/each}}
</script>