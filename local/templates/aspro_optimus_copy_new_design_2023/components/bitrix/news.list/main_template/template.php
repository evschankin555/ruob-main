<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php $this->setFrameMode(true); ?>

<?php if (count($arResult["ITEMS"])): ?>
    <div class="club__wrap-grid">
        <?php foreach ($arResult["ITEMS"] as $arItem):
            ?>
            <div class="club__wrap-grid__item">
                <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="club__wrap-link">
                    <?php
                    $img = CFile::ResizeImageGet(
                        $arItem["PREVIEW_PICTURE"],
                        array("width" => 356, "height" => 240),
                        BX_RESIZE_IMAGE_EXACT,
                        true
                    );
                    ?>
                    <img src="<?= $img["src"] ?>" width="<?= $img["width"] ?>" height="<?= $img["height"] ?>" loading="lazy" class="club__wrap-img" alt="">
                </a>
                <h6 class="h6 club__wrap-title"><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a></h6>
                <div class="club__wrap-descript">
                    <?= $arItem["PREVIEW_TEXT"] ?>
                </div>
                <?php if (!empty($arItem["IBLOCK_SECTION_ID"])): ?>
                    <?php
                    $arSection = \CIBlockSection::GetByID($arItem["IBLOCK_SECTION_ID"])->Fetch();
                    if ($arSection): ?>
                        <span class="club__wrap-label"><?= $arSection["NAME"] ?></span>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="stat">
                    <div class="stat__date"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></div>
                    <div class="stat__view"><svg><use xlink:href="#eye"></use></svg>0</div>
                    <div class="stat__comments"><svg><use xlink:href="#comment"></use></svg>0</div>
                </div>
            </div>
            <!-- /.club__wrap-grid__item -->
        <?php endforeach; ?>
    </div>
    <?php if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?><?= $arResult["NAV_STRING"] ?><?php endif; ?>
<?php endif; ?>
