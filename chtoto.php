<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?
if (CModule::IncludeModule("iblock")):
// ID инфоблока из которого выводим элементы
$iblock_id = 14;
$my_slider = CIBlockElement::GetList (
// Сортировка элементов
Array("ID" => "ASC"),
Array("IBLOCK_ID" => $iblock_id ),
false,
false,
// Перечисляесм все свойства элементов, которые планируем выводить
Array(
'ID', 
'NAME'
)
);
while($ar_fields = $my_slider->GetNext())
{
//Выводим элемент со всеми свойствами + верстка
$text = $ar_fields['NAME'];
$new_text = explode(' ',$text);
	if($new_text[0]=="Автоклав"/* && $new_text[1]=="суповая" */){ $new_text[0]="Автоклавов";//$new_text[1]="суповых";
$text_new = implode(" ", $new_text);
$ELEMENT_ID = $ar_fields['ID'];  // код элемента
$PROPERTY_CODE = "NAME_RP";  // код свойства
$PROPERTY_VALUE = $text_new;  // значение свойства

// Установим новое значение для данного свойства данного элемента
CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));
}
echo "готово";
}
endif;
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>