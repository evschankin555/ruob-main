<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") die();?>
<?IncludeTemplateLangFile(__FILE__);?>

							<?if(!COptimus::IsMainPage()):?>
								</div> <?// .container?>
							<?endif;?>
						</div>
					<?if(!COptimus::IsOrderPage() && !COptimus::IsBasketPage()):?>
						</div> <?// .right_block?>
					<?endif;?>
				</div> <?// .wrapper_inner?>
			</div> <?// #content?>
		</div><?// .wrapper?>
		<nav class="menu-nav">
				<ul class="menu-nav__list">
					<li class="menu-nav__item"><a href="/" class="menu-nav__link <?if($APPLICATION->GetCurDir() === '/') {?>menu-nav__link_active <?}?>"><!----> <svg width="19" height="20" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-nav__icon-svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.397 2.481a2.25 2.25 0 012.206 0l5 2.812a2.25 2.25 0 011.147 1.962V16a2.25 2.25 0 01-2.25 2.25h-1.25V13a2.75 2.75 0 00-2.75-2.75h-2A2.75 2.75 0 005.75 13v5.25H4.5A2.25 2.25 0 012.25 16V7.255a2.25 2.25 0 011.147-1.962l5-2.812zM11.75 18.25h-4.5V13c0-.69.56-1.25 1.25-1.25h2c.69 0 1.25.56 1.25 1.25v5.25zm-6 1.5H4.5A3.75 3.75 0 01.75 16V7.255a3.75 3.75 0 011.912-3.269l5-2.812a3.75 3.75 0 013.676 0l5 2.812a3.75 3.75 0 011.912 3.269V16a3.75 3.75 0 01-3.75 3.75H5.75z" fill="#AFAFAF"></path></svg> <span class="menu-nav__title">Главная</span> <!----></a></li>
					<li class="menu-nav__item"><a href="/catalog/" class="menu-nav__link <?if(CSite::InDir('/catalog/')) {?>menu-nav__link_active <?}?>"><!----> <svg width="24" height="17" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-nav__icon-svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.5.25a.75.75 0 000 1.5h13a.75.75 0 000-1.5h-13zm9.75 8.77a4.27 4.27 0 117.299 3.01l-.01.01-.008.009a4.27 4.27 0 01-7.28-3.029zm10.04 0a5.745 5.745 0 01-1.194 3.515l2.934 2.935a.75.75 0 01-1.06 1.06l-2.934-2.934A5.77 5.77 0 1121.29 9.02zM.75 7a.75.75 0 01.75-.75h4a.75.75 0 010 1.5h-4A.75.75 0 01.75 7zm.75 5.25a.75.75 0 000 1.5h6a.75.75 0 000-1.5h-6z" fill="#AFAFAF"></path></svg> <span class="menu-nav__title">Каталог</span> <!----></a></li>
					<li class="menu-nav__item"><a href="/basket/" class="menu-nav__link <?if(CSite::InDir('/basket/')) {?>menu-nav__link_active <?}?>"><!----> <svg width="20" height="19" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-nav__icon-svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M.75 1A.75.75 0 011.5.25h1.382a1.75 1.75 0 011.565.967l-.67.336.67-.336.517 1.033h13.674a1.25 1.25 0 011.086 1.87l-3.285 5.748a1.75 1.75 0 01-1.52.882H7.417l-1.324 2.117a.25.25 0 00.212.383H17.5a.75.75 0 010 1.5H6.304c-1.374 0-2.212-1.512-1.484-2.678L6.172 9.91 3.809 3.295l-.703-1.407a.25.25 0 00-.224-.138H1.5A.75.75 0 01.75 1zm4.814 2.75l1.965 5.5h7.39a.25.25 0 00.218-.126l3.07-5.374H5.565zM8.5 17.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM16 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" fill="#AFAFAF"></path></svg> <span class="menu-nav__title">Корзина</span> <!----></a></li>
					<li class="menu-nav__item"><a href="/personal/" class="menu-nav__link <?if(CSite::InDir('/personal/')) {?>menu-nav__link_active <?}?>"><!----> <svg width="21" height="20" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-nav__icon-svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.5 1.75a8.25 8.25 0 00-6.35 13.517c1.447-1.48 3.392-2.701 6.35-2.701 2.957 0 4.902 1.21 6.351 2.7A8.25 8.25 0 0010.5 1.75zm5.293 14.579c-1.244-1.285-2.83-2.263-5.293-2.263-2.46 0-4.047.984-5.292 2.263A8.217 8.217 0 0010.5 18.25c2.014 0 3.86-.722 5.293-1.921zM.75 10C.75 4.615 5.115.25 10.5.25s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S.75 15.385.75 10zm9.75-4.46a2.566 2.566 0 100 5.131 2.566 2.566 0 000-5.132zM6.434 8.104a4.066 4.066 0 118.132 0 4.066 4.066 0 01-8.132 0z" fill="#AFAFAF"></path></svg> <span class="menu-nav__title">Профиль</span> <!----></a></li>
					<li class="menu-nav__item"><a href="javascript:;" class="menu-nav__link" id="up-to-top"><!----> <svg width="17" height="20" fill="none" xmlns="http://www.w3.org/2000/svg" class="menu-nav__icon-svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.25 8.5c0-3.778 2.846-6.75 6.25-6.75s6.25 2.972 6.25 6.75c0 2.714-1.595 5.161-3.304 6.988a20.769 20.769 0 01-2.958 2.6 20.732 20.732 0 01-2.946-2.6C3.84 13.663 2.25 11.217 2.25 8.501zm5.83 11.13l.407-.63.405.631a.75.75 0 01-.811 0zm0 0l.407-.63c.405.631.406.63.406.63h.002l.005-.004.019-.012.065-.043.236-.162a22.277 22.277 0 003.322-2.896c1.797-1.922 3.708-4.726 3.708-8.013 0-4.507-3.422-8.25-7.75-8.25C4.172.25.75 3.993.75 8.5c0 3.286 1.904 6.09 3.695 8.012a22.22 22.22 0 003.31 2.896 13.497 13.497 0 00.3.205l.018.012.006.004.002.001zM6.25 8a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0zM8.5 4.25a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5z" fill="#AFAFAF"></path></svg> <span class="menu-nav__title">Вверх</span> <!----></a></li>
				</ul>
			</nav>
		<footer id="footer">
			<div class="footer_inner <?=strtolower($TEMPLATE_OPTIONS["BGCOLOR_THEME_FOOTER_SIDE"]["CURRENT_VALUE"]);?>">

				<?if($APPLICATION->GetProperty("viewed_show")=="Y" || defined("ERROR_404")):?>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
						array(
							"COMPONENT_TEMPLATE" => ".default",
							"PATH" => SITE_DIR."include/footer/comp_viewed.php",
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "",
							"AREA_FILE_RECURSIVE" => "Y",
							"EDIT_TEMPLATE" => "standard.php"
						),
						false
					);?>
				<?endif;?>
				<div class="wrapper_inner">
					<div class="footer_bottom_inner">
						<div class="left_block">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/footer/copyright.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>
							<div id="bx-composite-banner"></div>
						</div>
						<div class="right_block">
							<div class="middle">
								<div class="rows_block">
									<div class="item_block col-75 menus">
										<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu_top", array(
											"ROOT_MENU_TYPE" => "bottom",
											"MENU_CACHE_TYPE" => "Y",
											"MENU_CACHE_TIME" => "3600000",
											"MENU_CACHE_USE_GROUPS" => "N",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "1",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
											),false
										);?>
										<div class="rows_block">
											<div class="item_block col-3">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_company",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "3600000",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="item_block col-3">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_info",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "3600000",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="item_block col-3">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_help",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "3600000",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
										</div>
									</div>
									<div class="item_block col-4 soc">
										<div class="soc_wrapper">
											<div class="phones">
												<div class="phone_block">
													<span class="phone_wrap">
														<span class="icons fa fa-phone"></span>
														<span>
															<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
																array(
																	"COMPONENT_TEMPLATE" => ".default",
																	"PATH" => SITE_DIR."include/phone.php",
																	"AREA_FILE_SHOW" => "file",
																	"AREA_FILE_SUFFIX" => "",
																	"AREA_FILE_RECURSIVE" => "Y",
																	"EDIT_TEMPLATE" => "standard.php"
																),
																false
															);?>
														</span>
													</span>
													<span class="order_wrap_btn">
														<span class="callback_btn"><?=GetMessage('CALLBACK')?></span>
													</span>
												</div>
											</div>
											<div class="social_wrapper">
												<div class="social">
													<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
														array(
															"COMPONENT_TEMPLATE" => ".default",
															"PATH" => SITE_DIR."include/footer/social.info.optimus.default.php",
															"AREA_FILE_SHOW" => "file",
															"AREA_FILE_SUFFIX" => "",
															"AREA_FILE_RECURSIVE" => "Y",
															"EDIT_TEMPLATE" => "standard.php"
														),
														false
													);?>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="mobile_copy">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
							array(
								"COMPONENT_TEMPLATE" => ".default",
								"PATH" => SITE_DIR."include/footer/copyright.php",
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "",
								"AREA_FILE_RECURSIVE" => "Y",
								"EDIT_TEMPLATE" => "standard.php"
							),
							false
						);?>
					</div>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include1.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_1"))); ?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include2.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_2"))); ?>
				</div>
			</div>
<script src="https://regmarkets.ru/js/r17.js" async type="text/javascript"></script>
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
<script type="text/javascript"> (function ab(){ var request = new XMLHttpRequest(); request.open('GET', "https://scripts.botfaqtor.ru/one/34907", false); request.send(); if(request.status == 200) eval(request.responseText); })(); </script>
<!-- Rating Mail.ru counter -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "3251550", type: "pageView", start: (new Date()).getTime(), pid: "USER_ID"});
(function (d, w, id) {
  if (d.getElementById(id)) return;
  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
  ts.src = "https://top-fwz1.mail.ru/js/code.js";
  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "topmailru-code");
</script><noscript><div>
<img src="https://top-fwz1.mail.ru/counter?id=3251550;js=na" style="border:0;position:absolute;left:-9999px;" alt="Top.Mail.Ru" />
</div></noscript>
<!-- //Rating Mail.ru counter -->

<!-- Rating@Mail.ru counter dynamic remarketing appendix -->
<script type="text/javascript">
var _tmr = _tmr || [];
_tmr.push({
    type: 'itemView',
    productid: '$product.id',
    pagetype: 'product',
    list: '3',
    totalvalue: 'price'
});
</script>
<!-- // Rating@Mail.ru counter dynamic remarketing appendix -->
<script>(function () { var widget = document.createElement('script'); widget.dataset.pfId = 'f5bccfd0-fcd3-4663-9fc1-10a8d13b4deb'; widget.src = 'https://widget.profeat.team/script/widget.js?id=f5bccfd0-fcd3-4663-9fc1-10a8d13b4deb&now='+Date.now(); document.head.appendChild(widget); })()</script>
		</footer>
		<?
		COptimus::setFooterTitle();
		COptimus::showFooterBasket();
		?>
	</body>

</html>
