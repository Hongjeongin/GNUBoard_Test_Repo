<?php
require_once './_common.php';

//bo_table
//under_sections 하위섹션이름들 > me_name
//se_id 섹션 이름
//me_code

/**
 * 2. 추가 - 한 섹션 추가 // 
 * >> board select 후 기존에 존재하던 것이면 return
 * >> menu select 후 기존에 존재하던 것이면 return // 보류
 * >> board insert + 섹션타입(bo_1) 저장
 * >> menu insert
 */

if ($_POST['under_sections']) {
    try {
        // 이미 있는 섹션(board)이면 리턴 업데이트 불가능
        $sql2 = "select *
                from {$g5['board_table']}
                where bo_table = '{$_POST['bo_table']}'";
        if (sql_fetch($sql2)) return false;

        
        // G5_BASE_PATH로 gr_id 찾아야함

        $sql10 = "select * from {$g5['board_table']} where bo_1 = '{$_POST['se_id']}'";
        if(sql_fetch($sql10)) return false;


        // board(섹션) 추가
        $sql3 = "insert into {$g5['board_table']}(
                    bo_table,
                    gr_id,
                    bo_subject,
                    bo_device,
                    bo_list_level,
                    bo_read_level,
                    bo_write_level,
                    bo_reply_level,
                    bo_comment_level,
                    bo_upload_level,
                    bo_download_level,
                    bo_html_level,
                    bo_link_level,
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
                    bo_page_rows,
                    bo_mobile_page_rows,
                    bo_new,
                    bo_hot,
                    bo_image_width,
                    bo_upload_count,
                    bo_upload_size,
                    bo_reply_order,
                    bo_use_search,
                    bo_skin,
                    bo_mobile_skin,
                    bo_include_head,
                    bo_include_tail,
                    bo_use_secret,
                    bo_1,
                    bo_3)
                values (
                    '{$_POST['bo_table']}',
                    '{$_POST['se_id']}',
                    '{$_POST['bo_table']}',
                    'both',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
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
                    '15',
                    '15',
                    '24', 
                    '100',
                    '600',
                    '2',
                    '1048576',
                    '1',
                    '1',
                    'basic',
                    'basic',
                    '_head.php',
                    '_tail.php',
                    '0',
                    '{$_POST['se_id']}',
                    '0')";
        $result5 = sql_query($sql3);
        $hello = sql_fetch($result5);

        $sql4 = "SELECT MAX(CAST(me_code AS UNSIGNED)) AS me_code FROM {$g5['menu_table']};";
        $result3 = sql_fetch($sql4);

        $me_code = intval($result3['me_code']) + 10;

        // menu(하위섹션) 추가
        for ($i = 0; $i < count($_POST['under_sections']); $i++) {
            $sql3 = "insert into {$g5['menu_table']}(
                        me_code,
                        me_name,
                        me_link,
                        bo_table)
                    values (
                        '{$me_code}',
                        '{$_POST['under_sections'][$i]}',
                        '{$_POST['me_link']}',
                        '{$_POST['bo_table']}')";

            $result2 = sql_query($sql3);
            $hello2 = sql_fetch($result2);
        }
        
        echo 'success';
    } catch(Exception $e) {
    }
}
?>