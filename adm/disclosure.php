<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

if ($_GET['sfl'] && $_GET['stx']) {
  $sql = "SELECT * FROM g5_write_notice WHERE {$_GET['sfl']} LIKE '%{$_GET['stx']}%' ORDER BY wr_id DESC";
} else {
  $sql = "SELECT * FROM g5_write_notice ORDER BY wr_id DESC";
}

$disclosure_list = sql_query($sql);
$disclosure_list_count = sql_num_rows($disclosure_list);

$page_size = 10;
$total_page = ceil($disclosure_list_count / $page_size);

if (isset($_GET['page']) && $_GET['page'] > 0) {
  $page = $_GET['page'];
} else {
  $page = 1;
}

$g5['title'] = '공시 리스트';
require_once './admin.head.php';
?>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
  <select name="sfl" id="sfl">
    <option value="wr_subject" <?php echo get_selected($sfl, 'wr_subject'); ?>>제목</option>
    <option value="wr_content" <?php echo get_selected($sfl, 'wr_content'); ?>>내용</option>
  </select>

  <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
  <input type="submit" class="btn_submit" value="검색">
</form>

<div class="tbl_head01 tbl_wrap">
  <table>
    <thead>
      <tr>
        <th scope="col" id="mb_list_name">번호</th>
        <th scope="col" id="mb_list_nick">제목</th>
        <th scope="col" id="mb_list_cert">작성자</th>
        <th scope="col" id="mb_list_mailc">작성날짜</th>
        <th scope="col" id="mb_list_mailr">등록날짜</th>
        <th scope="col" id="mb_list_mng">관리</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($disclosure_list_count == 0) {
        echo '<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
      } else {
        $table_sql = $sql . " LIMIT " . ($page - 1) * $page_size . ", " . $page_size;
        $table_list = sql_query($table_sql);

        for ($i = 0; $row = sql_fetch_array(
          $table_list
        ); $i++) {
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
      }
      ?>
    </tbody>
  </table>
</div>

<div class="btn_fixed_top">
  <?php if ($is_admin == 'super') { ?>
    <a href="./disclosure_form.php" id="wr_add" class="btn btn_03">공시 등록</a>
  <?php } ?>
</div>

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
    if (confirm('해당 공시를 삭제하시겠습니까?')) {
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
