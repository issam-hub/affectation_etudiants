<?php

declare(strict_types=1);

spl_autoload_register(function () {
    require_once "classes.php";
});

session_start();
const ACCOUNT_ERR_MSG = "NOT_CONNECTED";

if (!isset($_SESSION["agent_connected"])) {
    header("HTTP/1.0 403");
    exit();
}

header("expires: " . 0);
header("cache-control: no-cache, must-revalidate");

$statementsValues = [];

/*----------------Start Load Data From CSV File into DB--------------*/
const MAX_ROW_LEN = 10000;
const START = 15;

/**
 reference cells:
 * (matricule, nom_prenom, mgc, ordre_gl, ordre_gi, ordre_rt, choisit) E15, B15, Q15, R15, S15, T15, U15, V15
 */


if (isset($_GET["file"])) {
    $file = fopen($_GET["file"], "r");

    $keys = [];
    for ($i = "A"; $i <= "V"; $i++) {
        array_push($keys, $i);
    }

    $i = 1;
    while (($data = fgetcsv($file, MAX_ROW_LEN, ';'))) {
        if ($i++ < START)
            continue;

        $data_table_row = array_combine($keys, array_slice($data, 0, count($keys)));

        array_push($statementsValues, [
            "'" . $data_table_row["E"] . "'",
            "'" . $data_table_row["B"] . "'",
            $data_table_row["Q"],
            $data_table_row["R"],
            $data_table_row["S"],
            $data_table_row["T"],
            "DEFAULT",
            "DEFAULT",
            "'" . $data_table_row["U"] . "'",
            "'" . $data_table_row["V"] . "'"
        ]);
    }
    /*----------------Start Load Data to Database--------------*/
    try {
        $record = new Etudiant();
        $record->add_multi_records($statementsValues);
        exit();
    } catch (mysqli_sql_exception $e) {
        exit($e->getMessage());
    }
    /*----------------End Load Data From CSV File into DB--------------*/
}

/*----------------Start Send number of enrolled in speciality students --------------*/
if (isset($_GET["num_ins"])) {
    define("TABLE_NAME", "etudiant");
    require_once "db_connection.php";
    $res = $db->query("SELECT count(matricule) as num FROM " . TABLE_NAME . " WHERE choisit=1");
    $enrolled = $res->fetch_assoc()["num"];

    $res = $db->query("SELECT count(matricule) as num FROM " . TABLE_NAME . " WHERE choisit=0");
    $unenrolled = $res->fetch_assoc()["num"];

    $infos = [
        "enrolled" => $enrolled,
        "unenrolled" => $unenrolled
    ];
    echo json_encode($infos);
    exit();
}
/*----------------End Send number of enrolled in speciality students --------------*/


/*----------------Start Sending first choice (no longer needed) --------------*/
const TABLE_NAME = "etudiant";
require "db_connection.php";
function send_queue($name, $attribute)
{
    global $db;
    $queue = [];
    $res = $db->query("SELECT matricule, nom_prenom, mgc, $attribute FROM " . TABLE_NAME . " WHERE choisit=1 AND $attribute=1 ORDER BY mgc DESC;");
    $temp = [];
    while ($temp = $res->fetch_assoc()) {
        array_push($queue, $temp);
    }

    if (isset($_GET[$name])) {
        echo json_encode($queue);
    }
}

send_queue("initial_gl", "ordre_gl");

send_queue("initial_gi", "ordre_gi");

send_queue("initial_rt", "ordre_rt");
/*----------------End Sending first choice--------------*/

/*----------------Start Affectation Handling--------------*/
if (
    isset($_GET["gl_limit"]) &&
    isset($_GET["gi_limit"]) &&
    isset($_GET["rt_limit"])
) {
    $queue = [];
    $res = $db->query("SELECT matricule, nom_prenom, mgc, ordre_gl, ordre_gi, ordre_rt FROM " . TABLE_NAME . " WHERE choisit=1 ORDER BY mgc DESC;");
    $temp = [];
    while ($temp = $res->fetch_assoc()) {
        array_push($queue, array_flip($temp));
    }

    $list = $queue;

    $specs = [
        "ordre_gl" => [],
        "ordre_gi" => [],
        "ordre_rt" => [],
    ];

    $limits = [
        "ordre_gl" => $_GET["gl_limit"],
        "ordre_gi" => $_GET["gi_limit"],
        "ordre_rt" => $_GET["rt_limit"],
    ];

    $spec_map = [
        "ordre_gl" => "GL",
        "ordre_gi" => "GI",
        "ordre_rt" => "RT",
    ];

    for ($i  = 0; $i < count($list); $i++) {
        $spec_names = [];
        for ($j = 1; $j <= count($specs); $j++) {
            $spec_names[] = $list[$i][$j];
        }

        $index = 1;
        foreach ($spec_names as $spec_name) {
            if (count($specs[$spec_name]) < $limits[$spec_name]) {
                array_push($specs[$spec_name], $list[$i]);

                $matricule = array_keys($list[$i])[0];
                if ($index == 1) {
                    $db->query("UPDATE " . TABLE_NAME . " SET satisfaction='satisfait' WHERE matricule='" . $matricule . "'");
                }

                $voeu_affecte = $spec_map[$spec_name];
                $db->query("UPDATE " . TABLE_NAME . " SET voeu_affecte='" . $voeu_affecte . "' WHERE matricule='" . $matricule . "'");

                break;
            }
            $index++;
        }
    }
    exit();
}
/*----------------End Affectation Handling--------------*/

/*----------------Start Sending Final Speciality lists (no longer needed)--------------*/
if (isset($_GET["ordre_gl"])) {
    foreach ($specs["ordre_gl"] as $key => $student) {
        $student = array_flip($student);
        $specs["ordre_gl"][$key] = $student;
    }
    echo json_encode($specs["ordre_gl"]);
    exit();
}

if (isset($_GET["ordre_gi"])) {
    foreach ($specs["ordre_gi"] as $key => $student) {
        $student = array_flip($student);
        $specs["ordre_gi"][$key] = $student;
    }
    echo json_encode($specs["ordre_gi"]);
    exit();
}

if (isset($_GET["ordre_rt"])) {
    foreach ($specs["ordre_rt"] as $key => $student) {
        $student = array_flip($student);
        $specs["ordre_rt"][$key] = $student;
    }
    echo json_encode($specs["ordre_rt"]);
    exit();
}
/*----------------End Sending Final Speciality lists--------------*/


/*----------------Start Send statistics --------------*/
if (isset($_GET["stats"])) {
    $res = $db->query("SELECT * FROM " . TABLE_NAME . " WHERE choisit=1 ORDER BY MGC DESC");
    $final = [];
    while (($student = $res->fetch_assoc()) != null) {
        $nom_prenom = preg_split("/[ ]+/", $student["nom_prenom"]);

        if ($student["ordre_GL"] == 1)
            $voeu_choisit = "GL";

        if ($student["ordre_GI"] == 1)
            $voeu_choisit = "GI";

        if ($student["ordre_RT"] == 1)
            $voeu_choisit = "RT";

        $final[] = [
            "nom" => $nom_prenom[0],
            "prenom" => $nom_prenom[1],
            "matricule" => $student["matricule"],
            "voeu choisit" => $voeu_choisit,
            "voeu affecte" => $student["voeu_affecte"],
            "mgc" => $student["MGC"],
            "satisfaction" => $student["satisfaction"]
        ];
    }

    echo json_encode($final);
    exit();
}
/*----------------End Send statistics --------------*/