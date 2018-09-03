<?php
namespace Sevian;

class Valid{
	
	public static $msg = array();

	public static function initMsg($lang="esp", $msg){
		self::$msg = $msg[$lang];
		
	}
	
	public static function evalMsg($key, $rules, $value, $title){
		
		if(isset($rules[$key]["msg"])){
			$msg = $rules[$key]["msg"];
		}else{
			$msg = self::$msg[$key];
		}
		
		$msg = str_replace("{=title}", $title, $msg);
		
		if(isset($rules[$key]["value"])){
			$msg = str_replace("{=value}", $rules[$key]["value"], $msg);
		}
		
		return $msg;
	}
	
	public static function send($rules, $value, $title, $masterData){
		
		foreach($rules as $key => $rule){
			
			$error = false;
			
			switch($key){
				case "required":
					if(trim($value) == ""){
						$error = true;
					}
					break;
				case "alpha":
					if(!preg_match('/^([ A-ZáéíóúÁÉÍÓÚüÜñÑ]+)$/i', $value)){
						$error = true;
					}
					break;
				case "alphanumeric":
					if(!preg_match('/^([\\w]+)$/i', $value)){
						$error = true;
					}
					break;
				case "nospaces":
					if(preg_match('/[ ]+/i', $value)){
						$error = true;
					}
					break;
				case "numeric":
					if(!preg_match('/^[-]?\\d*\\.?\\d*$/i', $value)){
						$error = true;
					}
					break;
				case "integer":
					if(!preg_match('/^[-]?\\d*$/i', $value)){
						$error = true;
					}
					break;
				case "positive":
					if(!preg_match('/^\\d*\\.?\\d*$/i', $value)){
						$error = true;
					}
					break;
				case "exp":
					if(!preg_match($rule["value"], $value)){
						$error = true;
					}
					break;
				case "email":
					if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
						$error = true;
					}
					break;
				case "greater":
					if($value <= $rule["value"]){
						$error = true;
					}
					break;	
				case "less":
					if($value >= $rule["value"]){
						$error = true;
					}
					break;	
				case "greatestequal":
					if($value < $rule["value"]){
						$error = true;
					}
					break;	
				case "lessequal":
					if($value > $rule["value"]){
						$error = true;
					}
					break;	
				case "condition":
					if(!this.evalCondition($rule["value"], $value)){
						$error = true;
					}
					break;	
				case "date":
					if(!this.evalDate(value, "y-m-d")){
						return this.evalMsg((rules[rule].msg) || V_DATE);
					}
					break;	
			}
			if($error){
				return self::evalMsg($key, $rules, $value, $title);
			}
			
		}
		
	}
	
}

Valid::initMsg("spa", 
	array(
		"spa"=>array(
			"required"		=>"El campo {=title} es obligatorio",
			"alpha"			=>"El campo {=title} solo debe tener caracteres alfabéticos",
			"alphanumeric"	=>"El campo {=title} solo debe tener caracteres alfanuméricos",
			"nospaces"		=>"El campo {=title} no debe tener espacio en blancos",
			"numeric"		=>"El campo {=title} debe ser un valor numérico",
			"positive"		=>"El campo {=title} debe ser un número positivo",
			"integer"		=>"El campo {=title} debe ser un número entero",
			"email"			=>"El campo {=title} no es una dirección de correo válida",
			"date"			=>"El campo {=title} no es una fecha válida",
			"time"			=>"El campo {=title} no es una hora válida",
			"exp"			=>"El campo {=title} no coincide con un patrón válido",
			"minlength"		=>"La longitud en caracteres del campo {=title}, debe ser mayor que {=value}",
			"maxlength"		=>"La longitud en caracteres del campo {=title}, debe ser menor que {=value}",
			"greater"		=>"El campo {=title} debe ser mayor que {=value}",
			"less"			=>"El campo {=title} debe ser menor que {=value}",
			"greatestequal"	=>"El campo {=title} debe ser mayor o igual que {=value}",
			"lessequal"		=>"El campo {=title} debe ser menor o igual que {=value}",
			"condition"		=>"El campo {=title} no cumple la condición predefinida",
			
			)
		)
	);

?>