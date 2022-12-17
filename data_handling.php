<?php

declare(strict_types=1);
const TABLE_NAME = "etudiant";

spl_autoload_register(function () {
    require_once "classes.php";
});

/*----------------Start Send list of archived data years --------------*/

if (isset($_GET["annees_list"])) {
    require_once "db_connection.php";
    $res = $db->query("SELECT annee FROM nombre_places ORDER BY  SUBSTRING(annee,6) DESC");
    // $count = [];
    while (($count[] = $res->fetch_assoc())) {
    }
    array_pop($count);
    echo json_encode($count);
    exit();
}

/*----------------End Send list of archived data years --------------*/

/*----------------Start Affectation list by speciality--------------*/

if (
    isset($_GET["gl"]) &&
    isset($_GET["gi"]) &&
    isset($_GET["rt"]) &&
    isset($_GET["annee"])
) {
    require_once "db_connection.php";
    $annee = $_GET["annee"];
    // affectation($annee);
    $res = $db->query("SELECT nom_prenom FROM " . TABLE_NAME . "_$annee" . " WHERE voeu_affecte='gl'");
    $gl = [];
    while ($etudiant = $res->fetch_assoc()) {
        $gl[] = $etudiant;
    }
    $res = $db->query("SELECT nom_prenom FROM " . TABLE_NAME . "_$annee" . " WHERE voeu_affecte='gi'");
    $gi = [];
    while ($etudiant = $res->fetch_assoc()) {
        $gi[] = $etudiant;
    }
    $res = $db->query("SELECT nom_prenom FROM " . TABLE_NAME . "_$annee" . " WHERE voeu_affecte='rt'");
    $rt = [];
    while ($etudiant = $res->fetch_assoc()) {
        $rt[] = $etudiant;
    }

    echo json_encode([$gl, $gi, $rt]);
    exit();
}

/*----------------End Affectation list by speciality--------------*/

session_start();
const ACCOUNT_ERR_MSG = "NOT_CONNECTED";

if (!isset($_SESSION["agent_connected"])) {
    header("HTTP/1.0 403");
    exit();
}

header("expires: " . 0);
header("cache-control: no-cache, must-revalidate");

$statementsValues = [];
$annee = "2022_2023";

/*----------------Start Load Data From CSV File into DB--------------*/
const MAX_ROW_LEN = 10000;
const START = 15;

/**
 reference cells:
 * (matricule, nom_prenom, mgc, ordre_gl, ordre_gi, ordre_rt, choisit) E15, B15, Q15, R15, S15, T15, U15, V15
 */

if (
    isset($_GET["file"]) &&
    isset($_GET["annee"])
) {


    $file = fopen("data csv files/" . $_GET["file"], "r");

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
        $record = new Etudiant($_GET["annee"]);
        $record->create_table();
        $record->add_multi_records($statementsValues);
    } catch (mysqli_sql_exception $e) {
        echo $e->getMessage();
    }
    /*----------------End Load Data From CSV File into DB--------------*/
    header("Location: queue.html");
    exit();
}
require_once "db_connection.php";

/*----------------Start Send number of enrolled in speciality students --------------*/
if (
    isset($_GET["num_ins"]) &&
    isset($_GET["annee"])
) {
    $annee = $_GET["annee"];
    require_once "db_connection.php";
    $res = $db->query("SELECT count(matricule) as num FROM " . TABLE_NAME . "_$annee" . " WHERE choisit=1");
    $enrolled = $res->fetch_assoc()["num"];

    $res = $db->query("SELECT count(matricule) as num FROM " . TABLE_NAME . "_$annee" . " WHERE choisit=0");
    $unenrolled = $res->fetch_assoc()["num"];

    $infos = [
        "enrolled" => $enrolled,
        "unenrolled" => $unenrolled
    ];
    echo json_encode($infos);
    exit();
}
/*----------------End Send number of enrolled in speciality students --------------*/

/*----------------Start Affectation Handling--------------*/

require_once "db_connection.php";
function affectation($annee)
{
    // define("TABLE_NAME", "etudiant");
    global $db;
    $db->query("UPDATE " . TABLE_NAME . "_$annee" . " SET satisfaction='non satisfait', voeu_affecte=NULL");

    define("nombre_places", "nombre_places");
    $res = $db->query("SELECT gl, gi, rt FROM " . nombre_places . " WHERE annee='$annee'");
    $limits = $res->fetch_assoc();
    $gl_limit = $limits["gl"];
    $gi_limit = $limits["gi"];
    $rt_limit = $limits["rt"];

    $queue = [];
    $res = $db->query("SELECT matricule, nom_prenom, mgc, ordre_gl, ordre_gi, ordre_rt FROM " . TABLE_NAME . "_$annee" . " WHERE choisit=1 ORDER BY mgc DESC;");
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

    // $limits = [
    //     "ordre_gl" => $_GET["gl_limit"],
    //     "ordre_gi" => $_GET["gi_limit"],
    //     "ordre_rt" => $_GET["rt_limit"],
    // ];
    $limits = [
        "ordre_gl" => $gl_limit,
        "ordre_gi" => $gi_limit,
        "ordre_rt" => $rt_limit,
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
                    $db->query("UPDATE " . TABLE_NAME . "_$annee" . " SET satisfaction='satisfait' WHERE matricule='" . $matricule . "'");
                }

                $voeu_affecte = $spec_map[$spec_name];
                $db->query("UPDATE " . TABLE_NAME . "_$annee" . " SET voeu_affecte='" . $voeu_affecte . "' WHERE matricule='" . $matricule . "'");

                break;
            }
            $index++;
        }
    }
}

if (
    isset($_GET["gl_limit"]) &&
    isset($_GET["gi_limit"]) &&
    isset($_GET["rt_limit"]) &&
    isset($_GET["annee"])
) {
    echo "hwch";
    $annee = $_GET["annee"];
    define("nombre_places", "nombre_places");
    $db->query("UPDATE " . nombre_places . " 
    SET gl={$_GET['gl_limit']},
        gi={$_GET['gi_limit']},
        rt={$_GET['rt_limit']}
    WHERE annee='$annee'
    ");

    header("Location: statique.html");
}
/*----------------End Affectation Handling--------------*/

/*----------------Start Send statistics --------------*/
if (
    isset($_GET["stats"]) &&
    isset($_GET["annee"])
) {
    $annee = $_GET["annee"];
    affectation($annee);
    $res = $db->query("SELECT * FROM " . TABLE_NAME . "_$annee" . " WHERE choisit=1 ORDER BY MGC DESC");
    $final = [];
    while (($student = $res->fetch_assoc()) != null) {
        $nom_prenom = preg_split("/[ ]+/", $student["nom_prenom"]);

        if ($student["ordre_GL"] == 1)
            $voeu_choisit = "GL";

        if ($student["ordre_GI"] == 1)
            $voeu_choisit = "GI";

        if ($student["ordre_RT"] == 1)
            $voeu_choisit = "RT";

        $voeus_choisits[$student["ordre_GL"]] = "GL";
        $voeus_choisits[$student["ordre_GI"]] = "GI";
        $voeus_choisits[$student["ordre_RT"]] = "RT";

        // var_dump($voeus_choisits);
        $voeus_choisits = array_flip($voeus_choisits);
        // var_dump($voeus_choisits);

        $final[] = [
            "nom" => $nom_prenom[0],
            "prenom" => $nom_prenom[1],
            "matricule" => $student["matricule"],
            "ordre_gl" => $voeus_choisits["GL"],
            "ordre_gi" => $voeus_choisits["GI"],
            "ordre_rt" => $voeus_choisits["RT"],
            "voeu affecte" => $student["voeu_affecte"],
            "mgc" => $student["MGC"],
            "satisfaction" => $student["satisfaction"]
        ];
    }

    echo json_encode($final);
    exit();
}
/*----------------End Send statistics --------------*/
