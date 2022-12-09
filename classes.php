<?php

class Etudiant
{
    const MAX_LEN = 1048576;
    private mysqli $dbObj;
    private $table_name = "etudiant";
    function __construct($annee)
    {
        require_once "db_connection.php";
        $this->dbObj = $db;
        $this->table_name .= "_$annee";
    }

    function add_multi_records(array $statementsValues)
    {
        if ($statementsValues == []) {
            $this->dbObj->close();
            return;
        }

        $query = "INSERT INTO " . $this->table_name . " VALUES";

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

    function create_table()
    {
        $this->dbObj->query("DROP TABLE " . $this->table_name);
        $this->dbObj->query("USE affectation_etudiants;");
        $this->dbObj->query(
            <<<heredoc
        CREATE TABLE $this->table_name(
            matricule VARCHAR(255) NOT NULL PRIMARY KEY,
            nom_prenom VARCHAR(255) NOT NULL,
            MGC DECIMAL(4, 2) NOT NULL,
            ordre_GL INT NOT NULL,
            ordre_GI INT NOT NULL,
            ordre_RT INT NOT NULL,
            voeu_affecte VARCHAR(255) DEFAULT NULL,
            satisfaction VARCHAR(255) DEFAULT "non satisfait",
            choisit INT NOT NULL DEFAULT 0,
            mot_de_passe VARCHAR(8) NOT NULL DEFAULT "e_jkZe23",
            CONSTRAINT check_ordre_GL CHECK(ordre_GL IN (0, 1, 2, 3)),
            CONSTRAINT check_ordre_GI CHECK(ordre_GI IN (0, 1, 2, 3)),
            CONSTRAINT check_ordre_RT CHECK(ordre_RT IN (0, 1, 2, 3)),
            CONSTRAINT check_voeu CHECK(voeu_affecte IN ("GL", "GI", "RT")),
            CONSTRAINT check_satisfaction CHECK(satisfaction IN ("satisfait", "non satisfait")),
            CONSTRAINT check_mgc CHECK(mgc <= 20.00),
            CONSTRAINT check_choisit CHECK(choisit IN (0, 1))
        );
        heredoc
        );
    }
}
