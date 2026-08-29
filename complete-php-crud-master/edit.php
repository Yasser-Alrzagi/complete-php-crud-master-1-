<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header('Location: users.php');
    exit();
}

include_once("pdo_connection.php");

/* التأكد من وجود ID */
if (!isset($_GET['id'])) {
    die("Product ID missing");
}

$id = (int)$_GET['id'];

/* جلب المنتج حسب الصلاحية */
if ($_SESSION['is_admin'] == 1) {
    // Admin: يسمح له بتعديل أي منتج
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
} else {
    // User: يسمح له بتعديل منتجاته فقط
    $stmt = $pdo->prepare(
        "SELECT * FROM products 
         WHERE id = :id AND login_id = :login_id"
    );
    $stmt->execute([
        ':id' => $id,
        ':login_id' => $_SESSION['id']
    ]);
}

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Access denied");
}

/* عند التحديث */
if (isset($_POST['update'])) {

    $name  = $_POST['name'] ?? '';
    $qty   = $_POST['qty'] ?? '';
    $price = $_POST['price'] ?? '';

    if (empty($name) || empty($qty) || empty($price)) {
        echo "<font color='red'>All fields are required.</font><br/>";
    } else {

        $stmtUpdate = $pdo->prepare(
            "UPDATE products 
             SET name = :name, qty = :qty, price = :price
             WHERE id = :id"
        );

        $stmtUpdate->execute([
            ':name'  => $name,
            ':qty'   => $qty,
            ':price' => $price,
            ':id'    => $id
        ]);

        header("Location: view.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>
</head>

<body>

<a href="view.php">Back</a> | 
<a href="logout.php">Logout</a>

<br/><br/>

<form method="post">
<table border="0">
    <tr>
        <td>Name</td>
        <td>
            <input type="text" name="name" 
                   value="<?= htmlspecialchars($product['name']); ?>">
        </td>
    </tr>
    <tr>
        <td>Quantity</td>
        <td>
            <input type="text" name="qty" 
                   value="<?= htmlspecialchars($product['qty']); ?>">
        </td>
    </tr>
    <tr>
        <td>Price</td>
        <td>
            <input type="text" name="price" 
                   value="<?= htmlspecialchars($product['price']); ?>">
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type="submit" name="update" value="Update">
        </td>
    </tr>
</table>
</form>

</body>
</html>
