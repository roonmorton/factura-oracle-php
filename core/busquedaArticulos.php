<?php

include_once "../data/meta.php";
$query = json_decode(file_get_contents("php://input"));
$querys = explode(" ",$query->query);
//var_dump($querys);
$meta = new meta();
echo json_encode($meta->busquedaArticulos($querys));

?>