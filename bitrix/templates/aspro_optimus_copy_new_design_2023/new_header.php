<header class="header">
    <div class="container header__container">
        <div class="header__top">
            <span class="location">
                <?$APPLICATION->IncludeComponent(
                    "webdebug.seo:regions.link",
                    ".default",
                    array(
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        //"LABEL" => "Ваш город:",
                        "POPUP_FOOTER" => "",
                        "POPUP_HEIGHT" => "550",
                        "POPUP_MIN_HEIGHT" => "400",
                        "POPUP_TITLE" => "Выберите Ваш город",
                        "POPUP_WIDTH" => "800",
                        "COMPONENT_TEMPLATE" => ".default"
                    ),
                    false
                );?>
            </span>
            <div class="header__contacts">
                <div>
                    <a class="header__mail" href="mailto:info@ruoborudovanie.ru">info@ruoborudovanie.ru</a>
                </div>
                <div id="phone-container">
                    <?php
                    $phoneNumber = $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                        array(
                            "COMPONENT_TEMPLATE" => ".default",
                            "PATH" => SITE_DIR."include/phone.php",
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "",
                            "AREA_FILE_RECURSIVE" => "Y",
                            "EDIT_TEMPLATE" => "standard.php"
                        ),
                        true
                    );
                    ?>
                </div>
                <script>
                    // Находим элемент с id "phone-container"
                    var phoneContainer = document.getElementById('phone-container');

                    // Проверяем, что элемент существует
                    if (phoneContainer) {
                        // Находим все ссылки (элементы "a") внутри "phone-container"
                        var links = phoneContainer.getElementsByTagName('a');

                        // Перебираем найденные ссылки и добавляем им класс "header__phone"
                        for (var i = 0; i < links.length; i++) {
                            links[i].classList.add('header__phone');
                        }
                    }

                </script>
                <div>
                    <a class="header__callback callback_btn"><?=GetMessage("CALLBACK")?></a>
                </div>
            </div>
        </div>
        <!-- /.header__top -->
        <div class="header__center">
            <a href="/" class="logo header__logo"><img src="/images/dist/logo.svg" alt="RuOborudovanie.ru"></a>
            <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php"
                ),
                false
            );
            /**/
            ?>
            <script>
                // Найдем форму по её идентификатору
                const form = document.querySelector("#title-search form");

                // Создадим элемент кнопки
                const button = document.createElement("button");
                button.setAttribute("type", "submit");
                button.classList.add("mysearch__submit");

                // Создадим элемент изображения
                const image = document.createElement("img");
                image.setAttribute("src", "/images/dist/search-icon.png");
                image.setAttribute("alt", "");
                button.appendChild(image);

                // Добавим кнопку в форму
                form.appendChild(button);

                document.addEventListener("DOMContentLoaded", function() {
                    var form = document.querySelector("#title-search form");
                    var submitButton = form.querySelector('[name="s"]');

                    form.addEventListener("submit", function(event) {
                        event.preventDefault(); // Отменяет стандартное поведение отправки формы
                        // Дополнительный код, который вы хотите выполнить после предотвращения отправки формы
                    });
                });

            </script>
            <div class="header__btns">
                <a href="/catalog/compare.php" class="header__btns-item">
                    <img src="/images/dist/compare-icon.png" alt="">
                </a>
                <a href="/basket/#delayed" class "header__btns-item">
                    <img src="/images/dist/favourites.png" alt="">
                </a>
                <?php
                global $TEMPLATE_OPTIONS, $APPLICATION, $arBasketPrices;
                // get actual basket counters from session
                $arCounters = COptimus::getBasketCounters();
                ?>
                <a href="/basket/" class="header__btns-item basket">
                    <img src="/images/dist/basket-icon.png" alt="">
                    <span class="basket__count"><?=$arCounters['READY']['COUNT'];?></span>
                </a>
                <a href="/personal/" class="header__btns-item">
                    <img src="/images/dist/user-icon.png" alt="">
                </a>
            </div>
        </div>
        <!-- /.header__center -->
    </div>
    <!-- /.container header__container -->
</header>
<nav class="navigation">
    <div class="container navigation__container">
        <a href="#" class="toggle-mnu" id="pull"><span></span></a>
        <div class="navigation__wrap js-menu">
            <a href="#" class="navBtn" id="menu_top_block"><svg class="navBtn__svg"><use xlink:href="#catalog"></use></svg>Каталог</a>
            <a class="navigation__linkmobile" href="tel:88007074263">8 (800) 707-42-63</a>
            <a class="navigation__linkmobile" href="#">Заказать звонок</a>
            <a class="navigation__linkmobile" href="mailto:info@ruoborudovanie.ru">info@ruoborudovanie.ru</a>
            <ul class="menu">
                <li><a href="/sale/">Акции</a></li>
                <li><a href="/services/">Услуги</a></li>
                <li><a href="/help/">Как купить</a></li>
                <li><a href="/info/brands/">Производители</a></li>
                <li><a href="/company/">О компании</a></li>
                <li><a href="/contacts/">Контакты</a></li>
            </ul>
        </div>
        <!-- /.navigation__wrap -->

    </div>
    <!-- /.container navigation__container -->
    <div id="menu-top-container">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "PATH" => SITE_DIR . "include/left_block/menu.left_menu.php",
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php"
            ),
            false
        );?>
        <?$APPLICATION->ShowViewContent('left_menu');?>
    </div>
</nav>


<script>
    var menuTopBlock = $('#menu_top_block');
    var catalogBlock = $('.catalog_block');

    menuTopBlock.hover(function() {
        catalogBlock.show();
    });
    menuTopBlock.click(function() {
        catalogBlock.hide();
    });

    catalogBlock.hover(function() {
        catalogBlock.show();
    }, function() {
        if (!menuTopBlock.is(':hover')) {
            catalogBlock.hide();
        }
    });

</script>