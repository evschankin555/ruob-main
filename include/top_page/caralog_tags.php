<?php
use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionElementTable;

Loader::includeModule("iblock");

// Получаем ID текущего раздела
$arUrl = explode('/', trim($APPLICATION->GetCurPage(), '/'));
$sectionCode = end($arUrl);
$dbSection = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 14, 'CODE' => $sectionCode), false, array('ID'));
$sectionID = ($arSection = $dbSection->Fetch()) ? $arSection['ID'] : 0;

if ($sectionID > 0) {
    // Получаем список элементов в разделе
    $dbElements = SectionElementTable::getList(array(
        'select' => array('IBLOCK_ELEMENT_ID'),
        'filter' => array('IBLOCK_SECTION_ID' => $sectionID)
    ));

    $elementIDs = array();
    while ($element = $dbElements->fetch()) {
        $elementIDs[] = $element['IBLOCK_ELEMENT_ID'];
    }

    // Получаем теги для этих элементов
    $dbTags = ElementTable::getList(array(
        'select' => array('TAGS'),
        'filter' => array(
            'IBLOCK_ID' => 14,
            'ID' => $elementIDs
        )
    ));

    $allTags = array();
    while ($tags = $dbTags->fetch()) {
        if (!empty($tags['TAGS'])) {
            $allTags = array_merge($allTags, explode(',', $tags['TAGS']));
        }
    }

    // Считаем количество каждого тега
    $tagCounts = array_count_values($allTags);
    arsort($tagCounts);
    $topTags = array_slice(array_keys($tagCounts), 0, 10);

    echo "<div class='receipts'>";
    $currentTag = isset($_GET['tags']) ? urldecode($_GET['tags']) : '';

    foreach ($topTags as $tag) {
        $encodedTag = urlencode($tag);
        // Проверяем, активен ли текущий тег
        $isActive = ($tag === $currentTag) ? " active" : "";
        echo "<a href='" . $APPLICATION->GetCurPageParam("tags=$encodedTag", array("tags")) . "' class='receipts__item$isActive'>" . htmlspecialchars($tag) . "</a>";
    }
    echo "</div>";
}


$arUrl = explode('/', trim($APPLICATION->GetCurPage(), '/'));
$sectionCode = end($arUrl);

$arFilter = array('IBLOCK_ID' => 14, 'CODE' => $sectionCode);
$dbSection = CIBlockSection::GetList(array(), $arFilter, false, array('ID'));

if ($arSection = $dbSection->Fetch()) {
    $CURRENT_SECTION_ID = $arSection['ID'];
}
$arSelect = array("UF_*");
$arFilter = array("IBLOCK_ID" => 14, "ID" => $CURRENT_SECTION_ID);
$res = CIBlockSection::GetList(array(), $arFilter, false, $arSelect);
if ($arSection = $res->GetNext()) {
    $ads = $arSection["UF_ADS"][0];
?>

    <?php
    if (!empty($arSection["UF_ADS"])) {
        // Получение элементов инфоблока по их ID
        $arAdsIds = $arSection["UF_ADS"];
        ?>
        <div class="slider-top">
            <div class="slider-top__wrap js-slider-top">
                <?foreach ($arAdsIds as $adId):
                    $arAdsFilter = array(
                        "IBLOCK_ID" => 29, // ID инфоблока объявлений
                        "ID" => $adId
                    );
                    $arAdsSelect = array("ID", "NAME", "DETAIL_TEXT", "DETAIL_PICTURE");
                    $resAds = CIBlockElement::GetList(array("SORT" => "ASC"), $arAdsFilter, false, false, $arAdsSelect);

                    if ($ob = $resAds->GetNextElement()) {
                        $arFields = $ob->GetFields();
                        $imageSrc = CFile::GetPath($arFields["DETAIL_PICTURE"]);
                        $fullLink = !empty($arFields["DETAIL_TEXT"]) ? $arFields["DETAIL_TEXT"] : '#';
                        // Обрезание домена из URL
                        $parsedUrl = parse_url($fullLink);
                        $link = $parsedUrl['path'] . (!empty($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '');
                        ?>
                        <div>
                            <a href="<?= htmlspecialchars($link) ?>" class="slider-top__link">
                                <img src="<?= htmlspecialchars($imageSrc) ?>" alt="<?= htmlspecialchars($arFields["NAME"]) ?>" loading="lazy">
                            </a>
                        </div>
                    <?}?>
                <?endforeach;?>
            </div>
        </div>


        <?php
    }
}
