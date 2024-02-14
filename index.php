<?php require 'dbcon.php'; ?>
 <!DOCTYPE html>
<html lang="en" dir="ltr">
	<head> 
		<meta charset="utf-8">
		<title>Import Excel To MySQL</title>
		<style>
			body{
				font-family: Verdana, sans-serif;
			}
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
				}
				td, th{
					padding: 5px;
				}
		</style>
	</head>
	<body>
		<h3>Import & Export Excel</h3>
		<form class="" action="" method="post" enctype="multipart/form-data">
			<input type="file" name="excel" required value="">
			<button type="submit" name="import">Import</button>
		</form>
		<hr>
		<table>
			<tr>
				<th>Sr</th>
				<th>Name</th>
				<th>Age</th>
				<th>Country</th>
			</tr>
			<?php
			$i = 1;
			$rows = mysqli_query($conn, "SELECT * FROM excel");
			foreach($rows as $row) :
			?>
			<tr>
				<td> <?php echo $i++; ?> </td>
				<td> <?php echo $row["name"]; ?> </td>
				<td> <?php echo $row["age"]; ?> </td>
				<td> <?php echo $row["country"]; ?> </td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php
		if(isset($_POST["import"])){
			if($_FILES['excel']['name']){
			$fileName = $_FILES["excel"]["name"];
			$size=$_FILES['excel']['size'];
			$fileExtension = explode('.', $fileName);
      		$fileExtension = strtolower(end($fileExtension));
			$newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;
			$array= array('csv','xlsx');


			if (in_array($fileExtension, $array)) {
				if($size<2097152){

			$targetDirectory = "uploads/".$newFileName;
			move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

			error_reporting(0);
			ini_set('display_errors', 0);

			require 'excelReader/excel_reader2.php';
			require 'excelReader/SpreadsheetReader.php';

			$reader = new SpreadsheetReader($targetDirectory);
			foreach($reader as $key => $row){
				if($key!=0){
					$name = $row[0];
					$age = $row[1];
					$country = $row[2];
					$import= mysqli_query($conn, "INSERT INTO excel VALUES('', '$name', '$age', '$country')");
				}
			}
				if($import){

					unlink($targetDirectory);
					echo"<script>
						alert('Succesfully Imported');
						</script>";
						
					?><meta http-equiv="refresh" content="0" /><?php
				}
			
			}
			else{
				echo '<h4 style="color: red">please select file less than 2 MB </h4>';
			  }
			}
		else{
			echo '<h4 style="color: red">Select Excel file in csv, xlsx format</h4>';
		}
	}
	else{
	  echo '<h4 style="color: red">Please Select file</h4>';
  		}
	}

		?>
		<a href="download.php"><button type="button">download</button></a>
	</body>
</html>
