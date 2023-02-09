<?php
$sub_menu = "200100";
require_once './_common.php';

$g5['title'] = '공시 작성';
require_once './admin.head.php';
?>

<form name="fmember" id="fmember" action="./disclosure_create.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
  <div class="tbl_frm01 tbl_wrap">
    <table>
      <tbody>
        <tr>
          <th scope="row"><label for="wr_subject">공시 제목<strong class="sound_only">필수</strong></label></th>
          <td>
            <input type="text" name="wr_subject" id="wr_subject" required class="required frm_input" size="70" maxlength="1000">
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="wr_name">작성자<strong class="sound_only">필수</strong></label></th>
          <td>
            <input type="text" name="wr_name" id="wr_name" required class="required frm_input" size="70" maxlength="1000">
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="wr_datetime">등록 날짜<strong class="sound_only">필수</strong></label></th>
          <td>
            <input type="text" name="wr_datetime" value="2023-02-09" id="wr_datetime" required="" class="frm_input required hasDatepicker">
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="wr_content">내용<strong class="sound_only">필수</strong></label></th>
          <td>
            <textarea name="wr_content" id="wr_content" required class="required frm_input" cols="70" rows="10"></textarea>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="middle_fix">
    <input type="submit" value="확인" class="btn_submit btn">
  </div>
</form>

<script>

</script>

<?php
require_once './admin.tail.php';
