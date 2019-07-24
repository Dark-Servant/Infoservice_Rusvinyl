<?
use Bitrix\Main\Localization\Loc;

$buttonTitle = Loc::getMessage($arResult['MAINDATA']['BUTTON_TITLE_CODE']);
if ($arResult['CURRENT_USER_ID'] && !empty($buttonTitle)):?>
<div class="rusv-iblock-list-control-buttons">
    <span class="rusv-button rusv-iblock-new-unit-button"><?=$buttonTitle?></span>
</div><?
endif;?>
<div
	class="rusv-iblock-list rusv-unit-list"
	data-iblock-code="<?=$arResult['MAINDATA']['CODE']?>"
	data-main-data="<?=htmlspecialchars(json_encode($arResult['MAINDATA']))?>"></div>
<div class="rusv-iblock-pages rusv-unit-pages"></div><?

require $arResult['IBLOCK_TEMPLATE_FILE'];
require $_SERVER['DOCUMENT_ROOT'] . '/local/templates/rusvinyl/helpers/list+pages/templates.php';
?>