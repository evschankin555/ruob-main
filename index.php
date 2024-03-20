<?php if (isset($_GET['test']) && $_GET['test'] == 1):
   include 'index_2023.php';
    ?>
<?php else: ?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Промышленное оборудование для пищевого производства и общепита. Профессиональное оборудование для HoReCa с доставкой по РФ.");
$APPLICATION->SetPageProperty("title", "Оборудование для пищевого производства | РУОБОРУДОВАНИЕ.РУ");
$APPLICATION->SetPageProperty("viewed_show", "Y");
$APPLICATION->SetTitle("RuOborudovanie.ru");
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/mainpage/comp_banners_top_slider.php"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/mainpage/comp_tizers.php"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/mainpage/comp_banners_float.php"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/mainpage/comp_catalog_hit.php"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/mainpage/comp_news_akc.php"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/mainpage/comp_instagram.php"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/mainpage/inc_company.php"
	)
);?>

<h1>Промышленное пищевое оборудование</h1>
<p>Интернет-гипермаркет RuOborudovanie предлагает промышленное оборудование для пищевого производства по выгодным ценам. В каталоге большой выбор товаров от крупных надежных производителей.</p>
<h2>Что мы предлагаем?</h2>
<p>На сайте представлен широкий ассортимент промышленного пищевого оборудования:</p>
<ul>
<li>Все, что необходимо для мясопереработки;</li>
<li>Аппаратура для хлебопекарни;</li>
<li>Барное и кофейное оборудование;</li>
<li>Молочное оборудование;</li>
<li>Рыбоперерабатывающее оборудование;</li>
<li>Аппараты для быстрого приготовления блюд восточной кухни;</li>
<li>Аппаратура для фаст-фуда.</li>
</ul>
<p>Также мы предлагаем холодильное оборудование, мебель для кафе и ресторанов, аппараты для раздачи готовых блюд, для переработки овощей и многое другое. У нас вы сможете найти все необходимое для организации бизнеса.</p>
<p>Если вы ищете оборудование для horeca, обращайтесь к нам. Чтобы сделать заказа, добавьте товар в корзину, а затем укажите свои данные, чтобы завершить процедуру. Есть функция быстрого заказа: вы оставляете контактные данные, наш менеджер перезванивает и уточняет детали. Оплатить покупку можно банковской картой или с помощью электронной платежной системы.</p>
<h2>Преимущества интернет-гипермаркета</h2>
<p>У нас можно заказать качественное оборудование с доставкой. Заказчик выбирает тип доставки: курьером или транспортной компанией. Стоимость и сроки определяются индивидуально.</p>
<p>Воспользоваться нашими услугами стоит по нескольким причинам:</p>
<ul>
<li>Огромный выбор оборудования для общепита;</li>
<li>Гарантия качества предоставляемых товаров;</li>
<li>Удобная система поиска;</li>
<li>Постоянное пополняющийся каталог.</li>
</ul>
<p>Наши сотрудники готовы предоставить всю информацию по товарам. Вы можете следить за скидками и акциями на сайте в разделе &laquo;Акции&raquo;. Если вы хотите уточнить детали, проконсультироваться, позвоните по указанным телефонам.</p>


<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/mainpage/comp_brands.php"
	)
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<?php endif; ?>