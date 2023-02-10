<?php
$sub_menu = "200900";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

$table_name = 'g5_write_qa';

if ($_GET['sfl'] && $_GET['stx']) {
  $sql = "SELECT * FROM {$table_name} WHERE {$_GET['sfl']} LIKE '%{$_GET['stx']}%' ORDER BY wr_id DESC";
} else {
  $sql = "SELECT * FROM {$table_name} ORDER BY wr_id DESC";
}

$QnA_list = sql_query($sql);
$QnA_list_count = sql_num_rows($QnA_list);

$page_size = 10;
$total_page = ceil($QnA_list_count / $page_size);

if (isset($_GET['page']) && $_GET['page'] > 0) {
  $page = $_GET['page'];
} else {
  $page = 1;
}

$g5['title'] = '업무 요청 리스트';
require_once './admin.head.php';
?>

<form class="local_sch01 local_sch" method="get">
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
        <th scope="col" id="wr_id">번호</th>
        <th scope="col" id="wr_subject">제목</th>
        <th scope="col" id="wr_status">상태</th>
        <th scope="col" id="wr_datetime">작성날짜</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($QnA_list_count == 0) {
        echo '<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
      } else {
        $table_sql = $sql . " LIMIT " . ($page - 1) * $page_size . ", " . $page_size;
        $table_list = sql_query($table_sql);

        for ($i = 0; $row = sql_fetch_array(
          $table_list
        ); $i++) {
      ?>
          <tr>
            <td headers="wr_id"><?php echo get_text($row['wr_id']); ?></td>
            <td headers="wr_subject"><?php echo get_text($row['wr_subject']); ?></td>
            <td headers="wr_status">
              <?php
              if ($row['wr_1'] == '0')
                echo '<span style="color: blue;">답변 완료</span>';
              else
                echo '답변 대기';
              ?>
            <td headers="wr_datetime"><?php echo get_text($row['wr_datetime']) ?></td>
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
    <a href="./work_request_create.php" id="wr_add" class="btn btn_03">업무 요청 작성</a>
  <?php } ?>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?' . $qstr . '&amp;page='); ?>

<script>
  async function delete_disclosure(id) {
    console.log(id);
    if (confirm('해당 공시를 삭제하시겠습니까?')) {
      await $.ajax({
        method: 'POST',
        url: "./work_request_create.php",
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
