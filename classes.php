<?php

class Etudiant
{
    const MAX_LEN = 1048576;
    private mysqli $dbObj;
    const TABLE_NAME = "etudiant";
    function __construct()
    {
        require_once "db_connection.php";
        $this->dbObj = $db;
    }

    function add_multi_records(array $statementsValues)
    {
        if ($statementsValues == []) {
            $this->dbObj->close();
            return;
        }

        $query = "INSERT INTO " . self::TABLE_NAME . " VALUES";

        while (count($statementsValues)) {
            $statementValues = $statementsValues[0];
            $values = "(" . implode(", ", $statementValues) . "),";

            if (strlen($query) + strlen($values) > self::MAX_LEN) {
                break;
            }

            $query .= $values;
            array_shift($statementsValues);
        }

        $query[strlen($query) - 1] = ";";
        echo "$query<br>";
        $this->dbObj->query($query);
        $this->add_multi_records($statementsValues);
    }
}
