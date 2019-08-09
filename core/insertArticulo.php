<?php
include_once "../data/meta.php";

$articulo = json_decode(file_get_contents("php://input"));

//echo "codigo".$articulo->codigo;
$meta = new meta();
$meta->insertarArticulo(strtoupper($articulo->codigo),strtoupper($articulo->descripcion),$articulo->precio,$articulo->existencia);

?>