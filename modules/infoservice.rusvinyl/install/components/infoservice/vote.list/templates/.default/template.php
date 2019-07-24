<?
use Bitrix\Main\Localization\Loc;?>
<div class="rusv-vote-list rusv-unit-list"
    data-page-size="<?=$arResult['CHANNEL']['PAGE_SIZE']?>"
    data-id="<?=$arResult['CHANNEL']['ID']?>"></div>
<div class="rusv-vote-pages rusv-unit-pages"></div>

<script id="rusv-vote-list-template" type="text/x-handlebars-template">
    {{#each UNITS}}
    <div class="rusv-list-unit rusv-vote-unit" data-id="{{ID}}">
        <div class="rusv-unit-title rusv-vote-unit-title">
            <a href="<?=$arResult['CHANNEL']['DETAIL_URL']?>" class="rusv-unit-title-value rusv-vote-unit-title-value">{{NAME}}</a>
        </div>
        <div class="rusv-unit-subtitle rusv-vote-unit-counter">
            <span class="rusv-vote-unit-counter-value"><?=Loc::getMessage('VOTE_UNIT_COUNTER_TITLE')?></span>
        </div>
        <div class="rusv-unit-text rusv-vote-unit-text">
            <span class="rusv-unit-text-value rusv-vote-unit-text-value">{{{DETAIL_TEXT}}}</span>
            {{#if DETAIL_PICTURE}}
            <div class="rusv-unit-text-image rusv-vote-unit-text-image">
                <img class="rusv-unit-text-image-src rusv-vote-unit-text-image-src" src="{{DETAIL_PICTURE}}">
            </div>
            {{/if}}
            {{#if MORE_DETAIL_TEXT}}
            <span class="rusv-unit-text-more rusv-vote-unit-text-more">
                <span class="rusv-unit-text-more-value rusv-vote-unit-text-more-value">
                    <?=Loc::getMessage('VOTE_UNIT_TEXT_MORE')?>
                </span>
            </span>
            {{/if}}
        </div>
    </div>
    {{/each}}
</script><?

require $_SERVER['DOCUMENT_ROOT'] . '/local/templates/rusvinyl/helpers/list+pages/templates.php';