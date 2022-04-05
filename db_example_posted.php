<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>DB Example</title>
<style type='text/css'>
	html,body,select {font-size: 25px;}
</style>
</head>

<body>
<h1>This is my Web Page</h1>
<em>It happens to be a PHP page</em>
<br /><br/>

<?php
//establish connection info
$server = "localhost";
$userid = "";
$pw = ";";
$db= "";

// Create connection
$conn = new mysqli($server, $userid, $pw );

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

//select the database
$conn->select_db($db);
	
//run a query
$sql = "SELECT name FROM breeds";
echo "<br />The query is: " . $sql ."<br />";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<select name='ptype'>";   // set up drop down list 
  while($row = $result->fetch_array()) 
  {
	$type = $row[0];
	echo "$type<br>";	// display items retrieved
	  	// add the items to the array
	echo "<script language='javascript'>petTypes.push('$type')</script>";
	  	// create a select option
	echo "<option>$type</option>";
  }
  echo "</select><br />";
} 
else 
{
  echo "no results";
}
	
//close the connection	
$conn->close();

?>
<script language="javascript">
document.write (petTypes);

</script>
</body>
</html>
