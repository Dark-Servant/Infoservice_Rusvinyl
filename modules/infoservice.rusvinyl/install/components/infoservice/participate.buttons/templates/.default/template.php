<?
use Bitrix\Main\Localization\Loc;

if ($arResult['currentUserId']):?>
<div class="rusv-participate-buttons" data-element-id="<?=$arParams['ELEMENT_ID']?>"><?
    if ($arResult['STATUS'] === false):?>
    <span class="rusv-participate-add-button rusv-participate-button rusv-button">
        <?=Loc::getMessage('PARTICIPATE_BUTTON_TITLE')?>
    </span><?
    endif;?>
    <span class="rusv-participate-status"><?=$arResult['STATUS']?></span>
</div><?
endif;?>