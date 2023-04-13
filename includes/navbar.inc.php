<div class="page">
    <div class="sticky-top">
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href=".">
                        <img src="../assets/images/icon.png" width="110" height="32" alt="Giftify"
                            class="navbar-brand-image">
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="d-md-flex">


                        <?php
                        if (isset($_SESSION["role"]) && $_SESSION["role"] === "buyer") {
                            echo <<<HTML
                                <div class="nav-item d-md-flex me-3">
                                <a href="cart-detail.php" class="nav-link px-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M17 17h-11v-14h-2"></path>
                                        <path d="M6 5l14 1l-1 7h-13"></path>
                                    </svg>
                                    <span class="badge badge-pill bg-red" id="cart-count"></span>
                                </a>
                                </div>
                                HTML;
                        }
                        ?>
                    </div>
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        $fistChar = ucfirst(substr($_SESSION['user_name'], 0, 1));
                        echo <<<HTML
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                                    aria-label="Open user menu">
                                    <!-- <span class="avatar avatar-sm" id="avatar"></span> -->
                                    <div class="rounded-full overflow-hidden bg-slate-500 text-white w-10 h-10 flex items-center justify-center">
                                    <span class="text-lg font-medium">$fistChar</span>
                                    </div>
                                    <div class="d-none d-xl-block ps-2">
                                        <div>{$_SESSION['user_name']}</div>
                                        <div class="mt-1 small text-muted">{$_SESSION['role']}</div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <a href="../public/order-list.php" class="dropdown-item">Order</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="../public/edit-user.php" class="dropdown-item">Settings</a>
                                    <a href="../includes/logout.inc.php" class="dropdown-item">Logout</a>
                                </div>
                            </div>
                            HTML
                        ;
                    } else {
                        echo <<<HTML
                                <div class="nav-item d-md-flex me-3">
                                <div class="btn-list">
                                <a href="./register.php" class="btn btn-outline-primary">
                                Register
                            </a>
                            <a href="./login.php" class="btn btn-outline-success">
                            Login
                            </a>
                                </div>
                            </div>
                            HTML;
                    }
                    ?>

                </div>
                <div class="visible navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <ul class="navbar-nav">
                            <li id='home_li' class="nav-item ">
                                <a class="nav-link" href="./">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Home
                                    </span>
                                </a>
                            </li>
                            <li id="gift_li" class="nav-item ">
                                <a class="nav-link" href="./product.php">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-gift" viewBox="0 0 16 16">
                                            <path
                                                d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Gift <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') { echo 'Management'; } ?>
                                    </span>
                                </a>
                            </li>
                            <?php
                            if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
                                echo <<<HTML
                                    <li id="team_li" class="nav-item ">
                                        <a class="nav-link" href="./team-member.php">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-gift" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z" />
                                                </svg>
                                            </span>
                                            <span class="nav-link-title">
                                                Team Member Management
                                            </span>
                                        </a>
                                    </li>
                                HTML;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
    </div>
    <main id="swup" class="transition-fade">