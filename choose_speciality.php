<?php

require_once "db_connection.php";
$table_name = "etudiant";
const ACCOUNT_ERR_MSG = "wrong credentials, verify username or password";
if (
    isset($_GET["matricule"]) && !empty($_GET["matricule"]) &&
    isset($_GET["gl_choix"]) && !empty($_GET["gl_choix"]) &&
    isset($_GET["gi_choix"]) && !empty($_GET["gi_choix"]) &&
    isset($_GET["rt_choix"]) && !empty($_GET["rt_choix"]) &&
    isset($_GET["code"]) && !empty($_GET["code"])
) {
    $res = $db->query("SELECT annee FROM nombre_places ORDER BY  SUBSTRING(annee,6) DESC");
    $annee = $res->fetch_assoc()["annee"];
    $table_name .= "_$annee";

    $matricule = $_GET['matricule'];
    $code = $_GET["code"];

    $res = $db->query("SELECT matricule FROM " . $table_name . " WHERE matricule='$matricule' AND mot_de_passe='$code'");
    $user_found = $res->fetch_assoc();

    if (!$user_found) {
        die(json_encode(["status" => "NOT_CONNECTED"]));
    }

    $res = $db->query("SELECT choisit FROM " . $table_name . " WHERE matricule='$matricule'");
    $choisit = $res->fetch_assoc()["choisit"];

    $json = [];
    if (!$choisit) {
        $json["deja_choisit"] = 0;
        $gl_choix = $_GET["gl_choix"];
        $gi_choix = $_GET["gi_choix"];
        $rt_choix = $_GET["rt_choix"];
        $db->query("UPDATE " . $table_name . " SET ordre_gl=$gl_choix, ordre_gi=$gi_choix, ordre_rt=$rt_choix WHERE matricule='$matricule'");
        $db->query("UPDATE " . $table_name . " SET choisit=1 WHERE matricule='$matricule'");
    } else {
        $json["deja_choisit"] = 1;
    }
    $res = $db->query("SELECT ordre_gl, ordre_gi, ordre_rt FROM " . $table_name . " WHERE choisit=1 AND matricule='$matricule'");
    $res = $res->fetch_assoc();
    $json["ordre_gl"] = $res["ordre_gl"];
    $json["ordre_gi"] = $res["ordre_gi"];
    $json["ordre_rt"] = $res["ordre_rt"];
    $json["status"] =  "CONNECTED";
    die(json_encode($json));
}
