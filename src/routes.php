<?php
require_once 'config/database.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        include 'views/home.php';
        break;
    case 'products':
        include 'views/products.php';
        break;
    default:
        include 'views/404.php';
        break;
}
?>
