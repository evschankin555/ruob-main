<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arParams["USE_RSS"]=="Y"):?>
	<?
		if(method_exists($APPLICATION, 'addheadstring'))
		$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" title="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" href="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" />');
	?>
	<a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"]?>" title="RSS" class="rss_feed_icon"><?=GetMessage("RSS_TITLE")?></a>
<?endif;?>

<?if ($arParams["SHOW_FAQ_BLOCK"]=="Y"):?>
	<div class="right_side wide">
		<div class="ask_small_block">
			<div class="ask_btn_block">
				<a class="button vbig_btn wides ask_btn"><span><?=GetMessage("ASK_QUESTION")?></span></a>
			</div>
			<div class="description">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/ask_block_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("ASK_QUESTION_TEXT"), ));?>
			</div>
		</div>
	</div>
	<div class="left_side wide content_detail_faq">
<?endif;?>
<?
if($arResult["VARIABLES"]["ELEMENT_ID"] > 0){
	$arElement = COptimusCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => COptimusCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "ID" => $arResult["VARIABLES"]["ELEMENT_ID"]), false, false, array("ID", "IBLOCK_SECTION_ID", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "ACTIVE_FROM"));
}
elseif(strlen(trim($arResult["VARIABLES"]["ELEMENT_CODE"])) > 0){
	$arElement = COptimusCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => COptimusCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "=CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]), false, false, array("ID", "IBLOCK_SECTION_ID", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "ACTIVE_FROM"));
}
?>
<?if($arParams["USE_FILTER"]=="Y"){
	if(isset($arResult["VARIABLES"]["YEAR"]))
	{
		if($arElement["ACTIVE_FROM"])
		{
			if($arDateTime = ParseDateTime($arElement['ACTIVE_FROM'], FORMAT_DATETIME))
    		{
		        if($arDateTime['YYYY'] != $arResult["VARIABLES"]["YEAR"])
		        {
		        	echo GetMessage('ELEMENT_NOT_FOUND');
					CHTTP::SetStatus(404);
					return;
		        }
    		}
		}
	}
}?>

<?COptimus::AddMeta(
	array(
		'og:description' => $arElement['PREVIEW_TEXT'],
		'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
	)
);?>
<?$bUseShare = ($arParams["USE_SHARE"] == "Y" && $arElement);?>
<div class="content_wr_float <?=($bUseShare ? 'with-share' : '');?>">
<?$APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"main_template",
	Array(
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"SHOW_SERVICES_BLOCK" => $arParams["SHOW_SERVICES_BLOCK"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"META_KEYWORDS" => $arParams["META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"LINKED_ELEMENST_PAGE_COUNT" => ($arParams["LINKED_ELEMENST_PAGE_COUNT"] ? $arParams["LINKED_ELEMENST_PAGE_COUNT"] : 20),
		"FILE_404" => $arParams["FILE_404"],
		"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
		"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
		"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
		"LIST_VIEW" => $arParams["LIST_VIEW"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
		"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"USE_SHARE" 			=> $arParams["USE_SHARE"],
		"SHARE_HIDE" 			=> $arParams["SHARE_HIDE"],
		"SHARE_TEMPLATE" 		=> $arParams["SHARE_TEMPLATE"],
		"SHARE_HANDLERS" 		=> $arParams["SHARE_HANDLERS"],
		"SHARE_SHORTEN_URL_LOGIN"	=> $arParams["SHARE_SHORTEN_URL_LOGIN"],
		"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
		"IBLOCK_CATALOG_TYPE" => $arParams["IBLOCK_CATALOG_TYPE"],
		"CATALOG_IBLOCK_ID1" => $arParams["CATALOG_IBLOCK_ID1"],
		"CATALOG_IBLOCK_ID2" => $arParams["CATALOG_IBLOCK_ID2"],
		"CATALOG_IBLOCK_ID3" => $arParams["CATALOG_IBLOCK_ID3"],
		"CATALOG_IBLOCK_ID4" => $arParams["CATALOG_IBLOCK_ID4"],
		"PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
		"SHOW_BUY_BUTTONS" => $arParams["SHOW_BUY_BUTTONS"],
		"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
		"CATALOG_FILTER_NAME" => $arParams["CATALOG_FILTER_NAME"],
		"SHOW_FAQ_BLOCK" => $arParams["SHOW_FAQ_BLOCK"],
		"SHOW_BACK_LINK" => $arParams["SHOW_BACK_LINK"],
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"GALLERY_PROPERTY" => $arParams["GALLERY_PROPERTY"],
		"SHOW_GALLERY" => $arParams["SHOW_GALLERY"],
		"LINKED_PRODUCTS_PROPERTY" => $arParams["LINKED_PRODUCTS_PROPERTY"],
		"SHOW_LINKED_PRODUCTS" => $arParams["SHOW_LINKED_PRODUCTS"],
		"PRICE_PROPERTY" => $arParams["PRICE_PROPERTY"],
		"SHOW_PRICE" => $arParams["SHOW_PRICE"],
		"PERIOD_PROPERTY" => $arParams["PERIOD_PROPERTY"],
		"SHOW_ITEM_SECTION" => $arParams["SHOW_ITEM_SECTION"],
		"DEPTH_LEVEL_BRAND" => $arParams["DEPTH_LEVEL_BRAND"],
		"SHOW_PERIOD" => $arParams["SHOW_PERIOD"],
		'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : ''),
		'PRICE_CODE' => $arParams["PRICE_CODE"],
		'PRICE_VAT_INCLUDE' => $arParams["PRICE_VAT_INCLUDE"],
		'USE_PRICE_COUNT' => $arParams["USE_PRICE_COUNT"],
		'CONVERT_CURRENCY' => $arParams["CONVERT_CURRENCY"],
		'CURRENCY_ID' => $arParams["CURRENCY_ID"],
		'STORES' => $arParams["STORES"],
		'SHOW_DISCOUNT_PERCENT' => $arParams["SHOW_DISCOUNT_PERCENT"],
		'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
		'SHOW_OLD_PRICE' => $arParams["SHOW_OLD_PRICE"],
		'SHOW_MEASURE' => $arParams["SHOW_MEASURE"],
		"LINKED_MODE" => true,
	),
	$component
);?>
<?if($bUseShare):?>
	<div class="catalog_detail">
		<?$APPLICATION->IncludeFile(SITE_DIR."include/share_buttons.php", Array(), Array("MODE" => "html", "NAME" => 'SOCIAL'));?>
	</div>
<?endif;?>
</div>
<?if ($arParams["SHOW_FAQ_BLOCK"]=="Y"):?></div><?endif;?>