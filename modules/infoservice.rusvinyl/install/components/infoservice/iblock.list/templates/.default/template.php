<?
use Bitrix\Main\Localization\Loc;

$buttonTitle = Loc::getMessage($arResult['MAINDATA']['BUTTON_TITLE_CODE']);
if ($arResult['CURRENT_USER_ID'] && !empty($buttonTitle)):?>
<div class="rusv-iblock-list-control-buttons">
    <span class="rusv-button rusv-iblock-new-unit-button"><?=$buttonTitle?></span>
</div><?
endif;?>
<div
	class="rusv-iblock-list"
	data-iblock-code="<?=$arResult['MAINDATA']['CODE']?>"
	data-main-data="<?=htmlspecialchars(json_encode($arResult['MAINDATA']))?>"></div>
<div class="rusv-iblock-pages"></div><?

require $arResult['IBLOCK_TEMPLATE_FILE'];?>

<script id="rusv-iblock-list-pages-template" type="text/x-handlebars-template">
    {{#each PAGES}}
    --><span
            class="rusv-iblock-list-page{{#if CURRENT}} rusv-iblock-list-current-page{{/if}}"
            data-number="{{NUMBER}}">{{NUMBER}}</span><!--
    {{/each}}
</script>