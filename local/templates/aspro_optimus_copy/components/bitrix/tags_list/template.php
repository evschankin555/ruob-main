<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="receipts">
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $tags = $arItem["PROPERTIES"]["UF_TAGSLIST6"]["VALUE"];
        foreach($tags as $tag):
        ?>
            <a href="#" class="receipts__item"><?= $tag ?></a>
        <?endforeach;?>
    <?endforeach;?>
</div>
