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
