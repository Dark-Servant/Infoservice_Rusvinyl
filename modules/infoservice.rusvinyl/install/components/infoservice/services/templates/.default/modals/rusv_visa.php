<?
use Bitrix\Main\Localization\Loc;?>

<div class="rusv-visa-popup rusv-modal-body rusv-hidden">
    <div class="rusv-modal-area rusv-visa-country">
        <input type="text" name="new-visa-country"
            class="rusv-input rusv-modal-input rusv-visa-input rusv-visa-country-input"
            placeholder="<?=Loc::getMessage('VISA_COUNTRY_PLACEHOLDER')?>">
    </div>
    <div class="rusv-modal-area rusv-visa-date">
        <input type="text" readonly
            class="rusv-input rusv-modal-input rusv-modal-datetime rusv-visa-date-input"
            name="new-visa-date"
            placeholder="<?=Loc::getMessage('VISA_DATE_PLACEHOLDER')?>"
            onclick="BX.calendar({node: this, field: this, bTime: false});">
    </div>
    <div class="rusv-modal-area rusv-visa-purpoise">
        <select name="new-visa-purpoise"
            class="rusv-select rusv-modal-select rusv-visa-select rusv-visa-purpoise-select">
            <option value=""><?=Loc::getMessage('VISA_PURPOISE_NULL_VALUE')?></option><?
            foreach ($arResult['IBLOCK'][INFS_IBLOCK_VISA]['PROPERTIES'][INFS_IB_VISA_PR_PURPOISE] as $option):?>
            <option value="<?=$option['ID']?>"><?=$option['VALUE']?></option><?
            endforeach;?>
        </select>
    </div>
    <div class="rusv-modal-area rusv-visa-passport">
        <input type="text" name="new-visa-passport"
            class="rusv-input rusv-modal-input rusv-visa-input rusv-visa-passport-input"
            placeholder="<?=Loc::getMessage('VISA_PASSPORT_PLACEHOLDER')?>">
    </div>
    <div class="rusv-modal-area rusv-visa-language">
        <select class="rusv-select rusv-modal-select rusv-visa-select rusv-visa-language-select"
            name="new-visa-language">
            <option value=""><?=Loc::getMessage('VISA_LANGUAGE_NULL_VALUE')?></option><?
            foreach ($arResult['IBLOCK'][INFS_IBLOCK_VISA]['PROPERTIES'][INFS_IB_VISA_PR_LANGUAGE] as $option):?>
            <option value="<?=$option['ID']?>"><?=$option['VALUE']?></option><?
            endforeach;?>
        </select>
    </div>
    <div class="rusv-modal-area rusv-service-buttons rusv-visa-buttons">
        <span class="rusv-button rusv-modal-button rusv-add-service-button rusv-add-visa-button"
            data-service-code="<?=INFS_RUSVINYL_IBLOCK_PREFIX?>visa"><?=Loc::getMessage('ADD_VISA_BUTTON_TITLE')?></span>
    </div>
</div>