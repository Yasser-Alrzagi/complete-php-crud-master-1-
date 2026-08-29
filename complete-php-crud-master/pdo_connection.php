<?php
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=crud_with_login;charset=utf8",
        "root",
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}
?>
