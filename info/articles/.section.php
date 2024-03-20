<?
if(isset($_GET['test']) && $_GET['test'] == 1){
    $sSectionName = "Новости";
    $arDirProperties = array(
        "description" => "Новости от RuOborudovanie.ru"
    );
}else{
    $sSectionName = "Статьи";
    $arDirProperties = array(
        "description" => "Статьи с интересными новостями от RuOborudovanie.ru"
    );
}
?>