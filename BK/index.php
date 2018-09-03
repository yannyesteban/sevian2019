<?php


//sleep(5);



$f = new stdClass;
$f->cedula = $_POST["cedula"];
$f->targetId = "s1";
$f->html = "<b class='z'>Tres</b>  Chao";
$f->script = "";
$f->css = ".z{font-weight:bold;color:orange;}";
$f->typeAppend = 0;


$f->propertys = new stdClass;
$f->style = new stdClass;
//$f->propertys->innerHTML = "QUE";
//$f->propertys->placeholder = "....SELECCIONE";
$f->style->border = "2px solid green";
$f->style->color = "green";

$f->options = array();

for($i=0;$i<20;$i++){
	
	$opt = $f->options[$i] = new stdClass;
	
	$opt->value = $i;
	$opt->text = "Text $i";
}

$f->propertys->value = round(rand(0,19), 0);
echo json_encode($f); 

?>
