<?php
require_once './_common.php';

/**
 * bo_table(새 섹션 이름)
 * under_sections(하위섹션들) >> me_name에 저장해야함
 * se_id(섹션타입 이름) >> bo_1에 저장해야함
 * me_code 필요
 * me_link 필요
 * 
 * 
 * original_bo_table(기존 섹션 이름)
 * gr_id는 G5_BASE_PATH와 url 비교 -- 추후에
 */

if ($_POST['under_sections']) {
    try {
        // gr_1 찾기
        // $sql = "select se_no from g5_sections where se_id = '{$_POST['se_id']}';";
        // $gr_1 = sql_fetch($sql);

        // 이미 있는 섹션(board)이면 리턴 업데이트 불가능
        if ($_POST['original_bo_table'] !== $_POST['bo_table']) {
            $sql2 = "select * from {$g5['board_table']} where bo_table = '{$_POST['bo_table']}';";
            if (sql_fetch($sql2)) return false;
        }

        // me_link 가져오기

        // board(섹션) 업데이트
        // 이름, 순서(bo_3)
        $sql = "update {$g5['board_table']} set bo_table = '{$_POST['bo_table']}', bo_3 = '{$_POST['bo_3']}' where bo_table = '{$_POST['original_bo_table']}';";
        sql_query($sql);

        // 기존 menu(하위 섹션) 지우기
        // 기존 섹션타입이름과 그룹아이디가 필요함
        $sql = "delete from {$g5['menu_table']} where bo_table = '{$_POST['original_bo_table']}';";
        sql_query($sql);

        $sql4 = "SELECT MAX(CAST(me_code AS UNSIGNED)) AS me_code FROM {$g5['menu_table']};";
        $result3 = sql_fetch($sql4);

        $me_code = intval($result3['me_code']) + 10;

        // 새 menu(하위 섹션) 저장
        for ($i = 0; $i < count($_POST['under_sections']); $i++) {
            $sql3 = "insert into {$g5['menu_table']}(
                        me_code,
                        me_name,
                        me_link,
                        bo_table
                    )
                    values (
                        $me_code,
                        '{$_POST['under_sections'][$i]}',
                        '{$_POST['me_link']}',
                        '{$_POST['bo_table']}'
                    );";
            $result = sql_query($sql3);
            $hello = sql_fetch($result);
        }
        echo 'success';
    } catch(Exception $e) {
        echo $e;
    }
} else if($_POST['bo_table']) {
    try {
        $sql = "delete from {$g5['board_table']} where bo_table = '{$_POST['bo_table']}';";
        $result = sql_query($sql);
        if (!$result) return false;

        $sql2 = "delete from {$g5['menu_table']} where bo_table = '{$_POST['bo_table']}';";
        $result2 = sql_query($sql2);

        echo 'success';
    } catch(Exception $e) {
        echo $e;
    }
} else if($_POST['sections']) {
    try {
        for ($i = 0; $i < count($_POST['sections']); $i++) {
            $sql = "update {$g5['board_table']}
                    set bo_3 = '{$_POST['numbers'][$i]}'
                    where bo_table = '{$_POST['sections'][$i]}';";
            $result = sql_query($sql);
            $hello = sql_fetch($result);
        }
        echo 'success';
    } catch(Exception $e) {
        echo $e;
    }
}
?>