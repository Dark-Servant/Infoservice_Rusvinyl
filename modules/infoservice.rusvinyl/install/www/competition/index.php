<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?$APPLICATION->IncludeComponent(
	'infoservice:vote.list', '',
	[
		'CHANNEL_CODE' => INFS_RUSVINYL_COMPETITION_CODE,
		'PAGE_SIZE' => INFS_RUSVINYL_COMPETITION_GROUP_SIZE
	]
);?>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>