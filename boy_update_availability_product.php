<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	//phpinfo();
?>

	<div style="clear: both;">&nbsp;</div>

<?
    // Создание массива всех активных товаров
    $arSelect = Array(
        "ID", 
        "IBLOCK_ID",
        "NAME", 
        "DATE_ACTIVE_FROM",
        "PROPERTY_PRODUCTINSTOCK",
        "QUANTITY",
    );
    $arFilter = Array("IBLOCK_ID"=>14, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(
        Array(),
        $arFilter,
        false,
        Array(),
        $arSelect
    );

    // Массив с товарами, имеющий затребованные параметры
    $arProd = array();
    $ind = 0;
    while($ob = $res->GetNextElement()){
        $arFields[$ind] = $ob->GetFields();
        $arProd[] = $arFields[$ind];
    }

    // Обновление свойства PRODUCTINSTOCK у товаров в соответствии с их значением QUANTITY
    $arr = $arProd;
    $sovpadenie = 0;
    foreach ($arr as $key => $value) {
        if(($value["PROPERTY_PRODUCTINSTOCK_ENUM_ID"] == 187261) && ($value["QUANTITY"] > 0)){
            $sovpadenie = 1;
        } elseif (($value["PROPERTY_PRODUCTINSTOCK_ENUM_ID"] == 187336) && ($value["QUANTITY"] > 0)){
            $sovpadenie = 0;
        } elseif (($value["PROPERTY_PRODUCTINSTOCK_ENUM_ID"] == 187261) && ($value["QUANTITY"] == 0)){
            $sovpadenie = 0;
        } elseif (($value["PROPERTY_PRODUCTINSTOCK_ENUM_ID"] == 187336) && ($value["QUANTITY"] == 0)){
            $sovpadenie = 1;
        }
        if($sovpadenie == 0){
            if($value["QUANTITY"] > 0 ) {
                CIBlockElement::SetPropertyValues($value['ID'], 14, 187261, "PRODUCTINSTOCK");
              } else {
                CIBlockElement::SetPropertyValues($value['ID'], 14, 187336, "PRODUCTINSTOCK");
            }
        }

        echo "<br />{$key} => {$value["NAME"]} => {$value["QUANTITY"]}<br />";
        echo "Товар[{$value["ID"]}]<br />";
        echo "PROPERTY_PRODUCTINSTOCK_VALUE[{$value["PROPERTY_PRODUCTINSTOCK_VALUE"]}]<br />";
        echo "PROPERTY_PRODUCTINSTOCK_ENUM_ID[{$value["PROPERTY_PRODUCTINSTOCK_ENUM_ID"]}]<br />";
    }


	//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>