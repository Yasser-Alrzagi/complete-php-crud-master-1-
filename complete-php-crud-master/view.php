


<?php
session_start();
if (!isset($_SESSION['valid'])) {
    header('Location: users.php');
    exit();
}




include_once("pdo_connection.php");
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

if ($_SESSION['is_admin'] == 1) {
    $stmt = $pdo->prepare("
        SELECT products.*, categories.name AS category_name
        FROM products
        LEFT JOIN category_items ON products.id = category_items.crud_id
        LEFT JOIN categories ON categories.id = category_items.category_id
        ORDER BY products.id DESC
    ");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("
        SELECT products.*, categories.name AS category_name
        FROM products
        LEFT JOIN category_items ON products.id = category_items.crud_id
        LEFT JOIN categories ON categories.id = category_items.category_id
        WHERE products.login_id = :login_id
        ORDER BY products.id DESC
    ");
    $stmt->execute([':login_id' => $_SESSION['id']]);
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Products</title>
 <link rel="stylesheet" href="css/style_index.css"></head>
<body>
	<?php if ($isAdmin): ?>
    <a href="admin_users.php">Manage Users</a> |
<?php endif; ?>



<a href="index.php">Home</a> |
<a href="add.php">Add New Data</a> |
<a href="logout.php">Logout</a>

<table border="1">
<tr>
<td>Name</td><td>Qty</td><td>Price</td><td>Category</td><td>Actions</td>
</tr>

<?php foreach ($results as $r): ?>
<tr>
<td><?= $r['name']; ?></td>
<td><?= $r['qty']; ?></td>
<td><?= $r['price']; ?></td>
<td><?= $r['category_name'] ?? 'None'; ?></td>
<td>
<a href="edit.php?id=<?= $r['id']; ?>">Edit</a>
<?php if ($_SESSION['is_admin'] == 1): ?>
 | <a href="delete.php?id=<?= $r['id']; ?>">Delete</a>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>

</table>
</body>
</html>
