<?php
include_once '../config/db_connection.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?php echo $title; ?>
    </title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
        <link href="../assets/libs/tabler/css/tabler.min.css" rel="stylesheet">
        <link href="../assets/css/hover-min.css" rel="stylesheet">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script defer src="https://unpkg.com/swup@3"></script>
        <link href="../assets/css/global.css" rel="stylesheet">
        <script async src="https://unpkg.com/es-module-shims@1.6.3/dist/es-module-shims.js"></script>
        <script type="importmap">
        {
            "imports": {
            "three": "https://unpkg.com/three@0.151.3/build/three.module.js",
            "three/addons/": "https://unpkg.com/three@0.151.3/examples/jsm/"
            }
        }
        </script>
        <script defer type="module" src="../assets/js/home.js"></script>
    <?php echo $styles; ?>
</head>

<body id='body' class="theme-dark w-full hover:no-underline">
