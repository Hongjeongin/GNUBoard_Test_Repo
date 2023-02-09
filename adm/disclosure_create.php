<?php
$sub_menu = "200100";
require_once './_common.php';

$g5['title'] = '공시 등록';
require_once './admin.head.php';

if ($_POST['submit']) {
  $wr = array(
    'wr_num' => 0,
    'wr_reply' => '',
    'wr_parent' => '',
    'wr_is_comment' => 0,
    'wr_comment' => 0,
    'wr_comment_reply' => '',
    'ca_name' => '',
    'wr_option' => '',
    'wr_subject' => '',
    'wr_content' => '',
    'wr_seo_title' => '',
    'wr_link1' => '',
    'wr_link2' => '',
    'wr_link1_hit' => 0,
    'wr_link2_hit' => 0,
    'wr_hit' => 0,
    'wr_good' => 0,
    'wr_nogood' => 0,
    'mb_id' => '',
    'wr_password' => '',
    'wr_name' => '',
    'wr_email' => '',
    'wr_homepage' => '',
    'wr_datetime' => '',
    'wr_file' => 0,
    'wr_last' => '',
    'wr_ip' => '',
    'wr_facebook_user' => '',
    'wr_twitter_user' => '',
    'wr_1' => '',
    'wr_2' => '',
    'wr_3' => '',
    'wr_4' => '',
    'wr_5' => '',
    'wr_6' => '',
    'wr_7' => '',
    'wr_8' => '',
    'wr_9' => '',
    'wr_10' => '',
  );

  $wr['wr_subject'] = $_POST['wr_subject'];
  $wr['wr_content'] = $_POST['wr_content'];
  $wr['wr_name'] = $_POST['wr_name'];
  $wr['wr_datetime'] = $_POST['wr_datetime'];

  $wr_sql = "insert into g5_write_notice set ";
  foreach ($wr as $key => $value) {
    $wr_sql .= $key . " = '" . $value . "', ";
  }
  $wr_sql = substr($wr_sql, 0, -2);

  $wr_result = sql_query($wr_sql);

  if ($_FILES['file']['name'][0] != '') {
    $file = array(
      'name_origin' => [],
      'name_save' => [],
      'created_at' => [],
    );

    for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
      $file['name_origin'][$i] = $_FILES['file']['name'][$i];
      $file['name_save'][$i] = $_FILES['file']['name'][$i];
      $file['created_at'][$i] = date('Y-m-d H:i:s');
    }

    $file_sql = "insert into g5_upload_file set ";
    for ($i = 0; $i < count($file['name_origin']); $i++) {
      $file_sql .= "name_origin = '" . $file['name_origin'][$i] . "', ";
      $file_sql .= "name_save = '" . $file['name_save'][$i] . "', ";
      $file_sql .= "created_at = '" . $file['created_at'][$i] . "', ";
      $file_sql = substr($file_sql, 0, -2);
    }

    $file_result = sql_query($file_sql);
  }

  if ($wr_result && ($file_result || $_FILES['file']['name'][0] == '')) {
    alert('등록되었습니다.', './disclosure.php');
  } else {
    alert('등록에 실패했습니다.', './disclosure.php');
  }
}
?>

<form action="" method="post" enctype="multipart/form-data">
  <div class="tbl_frm01 tbl_wrap">
    <table>
      <tbody>
        <tr>
          <th scope="row">공시 제목<strong class="sound_only">필수</strong></label></th>
          <td>
            <input type="text" name="wr_subject" required class="required frm_input" size="70" maxlength="1000">
          </td>
        </tr>
        <tr>
          <th scope="row">작성자<strong class="sound_only">필수</strong></label></th>
          <td>
            <input type="text" name="wr_name" required class="required frm_input" size="70" maxlength="1000">
          </td>
        </tr>
        <tr>
          <th scope="row">등록 날짜<strong class="sound_only">필수</strong></label></th>
          <td>
            <input type="date" name="wr_datetime" required="" class="frm_input required hasDatepicker">
          </td>
        </tr>
        <tr>
          <th scope="row">내용<strong class="sound_only">필수</strong></label></th>
          <td>
            <textarea name="wr_content" required class="required frm_input" cols="70" rows="10"></textarea>
          </td>
        </tr>
        <tr>
          <th scope="row">첨부파일</label></th>
          <td>
            <input type="file" name="file[]" title="파일첨부 1 : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능">
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="middle_fix">
    <input type="submit" name="submit" value="등록 하기" class="btn_submit btn">
  </div>
</form>

<script>

</script>

<?php
require_once './admin.tail.php';
