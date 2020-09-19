<?php
require_once "config.php";

class dataBase{
    private $con = null;
    private $statement;
    private $estado = 1;
    
    public function __construct(){
        if($this->con == null){
            if(!($this->con = oci_connect("ISERTEC","admin1342",'localhost/XE'))){
                $this->estado = 0;
            }
        }
    }
    
    public function consulta($query){
        if($this->estado == 1){
            $this->statement = oci_parse($this->con,$query);
            if(!(oci_execute($this->statement)))
                $this->estado = 0;
        }    
    }
    
    public function parse($query){
        $this->statement = oci_parse($this->con,$query);
    }
    
    public function vincular($id,$var){
        oci_bind_by_name($this->statement,$id,$var);    
    }

    public function ejecutar(){
        if($this->estado == 1){
            oci_execute($this->statement);
        }
    }
    
    public function obtenerData(){
        return oci_fetch_object($this->statement);
    }
    
    public function cerrarConexion(){
        if($this->estado == 1)
            oci_close($this->con);
    }   
    public function estado(){
        return $this->estado;
    }
}

?>