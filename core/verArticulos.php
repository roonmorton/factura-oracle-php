<?php

include_once "../data/meta.php";
$meta = new meta();
echo json_encode($meta->obtenerArticulos());

?>