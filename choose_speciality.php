<?php

require_once "db_connection.php";
const TABLE_NAME = "etudiant";
const ACCOUNT_ERR_MSG = "wrong credentials, verify username or password";
if (
    isset($_GET["matricule"]) && !empty($_GET["matricule"]) &&
    isset($_GET["gl_choix"]) && !empty($_GET["gl_choix"]) &&
    isset($_GET["gi_choix"]) && !empty($_GET["gi_choix"]) &&
    isset($_GET["rt_choix"]) && !empty($_GET["rt_choix"]) &&
    isset($_GET["code"]) && !empty($_GET["code"])
) {
    $matricule = $_GET['matricule'];
    $code = $_GET["code"];

    $res = $db->query("SELECT matricule FROM " . TABLE_NAME . " WHERE matricule='$matricule' AND mot_de_passe='$code'");
    $user_found = $res->fetch_assoc();

    if (!$user_found) {
        die(ACCOUNT_ERR_MSG);
    }

    echo "CONNECTED";

    $res = $db->query("SELECT choisit FROM " . TABLE_NAME . " WHERE matricule='$matricule'");
    $choisit = $res->fetch_assoc()["choisit"];

    if ($choisit) {
        die("you've already chosen");
    } else {
        $gl_choix = $_GET["gl_choix"];
        $gi_choix = $_GET["gi_choix"];
        $rt_choix = $_GET["rt_choix"];
        $db->query("UPDATE " . TABLE_NAME . " SET ordre_gl=$gl_choix, ordre_gi=$gi_choix, ordre_rt=$rt_choix WHERE matricule='$matricule'");
        $db->query("UPDATE " . TABLE_NAME . " SET choisit=1 WHERE matricule='$matricule'");
        die("your choices were submitted with success");
    }
}
