<?php
require_once './_common.php';

if ($_POST['mb_level']) {
    $sql = "update {$g5['member_table']} set mb_level = '{$_POST['mb_level']}' where mb_id = '{$_POST['mb_id']}'";
    $result =  sql_query($sql);
    
    echo json_encode($result);
} else {
    try {
        $sql = "
        update {$g5['member_table']}
        set mb_password = '',
            mb_nick = '',
            mb_nick_date = '0000-00-00',
            mb_email = '',
            mb_homepage = '',
            mb_level = '0',
            mb_sex = '',
            mb_birth = '',
            mb_tel = '',
            mb_hp = '',
            mb_certify = '',
            mb_adult = '0',
            mb_dupinfo = '',
            mb_zip1 = '',
            mb_zip2 = '',
            mb_addr1 = '',
            mb_addr2 = '',
            mb_addr3 = '',
            mb_addr_jibeon = '',
            mb_signature = '',
            mb_recommend = '',
            mb_point = '0',
            mb_today_login = '0000-00-00 00:00:00',
            mb_login_ip = '',
            mb_datetime = '0000-00-00 00:00:00',
            mb_ip = '',
            mb_leave_date = '1',
            mb_intercept_date = '',
            mb_email_certify = '0000-00-00 00:00:00',
            mb_email_certify2 = '',
            mb_memo = '',
            mb_lost_certify = '',
            mb_mailling = '0',
            mb_sms = '0',
            mb_open = '0',
            mb_open_date = '0000-00-00',
            mb_profile = '',
            mb_memo_call = '',
            mb_memo_cnt = '0',
            mb_scrap_cnt = '0',
            mb_1 = '',
            mb_2 = '',
            mb_3 = '',
            mb_4 = '',
            mb_5 = '',
            mb_6 = '',
            mb_7 = '',
            mb_8 = '',
            mb_9 = '',
            mb_10 = ''
        where mb_id = '{$_POST['mb_id']}'";

        echo $_POST['mb_id'];
        echo $sql;

        // 값 전부 초기화 -> NULL로 만들기

        // $sql = "update {$g5['member_table']} set where mb_id = '{$_POST['mb_id']}'";
        $result = sql_query($sql);
        echo json_encode($result);
    } catch(Exception $e) {
        echo $e;
    }
    

}
?>