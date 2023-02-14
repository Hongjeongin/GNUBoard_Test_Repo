<?php
$menu['menu200'] = array(
    array('200000', '마이페이지', G5_ADMIN_URL . '/member_form.php', 'member'),
    array('200100', '마이페이지', G5_ADMIN_URL . '/member_form.php', 'mb_mail'),
    array('200300', '회원 리스트', G5_ADMIN_URL . '/member_list.php', 'mb_list'),
    array('200310', '기자 리스트', G5_ADMIN_URL . '/reporter_list.php', 'mb_list'),
    //array('200300', '회원메일발송', G5_ADMIN_URL . '/mail_list.php', 'mb_mail'),
    // array('200800', '접속자집계', G5_ADMIN_URL . '/visit_list.php', 'mb_visit', 1),
    array('200800', '미디어 기사 관리', G5_ADMIN_URL . '/writing_list.php', 'mb_visit'),
    array('200810', '가입한 미디어', G5_ADMIN_URL . '/my_boardgroup_list.php', 'mb_search'),
    array('200830', '미디어 가입', G5_ADMIN_URL . '/register_boardgroup.php', ''),
    array('200820', '기사 관리', G5_ADMIN_URL . '/visit_delete.php', 'mb_delete'),
    array('200200', '공시 관리', G5_ADMIN_URL . '/disclosure.php', 'mb_point'),
    array('200900', '업무 요청', G5_ADMIN_URL . '/work_request.php', 'mb_poll'),
    array('200550', '섹션 관리', G5_ADMIN_URL . '/boardgroup_list.php', 'mb_board')
);