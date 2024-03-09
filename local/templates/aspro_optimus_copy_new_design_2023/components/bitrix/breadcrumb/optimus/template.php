<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$strReturn = '';
if($arResult){
    CModule::IncludeModule("iblock");
    global $TEMPLATE_OPTIONS, $OptimusSectionID, $noAddElementToChain;
    $cnt = count($arResult);
    $lastindex = $cnt - 1;
    $bShowCatalogSubsections = COption::GetOptionString("aspro.optimus", "SHOW_BREADCRUMBS_CATALOG_SUBSECTIONS", "Y", SITE_ID) == "Y";
    $bMobileBreadcrumbs = ($TEMPLATE_OPTIONS["MOBILE_CATALOG_BREADCRUMBS"]["CURRENT_VALUE"] == "Y" && $OptimusSectionID);

    if ($bMobileBreadcrumbs) {
        if ($noAddElementToChain) {
            $visibleMobile = $lastindex;
        } else {
            $visibleMobile = $lastindex - 1;
        }

    }

    $strReturn = '<ul class="breadcrumbs">';
    for($index = 0; $index < $cnt; ++$index){
        $arSubSections = array();
        $bShowMobileArrow = false;
        $arItem = $arResult[$index];
        $title = htmlspecialcharsex($arItem["TITLE"]);
        $bLast = $index == $lastindex;

        if($OptimusSectionID){
            if ($bMobileBreadcrumbs && $visibleMobile == $index) {
                $bShowMobileArrow = true;
            }

            if($bShowCatalogSubsections){
                $arSubSections = COptimus::getChainNeighbors($OptimusSectionID, $arItem['LINK']);
            }
        }

        $strReturn .= '<li>';
        if($arItem["LINK"] <> "" && $arItem['LINK'] != GetPagePath() && $arItem['LINK']."index.php" != GetPagePath() || $arSubSections){
            $strReturn .= '<a href="'.$arItem["LINK"].'" itemprop="item">';
            $strReturn .= $title;
            $strReturn .= '</a>';
        }
        else{
            $strReturn .= $title;
        }
        $strReturn .= '</li>';
    }

    $strReturn .= '</ul>';

    return $strReturn;
}
else{
    return $strReturn;
}
?>
