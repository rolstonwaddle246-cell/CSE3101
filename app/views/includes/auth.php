<?php
session_start();

if (!isset($_SESSION['user'])) {
    // not logged in → redirect to login
    header("Location: index.php?action=login");
    exit;
}

$currentUserRole = strtolower($_SESSION['user']['role_name']);