<?php
require_once "./_common.php";

if ($_POST['id']) {
  $sql = "DELETE FROM g5_write_notice WHERE wr_id = " . $_POST['id'];
  $result = sql_query($sql);

  echo json_encode($result);
}
