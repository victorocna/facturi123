<?php include 'top.php'; ?>
<?php if (isset($_SESSION['id_user'])) { ?>
<?php include 'header.php'; ?>
<?php include 'menu.php'; ?>
<?php include 'module_home.php'; ?>
<?php include 'bottom.php'; ?>
<?php } ?>
<?php if (!isset($_SESSION['id_user'])) include 'module_logout.php'; ?>