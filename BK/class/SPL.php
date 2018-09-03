<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>

<?php
	$array_a = new ArrayIterator(array('a', 'b', 'c'));
$array_b = new ArrayIterator(array('d', 'e', 'f'));

$iterator = new AppendIterator;
$iterator->append($array_a);
$iterator->append($array_b);

foreach ($iterator as $current) {
    echo $current;
}
	
	echo "<hr>";
	
$o = new StdClass;
$a = new SplObjectStorage();
$a[$o] = "hola";

$b = new SplObjectStorage();
$b->addAll($a);
echo $b[$o]."\n";
?> 
</body>
</html>