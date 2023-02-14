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

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

// 메뉴테이블 생성
if (!isset($g5['menu_table'])) {
    die('<meta charset="utf-8">dbconfig.php 파일에 <strong>$g5[\'menu_table\'] = G5_TABLE_PREFIX.\'menu\';</strong> 를 추가해 주세요.');
}

if (!sql_query(" DESCRIBE {$g5['menu_table']} ", false)) {
    sql_query(
        " CREATE TABLE IF NOT EXISTS `{$g5['menu_table']}` (
                  `me_id` int(11) NOT NULL AUTO_INCREMENT,
                  `me_code` varchar(255) NOT NULL DEFAULT '',
                  `me_name` varchar(255) NOT NULL DEFAULT '',
                  `me_link` varchar(255) NOT NULL DEFAULT '',
                  `me_target` varchar(255) NOT NULL DEFAULT '0',
                  `me_order` int(11) NOT NULL DEFAULT '0',
                  `me_use` tinyint(4) NOT NULL DEFAULT '0',
                  `me_mobile_use` tinyint(4) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`me_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ",
        true
    );
}

// $sql = " select * from (select * from {$g5['board_table']} A LEFT JOIN g5_group B ON A.me_name = B.gr_id) C LEFT JOIN g5_sections D ON C.gr_1 = D.se_no order by me_id ";

$sql = " select * from {$g5['board_table']} A LEFT JOIN g5_sections B ON A.bo_1 = B.se_id order by bo_3";

$result = sql_query($sql);

// $g5['title'] = "메뉴설정";
$g5['title'] = '섹션 설정';
require_once './admin.head.php';

$colspan = 7;
$sub_menu_info = '';
?>

<div class="local_desc01 local_desc">
    <p><strong>주의!</strong> 메뉴설정 작업 후 반드시 <strong>확인</strong>을 누르셔야 저장됩니다.</p>
</div>

<form name="fmenulist" id="fmenulist" method="post" action="./menu_list_update.php" onsubmit="return fmenulist_submit(this);">
    <input type="hidden" name="token" value="">
    <div id="menulist" class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
                <tr>
                    <th scope="col">순서</th>
                    <th scope="col">관리</th>
                    <th scope="col">섹션타입</th>
                    <th scope="col">섹션명</th>
                    <th scope="col">링크</th>
                    <!-- <th scope="col">새창</th> -->
                    <!-- <th scope="col">PC사용</th>
                    <th scope="col">모바일사용</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    $bg = 'bg' . ($i % 2);
                    $sub_menu_class = '';
                    if (strlen($row['me_code']) == 4) {
                        $sub_menu_class = ' sub_menu_class';
                        $sub_menu_info = '<span class="sound_only">' . $row['me_name'] . '의 서브</span>';
                        $sub_menu_ico = '<span class="sub_menu_ico"></span>';
                    }

                    // $s_mov = '<button id="move_'.$row['gr_id'].'" class="btn btn_01">이동</button>';
                    $s_upd = "<button id='modify_".($row['se_no'])."' class='btn btn_03 btn-modal'>수정</button>";
                    $s_del = "<button id='delete_".($row['se_no'])."' class='btn btn_02 btn-modal'>삭제</button>";

                    $search  = array('"', "'");
                    $replace = array('&#034;', '&#039;');
                    $me_name = str_replace($search, $replace, $row['me_name']);
                ?>
                    <tr class="<?php echo $bg; ?> menu_list menu_group_<?php echo substr($row['me_code'], 0, 2); ?>">
                        <td class="td_num">
                            <label for="me_order_<?php echo $i; ?>" class="sound_only">순서</label>
                            <input type="text" name="me_order[]" value="<?php echo $row['bo_3']; ?>" id="me_order_<?php echo $row['se_no']; ?>" class="tbl_input" size="5">
                        </td>
                        <td class="td_mng">
                            <!-- <?php echo $s_mov ?> -->
                            <?php echo $s_upd ?><?php echo $s_del ?>
                        </td>
                        <!-- <td> -->
                            <!-- <label for="me_order_<?php echo $i; ?>" class="sound_only">순서</label> -->
                            <!-- <input type="text" name="me_table[]" value="<?php echo $row['bo_table'] ?>" id="me_table_<?php echo $i; ?>" class="required tbl_input full_input" size="5"> -->
                            <!-- <?php echo $row['bo_table']; ?>
                        </td> -->
                            <!-- <?php if (strlen($row['me_code']) == 2) { ?>
                                <button type="button" class="btn_add_submenu btn_03 ">추가</button>
                                <?php } ?>
                                <button type="button" class="btn_del_menu btn_02">삭제</button>
                            </td> -->
                        <td class="se_id_<?php echo ($i + 1) ?>">
                            <?php echo $row['se_id'] ?>
                        </td>
                        <td id="td_category_<?php echo $row['se_no']; ?>" class="td_category">
                            <!-- <input type="hidden" name="code[]" value="<?php echo substr($row['me_code'], 0, 2) ?>">
                            <label for="me_name_<?php echo $i; ?>" class="sound_only"><?php echo $sub_menu_info; ?> 메뉴<strong class="sound_only"> 필수</strong></label>
                            <input type="text" name="me_name[]" value="<?php echo get_sanitize_input($me_name); ?>" id="me_name_<?php echo $i; ?>" required class="required tbl_input full_input">-->
                            <?php echo $row['bo_table'] ?>
                        </td>
                        <td>
                            <?php echo $row['bo_2']?>
                            <!-- <label for="me_link_<?php echo $i; ?>" class="sound_only">링크<strong class="sound_only"> 필수</strong></label>
                            <input type="text" name="me_link[]" value="<?php echo $row['me_link'] ?>" id="me_link_<?php echo $i; ?>" required class="required tbl_input full_input"> -->
                        </td>
                        
                        <!-- <td class="td_mng">
                            <label for="me_target_<?php echo $i; ?>" class="sound_only">새창</label>
                            <select name="me_target[]" id="me_target_<?php echo $i; ?>">
                                <option value="self" <?php echo get_selected($row['me_target'], 'self', true); ?>>사용안함</option>
                                <option value="blank" <?php echo get_selected($row['me_target'], 'blank', true); ?>>사용함</option>
                            </select>
                        </td>
                        <td class="td_mng">
                            <label for="me_use_<?php echo $i; ?>" class="sound_only">PC사용</label>
                            <select name="me_use[]" id="me_use_<?php echo $i; ?>">
                                <option value="1" <?php echo get_selected($row['me_use'], '1', true); ?>>사용함</option>
                                <option value="0" <?php echo get_selected($row['me_use'], '0', true); ?>>사용안함</option>
                            </select>
                        </td>
                        <td class="td_mng">
                            <label for="me_mobile_use_<?php echo $i; ?>" class="sound_only">모바일사용</label>
                            <select name="me_mobile_use[]" id="me_mobile_use_<?php echo $i; ?>">
                                <option value="1" <?php echo get_selected($row['me_mobile_use'], '1', true); ?>>사용함</option>
                                <option value="0" <?php echo get_selected($row['me_mobile_use'], '0', true); ?>>사용안함</option>
                            </select>
                        </td> -->
                    </tr>
                <?php
                }

                if ($i == 0) {
                    echo '<tr id="empty_menu_list"><td colspan="' . $colspan . '" class="empty_table">자료가 없습니다.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
        <button type="button" onclick="return add_menu();" class="btn btn_02">메뉴추가<span class="sound_only"> 새창</span></button>
        <input id="finish_1" type="submit" name="act_button" value="확인" class="btn_submit btn btn-modal">
        <button id="create_" class="btn btn_01 btn-modal">섹션 추가하기</button>
    </div>

</form>

<!--                      ////////////////////////////////////////////                      -->
<?php
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

// if ($stx) {
//     $sql_search .= " and ( ";
//     switch ($sfl) {
//         case "gr_id":
//         case "gr_admin":
//             $sql_search .= " ({$sfl} = '{$stx}') ";
//             break;
//         default:
//             $sql_search .= " ({$sfl} like '%{$stx}%') ";
//             break;
//     }
//     $sql_search .= " ) ";
// }

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



$selectBoxSql = "select * from g5_sections";

$selectBoxResult = sql_query($selectBoxSql);

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



<!-- <div class="local_desc01 local_desc">
    <p>
        접근사용 옵션을 설정하시면 관리자가 지정한 회원만 해당 그룹에 접근할 수 있습니다.<br>
        접근사용 옵션은 해당 그룹에 속한 모든 게시판에 적용됩니다.
    </p>
</div> -->

<div id="modal" class="modal-overlay">
    <div class="modal-window">
        <div class="title">
            <h2 class="modal_title">섹션 수정</h2>
        </div>
        <div class="close-area">X</div>
        <div class="content">
            <table>
                <!-- <th></th>
                <th></th> -->
                <tr>
                    <td>
                        <p>섹션명</p>
                    </td>
                    <td>
                        <input id="modal_user_name" class="modal_user_name">
                    </td>
                    
                </tr>
                <tr>
                    <td>
                        <p>섹션타입</p>
                    </td>
                    <td class="auth_field">
                        <select name="auth_selectBox" id="auth_selectBox">
                            <!-- php 쿼리 후 반복문 돌려서 타입들 셀렉트 박스 만들기 -->
                            <?php for ($i = 0; $row = sql_fetch_array($selectBoxResult); $i++) { ?>
                                <option value="<?php echo $row['se_no'] ?>"><?php echo $row['se_id'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>하위섹션</p>
                    </td>
                    <td>
                        <!-- <table id="tblLocations" cellpadding="0" cellspacing="0" border="1">
                            <tr>
                                <th>ID </th>
                                <th>Location</th>
                                <th>Preference</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td> Goa</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Mahabaleshwar</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Kerala</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Kashmir</td>
                                <td>4</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Ooty</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Simla</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Manali</td>
                                <td>7</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Darjeeling</td>
                                <td>8</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Nanital</td>
                                <td>9</td>
                            </tr>
                        </table> -->
                        <table id= "under_section_table" class="under_section_table">
                            <!-- <tr class="under_section_${btnIndex}">
                                <td width="30">1</td>
                                <td width="50">
                                    <input class="under_input_1">
                                </td>
                                <td>
                                    <a href="javascript:underMove()" class="under_section_move_1 btn btn_01">
                                        이동
                                    </a>
                                    <a href="javascript:underDelete()" class="under_section_delete_1 btn btn_03">
                                        삭제
                                    </a>
                                    <a href="javascript:add_under_section()" class="under_section_add_1 btn btn_02">
                                        추가
                                    </a>
                                </td>
                            </tr> -->
                            <!-- <tr> 
                                <td width="10%">1</td>
                                <td width="40%"> <input class=""> </td>
                                <td width="30%">2</td>
                            </tr> -->
                        </table>
                    </td>
                </tr>
            </table>
            <div class="middle_fix">
                <!-- <button class="">닫기</button> -->
                <!-- <a href="./member_form.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $row['mb_id'] . '" class="btn btn_03 submit_btn">수정</a> -->
                <a href="javascript:modify();" class="btn btn_03 submit_btn">추가</a>
            </div>
        </div>
    </div>
</div>

<div id="modal_" class="modal-overlay_">
    <div class="modal-window">
        <div class="title">
            <h2 class="delete_modal_title">섹션 삭제</h2>
        </div>
        <div class="close-area_">X</div>
        <div class="content">
            <div>
                <h2>해당 섹션을 삭제하시겠습니까?<h2>
            </div>

            <div class="evenly_fix">
                <a href="javascript:delete_();" class="btn btn_03 submit_btn_">삭제</a>
                <a href="javascript:modal_Off();" class="btn btn_03">취소</a>
            </div>
        </div>
    </div>
</div>

<?php
$pagelist = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');
echo $pagelist;
?>

<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js"></script> -->
<!-- <script>
    $(function () {
        $("#tblLocations").sortable({
            items: 'tr:not(tr:first-child)',
            cursor: 'pointer',
            axis: 'y',
            dropOnEmpty: false,
            start: function (e, ui) {
                ui.item.addClass("selected");
            },
            stop: function (e, ui) {
                ui.item.removeClass("selected");
                $(this).find("tr").each(function (index) {
                    if (index > 0) {
                        $(this).find("td").eq(2).html(index);
                    }
                });
            }
        });
    });
</script> -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/0.9.1/jquery.tablednd.js" integrity="sha256-d3rtug+Hg1GZPB7Y/yTcRixO/wlI78+2m08tosoRn7A=" crossorigin="anonymous"></script>
<script>
    function fboardgrouplist_submit(f) {
        // if (!is_checked("chk[]")) {
        //     alert(document.pressed + " 하실 항목을 하나 이상 선택하세요.");
        //     return false;
        // }

        // if (document.pressed == "선택삭제") {
        //     if (!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
        //         return false;
        //     }
        // }

        return false;
    }
</script>

<!-- <script type="text/javascript">
$(document).ready(function() {
    // Initialise the table
    $("#sortable").tableDnD();
    $("#under_section_table").tableDnD();
}); -->



</script>

<script>
    var uppers = /^(?=.*?[A-Z])/;
    var lowers = /^(?=.*?[a-z])/;

    let id = '';
    let btnIndex = 0;
    let curGroupId = '';
    var sections = [];
    var section_names = [];
    var numbers = [];
    let original_bo_table = '';
    let bo_3 = '';


    const loremIpsum = document.getElementById("lorem-ipsum");
    fetch("https://baconipsum.com/api/?type=all-meat&paras=200&format=html")
        .then(response => response.text())
        .then(result => loremIpsum.innerHTML = result)
        const modal = document.getElementById("modal");
        const modal_ = document.getElementById("modal_");

    async function modalOn() {
        await add_under_section();
        modal.style.display = "flex"
    }
    function isModalOn() {
        return modal.style.display === "flex"
    }

    function modalOff() {
        modal.style.display = "none"
        $('.under_section_table').empty();
        btnIndex = 0;
    }

    const closeBtn = modal.querySelector(".close-area")
    closeBtn.addEventListener("click", e => {
        modalOff()
    })
    modal.addEventListener("click", e => {
        const evTarget = e.target
        if(evTarget.classList.contains("modal-overlay")) {
            modalOff()
        }
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

    async function select_section_type(group_id) {
        const result = await $.ajax({
            method: 'POST',
            url: "./boardgroup_list_select_type.php",
            data: { gr_id: group_id },
            success: function(data) {
                if (!data) {
                    alert('DB 오류입니다.');
                    return false;
                }
                return data;
            }
        });
        return JSON.parse(result);
    }

    async function create_section() {
        const bo_table = document.getElementById('modal_user_name').value;
        if (!bo_table) {
            alert('섹션 이름을 정해주세요.');
            return;
        }
        console.log('1');
        const under_sections = [];
        const table_length = document.getElementById('under_section_table').getElementsByTagName('tbody')[0].childElementCount;
        
        console.log('2');
        for (let i = 0; i < table_length; i++) {
            const curVal = document.getElementById(`under_input_${i + 1}`).value;
            if (!curVal) {
                alert('하위 섹션 이름을 정해주세요.');
                return;
            }
            under_sections.push(curVal);
        }
        const se_id = $("select[name=auth_selectBox] option:selected").text();

        const me_link = '';

        // me_link += `/board.php?bo_table=${bo_table}`;
        console.log('123');

        const result = await $.ajax({
            method: 'POST',
            url: "./boardgroup_list_create_group.php",
            data: {
                under_sections: under_sections,
                se_id: se_id,
                me_link: me_link,
                bo_table: bo_table,
            },
            success: function(data) {
                if (!data) {
                    alert('이미 존재하는 섹션입니다.');
                    return false;
                }
                return data;
            }
        });
        window.location.reload();
        modalOff();
        return result;
    }

    async function update_section() {
        const bo_table = document.getElementById('modal_user_name').value;
        console.log(bo_table);
        if (!bo_table) {
            alert('섹션 이름 정해주세요.');
            return;
        }
        const under_sections = [];
        const table_length = document.getElementById('under_section_table').getElementsByTagName('tbody')[0].childElementCount;
        
        for (let i = 0; i < table_length; i++) {
            const curVal = document.getElementById(`under_input_${i + 1}`).value;
            if (!curVal) {
                alert('하위 섹션 이름을 정해주세요.');
                return;
            }
            under_sections.push(curVal);
        }
        
        const se_id = $("select[name=auth_selectBox] option:selected").text();
        const bo_3 = document.getElementById(`me_order_${curGroupId}`).value;
        const me_link = '';

        const result = await $.ajax({
            method: 'POST',
            url: "./boardgroup_list_update_group.php",
            data: { 
                bo_table: bo_table,
                under_sections: under_sections,
                se_id: se_id,
                original_bo_table: original_bo_table,
                bo_3: bo_3,
                me_link: me_link,
            },
            success: function(data) {
                if (!data) {
                    alert('이미 존재하는 섹션입니다.');
                    return false;
                }
                return data;
            }
        })
        window.location.reload();
        modalOff();
        return result;
    }

    async function delete_section() {
        const bo_table = document.getElementById('modal_user_name').value;
        
        const result = await $.ajax({
            method: 'POST',
            url: "./boardgroup_list_update_group.php",
            data: { bo_table: bo_table },
            success: function(data) {
                if (!data) {
                    alert('DB 오류입니다.');
                    return false;
                }
                return data;
            }
        })
        window.location.reload();
        modalOff();
        return result;
    }

    async function finish() {
        const bo_table = document.getElementById('modal_user_name').value;

        const result = await $.ajax({
            method: 'POST',
            url: "./boardgroup_list_update_group.php",
            data: { 
                sections: section_names,
                numbers: numbers
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
        return result;
    }

    async function add_under_section(e) {
        btnIndex += 1;
        let newButton = "";
        newButton += `<tr class="under_section_${btnIndex}">`;
        newButton += `<td width="10%">${btnIndex}</td>`;
        newButton += `<td width="40%">
                        <input id="under_input_${btnIndex}" class="under_input_${btnIndex}">
                    </td>`;
                        
        newButton += `<td width="40%">
                        <a href="javascript:" onclick="add_under_section(this)" class="under_section_add_${btnIndex} btn btn_03">
                            추가
                        </a>
                        `;

        newButton += `<a href="javascript:" onclick="delete_under_section(this)" class="under_section_delete_${btnIndex} btn btn_02">
                            삭제
                        </a>
                    </td>
                    </tr>`;
        await $('.under_section_table').append(newButton);
    }

    

    // function move_under_section(e) {
    //     $("#sortable").tableDnD()({
    //         onDragClass: "myDragClaas"
    //     });
    // }

    async function delete_under_section(e) {
        
        var table_ = document.getElementById('under_section_table');
        var rowList_ = table_.rows;
        let length_ = rowList_.length;

        if (length_ === 1) {
            alert('하나 이상의 하위 섹션이 필요합니다.');
            return;
        }

        const splitData = ($(e).attr("class")).split(' ')[0];
        const index = Number(splitData.split('_')[3]);
        
        await $(`.under_section_${index}`).remove();
        // 다시 재정렬 필요
        // var table = $("#under_section_table");

        var table = document.getElementById('under_section_table');
        var rowList = table.rows;
        let length = rowList.length;
        
        var basic_input = [];


        for (let i = 0; i < length; i++) {
            const parseClass = (rowList[i].className).split('_')[2];
            var curInput = document.getElementById(`under_input_${parseClass}`).value;
            await basic_input.push(curInput);
        }

        const realTable = $('.under_section_table');
        await realTable.empty();

        btnIndex = 0;

        for (let i = 0; i < length; i++) {
            btnIndex += 1;

            let newButton = "";
            newButton += `<tr class="under_section_${btnIndex}">`;
            newButton += `<td width="10%">${btnIndex}</td>`;
            newButton += `<td width="40%">
                            <input id="under_input_${btnIndex}" class="under_input_${btnIndex}">
                        </td>`;
        

            newButton += `<td width="30%">
                            <a href="javascript:" onclick="add_under_section(this)" class="under_section_add_${btnIndex} btn btn_03">
                                추가
                            </a>`;
            
            newButton += `<a href="javascript:" onclick="delete_under_section(this)" class="under_section_delete_${btnIndex} btn btn_02">
                                삭제
                        </a>
                        </td>
                        </tr>`;
            await realTable.append(newButton);

            document.getElementById(`under_input_${btnIndex}`).value = basic_input[i];
        }
    }

    var btnModal = document.querySelectorAll(".btn-modal");

    btnModal.forEach(
        function(currentValue, currentIndex, listObj) {
            currentValue.addEventListener("click", async e => {
                const curBtn = (currentValue.id).split('_');
                const curBtnText = curBtn[0];
                const curBtnGroup = curBtn[1];

                const submit_btn = document.querySelector(".submit_btn");
                const modal_title = document.querySelector(".modal_title");

                $('.btn-modal').on('click', function() {
                    var thisRow = $(this).closest('tr');
                });

                

                if (!curBtnGroup) {
                    modal_title.innerHTML = '섹션 추가';
                    submit_btn.innerHTML = '추가';
                    $('#auth_selectBox').val('1').prop('selected', true);
                    document.getElementById("auth_selectBox").removeAttribute("disabled");
                    await $('.submit_btn').prop('href', 'javascript:create_section();');
                    document.getElementById("modal_user_name").value = '';
                    modalOn();
                    return;
                }

                if (curBtnText === 'finish') {
                    // section_names + numbers 만들기
                    let order = document.querySelectorAll(".tbl_input");
                    let section_ = document.querySelectorAll(".td_category");
                    for (let i = 0; i < order.length; i++) {
                        section_names.push(section_[i].innerText);
                        numbers.push(order[i].value);
                    }
                    await finish();
                    return;
                }

                

                curGroupId = curBtnGroup;

                // id = (await select_section_type(curBtnGroup))['gr_1'];
                // if (!id) return;

                const bo_name = document.getElementById(`td_category_${curBtnGroup}`).innerText;
                original_bo_table = bo_name
                document.getElementById("modal_user_name").value = bo_name;

                if (curBtnText === 'delete') {
                    await $('.submit_btn_').prop('href', 'javascript:delete_section();');
                    modal_On();
                    return;
                }

                sections = [];

                await $.ajax({
                    method: 'POST',
                    url: "./boardgroup_list_select_type.php",
                    data: { se_no: curBtnGroup, no: 'no' },
                    success: function(data) {
                        const array_data = data.split('}');
                        
                        for (let i = 0; i < array_data.length; i++) {
                            
                            if (i + 1 === array_data.length) {
                                break;
                            }
                            array_data[i] += "}";

                            sections.push((JSON.parse(array_data[i]))['me_name']);
                        }
                    }
                })

                if (curBtnText === 'modify') {
                    modal_title.innerHTML = '섹션 수정';
                    submit_btn.innerHTML = '수정';
                    $('#auth_selectBox').val(`${curBtnGroup}`).prop('selected', true);

                    // const bo_name = document.getElementById(`td_category_${curBtnGroup}`).innerText;
                    // original_bo_table = bo_name
                    // document.getElementById("modal_user_name").value = bo_name;
                    await $('.submit_btn').prop('href', 'javascript:update_section();');
                    
                    document.getElementById("auth_selectBox").disabled = "disabled";
                    $('.under_section_table').empty();
                    if (!(sections[0] === null)) {
                        for(let i = 0; i < sections.length; i++) {
                            add_under_section();
                            document.getElementById(`under_input_${i + 1}`).value = sections[i];
                        }
                    }
                }
                await modalOn();
            });
        }
    );

    function base_convert(number, frombase, tobase) {
        //  discuss at: http://phpjs.org/functions/base_convert/
        // original by: Philippe Baumann
        // improved by: Rafał Kukawski (http://blog.kukawski.pl)
        //   example 1: base_convert('A37334', 16, 2);
        //   returns 1: '101000110111001100110100'

        return parseInt(number + '', frombase | 0)
            .toString(tobase | 0);
    }
</script>

<script>
    // $(function() {
    //     $(document).on("click", ".btn_add_submenu", function() {
    //         var code = $(this).closest("tr").find("input[name='code[]']").val().substr(0, 2);
    //         add_submenu(code);
    //     });

    //     $(document).on("click", ".btn_del_menu", function() {
    //         if (!confirm("메뉴를 삭제하시겠습니까?\n메뉴 삭제후 메뉴설정의 확인 버튼을 눌러 메뉴를 저장해 주세요."))
    //             return false;

    //         var $tr = $(this).closest("tr");
    //         if ($tr.find("td.sub_menu_class").length > 0) {
    //             $tr.remove();
    //         } else {
    //             var code = $(this).closest("tr").find("input[name='code[]']").val().substr(0, 2);
    //             $("tr.menu_group_" + code).remove();
    //         }

    //         if ($("#menulist tr.menu_list").length < 1) {
    //             var list = "<tr id=\"empty_menu_list\"><td colspan=\"<?php echo $colspan; ?>\" class=\"empty_table\">자료가 없습니다.</td></tr>\n";
    //             $("#menulist table tbody").append(list);
    //         } else {
    //             $("#menulist tr.menu_list").each(function(index) {
    //                 $(this).removeClass("bg0 bg1")
    //                     .addClass("bg" + (index % 2));
    //             });
    //         }
    //     });
    // });

    // // function add_menu() {
    // //     // var max_code = base_convert(0, 10, 36);
    // //     // $("#menulist tr.menu_list").each(function() {
    // //     //     var me_code = $(this).find("input[name='code[]']").val().substr(0, 2);
    // //     //     if (max_code < me_code)
    // //     //         max_code = me_code;
    // //     // });

    // //     var url = "./menu_form.php?code=" + max_code + "&new=new";
    // //     window.open(url, "add_menu", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
    // //     return false;
    // // }

    // function add_submenu(code) {
    //     var url = "./menu_form.php?code=" + code;
    //     window.open(url, "add_menu", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
    //     return false;
    // }

    // // function base_convert(number, frombase, tobase) {
    // //     //  discuss at: http://phpjs.org/functions/base_convert/
    // //     // original by: Philippe Baumann
    // //     // improved by: Rafał Kukawski (http://blog.kukawski.pl)
    // //     //   example 1: base_convert('A37334', 16, 2);
    // //     //   returns 1: '101000110111001100110100'

    // //     return parseInt(number + '', frombase | 0)
    // //         .toString(tobase | 0);
    // // }

    function fmenulist_submit(f) {

        var me_links = document.getElementsByName('me_link[]');
        var reg = /^javascript/;

        for (i = 0; i < me_links.length; i++) {

            if (reg.test(me_links[i].value)) {

                alert('링크에 자바스크립트문을 입력할수 없습니다.');
                me_links[i].focus();
                return false;
            }
        }

        return false;
    }
</script>
<script>
    // $(function () {
    //     //-- table td drag and drop
    //     let start_index = 0;
    //     let stop_index = 0;

    //     $("#sortable").sortable({
    //         items: 'tr',
    //         start: function (event, ui) {
    //             start_index = ui.item.context.cellIndex;
    //         },
    //         stop: function (event, ui) {
    //             stop_index = ui.item.context.cellIndex;
    //             let $table = $('#sortable');
    //             let $tr = $table.find("tbody").find("tr");
    //             $.each($tr, function (index, el) {
    //                 let current_tr = $(this);
    //                 let $tds = current_tr.find("td");
    //                 let move_td = $tds.get(start_index);
    //                 let arrive_td = $tds.get(stop_index);
    //                 if (start_index > stop_index) {
    //                     arrive_td.before(move_td);
    //                 } else {
    //                     arrive_td.after(move_td);
    //                 }
    //             });
    //             // colResizable();
    //         }
    //     });
    //     $("#sortable").disableSelection();
    //     //-- table td drag and drop
    //     colResizable();
    // });

    //-- table 넓이 조절
    var colResizable = function(){
        $("table").colResizable({
            liveDrag: true,
            // minWidth: 50,
            resizeMode:'overflow'
        });
    };
</script>

<!-- <script src="jquery_drag_and_drop.js"></script> -->



<?php
require_once './admin.tail.php';
