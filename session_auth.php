<?php

require("db_connection.php");
const ACCOUNT_ERR_MSG = "NOT_CONNECTED";

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

    session_start();
    $_SESSION['agent_connected'] = "YES";
    echo "connected with success";
}

/*----------------End Agent Session--------------*/