<?
use Bitrix\Main\Localization\Loc;?>

<div class="rusv-servicedesk-popup rusv-modal-body rusv-hidden">
    <div class="rusv-modal-area rusv-servicedesk-text">
        <textarea name="new-servicedesk-text"
            placeholder="<?=Loc::getMessage('SERVICEDESK_TEXT_PLACEHOLDER')?>"
            class="rusv-textarea rusv-modal-textarea rusv-servicedesk-textarea rusv-servicedesk-text-input"></textarea>
    </div>
    <div class="rusv-modal-area rusv-service-buttons rusv-servicedesk-buttons">
        <span class="rusv-button rusv-modal-button rusv-add-service-button rusv-add-servicedesk-button"
            data-service-code="<?=INFS_RUSVINYL_IBLOCK_PREFIX?>servicedesk"><?=Loc::getMessage('ADD_SERVICEDESK_BUTTON_TITLE')?></span>
    </div>
</div>