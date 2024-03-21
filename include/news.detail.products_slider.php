<?

	//echo "<p>templateData['CODE'] = " . $templateData["CODE"] . "</p><br />";
	//echo "<p>arResult['NAME'] = " . $arResult["NAME"] . "</p><br />";

	// Создание массива всех активных товаров
	$arSelect = Array(
	    "ID", 
	    "IBLOCK_ID",
	    "NAME", 
	    "DATE_ACTIVE_FROM",
	    "PROPERTY_CML2_MANUFACTURER",
	);
	$arFilter = Array("IBLOCK_ID"=>14, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(
		Array(),
		$arFilter,
		false,
		Array(),
		$arSelect
	);

	// Массив с товарами, имеющий затребованные параметры
	$arProd = array();
	$ind = 0;
	while($ob = $res->GetNextElement()){
		$arFields[$ind] = $ob->GetFields();
		$arProd[] = $arFields[$ind];
	}

	// Вывод товаров IBLOCK_ID=>14 с кодом группы
	$arr = $arProd;
	$schet = 0;
	$arrGroup = array();
	foreach ($arr as $key => $value) {
		//if($schet > 100){
			//if ($schet > 105){
			//	break;
			//}
			if($value["PROPERTY_CML2_MANUFACTURER_VALUE"] == $arResult["NAME"]){
			/*
				echo "<br />{$key} => {$value["NAME"]}<br />";
				echo "Товар[{$value["ID"]}]<br />";
				echo "IBLOCK_SECTION_ID[{$value["IBLOCK_SECTION_ID"]}]<br />";
				echo "SECTION_ID[{$value["SECTION_ID"]}]<br />";
				echo "SECTION_CODE[{$value["SECTION_CODE"]}]<br />";
				echo "SUBSECTION[{$value["SUBSECTION"]}]<br />";
				echo "PROPERTY_CML2_MANUFACTURER_VALUE[{$value["PROPERTY_CML2_MANUFACTURER_VALUE"]}]<br />";
			*/
				$arrGroup[] = $value["ID"];
			}
		//}
		$schet++;
	}
	$resultarrGroup = array_unique($arrGroup);

	$GLOBALS['arBrandSections'] = array(
	    //"ID" => [2902, 2899]
	    "ID" => $resultarrGroup
	);

	$APPLICATION->IncludeComponent(
		"bitrix:catalog.top", 
		"products_slider", 
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
			"PRICE_CODE" => array(
				0 => "BASE",
			),
			"USE_PRICE_COUNT" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_PROPERTIES" => array(
			),
			"CONVERT_CURRENCY" => "N",
			/*"FILTER_NAME" => $arParams["CATALOG_FILTER_NAME"],*/
			"FILTER_NAME" => "arBrandSections",
			"SHOW_BUY_BUTTONS" => $arParams["SHOW_BUY_BUTTONS"],
			"USE_PRODUCT_QUANTITY" => "N",
			"INIT_SLIDER" => "Y",
			"COMPONENT_TEMPLATE" => "products_slider",
			"ELEMENT_SORT_FIELD2" => "id",
			"ELEMENT_SORT_ORDER2" => "desc",
			"HIDE_NOT_AVAILABLE" => "N",
			"OFFERS_FIELD_CODE" => array(
				0 => "ID",
				1 => "NAME",
				2 => "",
			),
			"OFFERS_PROPERTY_CODE" => array(
				0 => "",
				1 => "",
			),
			"OFFERS_SORT_FIELD" => "sort",
			"OFFERS_SORT_ORDER" => "asc",
			"OFFERS_SORT_FIELD2" => "id",
			"OFFERS_SORT_ORDER2" => "desc",
			"SEF_MODE" => "N",
			"CACHE_FILTER" => "Y",
			"SHOW_MEASURE" => "Y",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"OFFERS_CART_PROPERTIES" => array(
			),
			"COMPARE_PATH" => "",
			"SHOW_DISCOUNT_PERCENT" => "Y",
			"SHOW_OLD_PRICE" => "Y",
			"SHOW_RATING" => "Y",
			"DISPLAY_WISH_BUTTONS" => \Bitrix\Main\Config\Option::get('aspro.optimus', 'SHOW_DELAY', 'Y'),
		),
		false, array("HIDE_ICONS"=>"Y")
	);

?>