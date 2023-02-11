<?php
require_once './_common.php';

if ($_POST['no'] ) {
    try {

        $sql = "SELECT * FROM (select * from (select * from g5_sections where se_no = '{$_POST['se_no']}') A LEFT JOIN {$g5['board_table']} B ON A.se_id = B.bo_1) C LEFT JOIN {$g5['menu_table']} D ON C.bo_table = D.bo_table;";

        $result = sql_query($sql);

        for ($i = 0; $row = sql_fetch_array($result); $i++) {
            echo json_encode($row);
        }
        
    } catch(Exception $e) {
    }
} else if ($_POST['gr_id']) {
    try {

        $sql = "select gr_1 from {$g5['group_table']} where gr_id = '{$_POST['gr_id']}'";

        $result = sql_fetch($sql);
        echo json_encode($result);
    } catch(Exception $e) {
    }
} else {
    try {
        $sql = "SELECT MAX(CAST(me_code AS UNSIGNED)) AS me_code FROM {$g5['menu_table']};";

        $result = sql_fetch($sql);
        echo json_encode($result);
    } catch(Exception $e) {
    }
}
?>