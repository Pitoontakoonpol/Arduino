<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
?>
<div style="padding:0 70px">
  <select id="check_in_user" style="font-size:50px;">
    <option value="">ผู้ใช้งาน</option>
    <?php
    $sql = "SELECT ID,Username,Firstname FROM username WHERE BranchID='$br' AND Username<>'admin' AND Status=1 AND Username<>'' ORDER BY Username ";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
      $dbUserID = $db[0];
      $dbUsername = $db[1];
      $dbFirstname = $db[2];
      echo"<option value='__" . $dbUsername . "___" . $dbUserID . "___" . $br . "___'>$dbUsername";
      if ($dbFirstname) {
        echo" ($dbFirstname)";
      }
      echo "</option>";
    }
    ?>
  </select>
</div>