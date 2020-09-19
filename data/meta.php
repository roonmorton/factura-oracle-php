<?php
    
include_once "dataBase.php";

class meta{
    private $conDB;
    
    public function __construct(){
        $this->conDB = new DataBase();
    }
    
    public function noFactura(){
        $query = "select count(*) facturas from factura";
        $this->conDB->consulta($query);
        return $this->conDB->obtenerData();
    }
    public function facturaSinCliente(
        $codFactura,
        $nombreCliente, 
        $nitCliente, 
        $direccionCliente,
        $tipoPago,
        $monto,
        $articulos
    ){
        $query = "BEGIN FacturarSinCliente(:nombre,:direccion,:nit,:facCodigo,:monto,:tipo); END;";
        $this->conDB->parse($query);
        $this->conDB->vincular(':nombre',$nombreCliente);
        $this->conDB->vincular(':direccion',$direccionCliente);
        $this->conDB->vincular(':nit',$nitCliente);
        $this->conDB->vincular(':facCodigo',$codFactura);
        $this->conDB->vincular(':monto',$monto);
        $this->conDB->vincular(':tipo',$tipoPago);
        $this->conDB->Ejecutar();
        $this->detalleFactura($codFactura,$articulos);
        $this->conDB->cerrarConexion();
        
    }
    
    public function facturaConCliente(
        $nit,
        $codFac,
        $tipo,
        $monto,
        $articulos
    ){
        $query = "BEGIN facturarConCliente(:nit,:facCodigo,:pago,:monto); END;";
        $this->conDB->parse($query);
        $this->conDB->vincular(':nit',$nit);
        $this->conDB->vincular(':facCodigo',$codFac);
        $this->conDB->vincular(':pago',$tipo);
        $this->conDB->vincular(':monto',$monto);
        $this->conDB->Ejecutar();
        $this->detalleFactura($codFac,$articulos);
        $this->conDB->cerrarConexion();
        
    }
    
    public function cliente($nit){
        $query = "select * from cliente where nit = '".$nit."'";
        $this->conDB->consulta($query);
        $d = $this->conDB->obtenerData();
        if(isset($d->NIT)){
            return true;
        }else
            return false;
    }
    
    public function detalleFactura($codFac,$articulos){
        foreach($articulos as $articulo){
            $query = "BEGIN detalleFactura(:facCodigo,:artCodigo,:cantidad);END;";
            $this->conDB->parse($query);
            $this->conDB->vincular('facCodigo',$codFac);
            $this->conDB->vincular('artCodigo',$articulo->codigo);
            $this->conDB->vincular('cantidad',$articulo->cantidad);
            $this->conDB->Ejecutar();
        }
    }
    
    
    public function obtenerFacturas(){
        $query = "select f.codigo, f.monto, f.fecha, c.nombre from factura f
                    join cliente c
                    on f.cliente_nit = c.nit order by f.codigo desc";
        $this->conDB->consulta($query);
        $factura = array();
        while($r = $this->conDB->obtenerData()){
            array_push($factura,$r);
        }
        return $factura;    
    }
    
    public function detallarFactura($id){
        $query = "select f.codigo codigo, f.monto,f.fecha fecha, c.nit NIT, c.nombre                                     nombre,c.direccion, a.descripcion, df.cantidad, a.precio from factura f
                    join cliente c
                    on f.cliente_nit = c.nit
                    join detalle_factura df
                    on df.factura_codigo = f.codigo
                    join articulo a
                    on df.articulo_codigo = a.codigo where f.codigo = '$id'";
        $this->conDB->consulta($query);
        $factura = array();
        while($r = $this->conDB->obtenerData()){
            array_push($factura,$r);
        }
        return $factura;
    }
    
    public function busquedaArticulos($search){
        $query = "select * from articulo where codigo like '%".strtoupper($search[0])."%' or descripcion like '%".strtoupper($search[0])."%'";
        $this->conDB->consulta($query);
        $articulos = array();
        while($row = $this->conDB->obtenerData()){
            array_push($articulos,$row);
        }
        return $articulos;
    }
    
    
    public function insertarArticulo($codigo,$descripcion,$precio,$existencia){
        $query = "BEGIN nuevoArticulo(:codigo,:descripcion,:precio,:existencia); END;";
        $this->conDB->parse($query);
        $this->conDB->vincular(':codigo',$codigo);
        $this->conDB->vincular(':descripcion',$precio);
        $this->conDB->vincular(':precio',$descripcion);
        $this->conDB->vincular(':existencia',(int)$existencia);
        $this->conDB->Ejecutar();
        $this->conDB->cerrarConexion();
    }
    
    public function updateArticulo($codigo,$descripcion,$precio,$existencia){
        #$query = "insert into articulo values(incremento.NextVal,'$precio','$existencia')";
        
    }
    
    public function obtenerArticulos(){
        $query = "select * from articulo";
        $this->conDB->consulta($query);
        $articulos = array();
        while($r = $this->conDB->obtenerData())
            array_push($articulos,$r);
        return $articulos;
    }
    
    public function actualizarExistenciaArticulo($codigo,$existencia){
        $query = "update articulo set existencia = $existencia where codigo = $codigo";
        $this->conDB->consulta($query);
    }
}

?>