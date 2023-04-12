<?php
if($_SERVER['REQUEST_URI'] == '/cms_oop/index.php' ) {
    header('Location: /cms_oop/');
}
$title = 'Home page';
ob_start();
?>

<h1>Home Page</h1>

<?php $content = ob_get_clean();
include 'app/views/layout.php';
?>
