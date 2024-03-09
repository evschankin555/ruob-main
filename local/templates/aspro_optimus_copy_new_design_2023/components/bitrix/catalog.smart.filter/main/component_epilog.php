<?php
if(\Bitrix\Main\Loader::includeModule('webdebug.seo')){
  \WD\Seo\SmartFilter\AutoSeo::set($arResult, $arParams);
}
