<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!\Bitrix\Main\Loader::includeModule('sale') ||
	!\Bitrix\Main\Loader::includeModule('catalog') ||
	!\Bitrix\Main\Loader::includeModule('iblock') ||
	!\Bitrix\Main\Loader::includeModule('aspro.optimus')) {
	echo "failure";
	return;
}

COptimusCache::ClearCacheByTag('sale_basket');
$iblockID = isset($_GET["iblockID"]) ? $_GET["iblockID"] : COptimusCache::$arIBlocks[SITE_ID]["aspro_optimus_catalog"]["IBLOCK_ID"];
$arItems = COptimus::getBasketItems($iblockID);
echo count($arItems['BASKET']);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>
