<?php
    require_once './_common.php';
    // echo G5_BBS_URL;

    // 게시판 테이블 생성
    $file = file('./sql_write.sql');
    $file = get_db_create_replace($file);

    
    $sql = implode("\n", $file);

    $create_table = $g5['write_prefix'] . $_POST['se_id'] . '_' . $_POST['bo_table'];

    // sql_board.sql 파일의 테이블명을 변환
    $source = array('/__TABLE_NAME__/', '/;/');
    $target = array($create_table, '');
    $sql = preg_replace($source, $target, $sql);
    
    echo json_encode($sql);

    // sql_query($sql, false);
    // $create_table = $g5['write_prefix'] . $bo_table;

    // // sql_board.sql 파일의 테이블명을 변환
    // $source = array('/__TABLE_NAME__/', '/;/');
    // $target = array($create_table, '');
    // $sql = preg_replace($source, $target, $sql);
    // sql_query($sql, false);




?>