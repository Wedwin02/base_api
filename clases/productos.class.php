<?php 
require_once "conexion/conexion.php";
require_once "respuestas.class.php";


class productos extends conexion{
    private $table = "productos";
    private $ID  = "";
    private $NOMBRE = "";
    private $CODIGO = "";
    private $MARCA = "";
    private $PESO = "";

    public function listaProductos($pagina = 1){
        $inicio = 0;
        $cantidad = 100;
        if($pagina > 1){
            $inicio = ($cantidad *($pagina -1)) + 1;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT NOMBRE, CODIGO, MARCA, PESO FROM ". $this->table." limit $inicio , $cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

    public function obtenerProductos($id){
        $query = "SELECT * FROM " . $this->table . " WHERE ID = '$id'";
    }

    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['NOMBRE']) || !isset($datos['CODIGO'])){
            return $_respuestas->error_400();
        }else{
            $this->NOMBRE = $datos['NOMBRE'];
            $this->CODIGO = $datos['CODIGO'];
            if(isset($datos['MARCA'])){$this->MARCA = $datos['MARCA'];}
            if(isset($datos['PESO'])){$this->PESO = $datos['PESO'];}


            $resp = $this->insertarProducto();
            //Obtenemos el ID insertado
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array("ID" => $resp);
                return $respuesta;
            }else{
                return $_respuestas->error_500();
            }
        }
    }

    public function put($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['ID'])){
            return $_respuestas->error_400();
        }else{
            $this->ID = $datos['ID'];
            if(isset($datos['NOMBRE'])){$this->NOMBRE = $datos['NOMBRE'];}
            if(isset($datos['CODIGO'])){$this->CODIGO = $datos['CODIGO'];}
            if(isset($datos['MARCA'])){$this->MARCA = $datos['MARCA'];}
            if(isset($datos['PESO'])){$this->PESO = $datos['PESO'];}


            $resp = $this->modificarProducto();

            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "ID" => $this->ID
                );
                return $respuesta;
            }else{
                return $_respuestas->error_500();
            }
        }
    }

    public function delete($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if (!isset($datos['ID'])) {
            return $_respuestas->error_400();
        } else {
            $this->ID = $datos['ID'];
            $resp = $this->eliminarProducto();
            if ($resp) {
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "ID" => $this->ID
                );
                return $respuesta;
                
            }else{
                return $_respuestas->error_500();
            }
        }
        

    }

    private function insertarProducto(){
        $query = "INSERT INTO ". $this->table . "(NOMBRE , CODIGO , MARCA , PESO ) 
        values 
        ('" . $this->NOMBRE .  "' , '" . $this->CODIGO . "' , '" . $this->MARCA .  "' , '"  . $this->PESO .  "')" ;
        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    private function modificarProducto(){
        $query = "UPDATE " . $this->table . " SET NOMBRE ='" . $this->NOMBRE . "', CODIGO = '" . $this->CODIGO . "', MARCA = '" . $this->MARCA . "', PESO = '" . $this->PESO . "' WHERE ID = '" . $this->ID . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

    private function eliminarProducto(){
        $query = "DELETE FROM " . $this->table . " WHERE ID = '" . $this->ID . "'";
        $resp = parent::nonQuery($query);
        if ($resp>=1) {
            return $resp;
        }else{
            return 0 ;
        }
    }
}
?> 