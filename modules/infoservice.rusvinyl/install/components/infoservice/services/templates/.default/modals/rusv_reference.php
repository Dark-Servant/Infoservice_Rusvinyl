<?
use Bitrix\Main\Localization\Loc;?>

<div class="rusv-reference-popup rusv-modal-body rusv-hidden">
    <div class="rusv-modal-area rusv-reference-salary">
        <span class="rusv-modal-title rusv-reference-title rusv-reference-salary-title">
            <?=Loc::getMessage('REFERENCE_SALARY_TITLE')?>
        </span>
        <select class="rusv-select rusv-modal-select rusv-reference-select rusv-reference-salary-select"
            name="new-reference-salary"><?
            foreach ($arResult['IBLOCK'][INFS_IBLOCK_REFERENCE]['PROPERTIES'][INFS_IB_REFERENCE_PR_SALARY] as $option):?>
            <option value="<?=$option['ID']?>"><?=$option['VALUE']?></option><?
            endforeach;?>
        </select>
    </div>
    <div class="rusv-modal-area rusv-service-buttons rusv-reference-buttons">
        <span class="rusv-button rusv-modal-button rusv-add-service-button rusv-add-reference-button"
            data-service-code="<?=INFS_RUSVINYL_IBLOCK_PREFIX?>reference"><?=Loc::getMessage('ADD_REFERENCE_BUTTON_TITLE')?></span>
    </div>
</div>