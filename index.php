<?php
session_start();
require_once 'admin/src/php/utils/all_include.php';
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PcPro - PC Gaming</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="admin/assets/css/style.css">
</head>
<body>
<header>
    <?php include 'admin/src/php/utils/header.php'; ?>
</header>

<main>
    <?php
    $page = $_GET['page'] ?? 'accueil';
    $path = "admin/content/$page.php";

    if (file_exists($path)) {
        include $path;
    } else {
        include 'content/page404.php';
    }
    ?>
</main>

<footer>
    <?php include 'admin/src/php/utils/footer.php'; ?>
</footer>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="admin/assets/js/sur_mesure.js"></script>
<script src="admin/assets/js/ui.js"></script>
<script src="admin/assets/js/admin_ajax.js"></script>
<script src="admin/assets/js/admin_filtre.js"></script>
</body>
</html>