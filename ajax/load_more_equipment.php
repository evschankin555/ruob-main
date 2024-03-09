<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionElementTable;


$page = $_POST['page']; // Номер текущей страницы
$sectionIds = explode(',', $_POST['sectionIds']); // ID разделов

// Определяем количество элементов на странице и смещение
$itemsPerPage = 20;
$offset = ($page - 1) * $itemsPerPage;

// Получаем общее количество элементов для правильной пагинации
$countQuery = CIBlockElement::GetList(
    array(),
    array('IBLOCK_ID' => 14, 'SECTION_ID' => $sectionIds, '>PRICE' => 0, '>QUANTITY' => 0),
    false,
    false,
    array('ID')
);
$totalItems = $countQuery->SelectedRowsCount();

// Получаем товары из всех разделов, собранных в массив $sectionIds с пагинацией
$dbItems = CIBlockElement::GetList(
    array('CATALOG_QUANTITY' => 'DESC', 'CATALOG_PRICE_1' => 'ASC'),
    array('IBLOCK_ID' => 14, 'SECTION_ID' => $sectionIds, '>PRICE' => 0, '>QUANTITY' => 0),
    false,
    array('iNumPage' => $page, 'nPageSize' => $itemsPerPage, 'offset' => $offset),
    array('ID', 'NAME', 'DETAIL_PICTURE', '*')
);

// Инициализируем навигацию
$dbItems->NavStart($itemsPerPage, false);

$itemsData = array(); // Массив для хранения данных о товарах

// Обработка результатов запроса
while ($item = $dbItems->Fetch()) {
    // Получаем пути к картинкам товара
    $pictureSrc1 = '';
    $pictureSrc2 = '';
    if (!empty($item['DETAIL_PICTURE'])) {
        $arItemPictures = [];
        $arItemPictures[] = CFile::GetFileArray($item['DETAIL_PICTURE']);
        if (!empty($item['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
            foreach ($item['PROPERTIES']['MORE_PHOTO']['VALUE'] as $morePhotoId) {
                $arItemPictures[] = CFile::GetFileArray($morePhotoId);
            }
        }

        if (count($arItemPictures) >= 2) {
            $pictureSrc1 = $arItemPictures[0]['SRC'];
            $pictureSrc2 = $arItemPictures[1]['SRC'];
        } elseif (count($arItemPictures) === 1) {
            $pictureSrc1 = $arItemPictures[0]['SRC'];
            $pictureSrc2 = $arItemPictures[0]['SRC'];
        }
    }

    // Сохраняем данные о товаре в массив
    $itemsData[] = array(
        'ID' => $item['ID'],
        'NAME' => $item['NAME'],
        'PICTURE_SRC1' => $pictureSrc1,
        'PICTURE_SRC2' => $pictureSrc2,
        'DESCRIPTION' => $item['DETAIL_TEXT'],
        'COUNTRY' => $item['PROPERTY_COUNTRY_VALUE'],
        'MATERIAL' => $item['PROPERTY_MATERIAL_VALUE'],
        'DIMENSIONS' => $item['PROPERTY_DIMENSIONS_VALUE'],
        'PRICE' => $item['CATALOG_PRICE_1'],
        'QUANTITY' => $item['CATALOG_QUANTITY']
    );
}

// Формируем HTML-код для товаров
$htmlCode = '';
foreach ($itemsData as $item) {
    $htmlCode .= '<div class="card-grid__item">
        <div class="mycard">
            <div class="mycard__inner">
                <div class="mycard__top">
                    <div class="mycard__btns">
                        <button type="button" class="mycard__btns-item">
                            <svg><use xlink:href="#zoom-in"></use></svg>
                        </button>
                        <button type="button" class="mycard__btns-item">
                            <svg><use xlink:href="#favourite"></use></svg>
                        </button>
                        <button type="button" class="mycard__btns-item">
                            <img src="/dist/images/dist/compare-icon-3.png" alt="">
                        </button>
                    </div>
                    <img src="' . $item['PICTURE_SRC1'] . '" alt="" class="mycard__firstimg" style="max-height: 167px;">
                    <img src="' . $item['PICTURE_SRC2'] . '" alt="" class="mycard__twoimg" style="max-height: 167px;">
                </div>
                <a href="' . $item['DETAIL_PAGE_URL'] . '" class="mycard__title">' . $item['NAME'] . '</a>
                <div class="mycard__descript"></div>
                <div class="availability mycard__availability">В наличии<span>(' . $item['QUANTITY'] . ')</span></div>
                <div class="mycard__price"><span class="mycard__price-num">' . number_format($item["PRICE"], 0, ',', ' ') . ' ₽</span></div>
                <div class="credit">
                    <svg class="credit__svg"><use xlink:href="#credit"></use></svg>
                </div>
                <div class="mycard__footer">
                    <a href="#" class="blueBtn mycard__blueBtn">В КОРЗИНУ</a>
                </div>
            </div>
        </div>
    </div>';
}

// Возвращаем HTML-код следующих товаров
echo $htmlCode;

