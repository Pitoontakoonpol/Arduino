<?php

include("admin/php-config.conf");
include("admin/php-db-config.conf");
$opr=$_GET['opr'];
$fbid=$_GET['fbid'];
echo ">>".$opr;
if ($opr=="select_id_fb") {
    $sql="SELECT ID,Name FROM member WHERE FBID='$fbid' LIMIT 1";
    $result=$conn_db->query($sql);
    $list=$result->fetch_row();
    $MemberID=$list[0];
    $Name=$list[1];

    if ($MemberID) {
        echo "Return_MemberDesc:".$MemberID."__19__".$Name;
    }
} elseif ($opr=='fb_register') {
    $fb_msg_header=$_GET['fb_msg_header'];
    $fb_msg=$_GET['fb_msg'];

    $fmh_Split=EXPLODE("__19__", $fb_msg_header);
    $fm_Split=EXPLODE("__19__", $fb_msg);

    for ($i=0;$i<=COUNT($fmh_Split);$i++) {
        $header=$fmh_Split[$i];
        $value=$fm_Split[$i];
        $$header=$value;
        if ($value=='undefined') {
            $value=0;
        }
        if ($header) {
            $table.=$header.",";
            $table_value.="'".$value."',";
        }
    }
    $sql="INSERT INTO member (".substr($table, 0, -1).",Created) VALUES (".substr($table_value, 0, -1).",".date('U').")";
    $result=$conn_db->query($sql);
    $return_code=$conn_db->errno;
    echo "rStatus:".$return_code;
}
