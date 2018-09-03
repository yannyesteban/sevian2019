<?php
include("sgPostgres.php");
//include("cls_conexion_mysql.php");
//include("cls_mysql.php");
include("sgMysql.php");
class descrip_campo{
	var $tabla = "";
	var $campo = "";
	var $aux = "";
	var $tipo = "";
	var $longitud = "";
	var $primary = false;
	var $index = false;
	var $serial = false;
	var $default = "";
	var $null = true;
	var $unique = false;
	var $meta = "";
	var $num = false;
}// end class
class id_conexion{
	public $type = "";
	public $server = "";
	public $user = "";
	public $password = "";
	public $dbase = "";
	public $port = "";
	public $charset = "";
	
	function __construct($id="") {
		if($id==""){
			return false;
			
		}// end if
		$aux = explode(",", $id);
		$this->type = $aux[0];
		$this->server = $aux[1];
		$this->user = $aux[2];
		$this->password = $aux[3];
		$this->dbase = $aux[4];
		$this->port = $aux[5];

	}// end function
}// end class


$pr = array();
$cc = array();
function set_connections($p){
	global $pr, $cc;
	foreach($p as $k => $v){
		$pr[$k] = new id_conexion($v);
		$cc[$k] = new_conection(
			$pr[$k]->type,
			$pr[$k]->server,
			$pr[$k]->user,
			$pr[$k]->password,
			$pr[$k]->dbase,
			$pr[$k]->port
		); 
	}// next
}// end if



function conection($k = "_default", $nueva=false){
	global $pr, $cc;
	
	if(!$nueva){
		if($cc[$k]){
			return $cc[$k];
		}else{
			return $cc["_default"];
		}// end if
	}else{
	//hr($pr[$k]->bdatos);
		return new_conection(
			$pr[$k]->type,
			$pr[$k]->server,
			$pr[$k]->user,
			$pr[$k]->password,
			$pr[$k]->dbase,
			$pr[$k]->port
		); 
	}// end if
	
}// end function

function new_conection($type, $server="", $user="", $password="", $dbase="", $port="", $charset=""){
	switch(strtolower(trim($type))){
	case "mysql":
		//$cn = new cls_mysql($server,$user,$password,$dbase,$port);
		return new cls_mysql($server, $user, $password, $dbase, $port, $charset);
		break;
	case "mysqli":
		//$cn = new cls_mysql($server,$user,$password,$dbase,$port);
		return new sgMysql($server, $user, $password, $dbase, $port, $charset);
		break;
	
	case "postgres":
		return new sgPostgres($server, $user, $password, $dbase, $port, $charset);
		break;
	
	
	}// end switch
	

}// end function
?>