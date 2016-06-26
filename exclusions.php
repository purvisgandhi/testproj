
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
</head><?php
include_once('common.php');

checklogin();
$where = array();$wherecond = '';
$start = 0;$limit = 100;
if(isset($_POST['search']))
{
    //print_r($_POST);
    if(isset($_POST['LASTNAME']) && $_POST['LASTNAME'] != ''){
        $where[] = 'LASTNAME LIKE "%'.$_POST['LASTNAME'].'%"';
    }if(isset($_POST['FIRSTNAME']) && $_POST['FIRSTNAME'] != ''){
        $where[] = 'FIRSTNAME LIKE "%'.$_POST['FIRSTNAME'].'%"';
    }if(isset($_POST['MIDNAME']) && $_POST['MIDNAME'] != ''){
        $where[] = 'MIDNAME LIKE "%'.$_POST['MIDNAME'].'%"';
    }if(isset($_POST['BUSNAME']) && $_POST['BUSNAME'] != ''){
        $where[] = 'BUSNAME LIKE "%'.$_POST['BUSNAME'].'%"';
    }if(isset($_POST['DBANAME']) && $_POST['DBANAME'] != ''){
        $where[] = 'DBA_NAME LIKE "%'.$_POST['DBANAME'].'%"';
    }if(isset($_POST['CITY']) && $_POST['CITY'] != ''){
        $where[] = 'CITY LIKE "%'.$_POST['CITY'].'%"';
    }if(isset($_POST['STATE']) && $_POST['STATE'] != ''){
        $where[] = 'STATE LIKE "%'.$_POST['STATE'].'%"';
    }if(isset($_POST['ZIP']) && $_POST['ZIP'] != ''){
        $where[] = 'ZIP LIKE "%'.$_POST['ZIP'].'%"';
    }
    if(!empty($where)){
        $_SESSION['condtion'] = $wherecond = 'WHERE '.implode(' AND ', $where);
    }
}

if(isset($_GET['page']) && $_GET['page'] != '' && $_SESSION['condtion'] != '')
{
    $wherecond =  $_SESSION['condtion'];
}//echo $_SESSION['condtion']. ' test ::'.$wherecond;

if(isset($_REQUEST['limit'])) $limit = $_REQUEST['limit'];
    $page = 1;

if(isset($_GET['page']))
{
  $page=$_GET['page'];
  $start=($page-1)*$limit;
}

$sql = "SELECT * FROM `Exclusions` $wherecond ORDER BY ID DESC LIMIT $start, $limit";
$rs = mysql_query($sql);//echo $sql
$count = mysql_num_rows($rs);

$rows = mysql_num_rows(mysql_query("SELECT * FROM `Exclusions` $wherecond"));
$total = ceil($rows/$limit);
?>
<body>

<form id="frmindexvendor" name="frmindexvendor" method="post" action="exclusions.php">
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="maintable">

    <tr>
	<td valign="top" align="center">
	    <table width="100%" border="0" cellpadding="0" cellspacing="0">
	    <tr>
	    <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
			<td class="toppart">
			    <table width="100%" border="0" cellpadding="0" cellspacing="0">
			    <tr>
			      <td valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				    <td width="20"><img src="../img/logo.png" alt="logo" title="site logo" hspace="30" width="150"/></td>
				    <td align="left" valign="top" ><table  border="0" align="right" cellpadding="0" cellspacing="0">
					<tr style="background:url(../admin/images/toplink-bg.gif)">
					  <td width="11" valign="top"><img src="images/toplink-left.gif" alt="" height="32" /></td>
					  <td class="tophlink1"><a href="exclusions.php"  style="color:#000;" ><img src="../admin/images/top-arrow.gif" alt="Logout" hspace="3" vspace="0" title="Logout" border="0" />&nbsp;&nbsp;Home</a></td>

					  <td width="10px" align="center" valign="middle" style="background:url(../admin/images/tab-silver-center1.gif);"><img src="../admin/images/toplink-line.gif" alt="" height="30" /></td>
					  <td class="tophlink1"><a href="logout.php"  style="color:#000;" ><img src="../admin/images/top-arrow.gif" alt="Logout" hspace="3" vspace="0" title="Logout" border="0" />&nbsp;&nbsp;Logout</a> </td>
					</tr>
				      </table></td>
				  </tr>
				</table></td>
			    </tr>
			    </table>
			</td>
		    </tr>
		    <tr>
			<td class="midpart">
			<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" >
			<tr>
			    <td class="bottomborder"><h1>Exclusion List </h1></td>
			<!--    <td align="right" valign="bottom" class="bottomborder">-->
			<!--	<table border="0" cellpadding="2" cellspacing="0">-->
			<!--	    <tr>-->
			<!--		<td valign="bottom"><img src="../admin/images/ico-serach.gif" title="Search" alt="Search" /> &nbsp;<A href="javascript:ChangeIMG(0);" title="Search" >Search</a></td>-->
			<!--	    </tr>-->
			<!--	</table>-->
			<!--    </td>-->
			</tr>
			<tr>
			    <td align="center" valign="top" colspan="2">
				<table cellspacing="0" cellpadding="2" width="100%" align="center" border="0">
				    <tr>
					<td>
					    <DIV id=div0 style="DISPLAY: block;">
						<table width="50%" border="0" cellspacing="0" cellpadding="0">
						    <tr>
							<td>
							    <fieldset class="tableborder whitebg">
								<legend class="midheader"> <img src="../admin/images/ico-serach.gif" alt="Search Assets" hspace="2" align="absmiddle" title="Search Assets" />&nbsp;Search detail</legend>
								<table align="center" border="0" cellpadding="2" cellspacing="3" width="100%">
								    <tr>
									<td colspan="3" align="left"><font class="error">NOTE:</font> Enter few letters for any or all criteria</td>
									<td colspan="3" align="right"><img src="../admin/images/icon-close.gif" onClick="ShowContents('0','');" style="cursor: pointer;" title="Close" alt="Close" /></td>
								    </tr>
								    <tr>
									<td width="50%" align="right">
									    <b>LAST NAME</b>													</td>
									    <td align="right"> <input type="text" name="LASTNAME" id="LASTNAME" class="form-control input-sm">
									    </td>

									<td width="50%" align="right">
									    <b>FIRST NAME</b>													</td>
									    <td align="right">
										<input type="text" name="FIRSTNAME" id="FIRSTNAME" class="form-control input-sm">
									    </td>
								    </tr>
								    <tr>
									<td width="50%" align="right">
									    <b>MID NAME</b>													</td>
									    <td align="right"> <input type="text" name="MIDNAME" id="MIDNAME" class="form-control input-sm">
									    </td>

									<td width="50%" align="right">
									    <b>BUSINESS NAME</b>													</td>
									    <td align="right">
										<input type="text" name="BUSNAME" id="BUSNAME" class="form-control input-sm">
									    </td>
								    </tr>
								    <tr>
									<td width="50%" align="right">
									    <b>DBA NAME</b>													</td>
									    <td align="right"> <input type="text" name="DBANAME" id="DBANAME" class="form-control input-sm">
									    </td>

									<td width="50%" align="right">
									    <b>CITY</b>													</td>
									    <td align="right">
										<input type="text" name="CITY" id="CITY" class="form-control input-sm">
									    </td>
								    </tr>
								    <tr>
									<td width="50%" align="right">
									    <b>STATE</b>													</td>
									    <td align="right"> <input type="text" name="STATE" id="STATE" class="form-control input-sm">
									    </td>

									<td width="50%" align="right">
									    <b>ZIP</b>													</td>
									    <td align="right">
										<input type="text" name="ZIP" id="ZIP" class="form-control input-sm">
									    </td>
								    </tr>
								    <tr>
									<td colspan="2" align="center">
									    <input name="search" value="Search" id="Search" class="button" type="submit" />
									      &nbsp;

									</td>
								    </tr>
								</table>
							    </fieldset>															</td>
							</tr>
						    </table>
						</DIV>												</td>
					    </tr>
					    <tr>
						<td valign="top" align="center"></td>
					    </tr>
					    <tr>
						<td>
						    <table class="allborder" width="100%" cellpadding="2" cellspacing="1">
							<thead>
							    <tr>
								<th class="fieldheader" width='1%'>#</th>
								<th class="fieldheader">LAST NAME</th>
								<th class="fieldheader">FIRST NAME</th>
								<th class="fieldheader">MIDDLE NAME</th>
								<th class="fieldheader">BUSINESS NAME</th>
								<th class="fieldheader">DBA NAME</th>
								<th class="fieldheader">CITY</th>
								<th class="fieldheader">STATE</th>
								<th class="fieldheader">ZIP</th>
							    </tr>
							</thead>
							<tbody>
							    <?php if(mysql_num_rows($rs) > 0){ //echo '<pre>';print_r($searchedRows);
								if($page > 1 )$j=(($page)*$limit)+1;else $j =1;
								 while($rows = mysql_fetch_assoc($rs)) {?>
								    <tr>
									<td class="whitebg"><?php echo $j;?></td>
									<td class="whitebg"><?php echo $rows['LASTNAME'];?></td>
									<td class="whitebg"><?php echo $rows['FIRSTNAME'];?></td>
									<td class="whitebg"><?php echo $rows['MIDNAME'];?></td>
									<td class="whitebg"><?php echo $rows['BUSNAME'];?></td>
									<td class="whitebg"><?php echo $rows['DBA_NAME'];?></td>
									<td class="whitebg"><?php echo $rows['CITY'];?></td>
									<td class="whitebg"><?php echo $rows['STATE'];?></td>
									<td class="whitebg"><?php echo $rows['ZIP'];?></td>
								    </tr><?php $j++;
								}?>
								<tr>
								    <td style="" align="center" valign="middle" colspan="9" class="whitebg">&nbsp;</td>
								</tr>
								<?php
								if($total > 1){echo '<tr ><td colspan="9" style="padding:0px;" class="whitebg"><b>Page:</b> ';
								  for($i=1;$i<=$total;$i++){
								    if($i==$page) { echo "<span style='font-weight:bold;'>".$i."  </span>"; }
								    else { echo "<a style='font-weight:normal;' href='?page=".$i."'>".$i."</a>  "; }
								  }
								  echo '</td></td></tr>';
								}?><?php
							    }else{?>
							    <tr>
								<td style="" align="center" valign="middle" colspan="9" class="whitebg">&nbsp;</td>
							    </tr>
							    <tr>
								<td align="center" valign="middle" colspan="9" class="whitebg"><b>No Record Found!</b></td>
							    </tr><?php
							    }?>
							</tbody>
						    </table>
						</td>
					    </tr>
					</table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" colspan="2"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
	</tr>
	<tr>
	    <td>
	     <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="footerpart">
		<tr>
		   <td style="padding-left:10px;" align="center"> &copy; Copyright <?php echo date('Y');?> Venops Inc. &trade; All rights reserved.</td>
	       </tr>
	    </table>
	     </td>
	</tr>
    </table>
</td>
    </tr></table>

</form>
</body>
</html>