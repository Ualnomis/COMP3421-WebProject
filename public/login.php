<?php
$pageTitle = "Index";
$styles = "";

// Include the template file
include('../templates/header.php');
?>
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" height="36" alt=""></a>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Login to your account</h2>
                <form action="" method="post" autocomplete="off" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" placeholder="your@email.com"
                            autocomplete="off">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Password
                            <span class="form-label-description">
                                <a href="./forgot-password.html">I forgot password</a>
                            </span>
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="password" class="form-control" placeholder="Your password"
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
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" />
                            <span class="form-check-label">Remember me on this device</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center text-muted mt-3">
            Don't have account yet? <a href="./register.php" tabindex="-1">Sign up</a>
        </div>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // call the register method
    $result = $user->login($email, $password);

    // handle the result
    if ($result) {
        // registration successful, redirect to the login page
        header('Location: index.php');
        exit();
    } else {
        // registration failed, display an error message
        echo 'Login failed. Please try again.';
    }
}
?>

<?php
$scripts = "";
// Include the template file
include('../templates/footer.php');
?>