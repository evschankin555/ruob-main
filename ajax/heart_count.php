<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("iblock")){
	echo "failure";
	return;
}

\Bitrix\Main\Loader::IncludeModule('aspro.optimus');
COptimusCache::ClearCacheByTag('sale_basket');
$iblockID=(isset($_GET["iblockID"]) ? $_GET["iblockID"] : COptimusCache::$arIBlocks[SITE_ID][0] );
$arItems = COptimus::getBasketItems($iblockID);
echo (count($arItems['DELAY']));
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>