<?php
require_once './_common.php';

/**
 * se_name >> gr_id
 * under_sections >> bo_subject
 * se_id >> gr_1
 * original_gr_id
 */

if ($_POST['under_sections']) {
    try {
        // gr_1 찾기
        $sql = "select se_no from g5_sections where se_id = '{$_POST['se_id']}';";
        $gr_1 = sql_fetch($sql);

        // 이미 있는 섹션이면 리턴 업데이트 불가능
        if ($_POST['original_gr_id'] !== $_POST['se_name']) {
            $sql2 = "select * from {$g5['group_table']} where gr_id = '{$_POST['se_name']}'";
            if (sql_fetch($sql2)) return false;
        }

        // group(섹션) 업데이트
        $sql = "update {$g5['group_table']} set gr_id = '{$_POST['se_name']}', gr_1 = '{$gr_1['se_no']}', gr_subject = '{$_POST['se_name']}' where gr_id = '{$_POST['original_gr_id']}';";
        sql_query($sql);

        // 기존 board(하위 섹션) 지우기
        $sql = "delete from {$g5['board_table']} where gr_id = '{$_POST['original_gr_id']}';";
        sql_query($sql);

        // 새 board(하위 섹션) 저장
        for ($i = 0; $i < count($_POST['under_sections']); $i++) {
            $sql3 = "insert into {$g5['board_table']}(
                        gr_id,
                        bo_subject,
                        bo_device,
                        bo_count_delete,
                        bo_count_modify,
                        bo_gallery_cols,
                        bo_gallery_width,
                        bo_gallery_height,
                        bo_mobile_gallery_width,
                        bo_mobile_gallery_height,
                        bo_table_width,
                        bo_subject_len,
                        bo_mobile_subject_len,
                        bo_new,
                        bo_hot,
                        bo_image_width,
                        bo_upload_count,
                        bo_upload_size,
                        bo_reply_order,
                        bo_use_search,
                        bo_skin,
                        bo_mobile_skin,
                        bo_use_secret)
                    values (
                        '{$_POST['se_name']}',
                        '{$_POST['under_sections'][$i]}',
                        'both',
                        '1',
                        '1',
                        '4',
                        '202',
                        '150',
                        '125',
                        '100',
                        '100',
                        '60',
                        '30',
                        '24', 
                        '100',
                        '600',
                        '2',
                        '1048576',
                        '1',
                        '1',
                        'basic',
                        'basic',
                        '0');";

            $result = sql_query($sql3);
            $hello = sql_fetch($result);
        }

        

        echo 'success';
    } catch(Exception $e) {
        echo $e;
    }
} else if($_POST['gr_id']) {
    try {
        echo json_encode($_POST['gr_id']);

        $sql = "delete from {$g5['group_table']} where gr_id = '{$_POST['gr_id']}';";

        $result = sql_query($sql);
        if (!$result) echo json_encode($result);

        $sql = "delete from {$g5['board_table']} where gr_id = '{$_POST['gr_id']}';";

        $result = sql_query($sql);

        echo json_encode($result);
    } catch(Exception $e) {
        echo $e;
    }
}
?>