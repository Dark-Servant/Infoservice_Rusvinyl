<?
use Bitrix\Main\Localization\Loc;?>

<div class="rusv-servicerecord-popup rusv-modal-body rusv-hidden">
    <div class="rusv-modal-area rusv-servicerecord-count">
        <input type="text"
            class="rusv-input rusv-modal-input rusv-servicerecord-input rusv-servicerecord-count-input"
            name="new-servicerecord-count"
            placeholder="<?=Loc::getMessage('SERVICERECORD_COUNT_PLACEHOLDER')?>">
    </div>
    <div class="rusv-modal-area rusv-service-buttons rusv-servicerecord-buttons">
        <span class="rusv-button rusv-modal-button rusv-add-service-button rusv-add-servicerecord-button"
            data-service-code="<?=INFS_RUSVINYL_IBLOCK_PREFIX?>servicerecord"><?=Loc::getMessage('ADD_SERVICERECORD_BUTTON_TITLE')?></span>
    </div>
</div>