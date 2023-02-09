<?php
require_once './_common.php';

$wr = array(
  'wr_num' => 0,
  'wr_reply' => '',
  'wr_parent' => '',
  'wr_is_comment' => 0,
  'wr_comment' => 0,
  'wr_comment_reply' => '',
  'ca_name' => '',
  'wr_option' => '',
  'wr_subject' => '',
  'wr_content' => '',
  'wr_seo_title' => '',
  'wr_link1' => '',
  'wr_link2' => '',
  'wr_link1_hit' => 0,
  'wr_link2_hit' => 0,
  'wr_hit' => 0,
  'wr_good' => 0,
  'wr_nogood' => 0,
  'mb_id' => '',
  'wr_password' => '',
  'wr_name' => '',
  'wr_email' => '',
  'wr_homepage' => '',
  'wr_datetime' => '',
  'wr_file' => 0,
  'wr_last' => '',
  'wr_ip' => '',
  'wr_facebook_user' => '',
  'wr_twitter_user' => '',
  'wr_1' => '',
  'wr_2' => '',
  'wr_3' => '',
  'wr_4' => '',
  'wr_5' => '',
  'wr_6' => '',
  'wr_7' => '',
  'wr_8' => '',
  'wr_9' => '',
  'wr_10' => '',
);

$wr['wr_subject'] = $_POST['wr_subject'];
$wr['wr_content'] = $_POST['wr_content'];
$wr['wr_name'] = $_POST['wr_name'];
$wr['wr_datetime'] = $_POST['wr_datetime'];

$sql = "insert into g5_write_notice set ";
foreach ($wr as $key => $value) {
  $sql .= $key . " = '" . $value . "', ";
}
$sql = substr($sql, 0, -2);

$result = sql_query($sql);

if ($result) {
  alert('등록되었습니다.', './disclosure.php');
} else {
  alert('등록에 실패했습니다.', './disclosure.php');
}
