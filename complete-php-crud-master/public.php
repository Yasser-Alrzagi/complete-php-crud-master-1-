<?php
include_once("pdo_connection.php");

/* جلب جميع التصنيفات */
$stmtCats = $pdo->prepare("SELECT * FROM categories");
$stmtCats->execute();
$categories = $stmtCats->fetchAll(PDO::FETCH_ASSOC);

/* إذا تم اختيار تصنيف */
$products = [];
if (isset($_GET['category_id'])) {

    $category_id = (int)$_GET['category_id'];

    $stmt = $pdo->prepare("
        SELECT products.*
        FROM products
        INNER JOIN category_items 
            ON products.id = category_items.crud_id
        WHERE category_items.category_id = :category_id
    ");

    $stmt->execute([
        ':category_id' => $category_id
    ]);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products by Category</title>
     <link rel="stylesheet" href="css/style_public.css">
</head>

<body>
     <a href='index.php'>Home</a> 

<h2>📂 Categories</h2>

<ul>
    <?php foreach ($categories as $cat): ?>
        <li>
            <a href="public.php?category_id=<?= $cat['id']; ?>">
                <?= htmlspecialchars($cat['name']); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<hr>

<?php if (isset($_GET['category_id'])): ?>

    <h2>🛒 Products</h2>

    <?php if (count($products) > 0): ?>
        <table border="1" width="70%">
            <tr bgcolor="#CCCCCC">
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>

            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']); ?></td>
                    <td><?= htmlspecialchars($p['qty']); ?></td>
                    <td><?= htmlspecialchars($p['price']); ?></td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?>
        <p>No products in this category.</p>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>
