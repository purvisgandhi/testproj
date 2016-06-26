<?php
require('common.php');
if(isset($_POST) && !empty($_POST))
{
    $uname = $_POST['UserName'];
    $pwd = base64_encode($_POST['Password']);
    //$remember = @$_POST['chkRemember'];
    if($uname != '' && $pwd != '' ){
        $search_user = "SELECT * from `tbl_muser` WHERE MUSRXvarUnm0='".$uname."' AND MUSRXvarPas0 = '".$pwd."'";
        $result = mysql_query($search_user);
        $row = mysql_fetch_array($result);
        if(!empty($row)){
            $_SESSION['uname']=$row['MUSRXvarUnm0'];
            $_SESSION['type']=$row['MUSRXvarType0'];
            header('Location: leie_user_exclusions.php');die;
        }else{
            header('Location: index.php?login=fail');die;
        }
    }
}
?>