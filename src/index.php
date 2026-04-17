<?php
session_start();
require_once 'includes/header.php';

if (isset($_SESSION['utilizador_id'])) {
    header("Location: agenda.php");
} else {
    header("Location: login.php");
}
exit;