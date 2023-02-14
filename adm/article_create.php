<?php
$sub_menu = "200820";
require_once './_common.php';

$g5['title'] = '기사 작성';
require_once './admin.head.php';

if ($_POST['submit']) {
  $table_name = 'g5_write_free';

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
  $wr['wr_1'] = $_POST['wr_datetime'];

  $wr_sql = "INSERT INTO {$table_name} SET ";
  foreach ($wr as $key => $value)
    $wr_sql .= $key . " = '" . $value . "', ";
  $wr_sql = substr($wr_sql, 0, -2);

  $wr_result = sql_query($wr_sql);

  $count = array();
  for ($i = 1; $i < count($_FILES['upload_file']['name']); $i++)
    if ($_FILES['upload_file']['name'][$i] != '')
      $count[] = $i;

  if (count($count) > 0) {
    $upload_file_result = true;

    $wr_id_sql = "SELECT wr_id FROM {$table_name} ORDER BY wr_id DESC LIMIT 1";
    $wr_id_result = sql_fetch($wr_id_sql);
    $wr_id = $wr_id_result['wr_id'];

    foreach ($count as $i) {
      $upload_file = array(
        'up_name_origin' => '',
        'up_name_save' => '',
        'up_path' => '',
        'up_created_at' => '',
        'wr_table' => '',
        'wr_id' => 0,
      );

      $ext = substr(strrchr($_FILES['upload_file']['name'][$i], '.'), 1);
      $code_name = md5(uniqid(rand(), true));

      $upload_file['up_name_origin'] = $_FILES['upload_file']['name'][$i];
      $upload_file['up_name_save'] = $code_name;
      $upload_file['up_path'] = G5_DATA_PATH . '/upload_file/' . $code_name . '.' . $ext;
      $upload_file['up_created_at'] = date('Y-m-d H:i:s');
      $upload_file['wr_table'] = $table_name;
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
    alert('등록되었습니다.', './article_create.php');
  } else {
    alert('등록에 실패했습니다.');
  }
}
?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<form action="" method="post" enctype="multipart/form-data">
  <div class="article_container" style="width: 100%; height: 100%; display: flex;">
    <div class="tbl_frm01 tbl_wrap" style="width: 70%; border-right: 1px solid #ddd;">
      <table>
        <tbody>
          <tr sty>
            <th scope="row">미디어 선택<strong class="sound_only">필수</strong></label>
              <button type="button" class="btn btn_03" onclick="add_media()" style="display: right;">추가</button>
            </th>
            <td>


            </td>
          </tr>
          <tr>
            <th scope="row">제목<strong class="sound_only">필수</strong></label></th>
            <td>
              <input type="text" name="wr_subject" required class="required frm_input" size="70" maxlength="1000" placeholder="기사 제목을 입력하세요">
            </td>
          </tr>
          <tr>
            <th scope="row">부제목</label></th>
            <td>
              <input type="text" name="ㅁㄴㅇ" class="frm_input" size="70" maxlength="1000">
            </td>
          </tr>
          <tr>
            <th scope="row">등록 날짜<strong class="sound_only">필수</strong></label></th>
            <td>
              <input type="date" name="wr_datetime" required class="frm_input required hasDatepicker">
            </td>
          </tr>
          <tr>
            <th scope="row">기사 링크 (URL)</th>
            <td>
              <input type="text" name="ㅁㄴㅇ" class="frm_input" size="70" maxlength="1000">
            </td>
          </tr>
          <tr>
            <th scope="row">내용<strong class="sound_only">필수</strong></label></th>
            <td>
              <textarea name="wr_content" id="summernote" required="required" class="required frm_input"></textarea>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="file_container" style="width: 30%;">
      <table class="tbl_frm01 tbl_wrap">
        <thead>
          <th scope="row" style="width: 100%;">
            <label for="upload_file" style=" text-align: center; margin: 0 auto;">파일 업로드</label>
            <button type="button" class="btn btn_03" onclick="add_file()" style="float: right;">추가</button>
            <input type="file" name="upload_file[]" onchange="file_change(this)" style="display: none;">
          </th>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  <div class="middle_fix">
    <input type="submit" name="submit" value="등록 하기" class="btn_submit btn">
  </div>
</form>

<script>
  $(document).ready(function() {
    $('#summernote').summernote({
      height: 300,
      minHeight: null,
      maxHeight: null,
      lang: 'ko-KR',
      placeholder: '내용을 입력하세요',
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link'], ],
      ],
    });
  });

  function add_file() {
    if ($('.file_container tbody tr').length >= 5) {
      alert('파일은 5개까지만 첨부 가능합니다.');
      return;
    }

    $('.file_container thead input').click();
  }

  function file_change(obj) {
    if (obj.files.length == 0)
      return;

    const clone = obj.cloneNode(true);
    clone.files = obj.files;

    const html = `
    <tr>
      <td>
        <img src="" alt="" style="width: 100px; height: 100px;">
        <div style="float: right; line-height: 100px;">
          <button type="button" class="btn btn_01" onclick="delete_file(this)">X</button>
        </div>
      </td>
    </tr>
  `;

    $('.file_container tbody').append(html);
    $('.file_container tbody tr:last-child').append(clone);

    const file = obj.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
      $('.file_container tbody tr:last-child img').attr('src', e.target.result);
    }

    reader.readAsDataURL(file);
  }

  function delete_file(obj) {
    $(obj).parent().parent().parent().remove();
  }
</script>

<?php
require_once './admin.tail.php';
