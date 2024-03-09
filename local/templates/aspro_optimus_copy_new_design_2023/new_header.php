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
            <div class=" header__btns" >
                <?php
                global $TEMPLATE_OPTIONS, $APPLICATION, $arBasketPrices;
                // get actual basket counters from session
                $arCounters = COptimus::getBasketCounters();
                ?>
                <a href="/catalog/compare.php" class="header__btns-item">
                    <img src="/images/dist/compare-icon.png" alt="">
                    <span class="basket__count" id="compare_line" ></span>
                </a>
                <script>
                    $.ajax({
                        url: '/ajax/show_compare_preview_top.php',
                        method: 'GET',
                        success: function(response) {
                            // Парсим ответ как число
                            var count = parseInt(response);

                            // Вставляем число в указанный элемент
                            $('#compare_line').text(count);
                        },
                        error: function(error) {
                            console.error('Ошибка при выполнении AJAX-запроса:', error);
                        }
                    });
                </script>
                <a href="/basket/#delayed" class="header__btns-item">
                    <img src="/images/dist/favourites.png" alt="">
                    <span class="basket__count" id="favourites_line" >0</span>
                </a>
                <a href="/basket/" class="header__btns-item basket">
                    <img src="/images/dist/basket-icon.png" alt="">
                    <span class="basket__count" id="basket_line" ><?=$arCounters['READY']['COUNT'];?></span>
                </a>
                <script>
                    window.onload = function (){
                        // Функция для обновления количества в корзине
                        function updateBasketCount() {
                            $.ajax({
                                url: '/ajax/basket_count.php',
                                method: 'GET',
                                success: function(response) {
                                    var count = parseInt(response);
                                    $('#basket_line').text(count);
                                },
                                error: function(error) {
                                    console.error('Ошибка при выполнении AJAX-запроса:', error);
                                }
                            });
                        }

                        // Обработчик событий для кнопок "Купить"
                        $('.to-cart').each(function() {
                            $(this).on('click', function() {

                                // Ждем 300мс и вызываем функцию обновления количества в корзине
                                setTimeout(updateBasketCount, 300);
                            });
                        });
                        updateBasketCount();

                        function updateHeartCount() {
                            $.ajax({
                                url: '/ajax/heart_count.php',
                                method: 'GET',
                                success: function(response) {
                                    var count = parseInt(response);
                                    $('#favourites_line').text(count);
                                },
                                error: function(error) {
                                    console.error('Ошибка при выполнении AJAX-запроса:', error);
                                }
                            });
                        }
                        updateHeartCount();

                        $('.wish_item ').each(function() {
                            $(this).on('click', function() {
                                // Ждем 300мс и вызываем функцию обновления количества в корзине
                                setTimeout(updateHeartCount, 300);
                            });
                        });
                    };
                </script>

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
            <a href="/catalog/" class="navBtn" id="menu_top_block"><svg class="navBtn__svg"><use xlink:href="#catalog"></use></svg>Каталог</a>
            <a class="navigation__linkmobile" href="tel:88007074263">8 (800) 707-42-63</a>
            <a class="navigation__linkmobile callback_btn" href="#">Заказать звонок</a>
            <a class="navigation__linkmobile" href="mailto:info@ruoborudovanie.ru">info@ruoborudovanie.ru</a>
            <ul class="menu">
                <li><a href="/info/articles/">Новости</a></li>
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

    </div>
</nav>


<script>
    $(document).ready(function() {
        var menuTopBlock = $('#menu_top_block');
        var catalogBlock = $('.menu_top_block.catalog_block');

        menuTopBlock.hover(function() {
            if (window.innerWidth > 1000) {
                catalogBlock.show();
            }
        });
        menuTopBlock.click(function() {
            if (window.innerWidth > 1000) {
                catalogBlock.hide();
            }
        });

        catalogBlock.hover(function() {
            if (window.innerWidth > 1000) {
                catalogBlock.show();
            }
        }, function() {
            if (window.innerWidth > 1000 && !menuTopBlock.is(':hover')) {
                catalogBlock.hide();
            }
        });
    });

</script>




<div class="sprite" aria-hidden="true">
    <svg display="none" xmlns="http://www.w3.org/2000/svg">
        <symbol id="catalog" viewBox="0 0 62 62">
            <rect opacity="0.4" x="0.5" y="0.5" width="61" height="61" rx="9.5" stroke="#0065BB"></rect>
            <rect x="13" y="21" width="36" height="2" rx="1" fill="#0065BB"></rect>
            <rect x="13" y="30" width="36" height="2" rx="1" fill="#0065BB"></rect>
            <rect x="13" y="39" width="22.8293" height="2" rx="1" fill="#0065BB"></rect>
        </symbol>
        <symbol viewBox="0 0 19 29" id="arrow-prev">
            <path d="M18 28L2 14.5L18 1" stroke-width="2" stroke-linecap="round"></path>
        </symbol>
        <symbol viewBox="0 0 19 29" id="arrow-next">
            <path d="M1 1L17 14.5L1 28" stroke-width="2" stroke-linecap="round"></path>
        </symbol>
        <symbol viewBox="0 0 114 22" id="star">
            <path d="M10.5245 1.46352C10.6741 1.00287 11.3259 1.00287 11.4755 1.46353L13.3574 7.25532C13.4243 7.46133 13.6163 7.60081 13.8329 7.60081H19.9228C20.4071 7.60081 20.6085 8.22062 20.2167 8.50532L15.2899 12.0848C15.1146 12.2122 15.0413 12.4379 15.1082 12.6439L16.9901 18.4357C17.1398 18.8963 16.6125 19.2794 16.2207 18.9947L11.2939 15.4152C11.1186 15.2878 10.8814 15.2878 10.7061 15.4152L5.77931 18.9947C5.38745 19.2794 4.86021 18.8963 5.00989 18.4357L6.89176 12.6439C6.9587 12.4379 6.88537 12.2122 6.71012 12.0848L1.78333 8.50532C1.39147 8.22062 1.59286 7.60081 2.07722 7.60081H8.16708C8.38369 7.60081 8.57567 7.46133 8.6426 7.25532L10.5245 1.46352Z" fill="#0065BB"></path>
            <path d="M33.5245 1.46352C33.6741 1.00287 34.3259 1.00287 34.4755 1.46353L36.3574 7.25532C36.4243 7.46133 36.6163 7.60081 36.8329 7.60081H42.9228C43.4071 7.60081 43.6085 8.22062 43.2167 8.50532L38.2899 12.0848C38.1146 12.2122 38.0413 12.4379 38.1082 12.6439L39.9901 18.4357C40.1398 18.8963 39.6125 19.2794 39.2207 18.9947L34.2939 15.4152C34.1186 15.2878 33.8814 15.2878 33.7061 15.4152L28.7793 18.9947C28.3875 19.2794 27.8602 18.8963 28.0099 18.4357L29.8918 12.6439C29.9587 12.4379 29.8854 12.2122 29.7101 12.0848L24.7833 8.50532C24.3915 8.22062 24.5929 7.60081 25.0772 7.60081H31.1671C31.3837 7.60081 31.5757 7.46133 31.6426 7.25532L33.5245 1.46352Z" fill="#0065BB"></path>
            <path d="M56.5245 1.46352C56.6741 1.00287 57.3259 1.00287 57.4755 1.46353L59.3574 7.25532C59.4243 7.46133 59.6163 7.60081 59.8329 7.60081H65.9228C66.4071 7.60081 66.6085 8.22062 66.2167 8.50532L61.2899 12.0848C61.1146 12.2122 61.0413 12.4379 61.1082 12.6439L62.9901 18.4357C63.1398 18.8963 62.6125 19.2794 62.2207 18.9947L57.2939 15.4152C57.1186 15.2878 56.8814 15.2878 56.7061 15.4152L51.7793 18.9947C51.3875 19.2794 50.8602 18.8963 51.0099 18.4357L52.8918 12.6439C52.9587 12.4379 52.8854 12.2122 52.7101 12.0848L47.7833 8.50532C47.3915 8.22062 47.5929 7.60081 48.0772 7.60081H54.1671C54.3837 7.60081 54.5757 7.46133 54.6426 7.25532L56.5245 1.46352Z" fill="#0065BB"></path>
            <path d="M79.5245 1.46352C79.6741 1.00287 80.3259 1.00287 80.4755 1.46353L82.3574 7.25532C82.4243 7.46133 82.6163 7.60081 82.8329 7.60081H88.9228C89.4071 7.60081 89.6085 8.22062 89.2167 8.50532L84.2899 12.0848C84.1146 12.2122 84.0413 12.4379 84.1082 12.6439L85.9901 18.4357C86.1398 18.8963 85.6125 19.2794 85.2207 18.9947L80.2939 15.4152C80.1186 15.2878 79.8814 15.2878 79.7061 15.4152L74.7793 18.9947C74.3875 19.2794 73.8602 18.8963 74.0099 18.4357L75.8918 12.6439C75.9587 12.4379 75.8854 12.2122 75.7101 12.0848L70.7833 8.50532C70.3915 8.22062 70.5929 7.60081 71.0772 7.60081H77.1671C77.3837 7.60081 77.5757 7.46133 77.6426 7.25532L79.5245 1.46352Z" fill="#0065BB"></path>
            <path d="M102.524 1.46352C102.674 1.00287 103.326 1.00287 103.476 1.46353L105.357 7.25532C105.424 7.46133 105.616 7.60081 105.833 7.60081H111.923C112.407 7.60081 112.609 8.22062 112.217 8.50532L107.29 12.0848C107.115 12.2122 107.041 12.4379 107.108 12.6439L108.99 18.4357C109.14 18.8963 108.613 19.2794 108.221 18.9947L103.294 15.4152C103.119 15.2878 102.881 15.2878 102.706 15.4152L97.7793 18.9947C97.3875 19.2794 96.8602 18.8963 97.0099 18.4357L98.8918 12.6439C98.9587 12.4379 98.8854 12.2122 98.7101 12.0848L93.7833 8.50532C93.3915 8.22062 93.5929 7.60081 94.0772 7.60081H100.167C100.384 7.60081 100.576 7.46133 100.643 7.25532L102.524 1.46352Z" fill="#9C9C9C"></path>
        </symbol>
        <symbol viewBox="0 0 16 16" id="plus">
            <rect x="7" y="16" width="16" height="2" rx="1" transform="rotate(-90 7 16)"></rect>
            <rect y="7" width="16" height="2" rx="1"></rect>
        </symbol>
        <symbol id="star-one" viewBox="0 0 28 26">
            <path d="M13.0489 0.927047C13.3483 0.00573683 14.6517 0.00573993 14.9511 0.927051L17.3677 8.36475C17.5016 8.77677 17.8855 9.05573 18.3188 9.05573H26.1392C27.1079 9.05573 27.5107 10.2953 26.727 10.8647L20.4001 15.4615C20.0496 15.7161 19.903 16.1675 20.0369 16.5795L22.4535 24.0172C22.7529 24.9385 21.6984 25.7047 20.9147 25.1353L14.5878 20.5385C14.2373 20.2839 13.7627 20.2839 13.4122 20.5385L7.08533 25.1353C6.30162 25.7047 5.24714 24.9385 5.54649 24.0172L7.96315 16.5795C8.09702 16.1675 7.95036 15.7161 7.59987 15.4615L1.27299 10.8647C0.489277 10.2953 0.892056 9.05573 1.86078 9.05573H9.68123C10.1145 9.05573 10.4984 8.77677 10.6323 8.36475L13.0489 0.927047Z"></path>
        </symbol>
        <symbol viewBox="0 0 64 64" id="arrow-next-long">
            <path d="M15 31C14.4477 31 14 31.4477 14 32C14 32.5523 14.4477 33 15 33V31ZM49.7071 32.7071C50.0976 32.3166 50.0976 31.6834 49.7071 31.2929L43.3431 24.9289C42.9526 24.5384 42.3195 24.5384 41.9289 24.9289C41.5384 25.3195 41.5384 25.9526 41.9289 26.3431L47.5858 32L41.9289 37.6569C41.5384 38.0474 41.5384 38.6805 41.9289 39.0711C42.3195 39.4616 42.9526 39.4616 43.3431 39.0711L49.7071 32.7071ZM15 33H49V31H15V33Z"></path>
        </symbol>
        <symbol id="main">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.397 2.481a2.25 2.25 0 012.206 0l5 2.812a2.25 2.25 0 011.147 1.962V16a2.25 2.25 0 01-2.25 2.25h-1.25V13a2.75 2.75 0 00-2.75-2.75h-2A2.75 2.75 0 005.75 13v5.25H4.5A2.25 2.25 0 012.25 16V7.255a2.25 2.25 0 011.147-1.962l5-2.812zM11.75 18.25h-4.5V13c0-.69.56-1.25 1.25-1.25h2c.69 0 1.25.56 1.25 1.25v5.25zm-6 1.5H4.5A3.75 3.75 0 01.75 16V7.255a3.75 3.75 0 011.912-3.269l5-2.812a3.75 3.75 0 013.676 0l5 2.812a3.75 3.75 0 011.912 3.269V16a3.75 3.75 0 01-3.75 3.75H5.75z"></path>
        </symbol>
        <symbol id="catalog">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5.25a.75.75 0 000 1.5h13a.75.75 0 000-1.5h-13zm9.75 8.77a4.27 4.27 0 117.299 3.01l-.01.01-.008.009a4.27 4.27 0 01-7.28-3.029zm10.04 0a5.745 5.745 0 01-1.194 3.515l2.934 2.935a.75.75 0 01-1.06 1.06l-2.934-2.934A5.77 5.77 0 1121.29 9.02zM.75 7a.75.75 0 01.75-.75h4a.75.75 0 010 1.5h-4A.75.75 0 01.75 7zm.75 5.25a.75.75 0 000 1.5h6a.75.75 0 000-1.5h-6z"></path>
        </symbol>
        <symbol id="basket">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M.75 1A.75.75 0 011.5.25h1.382a1.75 1.75 0 011.565.967l-.67.336.67-.336.517 1.033h13.674a1.25 1.25 0 011.086 1.87l-3.285 5.748a1.75 1.75 0 01-1.52.882H7.417l-1.324 2.117a.25.25 0 00.212.383H17.5a.75.75 0 010 1.5H6.304c-1.374 0-2.212-1.512-1.484-2.678L6.172 9.91 3.809 3.295l-.703-1.407a.25.25 0 00-.224-.138H1.5A.75.75 0 01.75 1zm4.814 2.75l1.965 5.5h7.39a.25.25 0 00.218-.126l3.07-5.374H5.565zM8.5 17.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM16 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
        </symbol>
        <symbol id="profil">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5 1.75a8.25 8.25 0 00-6.35 13.517c1.447-1.48 3.392-2.701 6.35-2.701 2.957 0 4.902 1.21 6.351 2.7A8.25 8.25 0 0010.5 1.75zm5.293 14.579c-1.244-1.285-2.83-2.263-5.293-2.263-2.46 0-4.047.984-5.292 2.263A8.217 8.217 0 0010.5 18.25c2.014 0 3.86-.722 5.293-1.921zM.75 10C.75 4.615 5.115.25 10.5.25s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S.75 15.385.75 10zm9.75-4.46a2.566 2.566 0 100 5.131 2.566 2.566 0 000-5.132zM6.434 8.104a4.066 4.066 0 118.132 0 4.066 4.066 0 01-8.132 0z"></path>
        </symbol>
        <symbol id="place-top">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.25 8.5c0-3.778 2.846-6.75 6.25-6.75s6.25 2.972 6.25 6.75c0 2.714-1.595 5.161-3.304 6.988a20.769 20.769 0 01-2.958 2.6 20.732 20.732 0 01-2.946-2.6C3.84 13.663 2.25 11.217 2.25 8.501zm5.83 11.13l.407-.63.405.631a.75.75 0 01-.811 0zm0 0l.407-.63c.405.631.406.63.406.63h.002l.005-.004.019-.012.065-.043.236-.162a22.277 22.277 0 003.322-2.896c1.797-1.922 3.708-4.726 3.708-8.013 0-4.507-3.422-8.25-7.75-8.25C4.172.25.75 3.993.75 8.5c0 3.286 1.904 6.09 3.695 8.012a22.22 22.22 0 003.31 2.896 13.497 13.497 0 00.3.205l.018.012.006.004.002.001zM6.25 8a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0zM8.5 4.25a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5z"></path>
        </symbol>
        <symbol id="close" viewBox="0 0 24 24">
            <path d="M6.2253 4.81108C5.83477 4.42056 5.20161 4.42056 4.81108 4.81108C4.42056 5.20161 4.42056 5.83477 4.81108 6.2253L10.5858 12L4.81114 17.7747C4.42062 18.1652 4.42062 18.7984 4.81114 19.1889C5.20167 19.5794 5.83483 19.5794 6.22535 19.1889L12 13.4142L17.7747 19.1889C18.1652 19.5794 18.7984 19.5794 19.1889 19.1889C19.5794 18.7984 19.5794 18.1652 19.1889 17.7747L13.4142 12L19.189 6.2253C19.5795 5.83477 19.5795 5.20161 19.189 4.81108C18.7985 4.42056 18.1653 4.42056 17.7748 4.81108L12 10.5858L6.2253 4.81108Z"></path>
        </symbol>
        <symbol id="icon-plus" viewBox="0 0 64 64">
            <g transform="translate(28.000000, 278.000000)"><path class="st0" d="M4-222.1c-13.2,0-23.9-10.7-23.9-23.9c0-13.2,10.7-23.9,23.9-23.9s23.9,10.7,23.9,23.9     C27.9-232.8,17.2-222.1,4-222.1L4-222.1z M4-267.3c-11.7,0-21.3,9.6-21.3,21.3s9.6,21.3,21.3,21.3s21.3-9.6,21.3-21.3     S15.7-267.3,4-267.3L4-267.3z" id="Fill-38"></path><polygon class="st0" id="Fill-39" points="-8.7,-247.4 16.7,-247.4 16.7,-244.6 -8.7,-244.6    "></polygon><polygon class="st0" id="Fill-40" points="2.6,-258.7 5.4,-258.7 5.4,-233.3 2.6,-233.3"></polygon></g>
        </symbol>
        <symbol id="copy" viewBox="0 0 512 512">
            <path d="M448 0H224C188.7 0 160 28.65 160 64v224c0 35.35 28.65 64 64 64h224c35.35 0 64-28.65 64-64V64C512 28.65 483.3 0 448 0zM464 288c0 8.822-7.178 16-16 16H224C215.2 304 208 296.8 208 288V64c0-8.822 7.178-16 16-16h224c8.822 0 16 7.178 16 16V288zM304 448c0 8.822-7.178 16-16 16H64c-8.822 0-16-7.178-16-16V224c0-8.822 7.178-16 16-16h64V160H64C28.65 160 0 188.7 0 224v224c0 35.35 28.65 64 64 64h224c35.35 0 64-28.65 64-64v-64h-48V448z"></path>
        </symbol>
        <symbol id="favourite" viewBox="0 0 128 128">
            <path d="M116.22,16.68C108,8.8,95.16,4.88,83.44,6.71,75,8,68.17,12.26,64.07,18.68c-4-6.53-10.62-10.84-18.93-12.22-11.61-1.91-25,2.19-33.37,10.21A38.19,38.19,0,0,0,0,44.05,39.61,39.61,0,0,0,11.74,72.65L59,119.94a7,7,0,0,0,9.94,0l47.29-47.3A39.61,39.61,0,0,0,128,44.05,38.19,38.19,0,0,0,116.22,16.68ZM112,68.4,64.73,115.7a1,1,0,0,1-1.46,0L16,68.4A33.66,33.66,0,0,1,6,44.11,32.23,32.23,0,0,1,15.94,21c5.89-5.67,14.78-9,23.29-9a30.38,30.38,0,0,1,4.94.4c5,.82,11.67,3.32,15.42,10.56A5.06,5.06,0,0,0,64,25.68h0a4.92,4.92,0,0,0,4.34-2.58h0c3.89-7.2,10.82-9.66,15.94-10.46,9.77-1.52,20.9,1.84,27.7,8.37A32.23,32.23,0,0,1,122,44.11,33.66,33.66,0,0,1,112,68.4Z"></path>
        </symbol>
        <symbol id="angle-down" viewBox="0 0 50 50"><g id="Layer_1_1_"><polygon points="48.707,13.853 47.293,12.44 25,34.732 2.707,12.44 1.293,13.853 25,37.56  "></polygon></g></symbol>
        <symbol id="basket-2" viewBox="0 0 512 512">
            <g data-name="1"><path d="M356.65,450H171.47a41,41,0,0,1-40.9-40.9V120.66a15,15,0,0,1,15-15h237a15,15,0,0,1,15,15V409.1A41,41,0,0,1,356.65,450ZM160.57,135.66V409.1a10.91,10.91,0,0,0,10.9,10.9H356.65a10.91,10.91,0,0,0,10.91-10.9V135.66Z"></path><path d="M327.06,135.66h-126a15,15,0,0,1-15-15V93.4A44.79,44.79,0,0,1,230.8,48.67h66.52A44.79,44.79,0,0,1,342.06,93.4v27.26A15,15,0,0,1,327.06,135.66Zm-111-30h96V93.4a14.75,14.75,0,0,0-14.74-14.73H230.8A14.75,14.75,0,0,0,216.07,93.4Z"></path><path d="M264.06,392.58a15,15,0,0,1-15-15V178.09a15,15,0,1,1,30,0V377.58A15,15,0,0,1,264.06,392.58Z"></path><path d="M209.9,392.58a15,15,0,0,1-15-15V178.09a15,15,0,0,1,30,0V377.58A15,15,0,0,1,209.9,392.58Z"></path><path d="M318.23,392.58a15,15,0,0,1-15-15V178.09a15,15,0,0,1,30,0V377.58A15,15,0,0,1,318.23,392.58Z"></path><path d="M405.81,135.66H122.32a15,15,0,0,1,0-30H405.81a15,15,0,0,1,0,30Z"></path></g>
        </symbol>
        <symbol viewBox="0 0 128 128" id="question">
            <path d="M64.049,114.172v-2l-0.006,2c-27.642,0-50.129-22.486-50.129-50.128c0-27.642,22.487-50.13,50.129-50.13  s50.129,22.488,50.129,50.13C114.172,91.686,91.687,114.172,64.049,114.172z M64.043,17.914c-25.436,0-46.129,20.694-46.129,46.13  c0,25.436,20.693,46.128,46.129,46.128h0.006c25.433,0,46.123-20.692,46.123-46.128C110.172,38.608,89.479,17.914,64.043,17.914z"></path><g><path d="M64.043,74.354c-1.104,0-2-0.896-2-2v-9.756c0-1.104,0.896-2,2-2c5.27,0,9.557-4.286,9.557-9.556   c0-5.271-4.287-9.56-9.557-9.56s-9.557,4.289-9.557,9.56c0,1.104-0.896,2-2,2s-2-0.896-2-2c0-7.477,6.081-13.56,13.557-13.56   S77.6,43.565,77.6,51.042c0,6.796-5.026,12.439-11.557,13.409v7.903C66.043,73.458,65.147,74.354,64.043,74.354z"></path><circle cx="64.042" cy="85.098" r="3.796"></circle></g>
        </symbol>
        <symbol id="comment" viewBox="0 0 50 50">
            <g id="Layer_1_1_"><path d="M1,39.293h10v9.414l9.414-9.414H49v-38H1V39.293z M3,3.293h44v34H19.586L13,43.879v-6.586H3V3.293z"></path></g>
        </symbol>
        <symbol viewBox="0 0 32 32" id="eye">
            <g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#333"><path d="M16,9 C7,9 3,16 3,16 C3,16 7,23.000001 16,23 C25,22.999999 29,16 29,16 C29,16 25,9 16,9 L16,9 L16,9 Z M16,10 C8,10 4.19995117,16 4.19995117,16 C4.19995117,16 8,22.0000006 16,22 C24,21.999999 27.8000488,16 27.8000488,16 C27.8000488,16 24,10 16,10 L16,10 L16,10 Z M16,20 C18.2091391,20 20,18.2091391 20,16 C20,13.7908609 18.2091391,12 16,12 C13.7908609,12 12,13.7908609 12,16 C12,18.2091391 13.7908609,20 16,20 L16,20 L16,20 Z M16,19 C17.6568543,19 19,17.6568543 19,16 C19,14.3431457 17.6568543,13 16,13 C14.3431457,13 13,14.3431457 13,16 C13,17.6568543 14.3431457,19 16,19 L16,19 L16,19 Z M16,17 C16.5522848,17 17,16.5522848 17,16 C17,15.4477152 16.5522848,15 16,15 C15.4477152,15 15,15.4477152 15,16 C15,16.5522848 15.4477152,17 16,17 L16,17 L16,17 Z"></path></g></g>
        </symbol>
        <symbol id="grid-four" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24">
            <rect height="7" width="7" x="3" y="3"></rect><rect height="7" width="7" x="14" y="3"></rect><rect height="7" width="7" x="14" y="14"></rect><rect height="7" width="7" x="3" y="14"></rect>
        </symbol>
        <symbol id="view-row" viewBox="0 0 24 24">
            <rect x="4" y="5" width="16" height="5" rx="1" stroke-width="1" stroke-linejoin="round"></rect>
            <rect x="4" y="14" width="16" height="5" rx="1" stroke-width="1" stroke-linejoin="round"></rect>
        </symbol>
        <symbol id="info" viewBox="0 0 512 512">
            <g><path d="M257.338,166.245c16.297,0,29.52-13.223,29.52-29.52c0-16.317-13.223-29.501-29.52-29.501   c-16.298,0-29.52,13.185-29.52,29.501C227.818,153.022,241.04,166.245,257.338,166.245z"></path><polygon points="277.383,205.605 277.383,195.265 277.383,185.925 218.343,185.925 218.343,205.605    238.023,205.605 238.023,372.885 218.343,372.885 218.343,392.565 297.063,392.565 297.063,372.885 277.383,372.885  "></polygon><path d="M256.108,9.65c-135.857,0-246,110.143-246,246c0,135.877,110.143,246,246,246   c135.857,0,246-110.123,246-246C502.108,119.793,391.966,9.65,256.108,9.65z M256.108,481.97   c-124.797,0-226.32-101.533-226.32-226.32S131.312,29.33,256.108,29.33c124.797,0,226.32,101.533,226.32,226.32   S380.905,481.97,256.108,481.97z"></path></g>
        </symbol>
        <symbol id="question" viewBox="0 0 128 128">
            <path d="M64.049,114.172v-2l-0.006,2c-27.642,0-50.129-22.486-50.129-50.128c0-27.642,22.487-50.13,50.129-50.13  s50.129,22.488,50.129,50.13C114.172,91.686,91.687,114.172,64.049,114.172z M64.043,17.914c-25.436,0-46.129,20.694-46.129,46.13  c0,25.436,20.693,46.128,46.129,46.128h0.006c25.433,0,46.123-20.692,46.123-46.128C110.172,38.608,89.479,17.914,64.043,17.914z"></path><g><path d="M64.043,74.354c-1.104,0-2-0.896-2-2v-9.756c0-1.104,0.896-2,2-2c5.27,0,9.557-4.286,9.557-9.556   c0-5.271-4.287-9.56-9.557-9.56s-9.557,4.289-9.557,9.56c0,1.104-0.896,2-2,2s-2-0.896-2-2c0-7.477,6.081-13.56,13.557-13.56   S77.6,43.565,77.6,51.042c0,6.796-5.026,12.439-11.557,13.409v7.903C66.043,73.458,65.147,74.354,64.043,74.354z"></path><circle cx="64.042" cy="85.098" r="3.796"></circle></g>
        </symbol>
        <symbol viewBox="0 0 24 24" id="filter">
            <g id="grid_system"></g><g id="_icons"><path d="M4,17h8.1c0.4,1.7,2,3,3.9,3s3.4-1.3,3.9-3H20c0.6,0,1-0.4,1-1s-0.4-1-1-1h-0.1c-0.4-1.7-2-3-3.9-3s-3.4,1.3-3.9,3H4   c-0.6,0-1,0.4-1,1S3.4,17,4,17z M16,14c1.1,0,2,0.9,2,2s-0.9,2-2,2s-2-0.9-2-2S14.9,14,16,14z"></path><path d="M4,9h0.1c0.4,1.7,2,3,3.9,3s3.4-1.3,3.9-3H20c0.6,0,1-0.4,1-1s-0.4-1-1-1h-8.1c-0.4-1.7-2-3-3.9-3S4.6,5.3,4.1,7H4   C3.4,7,3,7.4,3,8S3.4,9,4,9z M8,6c1.1,0,2,0.9,2,2s-0.9,2-2,2S6,9.1,6,8S6.9,6,8,6z"></path></g>
        </symbol>
        <symbol  viewBox="0 0 512 512" id="heart">
            <path d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z" stroke-width="8"/>
        </symbol>
        <symbol id="chart3" viewBox="0 0 448 512">
            <path d="M160 80c0-26.5 21.5-48 48-48h32c26.5 0 48 21.5 48 48V432c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V80zM0 272c0-26.5 21.5-48 48-48H80c26.5 0 48 21.5 48 48V432c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V272zM368 96h32c26.5 0 48 21.5 48 48V432c0 26.5-21.5 48-48 48H368c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48z" stroke-width="8"/>
        </symbol>
    </svg>
</div>