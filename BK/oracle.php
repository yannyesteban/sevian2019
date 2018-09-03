<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');


$username='yanny';
$password='123456';
$db='(DESCRIPTION =
(ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521))
(CONNECT_DATA =
(SERVER = DEDICATED)
(SERVICE_NAME = XE)
)
)';
//$conn = oci_connect('hr', 'welcome', $db);
//$conn = oci_connect('hr', 'welcome', 'localhost/XE');
//$conn = oci_connect('yanny', '123456', $db);

//$conn = oci_connect('sevian', '123456', 'localhost/XE');

//$stid = oci_parse($conn, 'select * from hr.employees');
//$stid = oci_parse($conn, 'select * from personas');

echo 3;
//$conn = oci_connect('system', '123456', 'localhost/SEVIAN');
$conn = oci_connect('system', '123456', 'localhost/SENIAT');

//$stid = oci_parse($conn, "insert into  personas values('2004','Juana')");
//oci_execute($stid);
$stid = oci_parse($conn, 'SELECT * FROM user_tablespaces');
//$stid = oci_parse($conn, 'select * from empleados.materias');
//$stid = oci_parse($conn, 'select * from version;');



oci_execute($stid);

echo "<table border='1'>\n";
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "  <td>".($item !== null ? htmlspecialchars($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

echo 6;
exit;


$conn = oci_connect('hr', 'welcome', 'localhost/orcl');

$stid = oci_parse($conn, 'SELECT * FROM EMPLOYEE_TABLE');
oci_execute($stid);

echo "<table>\n";
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "  <td>".($item !== null ? htmlspecialchars($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

?>