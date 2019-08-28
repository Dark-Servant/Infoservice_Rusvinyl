<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("404 Not Found");

?><img src="<?=SITE_TEMPLATE_PATH?>/images/cap.png?<?=INFS_CURRENT_TIMESTAMP?>"><?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>