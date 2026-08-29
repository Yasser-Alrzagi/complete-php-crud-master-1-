<?php
session_start();

/* Admin فقط */
if (!isset($_SESSION['valid']) || $_SESSION['is_admin'] != 1) {
    die("Access denied");
}

include_once("pdo_connection.php");

if (!isset($_GET['id'], $_GET['action'])) {
    die("Invalid request");
}

$id = (int)$_GET['id'];
$action = $_GET['action'];

/* منع الأدمن من تعديل نفسه */
if ($id === $_SESSION['id']) {
    die("You cannot modify yourself");
}

switch ($action) {

    case 'disable':
        $stmt = $pdo->prepare("UPDATE users SET is_active = 0 WHERE id = :id");
        break;

    case 'enable':
        $stmt = $pdo->prepare("UPDATE users SET is_active = 1 WHERE id = :id");
        break;

    case 'make_admin':
        $stmt = $pdo->prepare("UPDATE users SET is_admin = 1 WHERE id = :id");
        break;

    case 'remove_admin':
        $stmt = $pdo->prepare("UPDATE users SET is_admin = 0 WHERE id = :id");
        break;

    default:
        die("Unknown action");
}

$stmt->execute([':id' => $id]);

header("Location: admin_users.php");
exit();
