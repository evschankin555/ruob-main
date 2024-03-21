<?
//echo "<p>templateData['CODE'] = " . $templateData["CODE"] . "</p><br />";
//echo "<p>arResult['NAME'] = " . $arResult["NAME"] . "</p><br />";

// Создание массива всех активных товаров
$arSelect = Array(
    "ID", 
    "IBLOCK_SECTION_ID",
    "IBLOCK_ID",
    "NAME", 
    "DATE_ACTIVE_FROM",
    "PROPERTY_CML2_MANUFACTURER",
    //"QUANTITY",
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
			$arrGroup[] = $value["IBLOCK_SECTION_ID"];

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
	"aspro:catalog.section.list.optimus",
	"front_sections_only",
	array(
		"IBLOCK_TYPE" => "aspro_optimus_catalog",
		"IBLOCK_ID" => "14",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"COUNT_ELEMENTS" => "N",
		"FILTER_NAME" => "arBrandSections",
		"TOP_DEPTH" => $arParams["DEPTH_LEVEL_BRAND"],
		"SECTION_URL" => "",
		"VIEW_MODE" => "",
		"SHOW_PARENT_NAME" => "N",
		"HIDE_SECTION_NAME" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"USE_FILTER_SECTION" => "N",
		"BRAND_NAME" => $arResult["NAME"],
		"BRAND_CODE" => $templateData["CODE"],
		"SHOW_SECTIONS_LIST_PREVIEW" => "N",
		"SECTIONS_LIST_PREVIEW_PROPERTY" => "N",
		"SECTIONS_LIST_PREVIEW_DESCRIPTION" => "N",
		"SHOW_SECTION_LIST_PICTURES" => "N",
		"DISPLAY_PANEL" => "N",
		"COMPONENT_TEMPLATE" => "front_sections_only",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
	),
	false, array("HIDE_ICONS" => "Y")
);
?>