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
$where = array();$wherecond = '';$_SESSION['check_tmp']='';
$start = 0;$limit = 100;//print_r($_POST);
if(isset($_POST['search']))
{
    $_SESSION['condtion'] = '';
    if(isset($_POST['LASTNAME']) && $_POST['LASTNAME'] != ''){
	$_POST['LASTNAME'] = mysql_real_escape_string($_POST['LASTNAME']);
        $wherecond= '(e.LASTNAME LIKE "%'.$_POST['LASTNAME'].'%" OR e.FIRSTNAME LIKE "%'.$_POST['LASTNAME'].'%" OR
		 e.MIDNAME LIKE "%'.$_POST['LASTNAME'].'%" OR e.BUSNAME LIKE "%'.$_POST['LASTNAME'].'%" OR
		 e.DBA_NAME LIKE "%'.$_POST['LASTNAME'].'%" OR e.CITY LIKE "%'.$_POST['LASTNAME'].'%" OR
		 e.STATE LIKE "%'.$_POST['LASTNAME'].'%" OR e.ZIP LIKE "%'.$_POST['LASTNAME'].'%" OR
		 e.INPUT_SOURCE LIKE "%'.$_POST['LASTNAME'].'%")';
    }
    if($wherecond != ''){
        $_SESSION['condtion'] = $wherecond = 'WHERE '.$wherecond;
    }
}

if(isset($_FILES["search_me"]))
{
    $filename = $_FILES["search_me"]["tmp_name"];
    if($_FILES["search_me"]["size"] > 0)
    {
	//  Include PHPExcel_IOFactory

	/** Include path **/
	set_include_path('../vendor/PHPExcel/Classes/');

	/** PHPExcel_IOFactory */
	require_once 'PHPExcel/IOFactory.php';

	$inputFileName = $_FILES['search_me']['tmp_name'];

	//  Read your Excel workbook
	try {
	    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objPHPExcel = $objReader->load($inputFileName);
	} catch(Exception $e) {
	    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}

	//  Get worksheet dimensions
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
	//  Loop through each row of the worksheet in turn
	for ($row = 1; $row <= $highestRow; $row++){
	    //  Read a row of data into an array
	    $data1 = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
					    NULL,
					    TRUE,
					    FALSE);//print_r($data1).'<br>';

	    if($data1[0][6] != '' && false === strtotime($data1[0][6]))
		$data1[0][6] = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($data1[0][6]));
	    //if(!empty($data1[0][14]))
		$data[] =  $data1[0];
	}//echo '<pre>';print_r($data);die;
	if($data){
	    $sql =  "TRUNCATE exclusions_tmp";mysql_query($sql);//empty the temp table.
	    for($i=1;$i<count($data);$i++)
	    {
		$sql = 'INSERT into exclusions_tmp(ID, LASTNAME, FIRSTNAME, MIDNAME, BUSNAME, DBA_NAME, DOB, ADDRESS,
		    ADDRESS_TWO, CITY, STATE, ZIP, ZIP_4, INPUT_SOURCE, INPUT_MONTH) VALUES ("", "'.$data[$i][1].'", "'.$data[$i][2].'", "'.$data[$i][5].'",
		    "'.$data[$i][6].'", "'.$data[$i][7].'" ,"'.$data[$i][8].'" , "'.$data[$i][9].'", "'.$data[$i][10].'", "'.$data[$i][10].'", "'.$data[$i][12].'",
		    '.$data[$i][13].', '.$data[$i][14].', "'.$data[$i][15].'", "'.$data[$i][16].'")';
		//echo $sql.'<br>';
		$rs = mysql_query($sql);
		$_SESSION['check_tmp'] = 1;
	    }
	}else{
	    $_SESSION['check_tmp'] = 0;
	}
    }
}

if(isset($_GET['page']) && $_GET['page'] != '' && isset($_SESSION['condtion']) && $_SESSION['condtion'] != '')
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

$sql = "SELECT * FROM `exclusions` as e $wherecond ORDER BY ID DESC LIMIT $start, $limit";
if($_SESSION['check_tmp'] == 1){
     $sql = "SELECT exclusions.LASTNAME, exclusions.FIRSTNAME, exclusions.MIDNAME, exclusions.BUSNAME, exclusions.DBA_NAME, exclusions.DOB, exclusions.CITY, exclusions.STATE, exclusions.ZIP, exclusions.INPUT_SOURCE FROM (SELECT * FROM `exclusions` as e $wherecond ) `exclusions`
	    INNER JOIN exclusions_tmp as tmp ON exclusions.ID = tmp.ID
	    ORDER BY exclusions.ID DESC LIMIT $start, $limit";//exclusions.FIRSTNAME = tmp.FIRSTNAME AND exclusions.LASTNAME = tmp.LASTNAME AND exclusions.BUSNAME = tmp.BUSNAME
}
/*SELECT * FROM (SELECT * FROM `exclusions` as em WHERE (em.LASTNAME LIKE "%NY%" OR em.FIRSTNAME LIKE "%NY%" OR em.MIDNAME LIKE "%NY%" OR em.BUSNAME LIKE "%NY%" OR em.DBA_NAME LIKE "%NY%" OR em.CITY LIKE "%NY%" OR em.STATE LIKE "%NY%" OR em.ZIP LIKE "%NY%" OR em.INPUT_SOURCE LIKE "%NY%")) `exclusions` INNER JOIN exclusions_tmp as tmp ON exclusions.ID = tmp.ID

SELECT * FROM `Exclusions` WHERE (LASTNAME LIKE "%JOHN%" OR FIRSTNAME LIKE "%JOHN%" OR MIDNAME LIKE "%JOHN%" OR BUSNAME LIKE "%JOHN%" OR DBA_NAME LIKE "%JOHN%" OR CITY LIKE "%JOHN%" OR STATE LIKE "%JOHN%" OR ZIP LIKE "%JOHN%" OR INPUT_SOURCE LIKE "%JOHN%") ORDER BY ID DESC LIMIT 0, 100

SELECT * FROM (SELECT * FROM `exclusions` as e WHERE (e.LASTNAME LIKE "%NY%" OR e.FIRSTNAME LIKE "%NY%" OR e.MIDNAME LIKE "%NY%" OR e.BUSNAME LIKE "%NY%" OR e.DBA_NAME LIKE "%NY%" OR e.CITY LIKE "%NY%" OR e.STATE LIKE "%NY%" OR e.ZIP LIKE "%NY%" OR e.INPUT_SOURCE LIKE "%NY%")) `exclusions` as e join exclusions_tmp as tmp on e.ID = tmp.ID WHERE e.LASTNAME = tmp.LASTNAME AND e.FIRSTNAME = tmp.FIRSTNAME AND e.MIDNAME = tmp.MIDNAME AND e.CITY = tmp.CITY
*/
$rs = mysql_query($sql);echo $sql;
$count = mysql_num_rows($rs);


if($_SESSION['check_tmp'] == 1){
    $rows = mysql_num_rows(mysql_query("SELECT * FROM (SELECT * FROM `exclusions` as e $wherecond ) `exclusions`
	    INNER JOIN exclusions_tmp as tmp ON exclusions.ID = tmp.ID"));
}else{
    $rows = mysql_num_rows(mysql_query("SELECT * FROM `exclusions` as e $wherecond"));
}
$total = ceil($rows/$limit);
?>
<body>

<form id="frmindexvendor" name="frmindexvendor" method="post" action="leie_user_exclusions.php" enctype="multipart/form-data">
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
					  <td width="11" valign="top"><img src="../admin/images/toplink-left.gif" alt="" height="32" /></td>
					  <td class="tophlink1"><a href="leie_user_exclusions.php"  style="color:#000;" ><img src="../admin/images/top-arrow.gif" alt="Logout" hspace="3" vspace="0" title="Logout" border="0" />&nbsp;&nbsp;Home</a></td>

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
			    <td align="right" valign="bottom" class="bottomborder">
				<table border="0" cellpadding="2" cellspacing="0">
				    <tr>
					<td valign="bottom"><img src="../admin/images/ico-serach.gif" title="Search" alt="Search" /> &nbsp;<A href="javascript:ChangeIMG(0);" title="Openup Searchbox" >Search</a></td>
				    </tr>
				</table>
			   </td>
			</tr>
			<tr>
			    <td align="left" valign="top" colspan="2">
				<table cellspacing="0" cellpadding="2" width="49%" align="left" border="0">
				    <tr>
					<td width="49%">
					    <DIV id=div0 style="DISPLAY: block;">
						<table width="99%" border="0" cellspacing="0" cellpadding="0">
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
									<td align="left">
									    <b>Enter Your Search</b>													</td>
									    <td align="left"> <input type="text" name="LASTNAME" id="LASTNAME" class="form-control input-sm">
									    </td>
								    </tr>
								    <tr>
									    <td align="left">
										<b>Search with File</b>													</td>
										<td align="left"> <input type="file" name="search_me" id="search_me" class="form-control input-sm" onchange="">
										</td>
								    </tr>
								    <tr>
									<td colspan="2" align="left">
									    <input name="search" value="Search" id="Search" class="button" type="submit" />
									      &nbsp;

									</td>
								    </tr>

								</table>
							    </fieldset>															</td>
							</tr>
						    </table>
						</DIV>
					    </td>
					    <?php /*
					    <td>
						<DIV id=div1 style="DISPLAY: block;">
						    <table width="99%" border="0" cellspacing="0" cellpadding="0">
							<tr>
							    <td>
								<fieldset class="tableborder whitebg">
								    <legend class="midheader"> <img src="../admin/images/ico-serach.gif" alt="Search Assets" hspace="2" align="absmiddle" title="Search Assets" />&nbsp;Import File</legend>
								    <table  align="center" border="0" cellpadding="2" cellspacing="3" width="100%">
									<tr>
									    <td colspan="3" align="left"><font class="error">NOTE:</font> Upload only excel file.</td>

									</tr>
									<tr>
									    <td align="left">
										<b>Upload File</b>													</td>
										<td align="left"> <input type="file" name="upload_me" id="upload_me" class="form-control input-sm" onchange="">
										</td>
									</tr>

									<tr>
									    <td colspan="2" align="left">
										<input name="upload" value="Upload" id="upload" class="button" type="button" onclick="test();"/>
										  &nbsp;

									    </td>
									</tr>
								    </table>
								</fieldset>
							    </td>
							</tr>
						    </table>
						</DIV>
					    </td>*/?>
					</tr>

					<tr>
					<?php if(isset($msg) && $msg != '') {
					    if($success_msg == 1){ ?>
						<td colspan="2" align="left" style="font-size: 14px;font-weight: bold; color: green;"><?php echo $msg;?> </td><?php
					    }else{?>
						<td colspan="2" align="left" style="font-size: 14px;font-weight: bold; color: RED;"><?php echo $msg;?> </td><?php
					    }
					}?>
					</tr>
				    </table>
				    <table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
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
								<th class="fieldheader">INPUT SOURCE</th>
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
									<td class="whitebg"><?php echo $rows['INPUT_SOURCE'];?></td>
								    </tr><?php $j++;
								}?>
								<tr>
								    <td style="" align="center" valign="middle" colspan="10" class="whitebg">&nbsp;</td>
								</tr>
								<?php
								if($total > 1){echo '<tr ><td colspan="10" style="padding:0px;" class="whitebg"><b>Page:</b> ';
								  for($i=1;$i<=$total;$i++){
								    if($i==$page) { echo "<span style='font-weight:bold;'>".$i."  </span>"; }
								    else { echo "<a style='font-weight:normal;' href='?page=".$i."'>".$i."</a>  "; }
								  }
								  echo '</td></td></tr>';
								}?><?php
							    }else{?>
							    <tr>
								<td style="" align="center" valign="middle" colspan="10" class="whitebg">&nbsp;</td>
							    </tr>
							    <tr>
								<td align="center" valign="middle" colspan="10" class="whitebg"><b>No Record Found!</b></td>
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
<script type="text/javascript">

    function test()
    {
	obj = document.getElementById('search_me');
	if(isExcel(obj.value)){
	    document.getElementById('frmindexvendor').submit();
	    return true;
	}else{
	    return false;
	}
    }
</script>
</body>
</html>