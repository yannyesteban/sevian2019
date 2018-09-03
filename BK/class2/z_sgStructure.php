<?php
include ("functions.php");
include ("sg_html.php");
include ("sgHTMLDoc.php");
//include ("svForm.php");
include ("_sgTab.php");
class _sgStructure extends sgHTML{
	
	private $index = 0;
	private $e = array();
	private $_last = false;


	private $_pages = array();
	private $_page = false;
	
	private $_tabs = array();
	public $_tab = false;

	private $_tables = array();
	private $_table = false;


	private $_layers = array();
	private $_layer = false;
	
	
	private $mainPanel=false;
	private $name=false;
	private $config=false;

	public $_def = array();

	public function __construct($opt){
		
		//$this->tagName = "textarea";
		
		foreach($opt as $k => $v){
			$this->$k = $v; 
		}
		$this->_def = $opt;
		$this->_last = $this;
		$this->_config($this->config);
		
	}	
	
	
	public function _config($opt){
		foreach($opt as $k => $v){
			
			//$this->{$v["param"]}($v["value"]);
			
			
			if(method_exists($this, $v["param"])){

				$this->{$v["param"]}($v["value"]);
			}
			
		}
		
		
		
		
	}
	
	public function layer($opt){
		
		$e = new sgHTML($opt);
		
		$this->_last->appendChild($e);
		$this->e[] = $this->_last = $e;
		
		return $e;
		
	}


	public function tab($opt){
		
		$this->_tab = new _sgTab($opt);
		$this->_last->appendChild($this->_tab);
		
	}

	public function tabPage($opt){
		
		
		
		$e =$this->_tab->addPage($opt);
		$this->e[] = $this->_last = $e;
		
	}
	
	public function getJson(){
		
		return json_encode($this->_def, JSON_PRETTY_PRINT);	
		
	}
	
	
}// end class




$structure = array(
	
	"name"=>"sv",
	"_dimamic"=>true,
	"template"=>"<div id='tab'></div><div>--4----8--</div>",
	"title"=>"SEVIAN: 1.0 (2017)",
	"class"=>"sigefor",
	"mainPanel"=>4,
	"config"=>array(
		array("param"=>"layer", "value"=>array("tagName"=>"div","innerHTML"=>"Hola", "title"=>"jejeje")),
		array("param"=>"layer", "value"=>array("tagName"=>"div","innerHTML"=>"Adios", "title"=>"OK")),
		array("param"=>"layer", "value"=>array("tagName"=>"div","innerHTML"=>"A todo el Mundo", "title"=>"OHHH")),
		array("param"=>"tab", "value"			=>array("target"=>"", "class"=>"tab")),
		array("param"=>"tabPage", "value"		=>"Página 1"),
		array("param"=>"tabPage", "value"		=>"Página 2"),
		array("param"=>"setPanel", "value"	=>array("panel"=>4)),	
		array("param"=>"setPanel", "value"	=>array("panel"=>5)),
		
	)

);


define ("SS_CHARSET", "utf-8");
$d = new sgHTMLDoc;
$d->appendCssSheet("../_js/sevian1.css");
$d->appendCssSheet("../themes/calido/css/popup.css");
$d->appendCssSheet("../themes/calido/css/tab.css");
$d->appendScriptDoc("../js/sgPopup.js", true);
$d->appendScriptDoc("../_js/_sgQuery.js", true);
$d->appendScriptDoc("../_js/_sgWinDB.js", true);




$d->appendScriptDoc("../_js/_sgPage.js", true);
$d->appendScriptDoc("../_js/_sgTab.js");
$d->appendScriptDoc("../_js/_sgTable.js");
$d->appendScriptDoc("../_js/_sevian.js", true);



$d->setTitle("Welcome");
//$d->body->appendChild($v);
//$d->appendScript($d->body->getScript(), true);

echo $d->render();

exit;

define ("SS_CHARSET", "utf-8");
$v = new _sgStructure($structure);
hr( $v->getJson());
$d = new sgHTMLDoc;
$d->appendCssSheet("../_js/sevian1.css");
$d->appendCssSheet("../themes/calido/css/popup.css");
$d->appendCssSheet("../themes/calido/css/tab.css");
$d->appendScriptDoc("../_js/_sgTab.js");
$d->setTitle("Welcome");
$d->body->appendChild($v);
//$d->appendScript($d->body->getScript(), true);

echo $d->render();


?>