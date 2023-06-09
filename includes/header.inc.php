<?php
include_once '../config/db_connection.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="../assets/images/icon.png" />
    <title>
        <?php echo $title; ?>
    </title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
        <link href="../assets/libs/tabler/css/tabler.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/libs/tabler/css/tabler-payments.min.css">
        <link href="../assets/css/hover-min.css" rel="stylesheet">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script defer src="https://unpkg.com/swup@3"></script>
        <link href="../assets/css/global.css" rel="stylesheet">
        <script async src="https://unpkg.com/es-module-shims@1.6.3/dist/es-module-shims.js"></script>
        <script src="https://kit.fontawesome.com/9621f1d513.js" crossorigin="anonymous"></script>
        <script type="importmap">
        {
            "imports": {
            "three": "https://unpkg.com/three@0.151.3/build/three.module.js",
            "three/addons/": "https://unpkg.com/three@0.151.3/examples/jsm/"
            }
        }
        </script>
        <script defer type="module" src="../assets/js/main.js"></script>
    <?php echo $styles; ?>
</head>

<body id='body' class="theme-light w-full hover:no-underline">
