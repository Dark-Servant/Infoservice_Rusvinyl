<?
use Bitrix\Main\Localization\Loc;
?>
<script id="rusv-iblock-employee-announce-list-template" type="text/x-handlebars-template">
    {{#each UNITS}}
    <div class="rusv-list-unit rusv-employee-announce-unit" data-id="{{ID}}">
        <div class="rusv-unit-title rusv-employee-announce-unit-theme">
            <span class="rusv-unit-title-value rusv-employee-announce-unit-theme-value">{{PROPERTIES.<?=INFS_IB_EMPLOYEE_ANNOUNCE_PR_THEME?>}}</span>
        </div>
        <div class="rusv-unit-subtitle rusv-employee-announce-unit-name">
            <span class="rusv-employee-announce-unit-name-value">{{NAME}}</span>
        </div>
        <div class="rusv-unit-subtitle rusv-employee-announce-unit-full-name">
            <a class="rusv-employee-announce-unit-full-name-link"
                href="<?=str_replace('#ID#', '{{CREATED_BY}}', INFS_USER_LINK)?>">{{FULL_NAME}}</a>
        </div>
        <div class="rusv-unit-text rusv-employee-announce-unit-text">
            <span class="rusv-unit-text-value rusv-employee-announce-unit-text-value">{{{DETAIL_TEXT}}}</span>
            {{#if MORE_DETAIL_TEXT}}
            <span class="rusv-unit-text-more rusv-employee-announce-unit-text-more">
                <span class="rusv-unit-text-more-value rusv-employee-announce-unit-text-more-value">
                    <?=Loc::getMessage('EMPLOYEE_ANNOUNCE_UNIT_TEXT_MORE')?>
                </span>
            </span>
            {{/if}}
        </div>
    </div>
    {{/each}}
</script>

<div class="rusv-add-new-employee_announce-popup rusv-modal-body rusv-hidden">
    <div class="rusv-modal-area rusv-add-new-employee-announce-theme">
        <input type="text"
            name="new-employee-announce-theme"
            class="rusv-input rusv-modal-input rusv-add-new-employee-announce-input rusv-add-new-employee-announce-theme-input"
            placeholder="<?=Loc::getMessage('NEW_EMPLOYEE_ANNOUNCE_THEME')?>">
    </div>
    <div class="rusv-modal-area rusv-add-new-employee-announce-name">
        <input type="text"
            name="new-employee-announce-name"
            class="rusv-input rusv-modal-input rusv-add-new-employee-announce-input rusv-add-new-employee-announce-name-input"
            placeholder="<?=Loc::getMessage('NEW_EMPLOYEE_ANNOUNCE_NAME')?>">
    </div>
    <div class="rusv-modal-area rusv-add-new-employee-announce-text">
        <textarea
            name="new-employee-announce-text"
            class="rusv-textarea rusv-modal-textarea rusv-add-new-employee-announce-textarea"
            placeholder="<?=Loc::getMessage('NEW_EMPLOYEE_ANNOUNCE_TEXT')?>"></textarea>
    </div>
    <div class="rusv-modal-area rusv-add-new-employee-announce-buttons">
        <span class="rusv-button rusv-modal-button rusv-iblock-add-unit-button">
            <?=Loc::getMessage('ADD_EMPLOYEE_ANNOUNCE_BUTTON_TITLE')?>
        </span>
    </div>
</div>