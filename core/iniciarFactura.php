<?php

include_once "../data/meta.php";

$dat = json_decode(file_get_contents("php://input"));
$meta = new meta();
if($dat){    
    echo json_encode($meta->detallarFactura($dat->no));
}else{
    $MINFAC  = 1000;
    $MAXFAC = 10000;
    $factura = array(
        "no" => ($meta->noFactura()->FACTURAS)+$MINFAC,
        "fecha" => date("d/m/Y"),
        "direccion" => "",
        "nit" => "",
        "pago" => "0",
        "monto" => 0,
        "bruto" => 0,
        "articulos" => array()
    );
    echo json_encode($factura); 
}

?>
