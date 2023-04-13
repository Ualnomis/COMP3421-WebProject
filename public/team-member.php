<?php
$title = "Users";
$styles = <<<HTML
<style>
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

</style>
HTML;
$page_title = "";

include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
require_once('../classes/user.class.php');

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
} else if ($_SESSION['role'] === 'seller') {

} else if ($_SESSION['role'] === 'buyer') {
    echo '<script>window.location.replace("../public/");</script>';
    exit;
}

$user = new User($conn);
$users = $user->getAllUsers();
?>

<div class="container-xl mt-3">
    <div class="card">
        <div class="card-body">
            <div class="card-header d-flex justify-content-between">
                <h1 class="card-title">Users</h1>
                <a href="register.php" class="btn btn-primary add-user-btn">Add User</a>
            </div>
            <div class="row row-cards">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <!-- <th>Icon</th> -->
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($users) > 0): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <?= $user['id'] ?>
                                        </td>
                                        <td>
                                            <?= $user['username'] ?>
                                        </td>
                                        <td>
                                            <?= $user['email'] ?>
                                        </td>
                                        <!-- <td>
                                    <img src="<?= $user['icon'] ?>" width="50" height="50">
                                </td> -->
                                        <td>
                                            <?= $user['role'] ?>
                                        </td>
                                        <td>
                                            <a href="edit-user.php?userid=<?= $user['id'] ?>"><i
                                                    class="fa-solid fa-magnifying-glass"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = "";
include_once('../includes/footer.inc.php');
?>