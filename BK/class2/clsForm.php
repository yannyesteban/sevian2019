<?php
class clsForm extends sg_panel{
	public $element = "form";
	
	public $icnn = SS_CNN;
	public $icns = SS_CNS;
	public $icnd = SS_CND;	

	public $cnn = false;
	public $cns = false;
	public $cnd = false;	



	protected $t_forms = "";
	protected $t_form_fields = "";
	protected $t_ele_met = "";
	protected $t_grp_usr = "";
	protected $t_grp_ele = "";
	protected $t_usr_ele = "";


	
	public function __construct() {
		$this->cnn = conection($this->icnn);
		$this->cnd = conection($this->icnd);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
		
		$this->t_forms = TABLE_PREFIX."forms";
		$this->t_form_fields = TABLE_PREFIX."form_fields";		
		$this->t_ele_met = TABLE_PREFIX."ele_met";
		$this->t_grp_usr = TABLE_PREFIX."grp_usr";
		$this->t_grp_ele = TABLE_PREFIX."grp_ele";
		$this->t_usr_ele = TABLE_PREFIX."usr_ele";		
		
	}// end function	
	
	public function execute($name="", $method=""){
		global $seq;


		if(isset($this->eparams["design_mode"])){
			
			$cn = &$this->cnd;
			$this->cnn = conection($this->icns);
			$this->cns = $this->cnd;
		}else{
			$cn = &$this->cns;
		}

		if($name!==""){
			$this->form = $name;
		}// end if
		if($method!==""){
			//$this->method = $method;
		}// end if
		

		$this->logI();
		
		
		//$cn = &$this->cns;
		
		
		//hr($this->seq->st->get_template("sg_principal"));
		
		$cn->query = "
			SELECT DISTINCT
				f.form, f.title, f.query, f.class, f.template, f.template_panel, f.tabs, f.groups, f.menu,
				CONCAT(IFNULL(f.params,''), IFNULL(em.params,'')) as params,
				CONCAT(IFNULL(f.expressions,''), IFNULL(em.expressions,'')) as expressions,
				
				CONCAT(IFNULL(f.signs,''), IFNULL(em.signs,'')) as signs,
				CONCAT(IFNULL(f.eval_signs,''), IFNULL(em.eval_signs,'')) as eval_signs,

				CONCAT(IFNULL(f.functions,''), IFNULL(em.functions,'')) as functions,
				CONCAT(IFNULL(f.events_signs,''), IFNULL(em.events_signs,'')) as events_signs,


				f.style, f.propertys, f.title_style, f.title_propertys,
				
				
				f.elem_style,f.elem_propertys, f.elem_title_style, f.elem_title_propertys

			FROM $this->t_forms as f 

			LEFT JOIN $this->t_ele_met as em 
				ON em.element = '$this->elem_type' AND em.name = f.form AND em.method = '$method'  
			LEFT JOIN $this->t_grp_usr as gu 
				ON gu.user = '$this->user'
			LEFT JOIN $this->t_grp_ele as g 
				ON g.element = '$this->elem_type' AND g.name = f.form AND g.group = gu.group 
			LEFT JOIN $this->t_usr_ele as u 
				ON u.element = '$this->elem_type' AND u.name = f.form AND u.user = gu.user
			WHERE f.form = '$this->form'
				AND (g.allow=1 OR g.name IS NULL)
				AND (u.allow=1 OR u.name IS NULL)";



			

		
		
		$result = $cn->execute($cn->query);
		$log = false;
		if($rs = $cn->getDataAssoc($result)){
			
			foreach($rs as $k => $v){
				$this->$k = $v;
			}// next
			if($prop = $seq->cmd->get_param($this->elem_params, $log["elem_params"])){
				foreach($prop as $k => $v){
					$this->$k = $v;
				}// next
			}// end if
			
			if($prop = $seq->cmd->get_param($this->params, $log["params"])){
				foreach($prop as $k => $v){
					$this->$k = $v;
				}// next
			}// end if
			
			$proc_before="proc_".$this->method."_before";
			if(isset($this->$proc_before)){
				$seq->procedure($this->$proc_before);
			}// end if
			
			$seq_before="seq_".$this->method."_before";
			if(isset($this->$seq_before)){
				$seq->procedure($this->$seq_before);
			}// end if
		
			if($prop = $seq->cmd->get_param($this->tabs, $log["tabs"])){
				foreach($prop as $k => $v){
					$this->iTabs[$k] = $v;
				}// next
				$this->wTabs = true;
			}// end if

			if($prop = $seq->cmd->get_param($this->groups, $log["groups"])){
				foreach($prop as $k => $v){
					$this->igroups[$k] = $v;
				}// next
			}// end if

			if($prop = $seq->cmd->get_param($this->signs, $log["signs"])){
				foreach($prop as $k => $v){
					$this->isigns[$k] = $v;
				}// next
			}// end if

			if($prop = $seq->cmd->get_param($this->eval_signs, $log["eval_signs"])){
				foreach($prop as $k => $v){
					$this->esigns[$k] = $v;
				}// next
			}// end if

			if($prop = $seq->cmd->get_param($this->functions, $log["functions"])){
				foreach($prop as $k => $v){
					$this->f[$k] = $v;
				}// next
			}// end if
			
			$this->value_ref = $this->get_idrecord($this->use_ref);

			if($this->target_tables){
				$this->_targetTable = array_flip(explode(",", $this->target_tables));
			}
			
		}else{

			$this->logE("FORM", "", "", "NO FOUND");
			$this->query = "SELECT * FROM $this->form";
			
		}// end if	
		if($log){
			$this->logP(false, $log);
		}// end if
		
		
		
		$this->cfgFields($seq->cmd->eval_var($this->query), $method);
		
	}// end function	
	
	
	
}// end class
?>