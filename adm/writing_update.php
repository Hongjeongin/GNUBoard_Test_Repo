<?php
require_once './_common.php';

/**
 * wr_id 글 PK
 */

if ($_POST['option'] === 'allow') {
    try {
        // G5_BASE_PATH 추가해야함
        $sql = "update g5_write_free
                set wr_1 = '1'
                where wr_id = '{$_POST['wr_id']}';";
        $result = sql_query($sql);
        $hello = sql_fetch($result);
        
        echo 'success';
    } catch(Exception $e) {
    }
} else if ($_POST['option'] === 'refer') {
    try {
        // G5_BASE_PATH 추가해야함
        $sql = "update g5_write_free
                set wr_1 = '2'
                where wr_id = '{$_POST['wr_id']}';";
        $result = sql_query($sql);
        $hello = sql_fetch($result);
        
        echo 'success';
    } catch(Exception $e) {
    }
} else if ($_POST['option'] === 'search') {
    try {
        echo 'success';
    } catch(Exception $e) {
    }
}

?>