<?php
include_once "../data/meta.php";
$cliente = json_decode(file_get_contents("php://input"));

//echo $cliente->nit;

$meta = new meta();
if($c = $meta->cliente($cliente->nit))
    echo json_encode($c);

?>