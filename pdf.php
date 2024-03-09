<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);
} else {
    exit;
}

$current_page = isset($_GET['current_page']) ? urldecode($_GET['current_page']) : '';

// ID блока каталога
$catalogId = 14;

// Получаем элемент из Битрикса
$res = CIBlockElement::GetList(
    [],
    [
        'IBLOCK_ID' => $catalogId,
        'ID' => $productId,
    ],
    false,
    false,
    ['ID', 'NAME', '*']
);

$priceHeaderImage = $_SERVER["DOCUMENT_ROOT"] . '/dist/images/price_header.jpg';
$priceHeaderImage = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($priceHeaderImage));

$priceHeaderHtml = '<a href="#"><img width="1920"  src="' . $priceHeaderImage . '"></a>';

$footerImage = $_SERVER["DOCUMENT_ROOT"] . '/dist/images/footer.jpg';
$footerImage = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($footerImage));

$footerHtml = '<a href="#" style="margin-top: 300px;display: inline-block;"><img width="1920"  src="' . $footerImage . '"></a>';


$saleImage = $_SERVER["DOCUMENT_ROOT"] . '/dist/images/sale2.jpg';
$saleImage = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($saleImage));

use Bitrix\Catalog\Model\Price;

// Замените $productId на фактический ID вашего товара

// Получение текущей цены
$priceIterator = Price::getList([
    'filter' => [
        'PRODUCT_ID' => $productId,
    ],
    'order' => ['PRICE' => 'ASC'],
    'select' => ['ID', 'PRICE', '*'],
]);


if ($ob = $res->GetNextElement()) {

    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();

    // Доступ к данным элемента
    $productId = $arFields['ID'];
    $productName = $arFields['NAME'];
    // Доступ к свойствам элемента
    $propertyValue = $arFields['PROPERTY_CODE_VALUE'];

    // Дополнительные данные
    $productDetailText = $arFields['DETAIL_TEXT'];
    $catalogQuantity = $arFields['CATALOG_QUANTITY'];
    if ($price = $priceIterator->fetch()) {
        $currentPrice = $price['PRICE'];
        $currency = $price['CURRENCY'];

    }

    $minPrice = $currentPrice;
    $manufacturer = $arFields['DISPLAY_PROPERTIES']['CML2_MANUFACTURER']['VALUE'];
    $brandElementId = $arFields['DISPLAY_PROPERTIES']['CML2_MANUFACTURER']['VALUE_XML_ID'];

    // Получение путей к изображениям
    $previewPictureId = $arFields["PREVIEW_PICTURE"];
    $detailPictureId = $arFields["DETAIL_PICTURE"];

    $previewPictureSrc = $_SERVER["DOCUMENT_ROOT"] . CFile::GetPath($previewPictureId);
    $detailPictureSrc = $_SERVER["DOCUMENT_ROOT"] . CFile::GetPath($detailPictureId);

    $previewPictureSrc = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($detailPictureSrc));

// Формируем данные характеристик на основе параметров из URL
    $characteristicsHtml = '<ul style="min-width: 300px; list-style: none; padding: 0; margin: 75px;" class="list-param card__list-param">';
    foreach ($_GET as $key => $value) {
        if ($key !== 'id' && $key !== 'current_page') {
            $characteristicsHtml .= '<li style="display: block; padding: 5px 0; " class="list-param__item">';
            $characteristicsHtml .= '<span style="display: inline-block; width: 50%;text-align: left">' . htmlspecialchars($value['NAME']) . '</span>';
            $characteristicsHtml .= '<span style="display: inline-block; width: 50%;text-align: right">' . htmlspecialchars($value['VALUE']) . '</span>';
            $characteristicsHtml .= '</li>';
        }
    }
    $characteristicsHtml .= '</ul>';

    $formattedPrice = number_format($minPrice, 2, ',', ' ');

// Проверяем, если дробная часть равна 00
    if (substr($formattedPrice, -3) === ',00') {
        // Если равна 00, то выводим число без дробей
        $formattedPrice = number_format($minPrice, 0, ',', ' ');
    }



    // Пример вывода данных
    $commercialOfferHtml = '<html xmlns="http://www.w3.org/1999/html">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            body { font-family: DejaVu Sans, sans-serif; }
        </style>
        <title>Коммерческое предложение</title>
    </head>
    <body>
        <div class="goods-header">
            ' . $priceHeaderHtml . '
        </div>
        <h1 class="goods-header" style="margin: 75px 0; text-align: center;">
            Коммерческое предложение от ' . date('d.m.Y') . '
        </h1>
        <div style="display: table; width: 1500px; margin: 0 auto;border-top: 5px solid #000;">
            <div style="display: table-cell; width: 750px; vertical-align: top;">    
                <h2 style="text-align: center; width: 100%;margin-top: 75px;" class="card__colright-title">' . $productName . '</h2>
                <div style="display:inline-block;text-align: center;margin-top: 20px;width: 100%">
                    <img src="' . $previewPictureSrc . '" alt="' . $productName . '" style="display:inline-block;width:500px;" >
                </div>
            </div>
            <div style="display: table-cell; width: 750px; vertical-align: top;">
                <div class="grayDescript">
                
        ' . $characteristicsHtml . '
                    <div class="card__colright-row">
                        <h2 class="card__colright-price" style="text-align: center;width: 100%;">' . $formattedPrice . ' ₽</h2>
                    </div>
                    <a href="' . $current_page . '" target="_blank" style="width:100%;display:inline-block; text-align: center;margin-top: 20px;">
                        <img src="' . $saleImage . '" style="width:300px;">
                    </a>
                </div>
            </div>
        </div>
           ' . $footerHtml . '
       
    </body>
    </html>';

    $dompdf = new Dompdf();
    $dompdf->loadHtml($commercialOfferHtml);

    // (Optional) Setup the paper size and orientation
    //$dompdf->setPaper('A4', 'landscape');
    $dompdf->setPaper(array(0, 0, 1500, 2121));

    // Render the HTML as PDF
    $dompdf->render();

    $docName = 'Коммерческое предложение на ' . $productName;
    // Output the generated PDF to Browser
    $dompdf->stream($docName);
}
