<?php

require("db_connection.php");
const ACCOUNT_ERR_MSG = "NOT_CONNECTED";

session_start();
if (isset($_SESSION["agent_connected"])) {
    die("You are already connected as {$_SESSION['agent_name']}");
}

/*----------------Start Agent Session--------------*/
const TABLE_NAME2 = "agent";

if (
    isset($_GET["agent_name"]) && !empty($_GET["agent_name"]) &&
    isset($_GET["agent_code"]) && !empty($_GET["agent_code"])
) {
    $user = $_GET["agent_name"];
    $code = $_GET["agent_code"];
    $res = $db->query("SELECT username FROM " . TABLE_NAME2 . " WHERE username='$user' AND mot_de_passe='$code'");
    $user_found = $res->fetch_assoc();

    if (!$user_found) {
        exit(ACCOUNT_ERR_MSG);
    }

    $_SESSION['agent_connected'] = "YES";
    $_SESSION['agent_name'] = $_GET["agent_name"];
    header("Location: agentChoices.html");
}

/*----------------End Agent Session--------------*/