<?
use Bitrix\Main\Localization\Loc;
?>
<script id="rusv-iblock-question-list-template" type="text/x-handlebars-template">
    {{#each UNITS}}
    <div class="rusv-list-unit rusv-question-unit" data-id="{{ID}}">
        <div class="rusv-unit-title rusv-question-unit-theme">
            <span class="rusv-unit-title-value rusv-question-unit-theme-value">{{PROPERTIES.<?=INFS_IB_QUESTION_PR_THEME?>}}</span>
        </div>
        <div class="rusv-unit-subtitle rusv-question-unit-name">
            <span class="rusv-question-unit-name-value">{{NAME}}</span>
        </div>
        <div class="rusv-unit-text rusv-question-unit-text">
            <span class="rusv-unit-text-value rusv-question-unit-text-value">{{{DETAIL_TEXT}}}</span>
            {{#if MORE_DETAIL_TEXT}}
            <span class="rusv-unit-text-more rusv-question-unit-text-more">
                <span class="rusv-unit-text-more-value rusv-question-unit-text-more-value">
                    <?=Loc::getMessage('QUESTION_UNIT_TEXT_MORE')?>
                </span>
            </span>
            {{/if}}
        </div>
    </div>
    {{/each}}
</script>

<div class="rusv-add-new-question-popup rusv-modal-body rusv-hidden">
    <div class="rusv-modal-area rusv-add-new-question-theme">
        <input type="text"
            name="new-question-theme"
            class="rusv-input rusv-modal-input rusv-add-new-question-input rusv-add-new-question-theme-input"
            placeholder="<?=Loc::getMessage('NEW_QUESTION_THEME')?>">
    </div>
    <div class="rusv-modal-area rusv-add-new-question-name">
        <input type="text"
            name="new-question-name"
            class="rusv-input rusv-modal-input rusv-add-new-question-input rusv-add-new-question-name-input"
            placeholder="<?=Loc::getMessage('NEW_QUESTION_NAME')?>">
    </div>
    <div class="rusv-modal-area rusv-add-new-question-text">
        <textarea
            name="new-question-text"
            class="rusv-textarea rusv-modal-textarea rusv-add-new-question-textarea"
            placeholder="<?=Loc::getMessage('NEW_QUESTION_TEXT')?>"></textarea>
    </div>
    <div class="rusv-modal-area rusv-add-new-question-buttons">
        <span class="rusv-button rusv-modal-button rusv-iblock-add-unit-button">
            <?=Loc::getMessage('ADD_QUESTION_BUTTON_TITLE')?>
        </span>
    </div>
</div>