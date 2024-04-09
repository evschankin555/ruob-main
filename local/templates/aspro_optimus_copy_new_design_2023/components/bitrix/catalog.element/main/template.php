<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="basket_props_block" id="bx_basket_div_<?=$arResult["ID"];?>" style="display: none;">
	<?if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])){
		foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo){?>
			<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
			<?if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
				unset($arResult['PRODUCT_PROPERTIES'][$propID]);
		}
	}
	$arResult["EMPTY_PROPS_JS"]="Y";
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if (!$emptyProductProperties){
		$arResult["EMPTY_PROPS_JS"]="N";?>
		<div class="wrapper">
			<table>
				<?foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo){?>
					<tr>
						<td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
						<td>
							<?if('L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE'] && 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']){
								foreach($propInfo['VALUES'] as $valueID => $value){?>
									<label>
										<input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
									</label>
								<?}
							}else{?>
								<select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]">
									<?foreach($propInfo['VALUES'] as $valueID => $value){?>
										<option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
									<?}?>
								</select>
							<?}?>
						</td>
					</tr>
				<?}?>
			</table>
		</div>
	<?}?>
</div>
<?
$this->setFrameMode(true);
$currencyList = '';
if (!empty($arResult['CURRENCIES'])){
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'STORES' => array(
		"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
		"SCHEDULE" => $arParams["SCHEDULE"],
		"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
		"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
		"ELEMENT_ID" => $arResult["ID"],
		"STORE_PATH"  =>  $arParams["STORE_PATH"],
		"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
		"MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
		"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
		"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
		"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
		"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
		"USER_FIELDS" => $arParams['USER_FIELDS'],
		"FIELDS" => $arParams['FIELDS'],
		"STORES" => $arParams['STORES'] = array_diff($arParams['STORES'], array('')),
	)
);
unset($currencyList, $templateLibrary);

$arSkuTemplate = array();
if (!empty($arResult['SKU_PROPS'])){
	$arSkuTemplate=COptimus::GetSKUPropsArray($arResult['SKU_PROPS'], $arResult["SKU_IBLOCK_ID"], "list", $arParams["OFFER_HIDE_NAME_PROPS"]);
}
$strMainID = $this->GetEditAreaId($arResult['ID']);

$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$arResult["strMainID"] = $this->GetEditAreaId($arResult['ID']);
$arItemIDs=COptimus::GetItemsIDs($arResult, "Y");
$totalCount = COptimus::GetTotalCount($arResult);
$arQuantityData = COptimus::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"], "Y");
$arQuantityData['HTML'] = str_replace("Нет в наличии", "Уточняйте наличие", $arQuantityData["HTML"]);

$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());
$useStores = $arParams["USE_STORE"] == "Y" && $arResult["STORES_COUNT"] && $arQuantityData["RIGHTS"]["SHOW_QUANTITY"];
$showCustomOffer=(($arResult['OFFERS'] && $arParams["TYPE_SKU"] !="N") ? true : false);
if($showCustomOffer){
	$templateData['JS_OBJ'] = $strObName;
}
$strMeasure='';
if($arResult["OFFERS"]){
	$strMeasure=$arResult["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
	$templateData["STORES"]["OFFERS"]="Y";
	foreach($arResult["OFFERS"] as $arOffer){
		$templateData["STORES"]["OFFERS_ID"][]=$arOffer["ID"];
	}
}else{
	if (($arParams["SHOW_MEASURE"]=="Y")&&($arResult["CATALOG_MEASURE"])){
		$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arResult["CATALOG_MEASURE"]), false, false, array())->GetNext();
		$strMeasure=$arMeasure["SYMBOL_RUS"];
	}
	$arAddToBasketData = COptimus::GetAddToBasketArray($arResult, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'big_btn w_icons', $arParams);
}
$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);

// save item viewed
$arFirstPhoto = reset($arResult['MORE_PHOTO']);
$arItemPrices = $arResult['MIN_PRICE'];
if(isset($arResult['PRICE_MATRIX']) && $arResult['PRICE_MATRIX'])
{
	$rangSelected = $arResult['ITEM_QUANTITY_RANGE_SELECTED'];
	$priceSelected = $arResult['ITEM_PRICE_SELECTED'];
	if(isset($arResult['FIX_PRICE_MATRIX']) && $arResult['FIX_PRICE_MATRIX'])
	{
		$rangSelected = $arResult['FIX_PRICE_MATRIX']['RANGE_SELECT'];
		$priceSelected = $arResult['FIX_PRICE_MATRIX']['PRICE_SELECT'];
	}
	$arItemPrices = $arResult['ITEM_PRICES'][$priceSelected];
	$arItemPrices['VALUE'] = $arItemPrices['PRICE'];
	$arItemPrices['PRINT_VALUE'] = \Aspro\Functions\CAsproOptimusItem::getCurrentPrice('PRICE', $arItemPrices);
	$arItemPrices['DISCOUNT_VALUE'] = $arItemPrices['BASE_PRICE'];
	$arItemPrices['PRINT_DISCOUNT_VALUE'] = \Aspro\Functions\CAsproOptimusItem::getCurrentPrice('BASE_PRICE', $arItemPrices);
}
$arViewedData = array(
	'PRODUCT_ID' => $arResult['ID'],
	'IBLOCK_ID' => $arResult['IBLOCK_ID'],
	'NAME' => $arResult['NAME'],
	'DETAIL_PAGE_URL' => $arResult['DETAIL_PAGE_URL'],
	'PICTURE_ID' => $arResult['PREVIEW_PICTURE'] ? $arResult['PREVIEW_PICTURE']['ID'] : ($arFirstPhoto ? $arFirstPhoto['ID'] : false),
	'CATALOG_MEASURE_NAME' => $arResult['CATALOG_MEASURE_NAME'],
	'MIN_PRICE' => $arItemPrices,
	'CAN_BUY' => $arResult['CAN_BUY'] ? 'Y' : 'N',
	'IS_OFFER' => 'N',
	'WITH_OFFERS' => $arResult['OFFERS'] ? 'Y' : 'N',
);

$actualItem = $arResult["OFFERS"] ? (isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]) ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']] : reset($arResult['OFFERS'])) : $arResult;
?>
<script type="text/javascript">
setViewedProduct(<?=$arResult['ID']?>, <?=CUtil::PhpToJSObject($arViewedData, false)?>);
</script>
<meta itemprop="name" content="<?=$name = strip_tags(!empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arResult['NAME'])?>" />
<meta itemprop="category" content="<?=$arResult['CATEGORY_PATH']?>" />
<meta itemprop="description" content="<?=(strlen(strip_tags($arResult['PREVIEW_TEXT'])) ? strip_tags($arResult['PREVIEW_TEXT']) : (strlen(strip_tags($arResult['DETAIL_TEXT'])) ? strip_tags($arResult['DETAIL_TEXT']) : $name))?>" />

<div class="card-product">
    <div class="container card-product__container">
        <div class="card">
            <div class="card__slider">
                <div class="card__colright-title"><?=$arResult["NAME"]?></div>
                <div class="card-slider">
                    <div class="card-slider__big js-card-slider-big">
                        <?php
                        if ($arResult["MORE_PHOTO"]) {
                            foreach ($arResult["MORE_PHOTO"] as $i => $arImage) {
                                $isEmpty = empty($arImage["SMALL"]["src"]);
                                ?>
                                <div>
                                    <div class="card-slider__big-wrap">
                                        <img src="<?=$isEmpty ? $arImage["SRC"] : $arImage["SMALL"]["src"]?>" width="238" height="450" loading="lazy" alt="<?=$arImage["ALT"]?>">
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <!-- /.card-slider__big -->
                    <div class="card-slider__bottom">
                        <button type="button" class="card-slider__arrow card-slider__arrow-prev" id="js-card-slider-prev"><svg><use xlink:href="#arrow-prev"></use></svg></button>
                        <div class="card-slider__thumb js-card-slider-thumb">
                            <?php
                            if ($arResult["MORE_PHOTO"]) {
                                foreach ($arResult["MORE_PHOTO"] as $i => $arImage) {
                                    $isEmpty = empty($arImage["SMALL"]["src"]);
                                    ?>
                                    <div>
                                        <div class="card-slider__thumb-slide"><img src="<?=$isEmpty ? $arImage["SRC"] : $arImage["SMALL"]["src"]?>" width="46" height="88" alt="<?=$arImage["ALT"]?>"></div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <!-- /.card-slider__thumb -->
                        <button type="button" class="card-slider__arrow card-slider__arrow-next" id="js-card-slider-next"><svg><use xlink:href="#arrow-next"></use></svg></button>
                    </div>
                    <!-- /.card-slider__bottom -->
                </div>
                <!-- /.card-slider -->
            </div>
            <!-- /.card__slider -->
            <div class="products-link">
                <?php
                use Bitrix\Main\Loader;
                use Bitrix\Iblock\ElementTable;
                use Bitrix\Main\Data\Cache;

                // Подключаем модуль инфоблоков
                Loader::includeModule('iblock');

                $cache = Cache::createInstance();
                $cacheTime = 86400; // 24 часа
                $cacheId = 'brandData_' . md5($APPLICATION->GetCurPage()); // Уникальный ID кеша на основе текущего URL
                $cacheDir = '/brand_data/'; // Каталог для хранения кеша

                if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
                    // Если кеш валиден, получаем сохраненные данные
                    extract($cache->getVars());
                } elseif ($cache->startDataCache()) {
                    // ID элемента инфоблока и имя производителя
                    $brandElementId = $arResult["DISPLAY_PROPERTIES"]["CML2_MANUFACTURER"]['VALUE_XML_ID'];
                    $manufacturer = $arResult["DISPLAY_PROPERTIES"]["CML2_MANUFACTURER"]["VALUE"];
                    $manufacturerUrl = '/info/brands/' . $brandElementId.'/';
                    $brandImage = "";

                    if (!empty($brandElementId)) {
                        $brandFilter = ['IBLOCK_ID' => 9, 'CODE' => $brandElementId];
                        $brandSelect = ["ID", "NAME", "PREVIEW_PICTURE"];
                        $brandResult = ElementTable::getList([
                            'select' => $brandSelect,
                            'filter' => $brandFilter,
                        ]);

                        if ($brandData = $brandResult->fetch()) {
                            $brandImageId = $brandData['PREVIEW_PICTURE'];
                            if (!empty($brandImageId)) {
                                $brandImage = CFile::GetPath($brandImageId);
                            }
                        }
                    }

                    $NameCategory = $arResult['SECTION']['NAME'];
                    $urlFilterBrandCategory = $arResult['SECTION']['SECTION_PAGE_URL'].'filter/cml2_manufacturer-is-'.$brandElementId.'/apply/';

                    // Сохраняем данные в кеш
                    $cache->endDataCache([
                        'brandElementId' => $brandElementId,
                        'manufacturer' => $manufacturer,
                        'manufacturerUrl' => $manufacturerUrl,
                        'brandImage' => $brandImage,
                        'NameCategory' => $NameCategory,
                        'urlFilterBrandCategory' => $urlFilterBrandCategory,
                    ]);
                }
                ?>
                <div class="products-link__item">
                    <?php if (!empty($brandImage)) : ?>
                        <img src="<?=htmlspecialchars($brandImage)?>" class="products-link__logo" width="190" height="46" alt="<?=htmlspecialchars($manufacturer)?>">
                    <?php endif; ?>
                    <div class="products-link__logo2">Официальный дилер</div>
                </div>
                <div class="products-link__item">
                    <a href="<?=$manufacturerUrl?>" class="products-link__link">Все товары <?=$manufacturer?></a>
                    <a href="<?=$urlFilterBrandCategory?>" class="products-link__link">Все <?=$NameCategory?> <?=$manufacturer?></a>
                </div>
            </div>
            <div class="card__article">
                <h6 class="h6 card__article-title">Описание</h6>
                <p><?=$arResult["DETAIL_TEXT"]?></p>
                <h6 class="h6 card__article-title mt60" id="char">Характеристики</h6>
                <ul class="list-param list-param--white card__article-list-param">
                    <?php
                    foreach ($arResult["DISPLAY_PROPERTIES"] as $arProp):
                        if (!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):
                            ?>
                            <li class="list-param__item">
                                <span><?=$arProp["NAME"]?></span>
                                <span><?=$arProp["DISPLAY_VALUE"]?></span>
                            </li>
                        <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
                <div class="your-rev">
                    <div class="your-rev__title">Оставьте свой отзыв</div>
                    <div class="write-review__icons set-review">
                        <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                        <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                        <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                        <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                        <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                    </div>
                </div>

                <div class="certif">
                    <div class="certif__title">Документация</div>
                    <a href="#contactsBox" class="certif__link">Запросите документы у менеджера</a>
                </div>
            </div>
            <!-- /.card__article -->
            <div class="card__colright">
                <div class="grayDescript">
                    <?php
                    if(!empty($arResult['CATALOG_QUANTITY']) && $arResult['CATALOG_QUANTITY'] > 0): ?>
                        <div class="availability">В наличии <span> (<?=$arResult['CATALOG_QUANTITY']?>)</span></div>
                    <?php else: ?>
                        <div class="item-stock" id="bx_3966226736_471809_store_quantity">
                            <span class="icon  order" style="zoom:2;"></span>
                            <a class="value callback_btn" style="color: rgb(136, 136, 136);margin-left: 5px;font-size: 20px;font-weight: 400;line-height: 22px">Заказать</a></div>
                    <?php endif; ?>
                    <h1 class="card__colright-title"><?=$arResult['NAME']?></h1>

                    <div class="card__colright-row">
                        <div class="card__colright-price"><?=CCurrencyLang::CurrencyFormat($arResult['MIN_PRICE']['VALUE'], $arResult['MIN_PRICE']['CURRENCY'], true)?></div>
                        <a class="card__colright-cheaper callback_btn">нашли дешевле?</a>
                    </div>

                    <div class="card__reviews">
                        <div class="write-review__icons set-review">
                            <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                            <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                            <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                            <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                            <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                        </div>
                        <span class="card__reviews-count">0 отзывов</span>
                    </div>
                    <div class="card__articul">Артикул: <span><?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span></div>
                    <ul class="list-param card__list-param">
                        <?php
                        $maxLines = 4;
                        $lineCount = 0;
                        foreach ($arResult["DISPLAY_PROPERTIES"] as $arProp):
                            if (!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):
                                if ($lineCount < $maxLines):
                                    ?>
                                    <li class="list-param__item">
                                        <span><?= $arProp["NAME"] ?></span>
                                        <span><?= $arProp["DISPLAY_VALUE"] ?></span>
                                    </li>
                                    <?php
                                    $lineCount++;
                                else:
                                    break;
                                endif;
                            endif;
                        endforeach;
                        ?>
                    </ul>
                    <a href="#char" class="small-btn js-nav">Посмотреть все характеристики</a>
                    <script>
                    jQuery(document).ready(function() {
                        jQuery('.catalog_detail .tabs_section .tabs_content .form.inline input[data-sid="PRODUCT_NAME"]').attr('value', jQuery('h1').text());
                    });
                    </script>

                    <?if($arAddToBasketData["ACTION"] !== "NOTHING"):?>
                        <?if($arAddToBasketData["ACTION"] == "ADD" && $arResult["CAN_BUY"] && $arParams["SHOW_ONE_CLICK_BUY"]!="N"):?>
                            <div class="card__colright-btns">
                                <a class="order-btn one_click" data-item="<?=$arResult["ID"]?>" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=$arAddToBasketData["MIN_QUANTITY_BUY"];?>" onclick="oneClickBuy('<?=$arResult["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">ЗАКАЗАТЬ В ОДИН КЛИК</a>
                                <?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
                                    <?if(!$arResult["OFFERS"]):?>
                                        <div class="wish_item_button">
									<span class="wish_item to" data-item="<?=$arResult["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>">
                                        <svg class="heart-btn"><use xlink:href="#heart"></use></svg>
                                       <?php /* <div><?=GetMessage('CATALOG_WISH')?></div>*/ ?>
                                    </span>
                                            <span class="wish_item in added" style="display: none;" data-item="<?=$arResult["ID"]?>"
                                                  data-iblock="<?=$arResult["IBLOCK_ID"]?>">
                                        <svg class="heart-btn"><use xlink:href="#heart"></use></svg>
                                        <?php /*<div><?=GetMessage('CATALOG_WISH_OUT2')?></div>*/ ?>
                                    </span>
                                        </div>
                                    <?elseif($arResult["OFFERS"] && !empty($arResult['OFFERS_PROP'])):?>
                                        <div class="wish_item_button">
                                            <div class="wish_item text " data-item="" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>">
										<span class="value <?=$arParams["TYPE_SKU"];?>">
                                                <svg class="heart-btn"><use xlink:href="#heart"></use></svg>
                                            <?php /*<div><?=GetMessage('CATALOG_WISH')?></div>*/ ?>
                                        </span>
                                                <span class="value added <?=$arParams["TYPE_SKU"];?>">
                                            <svg class="heart-btn"><use xlink:href="#heart"></use></svg>
                                            <?php /*<div><?=GetMessage('CATALOG_WISH_OUT2')?></div>*/ ?>
                                        </span>
                                            </div>
                                        </div>
                                    <?endif;?>
                                <?endif;?>
                                <?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
                                    <?if(!$arResult["OFFERS"] || ($arParams["TYPE_SKU"] !== 'TYPE_1' || ($arParams["TYPE_SKU"] == 'TYPE_1' && !$arResult["OFFERS_PROP"]))):?>
                                        <div class="compare_item_button">
									<span class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arResult["ID"]?>" >
                                           <svg class="chart-btn"><use xlink:href="#chart3"></use></svg>
                                       <?php /* <div><?=GetMessage('CATALOG_COMPARE')?></div>*/ ?>
                                    </span>
                                            <span class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>"
                                                  data-item="<?=$arResult["ID"]?>">
                                        <svg class="chart-btn"><use xlink:href="#chart3"></use></svg>
                                       <?php /* <div><?=GetMessage('CATALOG_COMPARE_OUT')?></div>*/ ?>
                                    </span>
                                        </div>
                                    <?elseif($arResult["OFFERS"]):?>
                                        <div class="compare_item_button">
									<span class="compare_item to <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>"
                                          data-item="" >
                                           <svg class="chart-btn"><use xlink:href="#chart3"></use></svg>
                                       <?php /* <div>
                                            <?=GetMessage('CATALOG_COMPARE')?></div>*/ ?>

                                    </span>
                                            <span class="compare_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;"
                                                  data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="">
                                        <svg class="chart-btn"><use xlink:href="#chart3"></use></svg>
                                        <?php /*<div><?=GetMessage('CATALOG_COMPARE_OUT')?></div> */ ?>
 </span>
                                        </div>
                                    <?endif;?>
                                <?endif;?>
                            </div>
                        <?endif;?>
                    <?endif;?>



                        <div class="card__colright-btns card__colright-btns--2">
                            <div id="<? echo $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER" /*&& !$arResult["CAN_BUY"]*/) || !$arResult["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arAddToBasketData["ACTION"] == "SUBSCRIBE" && $arResult["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>">
                                <!--noindex-->
                                <?=$arAddToBasketData["HTML"]?>
                                <!--/noindex-->
                            </div>
                            <div class="user-count">
                                <span class="user-count__btn minus-icon js-minus"></span>
                                <input type="text" class="user-count__input" id="output" value="1" readonly>
                                <span class="user-count__btn js-plus"><svg class="svg-plus"><use xlink:href="#plus"></use></svg></span>
                            </div>
                        </div>
                    <div class="card__colright-footer">
                        <div class="card__colright-footer__title">Работаем с юридическими лицами</div>
                        <div class="card__colright-descript">
                            Безналичный расчет без комиссии, НДС, доставка по России
                        </div>
                        <?php
                        $currentURL = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                        $params = array();
                        // Добавим артикул в массив $params
                        $params["CML2_ARTICLE"]["NAME"] = "Артикул";
                        $params["CML2_ARTICLE"]["VALUE"] = $arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"];

                        foreach ($arResult["DISPLAY_PROPERTIES"] as $arProp) {
                            if (!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))) {
                                // Добавим название параметра и его значение в массив $params
                                $params[$arProp["CODE"]]["NAME"] = $arProp["NAME"];
                                $params[$arProp["CODE"]]["VALUE"] = $arProp["DISPLAY_VALUE"];
                            }
                        }


                        // Формируем ссылку с дополнительными параметрами
                        $queryString = http_build_query($params);
                        $link = "/pdf.php?id=" . $arResult["ID"] . "&current_page=" . urlencode($currentURL) . "&" . $queryString;
                        ?>

                        <a href="<?= $link ?>" class="card__colright-footer__client">Cформировать коммерческое предложение</a>

                    </div>
                    <!-- /.card__colright-footer -->
                </div>


                <!-- /.grayDescript -->
                <div class="orangeBlock">
                    <div class="orangeBlock__text">
                        В лизинг выгоднее <img class="main-product-img" src="/dist/images/dist/arenza-logo.png" alt="">
                    </div>
                    <script src="https://forma.tinkoff.ru/static/onlineScript.js"></script>
                    <a class="orangeBlock__btn"  onclick="widgetInit()">Оформить</a>
                </div>
                   <script src="https://arenza.ru/arenza-partner-widget.min.js">
                    </script>
                    <script>
                        function widgetInit() {
                            ArenzaPartnerWidget.init({
                                token: 'CQqTdXeTa2igIqGs1cl5QBnST6a523ZDQGKhpuAHB8k',
                                amount: <?=$arResult["PRICES"]['BASE']['VALUE']?>,
                                orderItems: [
                                    {
                                        id: <?=$arResult["ID"]?>,
                                        name: '<?=$arResult["NAME"]?>',
                                        quantity: 1,
                                        price: <?=$arResult["PRICES"]['BASE']['VALUE']?>,
                                    },
                                ],


                            });
                        }
                    </script>


                <div class="credit-block" style="cursor: pointer"
                     onclick="tinkoff.create(
                             {
                             sum: <?=$arResult['PRICES']['BASE']['VALUE']?>,
                             items: [{name: '<?=$arResult['NAME']?>', price: <?=$arResult['PRICES']['BASE']['VALUE']?>, quantity: 1}],
                             promoCode: 'default',
                             shopId: 'e1413c51-5daa-4bed-970f-64061a214b19',
                             showcaseId: 'da084094-309b-4729-b6f6-505d8b514e32',
                             },
                             {view: 'modal'}
                             )">
                    <div class="credit-block__title tinkoff">Покупайте товар в кредит</div>
                    <div class="credit-block__row">
                        <?php
                        $result = round($arResult['PRICES']['BASE']['VALUE'] / 24 * 1.205, );
                        $raschet = number_format($result, 0, '.', ' ');
                        ?>
                        <div class="credit-block__row-item">В кредит от <span><?=$raschet?> ₽</span>/мес</div>
                        <img src="/dist/images/dist/tinkoff.png" alt="">
                        <img src="/dist/images/dist/other-logo.png" alt="">
                    </div>
                </div>

                <div class="bite_price">
                    <?
                    $APPLICATION->IncludeComponent(
                        "revo:buy.link",
                        "",
                        Array(
                            // Элемент отображения цены
                            "PRICE" => $arResult['PRICES']['BASE']['VALUE'],
                            "BUY_BTN_SELECTOR" => '#'
                        )
                    );
                    ?>
                </div>
                <?php /*
                <div class="delivery-order" id="deliveryOrderContainer"
                     data-weight="<?php echo isset($arResult['PROPERTIES']['VES']['VALUE_ENUM']) ? htmlspecialchars($arResult['PROPERTIES']['VES']['VALUE_ENUM']) : 1; ?>">
                    <div class="delivery-order-ajax">

                    </div>
                    <?php
                    // Исходная строка
                    $originalString = $arResult['PROPERTIES']['VES']['VALUE_ENUM'];

                    // Используем floatval для извлечения числа
                    $numericValue = floatval($originalString);

                    // Устанавливаем значение по умолчанию в 1, если $numericValue равно 0 или не существует
                    $defaultValue = ($numericValue == 0 || !isset($numericValue)) ? 1 : $numericValue;
                    ?>
                <div id="eShopLogisticWidgetModal"
                         data-lazy-load="true"
                         data-key="331792-174-238"
                         data-offers='[{ "weight":<?=$defaultValue?> }]'>
                    </div>
                    <!-- /.deliver-order__list -->
                    <a href="#" class="delivery-order__btn" data-esl-widget>Рассчитать стоимость доставки в ваш город</a>
                </div>
                <script>
                    $(document).ready(function() {
                        var weight = $('#deliveryOrderContainer').attr('data-weight');
                        var randomParam = Math.random(); // Генерируем случайное число

                        // AJAX запрос при загрузке страницы с передачей параметра weight и случайного числа
                        $.ajax({
                            url: '/ajax_delavery.php?random=' + randomParam, // Добавляем случайное число к URL
                            method: 'POST',
                            dataType: 'html',
                            data: { weight: weight },
                            success: function(response) {
                                $('#deliveryOrderContainer .delivery-order-ajax').html(response);
                            },
                            error: function(error) {
                                console.log('Error:', error);
                            }
                        });
                    });


                </script>*/?>
                <!-- /.delivery-order -->
                <div class="payment-way">
                    <div class="payment-way__title">Принимаем оплату</div>
                    <div class="payment-way__icons">
                        <picture>
                            <source srcset="/dist/images/dist/payment-way-1-1024.png" media="(max-width: 1300px)">
                            <img src="/dist/images/dist/payment-way-1.png" alt="">
                        </picture>
                        <picture>
                            <source srcset="/dist/images/dist/payment-way-2-1024.png" media="(max-width: 1300px)">
                            <img src="/dist/images/dist/payment-way-2.png" alt="">
                        </picture>
                        <picture>
                            <source srcset="/dist/images/dist/payment-way-3-1024.png" media="(max-width: 1300px)">
                            <img src="/dist/images/dist/payment-way-3.png" alt="">
                        </picture>
                        <picture>
                            <source srcset="/dist/images/dist/payment-way-4-1024.png" media="(max-width: 1300px)">
                            <img src="/dist/images/dist/payment-way-4.png" alt="">
                        </picture>
                        <picture>
                            <source srcset="/dist/images/dist/payment-way-5-1024.png" media="(max-width: 1300px)">
                            <img src="/dist/images/dist/payment-way-5.png" alt="">
                        </picture>
                        <picture>
                            <source srcset="/dist/images/dist/payment-way-6-1024.png" media="(max-width: 1300px)">
                            <img src="/dist/images/dist/payment-way-6.png" alt="">
                        </picture>
                    </div>
                </div>

            </div>
            <!-- /.card__colright -->
        </div>
        <!-- /.card -->

        <div class="trust">
            <div class="trust__inner">
                <h3 class="h3 trust__title" id="white-font">Мы превращаем сложное в простое!</h3>
                <div class="trust__grid">
                    <div class="trust__grid-item">
                        <div class="trust__grid-title">Быстрая доставка по России</div>
                        <div class="trust__grid-descript">
                            Вы ищете быструю и удобную доставку? Тогда наш интернет-гипермаркет - именно то, что вам нужно! Благодаря современным логистическим системам, Вы получите заказ в максимально короткие сроки!
                        </div>
                    </div>
                    <div class="trust__grid-item">
                        <div class="trust__grid-title">Гарантия лучшей цены</div>
                        <div class="trust__grid-descript">
                            Вам больше не нужно искать долго и сравнивать цены! Мы гарантируем, что Вы не найдете лучшей цены на наши товары в других интернет-магазинах. Иначе мы снизим стоимость и предоставим вам дополнительную скидку.
                        </div>
                    </div>
                    <div class="trust__grid-item">
                        <div class="trust__grid-title">Гарантия качества</div>
                        <div class="trust__grid-descript">
                            Мы работаем только с проверенными брендами и производителями, чтобы гарантировать высокое качество всех наших товаров.
                        </div>
                    </div>
                    <div class="trust__grid-item">
                        <div class="trust__grid-title">Высокий кэшбек</div>
                        <div class="trust__grid-descript">
                            Получите еще больше выгоды от покупок! Мы предлагаем самый щедрый кэшбек на рынке, покупайте товары и забирайте начисленный кэшбек.
                        </div>
                    </div>
                </div>
                <!-- /.trust__grid -->
            </div>
            <!-- /.trust__inner -->

        </div>
        <!-- /.trust -->
        <div class="reviews">
            <ul class="reviews__title js-tabs">
                <li>
                    <a class="is-active title-tab-heading js-reviews-tab" href="#reviews"><?=($arParams["TAB_REVIEW_NAME"] ? $arParams["TAB_REVIEW_NAME"] : GetMessage("REVIEW_TAB"))?><span class="count empty"></span></a>
                </li> <li>
                    <a href="#questions">Вопрос/Ответ</a>
                </li></ul>
            <div class="tabContent js_tabContent">
                <div id="reviews">
                    <div class="reviews__grid">
                        <div class="reviews__grid-left">
                            <script>
                                jQuery(document).ready(function() {
                                    jQuery('.js-reviews-tab').trigger('click');
                                    setTimeout(function (){
                                        var idReview = jQuery('.reviews-collapse-link.button.wicon').attr('id');
                                        jQuery('.blueBtn.write-review__blueBtn').attr('id',idReview ).addClass('reviews-collapse-link');
                                        jQuery('.reviews-collapse-link.button.wicon').attr('id', idReview + 'old').hide();

                                        jQuery('.reviews-block-container table thead tr td div b').each(function(){
                                            jQuery(this).prepend(jQuery('<div class="reviews__item-icon"><img src="/dist/images/dist/review-user-icon.png" loading="lazy" width="39" height="39" alt=""></div>'));
                                        });
                                    }, 777);


                                });
                            </script>
                            <div id="for_product_reviews_tab"></div>
                        </div>
                        <!-- /.reviews__grid-left -->
                        <div class="reviews__grid-right">
                            <div class="write-review">
                                <div class="write-review__icons">
                                    <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                                    <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                                    <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                                    <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                                    <svg class="write-review__icon"><use xlink:href="#star-one"></use></svg>
                                </div>
                                <strong class="write-review__raiting"> 5 / 5</strong>
                                <a class="blueBtn write-review__blueBtn">Написать отзыв</a><!---->
                            </div>
                        </div>
                        <!-- /.reviews__grid-right -->
                    </div>
                    <!-- /.reviews__grid -->
                </div>
                <div id="questions">
                    <div class="answers">
                        <div class="wrap_md forms">
                            <div class="iblock text_block">
                                <?$APPLICATION->IncludeFile(SITE_DIR."include/ask_tab_detail_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ASK_DESCRIPTION')));?>
                            </div>
                            <div class="iblock form_block">
                                <div id="ask_block"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /.answers -->
                </div>
            </div>
            <!-- /.tabContent -->
        </div>
        <!-- /.reviews -->

        <div class="contactsBox">
            <a href="tel:+78007074263" class="contactsBox__link">
                <img src="/dist/images/dist/phone-icon-white-35x35.png" alt="">
                Позвонить<br> 8 (800) 707-42-63
            </a>
            <a href="mailto:info@ruoborudovanie.ru" class="contactsBox__link">
                <img src="/dist/images/dist/mail-icon-white-35x35.png" alt="">
                Написать на Email
            </a>
            <a class="contactsBox__link callback_btn">
                <img src="/dist/images/dist/headphones-icon-white-35x35.png" alt="">
                Заказать обратный звонок
            </a>
            <a class="contactsBox__link one_click" data-item="<?=$arResult["ID"]?>" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=$arAddToBasketData["MIN_QUANTITY_BUY"];?>" onclick="oneClickBuy('<?=$arResult["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
                <img src="/dist/images/dist/basket-icon.png" alt="" >
                Купить товар в 1 клик
            </a>
        </div>
        <!-- /.contactsBox -->
        <div class="timework">
            График работы: Пн-Пт: 9.00-18.00 <span>Сб-Вс: выходной</span>
        </div>
        <div class="productsBox">
            <h3 class="h3 productsBox__title">Похожие товары</h3>
            <?php


            $categoryID = $arResult['IBLOCK_SECTION_ID'];

            $arFilter = array(
                "IBLOCK_ID" => 14,
                "ACTIVE" => "Y",
                "SECTION_ID" => $categoryID,
                ">CATALOG_QUANTITY" => 0, // Только если количество товара больше 0
            );

            $arSelect = array(
                "ID",
                "NAME",
                "DETAIL_PAGE_URL",
                '*'
            );

            $arOrder = array(
                "RAND" => "ASC",
            );

            $rsItems = CIBlockElement::GetList($arOrder, $arFilter, false, array('nTopCount' => 4), $arSelect);

            ?>

            <div class="productsBox__grid">

                <?php while ($arItem = $rsItems->GetNext()):
                    $imageId = $arItem['PREVIEW_PICTURE'];
                    $fileData = CFile::GetFileArray($imageId);
                    $productID = $arItem['ID'];

                    // Получение объекта цены
                    $arPrice = CPrice::GetList(
                        array(),
                        array(
                            "PRODUCT_ID" => $productID,
                            "CATALOG_GROUP_ID" => 1,
                            ">PRICE" => 0, // Только если цена больше 0
                        )
                    )->Fetch();

                    // Проверка наличия цены и активности товара
                    if ($arPrice && $arItem['ACTIVE'] == 'Y'):
                        // Получение объекта количества в наличии
                        $arProduct = CCatalogProduct::GetByID($productID);
                        ?>

                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="product-item">
                            <img src="<?=$fileData["SRC"]?>" class="product-item__img" width="<?=$fileData["WIDTH"]?>"
                                 height="<?=$fileData["HEIGHT"]?>" loading="lazy" alt="">
                            <span class="product-item__title"><?= $arItem["NAME"] ?></span>
                            <span class="product-item__avail"><span>В наличии</span> (<?=$arProduct["QUANTITY"]?>)</span>
                            <span class="product-item__price"><?= number_format($arPrice["PRICE"], 0, ',', ' ') ?> ₽</span>
                        </a>

                    <?php
                    endif;
                endwhile;
                ?>
            </div>
            <!-- /.productsBox -->



        <div class="article-short">
            Cведения о товарах, размещённых на сайте, носят исключительно информационный характер и не являются публичной офертой. Производитель вправе менять технические характеристики, внешний вид, комплектацию и страну производства любой модели без предварительного уведомления. Пожалуйста, перед оформлением заказа, уточняйте характеристики интересующих Вас товаров у наших менеджеров
        </div>
    </div>
    <!-- /.container card-product__container -->
</div>
<!-- /.card-product -->


<?php
/*$APPLICATION->IncludeComponent(
    "bitrix:catalog.top",
    "products_block",
    array(
        "IBLOCK_TYPE" => "aspro_optimus_catalog",
        "IBLOCK_ID" => \Bitrix\Main\Config\Option::get("aspro.optimus", "CATALOG_IBLOCK_ID", '#IBLOCK_CATALOG_ID#'),
        "ELEMENT_SORT_FIELD" => "sort",
        "ELEMENT_SORT_ORDER" => "asc",
        "ELEMENT_COUNT" => 4,
        "LINE_ELEMENT_COUNT" => "",
        "PROPERTY_CODE" => array(
            0 => "",
            1 => "",
        ),
        "OFFERS_LIMIT" => "4",
        "SECTION_URL" => "",
        "DETAIL_URL" => "",
        "BASKET_URL" => SITE_DIR."basket/",
        "ACTION_VARIABLE" => "action",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600000",
        "CACHE_GROUPS" => "N",
        "DISPLAY_COMPARE" => "Y",
        "PRICE_CODE" => array(
            0 => "BASE",
        ),
        "USE_PRICE_COUNT" => "N",
        "SHOW_PRICE_COUNT" => "Y",
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_PROPERTIES" => array(
        ),
        "CONVERT_CURRENCY" => "N",
        "FILTER_NAME" => $arParams["CATALOG_FILTER_NAME"],
        "SHOW_BUY_BUTTONS" => $arParams["SHOW_BUY_BUTTONS"],
        "USE_PRODUCT_QUANTITY" => "N",
        "INIT_SLIDER" => "Y",
        "COMPONENT_TEMPLATE" => "products_slider",
        "HIDE_NOT_AVAILABLE" => "N",
        "OFFERS_FIELD_CODE" => array(
            0 => "ID",
            1 => "NAME",
            2 => "",
        ),
        "OFFERS_PROPERTY_CODE" => array(
            0 => "",
            1 => "",
        ),
        "SEF_MODE" => "N",
        "CACHE_FILTER" => "N",
        "SHOW_MEASURE" => "Y",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "OFFERS_CART_PROPERTIES" => array(
        ),
        "COMPARE_PATH" => "",
        "SHOW_DISCOUNT_PERCENT" => "Y",
        "SHOW_OLD_PRICE" => "Y",
        "SHOW_RATING" => "N",
        "DISPLAY_WISH_BUTTONS" => \Bitrix\Main\Config\Option::get('aspro.optimus', 'SHOW_DELAY', 'Y'),
        "SECTION_ID" => $arResult['IBLOCK_SECTION_ID'] ?: false,
    ),
    false, array("HIDE_ICONS"=>"N")
);*/
/*
<div class="item_main_info <?=(!$showCustomOffer ? "noffer" : "");?> <?=($arParams["SHOW_UNABLE_SKU_PROPS"] != "N" ? "show_un_props" : "unshow_un_props");?>" id="<?=$arItemIDs["strMainID"];?>">

    <div class="img_wrapper">
		<div class="stickers">
			<?if (is_array($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
				<?foreach($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
					<div><div class="sticker_<?=strtolower($class);?>"><?=$arResult["PROPERTIES"]["HIT"]["VALUE"][$key]?></div></div>
				<?}?>
			<?endif;?>
			<?if($arParams["SALE_STIKER"] && $arResult["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
				<div><div class="sticker_sale_text"><?=$arResult["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div></div>
			<?}?>
		</div>
		<div class="item_slider">
			<?if(false && ($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y") || (strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || ($arResult['SHOW_OFFERS_PROPS'] && $showCustomOffer))):?>
				<div class="like_wrapper">
					<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
						<div class="like_icons iblock">
							<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
								<?if(!$arResult["OFFERS"]):?>
									<div class="wish_item text" data-item="<?=$arResult["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>">
										<span class="value" title="<?=GetMessage('CT_BCE_CATALOG_IZB')?>" ><i></i></span>
										<span class="value added" title="<?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?>"><i></i></span>
									</div>
								<?elseif($arResult["OFFERS"] && $arParams["TYPE_SKU"] === 'TYPE_1' && !empty($arResult['OFFERS_PROP'])):?>
									<div class="wish_item text " data-item="" data-iblock="<?=$arResult["IBLOCK_ID"]?>" <?=(!empty($arResult['OFFERS_PROP']) ? 'data-offers="Y"' : '');?> data-props="<?=$arOfferProps?>">
										<span class="value <?=$arParams["TYPE_SKU"];?>" title="<?=GetMessage('CT_BCE_CATALOG_IZB')?>"><i></i></span>
										<span class="value added <?=$arParams["TYPE_SKU"];?>" title="<?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?>"><i></i></span>
									</div>
								<?endif;?>
							<?endif;?>
							<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
								<?if(!$arResult["OFFERS"] || ($arResult["OFFERS"] && $arParams["TYPE_SKU"] === 'TYPE_1' && !$arResult["OFFERS_PROP"])):?>
									<div data-item="<?=$arResult["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-href="<?=$arResult["COMPARE_URL"]?>" class="compare_item text <?=($arResult["OFFERS"] ? $arParams["TYPE_SKU"] : "");?>" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['COMPARE_LINK']; ?>">
										<span class="value" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE')?>"><i></i></span>
										<span class="value added" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?>"><i></i></span>
									</div>
								<?elseif($arResult["OFFERS"] && $arParams["TYPE_SKU"] === 'TYPE_1'):?>
									<div data-item="" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-href="<?=$arResult["COMPARE_URL"]?>" class="compare_item text <?=$arParams["TYPE_SKU"];?>">
										<span class="value" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE')?>"><i></i></span>
										<span class="value added" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?>"><i></i></span>
									</div>
								<?endif;?>
							<?endif;?>
						</div>
					<?endif;?>
				</div>
			<?endif;?>

			<?reset($arResult['MORE_PHOTO']);
			$arFirstPhoto = current($arResult['MORE_PHOTO']);
			$viewImgType=$arParams["DETAIL_PICTURE_MODE"];?>
			<div class="slides">
				<?if($showCustomOffer && !empty($arResult['OFFERS_PROP'])){?>
					<div class="offers_img wof">
						<?$alt=$arFirstPhoto["ALT"];
						$title=$arFirstPhoto["TITLE"];?>
						<?if($arFirstPhoto["BIG"]["src"]){?>
							<a href="<?=($viewImgType=="POPUP" ? $arFirstPhoto["BIG"]["src"] : "javascript:void(0)");?>" class="<?=($viewImgType=="POPUP" ? "popup_link" : "line_link");?>" title="<?=$title;?>">
								<img id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>" src="<?=$arFirstPhoto['SMALL']['src']; ?>" <?=($viewImgType=="MAGNIFIER" ? 'data-large="" xpreview="" xoriginal=""': "");?> alt="<?=$alt;?>" title="<?=$title;?>" itemprop="image">
							</a>
						<?}else{?>
							<a href="javascript:void(0)" class="" title="<?=$title;?>">
								<img id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>" src="<?=$arFirstPhoto['SRC']; ?>" alt="<?=$alt;?>" title="<?=$title;?>" itemprop="image">
							</a>
						<?}?>
					</div>
				<?}else{
					if($arResult["MORE_PHOTO"]){
						$bMagnifier = ($viewImgType=="MAGNIFIER");?>
						<ul>
							<?foreach($arResult["MORE_PHOTO"] as $i => $arImage){
								if($i && $bMagnifier):?>
									<?continue;?>
								<?endif;?>
								<?$isEmpty=($arImage["SMALL"]["src"] ? false : true );?>
								<?
								$alt=$arImage["ALT"];
								$title=$arImage["TITLE"];
								?>
								<li id="photo-<?=$i?>" <?=(!$i ? 'class="current"' : 'style="display: none;"')?>>
									<?if(!$isEmpty){?>
										<a href="<?=($viewImgType=="POPUP" ? $arImage["BIG"]["src"] : "javascript:void(0)");?>" <?=($bIsOneImage ? '' : 'data-fancybox-group="item_slider"')?> class="<?=($viewImgType=="POPUP" ? "popup_link fancy" : "line_link");?>" title="<?=$title;?>">
											<img  src="<?=$arImage["SMALL"]["src"]?>" <?=($viewImgType=="MAGNIFIER" ? "class='zoom_picture'" : "");?> <?=($viewImgType=="MAGNIFIER" ? 'xoriginal="'.$arImage["BIG"]["src"].'" xpreview="'.$arImage["THUMB"]["src"].'"' : "");?> alt="<?=$alt;?>" title="<?=$title;?>"<?=(!$i ? ' itemprop="image"' : '')?>/>
										</a>
									<?}else{?>
										<img  src="<?=$arImage["SRC"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
									<?}?>
								</li>
							<?}?>
						</ul>
					<?}
				}?>
			</div>123

			<?if(!$showCustomOffer || empty($arResult['OFFERS_PROP'])){
				if(count($arResult["MORE_PHOTO"]) > 1):?>
					<div class="wrapp_thumbs xzoom-thumbs">
						<div class="thumbs flexslider" data-plugin-options='{"animation": "slide", "selector": ".slides_block > li", "directionNav": true, "itemMargin":10, "itemWidth": 54, "controlsContainer": ".thumbs_navigation", "controlNav" :false, "animationLoop": true, "slideshow": false}' style="max-width:<?=ceil(((count($arResult['MORE_PHOTO']) <= 4 ? count($arResult['MORE_PHOTO']) : 4) * 64) - 10)?>px;">
							<ul class="slides_block" id="thumbs">
								<?foreach($arResult["MORE_PHOTO"]as $i => $arImage):?>
									<li <?=(!$i ? 'class="current"' : '')?> data-big_img="<?=$arImage["BIG"]["src"]?>" data-small_img="<?=$arImage["SMALL"]["src"]?>">
										<span><img class="xzoom-gallery" width="50" xpreview="<?=$arImage["THUMB"]["src"];?>" src="<?=$arImage["THUMB"]["src"]?>" alt="<?=$arImage["ALT"];?>" title="<?=$arImage["TITLE"];?>" /></span>
									</li>
								<?endforeach;?>
							</ul>
							<span class="thumbs_navigation custom_flex"></span>
						</div>
					</div>
					<script>
						$(document).ready(function(){
							$('.item_slider .thumbs li').first().addClass('current');
							$('.item_slider .thumbs .slides_block').delegate('li:not(.current)', 'click', function(){
								var slider_wrapper = $(this).parents('.item_slider'),
									index = $(this).index();
								$(this).addClass('current').siblings().removeClass('current')
								if(arOptimusOptions['THEME']['DETAIL_PICTURE_MODE'] == 'MAGNIFIER')
								{
									var li = $(this).parents('.item_slider').find('.slides li');
									li.find('img').attr('src', $(this).data('small_img'));
									li.find('img').attr('xoriginal', $(this).data('big_img'));
								}
								else
								{
									slider_wrapper.find('.slides li').removeClass('current').hide();
									slider_wrapper.find('.slides li:eq('+index+')').addClass('current').show();
								}
							});
						})
					</script>
				<?endif;?>
			<?}else{?>
				<div class="wrapp_thumbs">
					<div class="sliders">
						<div class="thumbs" style="">
						</div>
					</div>
				</div>
			<?}?>
		</div>

		<?if(!$showCustomOffer || empty($arResult['OFFERS_PROP'])){?>
			<div class="item_slider flex flexslider" data-plugin-options='{"animation": "slide", "directionNav": false, "controlNav": true, "animationLoop": false, "slideshow": true, "slideshowSpeed": 10000, "animationSpeed": 600}'>
				<ul class="slides">
					<?if($arResult["MORE_PHOTO"]){
						foreach($arResult["MORE_PHOTO"] as $i => $arImage){?>
							<?$isEmpty=($arImage["SMALL"]["src"] ? false : true );?>
							<li id="mphoto-<?=$i?>" <?=(!$i ? 'class="current"' : 'style="display: none;"')?>>
								<?
								$alt=$arImage["ALT"];
								$title=$arImage["TITLE"];
								?>
								<?if(!$isEmpty){?>
									<a href="<?=$arImage["BIG"]["src"]?>" data-fancybox-group="item_slider_flex" class="fancy" title="<?=$title;?>" >
										<img src="<?=$arImage["SMALL"]["src"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
									</a>
								<?}else{?>
									<img  src="<?=$arImage["SRC"];?>" alt="<?=$alt;?>" title="<?=$title;?>" />
								<?}?>
							</li>
						<?}
					}?>
				</ul>
			</div>
		<?}else{?>
			<div class="item_slider flex"></div>
		<?}?>
	</div>

	<div class="right_info">
		<div class="info_item">
			<?$isArticle=(strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || ($arResult['SHOW_OFFERS_PROPS'] && $showCustomOffer));?>
			<?if($isArticle || $arResult["BRAND_ITEM"] || $arParams["SHOW_RATING"] == "Y" || strlen($arResult["PREVIEW_TEXT"])){?>
				<div class="top_info">
					<div class="rows_block">
						<?$col=1;
						if($isArticle && $arResult["BRAND_ITEM"] && $arParams["SHOW_RATING"] == "Y"){
							$col=3;
						}elseif(($isArticle && $arResult["BRAND_ITEM"]) || ($isArticle && $arParams["SHOW_RATING"] == "Y") || ($arResult["BRAND_ITEM"] && $arParams["SHOW_RATING"] == "Y")){
							$col=2;
						}?>
						<?if($arParams["SHOW_RATING"] == "Y"):?>
							<div class="item_block col-<?=$col;?>">
								<?$frame = $this->createFrame('dv_'.$arResult["ID"])->begin('');?>
									<div class="rating">
										<?$APPLICATION->IncludeComponent(
										   "bitrix:iblock.vote",
										   "element_rating",
										   Array(
											  "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
											  "IBLOCK_ID" => $arResult["IBLOCK_ID"],
											  "ELEMENT_ID" => $arResult["ID"],
											  "MAX_VOTE" => 5,
											  "VOTE_NAMES" => array(),
											  "CACHE_TYPE" => $arParams["CACHE_TYPE"],
											  "CACHE_TIME" => $arParams["CACHE_TIME"],
											  "DISPLAY_AS_RATING" => 'vote_avg'
										   ),
										   $component, array("HIDE_ICONS" =>"Y")
										);?>
									</div>
								<?$frame->end();?>
							</div>
						<?endif;?>
						
						<?if($isArticle):?>
							<div class="item_block col-<?=$col;?>">
								<div style='display: inline-block;  padding: 0.5rem; font-size:1.2rem; border: 3px solid #ffd02e;' class="article iblock" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue" <?if($arResult['SHOW_OFFERS_PROPS']){?>id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_ARTICLE_DIV'] ?>" style="display: none;"<?}?>>
									<span class="block_title" itemprop="name"><?=GetMessage("ARTICLE");?>:</span>
									<span class="value" itemprop="value"><?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span>
								</div>
							</div>
						<?endif;?>

						<?if($arResult["BRAND_ITEM"]){?>
							<div class="item_block col-<?=$col;?>">
								<div class="brand">
									<meta itemprop="brand" content="<?=$arResult["BRAND_ITEM"]["NAME"]?>" />
									<?if(!$arResult["BRAND_ITEM"]["IMAGE"]):?>
										<b class="block_title"><?=GetMessage("BRAND");?>:</b>
										<a href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>"><?=$arResult["BRAND_ITEM"]["NAME"]?></a>
									<?else:?>
                                        <div class="brand_flex">
                                        <div class="product-core-info__brand-text">Официальный дилер</div>
										<a class="brand_picture" rel="nofollow" target="_blank" href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>">
											<img  src="<?=$arResult["BRAND_ITEM"]["IMAGE"]["src"]?>" alt="<?=$arResult["BRAND_ITEM"]["NAME"]?>" title="<?=$arResult["BRAND_ITEM"]["NAME"]?>" />
										</a>

                                        </div>
                                        <a class="textlink" rel="nofollow" target="_blank" href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>">
                                            Все товары бренда <?=$arResult["BRAND_ITEM"]["NAME"]?>
                                        </a>
									<?endif;?>
								</div>
							</div>
						<?}?>
					</div>

				</div>
			<?}?>
			<div class="middle_info main_item_wrapper">
				<?$frame = $this->createFrame()->begin();?>
				<div class="prices_block">
					<div class="cost prices clearfix">
						<?if( count( $arResult["OFFERS"] ) > 0 ){?>
							<div class="with_matrix" style="display:none;">
								<div class="price price_value_block"><span class="values_wrapper"></span></div>
								<?if($arParams["SHOW_OLD_PRICE"]=="Y"):?>
									<div class="price discount"></div>
								<?endif;?>
								<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
									<div class="sale_block matrix" style="display:none;">
										<div class="sale_wrapper">
										<div class="value">-<span></span>%</div>
										<div class="text"><span class="title"><?=GetMessage("CATALOG_ECONOMY");?></span>
										<span class="values_wrapper"></span></div>
										<div class="clearfix"></div></div>
									</div>
								<?}?>
							</div>
							<?\Aspro\Functions\CAsproOptimusSku::showItemPrices($arParams, $arResult, $item_id, $min_price_id, $arItemIDs);?>
						<?}else{?>
							<?
							$item_id = $arResult["ID"];
							if(isset($arResult['PRICE_MATRIX']) && $arResult['PRICE_MATRIX']) // USE_PRICE_COUNT
							{
								if($arResult['PRICE_MATRIX']['COLS'])
								{
									$arCurPriceType = current($arResult['PRICE_MATRIX']['COLS']);
									$arCurPrice = current($arResult['PRICE_MATRIX']['MATRIX'][$arCurPriceType['ID']]);
									$min_price_id = $arCurPriceType['ID'];?>
									<div class="" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
										<?$price = ($arResult['MIN_PRICE']['DISCOUNT_VALUE'] ? $arResult['MIN_PRICE']['DISCOUNT_VALUE'] : $arResult['MIN_PRICE']['VALUE'] )?>
										<?$currency = $arResult['MIN_PRICE']['CURRENCY'] ? $arResult['MIN_PRICE']['CURRENCY'] : $arParams['CURRENCY_ID']?>
										<meta itemprop="price" content="<?=($price ? $price : 0)?>" />
										<meta itemprop="priceCurrency" content="<?=$currency ? $currency : CSaleLang::GetLangCurrency(SITE_ID)?>" />
										
										<link itemprop="availability" href="http://schema.org/<?=($arResult['PRICE_MATRIX']['AVAILABLE'] == 'Y' ? 'InStock' : 'OutOfStock')?>" />
										<?
										if($arDiscount["ACTIVE_TO"]){?>
											<meta itemprop="priceValidUntil" content="<?=date("Y-m-d", MakeTimeStamp($arDiscount["ACTIVE_TO"]))?>" />
										<?}?>
										<link itemprop="url" href="<?=$arResult["DETAIL_PAGE_URL"]?>" />
									</div>
								<?}?>
								<?if($arResult['ITEM_PRICE_MODE'] == 'Q' && count($arResult['PRICE_MATRIX']['ROWS']) > 1):?>
									<?=COptimus::showPriceRangeTop($arResult, $arParams, GetMessage("CATALOG_ECONOMY"));?>
								<?endif;?>
								<?=COptimus::showPriceMatrix($arResult, $arParams, $strMeasure, $arAddToBasketData);?>
							<?
							}
							else
							{?>
								<?\Aspro\Functions\CAsproOptimusItem::showItemPrices($arParams, $arResult["PRICES"], $strMeasure, $min_price_id);?>
							<?}?>
						<?}?>
					</div>
					<?if($arParams["SHOW_DISCOUNT_TIME"]=="Y"){?>
						<?$arUserGroups = $USER->GetUserGroupArray();?>
						<?if($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] != 'Y' || ($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] == 'Y' && !$arResult['OFFERS'])):?>
							<?$arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $arUserGroups, "N", $min_price_id, SITE_ID);
							$arDiscount=array();
							if($arDiscounts)
								$arDiscount=current($arDiscounts);
							if($arDiscount["ACTIVE_TO"]){?>
								<div class="view_sale_block <?=($arQuantityData["HTML"] ? '' : 'wq');?>"">
									<div class="count_d_block">
										<span class="active_to hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
										<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
										<span class="countdown values"></span>
									</div>
									<?if($arQuantityData["HTML"]):?>
										<div class="quantity_block">
											<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
											<div class="values">
												<span class="item">
													<span class="value" <?=((count( $arResult["OFFERS"] ) > 0 && $arParams["TYPE_SKU"] == 'TYPE_1' && $arResult["OFFERS_PROP"]) ? 'style="opacity:0;"' : '')?>><?=$totalCount;?></span>
													<span class="text"><?=GetMessage("TITLE_QUANTITY");?></span>
												</span>
											</div>
										</div>
									<?endif;?>
								</div>
							<?}?>
						<?else:?>
							<?if($arResult['JS_OFFERS'])
							{

								foreach($arResult['JS_OFFERS'] as $keyOffer => $arTmpOffer2)
								{
									$active_to = '';
									$arDiscounts = CCatalogDiscount::GetDiscountByProduct( $arTmpOffer2['ID'], $arUserGroups, "N", array(), SITE_ID );
									if($arDiscounts)
									{
										foreach($arDiscounts as $arDiscountOffer)
										{
											if($arDiscountOffer['ACTIVE_TO'])
											{
												$active_to = $arDiscountOffer['ACTIVE_TO'];
												break;
											}
										}
									}
									$arResult['JS_OFFERS'][$keyOffer]['DISCOUNT_ACTIVE'] = $active_to;
								}
							}?>
							<div class="view_sale_block" style="display:none;">
								<div class="count_d_block">
										<span class="active_to_<?=$arResult["ID"]?> hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
										<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
										<span class="countdown countdown_<?=$arResult["ID"]?> values"></span>
								</div>
								<?if($arQuantityData["HTML"]):?>
									<div class="quantity_block">
										<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
										<div class="values">
											<span class="item">
												<span class="value"><?=$totalCount;?></span>
												<span class="text"><?=GetMessage("TITLE_QUANTITY");?></span>
											</span>
										</div>
									</div>
								<?endif;?>
							</div>
						<?endif;?>
					<?}?>
					<?if($useStores){?>
						<div class="p_block">
					<?}?>
						<?=$arQuantityData["HTML"];?>
					<?if($useStores){?>
						</div>
					<?}?>
					<?if($arParams["SHOW_CHEAPER_FORM"] == "Y"):?>
						<!--<div class="cheaper_form">-->
						<div class="cheaper_form order_wrap_btn">
							<!--<span class="animate-load cheaper" data-name="<?=$arResult["NAME"]?>" data-item="<?=$arResult['ID'];?>">-->
							<span class="animate-load cheaper callback_btn">
								<?=($arParams["CHEAPER_FORM_NAME"] ? $arParams["CHEAPER_FORM_NAME"] : GetMessage("CHEAPER"));?>
							</span>
						</div>
					<?endif;?>
				</div>
				<div class="buy_block">
					<?if($arResult["OFFERS"] && $showCustomOffer){?>
						<div class="sku_props">
							<?if (!empty($arResult['OFFERS_PROP'])){?>
								<div class="bx_catalog_item_scu wrapper_sku" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>">
									<?foreach ($arSkuTemplate as $code => $strTemplate){
										if (!isset($arResult['OFFERS_PROP'][$code]))
											continue;
										echo str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate);
									}?>
								</div>
							<?}?>
							<?$arItemJSParams=COptimus::GetSKUJSParams($arResult, $arParams, $arResult, "Y");?>
							<script type="text/javascript">
								var <? echo $arItemIDs["strObName"]; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arItemJSParams, false, true); ?>);
							</script>
						</div>
					<?}?>
					<?if(!$arResult["OFFERS"]):?>
						<script>
							$(document).ready(function() {
								$('.catalog_detail .tabs_section .tabs_content .form.inline input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').text());
							});
						</script>
						<div class="counter_wrapp">
							<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $arResult["CAN_BUY"]):?>
								<div class="counter_block big_basket" data-offers="<?=($arResult["OFFERS"] ? "Y" : "N");?>" data-item="<?=$arResult["ID"];?>" <?=(($arResult["OFFERS"] && $arParams["TYPE_SKU"]=="N") ? "style='display: none;'" : "");?>>
									<span class="minus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>">-</span>
									<input type="text" class="text" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
									<span class="plus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
								</div>
							<?endif;?>
							<div id="<? echo $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER" ) || !$arResult["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arAddToBasketData["ACTION"] == "SUBSCRIBE" && $arResult["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>">

									<?=$arAddToBasketData["HTML"]?>

							</div>
						</div>
						<?if($arAddToBasketData["ACTION"] !== "NOTHING"):?>
							<?if($arAddToBasketData["ACTION"] == "ADD" && $arResult["CAN_BUY"] && $arParams["SHOW_ONE_CLICK_BUY"]!="N"):?>
								<div class="wrapp_one_click">
									<span class="transparent big_btn type_block button transition_bg one_click" data-item="<?=$arResult["ID"]?>" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=$arAddToBasketData["MIN_QUANTITY_BUY"];?>" onclick="oneClickBuy('<?=$arResult["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
										<span><?=GetMessage('ONE_CLICK_BUY')?></span>
									</span>
								</div>
							<?endif;?>
						<?endif;?>

						<?if(isset($arResult['PRICE_MATRIX']) && $arResult['PRICE_MATRIX']) // USE_PRICE_COUNT
						{?>
							<?if($arResult['ITEM_PRICE_MODE'] == 'Q' && count($arResult['PRICE_MATRIX']['ROWS']) > 1):?>
								<?$arOnlyItemJSParams = array(
									"ITEM_PRICES" => $arResult["ITEM_PRICES"],
									"ITEM_PRICE_MODE" => $arResult["ITEM_PRICE_MODE"],
									"ITEM_QUANTITY_RANGES" => $arResult["ITEM_QUANTITY_RANGES"],
									"MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
									"ID" => $arItemIDs["strMainID"],
								)?>
								<script type="text/javascript">
									var <? echo $arItemIDs["strObName"]; ?>el = new JCCatalogOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
								</script>
							<?endif;?>
						<?}?>
					<?elseif($arResult["OFFERS"] && $arParams['TYPE_SKU'] == 'TYPE_1'):?>
						<div class="offer_buy_block buys_wrapp" style="display:none;">
							<div class="counter_wrapp"></div>
						</div>
					<?elseif($arResult["OFFERS"] && $arParams['TYPE_SKU'] != 'TYPE_1'):?>
						<span class="big_btn slide_offer button transition_bg type_block"><i></i><span><?=GetMessage("MORE_TEXT_BOTTOM");?></span></span>
					<?endif;?>
<pre class="" style="display:none"><?print_r($arResult['PRICES']['BASE']['VALUE'])?></pre>
<?if($arResult['PRICES']['BASE']['VALUE']<1):?>
<span class="to-analog button transition_bg transparent" data-name="<?=$arResult['NAME']?>" data-item="<?=$arResult['ID']?>"><i></i><span>Подобрать аналог</span></span>
<?else:?>
					<div class="leasing">
<script src="https://forma.tinkoff.ru/static/onlineScript.js"></script>


<span class="transparent big_btn type_block button transition_bg one_click"  onclick="widgetInit()">
										<span>Купить в лизинг</span>
									</span>

<script src="https://arenza.ru/arenza-partner-widget.min.js">
</script>
<script>
  function widgetInit() {
    ArenzaPartnerWidget.init({
      	token: 'CQqTdXeTa2igIqGs1cl5QBnST6a523ZDQGKhpuAHB8k',
		amount: <?=$arResult["PRICES"]['BASE']['VALUE']?>,
		orderItems: [
			  { 
				id: <?=$arResult["ID"]?>,
				name: '<?=$arResult["NAME"]?>',
				quantity: 1,
				price: <?=$arResult["PRICES"]['BASE']['VALUE']?>,
			  },
			],


    });
  }
</script>
					</div>
<a
  class="tinkoff"
  onclick="tinkoff.create(
    {
      sum: <?=$arResult['PRICES']['BASE']['VALUE']?>,
      items: [{name: '<?=$arResult['NAME']?>', price: <?=$arResult['PRICES']['BASE']['VALUE']?>, quantity: 1}],
      promoCode: 'default',
      shopId: 'e1413c51-5daa-4bed-970f-64061a214b19',
      showcaseId: 'da084094-309b-4729-b6f6-505d8b514e32',
    },
    {view: 'modal'}
  )"
><svg width="250" height="64" viewBox="0 0 250 64" fill="none" xmlns="http://www.w3.org/2000/svg">
<g filter="url(#filter0_d_17003_5263)">
<rect x="4" width="242" height="56" rx="8" fill="#FFDD2D"/>
<path d="M85.8 19.63C85.8 20.46 85.695 21.215 85.485 21.895C85.275 22.565 84.96 23.145 84.54 23.635C84.13 24.125 83.615 24.5 82.995 24.76C82.385 25.02 81.675 25.15 80.865 25.15C80.025 25.15 79.295 25.02 78.675 24.76C78.055 24.49 77.54 24.115 77.13 23.635C76.72 23.145 76.415 22.56 76.215 21.88C76.015 21.2 75.915 20.445 75.915 19.615C75.915 18.515 76.095 17.555 76.455 16.735C76.815 15.915 77.36 15.275 78.09 14.815C78.83 14.355 79.76 14.125 80.88 14.125C81.95 14.125 82.85 14.355 83.58 14.815C84.31 15.265 84.86 15.905 85.23 16.735C85.61 17.555 85.8 18.52 85.8 19.63ZM77.34 19.63C77.34 20.53 77.465 21.305 77.715 21.955C77.965 22.605 78.35 23.105 78.87 23.455C79.4 23.805 80.065 23.98 80.865 23.98C81.675 23.98 82.335 23.805 82.845 23.455C83.365 23.105 83.75 22.605 84 21.955C84.25 21.305 84.375 20.53 84.375 19.63C84.375 18.28 84.095 17.225 83.535 16.465C82.975 15.695 82.09 15.31 80.88 15.31C80.07 15.31 79.4 15.485 78.87 15.835C78.35 16.175 77.965 16.67 77.715 17.32C77.465 17.96 77.34 18.73 77.34 19.63ZM94.7888 16.96V25H93.4688V18.085H89.3138V25H87.9938V16.96H94.7888ZM103.474 25H102.139V18.055H99.7691C99.6691 19.325 99.5441 20.41 99.3941 21.31C99.2441 22.2 99.0541 22.925 98.8241 23.485C98.5941 24.045 98.3191 24.455 97.9991 24.715C97.6791 24.975 97.3041 25.105 96.8741 25.105C96.7441 25.105 96.6141 25.095 96.4841 25.075C96.3641 25.065 96.2641 25.04 96.1841 25V24.01C96.2441 24.03 96.3091 24.045 96.3791 24.055C96.4491 24.065 96.5191 24.07 96.5891 24.07C96.8091 24.07 97.0091 23.98 97.1891 23.8C97.3691 23.62 97.5291 23.345 97.6691 22.975C97.8191 22.605 97.9541 22.14 98.0741 21.58C98.1941 21.01 98.2991 20.34 98.3891 19.57C98.4791 18.8 98.5591 17.93 98.6291 16.96H103.474V25ZM109.071 16.825C110.051 16.825 110.776 17.04 111.246 17.47C111.716 17.9 111.951 18.585 111.951 19.525V25H110.991L110.736 23.86H110.676C110.446 24.15 110.206 24.395 109.956 24.595C109.716 24.785 109.436 24.925 109.116 25.015C108.806 25.105 108.426 25.15 107.976 25.15C107.496 25.15 107.061 25.065 106.671 24.895C106.291 24.725 105.991 24.465 105.771 24.115C105.551 23.755 105.441 23.305 105.441 22.765C105.441 21.965 105.756 21.35 106.386 20.92C107.016 20.48 107.986 20.24 109.296 20.2L110.661 20.155V19.675C110.661 19.005 110.516 18.54 110.226 18.28C109.936 18.02 109.526 17.89 108.996 17.89C108.576 17.89 108.176 17.955 107.796 18.085C107.416 18.205 107.061 18.345 106.731 18.505L106.326 17.515C106.676 17.325 107.091 17.165 107.571 17.035C108.051 16.895 108.551 16.825 109.071 16.825ZM109.461 21.115C108.461 21.155 107.766 21.315 107.376 21.595C106.996 21.875 106.806 22.27 106.806 22.78C106.806 23.23 106.941 23.56 107.211 23.77C107.491 23.98 107.846 24.085 108.276 24.085C108.956 24.085 109.521 23.9 109.971 23.53C110.421 23.15 110.646 22.57 110.646 21.79V21.07L109.461 21.115ZM119.984 18.07H117.359V25H116.054V18.07H113.459V16.96H119.984V18.07ZM122.813 21.865C122.813 21.955 122.808 22.085 122.798 22.255C122.798 22.415 122.793 22.59 122.783 22.78C122.773 22.96 122.763 23.135 122.753 23.305C122.743 23.465 122.733 23.595 122.723 23.695L127.028 16.96H128.648V25H127.418V20.26C127.418 20.1 127.418 19.89 127.418 19.63C127.428 19.37 127.438 19.115 127.448 18.865C127.458 18.605 127.468 18.41 127.478 18.28L123.203 25H121.568V16.96H122.813V21.865ZM136.757 18.07H134.132V25H132.827V18.07H130.232V16.96H136.757V18.07ZM142.045 20.26C142.745 20.26 143.325 20.345 143.785 20.515C144.245 20.685 144.59 20.94 144.82 21.28C145.06 21.61 145.18 22.03 145.18 22.54C145.18 23.04 145.065 23.475 144.835 23.845C144.615 24.215 144.27 24.5 143.8 24.7C143.33 24.9 142.725 25 141.985 25H138.34V16.96H139.66V20.26H142.045ZM143.86 22.615C143.86 22.125 143.685 21.795 143.335 21.625C142.995 21.445 142.515 21.355 141.895 21.355H139.66V23.935H141.925C142.485 23.935 142.945 23.835 143.305 23.635C143.675 23.435 143.86 23.095 143.86 22.615ZM153.353 19.885C153.353 20.355 153.483 20.705 153.743 20.935C154.013 21.155 154.398 21.265 154.898 21.265C155.398 21.265 155.853 21.19 156.263 21.04C156.673 20.88 157.093 20.67 157.523 20.41V16.96H158.843V25H157.523V21.415C157.073 21.705 156.628 21.935 156.188 22.105C155.758 22.265 155.238 22.345 154.628 22.345C153.808 22.345 153.168 22.13 152.708 21.7C152.258 21.27 152.033 20.695 152.033 19.975V16.96H153.353V19.885ZM164.442 16.825C165.422 16.825 166.147 17.04 166.617 17.47C167.087 17.9 167.322 18.585 167.322 19.525V25H166.362L166.107 23.86H166.047C165.817 24.15 165.577 24.395 165.327 24.595C165.087 24.785 164.807 24.925 164.487 25.015C164.177 25.105 163.797 25.15 163.347 25.15C162.867 25.15 162.432 25.065 162.042 24.895C161.662 24.725 161.362 24.465 161.142 24.115C160.922 23.755 160.812 23.305 160.812 22.765C160.812 21.965 161.127 21.35 161.757 20.92C162.387 20.48 163.357 20.24 164.667 20.2L166.032 20.155V19.675C166.032 19.005 165.887 18.54 165.597 18.28C165.307 18.02 164.897 17.89 164.367 17.89C163.947 17.89 163.547 17.955 163.167 18.085C162.787 18.205 162.432 18.345 162.102 18.505L161.697 17.515C162.047 17.325 162.462 17.165 162.942 17.035C163.422 16.895 163.922 16.825 164.442 16.825ZM164.832 21.115C163.832 21.155 163.137 21.315 162.747 21.595C162.367 21.875 162.177 22.27 162.177 22.78C162.177 23.23 162.312 23.56 162.582 23.77C162.862 23.98 163.217 24.085 163.647 24.085C164.327 24.085 164.892 23.9 165.342 23.53C165.792 23.15 166.017 22.57 166.017 21.79V21.07L164.832 21.115ZM173.03 25.15C172.32 25.15 171.685 25.005 171.125 24.715C170.575 24.425 170.14 23.975 169.82 23.365C169.51 22.755 169.355 21.975 169.355 21.025C169.355 20.035 169.52 19.23 169.85 18.61C170.18 17.99 170.625 17.535 171.185 17.245C171.755 16.955 172.4 16.81 173.12 16.81C173.53 16.81 173.925 16.855 174.305 16.945C174.685 17.025 174.995 17.125 175.235 17.245L174.83 18.34C174.59 18.25 174.31 18.165 173.99 18.085C173.67 18.005 173.37 17.965 173.09 17.965C172.55 17.965 172.105 18.08 171.755 18.31C171.405 18.54 171.145 18.88 170.975 19.33C170.805 19.78 170.72 20.34 170.72 21.01C170.72 21.65 170.805 22.195 170.975 22.645C171.145 23.095 171.4 23.435 171.74 23.665C172.08 23.895 172.505 24.01 173.015 24.01C173.455 24.01 173.84 23.965 174.17 23.875C174.51 23.785 174.82 23.675 175.1 23.545V24.715C174.83 24.855 174.53 24.96 174.2 25.03C173.88 25.11 173.49 25.15 173.03 25.15ZM182.562 18.07H179.937V25H178.632V18.07H176.037V16.96H182.562V18.07ZM184.641 25H183.111L185.406 21.625C185.096 21.545 184.796 21.42 184.506 21.25C184.216 21.07 183.981 20.825 183.801 20.515C183.621 20.195 183.531 19.8 183.531 19.33C183.531 18.56 183.791 17.975 184.311 17.575C184.831 17.165 185.521 16.96 186.381 16.96H189.981V25H188.661V21.805H186.711L184.641 25ZM184.806 19.345C184.806 19.805 184.976 20.15 185.316 20.38C185.666 20.6 186.151 20.71 186.771 20.71H188.661V18.055H186.516C185.906 18.055 185.466 18.175 185.196 18.415C184.936 18.655 184.806 18.965 184.806 19.345ZM201.15 16.96V25H199.965V19.87C199.965 19.66 199.97 19.44 199.98 19.21C200 18.98 200.02 18.755 200.04 18.535H199.995L197.37 25H196.26L193.71 18.535H193.665C193.685 18.755 193.695 18.98 193.695 19.21C193.705 19.44 193.71 19.675 193.71 19.915V25H192.525V16.96H194.28L196.815 23.425L199.395 16.96H201.15ZM204.961 21.865C204.961 21.955 204.956 22.085 204.946 22.255C204.946 22.415 204.941 22.59 204.931 22.78C204.921 22.96 204.911 23.135 204.901 23.305C204.891 23.465 204.881 23.595 204.871 23.695L209.176 16.96H210.796V25H209.566V20.26C209.566 20.1 209.566 19.89 209.566 19.63C209.576 19.37 209.586 19.115 209.596 18.865C209.606 18.605 209.616 18.41 209.626 18.28L205.351 25H203.716V16.96H204.961V21.865Z" fill="#333333"/>
<path d="M81.061 41.041C81.061 41.5323 80.995 41.9687 80.863 42.35C80.7383 42.724 80.555 43.043 80.313 43.307C80.0783 43.571 79.7887 43.7727 79.444 43.912C79.1067 44.044 78.729 44.11 78.311 44.11C77.9223 44.11 77.563 44.044 77.233 43.912C76.903 43.7727 76.617 43.571 76.375 43.307C76.133 43.043 75.9423 42.724 75.803 42.35C75.671 41.9687 75.605 41.5323 75.605 41.041C75.605 40.3883 75.715 39.8383 75.935 39.391C76.155 38.9363 76.4703 38.5917 76.881 38.357C77.2917 38.115 77.7793 37.994 78.344 37.994C78.8793 37.994 79.3487 38.115 79.752 38.357C80.1627 38.5917 80.4817 38.9363 80.709 39.391C80.9437 39.8383 81.061 40.3883 81.061 41.041ZM76.606 41.041C76.606 41.503 76.6647 41.9063 76.782 42.251C76.9067 42.5883 77.0973 42.8487 77.354 43.032C77.6107 43.2153 77.937 43.307 78.333 43.307C78.729 43.307 79.0553 43.2153 79.312 43.032C79.5687 42.8487 79.7557 42.5883 79.873 42.251C79.9977 41.9063 80.06 41.503 80.06 41.041C80.06 40.5717 79.9977 40.172 79.873 39.842C79.7483 39.512 79.5577 39.259 79.301 39.083C79.0517 38.8997 78.7253 38.808 78.322 38.808C77.7207 38.808 77.2843 39.006 77.013 39.402C76.7417 39.798 76.606 40.3443 76.606 41.041ZM86.9577 38.918H85.0327V44H84.0757V38.918H82.1727V38.104H86.9577V38.918ZM90.3524 37.18C90.9684 36.96 91.5808 36.85 92.1894 36.85C92.7981 36.85 93.2968 37.004 93.6854 37.312C94.0741 37.62 94.2684 38.0087 94.2684 38.478C94.2684 38.8447 94.1731 39.1673 93.9824 39.446C93.7918 39.7173 93.5204 39.9227 93.1684 40.062C93.6818 40.2527 94.0521 40.491 94.2794 40.777C94.5068 41.0557 94.6204 41.4113 94.6204 41.844C94.6204 42.504 94.3638 43.0503 93.8504 43.483C93.3444 43.9083 92.6991 44.121 91.9144 44.121C91.5404 44.121 91.1738 44.0733 90.8144 43.978C90.4624 43.8753 90.2021 43.7727 90.0334 43.67L89.7804 43.516L90.2424 42.185C90.3084 42.2437 90.3964 42.317 90.5064 42.405C90.6164 42.493 90.8254 42.614 91.1334 42.768C91.4488 42.9147 91.7641 42.988 92.0794 42.988C92.3948 42.988 92.6698 42.9037 92.9044 42.735C93.1391 42.559 93.2564 42.3133 93.2564 41.998C93.2564 41.6827 93.1171 41.4223 92.8384 41.217C92.5671 41.0117 92.1894 40.909 91.7054 40.909H90.7594L90.9574 39.842H91.5624C91.9658 39.842 92.2811 39.7503 92.5084 39.567C92.7358 39.3837 92.8494 39.149 92.8494 38.863C92.8494 38.577 92.7541 38.357 92.5634 38.203C92.3728 38.0417 92.1418 37.961 91.8704 37.961C91.6064 37.961 91.3314 38.0087 91.0454 38.104C90.7668 38.192 90.4588 38.3277 90.1214 38.511L90.3524 37.18ZM100.48 41.646C100.48 42.328 100.205 42.911 99.6545 43.395C99.1119 43.879 98.4482 44.121 97.6635 44.121C97.2969 44.121 96.9449 44.077 96.6075 43.989C96.2702 43.8937 96.0245 43.802 95.8705 43.714L95.6395 43.571L96.1015 42.284C96.3289 42.46 96.6112 42.6213 96.9485 42.768C97.2932 42.9147 97.6122 42.988 97.9055 42.988C98.2062 42.988 98.4739 42.8963 98.7085 42.713C98.9432 42.5223 99.0605 42.2913 99.0605 42.02C99.0605 41.3527 98.6132 40.9457 97.7185 40.799L96.2555 40.557V36.916L98.1475 37.048L100.381 36.927L100.139 38.445L97.3115 38.192V39.479L98.5105 39.688C99.1265 39.798 99.6069 40.029 99.9515 40.381C100.304 40.733 100.48 41.1547 100.48 41.646ZM104.656 36.861C105.411 36.861 106.016 37.1617 106.471 37.763C106.933 38.357 107.164 39.1453 107.164 40.128C107.164 41.3673 106.885 42.3427 106.328 43.054C105.778 43.7653 105.019 44.121 104.051 44.121C103.31 44.121 102.705 43.8203 102.236 43.219C101.774 42.6103 101.543 41.8183 101.543 40.843C101.543 39.677 101.833 38.7237 102.412 37.983C102.999 37.235 103.747 36.861 104.656 36.861ZM104.403 43.043C104.872 43.043 105.199 42.8633 105.382 42.504C105.573 42.1447 105.668 41.5067 105.668 40.59C105.668 39.6733 105.573 39.0317 105.382 38.665C105.191 38.291 104.858 38.104 104.381 38.104C103.904 38.104 103.56 38.28 103.347 38.632C103.142 38.9767 103.039 39.5817 103.039 40.447C103.039 41.305 103.149 41.954 103.369 42.394C103.589 42.8267 103.934 43.043 104.403 43.043ZM111.34 36.861C112.095 36.861 112.7 37.1617 113.155 37.763C113.617 38.357 113.848 39.1453 113.848 40.128C113.848 41.3673 113.569 42.3427 113.012 43.054C112.462 43.7653 111.703 44.121 110.735 44.121C109.994 44.121 109.389 43.8203 108.92 43.219C108.458 42.6103 108.227 41.8183 108.227 40.843C108.227 39.677 108.517 38.7237 109.096 37.983C109.683 37.235 110.431 36.861 111.34 36.861ZM111.087 43.043C111.556 43.043 111.883 42.8633 112.066 42.504C112.257 42.1447 112.352 41.5067 112.352 40.59C112.352 39.6733 112.257 39.0317 112.066 38.665C111.875 38.291 111.542 38.104 111.065 38.104C110.588 38.104 110.244 38.28 110.031 38.632C109.826 38.9767 109.723 39.5817 109.723 40.447C109.723 41.305 109.833 41.954 110.053 42.394C110.273 42.8267 110.618 43.043 111.087 43.043ZM117.746 44V36.146H119.814C120.774 36.146 121.486 36.3403 121.948 36.729C122.417 37.1177 122.652 37.6823 122.652 38.423C122.652 38.9217 122.538 39.3507 122.311 39.71C122.083 40.062 121.746 40.3333 121.299 40.524C120.859 40.7147 120.312 40.81 119.66 40.81H118.736V44H117.746ZM116.789 42.504V41.789H120.551V42.504H116.789ZM116.789 40.81V39.974H119.308V40.81H116.789ZM119.517 39.974C119.964 39.974 120.342 39.9263 120.65 39.831C120.965 39.7357 121.207 39.578 121.376 39.358C121.544 39.138 121.629 38.8373 121.629 38.456C121.629 37.9573 121.475 37.587 121.167 37.345C120.859 37.103 120.378 36.982 119.726 36.982H118.736V39.974H119.517ZM131.254 39.589C131.254 39.9557 131.144 40.2417 130.924 40.447C130.704 40.6523 130.422 40.7917 130.077 40.865V40.909C130.444 40.9603 130.763 41.0923 131.034 41.305C131.305 41.5103 131.441 41.833 131.441 42.273C131.441 42.5223 131.393 42.7533 131.298 42.966C131.21 43.1787 131.071 43.362 130.88 43.516C130.689 43.67 130.444 43.791 130.143 43.879C129.842 43.9597 129.479 44 129.054 44H126.513V38.104H129.043C129.461 38.104 129.835 38.1517 130.165 38.247C130.502 38.335 130.766 38.489 130.957 38.709C131.155 38.9217 131.254 39.215 131.254 39.589ZM130.44 42.273C130.44 41.9357 130.312 41.6937 130.055 41.547C129.798 41.4003 129.421 41.327 128.922 41.327H127.481V43.219H128.944C129.428 43.219 129.798 43.1493 130.055 43.01C130.312 42.8633 130.44 42.6177 130.44 42.273ZM130.275 39.71C130.275 39.4313 130.172 39.2297 129.967 39.105C129.769 38.973 129.443 38.907 128.988 38.907H127.481V40.524H128.812C129.289 40.524 129.652 40.458 129.901 40.326C130.15 40.194 130.275 39.9887 130.275 39.71ZM141.673 38.104V44H140.804V40.238C140.804 40.084 140.808 39.9227 140.815 39.754C140.83 39.5853 140.845 39.4203 140.859 39.259H140.826L138.901 44H138.087L136.217 39.259H136.184C136.199 39.4203 136.206 39.5853 136.206 39.754C136.214 39.9227 136.217 40.095 136.217 40.271V44H135.348V38.104H136.635L138.494 42.845L140.386 38.104H141.673ZM146.232 37.994C146.738 37.994 147.171 38.104 147.53 38.324C147.897 38.544 148.176 38.8557 148.366 39.259C148.564 39.655 148.663 40.1207 148.663 40.656V41.239H144.626C144.641 41.9063 144.81 42.416 145.132 42.768C145.462 43.1127 145.921 43.285 146.507 43.285C146.881 43.285 147.211 43.252 147.497 43.186C147.791 43.1127 148.091 43.01 148.399 42.878V43.725C148.099 43.857 147.802 43.9523 147.508 44.011C147.215 44.077 146.867 44.11 146.463 44.11C145.906 44.11 145.411 43.9963 144.978 43.769C144.553 43.5417 144.219 43.2043 143.977 42.757C143.743 42.3023 143.625 41.7487 143.625 41.096C143.625 40.4507 143.732 39.897 143.944 39.435C144.164 38.973 144.469 38.6173 144.857 38.368C145.253 38.1187 145.712 37.994 146.232 37.994ZM146.221 38.786C145.759 38.786 145.393 38.9363 145.121 39.237C144.857 39.5303 144.7 39.941 144.648 40.469H147.651C147.651 40.1317 147.6 39.8383 147.497 39.589C147.395 39.3397 147.237 39.1453 147.024 39.006C146.819 38.8593 146.551 38.786 146.221 38.786ZM152.929 44.11C152.409 44.11 151.943 44.0037 151.532 43.791C151.129 43.5783 150.81 43.2483 150.575 42.801C150.348 42.3537 150.234 41.7817 150.234 41.085C150.234 40.359 150.355 39.7687 150.597 39.314C150.839 38.8593 151.166 38.5257 151.576 38.313C151.994 38.1003 152.467 37.994 152.995 37.994C153.296 37.994 153.586 38.027 153.864 38.093C154.143 38.1517 154.37 38.225 154.546 38.313L154.249 39.116C154.073 39.05 153.868 38.9877 153.633 38.929C153.399 38.8703 153.179 38.841 152.973 38.841C152.577 38.841 152.251 38.9253 151.994 39.094C151.738 39.2627 151.547 39.512 151.422 39.842C151.298 40.172 151.235 40.5827 151.235 41.074C151.235 41.5433 151.298 41.943 151.422 42.273C151.547 42.603 151.734 42.8523 151.983 43.021C152.233 43.1897 152.544 43.274 152.918 43.274C153.241 43.274 153.523 43.241 153.765 43.175C154.015 43.109 154.242 43.0283 154.447 42.933V43.791C154.249 43.8937 154.029 43.9707 153.787 44.022C153.553 44.0807 153.267 44.11 152.929 44.11ZM156.612 44H155.49L157.173 41.525C156.946 41.4663 156.726 41.3747 156.513 41.25C156.301 41.118 156.128 40.9383 155.996 40.711C155.864 40.4763 155.798 40.1867 155.798 39.842C155.798 39.2773 155.989 38.8483 156.37 38.555C156.752 38.2543 157.258 38.104 157.888 38.104H160.528V44H159.56V41.657H158.13L156.612 44ZM156.733 39.853C156.733 40.1903 156.858 40.4433 157.107 40.612C157.364 40.7733 157.72 40.854 158.174 40.854H159.56V38.907H157.987C157.54 38.907 157.217 38.995 157.019 39.171C156.829 39.347 156.733 39.5743 156.733 39.853ZM168.613 46.046H167.667V44H162.794V38.104H163.762V43.186H166.809V38.104H167.777V43.197H168.613V46.046Z" fill="#333333"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M63.076 24.5344L62.4389 26.5818C62.1011 27.6434 60.9923 29.3905 58.9288 29.5675C59.1546 30.5239 59.6576 31.5983 60.7683 32.0609L62.3673 32.7207L61.5044 34.2282C61.3098 34.5489 60.3056 36.0287 58.211 36.0287C57.853 36.0287 57.506 35.9919 57.1737 35.9218C57.0892 36.296 56.9846 36.6719 56.8598 37.0461C56.3696 38.5111 56.7918 39.5303 56.8101 39.5727L57.5812 41.2019L55.8115 41.6718L55.8097 41.6723C55.7723 41.6821 55.244 41.8211 54.6036 41.8211C54.3006 41.8211 54.0123 41.7897 53.7425 41.7252C53.5369 41.6774 53.3387 41.6129 53.1496 41.5354C52.9201 41.9464 52.6428 42.3206 52.3215 42.6541C51.1943 43.8188 49.5935 44.4086 47.5648 44.4086L46.1126 44.3902L49.0922 45.6378L46.8654 46.7233C46.0759 47.1102 45.0626 47.6061 44.0382 47.982L44.3245 48.7616L43.5407 49.1209C43.4709 49.1541 41.9784 49.8231 39.4944 49.9706C39.4944 49.9706 38.8978 50 38.5656 50C38.2333 50 37.6293 49.9724 37.6293 49.9724C35.1335 49.8289 33.631 49.1548 33.5559 49.1211L33.5555 49.1209L32.7734 48.7634L33.0562 47.9857C32.0611 47.6207 31.0826 47.1435 30.2584 46.7399L27.9891 45.6396L30.978 44.3938L29.5313 44.4122H29.524C27.4972 44.4122 25.8982 43.8225 24.7691 42.6596C24.446 42.3242 24.1688 41.9501 23.9412 41.5391C23.7521 41.6165 23.5556 41.681 23.3501 41.729C23.0783 41.7935 22.7901 41.8247 22.4872 41.8247C21.8207 41.8247 21.2884 41.6792 21.2663 41.6718L19.5039 41.2055L20.2933 39.5451C20.2988 39.5339 20.721 38.513 20.2309 37.0497C20.106 36.6775 19.9996 36.3016 19.9152 35.9256C19.5847 35.9955 19.2414 36.0306 18.8852 36.0324C17.2403 36.0324 16.0855 35.0704 15.5935 34.2484L14.7215 32.7244L16.3206 32.0627C17.4294 31.6002 17.9342 30.5276 18.162 29.5693C16.069 29.3813 14.9767 27.6342 14.6499 26.58L14 24.5344L16.1479 24.6044C16.304 24.6025 17.5983 24.5657 18.8504 23.6516C18.8285 23.6537 18.8068 23.656 18.7851 23.6583L18.785 23.6583H18.785H18.785H18.785H18.7849H18.7849H18.7849H18.7848H18.7848L18.7848 23.6583L18.7847 23.6583H18.7847L18.7847 23.6583L18.7846 23.6583H18.7846L18.7845 23.6583L18.7843 23.6584C18.7114 23.6661 18.6388 23.6738 18.564 23.6738C17.2733 23.6738 16.0213 22.8942 15.5201 21.7756L14.6187 19.7575C15.9515 19.7815 16.8126 19.6267 16.8126 19.6267C16.8123 19.6267 16.8132 19.6265 16.8153 19.6262C16.868 19.6171 17.6566 19.4808 18.564 18.5504C19.2451 17.8538 20.0859 17.4852 20.9965 17.4852C21.3948 17.4852 21.751 17.5571 22.0558 17.6548C22.008 17.4907 21.964 17.3249 21.9364 17.1553C21.6721 15.4525 22.6432 14.0978 22.8397 13.8417L24.4496 11.8587L25.4337 14.1863C25.4337 14.1863 25.6797 14.7208 26.1203 14.9825C26.2561 14.3024 26.5848 13.5358 27.2897 12.806C28.6116 11.4293 30.2253 11.2228 31.0698 11.2228C31.4627 11.2228 31.7398 11.2653 31.8316 11.2801L31.8941 11.2911L33.7189 11.6523C33.6106 11.2228 33.3756 10.4783 32.9459 9.80566L31.8886 8.15444L34.5432 8.45853L35.3877 6.65423L37.0307 7.50203L38.5417 6L40.0563 7.50203L41.6938 6.65423L42.5401 8.45853L45.1966 8.15444L44.141 9.80566C43.7115 10.4783 43.4764 11.2248 43.3663 11.6541L45.2792 11.2763C45.3472 11.2653 45.6263 11.2228 46.0173 11.2228C46.8618 11.2228 48.4754 11.4274 49.8009 12.806C50.5041 13.5376 50.8327 14.3024 50.9667 14.9825C51.3995 14.7239 51.6458 14.203 51.6617 14.1695L51.6625 14.1679L52.6447 11.8532L54.229 13.8233C54.229 13.8214 55.4406 15.2625 55.1506 17.1516C55.1249 17.3229 55.079 17.4889 55.0313 17.6528C55.3378 17.5552 55.694 17.4833 56.0923 17.4833C57.0029 17.4833 57.8437 17.852 58.523 18.5485C59.4468 19.4956 60.2484 19.619 60.2961 19.6263L60.2983 19.6267C60.2983 19.6267 61.3374 19.7704 62.4738 19.7557L61.5669 21.7736C61.0657 22.8905 59.8155 23.6718 58.523 23.6718C58.4483 23.6718 58.3748 23.664 58.3015 23.6562L58.3015 23.6561C58.2811 23.654 58.2607 23.6518 58.2404 23.6498C59.496 24.5639 60.7903 24.6007 60.9684 24.6007L63.076 24.5344ZM33.8364 12.3637L33.8585 12.3581L33.8547 12.3397L33.8364 12.3637Z" fill="white"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M42.5906 32.6012H31.2652V26.0348C31.2652 25.399 31.0798 24.7595 30.8613 24.1698H35.965C36.4497 24.6527 36.9251 24.9548 37.3767 25.1208C37.3199 25.3548 37.2427 25.8358 37.4594 26.3168C37.6374 26.7057 37.9587 27.0042 38.4251 27.2032C38.4764 27.6824 38.7794 28.4048 39.6128 28.7218C39.2475 29.0922 38.8913 29.4424 38.7482 29.5825L35.3591 29.529C35.2821 29.4922 35.1517 29.4037 35.093 29.282C35.089 29.2722 35.084 29.265 35.0792 29.2579C35.0749 29.2517 35.0707 29.2455 35.0672 29.2378L35.6198 29.3282C35.6308 29.33 35.6437 29.33 35.6547 29.33C35.7631 29.33 35.8548 29.2544 35.8732 29.1457C35.8915 29.0222 35.8089 28.9098 35.6896 28.8914L35.0103 28.7845L35.7648 26.1676L35.0508 24.6581L33.9051 25.8745L33.8354 28.5947L33.0661 28.473C32.9469 28.449 32.833 28.5357 32.8128 28.6574C32.7945 28.7771 32.8752 28.8896 32.9964 28.9116L33.3195 28.9614C33.2057 29.0277 33.1286 29.0794 33.1194 29.0868L33.0552 29.1328L33.0368 29.2139C32.8496 29.9769 33.2718 30.7011 33.7308 31.0255L33.7142 31.1213C33.6941 31.2393 33.7215 31.3536 33.7914 31.4493C33.8593 31.547 33.9602 31.606 34.0758 31.6263C34.0997 31.6318 34.1255 31.6337 34.1475 31.6337C34.3458 31.6337 34.5183 31.4955 34.5661 31.2945C34.7368 31.3148 34.8929 31.3185 34.9993 31.3185H35.0893L39.5339 32.1257L39.6091 32.0741C39.9616 31.8272 41.3128 30.8393 41.7552 30.5114C41.8764 30.6145 42.0122 30.7104 42.1775 30.7896C42.0325 31.2725 42.0252 32.0152 42.5906 32.6012ZM31.2653 33.0435H45.8291L45.8273 38.5448C45.8273 40.3656 44.7606 41.639 43.0809 42.0536L43.3617 40.583C43.3783 40.4965 43.3948 40.395 43.4077 40.3103C43.4884 40.3434 43.5711 40.3712 43.6629 40.3876C43.7675 40.408 43.8648 40.4172 43.9528 40.4172C44.3825 40.4172 44.6321 40.1904 44.7991 39.997L45.0158 39.7445L44.8396 39.4681C44.8383 39.4662 44.835 39.4609 44.8298 39.4527C44.7651 39.3506 44.4097 38.7896 44.1328 38.3973C44.074 38.3144 44.0171 38.2296 43.9603 38.1412L43.957 38.1363C43.616 37.6247 43.1901 36.9857 42.3354 36.9857L42.2584 36.9875C42.1189 36.9967 41.9977 37.0243 41.8986 37.0612C41.6305 36.9359 41.3515 36.8696 41.0632 36.8696C40.7089 36.8696 40.4097 36.9673 40.2114 37.0354C40.1934 37.0406 40.1759 37.0461 40.1592 37.0513C40.1231 37.0625 40.0903 37.0727 40.0628 37.0778L38.4618 36.9193C38.4416 36.9156 38.3755 36.8768 38.2966 36.7829V36.7737L38.6436 36.6336C38.5463 35.4744 37.4392 34.7612 36.8207 34.5198C36.7931 34.4037 36.738 34.2875 36.6407 34.1936L36.2973 33.8655L35.9945 34.2286L35.8457 34.4147L33.6023 33.4896L35.0618 35.3289L34.4855 36.2042C34.2743 36.429 34.5074 36.654 34.5074 36.654L35.1022 37.0668L35.719 36.8364C35.7312 36.8364 35.7433 36.8384 35.7553 36.8404C35.7634 36.8417 35.7715 36.843 35.7796 36.8438C35.7621 36.8964 35.7424 37.0353 35.7238 37.1654C35.7093 37.2674 35.6955 37.3641 35.6842 37.4095L34.6139 37.1975L33.4886 37.8352C32.9103 38.2609 32.8313 38.9668 33.0461 39.4497L33.1709 39.7242L33.349 40.1113L33.7438 39.9472C33.7492 39.9472 33.7547 39.9454 33.7584 39.9417C33.6171 40.395 33.6757 40.8282 33.9273 41.1101L34.166 41.4106L34.4835 41.8142L34.8324 41.4382C34.9333 41.3349 35.2675 40.9608 35.3703 40.6402L35.4455 40.395L35.2748 40.207L35.2455 40.174L35.4364 39.8569L36.8335 40.1868L37.1473 40.2587L37.3145 39.9822L37.4063 39.8311C37.4228 39.7998 37.4282 39.7942 37.4925 39.7942C37.5421 39.7942 37.5916 39.798 37.6449 39.8108C38.1718 39.9103 38.6583 39.9602 39.0898 39.9602C39.5101 39.9602 39.7598 39.9436 40.0572 39.8441C40.0572 39.8441 40.808 40.7009 40.8337 40.7323L40.3748 42.1569L40.3547 42.2416C39.4845 42.3135 39.0604 42.5052 38.5482 43.0175C37.8046 42.2766 37.2778 42.1993 35.2785 42.1993C32.8864 42.1993 31.2653 40.7912 31.2653 38.5429V33.0435Z" fill="#FFDD2D"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M42.3752 11.8499C41.6206 11.2436 40.2548 10.6759 38.5475 10.6759C36.842 10.6759 35.4798 11.2436 34.7234 11.8499C34.7234 11.8499 34.4994 10.4953 33.7338 9.29927L35.109 9.45586L35.8268 7.92075L37.2109 8.63582L38.5475 7.30518L39.8876 8.63582L41.2701 7.92075L41.9897 9.45586L43.3647 9.29927C42.5992 10.4953 42.3752 11.8499 42.3752 11.8499ZM35.4283 10.0346L35.0465 9.99399L34.6811 9.94975C34.8483 10.3257 34.9694 10.6851 35.0593 10.9819C36.025 10.4438 37.2752 10.1377 38.5457 10.1377C39.8215 10.1377 41.0735 10.4438 42.0355 10.9819C42.1237 10.6851 42.2467 10.3276 42.4138 9.94975L42.0503 9.99399L41.6684 10.0346L41.5069 9.68626L41.0204 8.65425L40.1337 9.11674L39.7885 9.29369L39.5131 9.021L38.5493 8.06267L37.5873 9.021L37.312 9.29369L36.9668 9.11674L36.0745 8.65425L35.5935 9.68626L35.4283 10.0346ZM35.9316 35.4246C35.8491 35.4172 35.7793 35.4817 35.7737 35.5628C35.7682 35.6475 35.8307 35.7194 35.9151 35.7212C35.9958 35.7286 36.0674 35.6659 36.0748 35.583C36.0748 35.5019 36.0161 35.4264 35.9316 35.4246ZM45.8099 45.3966L44.9709 45.0741L45.2022 44.2042L45.4574 43.2551C45.4574 43.2551 43.9703 44.0643 41.8756 44.6723C41.2808 44.8438 40.831 44.9211 40.831 44.9211V45.5662C40.831 45.5662 39.7112 45.7615 38.5785 45.7615H38.5656H38.5491H38.527H38.5142C37.3815 45.7615 36.2616 45.5662 36.2616 45.5662V44.9211C36.2616 44.9211 35.8099 44.8438 35.217 44.6723C33.1222 44.0643 31.6371 43.2551 31.6371 43.2551L31.8886 44.2042L32.1255 45.0741L31.2846 45.3966L30.352 45.7468C30.4629 45.8004 30.5783 45.8566 30.6974 45.9147C31.728 46.4171 33.0445 47.0589 34.2606 47.3925L33.9411 48.2698C33.9411 48.2698 35.6613 49.0567 38.527 49.0641V49.0659H38.5363H38.5491H38.5601H38.5674V49.0641C41.4369 49.0567 43.1552 48.2698 43.1552 48.2698L42.8339 47.3925C44.067 47.0542 45.4064 46.3991 46.4397 45.8937L46.4398 45.8936L46.4408 45.8931C46.544 45.8427 46.644 45.7937 46.7407 45.7468L45.8099 45.3966ZM31.3471 45.8058L32.7276 45.3451L32.3201 44.0089C32.9276 44.3554 34.2918 44.8751 35.0812 45.1165L34.4147 46.9539C33.1958 46.6167 32.1805 46.2242 31.3471 45.8058ZM38.5491 48.6033C36.9463 48.6033 35.116 48.2329 34.5781 48.0413L35.397 45.8168C36.0762 46.0158 37.4898 46.2278 38.516 46.2204H38.5179H38.5491H38.5766H38.5803C39.6066 46.2278 41.0165 46.0158 41.6994 45.8168L42.5163 48.0413C41.9785 48.2347 40.1499 48.6033 38.5491 48.6033ZM42.6797 46.9539L42.0152 45.1165C42.8064 44.8751 44.1668 44.3554 44.7763 44.0089L44.3705 45.3451L45.7493 45.8058C44.9139 46.2242 43.9042 46.6167 42.6797 46.9539ZM58.5782 28.6499C60.7868 28.6499 61.5248 26.3942 61.556 26.2984L61.8021 25.504L60.974 25.5298H60.9704C60.6785 25.5298 58.2423 25.4654 56.5148 23.2612L56.3513 23.0566L56.3457 23.0495C55.2188 21.6109 54.1489 20.2451 52.2391 19.6214C52.2941 19.5717 52.3731 19.509 52.4814 19.439C53.4967 18.7681 54.0878 17.9535 54.2365 17.0082C54.4531 15.5854 53.5499 14.452 53.5095 14.4059L52.8945 13.643L52.5108 14.5478L52.5105 14.5485C52.4806 14.615 51.8341 16.0535 50.4803 16.0535H50.3793C50.3509 16.0518 50.3231 16.0503 50.2955 16.0488L50.2952 16.0488C50.2321 16.0454 50.1708 16.042 50.1095 16.0369C50.1573 15.3901 50.0397 14.3893 49.1365 13.4512C48.0516 12.3215 46.7186 12.152 46.0211 12.152C45.6785 12.152 45.4415 12.1909 45.3854 12.2001L45.3851 12.2001L45.3806 12.2009C45.3781 12.2013 45.3761 12.2016 45.3748 12.2018L44.4459 12.386L45.0187 13.1435C45.4685 13.7277 45.8761 14.8059 45.3932 15.3698C45.247 15.5392 44.9541 15.6789 44.6645 15.8169L44.6167 15.8397C43.9906 16.129 43.1369 16.5308 43.1369 17.5555C43.1369 18.219 43.6492 18.9119 44.578 19.5016C47.3282 21.2377 49.0246 23.0493 49.757 25.0488C50.8897 28.1247 49.4118 29.8792 47.9762 30.8025L47.3318 31.2189L47.8955 31.7386L47.8956 31.7388C47.9715 31.8058 48.6617 32.4151 49.6799 32.5035C49.8727 32.5164 50.058 32.5274 50.2307 32.5274C50.8842 32.5274 51.4405 32.4243 51.8921 32.286C51.7673 32.5054 51.6148 32.7284 51.4222 32.9532C50.6015 33.9078 49.5918 34.3427 48.1396 34.3649L47.5999 34.3741L47.5576 34.9177C47.5521 35.0264 47.5118 36.0346 48.4627 36.8916C48.6078 37.0242 48.784 37.133 48.9676 37.2233C48.3232 37.8056 47.6678 38.8192 47.5412 40.5571C47.4347 42.0075 47.0895 42.5438 47.0859 42.5493L46.4892 43.4634L47.5669 43.4781C49.3477 43.4781 50.7117 42.9843 51.6571 42.0075C52.2152 41.4288 52.5347 40.7764 52.7238 40.1978C53.034 40.4631 53.4343 40.6953 53.9556 40.8188C54.1539 40.8648 54.3706 40.8907 54.6037 40.8907C55.0805 40.8907 55.4831 40.7946 55.5644 40.7752C55.5689 40.7741 55.5724 40.7733 55.5749 40.7727L56.2633 40.5884L55.9586 39.9434L55.9576 39.9412C55.924 39.8634 55.3687 38.5778 55.9805 36.7515C56.2467 35.9535 56.3825 35.2384 56.4487 34.6819C56.882 34.9085 57.4621 35.0965 58.2129 35.0983C59.8687 35.0983 60.6245 33.8921 60.6977 33.7751C60.6999 33.7717 60.7014 33.7693 60.7024 33.7678L61.0383 33.1816L60.416 32.9237C58.3855 32.0778 57.9357 29.8054 57.8458 28.5744C58.0293 28.6094 58.2387 28.6426 58.4755 28.6499H58.5782ZM60.1883 33.4673C60.1883 33.4673 59.5934 34.5049 58.2294 34.5049H58.2129C56.5753 34.4957 55.9144 33.4931 55.9144 33.4931C55.9144 33.4931 56.0099 34.798 55.4188 36.5579C54.7083 38.6791 55.4243 40.1959 55.4243 40.1959C55.4243 40.1959 55.0424 40.2991 54.6019 40.2991C54.4329 40.2991 54.2567 40.2825 54.0915 40.2382C52.7238 39.9121 52.3823 38.6625 52.3823 38.6625C52.3823 38.6625 52.5089 42.8848 47.6054 42.8848H47.5669C47.5669 42.8848 47.9983 42.295 48.1268 40.5976C48.3599 37.4647 50.473 37.0353 50.473 37.0353C50.473 37.0353 49.3457 36.8934 48.8574 36.4529C48.0937 35.7581 48.1469 34.9546 48.1469 34.9546C49.8727 34.9307 51.0018 34.3409 51.8627 33.3402C52.7954 32.2565 52.9496 31.09 52.9496 31.09C52.9496 31.09 52.0224 31.934 50.2289 31.934C50.071 31.934 49.9021 31.9266 49.7294 31.91C48.8813 31.8419 48.2938 31.3 48.2938 31.3C50.1609 30.0985 51.4717 28.0012 50.3096 24.8424C49.3789 22.3121 47.1428 20.4213 44.892 18.9967C44.143 18.5267 43.7262 18.0052 43.7262 17.5555C43.7262 16.9075 44.2884 16.644 44.8653 16.3736C45.2302 16.2025 45.6011 16.0287 45.8393 15.753C46.5479 14.9274 46.1036 13.5877 45.4886 12.7823C45.4886 12.7823 45.6961 12.7399 46.0229 12.7399C46.671 12.7399 47.7927 12.9039 48.7142 13.8622C49.9388 15.1338 49.4302 16.5179 49.4302 16.5179L49.7588 16.5824C49.9473 16.6231 50.134 16.6316 50.3272 16.6403L50.3519 16.6414C50.3739 16.6414 50.3959 16.6419 50.4179 16.6423C50.4399 16.6428 50.462 16.6433 50.4841 16.6433C52.2629 16.6433 53.0579 14.7763 53.0579 14.7763C53.0579 14.7763 54.9488 17.1113 52.1656 18.9395C51.6865 19.2528 51.4754 19.5421 51.5305 19.8518L51.5745 20.0545L51.7727 20.0896C53.7022 20.6147 54.72 21.9152 55.8955 23.4172C55.9492 23.4858 56.0032 23.5548 56.0576 23.6242C57.9504 26.0403 60.629 26.1178 60.9594 26.1178H60.9943C60.9943 26.1178 60.3829 28.0547 58.5819 28.0547C58.5525 28.0547 58.5269 28.0547 58.4957 28.0529C57.6677 28.0233 57.2436 27.7045 57.2436 27.7045C57.2436 27.7045 56.9187 32.1036 60.1883 33.4673ZM56.4233 21.4183C56.0029 20.8949 55.5237 20.3052 54.6995 19.8647L53.8513 19.4059L54.637 18.8512C54.6402 18.8492 54.6446 18.8464 54.6502 18.8428C54.7609 18.7726 55.3348 18.4088 56.0965 18.4088C56.7519 18.4088 57.3596 18.6798 57.8626 19.1939C59.0853 20.4489 60.1831 20.5484 60.2235 20.5502L61.0772 20.6019L60.7247 21.387C60.3741 22.1702 59.4487 22.7361 58.5272 22.7361C58.2922 22.7361 58.0608 22.7011 57.8424 22.6254C57.2363 22.4232 56.8454 21.94 56.4375 21.4358L56.4233 21.4183ZM57.4423 19.6123C56.9887 19.1423 56.5096 19.0023 56.0984 19.0023C55.4595 19.0023 54.9784 19.3413 54.9784 19.3413C55.9076 19.837 56.4372 20.4952 56.8819 21.0479C57.2606 21.5185 57.5779 21.9127 58.0279 22.0652C58.1949 22.1223 58.362 22.1481 58.529 22.1481C59.2505 22.1481 59.9482 21.6781 60.1886 21.1438C60.1886 21.1438 58.865 21.0627 57.4423 19.6123ZM55.0847 27.6954V27.725C54.5891 23.711 51.6847 20.8122 48.4537 19.0485C48.1545 18.8881 47.8791 18.7518 47.6111 18.6209C48.595 18.7738 50.1794 18.7922 51.4021 17.727C51.4021 17.727 49.5313 19.1093 45.6981 17.8543C46.0057 18.0546 46.3973 18.2788 46.8443 18.5346L46.8444 18.5346C47.9394 19.1614 49.3664 19.9781 50.7026 21.0996C55.4079 25.2499 52.041 29.2988 52.041 29.2988C53.7318 27.5278 53.8015 25.6296 53.3041 24.0797C54.1944 25.5669 54.7636 27.3729 54.6754 29.5992C54.5451 32.7893 52.7992 35.5482 50.9615 37.7653C50.0784 38.8415 49.8802 40.1629 49.8802 40.1629C49.8802 40.1629 50.2363 38.9963 51.2588 37.7966C53.2287 35.4819 54.9122 33.0768 55.1325 29.6121C55.3363 30.2406 56.1753 31.9895 57.5063 32.6474L57.5058 32.6471C57.4727 32.6263 55.8291 31.5935 55.0847 27.6954ZM30.0067 42.5438C30.0079 42.546 30.0086 42.5471 30.0087 42.5474L30.6073 43.4596L29.5277 43.4743C27.7469 43.4743 26.3847 42.9804 25.4375 42.0037C24.8812 41.425 24.5599 40.7725 24.3726 40.1939C24.0661 40.4594 23.6622 40.6915 23.139 40.815C22.9444 40.861 22.7277 40.8868 22.4927 40.8868C22.0066 40.8868 21.5929 40.7862 21.5268 40.7701L21.5216 40.7689L20.8313 40.5847L21.136 39.9395L21.137 39.9372C21.1715 39.8575 21.7273 38.5725 21.1159 36.7476C20.8497 35.9496 20.7138 35.2346 20.6477 34.678C20.2126 34.9047 19.6325 35.0926 18.8872 35.0946C17.2055 35.0946 16.45 33.8556 16.3961 33.7672L16.3941 33.7639L16.0581 33.1779L16.6786 32.9199C18.711 32.0721 19.1606 29.7997 19.2525 28.5705C19.0653 28.6074 18.8559 28.6388 18.6228 28.6462L18.5164 28.6498C16.3059 28.6498 15.5698 26.394 15.5404 26.2982L15.2871 25.504L16.1187 25.5298C16.4106 25.5298 18.8523 25.4652 20.578 23.261L20.7413 23.0565L20.7552 23.0387C21.8792 21.6041 22.9468 20.2416 24.85 19.6195C24.7949 19.5697 24.7178 19.507 24.6076 19.437C23.5924 18.7661 23.0013 17.9515 22.8544 17.0062C22.6393 15.6181 23.4998 14.5026 23.5721 14.4088L23.5759 14.4039L24.1965 13.641L24.5802 14.5458L24.5805 14.5465C24.6108 14.614 25.2554 16.0515 26.6124 16.0515H26.7115C26.7381 16.0499 26.7644 16.0485 26.7905 16.047L26.791 16.047L26.7914 16.047C26.855 16.0435 26.9173 16.0401 26.9796 16.0349C26.9337 15.3899 27.053 14.3892 27.9581 13.4493C29.0449 12.3196 30.374 12.1501 31.0717 12.1501C31.408 12.1501 31.6392 12.1872 31.7028 12.1974C31.71 12.1986 31.7151 12.1994 31.7179 12.1998L32.6506 12.3841L32.0741 13.1415C31.6261 13.7258 31.2167 14.8057 31.7014 15.3678C31.8476 15.5372 32.1405 15.6768 32.4301 15.8149L32.4779 15.8377C33.104 16.1253 33.9558 16.5289 33.9558 17.5535C33.9558 18.217 33.4473 18.9099 32.5183 19.4996C29.7646 21.2357 28.07 23.0472 27.3376 25.0469C26.2067 28.1209 27.679 29.8754 29.1202 30.8005L29.7646 31.2171L29.2009 31.7368C29.1239 31.8049 28.4335 32.413 27.4147 32.5015C27.2219 32.5144 27.0401 32.5255 26.8639 32.5255C26.2085 32.5255 25.6559 32.4223 25.2043 32.2841C25.3292 32.5033 25.4833 32.7263 25.6743 32.9512C26.4949 33.904 27.5065 34.3389 28.955 34.3629L29.4983 34.3721L29.535 34.9158C29.5406 35.0245 29.581 36.0325 28.6319 36.8896C28.4868 37.0241 28.3143 37.1328 28.127 37.2213C28.7732 37.8036 29.4268 38.8172 29.5553 40.5551C29.6608 41.9442 29.9785 42.4949 30.0067 42.5438ZM24.7087 38.6625C24.7087 38.6625 24.5838 42.8846 29.4874 42.8846H29.5222C29.5222 42.8846 29.0945 42.2948 28.9678 40.5975C28.7365 37.4645 26.6216 37.0351 26.6216 37.0351C26.6216 37.0351 27.7488 36.8914 28.2389 36.4527C29.0027 35.758 28.9476 34.9545 28.9476 34.9545C27.2219 34.9305 26.0928 34.3407 25.2264 33.3401C24.2992 32.2565 24.145 31.0898 24.145 31.0898C24.145 31.0898 25.0722 31.9339 26.8639 31.9339C27.0255 31.9339 27.1925 31.9266 27.3652 31.91C28.2151 31.8417 28.8026 31.3 28.8026 31.3C26.9318 30.0964 25.6229 28.0011 26.7832 24.8423C27.7121 22.3119 29.9481 20.4211 32.2008 18.9965C32.9498 18.5266 33.3665 18.0032 33.3665 17.5553C33.3665 16.9069 32.8042 16.6427 32.2278 16.372C31.8639 16.2011 31.4944 16.0275 31.2572 15.753C30.5485 14.9274 30.9927 13.5893 31.6059 12.7821C31.6059 12.7821 31.4021 12.7398 31.0735 12.7398C30.4273 12.7398 29.3056 12.902 28.384 13.8621C27.1576 15.1338 27.6662 16.5177 27.6662 16.5177L27.3376 16.5823C27.1608 16.6208 26.981 16.6304 26.7997 16.6401H26.7997C26.7813 16.6411 26.763 16.6421 26.7446 16.6431C26.7215 16.6431 26.6997 16.6436 26.6782 16.644H26.6782H26.6782H26.6781H26.6781H26.6781H26.678C26.6569 16.6445 26.636 16.645 26.6142 16.645C24.8316 16.645 24.0404 14.7799 24.0404 14.7799C24.0404 14.7799 22.1495 17.1131 24.9327 18.9431C25.4117 19.2564 25.6211 19.5457 25.566 19.8553L25.5219 20.0581L25.3199 20.0931C23.3908 20.6209 22.3713 21.9233 21.1932 23.4283C21.1415 23.4944 21.0894 23.5609 21.0369 23.6278C19.1442 26.0439 16.4693 26.1213 16.137 26.1213H16.1003C16.1003 26.1213 16.7098 28.0582 18.5127 28.0582C18.542 28.0582 18.5695 28.0582 18.5989 28.0564C19.4288 28.025 19.8528 27.7081 19.8528 27.7081C19.8528 27.7081 20.1741 32.1053 16.9063 33.4672C16.9063 33.4672 17.5011 34.5048 18.8651 34.5048H18.8817C20.521 34.4956 21.1802 33.4931 21.1802 33.4931C21.1802 33.4931 21.0847 34.7978 21.6721 36.5578C22.3826 38.6791 21.6703 40.1957 21.6703 40.1957C21.6703 40.1957 22.0504 40.299 22.4909 40.299C22.6616 40.299 22.8361 40.2824 22.9995 40.2382C24.3672 39.9119 24.7087 38.6625 24.7087 38.6625ZM18.566 22.738C18.8028 22.738 19.0341 22.703 19.2526 22.6274C19.8599 22.426 20.2483 21.9458 20.6544 21.4438L20.6735 21.4202L20.6747 21.4187L20.6747 21.4187C21.0967 20.8958 21.572 20.3067 22.3955 19.8667L23.2437 19.4097L22.4561 18.8549L22.4487 18.8502C22.3542 18.7888 21.7748 18.4127 21.0003 18.4127C20.3431 18.4127 19.7336 18.6817 19.2323 19.1977C18.0097 20.4509 16.9137 20.5522 16.8732 20.5542L16.0196 20.6038L16.3703 21.3908C16.7228 22.1703 17.6461 22.738 18.566 22.738ZM19.6527 19.6143C20.108 19.1442 20.5872 19.0043 21.0003 19.0043C21.6354 19.0043 22.1165 19.3432 22.1165 19.3432C21.1904 19.8389 20.661 20.4952 20.216 21.0469C19.8353 21.5188 19.5163 21.9143 19.0634 22.0672C18.9019 22.1243 18.733 22.1501 18.566 22.1501C17.8444 22.1501 17.1468 21.682 16.9082 21.1457C16.9082 21.1457 16.9099 21.1455 16.9132 21.1452C17.0033 21.1363 18.2811 21.0107 19.6527 19.6143ZM23.7888 24.0815C22.8967 25.5687 22.3275 27.3748 22.4193 29.601C22.5478 32.7912 24.2937 35.5519 26.1277 37.7653C27.0145 38.8415 27.2146 40.1629 27.2146 40.1629C27.2146 40.1629 26.8548 38.9963 25.8321 37.7966C23.8622 35.4818 22.1789 33.0769 21.9567 29.6122C21.7529 30.2424 20.9158 31.9895 19.5865 32.6475C19.5865 32.6475 21.2572 31.6357 22.0081 27.6937V27.725C22.5055 23.7111 25.4081 20.8121 28.6429 19.0484C28.9255 18.8951 29.193 18.7637 29.4487 18.6381L29.4837 18.6209C28.4996 18.7757 26.9152 18.7941 25.6926 17.7289C25.6926 17.7289 27.5634 19.1111 31.3948 17.8561C31.0866 18.0571 30.6938 18.2821 30.2453 18.5391L30.2453 18.5391C29.152 19.1655 27.7277 19.9815 26.392 21.1015C21.6849 25.2518 25.0519 29.3007 25.0519 29.3007C23.3593 27.5296 23.2914 25.6314 23.7888 24.0815ZM37.4404 21.4792V22.2754C37.7139 21.9419 38.2427 22.0101 38.2427 22.0101V23.1582C38.2427 23.1582 38.2243 23.7222 38.0242 23.9672H38.597H39.1679C38.9715 23.724 38.9512 23.1582 38.9512 23.1582V22.0101C38.9512 22.0101 39.48 21.9419 39.7517 22.2754V21.4792H38.597H37.4404ZM37.5209 17.0981C37.4721 17.0818 37.2699 17.014 36.9484 16.7576C36.3444 16.2858 36.3316 15.8527 36.3316 15.8509L37.6754 16.4185V17.1686L37.5322 17.1023C37.5313 17.1016 37.5275 17.1004 37.5209 17.0981ZM40.1447 16.7576C40.7523 16.2858 40.7634 15.8509 40.7634 15.8509L39.4195 16.4185V17.1686L39.5609 17.1023C39.5619 17.1017 39.5652 17.1006 39.5709 17.0987C39.6174 17.0835 39.819 17.0172 40.1447 16.7576ZM38.5528 11.544C40.6586 11.544 42.566 12.5668 42.566 13.7039L42.5257 13.9839L41.6848 18.829L42.5257 19.3211L44.7103 20.6019H44.703C44.703 20.6019 45.1949 20.8544 45.4189 21.0111C46.2561 21.5916 46.4305 22.4375 45.9476 22.7876C45.8264 22.0966 45.2849 21.376 44.2678 21.376C43.5354 21.376 42.9791 21.7538 42.5238 22.3251C42.4099 22.4689 42.3017 22.6218 42.1969 22.7876C42.0704 22.9959 41.9437 23.1802 41.8188 23.3553H47.4476C47.0327 24.1902 46.6398 25.0748 46.6398 26.0349V38.5374C46.6398 41.5635 44.3247 43.0083 41.8152 43.0083C41.6663 43.0083 41.5274 43.0078 41.3972 43.0073C39.83 43.0013 39.5323 43.0001 38.5492 43.9887C37.5596 43.0004 37.2621 43.0015 35.6906 43.0073C35.5624 43.0078 35.4257 43.0083 35.2795 43.0083C32.7718 43.0083 30.453 41.5635 30.453 38.5374V26.0349C30.453 25.0748 30.0602 24.1902 29.6471 23.3553H35.274C35.1473 23.1784 35.0206 22.9959 34.894 22.7876C34.7912 22.6218 34.6811 22.4689 34.5654 22.3251C34.1137 21.7556 33.5557 21.376 32.8231 21.376C31.8061 21.376 31.2627 22.0984 31.1434 22.7876C30.6606 22.4375 30.8368 21.5916 31.6684 21.0111C31.8961 20.8544 32.3881 20.6019 32.3881 20.6019H32.3807L34.5654 19.3211L35.4098 18.829L34.569 13.9839L34.5268 13.7039C34.5268 12.5668 36.4343 11.544 38.54 11.544H38.5455H38.551H38.5528ZM38.3105 15.274L35.3547 14.2678L36.1351 18.783L38.3105 19.9348V15.274ZM37.3779 25.1189C36.9263 24.9531 36.4508 24.6526 35.9661 24.168V24.1717H30.8625C31.081 24.7615 31.2663 25.4009 31.2663 26.0367V32.5995H42.5936C42.0262 32.0133 42.0355 31.2706 42.1786 30.7878C42.0134 30.7086 41.8776 30.6128 41.7564 30.5077C41.3139 30.8339 39.9646 31.8217 39.6103 32.0705L39.535 32.1202L35.0904 31.3149H35.0005C34.894 31.3149 34.7379 31.3112 34.5672 31.291C34.5177 31.4918 34.345 31.63 34.1468 31.63C34.1248 31.63 34.0991 31.6282 34.0752 31.6245C33.9613 31.6043 33.8585 31.5453 33.7925 31.4476C33.7227 31.3517 33.697 31.2375 33.7154 31.1195L33.732 31.0237C33.2747 30.6994 32.8507 29.9751 33.0379 29.212L33.0564 29.131L33.1224 29.0849C33.1316 29.0775 33.2068 29.026 33.3225 28.9596L32.9994 28.9099C32.8782 28.8877 32.7975 28.7754 32.8158 28.6555C32.836 28.5358 32.9498 28.4473 33.0692 28.4713L33.8384 28.5928L33.9082 25.8728L35.0537 24.6564L35.7678 26.1657L35.0133 28.7808L35.6926 28.8877C35.812 28.9061 35.8945 29.0186 35.8762 29.1421C35.8578 29.2527 35.7642 29.3263 35.6577 29.3263C35.6466 29.3263 35.6357 29.3263 35.6228 29.3245L35.0703 29.2342C35.0735 29.2449 35.0785 29.2525 35.0836 29.2602C35.0873 29.2658 35.091 29.2715 35.0942 29.2785C35.1528 29.402 35.2832 29.4885 35.3603 29.5273L38.7474 29.5807L38.7538 29.5744C38.9028 29.4287 39.2539 29.0851 39.614 28.7201C38.7786 28.4012 38.4776 27.6806 38.4261 27.2015C37.9617 27.0025 37.6386 26.7038 37.4606 26.3168C37.2421 25.8359 37.3191 25.3531 37.3779 25.1189ZM39.434 31.6466C39.8526 31.3517 41.0092 30.504 41.459 30.1723C41.1322 29.6931 41.101 29.1255 41.1744 28.7274C40.9614 28.5892 40.798 28.4325 40.6677 28.2685C40.1846 28.7973 39.2934 29.6687 39.0515 29.9052C39.0186 29.9374 38.9977 29.9578 38.9916 29.964L38.9274 30.0304L35.5329 29.9751C35.5292 30.3123 35.4759 30.5998 35.4228 30.8099C35.4178 30.8297 35.4129 30.8489 35.408 30.8681C35.4037 30.8848 35.3995 30.9015 35.3952 30.9187L39.434 31.6466ZM35.0849 29.8811C34.9655 29.8056 34.793 29.671 34.6975 29.4701C34.5874 29.2379 34.4314 29.1291 34.2019 29.131C33.9045 29.131 33.5869 29.3097 33.4492 29.3946C33.339 30.0378 33.8127 30.622 34.1303 30.7399C34.4167 30.845 34.7673 30.867 34.9454 30.867C34.9527 30.845 34.9619 30.8173 34.9711 30.7841C34.9876 30.7307 35.0022 30.6772 35.0133 30.6201C35.0628 30.3621 35.0812 30.0691 35.0849 29.8811ZM34.345 26.0496L34.2771 28.6611L34.5727 28.7053L35.2961 26.1989L34.9288 25.4286L34.345 26.0496ZM40.8146 42.2103C40.9981 42.2029 41.202 42.1993 41.4223 42.1993C41.4223 42.1993 41.9492 40.653 41.9804 40.6052C41.6848 40.4743 41.0716 40.1481 40.8347 39.9988C40.8855 40.0749 40.96 40.1546 41.0348 40.2345C41.1899 40.4005 41.3464 40.5678 41.2956 40.7083L40.8146 42.2103ZM42.6175 42.1366L42.9313 40.4927C42.9662 40.3121 42.9992 40.0725 42.9992 40.0689C42.9815 40.0508 42.96 40.0297 42.9358 40.0059C42.7947 39.8675 42.5601 39.6372 42.4191 39.3666C42.3032 39.1417 42.2813 38.9728 42.2567 38.7825C42.2344 38.6111 42.21 38.4224 42.1126 38.1596C42.1126 38.1596 42.1768 38.0305 42.3218 38.0305C42.3439 38.0305 42.3696 38.0323 42.3935 38.0397C42.5844 38.0945 42.7148 38.4198 42.8621 38.787C43.0616 39.2847 43.2922 39.8597 43.7464 39.9435C43.8236 39.9582 43.8952 39.9638 43.9576 39.9638C44.1852 39.9638 44.3211 39.8679 44.468 39.6929C44.468 39.6929 44.0714 39.0663 43.774 38.6443C43.7115 38.5564 43.6512 38.4656 43.5911 38.3752C43.2719 37.8948 42.9579 37.4223 42.3384 37.4223C42.32 37.4223 42.2998 37.4223 42.2814 37.4242C42.0392 37.4371 41.8904 37.5551 41.8904 37.5551C41.593 37.3671 41.3139 37.3026 41.0643 37.3026C40.7821 37.3026 40.538 37.3828 40.3501 37.4445C40.2324 37.4831 40.1367 37.5146 40.0674 37.5146H40.0601L38.4188 37.3523C38.1618 37.3284 37.9599 37.0686 37.8865 36.9671C37.8681 36.9395 37.8589 36.9229 37.8589 36.9229C37.8589 36.8788 37.8589 36.8326 37.8553 36.7921C37.8002 35.4468 36.7611 34.8699 36.3902 34.8607C36.3902 34.8607 36.4325 34.6046 36.3333 34.5069L35.9717 34.9473L34.7839 34.3484L35.632 35.3417L34.9142 36.1489C34.8224 36.241 34.8352 36.3645 34.872 36.418L35.1822 36.6926L35.5751 36.453C35.5953 36.4382 35.632 36.4346 35.6742 36.4346C35.7243 36.4346 35.7861 36.4405 35.8526 36.4468C35.93 36.4542 36.0138 36.4622 36.0928 36.4622C36.2543 36.4622 36.392 36.429 36.4086 36.2779C36.3937 36.4283 36.3414 36.6123 36.2839 36.8145C36.1507 37.2835 35.9895 37.8508 36.2012 38.3272L36.427 38.8433L34.9766 38.6331L34.2974 39.785C34.0862 40.1684 34.0531 40.5812 34.2552 40.8078L34.5104 41.1267C34.5104 41.1267 34.8664 40.7489 34.9454 40.4983L34.8297 40.371C34.7324 40.2642 34.7361 40.1665 34.7967 40.0523L35.2226 39.3446L36.9354 39.75L37.0272 39.5952C37.1429 39.3998 37.31 39.3428 37.4935 39.3428C37.5688 39.3428 37.6459 39.352 37.7267 39.3648C38.0444 39.4275 38.5676 39.5123 39.0908 39.5123C39.6635 39.5123 40.2345 39.4109 40.5374 39.0681C40.8553 39.5411 41.3404 39.81 41.7246 40.023C42.0903 40.2257 42.3646 40.3778 42.3163 40.607L41.9859 42.1881C42.2044 42.1789 42.4173 42.1643 42.6175 42.1366ZM34.1927 39.1271L34.1174 38.9372C34.0624 38.8138 34.0697 38.6793 34.1725 38.6074L34.7857 38.1817L35.81 38.3789C35.81 38.3789 35.6247 38.1466 35.643 37.8351L34.6663 37.6269L33.7448 38.1909C33.3483 38.4876 33.3207 38.9722 33.451 39.2597L33.5741 39.5288C33.5741 39.5288 34.1431 39.2929 34.1927 39.1271ZM43.3665 40.5756L43.0856 42.0463C44.7654 41.6316 45.832 40.36 45.8302 38.5356L45.8264 33.0381H31.2645V38.5356C31.2645 40.7839 32.8855 42.1919 35.2777 42.1919C37.2769 42.1919 37.8038 42.2692 38.5473 43.0101C39.0596 42.496 39.4836 42.3043 40.3538 42.2343L40.374 42.1477L40.8329 40.7249C40.8073 40.6917 40.0563 39.8366 40.0563 39.8366C39.7571 39.9361 39.5075 39.9527 39.0889 39.9527C38.6574 39.9527 38.1709 39.9029 37.6441 39.8034C37.5908 39.7924 37.5413 39.7868 37.4917 39.7868C37.4274 39.7868 37.4219 39.7924 37.4055 39.8237L37.3137 39.9748L37.1466 40.2513L36.8326 40.1794L35.4356 39.8495L35.2446 40.1665L35.2722 40.1996L35.4429 40.3876L35.3677 40.6328C35.2649 40.9534 34.9325 41.3275 34.8297 41.4308L34.481 41.8067L34.1634 41.4031L33.9246 41.1009C33.6714 40.817 33.6144 40.3858 33.7558 39.9324C33.7521 39.9361 33.7466 39.938 33.7411 39.9398L33.3481 40.1039L33.1701 39.7168L33.0453 39.4423C32.8305 38.9594 32.9094 38.2535 33.4877 37.8278L34.6131 37.1901L35.6834 37.4021C35.6951 37.3551 35.7094 37.2533 35.7244 37.1474C35.7423 37.0204 35.761 36.8876 35.7771 36.8363C35.7569 36.8345 35.7385 36.829 35.7165 36.829L35.0996 37.0594L34.5048 36.6484C34.5048 36.6484 34.2716 36.4235 34.4828 36.1987L35.0592 35.3233L33.5997 33.484L35.8432 34.4092L35.9918 34.223L36.2947 33.8581L36.6399 34.1862C36.7372 34.2801 36.7923 34.3962 36.8198 34.5123C37.4385 34.7538 38.5473 35.467 38.6446 36.6262L38.2976 36.7663V36.7737C38.3766 36.8694 38.4427 36.9082 38.4628 36.9119L40.0656 37.0704C40.0932 37.0653 40.1259 37.0551 40.162 37.0439C40.1787 37.0387 40.1962 37.0332 40.2142 37.028C40.4125 36.9598 40.7118 36.8622 41.0662 36.8622C41.3544 36.8622 41.6315 36.9266 41.9014 37.0538C41.9987 37.0169 42.1199 36.9893 42.2613 36.9801L42.3384 36.9783C43.1949 36.9783 43.6227 37.6202 43.9639 38.1324L43.9649 38.1338C44.0219 38.2241 44.0788 38.307 44.1375 38.3899C44.4386 38.8138 44.8278 39.433 44.8443 39.4607L45.0206 39.7371L44.8039 39.9896C44.6369 40.183 44.3872 40.4098 43.9576 40.4098C43.8694 40.4098 43.7721 40.4006 43.6675 40.3802C43.5757 40.3637 43.4912 40.3361 43.4123 40.3029C43.4033 40.362 43.3917 40.4292 43.3805 40.4939C43.3757 40.5219 43.3709 40.5494 43.3665 40.5756ZM45.8264 26.0331C45.8264 25.3973 46.0119 24.7577 46.234 24.168V24.1662H41.1359C40.3538 24.9439 39.603 25.2332 38.9329 25.2996C38.8117 25.3125 38.6887 25.3199 38.5638 25.3199H38.5547H38.5473C38.4225 25.3199 38.2976 25.3125 38.1764 25.2996C38.059 25.2904 37.936 25.27 37.813 25.2424C37.7689 25.4175 37.712 25.7825 37.8717 26.1307C38.0132 26.4459 38.3013 26.68 38.7217 26.8365L38.8631 26.8918L38.865 27.0448C38.865 27.0909 38.898 28.0843 39.9738 28.3588C40.1482 28.1782 40.3079 28.0124 40.4272 27.8759C40.2069 27.3562 40.3226 26.8255 40.6421 26.5657C40.6292 26.6191 40.4878 27.922 41.5269 28.4049L41.6976 28.4859L41.6463 28.6703C41.6279 28.7237 41.2827 29.9843 42.5403 30.4524L42.7624 30.5371L42.6633 30.7547C42.6394 30.8026 42.1474 31.9452 43.3095 32.5939H45.8284V30.5851C44.9324 30.4745 44.5524 29.7521 44.4551 29.2398C44.0383 29.1679 43.717 28.9872 43.495 28.6924C43.0235 28.0696 43.3344 27.4937 43.4112 27.3514C43.4206 27.3341 43.4265 27.3232 43.4271 27.3194C43.4251 27.325 43.4271 28.0068 43.8456 28.427C44.0531 28.6297 44.299 28.7882 44.6699 28.8232L44.8534 28.8398L44.87 29.0278C44.8737 29.0683 44.9746 29.9917 45.8284 30.1355L45.8264 26.0331ZM43.304 20.6019L42.309 20.0214L41.2845 19.4336L38.5528 20.9263L38.5455 20.9208L38.54 20.9263L35.8082 19.4336L34.7857 20.0214L33.7906 20.6019H33.7796L33.5263 20.7475C34.0898 20.9024 34.5177 21.1825 34.8793 21.5529C34.8867 21.5595 34.893 21.5665 34.8992 21.5734C34.9068 21.5818 34.9142 21.59 34.9233 21.5971C35.0022 21.6837 35.0812 21.7777 35.1583 21.8717C35.1767 21.8938 35.1933 21.9141 35.2116 21.9398C35.2832 22.0339 35.3529 22.1334 35.4228 22.2347C35.4309 22.246 35.439 22.2585 35.4475 22.2714C35.4543 22.2817 35.4612 22.2923 35.4686 22.303C35.5513 22.4283 35.6283 22.5572 35.7054 22.6899C36.4141 23.9155 37.545 24.6472 38.5492 24.649C39.5552 24.6472 40.6861 23.9155 41.3948 22.6899C41.4737 22.5536 41.5527 22.4209 41.6352 22.2974C41.6481 22.2809 41.6609 22.2625 41.6719 22.2439C41.7418 22.1372 41.8133 22.0357 41.8904 21.9362C41.8997 21.9249 41.9083 21.9135 41.9168 21.9025C41.9234 21.8939 41.9299 21.8854 41.9364 21.8773C42.0134 21.7796 42.096 21.6837 42.1786 21.5953C42.1832 21.5907 42.1878 21.5859 42.1923 21.5811C42.2006 21.5724 42.2089 21.5637 42.2172 21.5566C42.577 21.1861 43.0067 20.9042 43.5684 20.7475L43.3187 20.6019H43.304ZM38.7841 19.9367L40.9614 18.7848L41.738 14.2678L38.7841 15.274V19.9367ZM35.3089 13.4772L38.5473 14.7617L41.7858 13.4772C41.5122 12.823 40.2418 12.2663 38.5473 12.2645C36.8547 12.2663 35.5843 12.823 35.3089 13.4772Z" fill="#333333"/>
</g>
<defs>
<filter id="filter0_d_17003_5263" x="0" y="0" width="250" height="64" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
<feFlood flood-opacity="0" result="BackgroundImageFix"/>
<feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
<feOffset dy="4"/>
<feGaussianBlur stdDeviation="2"/>
<feComposite in2="hardAlpha" operator="out"/>
<feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
<feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_17003_5263"/>
<feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_17003_5263" result="shape"/>
</filter>
</defs>
</svg>
</a>
<?endif;?>
				</div>
				<?$frame->end();?>
			</div>
			<?if(is_array($arResult["STOCK"]) && $arResult["STOCK"]):?>
				<?foreach($arResult["STOCK"] as $key => $arStockItem):?>
					<div class="stock_board">
						<div class="title"><?=GetMessage("CATALOG_STOCK_TITLE")?></div>
						<div class="txt"><?=$arStockItem["PREVIEW_TEXT"]?></div>
						<a class="read_more" href="<?=$arStockItem["DETAIL_PAGE_URL"]?>"><?=GetMessage("CATALOG_STOCK_VIEW")?></a>
					</div>
				<?endforeach;?>
			<?endif;?>

<?
$weight = $arResult['PROPERTIES']['VES_1']['VALUE'];
if(!$weight) 
	$weight = 10;
?>
<div class="delivery">
	<div class="delivery_title">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file","PATH" => SITE_DIR."include/delviery_title.php"), false);?>
</div>

<div class="delivery_block">
<button type="button" сlass="big_btn type_block button"
            data-widget-button
            data-article="<?=$arResult['ID']?>"
            data-name="<?=$arResult['NAME']?>"
            data-price="<?=$arResult['MIN_PRICE']['VALUE']?>"
            data-unit="шт"
            data-ip="<?=$_SERVER['REMOTE_ADDR']?>"
            data-image="https://ruoborudovanie.ru/<?=$arResult['DETAIL_PICTURE']['SRC']?>"
            data-weight="<?=$weight?>">
        Расчет стоимости доставки
    </button>
<div class="delivery_note">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file","PATH" => SITE_DIR."include/delivery_text.php"), false);?>
	</div>
		</div>
    <div id="eShopLogisticApp" data-key="151401-1557-1546"></div>
    <script src="https://api.eshoplogistic.ru/widget/modal/v1/app.js"></script>
	</div>
<!--
<div id="eShopLogisticWidgetForm" data-key="151401-1557-1546" data-weight="3" data-price="<?=$arResult['MIN_PRICE']['VALUE']?>"></div>
    <script src="https://api.eshoplogistic.ru/widget/form/app.js"></script>

-->
			<div class="element_detail_text wrap_md">
				<div style='margin-bottom:0.5rem'>
				Мы ценим своих клиентов и хотим предложить Вам лучшие условия, получить консультацию можно по телефону:<br>
				<a href="tel:8 (800) 707-42-63">8 (800) 707-42-63</a><span> звонок БЕСПЛАТНЫЙ
				</div>
				<div style='margin-bottom:0.5rem'>
				Реквизиты отправлять на почту: <a href="mailto:info@ruoborudovanie.ru">info@ruoborudovanie.ru</a>
				</div>
			</div>
			
			
			<div class="element_detail_text wrap_md">
				<div class="sh">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/share_buttons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_SOC_BUTTON')));?>
				</div>
				<div class="price_txt">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/element_detail_text.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('CT_BCE_CATALOG_DOP_DESCR')));?>
				</div>
			</div>
		</div>
	</div>
	<?if($arResult['OFFERS']):?>
		<span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer" style="display:none;">
			<?$lowPrice = ($arResult['MIN_PRICE']['DISCOUNT_VALUE'] ? $arResult['MIN_PRICE']['DISCOUNT_VALUE'] : $arResult['MIN_PRICE']['VALUE'] )?>
			<?$currency = $arResult['MIN_PRICE']['CURRENCY'] ? $arResult['MIN_PRICE']['CURRENCY'] : $arParams['CURRENCY_ID']?>
			<meta itemprop="lowPrice" content="<?=($lowPrice ? $lowPrice : 0)?>" />
		<meta itemprop="priceCurrency" content="<?=$currency ? $currency : CSaleLang::GetLangCurrency(SITE_ID)?>" />
		  <meta itemprop="offerCount" content="<?=count($arResult['OFFERS'])?>" />
			<?foreach($arResult['OFFERS'] as $arOffer):?>
				<?$currentOffersList = array();?>
				<?foreach($arOffer['TREE'] as $propName => $skuId):?>
					<?$propId = (int)substr($propName, 5);?>
					<?foreach($arResult['SKU_PROPS'] as $prop):?>
						<?if($prop['ID'] == $propId):?>
							<?foreach($prop['VALUES'] as $propId => $propValue):?>
								<?if($propId == $skuId):?>
									<?$currentOffersList[] = $propValue['NAME'];?>
									<?break;?>
								<?endif;?>
							<?endforeach;?>
						<?endif;?>
					<?endforeach;?>
				<?endforeach;?>
				<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<meta itemprop="sku" content="<?=implode('/', $currentOffersList)?>" />
					<a href="<?=$arOffer['DETAIL_PAGE_URL']?>" itemprop="url"></a>
					<meta itemprop="price" content="<?=($arOffer['MIN_PRICE']['DISCOUNT_VALUE']) ? $arOffer['MIN_PRICE']['DISCOUNT_VALUE'] : $arOffer['MIN_PRICE']['VALUE']?>" />
					<meta itemprop="priceCurrency" content="<?=$arOffer['MIN_PRICE']['CURRENCY']?>" />
					<link itemprop="availability" href="http://schema.org/<?=($arOffer['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
					<?
					if($arDiscount["ACTIVE_TO"]){?>
						<meta itemprop="priceValidUntil" content="<?=date("Y-m-d", MakeTimeStamp($arDiscount["ACTIVE_TO"]))?>" />
					<?}?>
					<link itemprop="url" href="<?=$arResult["DETAIL_PAGE_URL"]?>" />
				</span>
			<?endforeach;?>
		</span>
		<?unset($arOffer, $currentOffersList);?>
	<?else:?>
		<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<?$price = ($arResult['MIN_PRICE']['DISCOUNT_VALUE'] ? $arResult['MIN_PRICE']['DISCOUNT_VALUE'] : $arResult['MIN_PRICE']['VALUE'] )?>
			<?$currency = $arResult['MIN_PRICE']['CURRENCY'] ? $arResult['MIN_PRICE']['CURRENCY'] : $arParams['CURRENCY_ID']?>
			<meta itemprop="price" content="<?=($price ? $price : 0)?>" />
			<meta itemprop="priceCurrency" content="<?=$currency ? $currency : CSaleLang::GetLangCurrency(SITE_ID)?>" />
			
			<link itemprop="availability" href="http://schema.org/<?=($arResult['MIN_PRICE']['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
			<?
			if($arDiscount["ACTIVE_TO"]){?>
				<meta itemprop="priceValidUntil" content="<?=date("Y-m-d", MakeTimeStamp($arDiscount["ACTIVE_TO"]))?>" />
			<?}?>
			<link itemprop="url" href="<?=$arResult["DETAIL_PAGE_URL"]?>" />
		</span>
	<?endif;?>
	<div class="clearleft"></div>
	<?if($arResult["TIZERS_ITEMS"]){?>
		<div class="tizers_block_detail">
			<div class="rows_block">
				<?$count_t_items=count($arResult["TIZERS_ITEMS"]);?>
				<?foreach($arResult["TIZERS_ITEMS"] as $arItem){?>
					<div class="item_block tizer col-<?=$count_t_items;?>">
						<div class="inner_wrapper">
							<?if($arItem["UF_LINK"]){?>
								<a href="<?=$arItem["UF_LINK"];?>" <?=(strpos($arItem["UF_LINK"], "http") !== false ? "target='_blank' rel='nofollow'" : '')?>>
							<?}?>
							<?if($arItem["UF_FILE"]){?>
								<div class="image">
									<img src="<?=$arItem["PREVIEW_PICTURE"]["src"];?>" alt="<?=$arItem["UF_NAME"];?>" title="<?=$arItem["UF_NAME"];?>">
								</div>
							<?}?>
							<div class="text">
								<?=$arItem["UF_NAME"];?>
							</div>
							<div class="clearfix"></div>
							<?if($arItem["UF_LINK"]){?>
								</a>
							<?}?>
						</div>
					</div>
				<?}?>
			</div>
		</div>
	<?}?>

	<?if($arParams["SHOW_KIT_PARTS"] == "Y" && $arResult["SET_ITEMS"]):?>
		<div class="set_wrapp set_block">
			<div class="title"><?=GetMessage("GROUP_PARTS_TITLE")?></div>
			<ul>
				<?foreach($arResult["SET_ITEMS"] as $iii => $arSetItem):?>
					<li class="item">
						<div class="item_inner">
							<div class="image">
								<a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>">
									<?if($arSetItem["PREVIEW_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arSetItem["PREVIEW_PICTURE"], array("width" => 140, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
										<img  src="<?=$img["src"]?>" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
									<?elseif($arSetItem["DETAIL_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arSetItem["DETAIL_PICTURE"], array("width" => 140, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
										<img  src="<?=$img["src"]?>" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
									<?else:?>
										<img  src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
									<?endif;?>
								</a>
								<?if($arResult["SET_ITEMS_QUANTITY"]):?>
									<div class="quantity">x<?=$arSetItem["QUANTITY"];?></div>
								<?endif;?>
							</div>
							<div class="item_info">
								<div class="item-title">
									<a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>"><span><?=$arSetItem["NAME"]?></span></a>
								</div>
								<?if($arParams["SHOW_KIT_PARTS_PRICES"] == "Y"):?>
									<div class="cost prices clearfix">
										<?
										$arCountPricesCanAccess = 0;
										foreach($arSetItem["PRICES"] as $key => $arPrice){
											if($arPrice["CAN_ACCESS"]){
												$arCountPricesCanAccess++;
											}
										}?>
										<?if($arSetItem["MEASURE"][$arSetItem["ID"]]["MEASURE"]["SYMBOL_RUS"])
											$strMeasure = $arSetItem["MEASURE"][$arSetItem["ID"]]["MEASURE"]["SYMBOL_RUS"];?>
										<?foreach($arSetItem["PRICES"] as $key => $arPrice):?>
											<?if($arPrice["CAN_ACCESS"]):?>
												<?$price = CPrice::GetByID($arPrice["ID"]);?>
												<?if($arCountPricesCanAccess > 1):?>
													<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
												<?endif;?>
												<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]):?>
													<div class="price">
														<?=$arPrice["PRINT_DISCOUNT_VALUE"];?><?if(($arParams["SHOW_MEASURE"] == "Y") && $strMeasure):?><small>/<?=$strMeasure?></small><?endif;?>
													</div>
													<?if($arParams["SHOW_OLD_PRICE"]=="Y"):?>
														<div class="price discount">
															<span><?=$arPrice["PRINT_VALUE"]?></span>
														</div>
													<?endif;?>
												<?else:?>
													<div class="price">
														<?=$arPrice["PRINT_VALUE"];?><?if(($arParams["SHOW_MEASURE"] == "Y") && $strMeasure):?><small>/<?=$strMeasure?></small><?endif;?>
													</div>
												<?endif;?>
											<?endif;?>
										<?endforeach;?>
									</div>
								<?endif;?>
							</div>
						</div>
					</li>
					<?if($arResult["SET_ITEMS"][$iii + 1]):?>
						<li class="separator"></li>
					<?endif;?>
				<?endforeach;?>
			</ul>
		</div>
	<?endif;?>
	<?if($arResult['OFFERS']):?>
		<?if($arResult['OFFER_GROUP']):?>
			<?foreach($arResult['OFFERS'] as $arOffer):?>
				<?if(!$arOffer['OFFER_GROUP']) continue;?>
				<span id="<?=$arItemIDs['ALL_ITEM_IDS']['OFFER_GROUP'].$arOffer['ID']?>" style="display: none;">
					<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "",
						array(
							"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
							"ELEMENT_ID" => $arOffer['ID'],
							"PRICE_CODE" => $arParams["PRICE_CODE"],
							"BASKET_URL" => $arParams["BASKET_URL"],
							"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
							"CACHE_TYPE" => $arParams["CACHE_TYPE"],
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
							"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
							"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
							"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
							"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
							"CURRENCY_ID" => $arParams["CURRENCY_ID"]
						), $component, array("HIDE_ICONS" => "Y")
					);?>
				</span>
			<?endforeach;?>
		<?endif;?>
	<?else:?>
		<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "",
			array(
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ELEMENT_ID" => $arResult["ID"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
				"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
				"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
				"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"]
			), $component, array("HIDE_ICONS" => "Y")
		);?>
	<?endif;?>
</div>

<div class="tabs_section">
	<?
	$showProps = false;
	if($arResult["DISPLAY_PROPERTIES"]){
		foreach($arResult["DISPLAY_PROPERTIES"] as $arProp){
			if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))){
				if(!is_array($arProp["DISPLAY_VALUE"])){
					$arProp["DISPLAY_VALUE"] = array($arProp["DISPLAY_VALUE"]);
				}
				if(is_array($arProp["DISPLAY_VALUE"])){
					foreach($arProp["DISPLAY_VALUE"] as $value){
						if(strlen($value)){
							$showProps = true;
							break 2;
						}
					}
				}
			}
		}
	}
	if(!$showProps && $arResult['OFFERS']){
		foreach($arResult['OFFERS'] as $arOffer){
			foreach($arOffer['DISPLAY_PROPERTIES'] as $arProp){
				if(!is_array($arProp["DISPLAY_VALUE"])){
					$arProp["DISPLAY_VALUE"] = array($arProp["DISPLAY_VALUE"]);
				}

				foreach($arProp["DISPLAY_VALUE"] as $value){
					if(strlen($value)){
						$showProps = true;
						break 3;
					}
				}
			}
		}
	}
	$arVideo = array();
	if(strlen($arResult["DISPLAY_PROPERTIES"]["VIDEO"]["VALUE"])){
		$arVideo[] = $arResult["DISPLAY_PROPERTIES"]["VIDEO"]["~VALUE"];
	}
	if(isset($arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["VALUE"])){
		if(is_array($arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["VALUE"])){
			$arVideo = $arVideo + $arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["~VALUE"];
		}
		elseif(strlen($arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["VALUE"])){
			$arVideo[] = $arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["~VALUE"];
		}
	}
	if(strlen($arResult["SECTION_FULL"]["UF_VIDEO"])){
		$arVideo[] = $arResult["SECTION_FULL"]["~UF_VIDEO"];
	}
	if(strlen($arResult["SECTION_FULL"]["UF_VIDEO_YOUTUBE"])){
		$arVideo[] = $arResult["SECTION_FULL"]["~UF_VIDEO_YOUTUBE"];
	}
	?>
	<ul class="tabs1 main_tabs1 tabs-head">
		<?$iTab = 0;?>
		<?if($arResult["OFFERS"] && $arParams["TYPE_SKU"]=="N"):?>
			<li class="prices_tab<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("OFFER_PRICES")?></span>
			</li>
		<?endif;?>
		<?if($arResult["DETAIL_TEXT"] || count($arResult["STOCK"]) || count($arResult["SERVICES"]) || ((count($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]) && is_array($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"])) || count($arResult["SECTION_FULL"]["UF_FILES"])) || ($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB")):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("DESCRIPTION_TAB")?></span>
			</li>
		<?endif;?>
		<?if($arParams["PROPERTIES_DISPLAY_LOCATION"] == "TAB" && $showProps):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("PROPERTIES_TAB")?></span>
			</li>
		<?endif;?>
		<?if($arVideo):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("VIDEO_TAB")?></span>
				<?if(count($arVideo) > 1):?>
					<span class="count empty">&nbsp;(<?=count($arVideo)?>)</span>
				<?endif;?>
			</li>
		<?endif;?>
		<?if($arParams["USE_REVIEW"] == "Y"):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>" id="product_reviews_tab">
				<span><?=GetMessage("REVIEW_TAB")?></span><span class="count empty"></span>
			</li>
		<?endif;?>
		<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
			<li class="product_ask_tab <?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage('ASK_TAB')?></span>
			</li>
		<?endif;?>
		<?if($useStores && ($showCustomOffer || !$arResult["OFFERS"] )):?>
			<li class="stores_tab<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("STORES_TAB");?></span>
			</li>
		<?endif;?>
		<?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("ADDITIONAL_TAB");?></span>
			</li>
		<?endif;?>
	</ul>
	<?if($arResult["OFFERS"] && $arParams["TYPE_SKU"] !== "TYPE_1"):?>
		<script>
			$(document).ready(function() {
				$('.catalog_detail .tabs_section .tabs_content .form.inline input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').text());
			});
		</script>
	<?endif;?>
	<ul class="tabs_content tabs-body">
		<?$show_tabs = false;?>
		<?$iTab = 0;?>
		<?
		$showSkUName = ((in_array('NAME', $arParams['OFFERS_FIELD_CODE'])));
		$showSkUImages = false;
		if(((in_array('PREVIEW_PICTURE', $arParams['OFFERS_FIELD_CODE']) || in_array('DETAIL_PICTURE', $arParams['OFFERS_FIELD_CODE'])))){
			foreach ($arResult["OFFERS"] as $key => $arSKU){
				if($arSKU['PREVIEW_PICTURE'] || $arSKU['DETAIL_PICTURE']){
					$showSkUImages = true;
					break;
				}
			}
		}?>
		<?if($arResult["OFFERS"] && $arParams["TYPE_SKU"] !== "TYPE_1"):?>
			<li class="prices_tab<?=(!($iTab++) ? ' current' : '')?>">
				<div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage("OFFER_PRICES");?></div><div>
				<div class="bx_sku_props" style="display:none;">
					<?$arSkuKeysProp='';
					$propSKU=$arParams["OFFERS_CART_PROPERTIES"];
					if($propSKU){
						$arSkuKeysProp=base64_encode(serialize(array_keys($propSKU)));
					}?>
					<input type="hidden" value="<?=$arSkuKeysProp;?>"></input>
				</div>

				<div class="list-offers ajax_load">
					<div class="bx_sku_props" style="display:none;">
						<?$arSkuKeysProp='';
						$propSKU=$arParams["OFFERS_CART_PROPERTIES"];
						if($propSKU){
							$arSkuKeysProp=base64_encode(serialize(array_keys($propSKU)));
						}?>
						<input type="hidden" value="<?=$arSkuKeysProp;?>" />
					</div>
					<div class="table-view flexbox flexbox--row">
						<?foreach($arResult["OFFERS"] as $key => $arSKU):?>
							<?
							if($arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"])
								$sMeasure = $arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"];
							else
								$sMeasure = GetMessage("MEASURE_DEFAULT");
							$skutotalCount = COptimus::GetTotalCount($arSKU, $arParams);
							$arskuQuantityData = COptimus::GetQuantityArray($skutotalCount, array('quantity-wrapp', 'quantity-indicators'));

							$arSKU["IBLOCK_ID"]=$arResult["IBLOCK_ID"];
							$arSKU["IS_OFFER"]="Y";
							$arskuAddToBasketData = COptimus::GetAddToBasketArray($arSKU, $skutotalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true, array(), 'small', $arParams);
							$arskuAddToBasketData["HTML"] = str_replace('data-item', 'data-props="'.$arOfferProps.'" data-item', $arskuAddToBasketData["HTML"]);
							?>
							<div class="table-view__item item bordered box-shadow main_item_wrapper <?=($useStores ? "table-view__item--has-stores" : "");?>">
								<div class="table-view__item-wrapper item_info catalog-adaptive flexbox flexbox--row">
									<?if($showSkUImages):?>
										<?//image-block?>
										<div class="item-foto">
											<div class="item-foto__picture">
												<?
												$srcImgPreview = $srcImgDetail = false;
												$imgPreviewID = ($arResult['OFFERS'][$key]['PREVIEW_PICTURE'] ? (is_array($arResult['OFFERS'][$key]['PREVIEW_PICTURE']) ? $arResult['OFFERS'][$key]['PREVIEW_PICTURE']['ID'] : $arResult['OFFERS'][$key]['PREVIEW_PICTURE']) : false);
												$imgDetailID = ($arResult['OFFERS'][$key]['DETAIL_PICTURE'] ? (is_array($arResult['OFFERS'][$key]['DETAIL_PICTURE']) ? $arResult['OFFERS'][$key]['DETAIL_PICTURE']['ID'] : $arResult['OFFERS'][$key]['DETAIL_PICTURE']) : false);
												$imgPreviewID;
												if($imgPreviewID || $imgDetailID){
													$arImgPreview = CFile::ResizeImageGet($imgPreviewID ? $imgPreviewID : $imgDetailID, array('width' => 350, 'height' => 350), BX_RESIZE_IMAGE_PROPORTIONAL, true);
													$srcImgPreview = $arImgPreview['src'];
												}
												if($imgDetailID){
													$srcImgDetail = CFile::GetPath($imgDetailID);
												}
												?>
												<?if($srcImgPreview || $srcImgDetail):?>
													<img src="<?=$srcImgPreview?>" alt="<?=$arSKU['NAME']?>" />
												<?else:?>
													<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arSKU['NAME']?>"/>
												<?endif;?>
											</div>
											<div class="adaptive">
												<div class="like_icons block">
													<div class="like_icons list static icons">
														<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
															<?if($arSKU['CAN_BUY']):?>
																<div class="wish_item_button o_<?=$arSKU["ID"];?>">
																	<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item text to <?=$arParams["TYPE_SKU"];?>" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i></span>
																	<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item text in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arSKU["IBLOCK_ID"]?>"><i></i></span>
																</div>
															<?endif;?>
														<?endif;?>
														<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
															<div class="compare_item_button o_<?=$arSKU["ID"];?>">
																<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to text <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>" ><i></i></span>
																<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added text <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>"><i></i></span>
															</div>
														<?endif;?>
													</div>
												</div>
											</div>
										</div>
									<?endif;?>

									<?//text-block?>
									<div class="item-info">
										<div class="item-title"><?=$arSKU['NAME']?></div>
										<div class="quantity_block_wrapper">
											<?if($useStores){?>
												<div class="p_block">
											<?}?>
												<?=$arskuQuantityData["HTML"];?>
											<?if($useStores){?>
												</div>
											<?}?>
											<?if($arSKU['PROPERTIES']['ARTICLE']['VALUE']):?>
												<div class="muted article">
													<span class="name"></span><span class="value"><?=$arSKU['PROPERTIES']['ARTICLE']['VALUE'];?></span>
												</div>
											<?endif;?>
										</div>
										<?if($arResult["SKU_PROPERTIES"]):?>
											<div class="properties list">
												<div class="properties__container properties props_list">
													<?foreach ($arResult["SKU_PROPERTIES"] as $key => $arProp){?>
														<?if(!$arProp["IS_EMPTY"] && $key != 'ARTICLE'):?>
															<div class="properties__item properties__item--compact ">
																<?if($arResult["TMP_OFFERS_PROP"][$arProp["CODE"]]){
																	echo $arResult["TMP_OFFERS_PROP"][$arProp["CODE"]]["VALUES"][$arSKU["TREE"]["PROP_".$arProp["ID"]]]["NAME"];?>
																	<?}else{
																		if (is_array($arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"])){
																			echo implode("/", $arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"]);
																		}else{
																			if($arSKU["PROPERTIES"][$arProp["CODE"]]["USER_TYPE"]=="directory" && isset($arSKU["PROPERTIES"][$arProp["CODE"]]["USER_TYPE_SETTINGS"]["TABLE_NAME"])){
																				$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('=TABLE_NAME'=>$arSKU["PROPERTIES"][$arProp["CODE"]]["USER_TYPE_SETTINGS"]["TABLE_NAME"])));
																				if ($arData = $rsData->fetch()){
																					$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arData);
																					$entityDataClass = $entity->getDataClass();
																					$arFilter = array(
																						'limit' => 1,
																						'filter' => array(
																							'=UF_XML_ID' => $arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"]
																						)
																					);
																					$arValue = $entityDataClass::getList($arFilter)->fetch();
																					if(isset($arValue["UF_NAME"]) && $arValue["UF_NAME"]){
																						$SkuProperti = $arValue["UF_NAME"];
																					}else{
																						$SkuProperti = $arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"];
																					}
																				}
																			}else{
																				$SkuProperti =  $arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"];
																			}
																		}
																	}?>
																<?if($SkuProperti):?>
																	<div class="properties__title muted properties__item--inline char_name">
																		<?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?>
																		<span class="props_item"><?=$arProp["NAME"]?>:</span>
																	</div>
																	<div class="properties__value darken properties__item--inline char_value font_xs"><?=$SkuProperti;?></div>
																<?endif;?>
															</div>
														<?endif;?>
													<?}?>
												</div>
											</div>
										<?endif;?>
									</div>

									<div class="item-actions flexbox flexbox--row">
										<?//prices-block?>
										<div class="item-price">
											<div class="cost prices clearfix">
												<?
												$collspan++;
												$arCountPricesCanAccess = 0;
												if(isset($arSKU['PRICE_MATRIX']) && $arSKU['PRICE_MATRIX'] && count($arSKU['PRICE_MATRIX']['ROWS']) > 1) // USE_PRICE_COUNT
												{?>
													<?=COptimus::showPriceRangeTop($arSKU, $arParams, GetMessage("CATALOG_ECONOMY"));?>
													<?echo COptimus::showPriceMatrix($arSKU, $arParams, $arSKU["CATALOG_MEASURE_NAME"]);
												}
												else
												{?>
													<?\Aspro\Functions\CAsproOptimusItem::showItemPrices($arParams, $arSKU["PRICES"], $arSKU["CATALOG_MEASURE_NAME"], $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] != "N" ? "N" : "Y"));?>
												<?}?>
											</div>

											<div class="basket_props_block" id="bx_basket_div_<?=$arSKU["ID"];?>" style="display: none;">
												<?if (!empty($arSKU['PRODUCT_PROPERTIES_FILL'])){
													foreach ($arSKU['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo){?>
														<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
														<?if (isset($arSKU['PRODUCT_PROPERTIES'][$propID]))
															unset($arSKU['PRODUCT_PROPERTIES'][$propID]);
													}
												}
												$arSKU["EMPTY_PROPS_JS"]="Y";
												$emptyProductProperties = empty($arSKU['PRODUCT_PROPERTIES']);
												if (!$emptyProductProperties){
													$arSKU["EMPTY_PROPS_JS"]="N";?>
													<div class="wrapper">
														<table>
															<?foreach ($arSKU['PRODUCT_PROPERTIES'] as $propID => $propInfo){?>
																<tr>
																	<td><? echo $arSKU['PROPERTIES'][$propID]['NAME']; ?></td>
																	<td>
																		<?if('L' == $arSKU['PROPERTIES'][$propID]['PROPERTY_TYPE']	&& 'C' == $arSKU['PROPERTIES'][$propID]['LIST_TYPE']){
																			foreach($propInfo['VALUES'] as $valueID => $value){?>
																				<label>
																					<input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
																				</label>
																			<?}
																		}else{?>
																			<select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
																				foreach($propInfo['VALUES'] as $valueID => $value){?>
																					<option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
																				<?}?>
																			</select>
																		<?}?>
																	</td>
																</tr>
															<?}?>
														</table>
													</div>
													<?
												}?>
											</div>
										</div>

										<?//buttons-block?>
										<div class="item-buttons item_<?=$arSKU["ID"]?> buy_block counter_block_wr">
											<div class="counter_wrapp list clearfix n-mb small-block">
												<?if($arskuAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && !count($arSKU["OFFERS"]) && $arskuAddToBasketData["ACTION"] == "ADD" && $arSKU["CAN_BUY"]):?>
													<div class="counter_block_inner">
														<div class="counter_block" data-item="<?=$arSKU["ID"];?>">
															<span class="minus">-</span>
															<input type="text" class="text" name="quantity" value="<?=$arskuAddToBasketData["MIN_QUANTITY_BUY"];?>" />
															<span class="plus">+</span>
														</div>
													</div>
												<?endif;?>
												<div class="button_block <?=(($arskuAddToBasketData["ACTION"] == "ORDER" ) || !$arSKU["CAN_BUY"] || !$arskuAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arskuAddToBasketData["ACTION"] == "SUBSCRIBE" && $arSKU["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>">
													<?=$arskuAddToBasketData["HTML"]?>
												</div>
											</div>
											<?if($arskuAddToBasketData["ACTION"] !== "NOTHING"):?>
												<?if($arskuAddToBasketData["ACTION"] == "ADD" && $arSKU["CAN_BUY"] && $arParams["SHOW_ONE_CLICK_BUY"]!="N"):?>
													<div class="one_click_buy">
														<span class="button small transparent one_click" data-item="<?=$arSKU["ID"]?>" data-offers="Y" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=$arskuAddToBasketData["MIN_QUANTITY_BUY"];?>" data-props="<?=$arOfferProps?>" onclick="oneClickBuy('<?=$arSKU["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
															<span><?=GetMessage('ONE_CLICK_BUY')?></span>
														</span>
													</div>
												<?endif;?>
											<?endif;?>

											<?
											if(isset($arSKU['PRICE_MATRIX']) && $arSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
											{?>
												<?if($arSKU['ITEM_PRICE_MODE'] == 'Q' && count($arSKU['PRICE_MATRIX']['ROWS']) > 1):?>
													<?$arOnlyItemJSParams = array(
														"ITEM_PRICES" => $arSKU["ITEM_PRICES"],
														"ITEM_PRICE_MODE" => $arSKU["ITEM_PRICE_MODE"],
														"ITEM_QUANTITY_RANGES" => $arSKU["ITEM_QUANTITY_RANGES"],
														"MIN_QUANTITY_BUY" => $arskuAddToBasketData["MIN_QUANTITY_BUY"],
														"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
														"ID" => $this->GetEditAreaId($arSKU["ID"]),
													)?>
													<script type="text/javascript">
														var ob<? echo $this->GetEditAreaId($arSKU["ID"]); ?>el = new JCCatalogSectionOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
													</script>
												<?endif;?>
											<?}?>
										</div>
									</div>

									<?//icons-block?>
									<div class="item-icons s_2">
										<div class="like_icons list static icons">
											<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
												<?if($arSKU['CAN_BUY']):?>
													<div class="wish_item_button o_<?=$arSKU["ID"];?>">
														<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item text to <?=$arParams["TYPE_SKU"];?>" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i></span>
														<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item text in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arSKU["IBLOCK_ID"]?>"><i></i></span>
													</div>
												<?endif;?>
											<?endif;?>
											<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
												<div class="compare_item_button o_<?=$arSKU["ID"];?>">
													<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to text <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>" ><i></i></span>
													<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added text <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>"><i></i></span>
												</div>
											<?endif;?>
										</div>
									</div>

									<?//stores icon?>
									<?if($useStores):?>
										<div class="opener top">
											<?$collspan++;?>
											<span class="opener_icon"><i></i></span>
										</div>
									<?endif;?>
								</div>
								<div class="offer_stores">
									<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "main", array(
											"PER_PAGE" => "10",
											"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
											"SCHEDULE" => $arParams["SCHEDULE"],
											"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
											"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
											"ELEMENT_ID" => $arSKU["ID"],
											"STORE_PATH"  =>  $arParams["STORE_PATH"],
											"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
											"MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
											"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
											"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
											"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
											"USER_FIELDS" => $arParams['USER_FIELDS'],
											"FIELDS" => $arParams['FIELDS'],
											"STORES" => $arParams['STORES'],
											"CACHE_TYPE" => "A",
											"SET_ITEMS" => $arResult["SET_ITEMS"],
										),
										$component
									);?>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
			</li>
		<?endif;?>
		<?if($arResult["DETAIL_TEXT"] || count($arResult["STOCK"]) || count($arResult["SERVICES"]) || ((count($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]) && is_array($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"])) || count($arResult["SECTION_FULL"]["UF_FILES"])) || ($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB")):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage("DESCRIPTION_TAB");?></div>
				<?if(strlen($arResult["DETAIL_TEXT"])):?>
					<div class="detail_text"><?=$arResult["DETAIL_TEXT"]?></div>
				<?endif;?>
				<?if($arResult["SERVICES"] && $showProps){?>
					<div class="wrap_md descr_div">
				<?}?>
				<?if($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB"):?>
					<?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
						<div class="props_block" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>">
							<?foreach($arResult["PROPERTIES"] as $propCode => $arProp):?>
								<?if(isset($arResult["DISPLAY_PROPERTIES"][$propCode])):?>
									<?$arProp = $arResult["DISPLAY_PROPERTIES"][$propCode];?>
									<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
										<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
											<div class="char" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
												<div class="char_name">
													<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><span itemprop="name"><?=$arProp["NAME"]?></span></span>
												</div>
												<div class="char_value" itemprop="value">
													<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
														<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
													<?else:?>
														<?=$arProp["DISPLAY_VALUE"];?>
													<?endif;?>
												</div>
											</div>
										<?endif;?>
									<?endif;?>
								<?endif;?>
							<?endforeach;?>
						</div>
					<?else:?>
						<div class="iblock char_block <?=(!$arResult["SERVICES"] ? 'wide' : '')?>">
							<h4><?=GetMessage("PROPERTIES_TAB");?></h4>
							<table class="props_list">
								<?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
									<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
										<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
											<tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
												<td class="char_name">
													<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><span itemprop="name"><?=$arProp["NAME"]?></span></span>
												</td>
												<td class="char_value">
													<span itemprop="value">
														<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
															<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
														<?else:?>
															<?=$arProp["DISPLAY_VALUE"];?>
														<?endif;?>
													</span>
												</td>
											</tr>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>
							</table>
							<table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
						</div>
					<?endif;?>
				<?endif;?>
				<?if($arResult["SERVICES"]):?>
					<div class="iblock serv <?=($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE" ? "block_view" : "")?>">
						<h4><?=GetMessage("SERVICES_TITLE")?></h4>
						<div class="services_block">
							<?foreach($arResult["SERVICES"] as $arService):?>
								<span class="item">
									<a href="<?=$arService["DETAIL_PAGE_URL"]?>">
										<i class="arrow"><b></b></i>
										<span class="link"><?=$arService["NAME"]?></span>
										<div class="clearfix"></div>
									</a>
								</span>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
				<?if($arResult["SERVICES"] && $showProps){?>
					</div>
				<?}?>
				<?
				$arFiles = array();
				if($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]){
					$arFiles = $arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"];
				}
				else{
					$arFiles = $arResult["SECTION_FULL"]["UF_FILES"];
				}
				if(is_array($arFiles)){
					foreach($arFiles as $key => $value){
						if(!intval($value)){
							unset($arFiles[$key]);
						}
					}
				}
				?>
				<?if($arFiles):?>
					<div class="files_block">
						<h4><?=GetMessage("DOCUMENTS_TITLE")?></h4>
						<div class="wrap_md">
							<div class="wrapp_docs iblock">
							<?
							$i=1;
							foreach($arFiles as $arItem):?>
								<?$arFile=COptimus::GetFileInfo($arItem);?>
								<div class="file_type clearfix <?=$arFile["TYPE"];?>">
									<i class="icon"></i>
									<div class="description">
										<a target="_blank" href="<?=$arFile["SRC"];?>"><?=$arFile["DESCRIPTION"];?></a>
										<span class="size"><?=GetMessage('CT_NAME_SIZE')?>:
											<?=$arFile["FILE_SIZE_FORMAT"];?>
										</span>
									</div>
								</div>
								<?if($i%3==0){?>
									</div><div class="wrapp_docs iblock">
								<?}?>
								<?$i++;?>
							<?endforeach;?>
							</div>
						</div>
					</div>
				<?endif;?>
			</li>
		<?endif;?>

		<?if($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] == "TAB"):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage("PROPERTIES_TAB");?></div><div>
				<?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
					<div class="props_block" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>">
						<?foreach($arResult["PROPERTIES"] as $propCode => $arProp):?>
							<?if(isset($arResult["PROPERTIES"][$propCode])):?>
								<?$arProp = $arResult["PROPERTIES"][$propCode];?>
								<?if(!in_array($arProp["CODE"], array("PRODUCTINSTOCK","SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
									<?if((!is_array($arProp["VALUE"]) && strlen($arProp["VALUE"])) || (is_array($arProp["VALUE"]) && implode('', $arProp["VALUE"]))):?>
										<div class="char" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
											<div class="char_name">
												<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><span itemprop="name"><?=$arProp["NAME"]?></span></span>
											</div>
											<div class="char_value" itemprop="value">
												<?if(count($arProp["VALUE"]) > 1):?>
													<?=implode(', ', $arProp["VALUE"]);?>
												<?else:?>
													<?=$arProp["VALUE"];?>
												<?endif;?>
											</div>
										</div>
									<?endif;?>
								<?endif;?>
							<?endif;?>
						<?endforeach;?>
					</div>
				<?else:?>
					<table class="props_list">
						<?foreach($arResult["PROPERTIES"] as $arProp):
								if($arProp['PROPERTY_TYPE'] == 'F') continue;
?>
							<?if(!in_array($arProp["CODE"], array("PRODUCTINSTOCK", "SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
								<?if((!is_array($arProp["VALUE"]) && strlen($arProp["VALUE"])) || (is_array($arProp["VALUE"]) && implode('', $arProp["VALUE"]))):?>
									<tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
										<td class="char_name">
											<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><span itemprop="name"><?=$arProp["NAME"]?></span></span>
										</td>
										<td class="char_value">
											<span itemprop="value">
												<?if(is_array($arProp["VALUE"])):?>
													<?=implode(', ', $arProp["VALUE"]);?>
												<?else:?>
													<?=$arProp["VALUE"];?>
												<?endif;?>
											</span>
										</td>
									</tr>
								<?endif;?>
							<?endif;?>
						<?endforeach;?>
					</table>
					<table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
				<?endif;?></div>
			</li>
		<?endif;?>
		<?if($arVideo):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage("VIDEO_TAB");?>
					<?if(count($arVideo) > 1):?>
						<span class="count empty">&nbsp;(<?=count($arVideo)?>)</span>
					<?endif;?>
				</div>
				<div class="video_block">
					<?if(count($arVideo) > 1):?>
						<table class="video_table">
							<tbody>
								<tr>
									<?foreach($arVideo as $v => $value):?>
										<td width="50%"><?=str_replace('src=', 'width="458" height="257" src=', str_replace(array('width', 'height'), array('data-width', 'data-height'), $value));?></td>
										<?if(!(($v + 1) % 2)):?>
											</tr><tr>
										<?endif;?>
									<?endforeach;?>
								</tr>
							</tbody>
						</table>
					<?else:?>
						<?=$arVideo[0]?>
					<?endif;?>
				</div>
			</li>
		<?endif;?>

		<?if($arParams["USE_REVIEW"] == "Y"):?>
			<li class="<?=(!($iTab++) ? '' : '')?>"><div class="title-tab-heading js-reviews-tab aspro-bcolor-0099cc visible-xs"><?=($arParams["TAB_REVIEW_NAME"] ? $arParams["TAB_REVIEW_NAME"] : GetMessage("REVIEW_TAB"))?><span class="count empty"></span></div><div id="for_product_reviews_tab"></div></li>			
		<?endif;?>

		<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage('ASK_TAB')?></div>
				<div class="wrap_md forms">
					<div class="iblock text_block">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/ask_tab_detail_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ASK_DESCRIPTION')));?>
					</div>
					<div class="iblock form_block">
						<div id="ask_block"></div>
					</div>
				</div>
			</li>
		<?endif;?>

		<?if($useStores && ($showCustomOffer || !$arResult["OFFERS"] )):?>
			<li class="stores_tab<?=(!($iTab++) ? ' current' : '')?>">
				<div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage("STORES_TAB");?></div>
				<div>
					<?if($arResult["OFFERS"]){?>
						<span></span>
					<?}else{?>
						<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "main", array(
								"PER_PAGE" => "10",
								"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
								"SCHEDULE" => $arParams["SCHEDULE"],
								"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
								"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
								"ELEMENT_ID" => $arResult["ID"],
								"STORE_PATH"  =>  $arParams["STORE_PATH"],
								"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
								"MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
								"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
								"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
								"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
								"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
								"USER_FIELDS" => $arParams['USER_FIELDS'],
								"FIELDS" => $arParams['FIELDS'],
								"STORES" => $arParams['STORES'],
							),
							$component
						);?>
					<?}?>
				</div>
			</li>
		<?endif;?>

		<?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage("ADDITIONAL_TAB");?></div>
				<div><?$APPLICATION->IncludeFile(SITE_DIR."include/additional_products_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ADDITIONAL_DESCRIPTION')));?></div>
			</li>
		<?endif;?>
	</ul>
</div>

<?
if($arResult['CATALOG'] && $actualItem['CAN_BUY'] && \Bitrix\Main\ModuleManager::isModuleInstalled('sale') && $arParams['USE_DETAIL_PREDICTION'] !== 'N'){
	
	$APPLICATION->IncludeComponent(
		'bitrix:sale.prediction.product.detail',
		'main',
		array(
			'BUTTON_ID' => false,
			'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
			'POTENTIAL_PRODUCT_TO_BUY' => array(
				'ID' => $arResult['ID'],
				'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
				'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
				'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
				'IBLOCK_ID' => $arResult['IBLOCK_ID'],
				'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : null,
				'SECTION' => array(
					'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
					'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
					'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
					'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
				),
			)
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
}
?>
 ?>
<div class="gifts">
<?if ($arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled("sale"))
{
	$APPLICATION->IncludeComponent("bitrix:sale.gift.product", "main", array(
			"SHOW_UNABLE_SKU_PROPS"=>$arParams["SHOW_UNABLE_SKU_PROPS"],
			'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
			'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
			'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'SUBSCRIBE_URL_TEMPLATE' => $arResult['~SUBSCRIBE_URL_TEMPLATE'],
			'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
			"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
			'SHOW_DISCOUNT_TIME_EACH_SKU' => $arParams['SHOW_DISCOUNT_TIME_EACH_SKU'],

			"SHOW_DISCOUNT_PERCENT" => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
			"SHOW_OLD_PRICE" => $arParams['GIFTS_SHOW_OLD_PRICE'],
			"PAGE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
			"LINE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
			"HIDE_BLOCK_TITLE" => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
			"BLOCK_TITLE" => $arParams['GIFTS_DETAIL_BLOCK_TITLE'],
			"TEXT_LABEL_GIFT" => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
			"SHOW_NAME" => $arParams['GIFTS_SHOW_NAME'],
			"SHOW_IMAGE" => $arParams['GIFTS_SHOW_IMAGE'],
			"MESS_BTN_BUY" => $arParams['GIFTS_MESS_BTN_BUY'],

			"SHOW_PRODUCTS_{$arParams['IBLOCK_ID']}" => "Y",
			"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
			"PRODUCT_SUBSCRIPTION" => $arParams["PRODUCT_SUBSCRIPTION"],
			"MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
			"MESS_BTN_SUBSCRIBE" => $arParams["MESS_BTN_SUBSCRIBE"],
			"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
			"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
			"USE_PRODUCT_QUANTITY" => 'N',
			"OFFER_TREE_PROPS_{$arResult['OFFERS_IBLOCK']}" => $arParams['OFFER_TREE_PROPS'],
			"CART_PROPERTIES_{$arResult['OFFERS_IBLOCK']}" => $arParams['OFFERS_CART_PROPERTIES'],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
			"SALE_STIKER" => $arParams["SALE_STIKER"],
			"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
			"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
			"DISPLAY_TYPE" => "block",
			"SHOW_RATING" => $arParams["SHOW_RATING"],
			"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
			"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
			"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
			"TYPE_SKU" => "Y",

			"POTENTIAL_PRODUCT_TO_BUY" => array(
				'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
				'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
				'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
				'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
				'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

				'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : null,
				'SECTION' => array(
					'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
					'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
					'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
					'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
				),
			)
		), $component, array("HIDE_ICONS" => "Y"));
}
if ($arResult['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled("sale"))
{
	$APPLICATION->IncludeComponent(
			"bitrix:sale.gift.main.products",
			"main",
			array(
				"SHOW_UNABLE_SKU_PROPS"=>$arParams["SHOW_UNABLE_SKU_PROPS"],
				"PAGE_ELEMENT_COUNT" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
				"BLOCK_TITLE" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],
				'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],

				"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
				"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
				'SHOW_DISCOUNT_TIME_EACH_SKU' => $arParams['SHOW_DISCOUNT_TIME_EACH_SKU'],

				"AJAX_MODE" => $arParams["AJAX_MODE"],
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],

				"ELEMENT_SORT_FIELD" => 'ID',
				"ELEMENT_SORT_ORDER" => 'DESC',
				//"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
				//"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
				"FILTER_NAME" => 'searchFilter',
				"SECTION_URL" => $arParams["SECTION_URL"],
				"DETAIL_URL" => $arParams["DETAIL_URL"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],

				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],

				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SET_TITLE" => $arParams["SET_TITLE"],
				"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"],
				"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
				"TEMPLATE_THEME" => (isset($arParams["TEMPLATE_THEME"]) ? $arParams["TEMPLATE_THEME"] : ""),

				"ADD_PICT_PROP" => (isset($arParams["ADD_PICT_PROP"]) ? $arParams["ADD_PICT_PROP"] : ""),

				"LABEL_PROP" => (isset($arParams["LABEL_PROP"]) ? $arParams["LABEL_PROP"] : ""),
				"OFFER_ADD_PICT_PROP" => (isset($arParams["OFFER_ADD_PICT_PROP"]) ? $arParams["OFFER_ADD_PICT_PROP"] : ""),
				"OFFER_TREE_PROPS" => (isset($arParams["OFFER_TREE_PROPS"]) ? $arParams["OFFER_TREE_PROPS"] : ""),
				"SHOW_DISCOUNT_PERCENT" => (isset($arParams["SHOW_DISCOUNT_PERCENT"]) ? $arParams["SHOW_DISCOUNT_PERCENT"] : ""),
				"SHOW_OLD_PRICE" => (isset($arParams["SHOW_OLD_PRICE"]) ? $arParams["SHOW_OLD_PRICE"] : ""),
				"MESS_BTN_BUY" => (isset($arParams["MESS_BTN_BUY"]) ? $arParams["MESS_BTN_BUY"] : ""),
				"MESS_BTN_ADD_TO_BASKET" => (isset($arParams["MESS_BTN_ADD_TO_BASKET"]) ? $arParams["MESS_BTN_ADD_TO_BASKET"] : ""),
				"MESS_BTN_DETAIL" => (isset($arParams["MESS_BTN_DETAIL"]) ? $arParams["MESS_BTN_DETAIL"] : ""),
				"MESS_NOT_AVAILABLE" => (isset($arParams["MESS_NOT_AVAILABLE"]) ? $arParams["MESS_NOT_AVAILABLE"] : ""),
				'ADD_TO_BASKET_ACTION' => (isset($arParams["ADD_TO_BASKET_ACTION"]) ? $arParams["ADD_TO_BASKET_ACTION"] : ""),
				'SHOW_CLOSE_POPUP' => (isset($arParams["SHOW_CLOSE_POPUP"]) ? $arParams["SHOW_CLOSE_POPUP"] : ""),
				'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) ? $arParams['DISPLAY_COMPARE'] : ''),
				'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),
				"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
				"SALE_STIKER" => $arParams["SALE_STIKER"],
				"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
				"DISPLAY_TYPE" => "block",
				"SHOW_RATING" => $arParams["SHOW_RATING"],
				"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
				"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
			)
			+ array(
				'OFFER_ID' => empty($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID']) ? $arResult['ID'] : $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'],
				'SECTION_ID' => $arResult['SECTION']['ID'],
				'ELEMENT_ID' => $arResult['ID'],
			),
			$component,
			array("HIDE_ICONS" => "Y")
	);
}
*/ ?>
</div>
<script type="text/javascript">
	BX.message({
		QUANTITY_AVAILIABLE: '<? echo COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID); ?>',
		QUANTITY_NOT_AVAILIABLE: '<? echo COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), SITE_ID); ?>',
		ADD_ERROR_BASKET: '<? echo GetMessage("ADD_ERROR_BASKET"); ?>',
		ADD_ERROR_COMPARE: '<? echo GetMessage("ADD_ERROR_COMPARE"); ?>',
		ONE_CLICK_BUY: '<? echo GetMessage("ONE_CLICK_BUY"); ?>',
		SITE_ID: '<? echo SITE_ID; ?>'
	})
</script>
<style>
    .left_block{
        display: none;
    }
</style>