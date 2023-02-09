<?php
$sub_menu = "200100";
require_once './_common.php';

$g5['title'] = '공시 작성';
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
  $wr['wr_datetime'] = date('Y-m-d H:i:s');
  $wr['wr_last'] = $_POST['wr_datetime'];

  $wr_sql = "INSERT INTO g5_write_notice SET ";
  foreach ($wr as $key => $value)
    $wr_sql .= $key . " = '" . $value . "', ";
  $wr_sql = substr($wr_sql, 0, -2);

  $wr_result = sql_query($wr_sql);

  $count = array();
  for ($i = 0; $i < count($_FILES['upload_file']['name']); $i++)
    if ($_FILES['upload_file']['name'][$i] != '')
      $count[] = $i;

  if (count($count) > 0) {
    $upload_file_result = true;

    $wr_id_sql = "SELECT wr_id FROM g5_write_notice ORDER BY wr_id DESC LIMIT 1";
    $wr_id_result = sql_fetch($wr_id_sql);
    $wr_id = $wr_id_result['wr_id'];

    foreach ($count as $i) {
      $upload_file = array(
        'up_name_origin' => '',
        'up_name_save' => '',
        'up_path' => '',
        'up_created_at' => '',
        'wr_id' => 0,
      );

      $ext = substr(strrchr($_FILES['upload_file']['name'][$i], '.'), 1);
      $code_name = md5(uniqid(rand(), true));

      $upload_file['up_name_origin'] = $_FILES['upload_file']['name'][$i];
      $upload_file['up_name_save'] = $code_name;
      $upload_file['up_path'] = G5_DATA_PATH . '/upload_file/' . $code_name . '.' . $ext;
      $upload_file['up_created_at'] = date('Y-m-d H:i:s');
      $upload_file['wr_id'] = $wr_id;

      $upload_file_sql = "INSERT INTO g5_upload_file SET ";
      foreach ($upload_file as $key => $value)
        $upload_file_sql .= $key . " = '" . $value . "', ";
      $upload_file_sql = substr($upload_file_sql, 0, -2);

      move_uploaded_file($_FILES['upload_file']['tmp_name'][$i], $upload_file['up_path']);

      $upload_file_result &= sql_query($upload_file_sql);
    }
  }

  if ($wr_result && ($upload_file_result || count($count) == 0)) {
    alert('등록되었습니다.', './disclosure.php');
  } else {
    alert('등록에 실패했습니다.');
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
            <textarea name="wr_content" required class="required frm_input" style="width: 100%; height: 300px;"></textarea>
          </td>
        </tr>
        <tr>
          <th scope="row">첨부파일1</th>
          <td>
            <input type="file" name="upload_file[]" id="upload_file">
          </td>
        </tr>
        <tr>
          <th scope="row">첨부파일2</th>
          <td>
            <input type="file" name="upload_file[]" id="upload_file">
          </td>
        </tr>
        <tr>
          <th scope="row">첨부파일3</th>
          <td>
            <input type="file" name="upload_file[]" id="upload_file">
          </td>
        </tr>
        <tr>
          <th scope="row">첨부파일4</th>
          <td>
            <input type="file" name="upload_file[]" id="upload_file">
          </td>
        </tr>
        <tr>
          <th scope="row">첨부파일5</th>
          <td>
            <input type="file" name="upload_file[]" id="upload_file">
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
