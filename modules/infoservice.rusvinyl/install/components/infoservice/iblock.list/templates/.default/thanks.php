<?
use Bitrix\Main\Localization\Loc;
?>
<script id="rusv-iblock-thanks-list-template" type="text/x-handlebars-template">
    {{#each UNITS}}
    <div class="rusv-list-unit rusv-thanks-unit" data-id="{{ID}}">
        <div class="rusv-unit-title rusv-thanks-unit-title">
            <span class="rusv-unit-title-value rusv-thanks-unit-title-value">{{NAME}}</span>
        </div>
        <div class="rusv-unit-subtitle rusv-thanks-unit-user"><!--
            {{#each PROPERTIES.RECIPIENT}}
            --><a class="rusv-thanks-unit-user-link"
                href="<?=str_replace('#ID#', '{{ID}}', INFS_USER_LINK)?>">{{FULL_NAME}}</a><!--
            {{/each}}
        --></div>
        <div class="rusv-unit-text rusv-thanks-unit-text">
            <span class="rusv-unit-text-value rusv-thanks-unit-text-value">{{{DETAIL_TEXT}}}</span>
            {{#if DETAIL_PICTURE}}
            <div class="rusv-unit-text-image rusv-thanks-unit-text-image">
                <img class="rusv-unit-text-image-src rusv-thanks-unit-text-image-src" src="{{DETAIL_PICTURE}}">
            </div>
            {{/if}}
            {{#if MORE_DETAIL_TEXT}}
            <span class="rusv-unit-text-more rusv-thanks-unit-text-more">
                <span class="rusv-unit-text-more-value rusv-thanks-unit-text-more-value">
                    <?=Loc::getMessage('THANKS_UNIT_TEXT_MORE')?>
                </span>
            </span>
            {{/if}}
        </div>
    </div>
    {{/each}}
</script>

<div class="rusv-add-new-thanks-popup rusv-modal-body rusv-hidden">
    <div class="rusv-modal-area rusv-add-new-thanks-name">
        <input type="text"
            name="new-thanks-name"
            class="rusv-input rusv-modal-input rusv-add-new-thanks-input rusv-add-new-thanks-name-input"
            placeholder="<?=Loc::getMessage('NEW_THANKS_NAME')?>">
    </div>
    <div class="rusv-modal-area rusv-add-new-thanks-user"><?
            $APPLICATION->IncludeComponent(
                'bitrix:main.user.selector', '',
                [
                    'INPUT_NAME' => 'new-thanks-user[]',
                    'BUTTON_SELECT_CAPTION' => Loc::getMessage('NEW_THANKS_USER')
                ]
            );?>
    </div>
    <div class="rusv-modal-area rusv-add-new-thanks-text">
        <textarea
            name="new-thanks-text"
            class="rusv-textarea rusv-modal-textarea rusv-add-new-thanks-textarea"
            placeholder="<?=Loc::getMessage('NEW_THANKS_TEXT')?>"></textarea>
    </div>
    <div class="rusv-modal-area rusv-modal-control-table rusv-add-new-thanks-control">
        <div class="rusv-add-new-thanks-file">
            <label>
                <input type="file" name="new-thanks-file" class="rusv-modal-file-input rusv-add-new-thanks-file-input">
                <span class="rusv-modal-file-link rusv-add-new-thanks-file-link">
                    <span class="rusv-modal-file-link-name rusv-add-new-thanks-file-link-name"></span>
                    <span class="rusv-modal-file-link-value rusv-add-new-thanks-file-link-value">
                        <?=Loc::getMessage('NEW_THANKS_FILE_LINK')?>
                    </span>
                </span>
            </label>
        </div>
        <div class="rusv-add-new-thanks-buttons">
            <span class="rusv-button rusv-modal-button rusv-iblock-add-unit-button"><?=Loc::getMessage('ADD_THANKS_BUTTON_TITLE')?></span>
        </div>
    </div>
</div>