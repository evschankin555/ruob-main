<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

foreach($arResult["ITEMS"] as $key => $arItem)
{
	if(in_array($arItem["CODE"], $arParams['HIDDEN_PROP'])){
		unset($arResult["ITEMS"][$key]);
		continue;
	}

	if($arItem["CODE"]=="IN_STOCK"){
		if($arResult["ITEMS"][$key]["VALUES"] && is_array($arResult["ITEMS"][$key]["VALUES"]) && count($arResult["ITEMS"][$key]["VALUES"])<2){
			sort($arResult["ITEMS"][$key]["VALUES"]);		
			$arResult["ITEMS"][$key]["VALUES"][0]["VALUE"]=$arItem["NAME"];
		}
	}

	if(!isset($arItem["NAME"]))
		unset($arResult["ITEMS"][$key]);
}

global $sotbitFilterResult;
$sotbitFilterResult = $arResult;