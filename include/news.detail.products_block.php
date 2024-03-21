<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"catalog_block", 
	array(
		"IBLOCK_TYPE" => "aspro_optimus_catalog",
		"IBLOCK_ID" => \Bitrix\Main\Config\Option::get("aspro.optimus", "CATALOG_IBLOCK_ID", '14'),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_COUNT" => ($arParams["LINKED_ELEMENST_PAGE_COUNT"] ? $arParams["LINKED_ELEMENST_PAGE_COUNT"] : 20),
		"LINE_ELEMENT_COUNT" => "",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_LIMIT" => "10",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"BASKET_URL" => SITE_DIR."basket/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"CACHE_GROUPS" => "N",
		"DISPLAY_COMPARE" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"PRODUCT_PROPERTIES" => array(
		),
		"CONVERT_CURRENCY" => "N",
		"FILTER_NAME" => $arParams["CATALOG_FILTER_NAME"],
		"SHOW_BUY_BUTTONS" => $arParams["SHOW_BUY_BUTTONS"],
		"USE_PRODUCT_QUANTITY" => "N",
		"INIT_SLIDER" => "Y",
		"COMPONENT_TEMPLATE" => "catalog_block",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"HIDE_NOT_AVAILABLE" => "N",
		'OFFER_TREE_PROPS' => $arParams["OFFER_TREE_PROPS"],
		'OFFERS_FIELD_CODE' => $arParams["LIST_OFFERS_FIELD_CODE"],
		'OFFERS_PROPERTY_CODE' => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		'OFFERS_CART_PROPERTIES' => $arParams["OFFERS_CART_PROPERTIES"],
		'OFFERS_SORT_FIELD' => $arParams["OFFERS_SORT_FIELD"],
		'OFFERS_SORT_ORDER' => $arParams["OFFERS_SORT_ORDER"],
		'OFFERS_SORT_FIELD2' => $arParams["OFFERS_SORT_FIELD2"],
		'OFFERS_SORT_ORDER2' => $arParams["OFFERS_SORT_ORDER2"],
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"DISPLAY_TYPE" => "block",
		"TYPE_SKU" => $arParams['LINKED_MODE'] ? 'TYPE_2' : "TYPE_1",
		"LINKED_ITEMS" =>  $arParams['LINKED_MODE'] ? true : false,
		"PAGER_TEMPLATE" => "main",
		"AJAX_REQUEST" => ($bAjax ? "Y" : "N"),
		"SEF_MODE" => "N",
		"DISPLAY_WISH_BUTTONS" => \Bitrix\Main\Config\Option::get('aspro.optimus', 'SHOW_DELAY', 'Y'),
		"CACHE_FILTER" => "Y",
		'SHOW_MEASURE' => $arParams["SHOW_MEASURE"],
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"OFFERS_CART_PROPERTIES" => array(
		),
		"COMPARE_PATH" => "",
		'SHOW_OLD_PRICE' => $arParams["SHOW_OLD_PRICE"],
		"SHOW_RATING" => "Y",
		'PRICE_CODE' => $arParams["PRICE_CODE"],
		'PRICE_VAT_INCLUDE' => $arParams["PRICE_VAT_INCLUDE"],
		'USE_PRICE_COUNT' => $arParams["USE_PRICE_COUNT"],
		'CONVERT_CURRENCY' => $arParams["CONVERT_CURRENCY"],
		'CURRENCY_ID' => $arParams["CURRENCY_ID"],
		'STORES' => $arParams["STORES"],
		'SHOW_DISCOUNT_PERCENT' => $arParams["SHOW_DISCOUNT_PERCENT"],
		'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
		"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => $arParams["OFFER_SHOW_PREVIEW_PICTURE_PROPS"],
	),
	false, array("HIDE_ICONS"=>"Y")
);?>