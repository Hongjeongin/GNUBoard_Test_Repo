<?php
require_once "./_common.php";

if ($_POST['id']) {
  $sql = "delete from g5_write_notice where wr_id = '{$_POST['id']}'";
  $result = sql_query($sql);

  echo json_encode($result);
}
