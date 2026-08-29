<?php session_start();
?>
<html>
<head>
<title>Homepage</title>
<link href = 'style.css' rel = 'stylesheet' type = 'text/css'>
<link rel="stylesheet" href="css/style_index.css">
</head>

<body>
<div id = 'header'>
Welcome to my page!
</div>

<?php
if ( isset( $_SESSION[ 'valid' ] ) ) {

    include( 'connection.php' );

    $result = mysqli_query( $mysqli, 'SELECT * FROM users' );
    ?>

    Welcome <?php echo $_SESSION[ 'name' ] ?> ! <a href = 'logout.php'>Logout</a><br/>
    <br/>
    <?php if ( $_SESSION[ 'is_admin' ] == 1 ): ?>
    <a href = 'admin_users.php'>Manage Users</a> |
    <?php endif;
    ?>
     <a href="public.php">Browse Products</a>
     <!-- <a href="index.php">HOme</a> -->

    <a href = 'view.php'>View and Add Products</a>
    <br/><br/>
    <?php
} else {
    echo 'You must be logged in to view this page.<br/><br/>';
    echo "<a href='users.php'>Login</a> | <a href='register.php'>Register</a>| <a href='public.php'>Browse Products</a>";
}
?>
<div id = 'footer'>
Created by Yasser Alrazaki
</div>

</body>
</html>
