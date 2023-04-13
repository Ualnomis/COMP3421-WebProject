<?php
$title = "Register";
$styles = "";
$error = false;
$errorMessage = '';

$username ='';
$email = '';
$password = '';
$agree = false;

include('../includes/header.inc.php');
include_once('../classes/cart.class.php');
include_once('../includes/navbar.inc.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $agree = isset($_POST['agree']);
    // print($agree);

    // Validate username
    if (empty($username)) {
        $error = true;
        $errorMessage = 'Please enter a username.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = true;
        $errorMessage = 'Username can only contain letters, numbers, and underscores.';
    }

    // Validate email
    if (empty($email)) {
        $error = true;
        $errorMessage = 'Please enter an email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $errorMessage = 'Please enter a valid email address.';
    }

     // Validate password
     if (empty($password)) {
        $error = true;
        $errorMessage = 'Please enter a password.';
    } elseif (strlen($password) < 5) {
        $error = true;
        $errorMessage = 'Password must be at least 5 characters long.';
    }

    // Validate agree checkbox
    if (!$agree) {
        $error = true;
        $errorMessage = 'Please agree to the terms and policy.';
    }

    if (!$error) {
        // If there are no errors, proceed with registration
        $role = 'buyer';
        $result = $user->register($username, $email, $password, $role);
        if ($result) {
            // call the login method
            $result = $user->login($email, $password);
            $cart = new Cart($conn);
            if ($cart->select($_SESSION['user_id'])) {
            } else {
                $cart->insert($_SESSION['user_id']);
            }
            echo '<script>window.location.replace("register-success.php");</script>';
            exit();
        } else {
            $error = true;
            $errorMessage = 'Registration failed. Email address is already registered.';
        }
    }
}
?>
<div class="page page-center">
    <div class="container container-tight py-4 w-fit md:w-[450px]">
        <div class="text-center mb-4">
            <a href="." class="navbar-brand"><img src="../assets/images/icon.png" height="36" width="36"
                    alt="Giftify"></a>
        </div>
        <form class="card card-md" action="#" method="post" autocomplete="off" novalidate id="register_form">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Create new account</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter name" value="<?php echo $username?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email" value="<?php echo $email?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group input-group-flat">
                        <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo $password?>"
                            autocomplete="off">
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="Show password"
                                data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path
                                        d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-check">
                        <input type="checkbox" name="agree" class="form-check-input border-black cursor-pointer" <?php if($agree) echo 'checked'?>/>
                        <span class="form-check-label">Agree the <a href="./terms-of-service.html" tabindex="-1">terms
                                and policy</a>.</span>
                    </label>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100 bg-blue-600 hover:bg-blue-400 rounded">Create new account</button>
                </div>
            </div>
        </form>
        <div class="text-center text-muted mt-3">
            Already have account? <a href="./login.php" tabindex="-1" class="text-cyan-600">Sign in</a>
        </div>
    </div>

    <?php
    include_once('../includes/page-wrapper-end.inc.php');
    $scripts = "";
    $modals = <<<HTML
HTML;
    include('../includes/footer.inc.php');
    ?>