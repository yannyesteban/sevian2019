<?php
if(isset($_GET["id"])){
	session_name($_GET["id"]);
	//session_start();
	//session_regenerate_id() ;
	
}else{
	session_name("yanny");
}
session_start();

//print_r($_SESSION);
echo $_GET["PHPSESSID"] ."+++++++".SID;
echo "<br>...";



echo session_id();
echo "<br>...";

$_SESSION["a"]++;
	
	echo $_SESSION["a"];




?><form>
	
	<input type="text" name=xx>
	<input type="submit" value="ok">
	
	
</form>