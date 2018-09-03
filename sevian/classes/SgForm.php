<?php
//use Sevian;

class SgForm extends Sevian\Panel{
	
	public $title = "FORM 3.0";
	
	public function render(){
		$titulo = "";
		
		
		//print_r($s);
		
		//echo $s->test();
		
		$cn = Sevian\Connection::get();
		
		$cn->query = "SELECT a.*, status, status  FROM cfg_actions as a where action='guardar'";
		
		
		$info = $cn->infoQuery("select * from empresas");
		
		//print_r($info);
		
		$result = $cn->execute();
		
		if($rs = $cn->getData($result)){
			
			$titulo = $rs["title"];
		}
		
		$cn = Sevian\Connection::get("sevian");
		
		$cn->query = "select * from test";
		
		$result = $cn->execute();
		
		if($rs = $cn->getDataAssoc($result)){
			
			//echo "<br>......".$rs["ciudad"];
		}
		
		//print_r($cn->infoQuery("select TT.*, ciudad, ciudad as cc from test as TT"));
		
		//$bd = new PDO('pgsql:dbname=sevian host=localhost', "postgres", "postgres");
		
		//$s = $bd->query("select * from \"test\" as m ");
		
		
		//$f = $s->getColumnMeta(1);
		
		//print_r($f);
		/*
		 foreach ($bd->query("select * from test") as $row) {
			
			print $row['cedula'] . "\t";
			print $row['nombre'] . "\t";
			print $row['ciudad'] . "\n";
		}
		
		*/
		
		
		
		
		
		
		
		return "El Formulario { $titulo }($this->panel)".'<input type="submit" name="submit1" id="submit1" value="Enviar">';
		
	}
	
}


?>