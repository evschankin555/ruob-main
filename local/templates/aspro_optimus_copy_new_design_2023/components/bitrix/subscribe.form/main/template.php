<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame = $this->createFrame()->begin();?>
<div class="subscribe-form"  id="subscribe-form">
	<div class="wrap_bg">
		<div class="top_block box-sizing">
			<div class="text">
				<div class="title"><?$APPLICATION->IncludeFile(SITE_DIR."include/subscribe_title.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("TOP_BLOCK"),));?></div>
				<div class="more"><?$APPLICATION->IncludeFile(SITE_DIR."include/subscribe_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("TEXT_BLOCK"),));?></div>
			</div>
		</div>
		<form action="<?=$arResult["FORM_ACTION"];?>">
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
				<label for="sf_RUB_ID_<?=$itemValue["ID"]?>" class="hidden">
					<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?> /> <?=$itemValue["NAME"]?>
				</label>
			<?endforeach;?>
			<div class="subscribe__form">
				<input type="email" class="subscribe__input" name="sf_EMAIL" maxlength="100" required size="20" value="<?=$arResult["EMAIL"]?>" placeholder="<?=GetMessage("subscr_form_email_title")?>" />
                <button type="submit" name="OK" class="send_btn subscribe__submit" value="<?=($arResult["EMAIL"] ? GetMessage("subscr_form_button_change") : GetMessage("subscr_form_button"));?>"><svg><use xlink:href="#arrow-next-long"></use></svg></button>
			</div>
		</form>
	</div>
</div>
<script>
	$(document).ready(function(){
		$("form.sform").validate({
			rules:{ "sf_EMAIL": {email: true} }
		});
	})
</script>
<?$frame->end();?>
