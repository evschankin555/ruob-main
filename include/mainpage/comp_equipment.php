<?php
use Bitrix\Main\Loader;
use Bitrix\Main\FileTable;

Loader::includeModule("iblock");

// Получаем элементы инфоблока
$arSelect = ['ID', 'NAME', 'CODE', 'PROPERTY_IMG_DEFAULT', 'PROPERTY_IMG_HOVER', 'PROPERTY_TITLE_SHORT'];
$arFilter = ['IBLOCK_ID' => 30, 'ACTIVE' => 'Y'];
$res = CIBlockElement::GetList(['SORT' => 'ASC'], $arFilter, false, false, $arSelect);

while ($arFields = $res->GetNext()) {
    $imgDefaultSrc = CFile::GetPath($arFields['PROPERTY_IMG_DEFAULT_VALUE']);
    $imgHoverSrc = CFile::GetPath($arFields['PROPERTY_IMG_HOVER_VALUE']);
    $titleShort = $arFields['PROPERTY_TITLE_SHORT_VALUE'];

    echo "<a href='/equipment/".$arFields['CODE']."/' class='equipment__grid-item'>";
    echo "<div class='equipment__grid-default'>";
    if ($imgDefaultSrc) {
        echo "<img src='$imgDefaultSrc' loading='lazy' class='equipment__grid-img' width='150' height='150' alt='" . htmlspecialchars($arFields['NAME']) . "'>";
    }
    echo $titleShort;
    echo "</div>";

    echo "<div class='equipment__grid-hover'>";
    if ($imgHoverSrc) {
        echo "<img src='$imgHoverSrc' loading='lazy' class='equipment__grid-img' width='150' height='150' alt='" . htmlspecialchars($arFields['NAME']) . "'>";
    }
    echo $titleShort;
    echo "</div>";
    echo "</a>";
}
