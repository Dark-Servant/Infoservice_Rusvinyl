<?
use Bitrix\Main\Localization\Loc;?>
<div class="rusv-2-pit-popup rusv-modal-body rusv-hidden">
    <div class="rusv-modal-main-title rusv-2-pit-main-title">
        <span class="rusv-modal-main-title-value rusv-2-pit-main-title-value">
            <?=Loc::getMessage('2_PIT_MAIN_TITLE')?>
        </span>
    </div>
    <div class="rusv-modal-area rusv-2-pit-period">
        <span class="rusv-modal-title rusv-2-pit-title"><?=Loc::getMessage('2_PIT_PERIOD_TITLE')?></span>
        <input type="text" readonly
            class="rusv-input rusv-modal-input rusv-datetime-input rusv-modal-datetime rusv-2-pit-datetime"
            name="new-2-pit-from"
            placeholder="<?=Loc::getMessage('2_PIT_PERIOD_FROM')?>">
        <input type="text" readonly
            class="rusv-input rusv-modal-input rusv-datetime-input rusv-modal-datetime rusv-2-pit-datetime"
            name="new-2-pit-to"
            placeholder="<?=Loc::getMessage('2_PIT_PERIOD_TO')?>">
    </div>
    <div class="rusv-modal-area rusv-service-buttons rusv-2-pit-buttons">
        <span class="rusv-button rusv-modal-button rusv-add-service-button rusv-add-2-pit-button"
            data-service-code="<?=INFS_RUSVINYL_IBLOCK_PREFIX?>2_pit"><?=Loc::getMessage('ADD_2_PIT_BUTTON_TITLE')?></span>
    </div>
</div>