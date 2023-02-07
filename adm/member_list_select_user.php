<?php
require_once './_common.php';

if ($_POST['mb_id']) {
    $sql = 'select * from g5_member where mb_id = "'.$_POST['mb_id'].'";';
    // $sql = 'select * from g5_member where mb_id = "qwe"';
    $result = sql_fetch($sql);
    
    echo json_encode($result);
    // echo $result;
}
?>