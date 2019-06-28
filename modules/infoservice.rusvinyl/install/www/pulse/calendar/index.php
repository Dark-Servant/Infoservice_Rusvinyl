<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("");?><?$APPLICATION->IncludeComponent(
	"bitrix:calendar.grid",
	"",
	Array(
		"ALLOW_RES_MEETING" => "Y",
		"ALLOW_SUPERPOSE" => "Y",
		"CALENDAR_TYPE" => "events"
	)
);?><?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>