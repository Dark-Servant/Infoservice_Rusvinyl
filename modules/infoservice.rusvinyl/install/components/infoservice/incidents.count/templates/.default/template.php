<?
use Bitrix\Main\Localization\Loc;?>
<span class="rusv-incidents-count-value<?=($arResult['isAdmin'] ? ' rusv-editable' : '')?>"
    data-label="<?=Loc::getMessage('INCIDENT_STATIC_VALUE')?>"
    data-last-date="<?=$arResult['lastTime']?>"></span><?
if ($arResult['isAdmin']):?>
<div class="rusv-incidents-count-popup rusv-modal-body rusv-hidden">
    <div class="rusv-modal-main-title rusv-incidents-count-title">
        <span class="rusv-modal-main-title-value rusv-incidents-count-title-value">
            <?=Loc::getMessage('INCIDENTS_COUNT_TITLE')?>
        </span>
    </div>
    <div class="rusv-modal-area rusv-add-new-incidents-count">
        <input type="text"
            name="incidents-count"
            class="rusv-input rusv-numeric-input rusv-modal-input rusv-incidents-count-input"
            placeholder="<?=Loc::getMessage('INCIDENTS_COUNT_PLACEHOLDER')?>">
    </div>
    <div class="rusv-modal-area rusv-save-incidents-buttons">
        <span class="rusv-button rusv-modal-button rusv-save-incidents-count-button">
            <?=Loc::getMessage('SAVE_INCIDENTS_BUTTON_TITLE')?>
        </span>
    </div>
</div><?
endif;