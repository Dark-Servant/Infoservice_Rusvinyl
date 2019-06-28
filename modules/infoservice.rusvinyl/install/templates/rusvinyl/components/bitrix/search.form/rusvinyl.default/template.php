<?
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();?>
<div class="rusv-search-layer">
    <form class="rusv-search-form" action="<?=$arResult['FORM_ACTION']?>">
        <input name="s" type="hidden" value="1">
        <span class="rusv-search-form-title"><?=Loc::getMessage('BSF_T_SEARCH_BUTTON')?></span>
        <input class="rusv-search-form-input" type="text" name="q" value="" size="15" maxlength="50"
            placeholder="<?=Loc::getMessage('BSF_T_SEARCH_BUTTON')?>">
        <span class="rusv-search-form-button">
            <img src="<?=SITE_TEMPLATE_PATH?>/images/search.svg?<?=time()?>">
        </span>
    </form>
</div>