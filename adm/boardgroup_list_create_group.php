<?php
require_once './_common.php';

//se_name >> gr_id
//under_sections >> bo_subject
//se_id >> gr_1

if ($_POST['under_sections']) {
    try {

        // 이미 있는 섹션이면 리턴 업데이트 불가능
        $sql2 = "select * from {$g5['group_table']} where gr_id = '{$_POST['se_name']}'";
        if (sql_fetch($sql2)) return false;

        // se_id 로 gr_1 찾기
        $sql = "select se_no from g5_sections where se_id = '{$_POST['se_id']}'";
        $gr_1 = sql_fetch($sql);


        // group(섹션) 추가
        $sql = "insert into {$g5['group_table']}(gr_id, gr_device, gr_subject, gr_1) values('{$_POST['se_name']}', 'both', '{$_POST['se_name']}', '{$gr_1['se_no']}')";
        sql_query($sql);

        
        // board(하위 섹션) 추가
        // for문 돌리기
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
                        '0')";

            $result = sql_query($sql3);
            $hello = sql_fetch($result);
        }
        
        echo 'success';
    } catch(Exception $e) {
        echo $e;
    }
}
?>