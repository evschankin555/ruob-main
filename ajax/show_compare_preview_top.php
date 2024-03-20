<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// Проверяем, действительно ли необходима подгрузка модуля aspro.optimus
if (\Bitrix\Main\Loader::includeModule('aspro.optimus')) {
    // Запускаем компонент, только если модуль доступен
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.compare.list",
        "compare_top",
        Array(
            "IBLOCK_TYPE" => "aspro_optimus_catalog",
            "IBLOCK_ID" => "14",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "DETAIL_URL" => "/catalog/#SECTION_CODE_PATH#/#ELEMENT_ID#/",
            "COMPARE_URL" => "/catalog/compare.php",
            "NAME" => "CATALOG_COMPARE_LIST",
            "AJAX_OPTION_ADDITIONAL" => ""
        )
    );

    // Вызываем очистку счетчиков, если модуль успешно загружен
    COptimus::clearBasketCounters();
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>
