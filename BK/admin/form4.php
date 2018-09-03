<?php


class FileUp{
	
	public $field = '';
	public $name = false;
	public $path = '';
	public $error = false;
	
	public $multiple = true;
	
	public function delete($name = ''){
		
		if(is_file($this->path.$this->name)){
			unlink($this->path.$this->name);
			return true;
		}else{
			return false;
		}
		
	}
	
	
	private function _load($file, $temp){
		
		$info = pathinfo($file);
		
		$ext = $info['extension'];

		if($this->name){
			$baseName = $this->name;
		}else{
			$baseName = uniqid('f');
		}


		$name = $this->path.$baseName.".".$ext;

		if(move_uploaded_file($temp, $name)){
			return $name;

		}else{
			$this->error = true;
			return false;
		}
	}
	
	
	
	public function load(){
		
		if(is_array($_FILES[$this->field]['name'])){
			
			$this->multiple = true;
		}else{
			$this->multiple = false;
		}
		
		if($this->multiple){
			
			$names = array();
			
			foreach ($_FILES[$this->field]['error'] as $k => $error) {

				if ($error == UPLOAD_ERR_OK) {
					$names[] = $this->_load($_FILES[$this->field]['name'][$k], $_FILES[$this->field]['tmp_name'][$k]);
				}

			}
			return $names;

		}
		
		if($_FILES[$this->field]['error'] == UPLOAD_ERR_OK) {

			
			return $this->_load($_FILES[$this->field]['name'], $_FILES[$this->field]['tmp_name']);
			

		}
		
		return false;
		
	}
	
	
	
	
	
	
}


class form4 extends Sevian\Panel{
	
	
	public function evalMethod($method=""){
		
		
		$f = new FileUp();
		
		$f->field = 'archivo';
		$f->path = 'files/';
		
		//$f->name = 'esteban';
		
		//echo $method;
		
		
		//$f->delete();
		$f->load();
		
	}
	
	
	public function render(){
		
		
		
		$main = new Sevian\HTML('div');
		
		$main->class = 'xxx';
		$main->text = 'hola';
		
		
		$input = $main->add('input');
		$input->type = 'file';
		
		$input->multiple = 'multiple';
		$input->name = 'archivo';
		$input->id = 'archivo';
		
		
		$input2 = $main->add('input');
		$input2->type = 'button';
		
		$input3 = $main->add('input');
		$input3->type = 'text';
		$input3->id = 'input_upload';
		$input3->placeholder = '...input_upload';
		
		$div3 = $main->add('div');
		//$div3->class = 'upload';
		$div3->id = 'main_upload';
		
		
		$label = $main->add('label');
		$label->for = 'archivo';
		$label->innerHTML = 'arrastre aqui';
		$label->id = 'label';
		
		$action = Sevian\Action::Send([
			'async'=>false,
			'panel'=>6,
			'valid'=>false,
			'params'=>[
			[
					'setMethod'=>[
						'panel'=>4,
						'element'=>'form4',
						'name'=>'uno',
						'method'=>'save',
					],
			
			
					
				],
				[
					
			
					'setPanel'=>[
						'panel'=>4,
						'element'=>'form4',
						'name'=>'uno',
						'method'=>'load',
					],
				]
				
			]
			
		]);
		
		$input2->onclick = $action;
		
		return $main->render();
	}
	
}
	
?>