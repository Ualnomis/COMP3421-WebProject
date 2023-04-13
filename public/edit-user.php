<?php ob_start(); ?>
<?php
$title = "Edit User";
$styles = <<<HTML
HTML;
$page_title = "Edit User";

include_once('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
include_once('../includes/page-wrapper-start.inc.php');
require_once('../classes/user.class.php');

$user_role = $_SESSION['role'];
$user = new User($conn);


if($user_role == "seller"){
    if (isset($_GET['userid'])) {
        $user_id = $_GET['userid'];
        $user_data = $user->selectUserByID($user_id);
    } elseif (isset($_POST['userid'])) {
        $user_id = $_POST['userid'];
        $user_data = $user->selectUserByID($user_id);
    } else {
        header("Location: team-member.php");
        exit();
    }
}else{
    $user_id = $_SESSION['user_id'];
    $user_data = $user->selectUserByID($user_id);
    print_r($user_data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_id = trim($_POST['userid']);
    
    if(isset($_POST['role'])){
        $role = $_POST['role'];
    }else{
        $role = $user_data['role'];
    }
    if(empty($email) || empty($password) || empty($confirm_password)){
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $user->updateUser($email, $role, $password, $user_id);
        $error = $user_id;
        if($user_role == 'buyer'){
            header('Location: index.php');
            exit();
        }else {
            header("Location: team-member.php");
            exit();
        }
    }
} 
?>

<div class="container-xl mt-3">
    <div class="row">
        <div class="col-12">
            <h1>Edit User</h1>
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php } ?>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" >
                <div class="form-group pt-2">
                    <label for="id">User ID:</label>
                    <input type="text" class="form-control" id="id" name="id" value="<?= $user_data['id'] ?>" disabled>
                    <input type="hidden" name="userid" value="<?= $user_id ?>">
                </div>
                <div class="form-group pt-2">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $user_data['email'] ?>">
                </div>
                <?php if($user_role == "seller" ): ?>
                <div class="form-group pt-3" >
                    <label for="role">Role:</label>
                    <select class="form-control" id="role" name="role">
                        <option value="user" <?= ($user_data['role'] == 'user') ? 'selected' : '' ?>>User</option>
                        <option value="seller" <?= ($user_data['role'] == 'seller') ? 'selected' : '' ?>>seller</option>
                    </select>
                </div>
                <?php endif; ?>
                <div class="form-group pt-3" >
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group pt-3" >
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="pt-3">
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = "";
include_once('../includes/footer.inc.php');
?>