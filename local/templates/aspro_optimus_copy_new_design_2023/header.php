<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if($GET["debug"] == "y"){
    error_reporting(E_ERROR | E_PARSE);
}

IncludeTemplateLangFile(__FILE__);
global $APPLICATION, $TEMPLATE_OPTIONS, $arSite;

// Кеширование данных сайта
$cache = new CPHPCache();
$cacheTime = 86400; // 24 часа
$cacheId = 'siteData' . SITE_ID;
$cacheDir = '/siteData/';

if($cache->InitCache($cacheTime, $cacheId, $cacheDir)) {
    $vars = $cache->GetVars();
    $arSite = $vars['arSite'];
} elseif($cache->StartDataCache()) {
    $arSite = CSite::GetByID(SITE_ID)->Fetch();
    $cache->EndDataCache(array('arSite' => $arSite));
}

$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : '');
?>
    <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?>>
    <head>
        <title><?$APPLICATION->ShowTitle()?></title>
        <?$APPLICATION->ShowMeta("viewport");?>
        <?$APPLICATION->ShowMeta("HandheldFriendly");?>
        <?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
        <?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
        <?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
        <?$APPLICATION->ShowHead();?>
        <?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject($MESS, false).')</script>', true);?>
        <?if(CModule::IncludeModule("aspro.optimus")) {COptimus::Start(SITE_ID);}?>
        <?$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false);?>
        <?if(!$bIndexBot):?>
            <!-- Оптимизированное подключение шрифтов -->
            <style>
                @font-face {
                    font-family: 'Ubuntu';
                    font-style: normal;
                    font-weight: 400;
                    src: local('Ubuntu'), local('Ubuntu-Regular'), url('/local/fonts/Ubuntu-Regular.woff2') format('woff2');
                }
                /* Добавьте необходимые варианты шрифтов */
            </style>
        <?endif;?>
        <link rel="stylesheet" href="/bitrix/templates/aspro_optimus_copy_new_design_2023/css/new_main.min.css?v=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/bitrix/templates/aspro_optimus_copy_new_design_2023/css/new_main.min.css'); ?>">
    </head>
<body class='<?=($bIndexBot ? "wbot" : "");?>' id="main">
		<div id="panel"><?$APPLICATION->ShowPanel();?></div>
		<?if(!CModule::IncludeModule("aspro.optimus")){?><center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?><?}?>
		<?$APPLICATION->IncludeComponent("aspro:theme.optimus", ".default", array("COMPONENT_TEMPLATE" => ".default"), false);?>
		<?COptimus::SetJSOptions();?>
		<?if($TEMPLATE_OPTIONS["MOBILE_FILTER_COMPACT"]["CURRENT_VALUE"] === "Y"):?>
            <div id="mobilefilter" class="visible-xs visible-sm scrollbar-filter"></div>
        <?endif;?>
		<div class="wrapper <?=(COptimus::getCurrentPageClass());?> basket_<?=strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]);?> <?=strToLower($TEMPLATE_OPTIONS["MENU_COLOR"]["CURRENT_VALUE"]);?> banner_auto">
			<div class="header_wrap <?=strtolower($TEMPLATE_OPTIONS["HEAD_COLOR"]["CURRENT_VALUE"])?>">
				<?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]=="NORMAL"){?>
					<div class="top-h-row">
						<div class="wrapper_inner">
							<div class="top_inner">

							</div>
						</div>
					</div>
				<?}?>
                <? include 'new_header.php'; ?>

			</div>
			<div class="wraps" id="content">
				<div class="wrapper_inner <?=(COptimus::IsMainPage() ? "front" : "");?> <?=((COptimus::IsOrderPage() || COptimus::IsBasketPage()) ? "wide_page" : "");?>">
<?if(COptimus::IsMainPage()):?>

<?else:?>
<?if(!COptimus::IsOrderPage() && !COptimus::IsBasketPage() && strpos($_SERVER['REQUEST_URI'], '/info/articles/') !== 0){?>
    <?$APPLICATION->ShowViewContent('detail_filter');?>
    <div class="left_block">
        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR."include/left_block/menu.left_menu.php",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php",
                "CACHE_TYPE" => "A", // Включено кеширование
                "CACHE_TIME" => 86400, // Время кеширования - 24 часа
            ),
            false
        );?>

        <?$APPLICATION->ShowViewContent('left_menu');?>

        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR."include/left_block/comp_banners_left.php",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => 86400,
            ),
            false
        );?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR."include/left_block/comp_subscribe.php",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => 86400,
            ),
            false
        );?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR."include/left_block/comp_news.php",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => 86400,
            ),
            false
        );?>

    <?/*$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/comp_news_articles.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);*/?>
						</div>
						<div class="right_block">
					<?}?>
    <!--<div class="middle">-->
							<?if(!COptimus::IsMainPage() && strpos($_SERVER['REQUEST_URI'], '/info/articles/') !== 0):?>
								<div class="container">
									<div id="navigation">
                                        <?
                                        $cache = Bitrix\Main\Data\Cache::createInstance();
                                        $cacheTime = 86400; // 24 часа
                                        $cacheId = 'breadcrumb_' . md5($_SERVER['REQUEST_URI']); // Уникальный ID для текущего URL
                                        $cachePath = '/breadcrumb/';

                                        if ($cache->initCache($cacheTime, $cacheId, $cachePath)) {
                                            $vars = $cache->getVars();
                                            echo $vars['breadcrumb'];
                                        } elseif ($cache->startDataCache()) {
                                            ob_start();
                                            $APPLICATION->IncludeComponent("bitrix:breadcrumb", "optimus", array(
                                                "START_FROM" => "0",
                                                "PATH" => "",
                                                "SITE_ID" => "-",
                                                "SHOW_SUBSECTIONS" => "N"
                                            ), false);
                                            $breadcrumb = ob_get_clean();
                                            echo $breadcrumb;

                                            $cache->endDataCache(array('breadcrumb' => $breadcrumb));
                                        }
                                        ?>
									</div>
									<?$APPLICATION->ShowViewContent('section_bnr_content');?>
    <?if($APPLICATION->GetCurPage() == "/order/"):?>
        <section class="section-order">
    <?elseif(strpos($_SERVER['REQUEST_URI'], '/info/articles/') == 0):?>

    <?endif;?>
    <?php
    // Получаем текущий URL без доменного имени
    $currentPath = trim($APPLICATION->GetCurDir(), '/');

    // Разбиваем путь на сегменты
    $pathSegments = explode('/', $currentPath);
    // Проверяем, что мы находимся на третьем уровне вложенности в каталоге
    if (count($pathSegments) == 3 && $pathSegments[0] == 'catalog') {
        ?>
        <script src="https://api.esplc.ru/widgets/block/app.js"></script>
        <h1 id="pagetitle" class="h1 products__title" ><?=$APPLICATION->ShowTitle(false);?></h1>
        <?php
        include($_SERVER["DOCUMENT_ROOT"] . "/include/top_page/caralog_tags.php");
    } elseif (count($pathSegments) == 4 && is_numeric(end($pathSegments))) {


    }else{

    ?>
    <!--title_content-->
    <h1 id="pagetitle" class="h1 products__title" ><?=$APPLICATION->ShowTitle(false);?></h1>
    <!--end-title_content-->
    <?php
    }
    ?>

    <?endif;?>

<?endif;?>

<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") $APPLICATION->RestartBuffer();?>