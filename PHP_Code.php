<!DOCTYPE html>
<html>
<body>
<style>
table {
  border-collapse: collapse;
}
td, th {
  border: 2px solid grey;
  text-align: center;
  padding: 8px;
}
</style>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  <label for="id">ID:</label>
  <input type="number" name="id"><br><br>
  <label for="name">Name:</label>
  <input type="text" name="name"><br><br>
  <label for="style">Style:</label>
  <input type="text" name="style"><br><br>
  <label for="color">Color:</label>
  <input type="text" name="color"><br><br>
  <label for="owner">Owner:</label>
  <input type="number" name="owner"><br><br>
  <input type="submit" value="Insert" name="insert">
  <input type="submit" value="Update" name="update">
  <input type="submit" value="Delete" name="delete">
  <input type="submit" value="Display All" name="displayAll">
</form>
<?php
$conn = new mysqli("localhost:8889", "root", "root", "test");
$arr_colors = array("red","blue","orange","white","black");
$arr_styles = array("t-shirt","polo","dress");

$Id = $_REQUEST['id'];
$name = $_REQUEST['name'];
$style = $_REQUEST['style'];
$color = $_REQUEST['color'];
$owner = $_REQUEST['owner'];

function insertData($var_id, $var_name, $var_style, $var_color, $var_owner ){
 global $conn;
 global $arr_colors;
 global $arr_styles;
 
 if($var_id == null){
     $var_id = null;
 }
 if($var_name != null){
     $sql = "INSERT INTO person VALUES (?, ?);";
     $stmt = $conn->prepare($sql);
     $SQLi_Check= $stmt->bind_param("ss", $var_id, $var_name);
     
 } else if ($var_color != null && $var_style != null && $var_owner != null){
     
     if(in_array($var_color,$arr_colors) && in_array($var_style,$arr_styles)){
     $sql = "INSERT INTO shirt VALUES (?, ?, ?, ?);";
     $stmt = $conn->prepare($sql);
     $SQLi_Check= $stmt->bind_param("ssss", $var_id, $var_style, $var_color, $var_owner);
    } else {
        echo"<script>alert('Only these types of Styles are allowed (t-shirt, polo and dress). And, only these colors are allowed (red, blue, orange, white and black)');</script>";
    }
 } else {
     echo "<script>alert('If you want to insert data into Person table then make sure that at least Name input is filled. If you want to insert data into Shirt table then make sure that at least Color, Style and Owner inputs are filled!');</script>";
 }
  if($SQLi_Check === false){
      echo "Failed to bind values: " . mysqli_error($conn);
  }
  if($stmt->execute() === true){
      echo "<script>alert('Successfully Submitted!');</script>";
  } else {
      echo "Failed to Submit: " . mysqli_error($conn);
  }
  $stmt->close();
  $conn->close();
}

function updateData($var_id, $var_name, $var_style, $var_color, $var_owner){
    global $conn;
    global $arr_colors;
    global $arr_styles;
        
    if($var_id != null && $var_name != null){
        
        $sql = "UPDATE person SET name=(?) WHERE id=(?);";      
        $stmt = $conn->prepare($sql);
        $SQLi_Check= $stmt->bind_param("ss", $var_name, $var_id);
        
    } else if($var_id != null && $var_style != null){
     if(in_array($var_style, $arr_styles)){
            $sql = "UPDATE shirt SET style=(?) WHERE id=(?);";
            $stmt = $conn->prepare($sql);
            $SQLi_Check= $stmt->bind_param("ss", $var_style, $var_id);
        } else {
            echo"<script>alert('Only t-shirt, polo and dress are allowed to be inserted!');</script>";
        }
    }
      else if($var_id != null && $var_color != null){
        
          if(in_array($var_color,$arr_colors)){
            $sql = "UPDATE shirt SET color=(?) WHERE id=(?);";
            $stmt = $conn->prepare($sql);
            $SQLi_Check= $stmt->bind_param("ss", $var_color, $var_id);
          } else {
             echo"<script>alert('Only Red, Blue, Orange, White and Black colors are allowed to be inserted!');</script>";
          }
    } else if($var_id != null && $var_owner != null){
        
        $sql = "UPDATE shirt SET owner=(?) WHERE id=(?);";
        $stmt = $conn->prepare($sql);
        $SQLi_Check= $stmt->bind_param("ss", $var_owner, $var_id);
    } else if ($var_id !=null && $var_style != null && $var_color != null && $var_owner != null) {
        echo"<script>alert('Please note that you can only update one column at a time!');</script>";
    } else {
        echo"<script>alert('If you want to update the Person table, you MUST provide ID and the Name. If you want to update the Shirt table then you MUST provide ID and the name of the data in any column!');</script>";
    }
    if($SQLi_Check === false){
        echo "Failed to bind values: " . mysqli_error($conn);
    }
    if($stmt->execute() === true){
        echo "<script>alert('Successfully Submitted!');</script>";
    } else {
        echo "Failed to Submit: " . mysqli_error($conn);
    }
    $stmt->close();
    $conn->close();
}

function deleteData($var_id, $var_name, $var_style, $var_color, $var_owner ){
    global $conn;
    
    if($var_name != null){
        $sql = "DELETE FROM person WHERE name=(?);";
        $stmt = $conn->prepare($sql);
        $SQLi_Check= $stmt->bind_param("s", $var_name);
    } else if($var_style != null && $var_color != null && $var_owner != null){
        $sql = "DELETE FROM shirt WHERE style=(?) and color=(?) and owner=(?);";
        $stmt = $conn->prepare($sql);
        $SQLi_Check= $stmt->bind_param("sss", $var_style, $var_color, $var_owner);
    } else {
        echo "<script>alert('If you want to Delete data in Person table then make sure that Name input is filled. If you want to Delete data in Shirt table then make sure that Color, Style and Owner inputs are filled!');</script>";
    }
    if($SQLi_Check === false){
        echo "Failed to bind values: " . mysqli_error($conn);
    }
    if($stmt->execute() === true){
        echo "<script>alert('Successfully Submitted!');</script>";
    } else {
        echo "Failed to Submit: " . mysqli_error($conn);
    }
    $stmt->close();
    $conn->close();
}
function selectAll(){
    global $conn;
    $res1 = $conn->query("SELECT * FROM person;");
    $res2 = $conn->query("SELECT * FROM shirt");
    
    echo "<br><table><tr><th>Id</th><th>Name</th></tr>";
    while ($row = $res1->fetch_assoc()) {
        echo "<tr><td>".$row['id']."</td><td>".$row['name']."</td></tr>";
        $conn->close();
    }
    echo "<table><tr><th>Id</th><th>Style</th><th>Color</th><th>Owner</th></tr>";
    while ($row = $res2->fetch_assoc()) {
        echo "<tr><td>".$row['id']."</td><td>".$row['style']."</td><td>".$row['color']."</td><td>".$row['owner']."</td></tr>";
        $conn->close();
    }
}
if($conn ->connect_error){
    echo "Connecion Failed: ".$conn->connect_error;
}

if(isset($_REQUEST['insert'])){
    insertData($Id, $name, $style, $color, $owner);
}

if(isset($_REQUEST['delete'])){
    deleteData($Id, $name, $style, $color, $owner);
}

if(isset($_REQUEST['update'])){
    updateData($Id, $name, $style, $color, $owner);
}
if(isset($_REQUEST['displayAll'])){
    selectAll();
}
?>
</body>
</html>