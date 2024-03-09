<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arParams["USE_RSS"]=="Y"):?>
	<?
		if(method_exists($APPLICATION, 'addheadstring'))
		$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" title="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" href="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" />');
	?>
	<a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"]?>" target="_blank" title="RSS" class="rss_feed_icon"><?=GetMessage("RSS_TITLE")?></a>
<?endif;?>

<?if ($arParams["SHOW_FAQ_BLOCK"]=="Y"):?>
	<div class="right_side wide">
		<div class="ask_small_block">
			<div class="ask_btn_block">
				<a class="button vbig_btn wides ask_btn"><span><?=GetMessage("ASK_QUESTION")?></span></a>
			</div>
			<div class="description">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/ask_block_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("ASK_QUESTION_TEXT"), ));?>
			</div>
		</div>
	</div>
	<div class="left_side wide">
<?endif;?>



    <div class="club">
        <nav class="club__menu">
            <a href="/info/articles/" class="club__menu-link club__menu-link--news is-active">Новости</a>
            <a href="#" class="club__menu-link club__menu-link--rating">Обзоры</a>
            <a href="/sale/" class="club__menu-link club__menu-link--blog">Блоги</a>
            <a href="#" class="club__menu-link club__menu-link--flow">Поток</a>
        </nav>
        <div class="club__content">
            <ul class="breadcrumbs products__breadcrumbs">
                <li><a href="/">Главная</a></li>
                <li>Новости</li>
            </ul>
            <h1 class="h1 club__title">Новости</h1>

            <div class="club__wrap">
                <?php
                $arFilter = array(
                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'GLOBAL_ACTIVE' => 'Y',
                    'ACTIVE' => 'Y',
                );

                $arSections = \CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), $arFilter, false, array('ID', 'NAME', 'CODE'));

                ?>

                <ul class="menu-content">
                    <li class="is-active"><a href="/info/articles/">Все</a></li>
                    <?php while ($arSection = $arSections->GetNext()): ?>
                        <li><a href="/info/articles/?category=<?= $arSection['CODE'] ?>"><?= $arSection['NAME'] ?></a></li>
                    <?php endwhile; ?>
                </ul>



                <?

                $categoryFilter = false;

                // Проверяем, установлен ли параметр 'category' в запросе
                if(isset($_GET['category']) && !empty($_GET['category'])) {
                    $categoryFilter = $_GET['category'];
                }


                $arFilterElements = array(
                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'ACTIVE' => 'Y',
                );

                // Добавляем фильтрацию по категории, если она установлена
                if ($categoryFilter !== false) {
                    $arFilterElements['SECTION_CODE'] = $categoryFilter;
                }

                $arSelectElements = array('ID', 'NAME', 'CODE', 'PROPERTY_*', '*');

                $arOrderElements = array('DATE_CREATE' => 'DESC'); // Сортировка по дате добавления в убывающем порядке

                $resElements = \CIBlockElement::GetList($arOrderElements, $arFilterElements, false, false, $arSelectElements);

if ($resElements->SelectedRowsCount() > 0): ?>
                <div class="club__wrap-grid">
                    <?php while ($element = $resElements->GetNext()): ?>
                        <?php
                        ?>
                        <div class="club__wrap-grid__item">
                            <a href="<?= $element["DETAIL_PAGE_URL"] ?>" class="club__wrap-link">
                                <?php
                                $img = CFile::ResizeImageGet(
                                    $element["PREVIEW_PICTURE"],
                                    array("width" => 356, "height" => 240),
                                    BX_RESIZE_IMAGE_EXACT,
                                    true
                                );
                                ?>
                                <img src="<?= $img["src"] ?>" width="<?= $img["width"] ?>" height="<?= $img["height"] ?>" loading="lazy" class="club__wrap-img" alt="">
                            </a>
                            <h6 class="h6 club__wrap-title"><a href="<?= $element["DETAIL_PAGE_URL"] ?>"><?= $element["NAME"] ?></a></h6>
                            <div class="club__wrap-descript">
                                <?= $element["PREVIEW_TEXT"] ?>
                            </div>
                            <?php if (!empty($element["IBLOCK_SECTION_ID"])): ?>
                                <?php
                                $arSection = \CIBlockSection::GetByID($element["IBLOCK_SECTION_ID"])->Fetch();
                                if ($arSection): ?>
                                    <span class="club__wrap-label"><?= $arSection["NAME"] ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="stat">
                                <div class="stat__date"><?= $element["ACTIVE_FROM"] ?></div>
                                <div class="stat__view"><svg><use xlink:href="#eye"></use></svg>0</div>
                                <div class="stat__comments"><svg><use xlink:href="#comment"></use></svg>0</div>
                            </div>
                        </div>
                        <!-- /.club__wrap-grid__item -->
                    <?php endwhile; ?>
                </div>
                <?php if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?><?= $resElements->GetPageNavStringEx($navComponentObject, '', '', 'Y'); ?><?php endif; ?>
                <?php endif;?>



                <!-- /.club__wrap-grid -->
            </div>
            <!-- /.club__wrap -->
        </div>
        <!-- /.club__content -->
    </div>
    <!-- /.club -->
<style>
    .wrapper .wraps>.wrapper_inner{

        padding-left: 0;
        padding-right: 0;
        padding-top: 0;
    }
    #menu-top-container{
        display: none;
    }
    .breadcrumbs{
        padding-bottom: 11px;
    }
</style>


<?if ($arParams["SHOW_FAQ_BLOCK"]=="Y"):?></div><?endif;?>