<?php





$conn = pg_connect(
		"host=127.0.0.1 dbname=sevian user=postgres password=postgres port=5432");
pg_set_error_verbosity($conn, PGSQL_ERRORS_VERBOSE);
if (!$conn) {
  echo "An error occurred.\n";
  exit;
}

$result = pg_query($conn, "SELECT * from x");
if (!$result) {
  echo "An error occurred.\n";
  exit;
}

while ($row = pg_fetch_row($result)) {
  echo "Author: $row[0]  E-mail: $row[1]";
  echo "<br />\n";
}
 
?>