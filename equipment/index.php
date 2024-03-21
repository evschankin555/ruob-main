<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Каталог пищевого и промышленного оборудование от компании RuOborudovanie.ru. Оборудование для пищевых производств и сегмента HoReCa.");
$APPLICATION->SetPageProperty("title", "Каталог профессионального оборудования | RuOborudovanie.ru");


use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionElementTable;

$request = Context::getCurrent()->getRequest();
$url = $request->getRequestUri();

// Обрезаем параметры запроса
$urlParts = explode('?', $url);
$path = $urlParts[0]; // Путь без параметров

// Разбираем URL, чтобы получить путь
$pathParts = parse_url($path);
$sectionCode = basename($pathParts['path']); // Получаем последний сегмент пути

// Проверка, что последняя часть не пустая
if (!empty($sectionCode)) {
    Loader::includeModule("iblock");

    // Фильтр по инфоблоку и свойствам
    $arFilter = array(
        'IBLOCK_ID' => 30,
        'CODE' => $sectionCode,
    );
    // Получение элементов инфоблока
    $res = CIBlockElement::GetList(
        array(),
        $arFilter,
        false,
        false,
        array('ID', 'NAME', 'PROPERTY_*') // Получаем все свойства элемента
    );
    // Обработка результатов запроса
    if ($item = $res->Fetch()) {
        $APPLICATION->SetTitle($item['NAME']);
        // ID элемента инфоблока с нужным вам элементом UF_EQUIPMENT
        $elementId = $item['ID'];
        $targetEquipmentValue = $item['ID'];

// Получение всех разделов, где значение поля UF_EQUIPMENT равно $targetEquipmentValue
        $dbSections = CIBlockSection::GetList(
            array(),
            array('IBLOCK_ID' => 14, 'UF_EQUIPMENT' => $targetEquipmentValue),
            false,
            array('ID', 'NAME', 'PICTURE', 'CODE') // Выбираем ID, название раздела и основную картинку
        );

// Обработка результатов запроса
        while ($section = $dbSections->Fetch()) {
            // Получаем путь к основной картинке раздела
            $pictureSrc = '';
            if (!empty($section['PICTURE'])) {
                $arSectionPicture = CFile::GetFileArray($section['PICTURE']);
                if ($arSectionPicture) {
                    $pictureSrc = $arSectionPicture['SRC'];
                }
            }

            // Получаем количество товаров в категории
            $dbItems = CIBlockElement::GetList(
                array(),
                array('IBLOCK_ID' => 14, 'SECTION_ID' => $section['ID'], '>CATALOG_PRICE_1' => 0, '>CATALOG_QUANTITY' => 0),
                false,
                false,
                array('ID')
            );
            $itemCount = $dbItems->SelectedRowsCount();

            // Сохраняем данные о разделе в массив
            $sectionsData[] = array(
                'ID' => $section['ID'],
                'NAME' => $section['NAME'],
                'PICTURE_SRC' => $pictureSrc,
                'ITEM_COUNT' => $itemCount // Количество товаров в категории
            );
        }

        // Получение всех родительских разделов для каждой категории
        foreach ($sectionsData as &$sectionData) {
            $sectionParents = [];
            $parentSectionId = $sectionData['ID'];

            // Получаем родительские разделы для текущей категории
            while ($parentSectionId) {
                $arParentSection = CIBlockSection::GetByID($parentSectionId)->GetNext();
                if ($arParentSection) {
                    // Добавляем в начало массива данных о родительской категории
                    array_unshift($sectionParents, $arParentSection);
                    $parentSectionId = $arParentSection['IBLOCK_SECTION_ID'];
                } else {
                    $parentSectionId = null;
                }
            }

            // Собираем URL из кодов родительских категорий
            $urlParts = ['/catalog']; // Начало URL
            foreach ($sectionParents as $parentSection) {
                $urlParts[] = $parentSection['CODE'];
            }

            // Добавляем код текущей категории и формируем полный URL
            $urlParts[] = $sectionData['CODE'];
            $sectionData['URL'] = implode('/', $urlParts);
        }


    } else {
        echo "Элемент не найден";
    }
} else {
    echo "Неверный URL";
}
?>

    <div class="list-page">
        <div class="cat-grid">
            <?php foreach ($sectionsData as $section): ?>
                <a href="<?=$section['URL']?>" class="cat-grid__link">
            <span class="cat-grid__wrap">
                <img src="<?php echo $section['PICTURE_SRC']; ?>" class="cat-grid__img" alt="">
            </span>
                    <span class="cat-grid__title">
                <?php echo $section['NAME']; ?>
                        <sup class="cat-grid__count"><?=$section['ITEM_COUNT']?></sup>
            </span>
                </a>
            <?php endforeach; ?>
        </div>
        <!-- /.cat-grid -->
        <hr class="separator">
        <?php
        /*<div class="sort">
            <div class="sort__label">
                Сортировка:
                <select class="myselect">
                    <option value="">По наличию</option>
                    <option value="">Сначала недорогие</option>
                    <option value="">Сначала дорогие</option>
                    <option value="">Сначала популярные</option>
                </select>
            </div>
            <div class="sort__view">
                <a href="" class="filter-btn sort__filter-btn"><svg><use xlink:href="#filter"></use></svg>Фильтры</a>
                <a href="" class="sort__view-link"><svg class="sort__view-gridfour"><use xlink:href="#grid-four"></use></svg></a>
                <a href="" class="sort__view-link"><svg class="sort__view-gridrow"><use xlink:href="#view-row"></use></svg></a>
            </div>

        </div>*/
        // Получение всех разделов, где значение поля UF_EQUIPMENT равно $targetEquipmentValue
        $dbSections = CIBlockSection::GetList(
            array(),
            array('IBLOCK_ID' => 14, 'UF_EQUIPMENT' => $targetEquipmentValue),
            false,
            array('ID', 'NAME', 'PICTURE') // Выбираем ID, название раздела и основную картинку
        );

        // Обработка результатов запроса
        $sectionIds = array(); // Создаем пустой массив для хранения ID разделов
        while ($section = $dbSections->Fetch()) {
            // Добавляем ID текущего раздела в массив $sectionIds
            $sectionIds[] = $section['ID'];
        }

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Получаем номер текущей страницы из URL, если он не задан, то используем 1
        $itemsPerPage = 20; // Количество товаров на странице
        $offset = ($page - 1) * $itemsPerPage; // Вычисляем смещение для выборки

        // Получение товаров из разделов, учитывая смещение и количество товаров на странице
        $dbItems = CIBlockElement::GetList(
            array('CATALOG_QUANTITY' => 'DESC', 'CATALOG_PRICE_1' => 'ASC'),
            array(
                'IBLOCK_ID' => 14,
                'SECTION_ID' => $sectionIds,
                '>PRICE' => 0,
                '>QUANTITY' => 0
            ),
            false,
            array('nPageSize' => $itemsPerPage, 'iNumPage' => $page),
            array('ID', 'NAME', 'DETAIL_PICTURE', '*')
        );


        $itemsData = array(); // Массив для хранения данных о товарах

// Обработка результатов запроса
            while ($item = $dbItems->Fetch()) {
                // Получаем пути к картинкам товара
                $pictureSrc1 = '';
                $pictureSrc2 = '';
                if (!empty($item['DETAIL_PICTURE'])) {
                    $arItemPictures = [];
                    $arItemPictures[] = CFile::GetFileArray($item['DETAIL_PICTURE']);
                    if (!empty($item['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
                        foreach ($item['PROPERTIES']['MORE_PHOTO']['VALUE'] as $morePhotoId) {
                            $arItemPictures[] = CFile::GetFileArray($morePhotoId);
                        }
                    }

                    if (count($arItemPictures) >= 2) {
                        $pictureSrc1 = $arItemPictures[0]['SRC'];
                        $pictureSrc2 = $arItemPictures[1]['SRC'];
                    } elseif (count($arItemPictures) === 1) {
                        $pictureSrc1 = $arItemPictures[0]['SRC'];
                        $pictureSrc2 = $arItemPictures[0]['SRC'];
                    }
                }

                // Сохраняем данные о товаре в массив
                $itemsData[] = array(
                    'ID' => $item['ID'],
                    'NAME' => $item['NAME'],
                    'PICTURE_SRC1' => $pictureSrc1,
                    'PICTURE_SRC2' => $pictureSrc2,
                    'DESCRIPTION' => $item['DETAIL_TEXT'],
                    'COUNTRY' => $item['PROPERTY_COUNTRY_VALUE'],
                    'MATERIAL' => $item['PROPERTY_MATERIAL_VALUE'],
                    'DIMENSIONS' => $item['PROPERTY_DIMENSIONS_VALUE'],
                    'PRICE' => $item['CATALOG_PRICE_1'],
                    'IBLOCK_SECTION_ID' => $item['IBLOCK_SECTION_ID'],
                    'QUANTITY' => $item['CATALOG_QUANTITY']
                );
            }
        // Получение общего количества элементов
        $dbItemCount = CIBlockElement::GetList(
            array(),
            array('IBLOCK_ID' => 14, 'SECTION_ID' => $sectionIds, '>PRICE' => 0, '>QUANTITY' => 0),
            false,
            false,
            array('ID')
        );
        $totalItemCount = $dbItemCount->SelectedRowsCount(); // Общее количество товаров

        // Рассчитываем количество страниц
        $itemsPerPage = 20; // Заданный лимит товаров на страницу
        $maxPage = ceil($totalItemCount / $itemsPerPage); // Определяем общее количество страниц
ini_set('display_errors', 1);

        function get_product_url_my($item) {
            $sectionId = $item['IBLOCK_SECTION_ID'];
            $sectionCodes = array();
            $parentSections = array();
            $sectionLevel = 0;

            while ($sectionId > 0) {
                $section = CIBlockSection::GetByID($sectionId, array('ID', 'CODE', 'IBLOCK_SECTION_ID'));

                if ($sectionItem = $section->Fetch()) {
                    // Check for empty code and handle as needed
                    if ($sectionItem['CODE']) {
                        $sectionCodes[] = $sectionItem['CODE'];
                    } else {
                        // Choose one of the following options for handling empty codes:
                        // 1. Replace with a fixed value:
                        //   $sectionCodes[] = 'other';
                        // 2. Exclude from the URL (no action needed in this case)
                    }

                    $parentSections[] = $sectionItem;

                    $sectionId = $sectionItem['IBLOCK_SECTION_ID'];
                    $sectionLevel++;
                }
            }

            // Reverse the section codes for correct URL order
            $sectionCodes = array_reverse($sectionCodes);

            // Build the URL
            $url = '/catalog/' . implode('/', $sectionCodes) . '/' . $item['ID'] . '/';
            return $url;
        }



// Выводим товары текущего раздела
            if (!empty($itemsData)) {
                echo '<div class="card-grid">';
                foreach ($itemsData as $item) {
                    ?>
                    <div class="card-grid__item">
                        <div class="mycard">
                            <div class="mycard__inner">
                                <div class="mycard__top">
                                    <div class="mycard__btns ">
                                        <div class="wish_item_button">
									<span class="wish_item to" data-item="<?php echo $item['ID']; ?>" data-iblock="14">
                                        <svg class="heart-btn"><use xlink:href="#heart"></use></svg>
                                    </span>
                                            <span class="wish_item in added" style="display: none;" data-item="<?php echo $item['ID']; ?>"
                                                  data-iblock="14">
                                        <svg class="heart-btn"><use xlink:href="#heart"></use></svg>
                                    </span>
                                        </div>

                                        <div class="compare_item_button">
									<span class="compare_item to" data-iblock="14" data-item="<?php echo $item['ID']; ?>" >
                                           <svg class="chart-btn"><use xlink:href="#chart3"></use></svg>
                                    </span>
                                            <span class="compare_item in added" style="display: none;" data-iblock="14"
                                                  data-item="<?php echo $item['ID']; ?>">
                                        <svg class="chart-btn"><use xlink:href="#chart3"></use></svg>
                                    </span>
                                        </div>
                                    </div>
                                    <img src="<?php echo $item['PICTURE_SRC1']; ?>" alt=""
                                         class="mycard__firstimg" style="max-height: 167px;">
                                    <img src="<?php echo $item['PICTURE_SRC2']; ?>" alt=""
                                         class="mycard__twoimg" style="max-height: 167px;">

                                </div>
                                <a href="<?php echo get_product_url_my($item); ?>" class="mycard__title">
                                    <?php echo $item['NAME']; ?></a>
                                <div class="mycard__descript">
                                </div>
                                <div class="availability mycard__availability">В наличии<span>(<?php echo $item['QUANTITY']; ?>)</span></div>
                                <div class="mycard__price"><span class="mycard__price-num"><?= number_format($item["PRICE"], 0, ',', ' ') ?> ₽</span></div>
                                <div class="credit">
                                    <svg class="credit__svg"><use xlink:href="#credit"></use></svg>
                                </div>
                                <div class="mycard__footer">
                                    <a href="#" class="blueBtn mycard__blueBtn to-cart" data-item="<?php echo $item['ID']; ?>">В КОРЗИНУ</a>
                                </div>
                            </div>
                            <!-- /.mycard__inner -->
                        </div>
                        <!-- /.mycard -->
                    </div>
                    <!-- /.card-grid__item -->
                    <?php
                }
                echo '</div>'; // Закрываем контейнер для товаров
            }


            ?>

        <!-- /.card-grid -->
        <div class="list-page__footer">
            <a href="#" id="load-more" class="blueBtn blueBtn--small" data-page="<?php echo $page; ?>"
               data-max-page="<?php echo $maxPage; ?>" data-section-ids="<?php
            echo implode(',', $sectionIds); ?>">Показать еще 20 товаров</a>

            <ul class="pagination">
                <?php for ($i = 1; $i <= $maxPage; $i++) { ?>
                    <li class="pagination__item">
                        <a href="?page=<?php echo $i; ?>" class="pagination__link <?php echo ($i == $page) ? 'pagination__link-active' : ''; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
            </ul>

        </div>
        <script>
            $(document).ready(function() {
                var maxPage = $('#load-more').data('max-page');

                $('#load-more').click(function(e) {
                    e.preventDefault();
                    var page = $(this).data('page');
                    var sectionIds = $(this).data('section-ids');

                    // Увеличиваем номер страницы для следующего запроса
                    $('#load-more').data('page', page + 1);

                    // Обновляем активную ссылку
                    $('.pagination__link.pagination__link-active').removeClass('pagination__link-active');
                    $('.pagination__link[href="?page=' + (page + 1) + '"]').addClass('pagination__link-active');

                    // Проверяем, не превышена ли максимальная страница
                    if ((page+1) >= maxPage) {
                        $('#load-more').hide(); // Скрываем кнопку "Показать еще"
                        return; // Завершаем выполнение функции
                    }

                    $.ajax({
                        url: '/ajax/load_more_equipment.php',
                        type: 'post',
                        data: {page: (page + 1), sectionIds: sectionIds},
                        success: function(response) {
                            $('.card-grid').append(response);
                        }
                    });
                });
                $(document).ready(function() {
                    $('.to-cart').click(function(e) {
                        e.preventDefault(); // отменяем стандартное действие ссылки

                        // получаем ID товара из data-атрибута
                        var itemId = $(this).data('item');

                        // меняем текст кнопки
                        $(this).text('В корзине');

                    });
                });

            });
        </script>


        <hr class="separator">
        <div class="article-short">
            Cведения о товарах, размещённых на сайте, носят исключительно информационный характер и не являются публичной офертой. Производитель вправе менять технические характеристики, внешний вид, комплектацию и страну производства любой модели без предварительного уведомления. Пожалуйста, перед оформлением заказа, уточняйте характеристики интересующих Вас товаров у наших менеджеров
        </div>
    </div>
    <!-- /.list-page -->
<style>
    .left_block {
        display: none!important;
    }
    .list-page{
        padding: 0;
    }
    .mycard__availability{
        background-size: 22px;
        font-size: 16px;
        margin-top: 20px;
    }
    .mycard__price{
        text-align: center;
    }
    .list-page__footer{
        margin-top: 120px;
    }

    .mycard__footer .to-cart{
        display: block!important;
    }

    .wish_item_button {
        zoom: 0.9;
    }
    .compare_item_button {
        margin-left: 10px;
        zoom: 0.9;
    }
</style>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

