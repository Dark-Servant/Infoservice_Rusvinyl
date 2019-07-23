<?
use Bitrix\Main\Localization\Loc;

$arResult['IBLOCK_TEMPLATE_FILE'] = __DIR__ . '/' . $arResult['MAINDATA']['CODE'] . '.php';

if (!file_exists($arResult['IBLOCK_TEMPLATE_FILE']))
	throw new Exception(
		Loc::getMessage(
			'ERROR_IBLOCK_TEMPLATE_FILE_EXISTS',
			[
				'#CODE#' => $arResult['MAINDATA']['CODE'],
				'#ID#' => $arResult['MAINDATA']['IBLOCK']['NAME']
			]
		)
	);
