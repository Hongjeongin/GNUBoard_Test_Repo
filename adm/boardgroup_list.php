<?php
$sub_menu = "200550";
require_once './_common.php';

// auth_check_menu($auth, $sub_menu, 'r');

// if (!isset($group['gr_device'])) {
//     // 게시판 그룹 사용 필드 추가
//     // both : pc, mobile 둘다 사용
//     // pc : pc 전용 사용
//     // mobile : mobile 전용 사용
//     // none : 사용 안함
//     sql_query(" ALTER TABLE  `{$g5['group_table']}` ADD  `gr_device` ENUM(  'both',  'pc',  'mobile' ) NOT NULL DEFAULT  'both' AFTER  `gr_subject` ", false);
// }

$sql_common = " from {$g5['group_table']} ";

$sql_section_type = "select * from g5_group";

$query = sql_query($sql_section_type);

$section_types = [];

for($i = 0; $row = sql_fetch_array($query); $i++) {
    array_push($section_types, $row['se_id']);
}

$sql_search = " where (1) ";
if ($is_admin != 'super') {
    $sql_search .= " and (gr_admin = '{$member['mb_id']}') ";
}

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "gr_id":
        case "gr_admin":
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default:
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($sst) {
    $sql_order = " order by {$sst} {$sod} ";
} else {
    $sql_order = " order by gr_id asc ";
}

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
    $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
}
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// $sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
// $sql = "SELECT @ROWNUM:=@ROWNUM+1 AS ROWNUM, g.* FROM g5_group g, (SELECT @ROWNUM:=0) TMP;";
$sql = "SELECT * FROM (SELECT @ROWNUM:=@ROWNUM+1 AS ROWNUM, g.* FROM g5_group g, (SELECT @ROWNUM:=0) TMP) A LEFT JOIN g5_sections S ON A.gr_1 = S.se_no;";
$result = sql_query($sql);

// $listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="ov_listall">처음</a>';

$g5['title'] = '섹션 설정';
require_once './admin.head.php';

$colspan = 10;
?>

<!-- <div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">전체그룹</span><span class="ov_num"> <?php echo number_format($total_count) ?>개</span></span>
</div> -->

<!-- <form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="gr_subject" <?php echo get_selected($sfl, "gr_subject"); ?>>제목</option>
        <option value="gr_id" <?php echo get_selected($sfl, "gr_id"); ?>>ID</option>
        <option value="gr_admin" <?php echo get_selected($sfl, "gr_admin"); ?>>그룹관리자</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" id="stx" value="<?php echo $stx ?>" required class="required frm_input">
    <input type="submit" value="검색" class="btn_submit">
</form> -->


<form name="fboardgrouplist" id="fboardgrouplist" action="./boardgroup_list_update.php" onsubmit="return fboardgrouplist_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
                <tr>
                    <!-- <th scope="col">
                        <label for="chkall" class="sound_only">그룹 전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th> -->
                    <th scope="col">게시순서</th>
                    <th scope="col">섹션타입</th>
                    <th scope="col">섹션명</th>
                    <th scope="col">관리</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    // 접근회원수
                    $sql1 = " select count(*) as cnt from {$g5['group_member_table']} where gr_id = '{$row['gr_id']}' ";
                    $row1 = sql_fetch($sql1);

                    // 게시판수
                    $sql2 = " select count(*) as cnt from {$g5['board_table']} where gr_id = '{$row['gr_id']}' ";
                    $row2 = sql_fetch($sql2);

                    $s_upd = '<a href="./boardgroup_form.php?' . $qstr . '&amp;w=u&amp;gr_id=' . $row['gr_id'] . '" class="btn_03 btn">수정</a>';

                    $bg = 'bg' . ($i % 2);
                ?>

                    <tr class="<?php echo $bg; ?>">
                        <!-- <td class="td_chk">
                            <input type="hidden" name="group_id[<?php echo $i ?>]" value="<?php echo $row['gr_id'] ?>">
                            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['gr_subject']); ?> 그룹</label>
                            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        </td> -->
                        <td><?php echo $row['ROWNUM'] ?></td>
                        <!-- 섹션타입 for문 필요 -->
                        
                        <td>
                            <?php echo $row['se_id'] ?>
                            <!-- <select name="sfl" id="sfl">
                                <?php foreach($section_types as $value) { ?>
                                    <option><?=$value?></option>
                                <?php }?>
                            </select> -->
                        </td>
                        <td><?php echo $row['gr_id'] ?></td>
                        <!-- <td class="td_num"><a href="./board_list.php?sfl=a.gr_id&amp;stx=<?php echo $row['gr_id'] ?>"><?php echo $row2['cnt'] ?></a></td>
                        <td class="td_numsmall">
                            <label for="gr_use_access_<?php echo $i; ?>" class="sound_only">접근회원 사용</label>
                            <input type="checkbox" name="gr_use_access[<?php echo $i ?>]" <?php echo $row['gr_use_access'] ? 'checked' : '' ?> value="1" id="gr_use_access_<?php echo $i ?>">
                        </td>
                        <td class="td_num"><a href="./boardgroupmember_list.php?gr_id=<?php echo $row['gr_id'] ?>"><?php echo $row1['cnt'] ?></a></td>
                        <td class="td_numsmall">
                            <label for="gr_order_<?php echo $i; ?>" class="sound_only">메인메뉴 출력순서</label>
                            <input type="text" name="gr_order[<?php echo $i ?>]" value="<?php echo $row['gr_order'] ?>" id="gr_order_<?php echo $i ?>" class="tbl_input" size="2">
                        </td>
                        <td class="td_mng">
                            <label for="gr_device_<?php echo $i; ?>" class="sound_only">접속기기</label>
                            <select name="gr_device[<?php echo $i ?>]" id="gr_device_<?php echo $i ?>">
                                <option value="both" <?php echo get_selected($row['gr_device'], 'both'); ?>>모두</option>
                                <option value="pc" <?php echo get_selected($row['gr_device'], 'pc'); ?>>PC</option>
                                <option value="mobile" <?php echo get_selected($row['gr_device'], 'mobile'); ?>>모바일</option>
                            </select>
                        </td> -->
                        <td><?php echo $s_upd ?></td>
                    </tr>
                <?php
                }
                if ($i == 0) {
                    echo '<tr><td colspan="' . $colspan . '" class="empty_table">자료가 없습니다.</td></tr>';
                }
                ?>
        </table>
    </div>

    <div class="btn_fixed_top">
        <!-- <input type="submit" name="act_button" onclick="document.pressed=this.value" value="선택수정" class="btn btn_02">
        <input type="submit" name="act_button" onclick="document.pressed=this.value" value="선택삭제" class="btn btn_02"> -->
        <a href="./boardgroup_form.php" class="btn btn_01">섹션 추가하기</a>
    </div>
</form>

<div class="local_desc01 local_desc">
    <p>
        접근사용 옵션을 설정하시면 관리자가 지정한 회원만 해당 그룹에 접근할 수 있습니다.<br>
        접근사용 옵션은 해당 그룹에 속한 모든 게시판에 적용됩니다.
    </p>
</div>

<?php
$pagelist = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');
echo $pagelist;
?>

<script>
    function fboardgrouplist_submit(f) {
        if (!is_checked("chk[]")) {
            alert(document.pressed + " 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        if (document.pressed == "선택삭제") {
            if (!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                return false;
            }
        }

        return true;
    }
</script>

<?php
require_once './admin.tail.php';
