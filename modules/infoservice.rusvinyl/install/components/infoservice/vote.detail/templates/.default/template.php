<?
use Bitrix\Main\Localization\Loc;?>
<div class="rusv-back-url">
    <a class="rusv-back-url-link" href="<?=$arResult['CHANNEL']['LIST_URL']?>"><?=
        Loc::getMessage('BACK_URL_LINK_TITLE', ['NAME' => $arResult['CHANNEL']['TITLE']])?></a>
</div>
<div class="rusv-vote-unit"><?
$APPLICATION->IncludeComponent(
    'bitrix:voting.current', 'rusvinyl.userfield',
    [
        'AJAX_MODE' => 'N',
        'AJAX_OPTION_ADDITIONAL' => '',
        'AJAX_OPTION_HISTORY' => 'N',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_STYLE' => 'Y',
        'CACHE_TIME' => '3600',
        'CACHE_TYPE' => 'A',
        'CHANNEL_SID' => $arResult['CHANNEL']['SYMBOLIC_NAME'],
        'VOTE_ALL_RESULTS' => 'Y',
        'CAN_VOTE' => 'Y',
        'CAN_REVOTE' => 'Y',
        'VOTE_ID' => $arResult['VOTE']['ID']
    ]
);?>
</div>
