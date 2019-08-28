<?
// $this->IncludeLangFile('form'".php");
use Bitrix\Main\Localization\Loc;

$params = $APPLICATION->IncludeComponent(
    'bitrix:voting.form', '.default',
    [
        'VOTE_ID' => $arResult['VOTE_ID'],
        'VOTE_ASK_CAPTCHA' => $arParams['VOTE_ASK_CAPTCHA'],
        'PERMISSION' => $arParams['PERMISSION'],
        'VOTE_RESULT_TEMPLATE' => $arResult['VOTE_RESULT_TEMPLATE'],
        'ADDITIONAL_CACHE_ID' => $arResult['ADDITIONAL_CACHE_ID'],
        'UID' => $arParams['UID'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
    ],
    ($this->__component->__parent ? $this->__component->__parent : $component),
    ['HIDE_ICONS' => 'Y']
);
$this->__component->params = (is_array($params) ? $params : []) + ['uid' => $arParams['UID']];?>
<div class="bx-vote-bottom-block">
    <a
        href="javascript:void(0);"
        class="rusv-button"
        id="vote-<?=$arParams['UID']?>-act"
        onmousedown="BX.addClass(this, 'feed-add-button-press')"
        onmouseup="BX.removeClass(this,'feed-add-button-press')"><?=Loc::getMessage('VOTE_SUBMIT_BUTTON')?></a><!--
    --><a
        class="rusv-button"
        href="<?=$APPLICATION->GetCurPageParam('view_result=Y', $arParams['GET_KILL'])?>"
        id="vote-<?=$arParams['UID']?>-results" ><?=Loc::getMessage('VOTE_RESULTS')?></a>
</div>
