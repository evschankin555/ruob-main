<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Промышленное оборудование для пищевого производства и общепита. Профессиональное оборудование для HoReCa с доставкой по РФ.");
$APPLICATION->SetPageProperty("title", "Оборудование для пищевого производства | РУОБОРУДОВАНИЕ.РУ");
$APPLICATION->SetPageProperty("viewed_show", "Y");
$APPLICATION->SetTitle("RuOborudovanie.ru");
?><?$APPLICATION->IncludeComponent(
	"webdebug.seo:regions.link", 
	".default", 
	array(
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"LABEL" => "Ваш город:",
		"POPUP_FOOTER" => "",
		"POPUP_HEIGHT" => "550",
		"POPUP_MIN_HEIGHT" => "400",
		"POPUP_TITLE" => "Выберите Ваш город",
		"POPUP_WIDTH" => "800",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>

<?print_r(\WD\Seo\Regions\Manager::getInstance(SITE_ID)->get());?>

#WD_SEO_REGION_NAME#

#WD_SEO_REGION_PHONE#

#WD_SEO_REGION_EMAIL#

<p>Вывод ИД региона: </p><?print_r(\WD\Seo\Regions\Manager::getInstance(SITE_ID)->getRegionId());?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>