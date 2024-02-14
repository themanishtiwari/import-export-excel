<?php
include'dbcon.php';

	header('Content-Type:text/csv; charset:utf-8');
	header('Content-Disposition: attachment; filename=data.csv');
	$output= fopen("php://output", "w");
	fputcsv($output, array('Sr','Name','Age','Country'));
	$query= "SELECT sr, name, age, country  FROM excel";
	$resul=mysqli_query($conn,$query);
	while ($ro= mysqli_fetch_assoc($resul)) {
		fputcsv($output,$ro);
	}
	fclose($output);

?>