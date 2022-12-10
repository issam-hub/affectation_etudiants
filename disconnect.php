<?php

session_start();

if (isset($_SESSION["agent_connected"])) {
    echo session_id();
    echo "hi";
    session_unset();
    session_destroy();
    echo $_SESSION["agent_connected"];
    header("Location: index.html");
}
