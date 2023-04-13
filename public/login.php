<?php
$title = "Index";
$styles = "";
$msg = '';
$error = false;

include_once('../includes/header.inc.php');
include_once('../classes/cart.class.php');
include_once('../includes/navbar.inc.php');

if(isset($_SESSION['role'])) {
    echo '<script>window.location.replace("../public/");</script>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // call the login method
    $result = $user->login($email, $password);

    // handle the result
    if ($result) {
        // Login
        $cart = new Cart($conn);
        if ($cart->select($_SESSION['user_id'])) {
            echo '<script>window.location.replace("index.php");</script>';
            exit();
        } else {
            $cart->insert($_SESSION['user_id']);
            echo '<script>window.location.replace("index.php");</script>';
            exit();
        }
        
    } else {
        $error = true;
        $msg = 'Login failed. Please try again.';
    }
}
?>
<div class="page page-center" id="login_page">
    <div class="container container-tight py-4 w-fit md:w-[450px]">
        <div class="text-center mb-4">
            <a href="." class="navbar-brand"><img src="../assets/images/icon.png" height="36" width="36"
                    alt="Giftify"></a>
        </div>
        <div class="card card-md flex justify-center items-center ">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Login to your account</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $msg; ?></div>
                <?php endif; ?>
                <form action="" method="post" autocomplete="off" novalidate>
                    <div class="mb-3">
                        <label class="form-label required">Email address</label>
                        <input type="email" name="email" class="form-control focus:text-dark" placeholder="your@email.com"
                            autocomplete="off">
                    </div>
                    <div class="mb-2">
                        <label class="form-label required">Password</label>
                        <div class="input-group input-group-flat">
                            <input id="password" type="password" name="password" class="form-control" placeholder="Your password"
                                autocomplete="off">
                            <span class="input-group-text border-[#6B7280]" id="eye">
                                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
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
                    <!-- <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input cursor-pointer border-white" />
                            <span class="form-check-label">Remember me on this device</span>
                        </label>
                    </div> -->
                    <div class="form-footer bg-blue-600 hover:bg-blue-400 rounded">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center text-muted mt-3">
            Don't have account yet? <a href="./register.php" tabindex="-1" class="text-cyan-600">Sign up</a>
        </div>
    </div>
</div>
<?php
include_once('../includes/page-wrapper-end.inc.php');
$scripts = "";
// Include the template file
$modals = <<<HTML
HTML;
include_once('../includes/footer.inc.php');
?>