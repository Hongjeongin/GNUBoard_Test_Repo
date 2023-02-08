<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

$authArray = ['일반회원', '기자', '미디어 관리자', '최고관리자'];
$writingStatusArray = ['대기', '승인', '거절'];

//$sql_common = " from {$g5['member_table']} ";
/**
 * 1. DB에서 g5_write가 붙은 테이블들 조회해서 배열로 만듦
 * 2. 해당 테이블들 전부 join + select query 진행
 * 3. 전부 나온 쿼리들 datetime 순으로 sort 하기 + 번호 붙이기
 * 
 * 
 * 
 * 
 * 
 */
$show_tables_sql = "SHOW TABLES IN dkmedia LIKE 'g5_write%'";

// $show_tables_sql = "SHOW TABLES
//                     IN dkmedia
//                     LIKE 'g5_write_noo%'";
// select_tables로 쿼리 실행 후 배열 리턴 받기
$tables = sql_query($show_tables_sql);
$join_user_and_board_sql = '';
// (게시판 테이블 + join 사용자 테이블) 전부 유니온한 쿼리 실행 order by datetime

$sql = "select * from g5_write_notice A LEFT JOIN {$g5['member_table']} B ON A.mb_id = B.mb_id order by wr_datetime desc";

for ($i = 0; $row = sql_fetch_array($tables); $i++) {
  $join_user_and_board_sql = $join_user_and_board_sql . "(select * from " . $row['Tables_in_dkmedia (g5_write%)'] . " A LEFT JOIN {$g5['member_table']} B ON A.mb_id = B.mb_id) UNION ";
}


$user_and_board_sql = substr($join_user_and_board_sql, 0, -6);

$user_and_board_sql = $user_and_board_sql . 'order by wr_datetime desc;';

$board_list = sql_query($sql);

$sql_common = "from {$g5['member_table']}";

$sql_search = " where (1) ";
if ($stx) {
  $sql_search .= " and ( ";
  switch ($sfl) {
    case 'mb_point':
      $sql_search .= " ({$sfl} >= '{$stx}') ";
      break;
    case 'mb_level':
      $sql_search .= " ({$sfl} = '{$stx}') ";
      break;
    case 'mb_tel':
    case 'mb_hp':
      $sql_search .= " ({$sfl} like '%{$stx}') ";
      break;
    default:
      $sql_search .= " ({$sfl} like '{$stx}%') ";
      break;
  }
  $sql_search .= " ) ";
}

if ($is_admin != 'super') {
  $sql_search .= " and mb_level <= '{$member['mb_level']}' ";
}

if (!$sst) {
  $sst = "mb_datetime";
  $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
  $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
}
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="ov_listall">전체목록</a>';

$g5['title'] = '공시 리스트';
require_once './admin.head.php';

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;
?>

<div class="local_ov01 local_ov">
  <?php echo $listall ?>
  <span class="btn_ov01"><span class="ov_txt">총회원수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
  <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01" data-tooltip-text="차단된 순으로 정렬합니다.&#xa;전체 데이터를 출력합니다."> <span class="ov_txt">차단 </span><span class="ov_num"><?php echo number_format($intercept_count) ?>명</span></a>
  <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01" data-tooltip-text="탈퇴된 순으로 정렬합니다.&#xa;전체 데이터를 출력합니다."> <span class="ov_txt">탈퇴 </span><span class="ov_num"><?php echo number_format($leave_count) ?>명</span></a>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

  <label for="sfl" class="sound_only">검색대상</label>
  <select name="sfl" id="sfl">
    <!-- <option value="mb_id" <?php echo get_selected($sfl, "mb_id"); ?>>회원아이디</option>
        <option value="mb_nick" <?php echo get_selected($sfl, "mb_nick"); ?>>닉네임</option> -->
    <option value="mb_name" <?php echo get_selected($sfl, "mb_name"); ?>>이름</option>
    <!-- <option value="mb_level" <?php echo get_selected($sfl, "mb_level"); ?>>권한</option>
        <option value="mb_email" <?php echo get_selected($sfl, "mb_email"); ?>>E-MAIL</option>
        <option value="mb_tel" <?php echo get_selected($sfl, "mb_tel"); ?>>전화번호</option>
        <option value="mb_hp" <?php echo get_selected($sfl, "mb_hp"); ?>>휴대폰번호</option>
        <option value="mb_point" <?php echo get_selected($sfl, "mb_point"); ?>>포인트</option>
        <option value="mb_datetime" <?php echo get_selected($sfl, "mb_datetime"); ?>>가입일시</option>
        <option value="mb_ip" <?php echo get_selected($sfl, "mb_ip"); ?>>IP</option>
        <option value="mb_recommend" <?php echo get_selected($sfl, "mb_recommend"); ?>>추천인</option> -->
  </select>
  <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
  <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
  <input type="submit" class="btn_submit" value="검색">

</form>

<div class="local_desc01 local_desc">
  <p>
    회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
  </p>
</div>


<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
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
          <th scope="col" id="mb_list_name">번호</th>
          <th scope="col" id="mb_list_nick">제목</th>
          <th scope="col" rowspan="2" id="mb_list_cert">작성자</th>
          <th scope="col" id="mb_list_mailc">작성날짜</th>
          <th scope="col" id="mb_list_mailr">등록날짜</th>
          <th scope="col" rowspan="2" id="mb_list_mng">관리</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM g5_write_notice ORDER BY wr_id DESC";
        $result = sql_query($sql);
        for ($i = 0; $row = sql_fetch_array($result); $i++) {
        ?>
          <tr class="<?php echo $bg; ?>">
            <td headers="mb_list_name"><?php echo get_text($row['wr_id']); ?></td>
            <td headers="mb_list_nick"><?php echo get_text($row['wr_subject']); ?></td>
            <td headers="mb_list_cert"><?php echo get_text($row['wr_name']); ?></td>
            <td headers="mb_list_mailc"><?php echo get_text($row['wr_datetime']) ?></td>
            <td headers="mb_list_mailr"><?php echo get_text($row['wr_datetime']) ?></td>
            <td headers="mb_list_mng" colspan="2" class="td_mng td_mng_s">
              <a href="" class="btn btn_02">확인</a>
              <a href="javascript:delete_disclosure('<?php echo $row['wr_id'] ?>')" class="btn btn_01">삭제</a>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?' . $qstr . '&amp;page='); ?>

<script>
  function fmemberlist_submit(f) {
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

  async function delete_disclosure(id) {
    console.log(id);
    if (confirm('정말 삭제하시겠습니까?')) {
      await $.ajax({
        method: 'POST',
        url: "./disclosure_delete.php",
        data: {
          id: id
        },
        success: function(data) {
          console.log(data);
          if (data) {
            alert('삭제되었습니다.');
            location.reload();
          } else {
            alert('삭제에 실패하였습니다.');
          }
        }
      });
    }
  }
</script>

<?php
require_once './admin.tail.php';
