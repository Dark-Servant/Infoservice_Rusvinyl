<?
if (!defined('B_PROLOG_INCLUDED') || (B_PROLOG_INCLUDED !== true)) die();?>
<div class="left-menu rusv-main-menu"><?
foreach ($arResult as $arItem):
    if (($arParams['MAX_LEVEL'] == 1) && ($arItem['DEPTH_LEVEL'] > 1)) continue;?>
    <a class="rusv-main-menu-unit rusv-main-menu-<?=$arItem['PARAMS']['CODE']?><?=
        $arItem['SELECTED'] ? ' selected' : ''  ?>"
        href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a><?
endforeach?>
</div>