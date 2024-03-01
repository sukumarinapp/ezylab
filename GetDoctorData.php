<?php
include_once 'booster/bridge.php';

$ref_prefix = $_REQUEST['ref_prefix'];

if($ref_prefix == 'Dr.'){
echo '<option value="">Select</option>';

$sql = "SELECT * FROM doctors ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='" . $row['id'] . "'>" . $row['prefix'].$row['d_name'] . "</option>";
}
}else{
    echo '<option value="Self">Self</option>';
}
?>