<!-- <script src="http://code.jquery.com/jquery-latest.js"></script>  -->
<?php
$sub_menu = "200810";

require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

// $authArray = ['일반회원', '기자', '미디어 관리자', '최고관리자'];
$writingStatusArray = ['대기', '승인', '거절'];

//$sql_common = " from {$g5['member_table']} ";
/**
 * 1. DB에서 g5_write가 붙은 테이블들 조회해서 배열로 만듦
 * 2. 해당 테이블들 전부 join + select query 진행
 * 3. 전부 나온 쿼리들 datetime 순으로 sort 하기 + 번호 붙이기
 */
// $sql = "SELECT bo_table AS table_name FROM (SELECT @ROWNUM:=@ROWNUM+1 AS ROWNUM, b.* FROM g5_board b, (SELECT @ROWNUM:=0) TMP) A LEFT JOIN g5_group G ON A.gr_id = G.gr_id WHERE gr_1 NOT IN (3, 4);";

//$show_tables_sql = "SHOW TABLES IN dkmedia LIKE 'g5_write%'";

// $show_tables_sql = "SHOW TABLES
//                     IN dkmedia
//                     LIKE 'g5_write_noo%'";
// select_tables로 쿼리 실행 후 배열 리턴 받기
// $tables = sql_query($show_tables_sql);
// $tables = sql_query($sql);
// $join_user_and_board_sql = '';
// (게시판 테이블 + join 사용자 테이블) 전부 유니온한 쿼리 실행 order by datetime

// for ($i = 0; $row = sql_fetch_array($tables); $i++) {
//     $join_user_and_board_sql = $join_user_and_board_sql."(select * from g5_write_".$row['table_name']." A LEFT JOIN {$g5['member_table']} B ON A.mb_id = B.mb_id) UNION ";
// }

// $user_and_board_sql = substr($join_user_and_board_sql, 0, -6);

// $user_and_board_sql = $user_and_board_sql.'order by wr_datetime desc;';

// $board_list = sql_query($user_and_board_sql);

// 홍정인 작성 - FROM절을 미디어 이름의 게시글 테이블로 수정해야함.
$sql_common = "from g5_write_free ";

$sql_search = "";

if ($stx) {
    switch ($sfl) {
        case 'wr_content':
        case 'wr_subject':
            $sql_search .= " where {$sfl} like '%{$stx}%' ";
            break;
        default:
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    // $sql_search .= " ) ";
}

// if ($is_admin != 'super') {
//     $sql_search .= " and mb_level <= '{$member['mb_level']}' ";
// }

if (!$sst) {
    $sst = "wr_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";

$result = sql_query($sql);
$row = sql_fetch($result);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
    $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
}
$from_record = ($page - 1) * $rows; // 시작 열을 구함

//탈퇴회원수
// $sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
// $row = sql_fetch($sql);
// $leave_count = $row['cnt'];

//차단회원수
// $sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
// $row = sql_fetch($sql);
// $intercept_count = $row['cnt'];

// $listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="ov_listall">전체목록</a>';

$g5['title'] = '가입한 미디어';
require_once './admin.head.php';

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";

// 홍정인 작성 - FROM절을 미디어 이름의 게시글 테이블로 수정해야함.
// $sql = "select * from g5_wirte_free";
// $sql = "select * from g5_write_free A LEFT JOIN g5_member ON A.wr_name = B.mb_id";
$result = sql_query($sql);

$colspan = 16;

?>

<!-- <div class="local_ov01 local_ov"> -->
    <!-- <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총회원수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span> -->
    <!-- <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01" data-tooltip-text="차단된 순으로 정렬합니다.&#xa;전체 데이터를 출력합니다."> <span class="ov_txt">차단 </span><span class="ov_num"><?php echo number_format($intercept_count) ?>명</span></a>
    <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01" data-tooltip-text="탈퇴된 순으로 정렬합니다.&#xa;전체 데이터를 출력합니다."> <span class="ov_txt">탈퇴 </span><span class="ov_num"><?php echo number_format($leave_count) ?>명</span></a> -->
<!-- </div> -->

<!-- <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get"> -->
    <!-- <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl"> -->
        <!-- <option value="mb_id" <?php echo get_selected($sfl, "mb_id"); ?>>회원아이디</option>
        <option value="mb_nick" <?php echo get_selected($sfl, "mb_nick"); ?>>닉네임</option> -->
        <!-- <option value="wr_subject" <?php echo get_selected($sfl, "wr_subject"); ?>>제목</option>
        <option value="wr_content" <?php echo get_selected($sfl, "wr_content"); ?>>내용</option> -->
        <!-- <option value="mb_email" <?php echo get_selected($sfl, "mb_email"); ?>>E-MAIL</option>
        <option value="mb_tel" <?php echo get_selected($sfl, "mb_tel"); ?>>전화번호</option>
        <option value="mb_hp" <?php echo get_selected($sfl, "mb_hp"); ?>>휴대폰번호</option>
        <option value="mb_point" <?php echo get_selected($sfl, "mb_point"); ?>>포인트</option>
        <option value="mb_datetime" <?php echo get_selected($sfl, "mb_datetime"); ?>>가입일시</option>
        <option value="mb_ip" <?php echo get_selected($sfl, "mb_ip"); ?>>IP</option>
        <option value="mb_recommend" <?php echo get_selected($sfl, "mb_recommend"); ?>>추천인</option> -->
    <!-- </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
    <input type="submit" class="btn_submit" value="검색"> -->
<!-- </form> -->

<!-- <div class="local_desc01 local_desc">
    <p>
        회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
    </p>
</div> -->


<!-- onsubmit="return fmemberlist_submit(this);" -->
<!-- action="./writing_list_update.php"  -->
<!-- method="post" -->
<form name="fmemberlist" id="fmemberlist" onsubmit="return fmemberlist_submit(this);" action="./writing_list_update.php"  method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_head01 tbl_wrap none_border">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <!-- <thead> -->
                <!-- <tr> -->
                    <!-- <th scope="col" id="mb_list_chk" rowspan="2">
                        <label for="chkall" class="sound_only">회원 전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th> -->
                    <!-- <th scope="col" id="wr_list_name"></th>
                    <th scope="col" id="wr_list_nick"></th>
                    <th scope="col" rowspan="2" id="wr_list_cert"></th>
                    <th scope="col" id="wr_list_mailc"></th> -->
                    <!-- <th scope="col" id="mb_list_open">정보공개</a></th> -->
                    <!-- <th scope="col" id="wr_list_mailr">등록날짜</th>
                    <th scope="col" id="wr_list_auth">상태</th> -->
                    <!-- <th scope="col" id="mb_list_mobile">휴대폰</th>
                    <th scope="col" id="mb_list_lastcall"><?php echo subject_sort_link('mb_today_login', '', 'desc') ?>최종접속</a></th>
                    <th scope="col" id="mb_list_grp">접근그룹</th> -->
                    <!-- <th scope="col" rowspan="2" id="wr_list_mng">관리</th> -->
                <!-- </tr> -->
                <!-- <tr>
                    <th scope="col" id="mb_list_name"><?php echo subject_sort_link('mb_name') ?>번호</a></th>
                    <th scope="col" id="mb_list_nick"><?php echo subject_sort_link('mb_nick') ?>회원명</a></th>
                    <th scope="col" id="mb_list_sms"><?php echo subject_sort_link('mb_sms', '', 'desc') ?>SMS수신</a></th>
                    <th scope="col" id="mb_list_adultc"><?php echo subject_sort_link('mb_adult', '', 'desc') ?>성인인증</a></th>
                    <th scope="col" id="mb_list_auth"><?php echo subject_sort_link('mb_intercept_date', '', 'desc') ?>접근차단</a></th>
                    <th scope="col" id="mb_list_deny"><?php echo subject_sort_link('mb_level', '', 'desc') ?>권한</a></th>
                    <th scope="col" id="mb_list_tel">전화번호</th>
                    <th scope="col" id="mb_list_join"><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>가입일</a></th>
                    <th scope="col" id="mb_list_point"><?php echo subject_sort_link('mb_point', '', 'desc') ?> 포인트</a></th>
                </tr> -->
            <!-- </thead> -->
            <tbody>
                <?php
                
                if (!$result->num_rows) {
                    echo "<tr><td colspan=\"" . $colspan . "\" class=\"empty_table\">자료가 없습니다.</td></tr>";
                } else {
                    for ($i = $result->num_rows; $row = sql_fetch_array($result); $i--) {
                        // 접근가능한 그룹수
                        // $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
                        // $row2 = sql_fetch($sql2);

                        echo '<script>';
                        echo 'console.log("'.$row.'");';
                        echo '</script>';
    
    
    
                        // $group = '';
                        // if ($row2['cnt']) {
                        //     $group = '<a href="./boardgroupmember_form.php?mb_id=' . $row['mb_id'] . '">' . $row2['cnt'] . '</a>';
                        // }
    
                        // echo '<script>';
                        // echo 'console.log('.json_encode($row).');';
                        // echo '</script>';
    
                        // 관리
                        // 확인, 승인, 거절 버튼
                        $s_inf = "<button id='search_".$row['wr_id']."' class='btn btn_01 btn-modal'>확인</button>";
                        $s_all = "<button id='allow_".$row['wr_id']."' class='btn btn_03 btn-modal'>승인</button>";
                        $s_ref = "<button id='refer_".$row['wr_id']."' class='btn btn_02 btn-modal'>거절</button>";
                        
                        // 글 상태 << 에 따라 관리 컬럼에 나타나는 버튼들이 달라짐
                        $writingStatus = $row['wr_1'];

                        // if ($row['mb_level'] > 8) {
                        //     $s_all = '';
                        //     $s_ref = '';
                        // } else {
                            
                        // }
                        // // if ($is_admin == 'group') {
                        // //     $s_mod = '';
                        // // } else {
                        // //     $s_mod = '<a href="./member_form.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $row['mb_id'] . '" class="btn btn_03">수정</a>';
                        // // }
                        // // $s_grp = '<a href="./boardgroupmember_form.php?mb_id=' . $row['mb_id'] . '" class="btn btn_02">그룹</a>';
    
                        // $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
                        // $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);
    
                        // $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);
    
                        // $mb_id = $row['mb_id'];
                        // $leave_msg = '';
                        // $intercept_msg = '';
                        // $intercept_title = '';
                        // if ($row['mb_leave_date']) {
                        //     $mb_id = $mb_id;
                        //     $leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
                        // } elseif ($row['mb_intercept_date']) {
                        //     $mb_id = $mb_id;
                        //     $intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
                        //     $intercept_title = '차단해제';
                        // }
                        // if ($intercept_title == '') {
                        //     $intercept_title = '차단하기';
                        // }
    
                        // $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';
    
                        // $bg = 'bg' . ($i % 2);
    
                        // switch ($row['mb_certify']) {
                        //     case 'hp':
                        //         $mb_certify_case = '휴대폰';
                        //         $mb_certify_val = 'hp';
                        //         break;
                        //     case 'ipin':
                        //         $mb_certify_case = '아이핀';
                        //         $mb_certify_val = '';
                        //         break;
                        //     case 'simple':
                        //         $mb_certify_case = '간편인증';
                        //         $mb_certify_val = '';
                        //         break;
                        //     case 'admin':
                        //         $mb_certify_case = '관리자';
                        //         $mb_certify_val = 'admin';
                        //         break;
                        //     default:
                        //         $mb_certify_case = '&nbsp;';
                        //         $mb_certify_val = 'admin';
                        //         break;
                        // }
                    ?>
    
                        <!-- <tr class="<?php echo $bg; ?>">
                            <td headers="mb_list_chk" class="td_chk" rowspan="2">
                                <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
                                <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
                                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                            </td> -->
                            <!-- <td headers="mb_list_id" colspan="2" class="td_name sv_use">
                                <?php echo $mb_id ?>
                                <?php
                                //소셜계정이 있다면
                                if (function_exists('social_login_link_account')) {
                                    if ($my_social_accounts = social_login_link_account($row['mb_id'], false, 'get_data')) {
                                        echo '<div class="member_social_provider sns-wrap-over sns-wrap-32">';
                                        foreach ((array) $my_social_accounts as $account) {     //반복문
                                            if (empty($account) || empty($account['provider'])) {
                                                continue;
                                            }
    
                                            $provider = strtolower($account['provider']);
                                            $provider_name = social_get_provider_service_name($provider);
    
                                            echo '<span class="sns-icon sns-' . $provider . '" title="' . $provider_name . '">';
                                            echo '<span class="ico"></span>';
                                            echo '<span class="txt">' . $provider_name . '</span>';
                                            echo '</span>';
                                        }
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </td> 
                            <td headers="mb_list_cert" rowspan="2" class="td_mbcert">
                                <input type="radio" name="mb_certify[<?php echo $i; ?>]" value="simple" id="mb_certify_sa_<?php echo $i; ?>" <?php echo $row['mb_certify'] == 'simple' ? 'checked' : ''; ?>>
                                <label for="mb_certify_sa_<?php echo $i; ?>">간편인증</label><br>
                                <input type="radio" name="mb_certify[<?php echo $i; ?>]" value="hp" id="mb_certify_hp_<?php echo $i; ?>" <?php echo $row['mb_certify'] == 'hp' ? 'checked' : ''; ?>>
                                <label for="mb_certify_hp_<?php echo $i; ?>">휴대폰</label><br>
                                <input type="radio" name="mb_certify[<?php echo $i; ?>]" value="ipin" id="mb_certify_ipin_<?php echo $i; ?>" <?php echo $row['mb_certify'] == 'ipin' ? 'checked' : ''; ?>>
                                <label for="mb_certify_ipin_<?php echo $i; ?>">아이핀</label>
                            </td> -->
                            <!-- <td headers="mb_list_mailc"><?php echo preg_match('/[1-9]/', $row['mb_email_certify']) ? '<span class="txt_true">Yes</span>' : '<span class="txt_false">No</span>'; ?></td> -->
                            <!-- <td headers="mb_list_open">
                                <label for="mb_open_<?php echo $i; ?>" class="sound_only">정보공개</label>
                                <input type="checkbox" name="mb_open[<?php echo $i; ?>]" <?php echo $row['mb_open'] ? 'checked' : ''; ?> value="1" id="mb_open_<?php echo $i; ?>">
                            </td>
                            <td headers="mb_list_mailr">
                                <label for="mb_mailling_<?php echo $i; ?>" class="sound_only">메일수신</label>
                                <input type="checkbox" name="mb_mailling[<?php echo $i; ?>]" <?php echo $row['mb_mailling'] ? 'checked' : ''; ?> value="1" id="mb_mailling_<?php echo $i; ?>">
                            </td>
                            <td headers="mb_list_auth" class="td_mbstat">
                                <?php
                                if ($leave_msg || $intercept_msg) {
                                    echo $leave_msg . ' ' . $intercept_msg;
                                } else {
                                    echo "정상    수 백발백중";
                                }
                                ?> 
                            </td> -->
                            <!-- <td headers="mb_list_mobile" class="td_tel"><?php echo get_text($row['mb_hp']); ?></td>
                            <td headers="mb_list_lastcall" class="td_date"><?php echo substr($row['mb_today_login'], 2, 8); ?></td>
                            <td headers="mb_list_grp" class="td_numsmall"><?php echo $group ?></td> -->
                            <!-- <td headers="mb_list_mng" rowspan="2" class="td_mng td_mng_s"><?php echo $s_mod ?><?php echo $s_grp ?></td> -->
                        <!-- </tr> -->
                        <tr class="<?php echo $bg; ?> media_margin">
                            <td>
                                <div>
                                    <img src="../img/lake.jpg" width=100 height=100>
                                    <p>안녕?</p>
                                </div>
                                <!-- <img src="../img/lake.jpg" width=100 height=100> -->
                                
                            </td>
                            <td>
                                <img src="../img/lake.jpg" width=100 height=100>
                                <p>안녕?</p>
                            </td>
                            <td>
                                <img src="../img/lake.jpg" width=100 height=100>
                                <p>안녕?</p>
                            </td>
                            <td>
                                <img src="../img/lake.jpg" width=100 height=100>
                                <p>안녕?</p>
                            </td>
                            <!-- <td headers="wr_list_name"><?php echo get_text($i);?></td>  -->
                            <!-- <td headers="mb_list_nick" class="td_mbname"><?php echo get_text($row['mb_name']); ?></td> -->
                            <!-- <td headers="wr_list_nick"><?php echo get_text($row['wr_subject']); ?></td> -->
                            <!-- <td headers="mb_list_cert" class="td_name sv_use">
                                <div><?php echo get_text($row['mb_id']); ?></div>
                            </td> -->
                            <!-- 해당 글의 writer name을 넣고 -->
                            <!-- <td headers="wr_list_cert"><?php echo get_text($row['wr_name']);?></td>
                            <td headers="wr_list_mailc"><?php echo get_text($row['wr_datetime']); ?></td> -->
    
                            <!-- <td headers="mb_list_sms">
                                <label for="mb_sms_<?php echo $i; ?>" class="sound_only">SMS수신</label>
                                <input type="checkbox" name="mb_sms[<?php echo $i; ?>]" <?php echo $row['mb_sms'] ? 'checked' : ''; ?> value="1" id="mb_sms_<?php echo $i; ?>">
                            </td>
                            <td headers="mb_list_adultc">
                                <label for="mb_adult_<?php echo $i; ?>" class="sound_only">성인인증</label>
                                <input type="checkbox" name="mb_adult[<?php echo $i; ?>]" <?php echo $row['mb_adult'] ? 'checked' : ''; ?> value="1" id="mb_adult_<?php echo $i; ?>">
                            </td> -->
                            <!-- <td headers="mb_list_deny">
                                <?php if (empty($row['wr_leave_date'])) { ?>
                                    <input type="checkbox" name="mb_intercept_date[<?php echo $i; ?>]" <?php echo $row['mb_intercept_date'] ? 'checked' : ''; ?> value="<?php echo $intercept_date ?>" id="mb_intercept_date_<?php echo $i ?>" title="<?php echo $intercept_title ?>">
                                    <label for="mb_intercept_date_<?php echo $i; ?>" class="sound_only">접근차단</label>
                                <?php } ?>
                            </td> -->
                            <!-- <td headers="wr_list_mailr"><?php echo get_text($row['wr_datetime'])?></td>
                            <td headers="wr_list_auth">
                                <?php
                                    if ($writingStatus == 0) {
                                        echo '<script>';
                                        echo 'console.log("1111");';
                                        echo '</script>';
                                        echo $writingStatusArray[0];
                                    } else if ($writingStatus == 1) {
                                        echo '<script>';
                                        echo 'console.log("2111");';
                                        echo '</script>';
                                        echo $writingStatusArray[1];
                                    } else if ($writingStatus == 2) {
                                        echo '<script>';
                                        echo 'console.log("3111");';
                                        echo '</script>';
                                        echo $writingStatusArray[2];
                                    }
                                ?>
                            </td> -->
                            <!-- <td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_tel']); ?></td> -->
                            <!-- <td headers="mb_list_join" class="td_date"><?php echo substr($row['mb_datetime'], 2, 8); ?></td> -->
                            <!-- <td headers="mb_list_point" class="td_num"><a href="point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo number_format($row['mb_point']) ?></a></td> -->
                            <!-- <td headers="wr_list_mng" colspan="2" class="td_mng td_mng_s">
                                <?php
                                    if ($writingStatus == 0) {
                                        echo $s_inf;
                                        echo $s_all;
                                        echo $s_ref;
                                    } else if ($writingStatus == 1) {
                                        echo $s_inf;
                                        echo $s_ref;
                                    } else if ($writingStatus == 2) {
                                        echo $s_inf;
                                        echo $s_all;
                                    }
                                ?>
                            </td> -->
    
                        </tr>
    
                    <?php
                    }
                }
                    ?>
            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
        <!-- <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02">
        <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02"> -->
        <!-- <?php if ($is_admin == 'super') { ?>
            <a href="./member_form.php" id="member_add" class="btn btn_01">회원추가</a>
        <?php } ?> -->
    </div>

    <!-- <div id="container">
        <h2>Lorem Ipsum</h2>
        <button id="btn-modal">모달 창 열기 버튼</button>
    </div> -->
</form>

<div id="modal" class="modal-overlay">
    <div class="modal-window">
        <div class="title">
            <h2>회원정보 확인</h2>
        </div>
        <div class="close-area">X</div>
        <div class="content">
            <table>
                <th>테이블 헤더 만드는 태그</th>
                <tr>
                    <td>
                        <p>안녕하세요</p>
                    </td>
                    <td>
                        <p>안녕하세요22</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div id="modal_" class="modal-overlay_">
    <div class="modal-window">
        <div class="title">
            <h2 class="modal_title_">기사 반려</h2>
        </div>
        <div class="close-area_">X</div>
        <div class="content">
            <div>
                <h2 class="modal_content_">해당 기사를 거절하시겠습니까?<h2>
            </div>

            <div class="evenly_fix">
                <a href="javascript:delete_();" class="btn btn_03 submit_btn_">삭제</a>
                <a href="javascript:modal_Off();" class="btn btn_03">취소</a>
            </div>
        </div>
    </div>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?' . $qstr . '&amp;page='); ?>

<script>
    function fmemberlist_submit(f) {
        // if (!is_checked("chk[]")) {
        //     alert(document.pressed + " 하실 항목을 하나 이상 선택하세요.");
        //     return false;
        // }

        if (document.pressed == "선택삭제") {
            if (!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                return false;
            }
        }

        return false;
    } 
</script>

<script>
    let curWriteId = '';


    const loremIpsum = document.getElementById("lorem-ipsum");

    fetch("https://baconipsum.com/api/?type=all-meat&paras=200&format=html")
        .then(response => response.text())
        .then(result => loremIpsum.innerHTML = result)
        const modal = document.getElementById("modal")
    function modalOn() {
        modal.style.display = "flex"
    }
    function isModalOn() {
        return modal.style.display === "flex"
    }
    function modalOff() {
        modal.style.display = "none"
    }

    const closeBtn = modal.querySelector(".close-area")
    closeBtn.addEventListener("click", e => {
        modalOff()
    })

    function modal_On() {
        modal_.style.display = "flex"
    }
    function isModal_On() {
        return modal_.style.display === "flex"
    }

    function modal_Off() {
        modal_.style.display = "none"
    }

    const closeBtn_ = modal_.querySelector(".close-area_")
    closeBtn_.addEventListener("click", e => {
        modal_Off()
    })

    modal.addEventListener("click", e => {
        const evTarget = e.target
        if(evTarget.classList.contains("modal-overlay")) {
            modalOff()
        }
    })

    modal_.addEventListener("click", e => {
        const evTarget = e.target
        if(evTarget.classList.contains("modal-overlay_")) {
            modal_Off()
        }
    })

    window.addEventListener("keyup", e => {
        if(isModal_On() && e.key === "Escape") {
            modal_Off();
        }
        if(isModalOn() && e.key === "Escape") {
            modalOff();
        }
    })
    
    // 확인 함수
    // 23.02.11 해당 기사 조회 후 화면에 보여주는 기능 필요
    async function search_write() {
        // ajax 확인 php파일
        const result = await $.ajax({
            method: 'POST',
            url: "./writing_update.php",
            data: { 
                wr_id: curWriteId,
                option: 'search',
            },
            success: function(data) {
                if (!data) {
                    alert('DB 오류입니다.');
                    return false;
                }
                return data;
            }
        });
        window.location.reload();
    }

    // 승인 함수
    async function allow_write() {
        // ajax 승인 php파일
        const result = await $.ajax({
            method: 'POST',
            url: "./writing_update.php",
            data: { 
                wr_id: curWriteId,
                option: 'allow',
            },
            success: function(data) {
                if (!data) {
                    alert('DB 오류입니다.');
                    return false;
                }
                return data;
            }
        });
        window.location.reload();
        modal_Off();
    }

    // 거절 함수
    async function refer_write() {
        // ajax 거절 php파일
        const result = await $.ajax({
            method: 'POST',
            url: "./writing_update.php",
            data: { 
                wr_id: curWriteId,
                option: 'refer',
            },
            success: function(data) {
                if (!data) {
                    alert('DB 오류입니다.');
                    return false;
                }
                return data;
            }
        });
        window.location.reload();
        modal_Off();
    }


    var btnModal = document.querySelectorAll(".btn-modal");

    btnModal.forEach(
        function(currentValue, currentIndex, listObj) {
            currentValue.addEventListener("click", async e => {
                const curBtn = (currentValue.id).split('_');
                const curBtnText = curBtn[0];
                const curBtnGroup = curBtn[1];

                const submit_btn = document.querySelector(".submit_btn");
                const submit_btn_ = document.querySelector(".submit_btn_");
                const modal_title_ = document.querySelector(".modal_title_");
                const modal_content_ = document.querySelector(".modal_content_");
                // const modal_title = document.querySelector(".modal_title");

                $('.btn-modal').on('click', function() {
                    var thisRow = $(this).closest('tr');
                });

                curWriteId = curBtnGroup;

                

                // 승인 버튼 눌렸을 때
                if (curBtnText === 'allow') {
                    modal_title_.innerHTML = '기사 승인';
                    modal_content_.innerHTML = '해당 기사를 승인하시겠습니까?';
                    submit_btn_.innerHTML = '승인';
                    await $('.submit_btn_').prop('href', 'javascript:allow_write();');
                    modal_On();
                    return;
                }
                
                // 거절 버튼 눌렸을 때
                if (curBtnText === 'refer') {
                    modal_title_.innerHTML = '기사 반려';
                    modal_content_.innerHTML = '해당 기사를 거절하시겠습니까?';
                    submit_btn_.innerHTML = '거절';
                    await $('.submit_btn_').prop('href', 'javascript:refer_write();');
                    modal_On();
                    return;
                }

                // 확인 버튼 눌렸을 때
                if (curBtnText === 'search') {
                    search_write();
                    return;
                }

                // if (!curBtnGroup) {
                //     modal_title.innerHTML = '섹션 추가';
                //     submit_btn.innerHTML = '추가';
                //     $('#auth_selectBox').val('1').prop('selected', true);
                //     document.getElementById("auth_selectBox").removeAttribute("disabled");
                //     await $('.submit_btn').prop('href', 'javascript:create_section();');
                //     document.getElementById("modal_user_name").value = '';
                //     modalOn();
                //     return;
                // }

                // if (curBtnText === 'search') {
                //     // section_names + numbers 만들기
                //     let order = document.querySelectorAll(".tbl_input");
                //     let section_ = document.querySelectorAll(".td_category");
                //     for (let i = 0; i < order.length; i++) {
                //         section_names.push(section_[i].innerText);
                //         numbers.push(order[i].value);
                //     }
                //     await finish();
                //     return;
                // }


                // id = (await select_section_type(curBtnGroup))['gr_1'];
                // if (!id) return;

                // const bo_name = document.getElementById(`td_category_${curBtnGroup}`).innerText;
                // original_bo_table = bo_name
                // document.getElementById("modal_user_name").value = bo_name;

                // if (curBtnText === 'delete') {
                //     await $('.submit_btn_').prop('href', 'javascript:delete_section();');
                //     modal_On();
                //     return;
                // }

                // sections = [];

                // await $.ajax({
                //     method: 'POST',
                //     url: "./boardgroup_list_select_type.php",
                //     data: { se_no: curBtnGroup, no: 'no' },
                //     success: function(data) {
                //         const array_data = data.split('}');
                        
                //         for (let i = 0; i < array_data.length; i++) {
                            
                //             if (i + 1 === array_data.length) {
                //                 break;
                //             }
                //             array_data[i] += "}";

                //             sections.push((JSON.parse(array_data[i]))['me_name']);
                //         }
                //     }
                // })

                // if (curBtnText === 'modify') {
                //     modal_title.innerHTML = '섹션 수정';
                //     submit_btn.innerHTML = '수정';
                //     $('#auth_selectBox').val(`${curBtnGroup}`).prop('selected', true);

                //     // const bo_name = document.getElementById(`td_category_${curBtnGroup}`).innerText;
                //     // original_bo_table = bo_name
                //     // document.getElementById("modal_user_name").value = bo_name;
                //     await $('.submit_btn').prop('href', 'javascript:update_section();');
                    
                //     document.getElementById("auth_selectBox").disabled = "disabled";
                //     $('.under_section_table').empty();
                //     if (!(sections[0] === null)) {
                //         for(let i = 0; i < sections.length; i++) {
                //             add_under_section();
                //             document.getElementById(`under_input_${i + 1}`).value = sections[i];
                //         }
                //     }
                // }
                await modalOn();
            });
        }
    );
</script>

<?php
require_once './admin.tail.php';
