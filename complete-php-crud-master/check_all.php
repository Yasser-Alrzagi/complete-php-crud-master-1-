
------------------------------
add.php
-------------------------------


<?php
session_start();

/* حماية الصفحة: Admin فقط */
if (!isset($_SESSION['valid']) || $_SESSION['is_admin'] != 1) {
    die("Access denied. Admin only.");
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

    /* التحقق */
    if (empty($name) || empty($qty) || empty($price) || empty($category_id)) {

        if (empty($name))  echo "<font color='red'>Name field is empty.</font><br/>";
        if (empty($qty))   echo "<font color='red'>Quantity field is empty.</font><br/>";
        if (empty($price)) echo "<font color='red'>Price field is empty.</font><br/>";
        if (empty($category_id)) echo "<font color='red'>Category is required.</font><br/>";

        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";

    } else {

        /* إدخال المنتج باستخدام PDO */
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

        /* جلب ID المنتج */
        $product_id = $pdo->lastInsertId();

        /* ربط المنتج بالتصنيف */
        $stmtRel = $pdo->prepare(
            "INSERT INTO category_items (crud_id, category_id)
             VALUES (:crud_id, :category_id)"
        );

        $stmtRel->execute([
            ':crud_id'     => $product_id,
            ':category_id' => $category_id
        ]);

        echo "<font color='green'>Data added successfully.</font><br/>";
        echo "<a href='view.php'>View Result</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Data</title>
</head>

<body>

<form action="" method="post">
<table width="30%" border="0">
    <tr>
        <td>Name</td>
        <td><input type="text" name="name"></td>
    </tr>
    <tr>
        <td>Quantity</td>
        <td><input type="text" name="qty"></td>
    </tr>
    <tr>
        <td>Price</td>
        <td><input type="text" name="price"></td>
    </tr>
    <tr>
        <td>Category</td>
        <td>
            <select name="category_id">
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id']; ?>">
                        <?= $cat['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" name="Submit" value="Add"></td>
    </tr>
</table>
</form>

</body>
</html>
---------------------------------------------------------------$_COOKIE


-----------------------
view.php
-----------------------
<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header('Location: users.php');
    exit();
}

include_once("pdo_connection.php");

/* الاستعلام يختلف حسب الصلاحية */
if ($_SESSION['is_admin'] == 1) {
    // Admin: يرى كل المنتجات
    $stmt = $pdo->prepare("
        SELECT 
            products.*,
            categories.name AS category_name
        FROM products
        LEFT JOIN category_items ON products.id = category_items.crud_id
        LEFT JOIN categories ON categories.id = category_items.category_id
        ORDER BY products.id DESC
    ");
    $stmt->execute();
} else {
    // User: يرى منتجاته فقط
    $stmt = $pdo->prepare("
        SELECT 
            products.*,
            categories.name AS category_name
        FROM products
        LEFT JOIN category_items ON products.id = category_items.crud_id
        LEFT JOIN categories ON categories.id = category_items.category_id
        WHERE products.login_id = :login_id
        ORDER BY products.id DESC
    ");
    $stmt->execute([
        ':login_id' => $_SESSION['id']
    ]);
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
</head>

<body>

<a href="index.php">Home</a> |

<?php if ($_SESSION['is_admin'] == 1): ?>
    <a href="add.php">Add New Data</a> |
<?php endif; ?>

<a href="logout.php">Logout</a>

<br/><br/>

<table width="90%" border="1">
    <tr bgcolor="#CCCCCC">
        <td>Name</td>
        <td>Quantity</td>
        <td>Price (euro)</td>
        <td>Category</td>
        <td>Actions</td>
    </tr>

    <?php foreach ($results as $res): ?>
        <tr>
            <td><?= $res['name']; ?></td>
            <td><?= $res['qty']; ?></td>
            <td><?= $res['price']; ?></td>
            <td><?= $res['category_name'] ?? 'No Category'; ?></td>
            <td>
                <a href="edit.php?id=<?= $res['id']; ?>">Edit</a>

                <?php if ($_SESSION['is_admin'] == 1): ?>
                    |
                    <a href="delete.php?id=<?= $res['id']; ?>"
                       onclick="return confirm('Are you sure you want to delete?')">
                       Delete
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
---------------------------------------------------------------------------


--------------
index.php
--------------
<?php session_start(); ?>
<html>
<head>
	<title>Homepage</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div id="header">
		Welcome to my page!
	</div>
	<?php
	if(isset($_SESSION['valid'])) {			
		include("connection.php");					
		$result = mysqli_query($mysqli, "SELECT * FROM users");
	?>
				
		Welcome <?php echo $_SESSION['name'] ?> ! <a href='logout.php'>Logout</a><br/>
		<br/>
		<a href='view.php'>View and Add Products</a>
		<br/><br/>
	<?php	
	} else {
		echo "You must be logged in to view this page.<br/><br/>";
		echo "<a href='users.php'>Login</a> | <a href='register.php'>Register</a>";
	}
	?>
<div id="footer">
    Created by Yasser Alrazaki
</div>

</body>
</html>
----------------------------------------------------------$_COOKIE

----------------
edit.php
----------------
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
-------------------------------------------------------------------------------


----------
delete.php
----------
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
// --------------------------------------------------



-------------
register.php
-------------
<html>
<head>
	<title>Register</title>
</head>

<body>
<a href="index.php">Home</a> <br />
<?php
include("connection.php");

if(isset($_POST['submit'])) {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$user = $_POST['username'];
	$pass = $_POST['password'];

	if($user == "" || $pass == "" || $name == "" || $email == "") {
		echo "All fields should be filled. Either one or many fields are empty.";
		echo "<br/>";
		echo "<a href='register.php'>Go back</a>";
	} else {
		mysqli_query($mysqli, "INSERT INTO users(name, email, username, password) VALUES('$name', '$email', '$user', md5('$pass'))")
			or die("Could not execute the insert query.");
			
		echo "Registration successfully";
		echo "<br/>";
		echo "<a href='view.php'>Login</a>";
	}
} else {
?>
	<p><font size="+2">Register</font></p>
	<form name="form1" method="post" action="">
		<table width="75%" border="0">
			<tr> 
				<td width="10%">Full Name</td>
				<td><input type="text" name="name"></td>
			</tr>
			<tr> 
				<td>Email</td>
				<td><input type="text" name="email"></td>
			</tr>			
			<tr> 
				<td>Username</td>
				<td><input type="text" name="username"></td>
			</tr>
			<tr> 
				<td>Password</td>
				<td><input type="password" name="password"></td>
			</tr>
			<tr> 
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="Submit"></td>
			</tr>
		</table>
	</form>
<?php
}
?>
</body>
</html>
------------------------------------------------------------------------


--------------
user.php
------------
<?php
session_start();
include("connection.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>

<body>

<a href="index.php">Home</a><br><br>

<?php
if (isset($_POST['submit'])) {

    $user = mysqli_real_escape_string($mysqli, $_POST['username']);
    $pass = mysqli_real_escape_string($mysqli, $_POST['password']);

    if ($user == "" || $pass == "") {
        echo "Either username or password field is empty.<br>";
        echo "<a href='users.php'>Go back</a>";
    } else {

        // جلب المستخدم من جدول users
        $result = mysqli_query(
            $mysqli,
            "SELECT * FROM users WHERE username='$user' AND password=md5('$pass')"
        ) or die("Could not execute the select query.");

        $row = mysqli_fetch_assoc($result);

        if (is_array($row) && !empty($row)) {

            // منع المستخدم الموقوف
            if ($row['is_active'] == 0) {
                die("Your account has been disabled by admin");
            }

            // إنشاء الجلسات
            $_SESSION['valid']    = true;
            $_SESSION['id']       = $row['id'];
            $_SESSION['name']     = $row['name'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['is_admin'] = $row['is_admin'];

            // تحويل بعد تسجيل الدخول
            header("Location: index.php");
            exit();

        } else {
            echo "Invalid username or password.<br>";
            echo "<a href='users.php'>Go back</a>";
        }
    }
} else {
?>

<p><font size="+2">Login</font></p>

<form name="form1" method="post" action="">
    <table width="75%" border="0">
        <tr>
            <td width="10%">Username</td>
            <td><input type="text" name="username"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" name="password"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="Submit"></td>
        </tr>
    </table>
</form>

<?php
}
?>

</body>
</html>
------------------------------------------------------------------------------


-----------
