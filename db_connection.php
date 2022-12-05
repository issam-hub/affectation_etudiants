<?php

const HOSTNAME = "localhost";
const USERNAME = "root";
const PASSWORD = "";
const DB_NAME = "affectation_etudiants";

try {
    $db = new mysqli(HOSTNAME, USERNAME, PASSWORD, DB_NAME);

    if ($db->connect_error) {
        throw new mysqli_sql_exception();
    }
} catch (mysqli_sql_exception $e) {
    echo $e->getMessage();
}
