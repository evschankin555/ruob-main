<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Промышленное оборудование для пищевого производства и общепита. Профессиональное оборудование для HoReCa с доставкой по РФ.");
$APPLICATION->SetPageProperty("title", "Оборудование для пищевого производства | РУОБОРУДОВАНИЕ.РУ");
$APPLICATION->SetPageProperty("viewed_show", "Y");
$APPLICATION->SetTitle("Dev RuOborudovanie.ru");

?>
    <div class="container">
        <div class="card-product">
            <div class="main-box">
                <h2 class="h2 main-box__title">Полное оборудование вашего бизнеса за 3 дня</h2>
            </div>
            <div class="threeCol">
                <div class="threeCol__item threeCol__item-green">
                    <div class="threeCol__title">Более 150 000<br> позиций</div>
                </div>
                <div class="threeCol__item threeCol__item-project">
                    <div class="threeCol__title">Проектирование и комплексное оснащение предприятий пищевой промышленности и торговли</div>
                </div>
                <div class="threeCol__item threeCol__item-gold">
                    <picture>
                        <source srcset="images/dist/of-warranty-mobile.svg" media="(max-width: 580px)">
                        <img src="images/dist/of-warranty.svg" width="424" height="386" alt="">
                    </picture>
                    <div class="threeCol__title threeCol__title-position">Официальная гарантия от производителя</div>
                </div>
                <div class="threeCol__item threeCol__item-delivery">
                    <div class="threeCol__title">Доставка по всей России</div>
                </div>
                <div class="threeCol__item threeCol__item-blue">
                    <div class="threeCol__title">Индивидуальные решения и особый подход</div>
                </div>
            </div>
            <!-- /.threeCol -->

            <div class="four-items">
                <div class="four-items__item">
                    <div class="four-items__name">Комплексное оснащение предприятий</div>
                </div>
                <div class="four-items__item">
                    <div class="four-items__name">Более 2000 реализованных проектов</div>
                </div>
                <div class="four-items__item">
                    <div class="four-items__name">Высочайший уровень сервиса</div>
                </div>
                <div class="four-items__item">
                    <div class="four-items__name">Вся продукция сертифицирована</div>
                </div>
            </div>

            <section class="equipment">
                <h2 class="h2 equipment__title">Подбор оборудования по типу предприятия</h2>
                <div class="equipment__grid">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        ".default",
                        Array(
                            "AREA_FILE_RECURSIVE" => "Y",
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "",
                            "COMPONENT_TEMPLATE" => ".default",
                            "EDIT_TEMPLATE" => "standard.php",
                            "PATH" => SITE_DIR."include/mainpage/comp_equipment.php"
                        )
                    );?>
                </div>
                <!-- /.equipment__grid -->
            </section>

            <section class="cert">
                <h2 class="h2 cert__title">Сертификаты</h2>
                <div class="slider-wrapper">
                    <div class="cert__slider js-cert-slider">
                        <div><a class="cert__slider-link" data-fancybox href="images/dist/cert-1.jpg"><img src="images/dist/cert-1.jpg" width="239" height="338" loading="lazy" alt=""></a></div>
                        <div><a class="cert__slider-link" data-fancybox href="images/dist/cert-1.jpg"><img src="images/dist/cert-1.jpg" width="239" height="338" loading="lazy" alt=""></a></div>
                        <div><a class="cert__slider-link" data-fancybox href="images/dist/cert-1.jpg"><img src="images/dist/cert-1.jpg" width="239" height="338" loading="lazy" alt=""></a></div>
                        <div><a class="cert__slider-link" data-fancybox href="images/dist/cert-1.jpg"><img src="images/dist/cert-1.jpg" width="239" height="338" loading="lazy" alt=""></a></div>
                        <div><a class="cert__slider-link" data-fancybox href="images/dist/cert-1.jpg"><img src="images/dist/cert-1.jpg" width="239" height="338" loading="lazy" alt=""></a></div>
                        <div><a class="cert__slider-link" data-fancybox href="images/dist/cert-1.jpg"><img src="images/dist/cert-1.jpg" width="239" height="338" loading="lazy" alt=""></a></div>
                    </div>
                    <!-- /.cert__slider -->
                </div>
                <!-- /.cert__wrap -->
            </section>

            <section class="selection">
                <h2 class="h2 selection__title">Подбор оборудования по производителю</h2>
                <div class="slider-wrapper">
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
                </div>
            </section>
            <section class="clients">
                <h2 class="h2 selection__title">Наши клиенты</h2>
                <div class="slider-wrapper">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        ".default",
                        Array(
                            "AREA_FILE_RECURSIVE" => "Y",
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "",
                            "COMPONENT_TEMPLATE" => ".default",
                            "EDIT_TEMPLATE" => "standard.php",
                            "PATH" => SITE_DIR."include/mainpage/comp_clients.php"
                        )
                    );?>
                </div>
            </section>

        </div>

    </div>
    <!-- /.container -->




<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>