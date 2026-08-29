<?php
include("connection.php");

if (isset($_POST['submit'])) {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $user  = $_POST['username'];
    $pass  = $_POST['password'];

    // بيانات الصورة
    $image_name = $_FILES['image']['name'];
    $image_tmp  = $_FILES['image']['tmp_name'];

    if ($name=="" || $email=="" || $user=="" || $pass=="" || $image_name=="") {
        echo "All fields are required";
    } else {

        // اسم فريد للصورة
        $new_image_name = time() . "_" . $image_name;

        // رفع الصورة
        move_uploaded_file($image_tmp, "uploads/" . $new_image_name);

        // إدخال البيانات
        mysqli_query($mysqli,"
            INSERT INTO users(name,email,username,password,image,is_admin,is_active)
            VALUES(
                '$name',
                '$email',
                '$user',
                md5('$pass'),
                '$new_image_name',
                0,
                1
            )
        ");

        header("Location: users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Register</h2>

<form method="post" enctype="multipart/form-data">

    Name <br>
    <input type="text" name="name"><br><br>

    Email <br>
    <input type="email" name="email"><br><br>

    Username <br>
    <input type="text" name="username"><br><br>

    Password <br>
    <input type="password" name="password"><br><br>

    Profile Image <br>
    <input type="file" name="image"><br><br>

    <input type="submit" name="submit" value="Register">

</form>

</body>
</html>
