<?php





class FormB extends Sevian\Panel{
	
	
	public function evalMethod($method=""){
		
		
		
		
	}
	
	
	public function render(){
		
		$opt = [
			"caption"=>"Hola"
			
			
		];
		$form = new Sevian\Form($opt);
		$form->addField([
			"caption"=>"Edad",
			"name"=>"cedula3",
		"input"=>["input"=>"date", "id"=>"aq1", "name"=>"aq","value"=>"1975-10-24", "className"=>"zelda"]]);



		$form->addField([
			"caption"=>"Cédula",
			"name"=>"cedula",
		"input"=>["input"=>"date", "id"=>"x", "name"=>"Nombre1","value"=>"1975-10-24", "className"=>"zelda"]]);
		
		$form->addField([
			"caption"=>"Nombre",
			"name"=>"cedula2",
		"input"=>["input"=>"date", "id"=>"x11", "name"=>"Nombre11","value"=>"1975-10-24", "className"=>"zelda"]]);
$form->addField([
			"caption"=>"TipoFecha",
			"name"=>"tipo2",
		"input"=>["input"=>"select", "id"=>"x11", "name"=>"x11","value"=>"1975-10-24", "className"=>"zelda", "data"=>[
			
			[1, "yanny"],
			[2, "esteban"],
			[3, "nuñez"],
			
		]]]);

		$form->addField([
			"caption"=>"Apellido",
			"name"=>"cedula21",
		"input"=>["input"=>"text", "id"=>"x2", "name"=>"u","value"=>"1975-10-24", "className"=>"zelda"]]);
		
		
		$form->addPage(["caption"=>"Opciones I"]);
		$form->addField([
			"caption"=>"Caution",
			"name"=>"cedula22",
		"input"=>["input"=>"text", "id"=>"x23", "name"=>"u33","value"=>"Hello !!!", "className"=>""]]);
	
		$form->addField([
			"caption"=>"Warning",
			"name"=>"cedula2",
			"comment"=>"hello",
		"input"=>["input"=>"text", "id"=>"x21", "name"=>"u21","value"=>"What !!!", "className"=>""]]);
		
		$form->addPage(["caption"=>"Opciones II"]);
		$form->addField([
			"caption"=>"Warning II",
			"name"=>"cedula2",
			"comment"=>"hello",
		"input"=>["input"=>"text", "id"=>"x21", "name"=>"u21","value"=>"What !!!", "className"=>""]]);

$form->addTab(["id"=>"sss"]);
$form->addTabPage(["title"=>"Tab 001"]);
$form->addField([
	"caption"=>"Warning VI",
	"name"=>"cedula2",
	"comment"=>"hello",
"input"=>["input"=>"text", "id"=>"x21", "name"=>"u21","value"=>"What !!!", "className"=>""]]);

$form->addTabPage(["title"=>"Tab 002"]);
$form->addField([
	"caption"=>"Revolution 45",
	"name"=>"cedula2",
	"comment"=>"hello",
"input"=>["input"=>"text", "id"=>"x21", "name"=>"u21","value"=>"What !!!", "className"=>""]]);
		$html = $form->render();
		
		$this->script = $form->getScript();
		

//echo json_encode($form, JSON_PRETTY_PRINT);exit;

		return $html;
		global $sevian;
		$g = $sevian->sgInput(["input"=>"date", "id"=>"x", "name"=>"Nombre1","value"=>"1975-10-24", "className"=>"zelda"]);
		
		$i = $sevian->sgInput(["input"=>"date", "id"=>"cedula", "name"=>"cedula", "className"=>"wolfs", "value"=>2,
							  
							  "propertys"=>["placeholder"=>"seleccione..."],
							   
							   
							   "events"=>["click"=>"alert(this.getValue())"]
							  ]);
		
		
		
		$i->data = [
			
			[1, "yanny"],
			[2, "esteban"],
			[3, "nuñez"],
			
		];
		
		$tab = new Sevian\Tab(["pages"=>[
			["title"=>"caja"],
			["title"=>"lider"]
			
		]]);
		
		$tab->add(["title"=>"Nuevo", "child"=>"Ja nnn"]);
		$tab->add(["title"=>"Opciones", "child"=>"Ok"]);
		$tab->add(["title"=>"Config", "child"=>"Cool"]);
		
		
		$fs = new Sevian\FieldSet(["caption"=>"hola"]);
		
		$ii = new Sevian\HTML("input");
		$ii->type = "text";
		$fs->appendChild($ii);
		
		$html = $i->render().$g->render().$form->render().$tab->render().$fs->render();
		
		
		
		$this->script = $i->getScript().$g->getScript().$tab->getScript();
		
		//echo(json_encode($tab, JSON_PRETTY_PRINT));
		
		/*
		
		$ff = new Sevian\Form([]);
		
		
		$ff->setCaption("");
		
		$ff->addPage("");
		$ff->addTab("");
		$ff->addTabPage();
		
		$ff->addField();
		
		$ff->addInput();
		$ff->addMenu();
		*/
		
		return $html;
	}
	
}
	
?>