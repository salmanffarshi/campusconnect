<?php
require_once "../../controllers/authCheck.php";

startSession();

if (isLoggedIn()) {
    echo $_SESSION["userId"] . "logged in.";
}
else{
    echo "not logged in";
}

?>