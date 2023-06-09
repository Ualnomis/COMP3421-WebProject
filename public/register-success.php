<?php
$title = "Index";
$styles = "";

// Include the template file
include('../includes/header.inc.php');
include_once('../includes/navbar.inc.php');
?>


<body class=" border-top-wide border-primary d-flex flex-column">
    <script src="./dist/js/demo-theme.min.js?1674944800"></script>
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="empty">
                <p class="empty-title">Register Success</p>
                <p class="empty-subtitle text-black">
                    Congratulations! You can start buying gifts in our store!
                </p>
                <div class="empty-action">
                    <a href="./." class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l14 0" />
                            <path d="M5 12l6 6" />
                            <path d="M5 12l6 -6" />
                        </svg>
                        Go to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once('../includes/page-wrapper-end.inc.php');
    $scripts = "";
    $modals = <<<HTML
HTML;
    include('../includes/footer.inc.php');
    ?>