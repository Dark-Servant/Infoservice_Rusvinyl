<?
use Bitrix\Main\Localization\Loc;
foreach ($arResult['IBLOCK'] as $iblockCode => $iblockData) {
    $fileName = __DIR__ . '/modals/' . $iblockCode . '.php';
    if (file_exists($fileName)) require $fileName;
}?>
<script type="text/javascript">
	var serviceOptions = <?=json_encode($arResult['IBLOCK'])?>;
</script>