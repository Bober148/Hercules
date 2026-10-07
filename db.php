<?php
// Единая точка подключения к БД. Подключать через require_once, затем $conn = db();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db(): mysqli {
    try {
        $conn = new mysqli("localhost", "root", "", "hercules");
        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (mysqli_sql_exception $e) {
        error_log("DB connection error: " . $e->getMessage());
        http_response_code(500);
        exit("Ошибка соединения с базой данных.");
    }
}
