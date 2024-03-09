<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'torgsnhm_dev');
define('DB_PASSWORD', '*1G6iN&O');
define('DB_DATABASE', 'torgsnhm_dev');
$db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$db -> set_charset("utf8");

if (isset($_GET['category_id'])) {
    $parentId = $_GET['category_id'];
}
else if (isset($_GET['product_id'])) {
    $contentId = $_GET['product_id'];
    $parentId = getProduct($contentId)->parentId;
    $contentHtml = getProduct($contentId)->contentHtml;
}
else {
    exit;
}

if (getProductsId($parentId)) {
    foreach (getProductsId($parentId) as $id) {
        if ($id == $contentId) {
            continue;
        }
        $contentHtml .= getProduct($id)->contentHtml;
    }
}

$priceHeaderImage = 'assets/images/price_header.jpg';
$priceHeaderHtml = '<a href="#"><img width="725" src="' . $priceHeaderImage . '"></a>';
$pageTitle = getPagetitle($parentId);
$topHtml = '<html xmlns="http://www.w3.org/1999/html">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
  body { font-family: DejaVu Sans, sans-serif; }
</style>
<title>Прайс-лист</title>
</head>
<body>
<div class="goods-header">
	' . $priceHeaderHtml . '
</div>
<div class="goods-header" style="margin: 5px; text-align: right;  font-size: 12px;">
	' . date('d-m-Y') . '
</div>
<div class="goods-header" style="margin-bottom: 20px; text-align: center;  font-size: 18px; font-weight: 600;">
	Прайс-лист на «' . $pageTitle . '»
</div>';

$bottomHtml = '</body>
</html>';

$pageHtml = $topHtml . $contentHtml . $bottomHtml;
// instantiate and use the dompdf class

$dompdf = new Dompdf();
$dompdf->loadHtml($pageHtml);

// (Optional) Setup the paper size and orientation
//$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

$docName = '_Прайс на ' . $pageTitle;
// Output the generated PDF to Browser
$dompdf->stream($docName);

function getPath($content_id, $path = '', $depth = 0) {
    global $db;
    $result_content = $db->query("SELECT `alias`,`parent` FROM `modx_site_content` WHERE `id` = '$content_id'");
    if (!$result_content->num_rows) {
        return false;
    }
    $row_content = $result_content->fetch_assoc();
    if (!$row_content['parent']) {
        return false;
    }
    $path = $row_content['alias'] . '/' . $path;
    if ($row_content['parent'] == '3') {
        return $path;
    }
    return getPath($row_content['parent'], $path, $depth + 1);
}

function getPagetitle($content_id) {
    global $db;
    $result_content = $db->query("SELECT `pagetitle` FROM `modx_site_content` WHERE `id` = '$content_id'");
    if (!$result_content->num_rows) {
        return false;
    }
    $row_content = $result_content->fetch_assoc();
    if (!$row_content['pagetitle']) {
        return false;
    }
    return $row_content['pagetitle'];
}

function makePictures($file){
    $basepath = dirname($file);
    $filename = basename($file);
    if (!file_exists($basepath . '/big')) {
        mkdir($basepath . '/big', 0777, false);
    }
    if (!file_exists($basepath . '/middle')) {
        mkdir($basepath . '/middle', 0777, false);
    }
    if (!file_exists($basepath . '/original')) {
        mkdir($basepath . '/original', 0777, false);
    }
    if (!file_exists($basepath . '/small')) {
        mkdir($basepath . '/small', 0777, false);
    }
    $file_dimensions = getimagesize($file);

    $ImageType = strtolower($file_dimensions['mime']);
    switch(strtolower($ImageType))
    {
        case 'image/png':
            $img = imagecreatefrompng($file);
            break;
        case 'image/jpeg':
            $img = imagecreatefromjpeg($file);
            break;
        default:
    }
    if ($img){
        if (!file_exists($basepath . '/big/' . $filename)) {
            $big_image = resizeImage($img, 300, 300, true);
            imagejpeg($big_image, $basepath . '/big/' . $filename);
            imagedestroy($big_image);
        }

        if (!file_exists($basepath . '/middle/' . $filename)) {
            $middle_image = resizeImage($img, 150, 150, false);
            imagejpeg($middle_image, $basepath . '/middle/' . $filename);
            imagedestroy($middle_image);
        }

        if (!file_exists($basepath . '/small/' . $filename)) {
            $small_image = resizeImage($img, 75, 75, true);
            imagejpeg($small_image, $basepath . '/small/' . $filename);
            imagedestroy($small_image);
        }

        if (!file_exists($basepath . '/original/' . $filename)) {
            $original_image = $img;
            imagejpeg($original_image, $basepath . '/original/' . $filename);
            imagedestroy($original_image);
        }
    }
    else {
        return false;
    }
    return true;
}

function resizeImage($src, $w, $h, $fit=FALSE) {

    $width = imagesx($src);
    $height = imagesy($src);
    $r = $width / $height;

    $x_offset = 0;
    $y_offset = 0;

    if ($fit) {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
            $x_offset = ($w - $newwidth) / 2;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
            $y_offset = ($h - $newheight) / 2;
        }
        $dst = imagecreatetruecolor($w, $h);
        imageFilledRectangle($dst, 0, 0, $w, $h, 16777215);
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imageFilledRectangle($dst, 0, 0, $newwidth, $newheight, 16777215);
    }

    imagecopyresampled($dst, $src, $x_offset, $y_offset, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

function getProduct($content_id) {
    global $db;
    $product = new stdClass();
    $result_content = $db->query("SELECT `alias`,`pagetitle`,`parent` FROM `modx_site_content` WHERE `id` = '$content_id'");
    if (!$result_content->num_rows) {
        exit;
    }
    $row_content = $result_content->fetch_assoc();
    $result_tmplvar_contentvalues = $db->query("SELECT `tmplvarid`,`value` FROM `modx_site_tmplvar_contentvalues` WHERE `contentid` = '$content_id'");
    if ($result_content->num_rows) {
        while ($row_tmplvar_contentvalues = $result_tmplvar_contentvalues->fetch_assoc()) {
            switch ($row_tmplvar_contentvalues['tmplvarid']) {
                case '4':
                    $price = $row_tmplvar_contentvalues['value'];
                    break;
                case '12':
                    $product_params = getParamsHtml($row_tmplvar_contentvalues['value']);
                    break;
                case '51':
                    $product_chars = $row_tmplvar_contentvalues['value'];
                    break;
                case '53':
                    $image2 = $row_tmplvar_contentvalues['value'];
                    break;
                default:
            }
        }
    }
    if (empty($product_chars)) {
        $product_chars = $product_params;
    }
    $result_sg_images = $db->query("SELECT `sg_image` FROM `modx_sg_images` WHERE `sg_rid` = '$content_id' AND `sg_isactive` = '1'");
    if ($result_content->num_rows) {
        $row_sg_images = $result_sg_images->fetch_assoc();
        if ($row_sg_images['sg_image']) {
            $image = dirname($row_sg_images['sg_image']) . '/middle/' . basename($row_sg_images['sg_image']);
            if (!file_exists($image)) {
                if (file_exists($row_sg_images['sg_image'])) {
                    makePictures($row_sg_images['sg_image']);
                }
                else {
                    $image = false;
                }
            }
        } else {
            $image = false;
        }
    }
    if (!$image && $image2) {
        if ($image2) {
            $image = dirname($image2) . '/middle/' . basename($image2);
            if (!file_exists($image)) {
                if (file_exists($image2)) {
                    makePictures($image2);
                }
                else {
                    $image = false;
                }
            }
        } else {
            $image = false;
        }
    }
    $alias = $row_content['alias'];
    $pagetitle = $row_content['pagetitle'];
    $parent_id = $row_content['parent'];
    $path = getPath($parent_id);
    $siteUrl = 'https://torgsnab-nn.ru/katalog/' . $path;
    if ($price) {
        $priceHtml = '<div style="text-align: center; color: #555;	font-size: 18px; font-weight: 700; margin: 0 0 15px;">
            Цена: ' . $price . ' ₽
				</div>
				<div style="">
                    <a style="text-decoration: none; display: inline-block; font-weight: 700; font-size: 16px; border: none; color: #fff; background: #d84d6f; border-radius: 25px; text-align: center; width: 200px; height: 40px; padding-top: 10px;" href="' . $siteUrl . $alias . '">
                        <span style="padding-top: 10px;">В корзину</span>
                    </a>
				</div>';
    }
    else {
        $priceHtml = '<div style="text-align: center; font-size: 16px; font-weight: 600; padding-top: 20px;">Товар отсутствует<br><a href="' . $siteUrl . $alias . '">Запросить аналог</a></div>';
    }
    if ($image) {
        $image_height = getimagesize($image)[1];
        $image_width = getimagesize($image)[0];
        $top_offset = (190 - $image_height) / 2;
        $left_offset = (170 - $image_width) / 2;
        $imageHtml = '<img src="' . $image . '" alt="' . $pagetitle . '">';
    }
    else {
        $imageHtml = '';
        $top_offset = 0;
        $left_offset = 0;
    }
    $contentHtml = '
<div style="margin-bottom:5px; padding: 15px; height: 195px; border: solid 1px darkgray; border-radius: 10px;">
        <div style="position: absolute; padding-top: ' . $top_offset . '; left: ' . $left_offset . ';">
			<div class="goods-description__view-box">
				' . $imageHtml . '
			</div>
		</div>
		<div style="position: absolute; left:180px; width:300px; font-size: 10px;">
			<div style="text-align: center;">
				<strong><a href="' . $siteUrl . $alias . '">' . $pagetitle . '</a></strong>
            </div>
            <div style="height: 150px; overflow: hidden;">
                <p class="param-1">' . $product_chars . '</p>
            </div>
        </div>
        <div style="text-align: right; position: absolute; padding-top: 180px; left:170px; width:300px; font-size: 10px;" class="show-more2">
            <a class="show-more__all" href="' . $siteUrl . $alias . '">
                <span style="padding: 5px 5px 5px 5px; background-color: white;">Все характеристики</span>
            </a>
        </div>
		<div style="position: absolute; left: 500px; font-size: 10px;">
			<div class="goods-info-price">
				<ul class="goods-offers">
					<li class="goods-offers__item">
						Оборудование “Под ключ” со скидками 
					</li>
					<li class="goods-offers__item">
					    <b>Доставка по всей России</b>
					</li>
				</ul>
				' . $priceHtml . '
			</div>
		</div>		
</div>
';
    $product->contentHtml = $contentHtml;
    $product->parentId = $parent_id;
    return $product;
}

function getProductsId($parent_id) {
    global $db;
    $result_content = $db->query("SELECT `id`,`isfolder` FROM `modx_site_content` WHERE `parent` = '$parent_id'");
    if (!$result_content->num_rows) {
        return false;
    }
    $result_id = [];
    while ($row_content = $result_content->fetch_assoc()) {
        if ($row_content['isfolder']) {
            if (getProductsId($row_content['id'])) {
                $result_id = array_merge($result_id, getProductsId($row_content['id']));
            }
        }
        else {
            $result_id[] = $row_content['id'];
        }
    }
    return $result_id;
}

function getParamsHtml($paramsText) {
    $str=str_replace("#", "", $paramsText);
    $str=str_replace("||", "", $str);
    $str=str_replace(" ", "", $str);
    $paramsText=str_replace("==", "#", $paramsText);
    if (($paramsText != "") && ($str != "")) {
        $paramsHtml = '<p class="param-1">';
        $paramRows=explode("||",$paramsText);
        foreach($paramRows as $index => $paramRow)
        {
            $param=explode("#",$paramRow);
            $paramsHtml .= "<b>".$param[0]." : </b> <span>".$param[1]."</span><br/>";
        }
        $paramsHtml .= "</p>";
        return $paramsHtml;
    }
    return false;
}