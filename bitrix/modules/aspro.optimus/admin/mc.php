<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
if($_SERVER['REQUEST_METHOD'] === 'GET'){
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');
}

?>
<?
if($_SERVER['REQUEST_METHOD'] === 'GET'){
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');
}
?>