<?php

class Record
{
    const MAX_LEN = 1048576;
    private mysqli $dbObj;
    private string $tableName;
    function __construct(mysqli $dbObj, $tableName)
    {
        $this->dbObj = $dbObj;
        $this->tableName = $tableName;
    }

    function add_multi_records(array $statementsValues)
    {
        if ($statementsValues == [])
            return;

        $query = "INSERT INTO $this->tableName VALUES";

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
