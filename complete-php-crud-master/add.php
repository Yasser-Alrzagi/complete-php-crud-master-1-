<?php
session_start();

/* أي مستخدم مسجّل */
if (!isset($_SESSION['valid'])) {
    header('Location: users.php');
    exit();
}

include_once("pdo_connection.php");

/* جلب التصنيفات */
$stmtCats = $pdo->prepare("SELECT * FROM categories");
$stmtCats->execute();
$categories = $stmtCats->fetchAll(PDO::FETCH_ASSOC);

/* عند إرسال الفورم */
if (isset($_POST['Submit'])) {

    $name        = $_POST['name'] ?? '';
    $qty         = $_POST['qty'] ?? '';
    $price       = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $loginId     = $_SESSION['id'];

    if (empty($name) || empty($qty) || empty($price) || empty($category_id)) {
        echo "<font color='red'>All fields are required.</font><br/>";
    } else {

        $stmt = $pdo->prepare(
            "INSERT INTO products (name, qty, price, login_id)
             VALUES (:name, :qty, :price, :login_id)"
        );

        $stmt->execute([
            ':name'     => $name,
            ':qty'      => $qty,
            ':price'    => $price,
            ':login_id' => $loginId
        ]);

        $product_id = $pdo->lastInsertId();

        $stmtRel = $pdo->prepare(
            "INSERT INTO category_items (crud_id, category_id)
             VALUES (:crud_id, :category_id)"
        );

        $stmtRel->execute([
            ':crud_id'     => $product_id,
            ':category_id' => $category_id
        ]);

        header("Location: view.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Data</title></head>
<body>

<form method="post">
<table>
<tr><td>Name</td><td><input type="text" name="name"></td></tr>
<tr><td>Quantity</td><td><input type="text" name="qty"></td></tr>
<tr><td>Price</td><td><input type="text" name="price"></td></tr>
<tr>
<td>Category</td>
<td>
<select name="category_id">
<option value="">Select</option>
<?php foreach ($categories as $cat): ?>
<option value="<?= $cat['id']; ?>"><?= $cat['name']; ?></option>
<?php endforeach; ?>
</select>
</td>
</tr>
<tr><td></td><td><input type="submit" name="Submit" value="Add"></td></tr>
</table>
</form>

</body>
</html>
