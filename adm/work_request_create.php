<?php
$sub_menu = "200900";
require_once './_common.php';

$g5['title'] = '업무 요청 작성';
require_once './admin.head.php';

if ($_POST['submit']) {
  $table_name = 'g5_write_qa';

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
  $wr['wr_datetime'] = date('Y-m-d H:i:s');

  $wr_sql = "INSERT INTO {$table_name} SET ";
  foreach ($wr as $key => $value)
    $wr_sql .= $key . " = '" . $value . "', ";
  $wr_sql = substr($wr_sql, 0, -2);

  $wr_result = sql_query($wr_sql);

  if ($wr_result) {
    alert('등록되었습니다.', './work_request.php');
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
  <div class="tbl_frm01 tbl_wrap">
    <table>
      <tbody>
        <tr>
          <th scope="row">제목<strong class="sound_only">필수</strong></label></th>
          <td>
            <input type="text" name="wr_subject" required class="required frm_input" size="70" maxlength="1000" placeholder="제목을 입력하세요">
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
</script>

<?php
require_once './admin.tail.php';
