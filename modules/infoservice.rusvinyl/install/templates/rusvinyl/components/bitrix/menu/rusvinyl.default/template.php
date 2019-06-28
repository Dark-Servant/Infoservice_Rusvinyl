<?
if (!defined('B_PROLOG_INCLUDED') || (B_PROLOG_INCLUDED !== true)) die();?>
<ul class="left-menu rusv-main-menu"><?
foreach ($arResult as $arItem):
    if (($arParams['MAX_LEVEL'] == 1) && ($arItem['DEPTH_LEVEL'] > 1)) continue;?>

    <li class="rusv-main-menu-unit rusv-main-menu-<?=$arItem['PARAMS']['CODE']?>">
        <a href="<?=$arItem['LINK']?>"<?
            if ($arItem['SELECTED']):?>
                class="selected"<?
            endif;?>><?=$arItem['TEXT']?></a>
    </li><?
endforeach?>
</ul>