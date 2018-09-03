<?php 
/*****************************************************************
creado: 23/04/2007
modificado: 03/11/2011
modificado: 03/13/2015
por: Yanny Nuñez
*****************************************************************/
//===========================================================


class cfg_command{

	public $sequence = "";
	public $elem = array();
	
	
	public $titulo = "yanny";
	public $parametros = "";
	public $status = "";

	public $modulo = "un Modulo";
	
	public $estructura = "";
	public $login = "";
	public $password = "";
	public $config_ini = "";
	
	
	public $panel_default = 4;


	//===========================================================
	public $vses = array();
	public $vform = array();
	public $vpara = array();
	public $vreg = array();
	public $vexp = array();
	public $vst = array();
	public $elems = array();

	public $signs = array();

	public $icnn = SS_CNN;
	public $icns = SS_CNS;
	
	
	
	//===========================================================
	public function __construct() {

		$this->v=new var_sevian;
	}// END FUNCTION
	//===========================================================
	public function init($data_elems) {

		
	}// end fucntion
	


	
	//===========================================================
	function extract_query($q){
		
		$exp = '
		{(
		(?:if\s*+:(?P<cond>(?P<sc>[^;"\']*+(?:"(?:[^"]*+(?:(?<=\\\)"[^"]*+)*+)" | \'(?:[^\']*+(?:(?<=\\\)\'[^\']*+)*+)\'))*+[^;"\']*+|(?P>sc));
			\s*then\s*:((?1)+)(?:\s*+else\s*+:((?1)+)?)\s*+endif\s*+;)
		|
		(?:if\s*+:((?P>cond);)\s*+then\s*+:((?1))(?:\s*+else\s*+:((?1)))?)
		|case;(when:((?P>cond));do:((?1)+))(
			(?:when:(?P>cond);do:(?1)+)*)(?:default:((?1)+))?endcase
		#|(?:for:[^;"\':]+;((?1)+)next)
		#|(?:while:((?P>cond);)((?1)+)wend)
		#|(?:do:((?1)+)while((?P>cond);))
		#|
		|
		#OLD: 
		#((\w+)\s*+:\s*+(?:"([^"]*+(?:(?<=\\\)"[^"]*+)*+)"|\'([^\']*+(?:(?<=\\\)\'[^\']*+)*+)\'|([^;"\':]+))\s*;)
		#NEW: 
		 ((\w+)\s*+:\s*+(?:"([^"]*+(?:(?<=\\\)"[^"]*+)*+)"|\'([^\']*+(?:(?<=\\\)\'[^\']*+)*+)\'|([^;"\':]*))\s*;)
		)}isx';
		if(preg_match_all($exp,$q,$c)){
			//print_r($c);
			return $c;
		}else{
			
			hr("Error: ".$q);
			//throw new Exception($q);
			//return array();	
			
		}// end if
	}// end function	
	public function setVar($q, $t, $data, $default = false){
		if($q=="" or count($data)==0){
			return $q;	
		}// end if
		
		//return $this->get_var($q, $t, $data);
		
		$exp="{
			(?:(?<![\{\\\])$t(\w++))
			|
			(?:\{$t(\w++)\})
			|
			(?:([\\\]($t\w++)))
			}isx";
			
			

		$q = preg_replace_callback($exp,
			function($i) use (&$data, $default){
				if(isset($data[$i[1]])){
					return $data[$i[1]];
				}elseif(isset($i[2]) and $i[2] != ""){
					return $data[$i[2]];
				}elseif(isset($i[4])){
					return $i[4];
				}else{
					if($default !== false){
						return $default;	
					}else{
						return $i[0];
					}// end if
				}// end if
			},$q);
		return $q;		
	}// end function

	
	function get_var($q, $t, $data){
		
		if($q=="" or count($data)==0){
			return $q;	
		}// end if
		
		$exp="
		{
		(?:'(?:[^']*+(?:(?<=\\\)'[^']*+)*+)')
		|	
		(?:\"(?:[^\"]*+(?:(?<=\\\)\"[^\"]*+)*+)\")
		|	
		#(?:@(\w+))
		(?:[^\"']*+)	
		}isx";
		
		if(preg_match_all($exp,$q,$c)){
			$qq="";
			foreach($c[0] as $k => $v){
				if(substr($v,0,1)!="'"){
					$v = preg_replace("{(?<!\\\)$t}","\\".$t,$v);	
				}// end if
				$qq .= $v;
			}// next
		}// end if
		//$q2 = preg_replace_callback($exp,'$pruebas::z',$q);
		//$qq = preg_replace("{\\\\($t(\w++))}e",'($data[($2)])?$data[($2)]:\'$1\'',$qq);
		$qq = preg_replace("{\\\\($t(\w++))}",'($data[($2)])?$data[($2)]:\'$1\'',$qq);
		return $qq;
	}// end function
	
	public function getList($q){
		$exp = '{[^,]+\'.+\'|[^,]+\(.+\)|[^,]+}isx';		
		
		if(preg_match_all($exp, $q, $c)){
			return $c[0];
		}else{
			throw new Exception($q);
			return array();	
			
		}// end if		
		
	}// end function


	public function evalVar($q="", $default=false){
		
		if($q==""){
			return $q;
		}//end if
		
		$q = $this->setVar($q, "@", $this->v->ses, $default);
		$q = $this->setVar($q, "\#", $this->v->req, $default);
		$q = $this->setVar($q, "&EX_", $this->v->exp, $default);
		$q = $this->setVar($q, "&", $this->v->rec, $default);
		
		
		//$q = $this->get_var($q, "&PR_", $this->v->par);
		
		return $q;
	}// end function
	
	public function eval_var($q="", $default=false){
		if($q==""){
			return $q;
		}//end if
		$q = $this->setVar($q, "@", $this->v->ses, $default);
		$q = $this->setVar($q, "\#", $this->v->req, $default);
		$q = $this->setVar($q, "&EX_", $this->v->exp, $default);
		$q = $this->setVar($q, "&", $this->v->rec, $default);
		
		//$q = $this->get_var($q, "&PR_", $this->v->par);
		
		return $q;
	}// end function	
	public function eval_varNO($q="", $con_comillas = false){
		$q = $this->get_var($q, "@", $this->v->ses);
		$q = $this->get_var($q, "#", $this->v->req);
		$q = $this->get_var($q, "&EX_", $this->v->exp);
		//$q = $this->get_var($q, "&PR_", $this->v->par);
		$q = $this->get_var($q, "&", $this->v->rec);
		return $q;
	}// end function
	

	public function getParam($q = "", &$log = false){
		$p = array();

		$log["qi"] = $q;	
		$q = $this->eval_var($q);
		
		try{
			$log["error"] = 0;	
			$this->eval_param($q, $p);	
		}catch(Exception $e){
			$log["error"] = 1;	
			//$log = $e->getMessage();
		}// end try
		$log["q"] = $q;	
		//$this->eval_param($q, $p);		
		return $p;
	}// end function



	public function get_param($q = "", &$log = false){
		$p = array();

		$log["qi"] = $q;	
		$q = $this->eval_var($q);
		
		try{
			$log["error"] = 0;	
			$this->eval_param($q, $p);	
		}catch(Exception $e){
			$log["error"] = 1;	
			//$log = $e->getMessage();
		}// end try
		$log["q"] = $q;	
		//$this->eval_param($q, $p);		
		return $p;
	}// end function


	public function eval_sequence($q){
		if(trim($q) == ""){
			return "";	
		}// end if
		$c = $this->extract_query($q);
		foreach($c[0] as $k => $v){
			if($c[2][$k]!=""){
				$c[2][$k] = $this->eval_var($c[2][$k]);
				eval("\$eval=".$c[2][$k].";");
				if($eval){
					$aux = $c[4][$k];
				}else{
					$aux = $c[5][$k];
				}// end if
				$this->eval_sequence($aux);
			}elseif($c[6][$k] != ""){
				$c[6][$k] = $this->eval_var($c[6][$k]);
				eval("\$eval=".$c[6][$k].";");
				if($eval){
					$aux = $c[7][$k];
				}else{
					$aux = $c[8][$k];
				}// end if
				$this->eval_sequence($aux);
			}elseif($c[9][$k] != ""){
				$c[10][$k] = $this->eval_var($c[10][$k]);
				eval("\$eval=".$c[10][$k].";");
				if($eval){
					$aux = $c[11][$k];
				}elseif($c[12][$k] != ""){
					$this->eval_sequence("case;".$c[12][$k]."default:".$c[13][$k]."endcase;");
				}elseif($c[13][$k]!=""){
					$aux = $c[13][$k];
				}else{
					$aux="";
				}// end if					
				$this->eval_sequence($aux);
			}elseif($c[16][$k] != ""){
				$this->sequence($c[15][$k], $c[16][$k]);
			}elseif($c[17][$k] != ""){
				$this->sequence($c[15][$k], $c[17][$k]);
			}else{
				$this->sequence($c[15][$k], $c[18][$k]);
			}//end if
		}// next
	}// end function

	
	public function eval_param($q="", &$p){

		if(trim($q) == ""){
			return "";	
		}// end if

		$c = $this->extract_query($q);
					
		foreach($c[0] as $k => $v){
			if($c[2][$k] != ""){
				eval("\$eval=".$c[2][$k].";");
				if($eval){
					$aux = $c[4][$k];
				}else{
					$aux = $c[5][$k];
				}// end if
				$this->eval_param($aux, $p);
			}elseif($c[6][$k] != ""){
				eval("\$eval=".$c[6][$k].";");
				if($eval){
					$aux = $c[7][$k];
				}else{
					$aux = $c[8][$k];
				}// end if
				$this->eval_param($aux, $p);
			}elseif($c[9][$k] != ""){
				eval("\$eval=".$c[10][$k].";");
				if($eval){
					$aux = $c[11][$k];
				}elseif($c[12][$k] != ""){
					$this->eval_param("case;".$c[12][$k]."default:".$c[13][$k]."endcase;",$p);
				}elseif($c[13][$k] != ""){
					$aux = $c[13][$k];
				}else{
					$aux="";
				}// end if					
				$this->eval_param($aux, $p);
			}elseif($c[16][$k] != ""){
				$p[$c[15][$k]] = $c[16][$k];
			}elseif($c[17][$k] != ""){
				$p[$c[15][$k]] = $c[17][$k];
			}else{
				$p[$c[15][$k]] = $c[18][$k];
			}//end if
		}// next
	}// end function
	

	function extractSQL($q, $onlyValue = true){
		
		// se puede mejorar, 13/04/2015 11:25pm
		// un (;) encerrado entre comillas  
		
		$exp = '
		{
			(\s*+(?:"([^"]*+(?:(?<=\\\)"[^"]*+)*+)"|([^;"]+))\s*;)
		}isx';
		if(preg_match_all($exp, $q, $c)){
			$aux = array();
			if($onlyValue){
				foreach($c[0] as $k => $v){
					if($c[2][$k]!=""){
						$aux[]=$c[2][$k];
					}elseif($c[3][$k]!=""){
						$aux[]=$c[3][$k];
					}else{
						$aux[]=$c[4][$k];
					}// end if
				}// next
				return $aux;
			}// end if
			return $c;
		}else{
			return array();	
		}// end if
	}// end function
	
	public function getCmd($q){
		global $seq;
		
		if(trim($q) == ""){
			return "";	
		}// end if
		$c = $seq->cmd->extract_query($q);
		foreach($c[0] as $k => $v){
			if($c[2][$k]!=""){
				$c[2][$k] = $seq->cmd->eval_var($c[2][$k]);
				eval("\$eval=".$c[2][$k].";");
				if($eval){
					$aux = $c[4][$k];
				}else{
					$aux = $c[5][$k];
				}// end if
				$this->evalSequence($aux);
			}elseif($c[6][$k] != ""){
				$c[6][$k] = $seq->cmd->eval_var($c[6][$k]);
				eval("\$eval=".$c[6][$k].";");
				if($eval){
					$aux = $c[7][$k];
				}else{
					$aux = $c[8][$k];
				}// end if
				$this->evalSequence($aux);
			}elseif($c[9][$k] != ""){
				$c[10][$k] = $seq->cmd->eval_var($c[10][$k]);
				eval("\$eval=".$c[10][$k].";");
				if($eval){
					$aux = $c[11][$k];
				}elseif($c[12][$k] != ""){
					$this->evalSequence("case;".$c[12][$k]."default:".$c[13][$k]."endcase;");
				}elseif($c[13][$k]!=""){
					$aux = $c[13][$k];
				}else{
					$aux="";
				}// end if					
				$this->evalSequence($aux);
			}elseif($c[16][$k] != ""){
				$this->_sequence($c[15][$k], $c[16][$k]);
			}elseif($c[17][$k] != ""){
				$this->_sequence($c[15][$k], $c[17][$k]);
			}else{
				$this->_sequence($c[15][$k], $c[18][$k]);
			}//end if
		}// next
	}// end function	
	
	public function addSession($vars){
		$this->v->ses = array_merge($this->v->ses, $vars);
		
	}// end fucntion
	public function addExpression($vars){
		$this->v->exp = array_merge($this->v->exp, $vars);
		
	}// end fucntion
	public function addRequest($vars){
		$this->v->req = array_merge($this->v->req, $vars);
		
	}// end fucntion

	
}// end class
?>