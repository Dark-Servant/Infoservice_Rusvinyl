<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');?>
<?$APPLICATION->IncludeComponent('infoservice:vote.detail', '', ['VOTE_ID' => $_REQUEST['VOTE_ID']]);?>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');?>