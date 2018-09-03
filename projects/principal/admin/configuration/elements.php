<?php

$cls_elements = [
	'form' => [
		'file' 	=> 'SgForm.php',
		'class' => 'SgForm'],
	'menu' 		=> [
		'file' 	=> 'SgMenu.php',
		'class' => 'SgMenu'],
	'fragment' 	=> [
		'file' 	=> 'ssFragment.php',
		'class' => 'ssFragment'],
	'procedure' => [
		'file' 	=> 'SsProcedure.php',
		'class' => 'SsProcedure'],
	'sgForm' 	=> [
		'file' 	=> 'Sigefor/Form.php',
		'class' => 'Sevian\Sigefor\Form'],
	'ImagesDir' => [
		'file' 	=> 'ImagesDir.php',
		'class' => 'Sevian\ImagesDir'],
	'menuX' 	=> [
		'file' 	=> 'mMenu.php',
		'class' => 'Sevian\mMenu'],
	
];

Sevian\S::elementsLoad($cls_elements);

?>