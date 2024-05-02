<?php
session_start();
if (isset($_GET['nselfCount']) && isset($_GET['nselfPerform']) && isset($_GET['selfCount']) && isset($_GET['selfPerform'])&& isset($_GET['id'])) {
    $_SESSION['nselfCount'] = $_GET['nselfCount'];
    $_SESSION['nselfPerform'] = $_GET['nselfPerform'];
    $_SESSION['selfCount'] = $_GET['selfCount'];
    $_SESSION['selfPerform'] = $_GET['selfPerform'];
    $_SESSION['id'] = $_GET['id'];
}

?>
