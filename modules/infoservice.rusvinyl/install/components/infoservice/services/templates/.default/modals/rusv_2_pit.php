<?
use Bitrix\Main\Localization\Loc;?>
<div class="rusv-2-pit-popup rusv-modal-body rusv-hidden">
    <div class="rusv-modal-area rusv-2-pit-user"><?
        $APPLICATION->IncludeComponent(
            'bitrix:main.user.selector', '',
            [
                'INPUT_NAME' => 'new-2-pit-user',
                'BUTTON_SELECT_CAPTION' => Loc::getMessage('NEW_2_PIT_USER')
            ]
        );?>
    </div>
    <div class="rusv-modal-area rusv-2-pit-period">
        <span class="rusv-modal-title rusv-2-pit-title"><?=Loc::getMessage('2_PIT_PERIOD_TITLE')?></span>
        <input type="text" readonly
            class="rusv-input rusv-modal-input rusv-modal-datetime rusv-2-pit-datetime"
            name="new-2-pit-from"
            placeholder="<?=Loc::getMessage('2_PIT_PERIOD_FROM')?>"
            onclick="BX.calendar({node: this, field: this, bTime: false});">
        <input type="text" readonly
            class="rusv-input rusv-modal-input rusv-modal-datetime rusv-2-pit-datetime"
            name="new-2-pit-to"
            placeholder="<?=Loc::getMessage('2_PIT_PERIOD_TO')?>"
            onclick="BX.calendar({node: this, field: this, bTime: false});">
    </div>
    <div class="rusv-modal-area rusv-service-buttons rusv-2-pit-buttons">
        <span class="rusv-button rusv-modal-button rusv-add-service-button rusv-add-2-pit-button"
            data-service-code="<?=INFS_RUSVINYL_IBLOCK_PREFIX?>2_pit"><?=Loc::getMessage('ADD_2_PIT_BUTTON_TITLE')?></span>
    </div>
</div>