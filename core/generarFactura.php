<?php

include_once "../data/meta.php";

$factura = json_decode(file_get_contents("php://input"));
$meta = new meta();
if($meta->cliente($factura->nit)){
    $meta->facturaConCliente(
        $factura->nit,
        $factura->no,
        $factura->fecha,
        $factura->monto,
        $factura->pago,
        $factura->articulos);
    //echo "cliente exite";
}else
    $meta->facturaSinCliente(
    $factura->no,
    $factura->fecha,
    strtoupper($factura->nombre),
    $factura->nit,
    strtoupper($factura->direccion),
    $factura->pago,
    $factura->monto,
    $factura->articulos);
?>