<?php
session_start();

/* Admin فقط */
if (!isset($_SESSION['valid']) || $_SESSION['is_admin'] != 1) {
    die("Access denied. Admin only.");
}

include_once("pdo_connection.php");

/* جلب جميع المستخدمين */
$stmt = $pdo->prepare("
    SELECT id, name, email, username, is_admin, is_active
    FROM users
    ORDER BY id DESC
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
</head>
<body>

<h2>Admin - Manage Users</h2>

<a href="view.php">Back to Products</a> |
<a href="logout.php">Logout</a>

<br><br>

<table border="1" width="90%">
<tr bgcolor="#CCCCCC">
    <th>ID</th>
    <th>Name</th>
    <th>Username</th>
    <th>Email</th>
    <th>Role</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php foreach ($users as $u): ?>
<tr>
    <td><?= $u['id']; ?></td>
    <td><?= htmlspecialchars($u['name']); ?></td>
    <td><?= htmlspecialchars($u['username']); ?></td>
    <td><?= htmlspecialchars($u['email']); ?></td>
    <td><?= $u['is_admin'] ? 'Admin' : 'User'; ?></td>
    <td><?= $u['is_active'] ? 'Active' : 'Disabled'; ?></td>
    <td>

    <?php if ($u['id'] != $_SESSION['id']): ?>

        <!-- تفعيل / تعطيل -->
        <?php if ($u['is_active']): ?>
            <a href="toggle_user.php?id=<?= $u['id']; ?>&action=disable">Disable</a>
        <?php else: ?>
            <a href="toggle_user.php?id=<?= $u['id']; ?>&action=enable">Enable</a>
        <?php endif; ?>

        |

        <!-- إعطاء / سحب أدمن -->
        <?php if ($u['is_admin']): ?>
            <a href="toggle_user.php?id=<?= $u['id']; ?>&action=remove_admin">Remove Admin</a>
        <?php else: ?>
            <a href="toggle_user.php?id=<?= $u['id']; ?>&action=make_admin">Make Admin</a>
        <?php endif; ?>

    <?php else: ?>
        ---
    <?php endif; ?>

    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
