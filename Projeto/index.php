<!-- Homepage do site -->
<?php 

$template = array(
    'titulo' =>  'Loja Online &mdash; GM Supermercado',
    'scripts' => ['static/js/site.js'],
    'styles' => ['static/css/site.css']
);
include __DIR__ . '/src/template/header.php';

?>

<h1>Homepage</h1>

<?php include __DIR__ . '/src/template/footer.php'; ?>