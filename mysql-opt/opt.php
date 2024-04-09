<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

global $DB;

$queries = [
    "CREATE INDEX IDX_AVITO_EXPORT_FEED_OFFER_FEED_ID ON avito_export_feed_offer (FEED_ID)"
];

foreach ($queries as $query) {
    $result = $DB->Query($query, true);
    if ($result) {
        echo "Запрос выполнен успешно: " . $query . "<br/>";
    } else {
        echo "Ошибка при выполнении запроса: " . $query . "<br/>";
        echo "MySQL Error: " . $DB->db_Error . "<br/>";
    }
}

?>
