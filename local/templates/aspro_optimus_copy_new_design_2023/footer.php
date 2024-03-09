<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") die();?>
<?IncludeTemplateLangFile(__FILE__);?>

							<?if(!COptimus::IsMainPage() &&  strpos($_SERVER['REQUEST_URI'], '/info/articles/') !== 0):?>
                                <?if($APPLICATION->GetCurPage() == "/order/"):?>
                                    </section>
                                <?endif;?>
								</div> <?// .container?>
							<?endif;?>
<!--</div>-->
					<?if(!COptimus::IsOrderPage() && !COptimus::IsBasketPage()):?>
						</div> <?// .right_block?>
					<?endif;?>
				</div> <?// .wrapper_inner?>
			</div> <?// #content?>
		</div><?// .wrapper
		/*<nav class="menu-nav">
				<ul class="menu-nav__list">
					<li class="menu-nav__item"><a href="/" class="menu-nav__link <?if($APPLICATION->GetCurDir() === '/') {?>menu-nav__link_active <?}?>"><!----> <svg width="19" height="20" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-nav__icon-svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.397 2.481a2.25 2.25 0 012.206 0l5 2.812a2.25 2.25 0 011.147 1.962V16a2.25 2.25 0 01-2.25 2.25h-1.25V13a2.75 2.75 0 00-2.75-2.75h-2A2.75 2.75 0 005.75 13v5.25H4.5A2.25 2.25 0 012.25 16V7.255a2.25 2.25 0 011.147-1.962l5-2.812zM11.75 18.25h-4.5V13c0-.69.56-1.25 1.25-1.25h2c.69 0 1.25.56 1.25 1.25v5.25zm-6 1.5H4.5A3.75 3.75 0 01.75 16V7.255a3.75 3.75 0 011.912-3.269l5-2.812a3.75 3.75 0 013.676 0l5 2.812a3.75 3.75 0 011.912 3.269V16a3.75 3.75 0 01-3.75 3.75H5.75z" fill="#AFAFAF"></path></svg> <span class="menu-nav__title">Главная</span> <!----></a></li>
					<li class="menu-nav__item"><a href="/catalog/" class="menu-nav__link <?if(CSite::InDir('/catalog/')) {?>menu-nav__link_active <?}?>"><!----> <svg width="24" height="17" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-nav__icon-svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.5.25a.75.75 0 000 1.5h13a.75.75 0 000-1.5h-13zm9.75 8.77a4.27 4.27 0 117.299 3.01l-.01.01-.008.009a4.27 4.27 0 01-7.28-3.029zm10.04 0a5.745 5.745 0 01-1.194 3.515l2.934 2.935a.75.75 0 01-1.06 1.06l-2.934-2.934A5.77 5.77 0 1121.29 9.02zM.75 7a.75.75 0 01.75-.75h4a.75.75 0 010 1.5h-4A.75.75 0 01.75 7zm.75 5.25a.75.75 0 000 1.5h6a.75.75 0 000-1.5h-6z" fill="#AFAFAF"></path></svg> <span class="menu-nav__title">Каталог</span> <!----></a></li>
					<li class="menu-nav__item"><a href="/basket/" class="menu-nav__link <?if(CSite::InDir('/basket/')) {?>menu-nav__link_active <?}?>"><!----> <svg width="20" height="19" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-nav__icon-svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M.75 1A.75.75 0 011.5.25h1.382a1.75 1.75 0 011.565.967l-.67.336.67-.336.517 1.033h13.674a1.25 1.25 0 011.086 1.87l-3.285 5.748a1.75 1.75 0 01-1.52.882H7.417l-1.324 2.117a.25.25 0 00.212.383H17.5a.75.75 0 010 1.5H6.304c-1.374 0-2.212-1.512-1.484-2.678L6.172 9.91 3.809 3.295l-.703-1.407a.25.25 0 00-.224-.138H1.5A.75.75 0 01.75 1zm4.814 2.75l1.965 5.5h7.39a.25.25 0 00.218-.126l3.07-5.374H5.565zM8.5 17.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM16 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" fill="#AFAFAF"></path></svg> <span class="menu-nav__title">Корзина</span> <!----></a></li>
					<li class="menu-nav__item"><a href="/personal/" class="menu-nav__link <?if(CSite::InDir('/personal/')) {?>menu-nav__link_active <?}?>"><!----> <svg width="21" height="20" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-nav__icon-svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.5 1.75a8.25 8.25 0 00-6.35 13.517c1.447-1.48 3.392-2.701 6.35-2.701 2.957 0 4.902 1.21 6.351 2.7A8.25 8.25 0 0010.5 1.75zm5.293 14.579c-1.244-1.285-2.83-2.263-5.293-2.263-2.46 0-4.047.984-5.292 2.263A8.217 8.217 0 0010.5 18.25c2.014 0 3.86-.722 5.293-1.921zM.75 10C.75 4.615 5.115.25 10.5.25s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S.75 15.385.75 10zm9.75-4.46a2.566 2.566 0 100 5.131 2.566 2.566 0 000-5.132zM6.434 8.104a4.066 4.066 0 118.132 0 4.066 4.066 0 01-8.132 0z" fill="#AFAFAF"></path></svg> <span class="menu-nav__title">Профиль</span> <!----></a></li>
					<li class="menu-nav__item"><a href="javascript:;" class="menu-nav__link" id="up-to-top"><!----> <svg width="17" height="20" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-nav__icon-svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.25 8.5c0-3.778 2.846-6.75 6.25-6.75s6.25 2.972 6.25 6.75c0 2.714-1.595 5.161-3.304 6.988a20.769 20.769 0 01-2.958 2.6 20.732 20.732 0 01-2.946-2.6C3.84 13.663 2.25 11.217 2.25 8.501zm5.83 11.13l.407-.63.405.631a.75.75 0 01-.811 0zm0 0l.407-.63c.405.631.406.63.406.63h.002l.005-.004.019-.012.065-.043.236-.162a22.277 22.277 0 003.322-2.896c1.797-1.922 3.708-4.726 3.708-8.013 0-4.507-3.422-8.25-7.75-8.25C4.172.25.75 3.993.75 8.5c0 3.286 1.904 6.09 3.695 8.012a22.22 22.22 0 003.31 2.896 13.497 13.497 0 00.3.205l.018.012.006.004.002.001zM6.25 8a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0zM8.5 4.25a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5z" fill="#AFAFAF"></path></svg> <span class="menu-nav__title">Вверх</span> <!----></a></li>
				</ul>
			</nav>*/
			?>
<section class="viewed">
    <button type="button" class="toTop" id="to-top"></button>
    <div class="container viewed__container" style="display: none">
        <h6 class="h6 viewed__title">Ранее вы смотрели</h6>
        <div class="viewed__grid">
            <a href="#" class="product-item product-item--small">
                <img src="images/dist/yen6szftdwt7fkgc4ujv9slvlrj43j20.jpeg" class="product-item__img" width="250" height="250" loading="lazy" alt="">
                <span class="product-item__title">Вешало для мяса Кобор ВПМ-80/100</span>
            </a>
        </div>
        <!-- /.viewed__grid -->
    </div>
    <!-- /.container viewed__container -->
</section>
<!-- /.viewed -->
<nav class="fixed-menu">
    <a href="" class="fixed-menu__link">
        <svg class="fixed-menu__svg"><use xlink:href="#main"></use></svg>Главная
    </a>
    <a href="" class="fixed-menu__link">
        <svg class="fixed-menu__svg"><use xlink:href="#catalog"></use></svg>Каталог
    </a>
    <a href="" class="fixed-menu__link">
        <svg class="fixed-menu__svg"><use xlink:href="#basket"></use></svg>Корзина
    </a>
    <a href="" class="fixed-menu__link">
        <svg class="fixed-menu__svg"><use xlink:href="#profil"></use></svg>Профиль
    </a>
    <a href="" class="fixed-menu__link">
        <svg class="fixed-menu__svg"><use xlink:href="#place-top"></use></svg>Вверх
    </a>
</nav>
<footer class="footer">
    <div class="container footer__container">
        <div class="footer__left">
            <a href="/" class="logo footer__logo"><img src="/images/dist/footer-logo.svg" alt="RuOborudovanie.ru"></a>
            <div class="copyright">
                2023 © RuOborudovanie <br>
                Интернет-гипермаркет промышленного оборудования
                <br><br>
                Все права защищены.
            </div>
            <div class="subscribe">

                <h5 class="h5 subscribe__title">Будьте всегда в курсе!</h5>
                <div class="subscribe__text">Узнавайте о скидках и акциях первым</div>
                <form action="/personal/subscribe/" class="subscribe__form">
                    <input type="email" class="subscribe__input" placeholder="Ваш Email" required>
                    <button type="submit" class="subscribe__submit"><svg><use xlink:href="#arrow-next-long"></use></svg></button>
                </form>
            </div>
        </div>
        <nav class="footer__nav">
            <div class="footer__nav-item">
                <h4 class="h4">
                    <a href="#" class="footer__nav-link">Компания</a>
                </h4>
                <ul class="submenu">
                    <li><a href="/catalog/">Каталог</a></li>
                    <li><a href="/company/">О компании</a></li>
                    <li><a href="/contacts/">Контакты</a></li>
                </ul>
            </div>
            <div class="footer__nav-item">
                <h4 class="h4">
                    <a href="#" class="footer__nav-link">Помощь</a>
                </h4>
                <ul class="submenu submenu--two">
                    <li><a href="/help/">Помощь</a></li>
                    <li><a href="/help/payment/">Условия оплаты</a></li>
                    <li><a href="/help/delivery/">Условия доставки</a></li>
                    <li><a href="/help/warranty/">Гарантия на товар</a></li>
                    <li><a href="/info/">Информация</a></li>
                    <li><a href="/info/faq/">Вопрос-ответ</a></li>
                    <li><a href="/info/brands/">Производители</a></li>
                    <li><a href="/info/articles/">Новости</a></li>
                </ul>
            </div>
        </nav>

        <div class="footer__right">
            <div id="phone-container-footer">
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
                var phoneContainer = document.getElementById('phone-container-footer');

                // Проверяем, что элемент существует
                if (phoneContainer) {
                    // Находим все ссылки (элементы "a") внутри "phone-container"
                    var links = phoneContainer.getElementsByTagName('a');

                    // Перебираем найденные ссылки и добавляем им класс "header__phone"
                    for (var i = 0; i < links.length; i++) {
                        links[i].classList.add('footer__right-phone');
                    }
                }

            </script>
            <a href="mailto:info@ruoborudovanie.ru" class="footer__right-email">info@ruoborudovanie.ru</a>
            <a class="footer__right-callBtn callback_btn" href=""><?=GetMessage("CALLBACK")?></a>

            <div class="footer__worktime">
                График работы: Пн-Пт: 9.00-18.00<br> <span>Сб-Вс: выходной</span>
            </div>
            <div class="footer__location">
                125212, Москва, Кронштадтский б-р, 7а, БЦ Кронштадтский
            </div>



            <div class="social">
                <!--noindex--><a href="https://vk.com/ruoborudovanie" target="_blank" title="ВКонтакте" class="social__link" rel="nofollow"><img src="/images/dist/vk.png" alt=""></a><!--/noindex-->
                <!--noindex--><a href="https://ok.ru/profile/585046656918" target="_blank" title="Одноклассники" class="social__link" rel="nofollow"><img src="/images/dist/ok.png" alt=""></a><!--/noindex-->
                <!--<a href="" target="_blank" title="" class="social__link" rel="nofollow"><img src="/images/dist/facebook.png" alt=""></a>/-->
                <!--<a href="" target="_blank" title="" class="social__link" rel="nofollow"><img src="/images/dist/insta.png" alt=""></a>/noindex-->
                <!--noindex--><a href="https://t.me/+jEJ-Uw24owwwYzYy" target="_blank" title="TELEGRAM" class="social__link" rel="nofollow"><img src="/images/dist/tg.png" alt=""></a><!--/noindex-->
                <!--noindex--><a href="https://www.viber.com/ruoborudovanie" target="_blank" title="VIBER" class="social__link" rel="nofollow"><img src="/images/dist/viber.png" alt=""></a><!--/noindex-->
            </div>
        </div>
    </div>
    <!-- /.container footer__container -->
</footer>
<div class="widget">
<script>(function () { var widget = document.createElement('script'); widget.dataset.pfId = '8a00e875-c172-429f-8b2c-acc9fce15fc9'; widget.src = 'https://widget.profeat.team/script/widget.js?id=8a00e875-c172-429f-8b2c-acc9fce15fc9&now='+Date.now(); document.head.appendChild(widget); })()</script>

			</div>
<!-- <script src="https://regmarkets.ru/js/r17.js" async type="text/javascript"></script> -->
			<!-- ROISTAT START-->
			<script>
(function(w, d, s, h, id) {
		w.roistatProjectId = id; w.roistatHost = h;
		var p = d.location.protocol == "https:" ? "https://" : "http://";
		var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init?referrer="+encodeURIComponent(d.location.href);
		var js = d.createElement(s); js.charset="UTF-8"; js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
})(window, document, 'script', 'cloud.roistat.com', 'a54e230f76b464793ebe8c67e29bdebb');
</script>
<!-- ROISTAT END-->
<!-- BEGIN WHATSAPP INTEGRATION WITH ROISTAT -->
<script class="js-whatsapp-message-container">"Обязательно отправьте это сообщение и дождитесь ответа. Ваш номер: {roistat_visit}"</script>
<script>
    (function() {
        if (window.roistat !== undefined) {
            handler();
        } else {
            var pastCallback = typeof window.onRoistatAllModulesLoaded === "function" ? window.onRoistatAllModulesLoaded : null;
            window.onRoistatAllModulesLoaded = function () {
                if (pastCallback !== null) {
                    pastCallback();
                }
                handler();
            };
        }

        function handler() {
            function init() {
                appendMessageToLinks();

                var delays = [1000, 5000, 15000];
                setTimeout(function func(i) {
                    if (i === undefined) {
                        i = 0;
                    }
                    appendMessageToLinks();
                    i++;
                    if (typeof delays[i] !== 'undefined') {
                        setTimeout(func, delays[i], i);
                    }
                }, delays[0]);
            }

            function replaceQueryParam(url, param, value) {
                var explodedUrl = url.split('?');
                var baseUrl = explodedUrl[0] || '';
                var query = '?' + (explodedUrl[1] || '');
                var regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?");
                var queryWithoutParameter = query.replace(regex, "$1").replace(/&$/, '');
                return baseUrl + (queryWithoutParameter.length > 2 ? queryWithoutParameter  + '&' : '?') + (value ? param + "=" + value : '');
            }

            function appendMessageToLinks() {
                var message = document.querySelector('.js-whatsapp-message-container').text;
                var text = message.replace(/{roistat_visit}/g, window.roistatGetCookie('roistat_visit'));
                text = encodeURI(text);
                var linkElements = document.querySelectorAll('[href*="//wa.me"], [href*="//api.whatsapp.com/send"], [href*="//web.whatsapp.com/send"], [href^="whatsapp://send"]');
                for (var elementKey in linkElements) {
                    if (linkElements.hasOwnProperty(elementKey)) {
                        var element = linkElements[elementKey];
                        element.href = replaceQueryParam(element.href, 'text', text);
                    }
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        };
    })();
</script>
<!-- END WHATSAPP INTEGRATION WITH ROISTAT -->
		</footer>
		<?
		COptimus::setFooterTitle();
		COptimus::showFooterBasket();
		?>
<div class="overlay js-overlay"></div>
<div class="popup js-popup" id="question-1">
    <svg class="popup__close js-close"><use xlink:href="#close"></use></svg>
    <h6 class="h6 popup__title">Стандарт Wi-Fi</h6>
    <a data-fancybox href="https://youtu.be/fPhWuENLz8s?feature=shared"><img src="/images/dist/youtube-placeholder.jpg" alt=""></a>
    <div class="popup__text">
        Поддерживаемые точкой доступа стандарты Wi-Fi определяют возможную скорость интернет-соединения и совместимые модели сетевых устройств. На рынке представлены роутеры с поддержкой устаревающего стандарта Wi-Fi 4, широко распространенного Wi-Fi 5 и передового Wi-Fi 6.
    </div>
</div>
<div class="popup js-popup" id="popup-price">
    <svg class="popup__close js-close"><use xlink:href="#close"></use></svg>
    <h6 class="h6 popup__title" id="popup__price-title">Электрическая варочная поверхность MAUNFELD EEHE.32.4B</h6>
    <div class="popup__descript">
        <div class="popup__price"><strong>Стоимость товара:</strong><span class="popup__price-total"  id="popup__price-price">6 299 ₽</span></div>
        <div class="popup__history" style="display: none">
            <strong class="popup__history-title">История цены</strong>
            <span class="popup__history-small">от 6 299 ₽ до 7 999 ₽</span>
            <canvas id="myChart"></canvas>
            <div class="popup__hint">За прошедшие недели отображается средняя цена продажи</div>
        </div>

        <div class="popup__credit">
            <div class="popup__credit-item">В кредит</div>
            <div class="popup__credit-item">от <span id="popup__price-credit">614</span> ₽/ мес. <svg class="popup__credit-svg" id="tippy-1"><use xlink:href="#question"></use></svg></div>
        </div>
        <div class="popup__accordeon">
            <div class="popup__accordeon-item">
                <div class="popup__accordeon-title js-popup-accordeon">Как оформить кредит в магазине <span class="popup__accordeon-icon"><svg><use xlink:href="#angle-down"></use></svg></span></div>
                <div class="popup__accordeon-answear">
                    <p>Условия кредитования банки устанавливают в индивидуальном порядке.</p>
                    <p>Подробную информацию можно получить на горячей линии выбранной кредитной организации.</p>
                </div>
            </div>
            <div class="popup__accordeon-item">
                <div class="popup__accordeon-title js-popup-accordeon">Как оформить кредит в магазине <span class="popup__accordeon-icon"><svg><use xlink:href="#angle-down"></use></svg></span></div>
                <div class="popup__accordeon-answear">
                    <p>Условия кредитования банки устанавливают в индивидуальном порядке.</p>
                    <p>Подробную информацию можно получить на горячей линии выбранной кредитной организации.</p>
                </div>
            </div>
        </div>
        <div class="popup__logos">
            <img src="/images/dist/logo-bank-1.jpg" alt="">
            <img src="/images/dist/logo-bank-1.jpg" alt="">
        </div>
    </div>
</div>
<nav class="fixed-menu">
    <a href="/" class="fixed-menu__link">
        <svg class="fixed-menu__svg"><use xlink:href="#main"></use></svg>Главная
    </a>
    <a href="/catalog/" class="fixed-menu__link">
        <svg class="fixed-menu__svg"><use xlink:href="#catalog"></use></svg>Каталог
    </a>
    <a href="/basket/" class="fixed-menu__link">
        <svg class="fixed-menu__svg"><use xlink:href="#basket"></use></svg>Корзина
    </a>
    <a href="/personal/" class="fixed-menu__link">
        <svg class="fixed-menu__svg"><use xlink:href="#profil"></use></svg>Профиль
    </a>
    <a href="" class="fixed-menu__link" id="customLink">
        <svg class="fixed-menu__svg"><use xlink:href="#place-top"></use></svg>Вверх
    </a>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var link = document.getElementById('customLink');
        var button = document.getElementById('to-top');

        link.addEventListener('click', function(event) {
            event.preventDefault(); // Предотвращение стандартного действия ссылки
            button.click(); // Имитация клика по кнопке
        });
    });

</script>
<!--<script src="/dist/js/jquery.fancybox.min.js"></script>/-->
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script src="/dist/js/ion.rangeSlider.min.js"></script>
<script src="/dist/js/slick.min.js"></script>
<script src="/dist/js/cdn.jsdelivr.net_npm_chart.js"></script>
<script src="/dist/js/app.js"></script>

<script src="https://api.esplc.ru/widgets/form-order/app.js"></script>
<script src="https://api.esplc.ru/widgets/modal/app.js"></script>

	</body>

</html>
