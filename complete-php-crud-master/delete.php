<?php
session_start();

/* Admin فقط */
if (!isset($_SESSION['valid']) || $_SESSION['is_admin'] != 1) {
    die("Access denied. Admin only.");
}

include_once("pdo_connection.php");

/* التأكد من وجود ID */
if (!isset($_GET['id'])) {
    die("Product ID missing");
}

$id = (int)$_GET['id'];

/* حذف العلاقة مع التصنيف أولاً */
$stmtRel = $pdo->prepare(
    "DELETE FROM category_items WHERE crud_id = :id"
);
$stmtRel->execute([':id' => $id]);

/* حذف المنتج */
$stmt = $pdo->prepare(
    "DELETE FROM products WHERE id = :id"
);
$stmt->execute([':id' => $id]);

/* الرجوع إلى صفحة العرض */
header("Location: view.php");
exit();
