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
set_time_limit(0);
include_once('common.php');

checklogin();
$where = array();$wherecond = '';
$start = 0;$limit = 100;//print_r($_POST);
if(isset($_POST['search']))
{
    $_SESSION['condtion'] = '';
    if(isset($_POST['LASTNAME']) && $_POST['LASTNAME'] != ''){
	$_POST['LASTNAME'] = mysql_real_escape_string($_POST['LASTNAME']);
        $wherecond= '(LASTNAME LIKE "%'.$_POST['LASTNAME'].'%" OR FIRSTNAME LIKE "%'.$_POST['LASTNAME'].'%" OR
		 MIDNAME LIKE "%'.$_POST['LASTNAME'].'%" OR BUSNAME LIKE "%'.$_POST['LASTNAME'].'%" OR
		 DBA_NAME LIKE "%'.$_POST['LASTNAME'].'%" OR CITY LIKE "%'.$_POST['LASTNAME'].'%" OR
		 STATE LIKE "%'.$_POST['LASTNAME'].'%" OR ZIP LIKE "%'.$_POST['LASTNAME'].'%" OR
		 INPUT_SOURCE LIKE "%'.$_POST['LASTNAME'].'%")';
    }
    if($wherecond != ''){
        $_SESSION['condtion'] = $wherecond = 'WHERE '.$wherecond;
    }

}

if(isset($_FILES["upload_me"]) && !isset($_POST['search']))
{
    $filename = $_FILES["upload_me"]["tmp_name"];
    if($_FILES["upload_me"]["size"] > 0)
    {
	//  Include PHPExcel_IOFactory

	/** Include path **/
	set_include_path('../vendor/PHPExcel/Classes/');

	/** PHPExcel_IOFactory */
	require_once 'PHPExcel/IOFactory.php';

	$inputFileName = $_FILES['upload_me']['tmp_name'];

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

	    if($data1[0][7] != '' && false === strtotime($data1[0][7]))
		$data1[0][7] = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($data1[0][7]));
	    if(!empty($data1[0][15]))
		$data[] =  $data1[0];
	}//echo '<pre>';print_r($data);die;
	if($data){
	    for($i=1;$i<count($data);$i++)
	    {
		$sql = 'INSERT into Exclusions(LASTNAME, FIRSTNAME, PREFIX, SUFFIX, MIDNAME, BUSNAME, DBA_NAME, DOB, ADDRESS,
		    ADDRESS_TWO, CITY, STATE, ZIP, ZIP_4, INPUT_SOURCE, INPUT_MONTH) VALUES ("'.$data[$i][0].'", "'.$data[$i][1].'", "'.$data[$i][2].'", "'.$data[$i][3].'",
		    "'.$data[$i][4].'", "'.$data[$i][5].'" ,"'.$data[$i][6].'" ,"'.$data[$i][7].'", "'.$data[$i][8].'", "'.$data[$i][9].'", "'.$data[$i][10].'",
		    "'.$data[$i][11].'", '.$data[$i][12].', '.$data[$i][13].', "'.$data[$i][14].'", "'.$data[$i][15].'")';
		//echo $sql.'<br>';
		$rs = mysql_query($sql);
	    }
	    if(isset($rs)){
		header('Location: leie_user_exclusions.php?import_success=1');die;
	    }else{
		header('Location: leie_user_exclusions.php?import_success=0');die;
	    }
	}else{
	    header('Location: leie_user_exclusions.php?import_success=-1');die;
	}
    }else{
	header('Location: leie_user_exclusions.php?import_success=-2');die;
    }
}

if(isset($_FILES["search_me"]) && $_FILES["search_me"]["tmp_name"] != '')
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

	    if($data1[0][8] != '' && false === strtotime($data1[0][8]))
		$data1[0][8] = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($data1[0][8]));
	    //if(!empty($data1[0][14]))
		$data[] =  $data1[0];
	}//echo '<pre>';print_r($data);die;
	if($data){
	    //$sql =  "TRUNCATE Exclusions_tmp";mysql_query($sql);//empty the temp table.
	    $searched_row = array();
	    for($i=1;$i<count($data);$i++)
	    {
		$search_where = array();
		if($data[$i][1] != '')
		    $search_where[] = "LASTNAME = '".$data[$i][1]."'";
		if($data[$i][2] != '')
		    $search_where[] = "FIRSTNAME = '".$data[$i][2]."'";
		if($data[$i][5] != '')
		    $search_where[] = "MIDNAME = '".$data[$i][5]."'";
		if($data[$i][6] != '')
		    $search_where[] = "BUSNAME = '".$data[$i][6]."'";
		if($data[$i][7] != '')
		    $search_where[] = "DBA_NAME = '".$data[$i][7]."'";
		//if($data[$i][8] != '')
		//    $search_where[] = "DOB = '".$data[$i][8]."'";
		if($data[$i][9] != '')
		    $search_where[] = "ADDRESS = '".$data[$i][9]."'";
		if($data[$i][10] != '')
		    $search_where[] = "ADDRESS_TWO = '".$data[$i][10]."'";
		if($data[$i][11] != '')
		    $search_where[] = "CITY = '".$data[$i][11]."'";
		if($data[$i][12] != '')
		    $search_where[] = "STATE = '".$data[$i][12]."'";
		if($data[$i][13] != '')
		    $search_where[] = "ZIP = '".$data[$i][13]."'";
		if($data[$i][14] != '')
		    $search_where[] = "ZIP_4 = '".$data[$i][14]."'";
		if($data[$i][15] != '')
		    $search_where[] = "INPUT_SOURCE = '".$data[$i][15]."'";
		if($data[$i][16] != '')
		    $search_where[] = "INPUT_MONTH = '".$data[$i][16]."'";
		if(!empty($search_where)){
		    $str_search = implode(' AND ', $search_where);
		    $sql = "SELECT ID FROM Exclusions WHERE $str_search";//echo $sql.'<br>';
		    if($rs = mysql_query($sql)){
			$row = mysql_fetch_array($rs);
			if($row['ID'] > 0)
			    $searched_row[] = $row['ID'];
		    }
		    $_SESSION['check_tmp'] = 1;
		}
	    }
	    if(!empty($searched_row)){
		$_SESSION['condtion'] = '';
		$ids = implode(', ', $searched_row);
		$wherecond = "ID IN ($ids)";
	    }else{
		$wherecond = " ID IN (-1)";
	    }
	    $_SESSION['condtion'] = $wherecond = 'WHERE '.$wherecond;
	   // echo '<pre>';print_r($searched_row);
	}else{
	    $_SESSION['check_tmp'] = 0;
	}
    }
}

if(isset($_GET['import_success']) && $_GET['import_success'] != '')
{
    switch($_GET['import_success']){
	case '1': $msg = 'Import has been done successfully.';$success_msg=1;break;
	case '0': $msg = 'Problem while inserting data onto DB.';$success_msg=0;break;
	case '-1': $msg = 'An Empty file found.';$success_msg=0;break;
	case '-2': $msg = 'Please choose to upload csv/exel file.';$success_msg=0;break;
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

$sql = "SELECT * FROM `Exclusions` $wherecond ORDER BY ID DESC LIMIT $start, $limit";
$rs = mysql_query($sql);//echo $sql;
$count = mysql_num_rows($rs);

$rows = mysql_num_rows(mysql_query("SELECT * FROM `Exclusions` $wherecond"));
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
			    <td align="center" valign="top" colspan="2">
				<table cellspacing="0" cellpadding="2" width="100%" align="center" border="0">
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
									    <b>Enter Your Text</b>													</td>
									    <td align="left"> <input type="text" name="LASTNAME" id="LASTNAME" class="form-control input-sm">
									    </td>
								    </tr>
								    <tr>
									<td align="MIDDLE">
									    <b>OR</b>													</td>
									    <td align="left"></td>
								    </tr>
								    <tr>
									    <td align="left">
										<b>Find common records from database using csv/excel File?</b>													</td>
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
									    <td align="left"></td>
									    <td align="left"></td>
									</tr>
									<tr>
									    <td align="left"></td>
									    <td align="left"></td>
									</tr>
									<tr>
									    <td align="left"></td>
									    <td align="left"></td>
									</tr>
									<tr>
									    <td align="left"></td>
									    <td align="left"></td>
									</tr><tr>
									    <td align="left"></td>
									    <td align="left"></td>
									</tr><tr>
									    <td align="left"></td>
									    <td align="left"></td>
									</tr><tr>
									    <td align="left"></td>
									    <td align="left"></td>
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
					    </td>
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
								if($page > 1 )$j=(($page-1) * 100)+1;else $j =1;
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
	obj = document.getElementById('upload_me');
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