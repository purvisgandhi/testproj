<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Venops - subadmin </title>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../admin/style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="../admin/script/validate.js"></script>
<script language="javascript" type="text/javascript" src="../admin/script/check.js"></script>
<script language="javascript" type="text/javascript" src="../admin/script/common.js"></script>
<script language="javascript" type="text/javascript" src="../admin/script/search-menu.js"></script>
</head>
<script language="javascript" type="text/javascript" src="../admin/script/validate.js"></script>
<script language="javascript" type="text/javascript">

    function valid_form(ObjFrm)
    {
        if(!isBlank(ObjFrm.UserName,trimAll(ObjFrm.UserName.value),"Username")) return false;
        if(!isBlank(ObjFrm.Password,trimAll(ObjFrm.Password.value),"Password")) return false;

        return true;
    }
    function MM_openBrWindow(theURL,winName,features) { //v2.0
        window.open(theURL,winName,features);
    }
    </script>
    <body>

	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="maintable" style="background-color:#fff;" >
            <tr>
              <td align="center" valign="middle" style="background:url(../admin/images/bg-loginscreen.jpg) no-repeat center; padding-top:120px;">
                <form  id="frmlogin" name="frmlogin" action="do_login.php" method="post">
                    <table align="center" width="50%" border="0" cellspacing="1" cellpadding="3" style="color:#000;">
                        <tr>
                            <td valign="top" align="center" colspan="2">&nbsp;</td>
                        </tr>
                        <?php if (isset($_GET['login'])&& $_GET['login'] == 'fail'){?>
                        <tr>
                            <td valign="top" align="center" colspan="2">
                                <span id="lblmsg" class="error"><?php echo 'Username or password does not match.';?></span>
                            </td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td width="36%" align="right" > <b>Username:</b></td>
                            <td width="64%">
                            <input name="UserName" type="text"  id="txtusername" maxlength="100" />
                        </td>
                        </tr>
                        <tr>
                          <td align="right"> <b>Password:</b> </td>
                          <td><input name="Password" type="password"  id="txtpassword" maxlength="50" /></td>
                        </tr>
                        <tr>
                            <td align="right">&nbsp;</td>
                            <td valign="middle">
                                <input type="image" class="noborder" src="../admin/images/btn-submit.jpg" align="middle" id="iBtnLogin" name="iBtnLogin" onClick="return valid_form(document.frmlogin);" /></b></a>
                            </td>
                        </tr>
                            <!--<tr>-->
                            <!--  <td> </td>-->
                            <!--  <td><input name="chkRemember" type="checkbox" class="noborder" id="chkRemember" value="1" {if $Username!=""} checked="checked"{/if}/>Remember my Username</td>-->
                            <!--</tr>-->
                        </table>
                    </form>
                </td>
            </tr>
        </table>

	</body>
</html>