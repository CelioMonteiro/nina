<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== 1) {
    $_SESSION['logado'] = 0;
    header("Location: http://localhost/nina/admin/login.html");

    exit();
}
?>
