<?php



print_r($_GET);

echo "articulo es ".$_GET['articulo'];
exit;


function callback($búfer)
{
  // reemplazar todas las manzanas por naranjas
  return (str_replace("manzanas", "naranjas", $búfer));
}

ob_start("callback");
$a = 100;
?>
<html>
<body>
<p>Es como comparar manzanas con naranjas.</p>
</body>
</html>
<?php

ob_end_flush();

?>
