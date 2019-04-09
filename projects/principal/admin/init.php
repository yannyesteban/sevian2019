<?php


$structure = [
	
	"name"=>"ne",
	"templates"=>"main3",
	
	
];


$init = [
	'theme'=>'sevian',
	'title'=>'Sigefor 1.00',
	'templateName' => 'login',
	'elements' => [
		[
			'panel'		=> 4,
			'element'	=> 'sgForm',
			'name'		=> 'login',
			'method'	=> 'request',
			'designMode'=> true,
			'fixed'		=> true,
		],
		
		[
			'panel'		=> 44,
			'element'	=> 'menuD',
			'name'		=> 'uno_m',
			'method'	=> 'request',
			'designMode'=> true,
			'fixed'		=> true,
		],
		[
			'panel'		=> 66,
			'element'	=> 'ImagesDir',
			'name'		=> 'dos',
			'method'	=> 'toolbar',
			'designMode'=> false,
			'fixed'		=> true,
		],
		[
			'panel'		=> 11,
			'element'	=> 'menuX',
			'name'		=> 'dos',
			'method'	=> 'toolbar',
			'designMode'=> false,
			'fixed'		=> true,
		],
	
	],
	
	'sequences' => [
	
	
	
	],
	'actions' => [
	
	
	
	],
	
	'css' => [],
	
	'js' => [],
	
];

Sevian\S::configInit($init);

