<div class="city-mobile">
<?$APPLICATION->IncludeComponent(
	"webdebug.seo:regions.link", 
	".default", 
	array(
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		//"LABEL" => "Ваш город:",
		"POPUP_FOOTER" => "",
		"POPUP_HEIGHT" => "550",
		"POPUP_MIN_HEIGHT" => "400",
		"POPUP_TITLE" => "Выберите Ваш город",
		"POPUP_WIDTH" => "800",
		"COMPONENT_TEMPLATE" => ".default"
		),
	false
);?>
</div>