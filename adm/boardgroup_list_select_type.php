<?php
require_once './_common.php';

if ($_POST['no'] ) {
    try {
        $sql = "select * from {$g5['board_table']} where gr_id = '{$_POST['gr_id']}'";
        $result = sql_query($sql);

        for ($i = 0; $row = sql_fetch_array($result); $i++) {
            echo json_encode($row);
        }
        
    } catch(Exception $e) {
        echo $e;
    }
} else if ($_POST['gr_id']) {
    try {

        $sql = "select gr_1 from {$g5['group_table']} where gr_id = '{$_POST['gr_id']}'";

        $result = sql_fetch($sql);
        echo json_encode($result);
    } catch(Exception $e) {
        echo $e;
    }
}
?>