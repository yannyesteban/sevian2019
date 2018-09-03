<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  -->
  
  <link rel="stylesheet" href="../bootstrap-3.3.7-dist/css/bootstrap.min.css">
  <link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.datepicker.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.accordion.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.button.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.autocomplete.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.menu.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.tabs.min.css" rel="stylesheet" type="text/css">
  <script src="_js/jquery-3.2.1.js"></script>
  <script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="jQueryAssets/jquery-1.11.1.min.js"></script>
  <script src="jQueryAssets/jquery.ui-1.10.4.datepicker.min.js"></script>
  <script src="jQueryAssets/jquery.ui-1.10.4.accordion.min.js"></script>
  <script src="jQueryAssets/jquery.ui-1.10.4.button.min.js"></script>
  <script src="jQueryAssets/jquery.ui-1.10.4.autocomplete.min.js"></script>
  <script src="jQueryAssets/jquery.ui-1.10.4.tabs.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Dropdown Example</h2>
  <p>The data-toggle="dropdown" attribute is used to open the dropdown menu.</p>
  <div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" id="menu1" type="button" data-toggle="dropdown">Dropdown Example
    <span class="caret"></span></button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">HTML</a></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">CSS</a></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">JavaScript</a></li>
      <li role="presentation" class="divider"></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">About Us</a></li>    
    </ul>
  </div>
</div>
<section class="bg-primary">Colocar aquí el contenido para  class "bg-primary"</section>
<input type="text" id="Datepicker1">
<div id="Accordion1">
  <h3><a href="#">Sección 1</a></h3>
  <div>
    <p>Contenido 1</p>
  </div>
  <h3><a href="#">Sección 2</a></h3>
  <div>
    <p>Contenido 2</p>
  </div>
  <h3><a href="#">Sección 3</a></h3>
  <div>
    <p>Contenido 3</p>
  </div>
</div>
<button id="Button1">Button</button>
<div id="Checkboxes1">
  <input type="checkbox" id="Checkbox1">
  <label for="Checkbox1">Etiqueta 1</label>
  <input type="checkbox" id="Checkbox2">
  <label for="Checkbox2">Etiqueta 2</label>
  <input type="checkbox" id="Checkbox3">
  <label for="Checkbox3">Etiqueta 3</label>
</div>
<div id="RadioButtons1">
  <input type="radio" name="RadioButtons1" id="Radio1">
  <label for="Radio1">Etiqueta 1</label>
  <input type="radio" name="RadioButtons1" id="Radio2">
  <label for="Radio2">Etiqueta 2</label>
  <input type="radio" name="RadioButtons1" id="Radio3">
  <label for="Radio3">Etiqueta 3</label>
</div>
<input type="text" id="Autocomplete1">
<div id="Tabs1">
  <ul>
    <li><a href="#tabs-1">Ficha 1</a></li>
    <li><a href="#tabs-2">Ficha 2</a></li>
    <li><a href="#tabs-3">Ficha 3</a></li>
  </ul>
  <div id="tabs-1">
    <p>Contenido 1</p>
  </div>
  <div id="tabs-2">
    <p>Contenido 2</p>
  </div>
  <div id="tabs-3">
    <p>Contenido 3</p>
  </div>
</div>
<script type="text/javascript">
$(function() {
	$( "#Datepicker1" ).datepicker(); 
});
$(function() {
	$( "#Accordion1" ).accordion(); 
});
$(function() {
	$( "#Button1" ).button(); 
});
$(function() {
	$( "#Checkboxes1" ).buttonset(); 
});
$(function() {
	$( "#RadioButtons1" ).buttonset(); 
});
$(function() {
	$( "#Autocomplete1" ).autocomplete(); 
});
$(function() {
	$( "#Tabs1" ).tabs(); 
});
</script>
</body>
</html>