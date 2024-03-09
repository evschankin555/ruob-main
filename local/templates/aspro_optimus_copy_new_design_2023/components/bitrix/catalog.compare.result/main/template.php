<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$isAjax = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["ajax_action"]) && $_POST["ajax_action"] == "Y");?>
<div class="compare__block">
    <div class="scroll" style="
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
">
        <ul class="compare-tabs js-tabs">
            <!-- <li><a class="compare-tabs__btn is-active" href="#tab-1">
                     Wi-Fi роутеры 2 <svg class="compare-tabs__close"><use xlink:href="#close"></use></svg>
                 </a></li>-->

         </ul><div class="toggle-btn">
             <div class="toggle-btn__icon"></div>
             <div class="toggle-btn__text">Только отличия</div>
             <input id="compare-1" type="checkbox" class="toggle-btn__input" checked="checked">
             <label for="compare-1" class="toggle-btn__label"></label>
         </div>
     </div>

     <div class="tabContent js_tabContent" id="for-fixed">
         <div id="tab-1">
             <div class="tabContent__item">
                 <?foreach($arResult["ITEMS"] as &$arElement):
                     ?>
                     <div class="product-item product-item--small">
                         <a class="product-item__remove" onclick="handleRemoveClick('<?=CUtil::JSEscape($arElement['~DELETE_URL'])?>');">
                             <svg><use xlink:href="#basket-2"></use></svg>
                         </a>

                         <script>
                             function handleRemoveClick(deleteUrl) {
                                 // Выполните вашу AJAX-операцию
                                 CatalogCompareObj.MakeAjaxAction(deleteUrl, 'Y', function() {
                                     // После выполнения AJAX-операции, установите задержку в 333 миллисекунды и перезагрузите страницу

                                 });
                                 setTimeout(function() {
                                     location.reload();
                                 }, 333);
                             }
                         </script>

                         <?php
                         $imgSrc = SITE_TEMPLATE_PATH . '/images/no_photo_medium.png'; // Default image path
                         if ($arElement["OFFER_FIELDS"]["PREVIEW_PICTURE"]) {
                             $img = is_array($arElement["OFFER_FIELDS"]["PREVIEW_PICTURE"]) ? $arElement["OFFER_FIELDS"]["PREVIEW_PICTURE"] : CFile::GetFileArray($arElement["OFFER_FIELDS"]["PREVIEW_PICTURE"]);
                             $imgSrc = $img["SRC"];
                         } elseif ($arElement["FIELDS"]["PREVIEW_PICTURE"]) {
                             $img = is_array($arElement["FIELDS"]["PREVIEW_PICTURE"]) ? $arElement["FIELDS"]["PREVIEW_PICTURE"] : false;
                             if ($img) {
                                 $imgSrc = $img["SRC"];
                             }
                         } elseif ($arElement["FIELDS"]["DETAIL_PICTURE"]) {
                             $img = is_array($arElement["FIELDS"]["DETAIL_PICTURE"]) ? $arElement["FIELDS"]["DETAIL_PICTURE"] : false;
                             if ($img) {
                                 $imgSrc = $img["SRC"];
                             }
                         }
                         ?>
                         <img src="<?=$imgSrc?>" class="product-item__img" width="207" height="250" loading="lazy" alt="<?=$arElement["NAME"]?>">
                         <span class="product-item__title"><a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="title"><?=$arElement["NAME"]?></a></span>
                         <span class="product-item__avail"><span>В наличии</span> (<?=$arElement["CATALOG_QUANTITY"]?>)</span>
                         <?php
                         // Преобразование числа
                         $formattedNumber = number_format($arElement['MIN_PRICE']['DISCOUNT_VALUE'], 2, ',', ' ');

                         // Исключение нулевой десятичной части, если она равна 00
                         $formattedNumber = rtrim($formattedNumber, '0');

                         // Если после удаления нулей осталась запятая в конце, то её тоже удаляем
                         $formattedNumber = rtrim($formattedNumber, ',');
                         ?>
                         <span class="product-item__price"><?=$formattedNumber?> ₽</span>
                         <div class="product-item__footer">
                             <a  class="favourite-btn" data-item="<?=$arElement["PROPERTIES"]["PRODUCTINSTOCK"]["ID"]?>"
                                 data-iblock="<?=$arParams["IBLOCK_ID"]?>"><svg class="favourite-btn__svg">
                                <use xlink:href="#favourite"></use></svg>
                             </a>
                             <a  class="blueBtn one_click" data-item="<?=$arElement["PROPERTIES"]["PRODUCTINSTOCK"]["ID"]?>" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="1" onclick="oneClickBuy('<?=$arElement["PROPERTIES"]["PRODUCTINSTOCK"]["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">Купить</a>
                         </div>
                     </div>
                 <?endforeach;?>
             </div>
             <!-- END tabContent__item -->

                     <?$arUnvisible = array("NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE");?>

                     <?//make conditions array?>
                     <?$arShowFileds = $arShowOfferFileds = $arShowProps = $arShowOfferProps = array();?>
                     <?if($arResult["SHOW_FIELDS"])
                     {
                         foreach ($arResult["SHOW_FIELDS"] as $code => $arProp)
                         {
                             if(!in_array($code, $arUnvisible))
                             {

                                 $showRow = true;
                                 if(!isset($arResult['FIELDS_REQUIRED'][$code]) || $arResult['DIFFERENT'])
                                 {
                                     $arCompare = array();
                                     foreach($arResult["ITEMS"] as &$arElement)
                                     {
                                         $arPropertyValue = $arElement["FIELDS"][$code];
                                         if(is_array($arPropertyValue))
                                         {
                                             sort($arPropertyValue);
                                             $arPropertyValue = implode(" / ", $arPropertyValue);
                                         }
                                         $arCompare[] = $arPropertyValue;
                                     }
                                     unset($arElement);
                                     $showRow = (count(array_unique($arCompare)) > 1);
                                 }
                                 if($showRow)
                                     $arShowFileds[$code] = $arProp;
                             }
                         }
                     }

                     if($arResult["SHOW_OFFER_FIELDS"])
                     {
                         foreach ($arResult["SHOW_OFFER_FIELDS"] as $code => $arProp)
                         {
                             $showRow = true;
                             if ($arResult['DIFFERENT'])
                             {
                                 $arCompare = array();
                                 foreach($arResult["ITEMS"] as &$arElement)
                                 {
                                     $Value = $arElement["OFFER_FIELDS"][$code];
                                     if(is_array($Value))
                                     {
                                         sort($Value);
                                         $Value = implode(" / ", $Value);
                                     }
                                     $arCompare[] = $Value;
                                 }
                                 unset($arElement);
                                 $showRow = (count(array_unique($arCompare)) > 1);
                             }
                             if ($showRow)
                                 $arShowOfferFileds[$code] = $arProp;
                         }
                     }
                     if($arResult["SHOW_PROPERTIES"])
                     {
                         foreach($arResult["SHOW_PROPERTIES"] as $code => $arProperty)
                         {
                             $showRow = true;
                             if($arResult['DIFFERENT'])
                             {
                                 $arCompare = array();
                                 foreach($arResult["ITEMS"] as &$arElement)
                                 {
                                     $arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
                                     if(is_array($arPropertyValue))
                                     {
                                         sort($arPropertyValue);
                                         $arPropertyValue = implode(" / ", $arPropertyValue);
                                     }
                                     $arCompare[] = $arPropertyValue;
                                 }
                                 unset($arElement);
                                 $showRow = (count(array_unique($arCompare)) > 1);
                             }
                             if($showRow)
                                 $arShowProps[$code] = $arProperty;
                         }
                     }
                     if($arResult["SHOW_OFFER_PROPERTIES"])
                     {
                         foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty)
                         {
                             $showRow = true;
                             if($arResult['DIFFERENT'])
                             {
                                 $arCompare = array();
                                 foreach($arResult["ITEMS"] as &$arElement)
                                 {
                                     $arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
                                     if(is_array($arPropertyValue))
                                     {
                                         sort($arPropertyValue);
                                         $arPropertyValue = implode(" / ", $arPropertyValue);
                                     }
                                     $arCompare[] = $arPropertyValue;
                                 }
                                 unset($arElement);
                                 $showRow = (count(array_unique($arCompare)) > 1);
                             }
                             if($showRow)
                                 $arShowOfferProps[$code] = $arProperty;
                         }
                     }
                     ?>
                     <?php
                     // Loop through the fields
                     foreach ($arShowFileds as $code => $arProp) {
                         ?>


                     <?php } ?>

                     <?php
                     // Repeat the above structure for $arShowOfferFileds, $arShowProps, and $arShowOfferProps
                     // ...

                     ?>


             <div class="compare-table">
                 <h2 class="h2 compare-table__title">Сравнение характеристик</h2>
                 <div class="accordeon">
                     <div class="accordeon__item">
                         <div class="accordeon__answear">
                             <?php
                             if ($arShowProps) :
                                 ?>
                                 <?php foreach ($arShowProps as $code => $arProperty) : ?>
                                 <div class="accordeon__answear-item__name"><?= $arProperty['NAME'] ?> </div>
                                 <div class="accordeon__answear-grid">
                                     <?php foreach ($arResult["ITEMS"] as &$arElement) : ?>
                                         <div class="accordeon__answear-item">
                                             <div class="accordeon__answear-item__descript">
                                                 <?=
                                                 // Check if the property exists for the current element
                                                 isset($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) ?
                                                     (is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) ?
                                                         implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) :
                                                         $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) :
                                                     '-';
                                                 ?>
                                             </div>
                                         </div>
                                     <?php endforeach; ?>
                                 </div>
                                 <!-- /.accordeon__answear-grid -->
                             <?php endforeach; ?>

                             <?php
                             endif;
                             ?>
                         </div>
                         <!-- /.accordeon__answear -->
                     </div>
                     <!-- END accordeon__item -->

                 </div>
                 <!-- /.accordeon -->
             </div>



        </div>

    </div>
    <!-- /.tabContent -->
</div>
<!-- /.compare__block -->
<style>
    .tabContent__item .product-item{
        min-height: 450px;
        padding: 10px 0;
        justify-content: space-around;
    }
</style>
<?php /*<div class="bx_compare" id="bx_catalog_compare_block">
<?if ($isAjax){
	$APPLICATION->RestartBuffer();
}?>
<div class="bx_sort_container">
	<ul class="tabs-head">
		<li <?=(!$arResult["DIFFERENT"] ? 'class="current"' : '');?>>
			<span class="sortbutton<? echo (!$arResult["DIFFERENT"] ? ' current' : ''); ?>" data-href="?DIFFERENT=N" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></span>
		</li>
		<li <?=($arResult["DIFFERENT"] ? 'class="current"' : '');?>>
			<span class="sortbutton diff <? echo ($arResult["DIFFERENT"] ? ' current' : ''); ?>" data-href="?DIFFERENT=Y" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></span>
		</li>
	</ul>
	<span class="wrap_remove_button">
		<?$arStr=$arCompareIDs=array();
		if($arResult["ITEMS"]){
			foreach($arResult["ITEMS"] as $arItem){
				$arCompareIDs[]=$arItem["ID"];
			}
		}
		$arStr=implode("&ID[]=", $arCompareIDs)?>
		<span class="button grey_br transparent remove_all_compare icon_close" onclick="CatalogCompareObj.MakeAjaxAction('/catalog/compare.php?action=DELETE_FROM_COMPARE_RESULT&ID[]=<?=$arStr?>', 'Y');"><?=GetMessage("CLEAR_ALL_COMPARE")?></span>
	</span>
</div>

<div class="table_compare wrap_sliders tabs-body">
	<?if (!empty($arResult["SHOW_FIELDS"])){?>
		<div class="frame top">
			<div class="wraps">
				<table class="compare_view top">
					<tr>
						<?foreach($arResult["ITEMS"] as &$arElement){?>
							<td>
								<div class="item_block">
									<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arElement['~DELETE_URL'])?>', 'Y');" class="remove" title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>"><i></i></span>
									<?$name = (isset($arElement["OFFER_FIELDS"]["NAME"]) ? $arElement["OFFER_FIELDS"]["NAME"] : $arElement["NAME"]);?>
									<?if($arParams['SKU_DETAIL_ID'] && isset($arElement["OFFER_FIELDS"]["ID"]))
										$arElement["DETAIL_PAGE_URL"] .= '?oid='.$arElement["OFFER_FIELDS"]["ID"];?>
									<div class="image_wrapper_block">
										<?if($arElement["OFFER_FIELDS"]["PREVIEW_PICTURE"]){
											if(is_array($arElement["OFFER_FIELDS"]["PREVIEW_PICTURE"]))
												$img = $arElement["OFFER_FIELDS"]["PREVIEW_PICTURE"];
											else
												$img = CFile::GetFileArray($arElement["OFFER_FIELDS"]["PREVIEW_PICTURE"]);?>
											<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img src="<?=$img["SRC"]?>" alt="<?=$img["ALT"]?>" title="<?=$img["TITLE"]?>" /></a>
										<?}elseif($arElement["FIELDS"]["PREVIEW_PICTURE"]){
											if(is_array($arElement["FIELDS"]["PREVIEW_PICTURE"])):?>
												<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img src="<?=$arElement["FIELDS"]["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arElement["FIELDS"]["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arElement["FIELDS"]["PREVIEW_PICTURE"]["TITLE"]?>" /></a>
											<?endif;?>
										<?}elseif($arElement["FIELDS"]["DETAIL_PICTURE"]){
											if(is_array($arElement["FIELDS"]["DETAIL_PICTURE"])):?>
												<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img src="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["ALT"]?>" title="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["TITLE"]?>" /></a>
											<?endif;
										}else{?>
												<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arElement["NAME"]?>" title="<?=$arElement["NAME"]?>" /></a>
										<?}?>
									</div>
									<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="title"><?=$name;?></a>
									<div class="cost prices clearfix">
										<?
										$frame = $this->createFrame()->begin('');
										$frame->setBrowserStorage(true);
										?>
										<?if (isset($arElement['MIN_PRICE']) && is_array($arElement['MIN_PRICE'])):?>
											<div class="price"><?=(isset($arElement['MIN_PRICE']['SUFFIX']) && $arElement['MIN_PRICE']['SUFFIX'] ? $arElement['MIN_PRICE']['SUFFIX'] : '')?><?=$arElement['MIN_PRICE']['PRINT_DISCOUNT_VALUE'];?></div>
										<?elseif(!empty($arElement['PRICE_MATRIX']) && is_array($arElement['PRICE_MATRIX'])):?>
											<?
											$matrix = $arElement['PRICE_MATRIX'];
											$rows = $matrix['ROWS'];
											$rowsCount = count($rows);
											if($rowsCount > 0):?>
												<div class="price_matrix_block">
													<div class="price_matrix_wrapper">
														<?if (count($rows) > 1):?>
															<?foreach ($rows as $index => $rowData):?>
																<?if (empty($matrix['MIN_PRICES'][$index]))
																	continue;?>
																<div class="price_wrapper_block">
																	<?if($rowData['QUANTITY_FROM'] == 0)
																		$rowData['QUANTITY_FROM'] = '';
																	if($rowData['QUANTITY_TO'] == 0)
																		$rowData['QUANTITY_TO'] = '';
																	?>
																	<div class="price_interval">
																		<?
																		$quantity_from = $rowData['QUANTITY_FROM'];
																		$quantity_to = $rowData['QUANTITY_TO'];
																		$text = ($quantity_to ? ($quantity_from ? $quantity_from.'-'.$quantity_to : '<'.$quantity_to ) : '>'.$quantity_from );
																		?>
																		<?=$text;?><?if($arParams['SHOW_MEASURE'] == 'Y'):?><?=GetMessage('MEASURE_UNIT');?><?endif;?>
																	</div>
																	<div class="price">
																		<span class="values_wrapper">
																			<?
																			$val = '';
																			$format_value = \CCurrencyLang::CurrencyFormat($matrix['MIN_PRICES'][$index]['PRICE'], $matrix['MIN_PRICES'][$index]['CURRENCY']);
																			echo $format_value;
																			?>
																		</span>
																	</div>
																</div>
															<?endforeach;?>
															<?unset($index, $rowData);?>
														<?else:?>
															<?$currentPrice = current($matrix['MIN_PRICES']);
															echo '<div class="price">'.\CCurrencyLang::CurrencyFormat($currentPrice['PRICE'], $currentPrice['CURRENCY']).'</div>';
															unset($currentPrice);?>
														<?endif;?>
													</div>
												</div>
											<?endif;?>
											<?unset($rowsCount, $rows, $matrix);?>
										<?endif;?>
										<?$frame->end();?>
									</div>
								</div>
							</td>
						<?}?>
					</tr>
				</table>
			</div>
		</div>
		<div class="wrapp_scrollbar">
			<div class="wr_scrollbar">
				<div class="scrollbar">
					<div class="handle">
						<div class="mousearea"></div>
					</div>
				</div>
			</div>
			<ul class="slider_navigation compare custom_flex">
				<ul class="flex-direction-nav">
					<li class="flex-nav-prev backward"><span class="flex-prev">Previous</span></li>
					<li class="flex-nav-next forward"><span class="flex-next">Next</span></li>
				</ul>
			</ul>
		</div>
	<?}?>
	<?if (!empty($arResult["ALL_FIELDS"]) || !empty($arResult["ALL_PROPERTIES"]) || !empty($arResult["ALL_OFFER_FIELDS"]) || !empty($arResult["ALL_OFFER_PROPERTIES"])){?>
		<div class="bx_filtren_container">
			<ul>
				<?if(!empty($arResult["ALL_FIELDS"])){
					foreach ($arResult["ALL_FIELDS"] as $propCode => $arProp){
						if (!isset($arResult['FIELDS_REQUIRED'][$propCode])){?>
							<li class="button vsmall transparent <?=($arProp["IS_DELETED"] != "N" ? 'visible' : '');?>">
								<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">+<?=GetMessage("IBLOCK_FIELD_".$propCode)?></span>
							</li>
						<?}
					}
				}
				if(!empty($arResult["ALL_OFFER_FIELDS"])){
					foreach($arResult["ALL_OFFER_FIELDS"] as $propCode => $arProp){?>
						<li class="button vsmall transparent <?=($arProp["IS_DELETED"] != "N" ? 'visible' : '');?>">
							<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">+<?=GetMessage("IBLOCK_FIELD_".$propCode)?></span>
						</li>
					<?}
				}
				if (!empty($arResult["ALL_PROPERTIES"])){
					foreach($arResult["ALL_PROPERTIES"] as $propCode => $arProp){?>
						<li class="button vsmall transparent <?=($arProp["IS_DELETED"] != "N" ? 'visible' : '');?>">
							<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">+<?=$arProp["NAME"]?></span>
						</li>
					<?}
				}
				if (!empty($arResult["ALL_OFFER_PROPERTIES"])){
					foreach($arResult["ALL_OFFER_PROPERTIES"] as $propCode => $arProp){?>
						<li class="button vsmall transparent <?=($arProp["IS_DELETED"] != "N" ? 'visible' : '');?>">
							<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">+<?=$arProp["NAME"]?></span>
						</li>
					<?}
				}?>
			</ul>
		</div>
	<?}?>
	<?$arUnvisible = array("NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE");?>
	<div class="table_props_with_title">
		<div class="prop_title_table"></div>

		<?//make conditions array?>
		<?$arShowFileds = $arShowOfferFileds = $arShowProps = $arShowOfferProps = array();?>
		<?if($arResult["SHOW_FIELDS"])
		{
			foreach ($arResult["SHOW_FIELDS"] as $code => $arProp)
			{
				if(!in_array($code, $arUnvisible))
				{
					$showRow = true;
					if(!isset($arResult['FIELDS_REQUIRED'][$code]) || $arResult['DIFFERENT'])
					{
						$arCompare = array();
						foreach($arResult["ITEMS"] as &$arElement)
						{
							$arPropertyValue = $arElement["FIELDS"][$code];
							if(is_array($arPropertyValue))
							{
								sort($arPropertyValue);
								$arPropertyValue = implode(" / ", $arPropertyValue);
							}
							$arCompare[] = $arPropertyValue;
						}
						unset($arElement);
						$showRow = (count(array_unique($arCompare)) > 1);
					}
					if($showRow)
						$arShowFileds[$code] = $arProp;
				}
			}
		}
		if($arResult["SHOW_OFFER_FIELDS"])
		{
			foreach ($arResult["SHOW_OFFER_FIELDS"] as $code => $arProp)
			{
				$showRow = true;
				if ($arResult['DIFFERENT'])
				{
					$arCompare = array();
					foreach($arResult["ITEMS"] as &$arElement)
					{
						$Value = $arElement["OFFER_FIELDS"][$code];
						if(is_array($Value))
						{
							sort($Value);
							$Value = implode(" / ", $Value);
						}
						$arCompare[] = $Value;
					}
					unset($arElement);
					$showRow = (count(array_unique($arCompare)) > 1);
				}
				if ($showRow)
					$arShowOfferFileds[$code] = $arProp;
			}
		}
		if($arResult["SHOW_PROPERTIES"])
		{
			foreach($arResult["SHOW_PROPERTIES"] as $code => $arProperty)
			{
				$showRow = true;
				if($arResult['DIFFERENT'])
				{
					$arCompare = array();
					foreach($arResult["ITEMS"] as &$arElement)
					{
						$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
						if(is_array($arPropertyValue))
						{
							sort($arPropertyValue);
							$arPropertyValue = implode(" / ", $arPropertyValue);
						}
						$arCompare[] = $arPropertyValue;
					}
					unset($arElement);
					$showRow = (count(array_unique($arCompare)) > 1);
				}
				if($showRow)
					$arShowProps[$code] = $arProperty;
			}
		}
		if($arResult["SHOW_OFFER_PROPERTIES"])
		{
			foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty)
			{
				$showRow = true;
				if($arResult['DIFFERENT'])
				{
					$arCompare = array();
					foreach($arResult["ITEMS"] as &$arElement)
					{
						$arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
						if(is_array($arPropertyValue))
						{
							sort($arPropertyValue);
							$arPropertyValue = implode(" / ", $arPropertyValue);
						}
						$arCompare[] = $arPropertyValue;
					}
					unset($arElement);
					$showRow = (count(array_unique($arCompare)) > 1);
				}
				if($showRow)
					$arShowOfferProps[$code] = $arProperty;
			}
		}
		?>

		<?if($arShowFileds || $arShowOfferFileds || $arShowProps || $arShowOfferProps):?>
			<div class="frame props">
				<div class="wraps">
					<table class="data_table_props compare_view">
						<?if($arShowFileds)
						{
							foreach($arShowFileds as $code => $arProp){?>
								<tr>
									<td>
										<?=GetMessage("IBLOCK_FIELD_".$code);?>
										<?if($arResult["ALL_FIELDS"][$code]){?>
											<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arResult["ALL_FIELDS"][$code]["ACTION_LINK"])?>')" class="remove"><i></i></span>
										<?}?>
									</td>
									<?foreach($arResult["ITEMS"] as $arElement){?>
										<td valign="top">
											<?=$arElement["FIELDS"][$code];?>

										</td>
									<?}
									unset($arElement);?>
								</tr>
							<?}?>
						<?}
						if($arShowOfferFileds){
							foreach ($arShowOfferFileds as $code => $arProp){?>
								<tr>
									<td>
										<?=GetMessage("IBLOCK_OFFER_FIELD_".$code)?>
										<?if($arResult["ALL_OFFER_FIELDS"][$code]){?>
											<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arResult["ALL_OFFER_FIELDS"][$code]["ACTION_LINK"])?>')" class="remove" title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>"><i></i></span>
										<?}?>
									</td>
									<?foreach($arResult["ITEMS"] as &$arElement){?>
										<td>
											<?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
										</td>
									<?}
									unset($arElement);
									?>
								</tr>
							<?}
						}?>
						<?
						if($arShowProps){
							foreach ($arShowProps as $code => $arProperty){?>
								<tr>
									<td>
									<?=$arProperty["NAME"]?>
									<?if($arResult["ALL_PROPERTIES"][$code]){?>
										<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arResult["ALL_PROPERTIES"][$code]["ACTION_LINK"])?>')" class="remove" title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>"><i></i></span>
									<?}?>
									</td>
									<?foreach($arResult["ITEMS"] as &$arElement){?>
										<td>
											<?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
										</td>
									<?}
									unset($arElement);
									?>
								</tr>
							<?}
						}?>
						<?if($arShowOfferProps){
							foreach($arShowOfferProps as $code=>$arProperty){?>
								<tr>
									<td>
										<?=$arProperty["NAME"]?>
										<?if($arResult["ALL_OFFER_PROPERTIES"][$code]){?>
											<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arResult["ALL_OFFER_PROPERTIES"][$code]["ACTION_LINK"])?>')" class="remove" title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>"><i></i></span>
										<?}?>
									</td>
									<?foreach($arResult["ITEMS"] as &$arElement){?>
										<td>
											<?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
										</td>
									<?}
									unset($arElement);
									?>
								</tr>
							<?}
						}?>
					</table>
				</div>
			</div>
		<?endif;?>
	</div>
</div> */?>
<script type="text/javascript">
	$(document).ready(function(){
		$(window).on('resize', function(){
			initSly();
			createTableCompare($('.data_table_props:not(.clone)'), $('.prop_title_table'), $('.data_table_props.clone'));
		});
		// createTableCompare($('.data_table_props'), $('.prop_title_table'), $('.data_table_props.clone'));
		$(window).resize();
		$('.wraps .item_block .title').sliceHeight({'row': '.compare_view', 'item': '.item_block'});
	})
</script>
<?if ($isAjax){
	die();
}?>
</div>
<script type="text/javascript">
	var CatalogCompareObj = new BX.Iblock.Catalog.CompareClass("bx_catalog_compare_block");
</script>