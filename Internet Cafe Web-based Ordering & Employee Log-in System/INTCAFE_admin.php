<?php
$user='root';
$password='010704';
$database='Internet_Cafe';
$servername='localhost:3310';

$mysqli= new mysqli($servername,$user,$password,$database);
date_default_timezone_set('Asia/Manila');

if($mysqli->connect_error){
	die('Connect Error('.$mysqli->maxdb_connect_errno.')'.$mysqli->maxdb_connect_error);
}


$sql1= "SELECT * FROM Internet_Cafe.Pricing";
$result1=$mysqli->query($sql1);
$sql2= "SELECT * FROM Internet_Cafe.Computer";
$result2=$mysqli->query($sql2);
$sql3= "SELECT * FROM Internet_Cafe.Session";
$result3=$mysqli->query($sql3);
$sql4= "SELECT * FROM Internet_Cafe.Room";
$result4=$mysqli->query($sql4);
$sql5= "SELECT * FROM Internet_Cafe.Wifi";
$result5=$mysqli->query($sql5);
$sql6= "SELECT * FROM Internet_Cafe.Employee";
$result6=$mysqli->query($sql6);
$sql7= "SELECT * FROM Internet_Cafe.Customer";
$result7=$mysqli->query($sql7);
$sql8= "SELECT * FROM Internet_Cafe.Payment";
$result8=$mysqli->query($sql8);

$priceout = $compout = $roomout = $seshout = $wifiout = $empout = $cusout = $payout = "";
//- START OF PROGRAM //

if (isset($_POST['add1'])) {
	$PRICE_ID = trim($_POST['price_id']);
	$PRICE_AMOUNT = trim($_POST['price_amt']);
	$PRICE_TYPE = trim($_POST['price_type']);
	$PRICE_DURATION = trim($_POST['price_dur']);
	
	$sql="INSERT INTO Internet_Cafe.PRICING (PRICE_ID, PRICE_TYPE, PRICE_DURATION, PRICE_AMOUNT) VALUES ('$PRICE_ID', '$PRICE_TYPE', '$PRICE_DURATION', '$PRICE_AMOUNT')";
	if(mysqli_query($mysqli,$sql))
	{
		$priceout =  "Data stored in Database succcessful!";
	}
	else
	{
		$priceout = mysqli_error($mysqli);
	}
}

elseif(isset($_POST['delete1']))
{
	$PRICE_ID = trim($_POST['price_id']);
	$PRICE_AMOUNT = trim($_POST['price_amt']);
	$PRICE_TYPE = trim($_POST['price_type']);
	$PRICE_DURATION = trim($_POST['price_dur']);
	
	$sql="DELETE FROM Internet_Cafe.PRICING WHERE PRICE_ID='$PRICE_ID'";
	if(mysqli_query($mysqli,$sql))
	{
		$priceout = "Data deleted from Database succcessful!";
	}
	else
	{
		$priceout =  mysqli_error($mysqli);
	}
}

elseif(isset($_POST['update1']))
{
	$PRICE_ID = trim($_POST['price_id']);
	$PRICE_AMOUNT = trim($_POST['price_amt']);
	$PRICE_TYPE = trim($_POST['price_type']);
	$PRICE_DURATION = trim($_POST['price_dur']);
	
	$origin="SELECT * FROM Internet_Cafe.PRICING WHERE PRICE_ID='$PRICE_ID'";
	$result=$mysqli->query($origin);
	$row=$result->fetch_assoc();

	if($PRICE_TYPE != "" AND $PRICE_TYPE != $row['PRICE_TYPE'])
	{
		$PRICE_TYPE=$PRICE_TYPE;
	}
	else
	{
		$PRICE_TYPE=$row['PRICE_TYPE'];
	}

	if($PRICE_DURATION != "" AND $PRICE_DURATION != $row['PRICE_DURATION'])
	{
		$PRICE_DURATION=$PRICE_DURATION;
	}
	else
	{
		$PRICE_DURATION=$row['PRICE_DURATION'];
	}

	if($PRICE_AMOUNT != "" AND $PRICE_AMOUNT != $row['PRICE_AMOUNT'])
	{
		$PRICE_AMOUNT=$PRICE_AMOUNT;
	}
	else
	{
		$PRICE_AMOUNT=$row['PRICE_AMOUNT'];
	}
	
	$sql="UPDATE Internet_Cafe.PRICING SET PRICE_TYPE='$PRICE_TYPE', PRICE_DURATION='$PRICE_DURATION', PRICE_AMOUNT='$PRICE_AMOUNT' WHERE PRICE_ID='$PRICE_ID'";

	if(mysqli_query($mysqli,$sql))
	{
		$priceout = "Data updated in Database succcessful!";
	}
	else
	{
		$priceout = mysqli_error($mysqli);
	}
}


if (isset($_POST['add2'])) {
	
	$COMP_NUM = trim($_POST['comp_num']);
	$COMP_DESC = trim($_POST['commp_desc']);
	$COMP_STATUS = trim($_POST['comp_stat']);
	$ROOM_NUM = trim($_POST['room_num2']);
	
	$sql="INSERT INTO Internet_Cafe.COMPUTER (COMP_NUM, COMP_DESC, COMP_STATUS, ROOM_NUM) VALUES ('$COMP_NUM', '$COMP_DESC', '$COMP_STATUS', '$ROOM_NUM')";
	if(mysqli_query($mysqli,$sql))
	{
		$compout =  "Data stored in Database succcessful!";
	}
	else
	{
		$compout = mysqli_error($mysqli);
	}
}

elseif(isset($_POST['delete2']))
{
	$COMP_NUM = trim($_POST['comp_num']);
	$COMP_DESC = trim($_POST['commp_desc']);
	$COMP_STATUS = trim($_POST['comp_stat']);
	$ROOM_NUM = trim($_POST['room_num2']);

	$sql="DELETE FROM Internet_Cafe.COMPUTER WHERE COMP_NUM='$COMP_NUM' AND ROOM_NUM='$ROOM_NUM'";
	if(mysqli_query($mysqli,$sql))
	{
		$compout = "Data deleted from Database succcessful!";
	}
	else
	{
		$compout =  mysqli_error($mysqli);
	}
}

elseif(isset($_POST['update2']))
{
	$COMP_NUM = trim($_POST['comp_num']);
	$COMP_DESC = trim($_POST['commp_desc']);
	$COMP_STATUS = trim($_POST['comp_stat']);
	$ROOM_NUM = trim($_POST['room_num2']);
	
	$origin="SELECT * FROM Internet_Cafe.COMPUTER WHERE COMP_NUM='$COMP_NUM' AND ROOM_NUM='$ROOM_NUM'";
	$result=$mysqli->query($origin);
	$row=$result->fetch_assoc();

	if($COMP_DESC != "" AND $COMP_DESC != $row['COMP_DESC'])
	{
		$COMP_DESC=$COMP_DESC;
	}
	else
	{
		$COMP_DESC=$row['COMP_DESC'];
	}

	if($COMP_STATUS != "" AND $COMP_STATUS != $row['COMP_STATUS'])
	{
		$COMP_STATUS=$COMP_STATUS;
	}
	else
	{
		$COMP_STATUS=$row['COMP_STATUS'];
	}

	$sql="UPDATE Internet_Cafe.COMPUTER SET COMP_DESC='$COMP_DESC', COMP_STATUS='$COMP_STATUS' WHERE ROOM_NUM='$ROOM_NUM' AND COMP_NUM='$COMP_NUM'";

	if(mysqli_query($mysqli,$sql))
	{
		$compout = "Data updated in Database succcessful!";
	}
	else
	{
		$compout = mysqli_error($mysqli);
	}
}

if (isset($_POST['add3'])) {
	$ROOM_NUM = trim($_POST['room_num']);
	$ROOM_TYPE = trim($_POST['room_type']);
	$WIFI_ID = trim($_POST['wifi_id']);
	
	$sql="INSERT INTO Internet_Cafe.ROOM (ROOM_NUM, ROOM_TYPE, WIFI_ID) VALUES ('$ROOM_NUM', '$ROOM_TYPE', '$WIFI_ID')";
	if(mysqli_query($mysqli,$sql))
	{
		$roomout =  "Data stored in Database succcessful!";
	}
	else
	{
		$roomout = mysqli_error($mysqli);
	}
}

elseif(isset($_POST['delete3']))
{
	$ROOM_NUM = trim($_POST['room_num']);
	$ROOM_TYPE = trim($_POST['room_type']);
	$WIFI_ID = trim($_POST['wifi_id']);
	
	$sql="DELETE FROM Internet_Cafe.ROOM WHERE ROOM_NUM='$ROOM_NUM' AND WIFI_ID='$WIFI_ID'";
	if(mysqli_query($mysqli,$sql))
	{
		$roomout = "Data deleted from Database succcessful!";
	}
	else
	{
		$roomout =  mysqli_error($mysqli);
	}
}

elseif(isset($_POST['update3']))
{
	$ROOM_NUM = trim($_POST['room_num']);
	$ROOM_TYPE = trim($_POST['room_type']);
	$WIFI_ID = trim($_POST['wifi_id']);
	
	$origin="SELECT * FROM Internet_Cafe.ROOM WHERE ROOM_NUM='$ROOM_NUM' AND WIFI_ID='$WIFI_ID'";
	$result=$mysqli->query($origin);
	$row=$result->fetch_assoc();

	if($ROOM_TYPE != "" AND $ROOM_TYPE != $row['ROOM_TYPE'])
	{
		$ROOM_TYPE=$ROOM_TYPE;
	}
	else
	{
		$ROOM_TYPE=$row['ROOM_TYPE'];
	}
	
	$sql="UPDATE Internet_Cafe.ROOM SET ROOM_TYPE='$ROOM_TYPE' WHERE ROOM_NUM='$ROOM_NUM' AND WIFI_ID='$WIFI_ID'";

	if(mysqli_query($mysqli,$sql))
	{
		$roomout = "Data updated in Database succcessful!";
	}
	else
	{
		$roomout = mysqli_error($mysqli);
	}
}


if (isset($_POST['add4'])) {
	$SESSION_ID = trim($_POST['sesh_id']);
	$SESSION_START = trim($_POST['sesh_start']);
	$SESSION_END = trim($_POST['sesh_end']);
	$CUS_ID = trim($_POST['cus_id3']);
	$COMP_NUM = trim($_POST['comp_num2']);
	
	$sql="INSERT INTO Internet_Cafe.SESSION (SESSION_ID, SESSION_START, SESSION_END, CUS_ID, COMP_NUM) VALUES ('$SESSION_ID', '$SESSION_START', '$SESSION_END','$CUS_ID', '$COMP_NUM')";
	if(mysqli_query($mysqli,$sql))
	{
		$seshout =  "Data stored in Database succcessful!";
	}
	else
	{
		$seshout = mysqli_error($mysqli);
	}
}

elseif(isset($_POST['delete4']))
{
	$SESSION_ID = trim($_POST['sesh_id']);
	$SESSION_START = trim($_POST['sesh_start']);
	$SESSION_END = trim($_POST['sesh_end']);
	$CUS_ID = trim($_POST['cus_id3']);
	$COMP_NUM = trim($_POST['comp_num2']);
	
	$sql="DELETE FROM Internet_Cafe.SESSION WHERE SESSION_ID='$SESSION_ID' AND CUS_ID='$CUS_ID' AND COMP_NUM='$COMP_NUM'";
	if(mysqli_query($mysqli,$sql))
	{
		$seshout = "Data deleted from Database succcessful!";
	}
	else
	{
		$seshout =  mysqli_error($mysqli);
	}
}

elseif(isset($_POST['update4']))
{
	$SESSION_ID = trim($_POST['sesh_id']);
	$SESSION_START = trim($_POST['sesh_start']);
	$SESSION_END = trim($_POST['sesh_end']);
	$CUS_ID = trim($_POST['cus_id3']);
	$COMP_NUM = trim($_POST['comp_num2']);
	
	$origin="SELECT * FROM Internet_Cafe.SESSION WHERE SESSION_ID='$SESSION_ID' AND CUS_ID='$CUS_ID' AND COMP_NUM='$COMP_NUM'";
	$result=$mysqli->query($origin);
	$row=$result->fetch_assoc();

	if($SESSION_START != "" AND $SESSION_START != $row['SESSION_START'])
	{
		$SESSION_START=$SESSION_START;
	}
	else
	{
		$SESSION_START=$row['SESSION_START'];
	}

	if($SESSION_END != "" AND $SESSION_END != $row['SESSION_END'])
	{
		$SESSION_END=$SESSION_END;
	}
	else
	{
		$SESSION_END=$row['SESSION_END'];
	}

	$sql="UPDATE Internet_Cafe.SESSION SET SESSION_START='$SESSION_START', SESSION_END='$SESSION_END' WHERE SESSION_ID='$SESSION_ID' AND CUS_ID='$CUS_ID' AND COMP_NUM='$COMP_NUM'";

	if(mysqli_query($mysqli,$sql))
	{
		$seshout = "Data updated in Database succcessful!";
	}
	else
	{
		$seshout = mysqli_error($mysqli);
	}
}


if (isset($_POST['add5'])) {
	$WIFI_ID = trim($_POST['wifi_id2']);
	$WIFI_CODE = trim($_POST['wifi_code']);
	$WIFI_NAME = trim($_POST['wifi_name']);
	
	$sql="INSERT INTO Internet_Cafe.WIFI (WIFI_ID, WIFI_CODE, WIFI_NAME) VALUES ('$WIFI_ID', '$WIFI_CODE', '$WIFI_NAME')";
	if(mysqli_query($mysqli,$sql))
	{
		$wifiout =  "Data stored in Database succcessful!";
	}
	else
	{
		$wifiout = mysqli_error($mysqli);
	}
}

elseif(isset($_POST['delete5']))
{
	$WIFI_ID = trim($_POST['wifi_id2']);
	$WIFI_CODE = trim($_POST['wifi_code']);
	$WIFI_NAME = trim($_POST['wifi_name']);
	
	$sql="DELETE FROM Internet_Cafe.WIFI WHERE WIFI_ID='$WIFI_ID'";
	if(mysqli_query($mysqli,$sql))
	{
		$wifiout = "Data deleted from Database succcessful!";
	}
	else
	{
		$wifiout =  mysqli_error($mysqli);
	}
}

elseif(isset($_POST['update5']))
{
	$WIFI_ID = trim($_POST['wifi_id2']);
	$WIFI_CODE = trim($_POST['wifi_code']);
	$WIFI_NAME = trim($_POST['wifi_name']);
	
	$origin="SELECT * FROM Internet_Cafe.WIFI WHERE WIFI_ID='$WIFI_ID'";
	$result=$mysqli->query($origin);
	$row=$result->fetch_assoc();

	if($WIFI_CODE != "" AND $WIFI_CODE != $row['WIFI_CODE'])
	{
		$WIFI_CODE=$WIFI_CODE;
	}
	else
	{
		$WIFI_CODE=$row['WIFI_CODE'];
	}

	if($WIFI_NAME != "" AND $WIFI_NAME != $row['WIFI_NAME'])
	{
		$WIFI_NAME=$WIFI_NAME;
	}
	else
	{
		$WIFI_NAME=$row['WIFI_NAME'];
	}
	
	$sql="UPDATE Internet_Cafe.WIFI SET WIFI_CODE='$WIFI_CODE', WIFI_NAME='$WIFI_NAME' WHERE WIFI_ID='$WIFI_ID'";

	if(mysqli_query($mysqli,$sql))
	{
		$wifiout = "Data updated in Database succcessful!";
	}
	else
	{
		$wifiout = mysqli_error($mysqli);
	}
}

if (isset($_POST['add6'])) {
	$EMP_ID = trim($_POST['emp_id']);
	$EMP_NAME = trim($_POST['emp_name']);
	$EMP_ROLE = trim($_POST['emp_role']);
	$EMP_PHONE = trim($_POST['emp_phone']);
	$EMP_PASSWORD = trim($_POST['emp_pass']);
	
	$sql="INSERT INTO Internet_Cafe.EMPLOYEE (EMP_ID, EMP_NAME, EMP_ROLE, EMP_PHONE, EMP_PASSWORD) VALUES ('$EMP_ID', '$EMP_NAME', '$EMP_ROLE', '$EMP_PHONE', '$EMP_PASSWORD')";
	if(mysqli_query($mysqli,$sql))
	{
		$empout =  "Data stored in Database succcessful!";
	}
	else
	{
		$empout = mysqli_error($mysqli);
	}
}

elseif(isset($_POST['delete6']))
{
	$EMP_ID = trim($_POST['emp_id']);
	$EMP_NAME = trim($_POST['emp_name']);
	$EMP_ROLE = trim($_POST['emp_role']);
	$EMP_PHONE = trim($_POST['emp_phone']);
	$EMP_PASSWORD = trim($_POST['emp_pass']);
	
	$sql="DELETE FROM Internet_Cafe.EMPLOYEE WHERE EMP_ID='$EMP_ID'";
	if(mysqli_query($mysqli,$sql))
	{
		$empout = "Data deleted from Database succcessful!";
	}
	else
	{
		$empout =  mysqli_error($mysqli);
	}
}

elseif(isset($_POST['update6']))
{
	$EMP_ID = trim($_POST['emp_id']);
	$EMP_NAME = trim($_POST['emp_name']);
	$EMP_ROLE = trim($_POST['emp_role']);
	$EMP_PHONE = trim($_POST['emp_phone']);
	$EMP_PASSWORD = trim($_POST['emp_pass']);
	
	$origin="SELECT * FROM Internet_Cafe.EMPLOYEE WHERE EMP_ID='$EMP_ID'";
	$result=$mysqli->query($origin);
	$row=$result->fetch_assoc();

	if($EMP_NAME != "" AND $EMP_NAME != $row['EMP_NAME'])
	{
		$EMP_NAME=$EMP_NAME;
	}
	else
	{
		$EMP_NAME=$row['EMP_NAME'];
	}

	if($EMP_ROLE != "" AND $EMP_ROLE != $row['EMP_ROLE'])
	{
		$EMP_ROLE=$EMP_ROLE;
	}
	else
	{
		$EMP_ROLE=$row['EMP_ROLE'];
	}
	if($EMP_PHONE != "" AND $EMP_PHONE != $row['EMP_PHONE'])
	{
		$EMP_PHONE=$EMP_PHONE;
	}
	else
	{
		$EMP_PHONE=$row['EMP_PHONE'];
	}
	if($EMP_PASSWORD != "" AND $EMP_PASSWORD != $row['EMP_PASSWORD'])
	{
		$EMP_PASSWORD=$EMP_PASSWORD;
	}
	else
	{
		$EMP_PASSWORD=$row['EMP_PASSWORD'];
	}
	$sql="UPDATE Internet_Cafe.EMPLOYEE SET EMP_NAME='$EMP_NAME', EMP_ROLE='$EMP_ROLE', EMP_PHONE='$EMP_PHONE', EMP_PASSWORD='$EMP_PASSWORD' WHERE EMP_ID='$EMP_ID'";

	if(mysqli_query($mysqli,$sql))
	{
		$empout = "Data updated in Database succcessful!";
	}
	else
	{
		$empout = mysqli_error($mysqli);
	}
}
if (isset($_POST['add7'])) {
    // Assuming $mysqli is already defined and connected
    $CUS_ID = $_POST['cus_id'];
    $CUS_NAME = $_POST['cus_name'];
    $CUS_PHONE = empty($_POST['cus_phone']) ? null : trim($_POST['cus_phone']);
    $CUS_EMAIL = empty($_POST['cus_email']) ? null : trim($_POST['cus_email']);

    // Prepare the SQL statement
    $sql = "INSERT INTO Internet_Cafe.CUSTOMER (CUS_ID, CUS_NAME, CUS_PHONE, CUS_EMAIL) 
            VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        // Bind parameters (s = string, NULL is handled as a placeholder)
        $stmt->bind_param('ssss', $CUS_ID, $CUS_NAME, $CUS_PHONE, $CUS_EMAIL);

        // Execute the statement
        if ($stmt->execute()) {
            $cusout = "Data stored in Database successfully!";
        } else {
            $cusout = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $cusout = "Error preparing statement: " . $mysqli->error;
    }
}



elseif(isset($_POST['delete7']))
{
	$CUS_ID = trim($_POST['cus_id']);
	$CUS_NAME = trim($_POST['cus_name']);
    $CUS_PHONE = empty($_POST['cus_phone']) ? "NULL" : "'" . trim($_POST['cus_phone']) . "'";
    $CUS_EMAIL = empty($_POST['cus_email']) ? "NULL" : "'" . trim($_POST['cus_email']) . "'";
 
	$sql="DELETE FROM Internet_Cafe.CUSTOMER WHERE CUS_ID='$CUS_ID'";
	if(mysqli_query($mysqli,$sql))
	{
		$cusout = "Data deleted from Database succcessful!";
	}
	else
	{
		$cusout =  mysqli_error($mysqli);
	}
}

elseif(isset($_POST['update7']))
{
	$CUS_ID = trim($_POST['cus_id']);
	$CUS_NAME = trim($_POST['cus_name']);
    $CUS_PHONE = empty($_POST['cus_phone']) ? "NULL" : "'" . trim($_POST['cus_phone']) . "'";
    $CUS_EMAIL = empty($_POST['cus_email']) ? "NULL" : "'" . trim($_POST['cus_email']) . "'";
 
	$origin="SELECT * FROM Internet_Cafe.CUSTOMER WHERE CUS_ID='$CUS_ID'";
	$result=$mysqli->query($origin);
	$row=$result->fetch_assoc();

	if($CUS_NAME != "" AND $CUS_NAME != $row['CUS_NAME'])
	{
		$CUS_NAME=$CUS_NAME;
	}
	else
	{
		$CUS_NAME=$row['CUS_NAME'];
	}

	if($CUS_PHONE != "" AND $CUS_PHONE != $row['CUS_PHONE'])
	{
		$CUS_PHONE=$CUS_PHONE;
	}
	else
	{
		$CUS_PHONE=$row['CUS_PHONE'];
	}
	if($CUS_EMAIL != "" AND $CUS_EMAIL != $row['CUS_EMAIL'])
	{
		$CUS_EMAIL=$CUS_EMAIL;
	}
	else
	{
		$CUS_EMAIL=$row['CUS_EMAIL'];
	}
	
	$sql="UPDATE Internet_Cafe.CUSTOMER SET CUS_NAME='$CUS_NAME', CUS_PHONE='$CUS_PHONE', CUS_EMAIL='$CUS_EMAIL' WHERE CUS_ID='$CUS_ID'";

	if(mysqli_query($mysqli,$sql))
	{
		$cusout = "Data updated in Database succcessful!";
	}
	else
	{
		$cusout = mysqli_error($mysqli);
	}
}


if (isset($_POST['add8'])) {
	$PAYMENT_ID = trim($_POST['pay_id']);
	$PAYMENT_INIT_AMT = trim($_POST['pay_init_amt']);
	$PAYMENT_FINAL_AMT = trim($_POST['pay_final_amt']);
	$PAYMENT_TYPE = trim($_POST['pay_type']);
	$PAYMENT_DATE = trim($_POST['pay_date']);
	$EMP_ID = trim($_POST['emp_id']);
	$CUS_ID = trim($_POST['id_cus']);
	$PRICE_ID = trim($_POST['price_id']);
    $PAYMENT_INIT_AMT = round((float)$PAYMENT_INIT_AMT, 2);
    $PAYMENT_FINAL_AMT = round((float)$PAYMENT_FINAL_AMT, 2);
    $PAYMENT_DISC_TYPE = empty($_POST['pay_disc_type']) ? "NULL" : "'" . trim($_POST['pay_disc_type']) . "'";
    $PAYMENT_DISC_AMT = empty($_POST['pay_disc_amt']) ? "NULL" : "'" . trim($_POST['pay_disc_amt']) . "'";
	if (empty($PAYMENT_ID) || empty($CUS_ID) || empty($PAYMENT_INIT_AMT) || empty($PAYMENT_FINAL_AMT) || empty($PAYMENT_DATE) || empty($PAYMENT_TYPE) || empty($EMP_ID) || empty($PRICE_ID)) {
        $payout = "Error: Accomplish all necessary fields.";
	}
	else {
		$sql = "INSERT INTO Internet_Cafe.PAYMENT (PAYMENT_ID, PAYMENT_INIT_AMT, PAYMENT_DISC_TYPE, PAYMENT_DISC_AMT, PAYMENT_FINAL_AMT, PAYMENT_TYPE, PAYMENT_DATE, EMP_ID, CUS_ID, PRICE_ID) 
		VALUES ('$PAYMENT_ID', $PAYMENT_INIT_AMT, $PAYMENT_DISC_TYPE, $PAYMENT_DISC_AMT, $PAYMENT_FINAL_AMT, '$PAYMENT_TYPE', '$PAYMENT_DATE', '$EMP_ID', '$CUS_ID', '$PRICE_ID')";
	
		if(mysqli_query($mysqli,$sql))
		{
			$payout =  "Data stored in Database succcessful!";
		}
		else
		{
		$payout = mysqli_error($mysqli);
		}
	}
}

elseif(isset($_POST['delete8']))
{
	$PAYMENT_ID = trim($_POST['pay_id']);
	$PAYMENT_INIT_AMT = trim($_POST['pay_init_amt']);
	$PAYMENT_FINAL_AMT = trim($_POST['pay_final_amt']);
	$PAYMENT_TYPE = trim($_POST['pay_type']);
	$PAYMENT_DATE = trim($_POST['pay_date']);
	$EMP_ID = trim($_POST['emp_id']);
	$CUS_ID = trim($_POST['id_cus']);
	$PRICE_ID = trim($_POST['price_id']);
	$PAYMENT_INIT_AMT = round((float)$PAYMENT_INIT_AMT, 2);
    $PAYMENT_FINAL_AMT = round((float)$PAYMENT_FINAL_AMT, 2);
    $PAYMENT_DISC_TYPE = empty($_POST['pay_disc_type']) ? "NULL" : "'" . trim($_POST['pay_disc_type']) . "'";
    $PAYMENT_DISC_AMT = empty($_POST['pay_disc_amt']) ? "NULL" : "'" . trim($_POST['pay_disc_amt']) . "'";

	$sql="DELETE FROM Internet_Cafe.PAYMENT WHERE PAYMENT_ID='$PAYMENT_ID' AND EMP_ID='$EMP_ID' AND CUS_ID='$CUS_ID' AND PRICE_ID='$PRICE_ID'";
	if(mysqli_query($mysqli,$sql))
	{
		$payout = "Data deleted from Database succcessful!";
	}
	else
	{
		$payout =  mysqli_error($mysqli);
	}
}

elseif(isset($_POST['update8']))
{
	$PAYMENT_ID = trim($_POST['pay_id']);
	$PAYMENT_INIT_AMT = trim($_POST['pay_init_amt']);
	$PAYMENT_FINAL_AMT = trim($_POST['pay_final_amt']);
	$PAYMENT_TYPE = trim($_POST['pay_type']);
	$PAYMENT_DATE = trim($_POST['pay_date']);
	$EMP_ID = trim($_POST['emp_id']);
	$CUS_ID = trim($_POST['id_cus']);
	$PRICE_ID = trim($_POST['price_id']);
    $PAYMENT_INIT_AMT = round((float)$PAYMENT_INIT_AMT, 2);
    $PAYMENT_FINAL_AMT = round((float)$PAYMENT_FINAL_AMT, 2);
    $PAYMENT_DISC_TYPE = empty($_POST['pay_disc_type']) ? "NULL" : "'" . trim($_POST['pay_disc_type']) . "'";
    $PAYMENT_DISC_AMT = empty($_POST['pay_disc_amt']) ? "NULL" : "'" . trim($_POST['pay_disc_amt']) . "'";
	
	$origin="SELECT * FROM Internet_Cafe.PAYMENT WHERE PAYMENT_ID='$PAYMENT_ID' AND EMP_ID='$EMP_ID' AND CUS_ID='$CUS_ID' AND PRICE_ID='$PRICE_ID'";
	$result=$mysqli->query($origin);
	$row=$result->fetch_assoc();

	if($PAYMENT_INIT_AMT != "" AND $PAYMENT_INIT_AMT != $row['PAYMENT_INIT_AMT'])
	{
		$PAYMENT_INIT_AMT=$PAYMENT_INIT_AMT;
	}
	else
	{
		$PAYMENT_INIT_AMT=$row['PAYMENT_INIT_AMT'];
	}

	if($PAYMENT_DISC_TYPE != "" AND $PAYMENT_DISC_TYPE != $row['PAYMENT_DISC_TYPE'])
	{
		$PAYMENT_DISC_TYPE=$PAYMENT_DISC_TYPE;
	}
	else
	{
		$PAYMENT_DISC_TYPE=$row['PAYMENT_DISC_TYPE'];
	}
	if($PAYMENT_DISC_AMT != "" AND $PAYMENT_DISC_AMT != $row['PAYMENT_DISC_AMT'])
	{
		$PAYMENT_DISC_AMT=$PAYMENT_DISC_AMT;
	}
	else
	{
		$PAYMENT_DISC_AMT=$row['PAYMENT_DISC_AMT'];
	}
	if($PAYMENT_FINAL_AMT != "" AND $PAYMENT_FINAL_AMT != $row['PAYMENT_FINAL_AMT'])
	{
		$PAYMENT_FINAL_AMT=$PAYMENT_FINAL_AMT;
	}
	else
	{
		$PAYMENT_FINAL_AMT=$row['PAYMENT_FINAL_AMT'];
	}
	if($PAYMENT_TYPE != "" AND $PAYMENT_TYPE != $row['PAYMENT_TYPE'])
	{
		$PAYMENT_TYPE=$PAYMENT_TYPE;
	}
	else
	{
		$PAYMENT_TYPE=$row['PAYMENT_TYPE'];
	}
	if($PAYMENT_DATE != "" AND $PAYMENT_DATE != $row['PAYMENT_DATE'])
	{
		$PAYMENT_DATE=$PAYMENT_DATE;
	}
	else
	{
		$PAYMENT_DATE=$row['PAYMENT_DATE'];
	}
	
	$sql="UPDATE Internet_Cafe.PAYMENT SET  PAYMENT_INIT_AMT='$PAYMENT_INIT_AMT', PAYMENT_DISC_TYPE='$PAYMENT_DISC_TYPE', PAYMENT_DISC_AMT='$PAYMENT_DISC_AMT', PAYMENT_FINAL_AMT='$PAYMENT_FINAL_AMT', PAYMENT_TYPE='$PAYMENT_TYPE', PAYMENT_DATE='$PAYMENT_DATE' WHERE PAYMENT_ID='$PAYMENT_ID' AND EMP_ID='$EMP_ID' AND CUS_ID='$CUS_ID' AND PRICE_ID='$PRICE_ID'";

	if(mysqli_query($mysqli,$sql))
	{
		$payout = "Data updated in Database succcessful!";
	}
	else
	{
		$payout = mysqli_error($mysqli);
	}
}

$mysqli->close();
?>					



<!DOCTYPE html>
<html>
<head>
    <style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
			background-color: #f8f9fa;
			color: #333;
			line-height: 1.6;
		}

		header {
			background-color: #6bc1c3;
			color: white;
			text-align: center;
			padding: 20px 0;
			font-size: 1.8rem;
			border-bottom: 3px solid #4fa3a3;
		}

		main {
			padding: 20px;
		}

		footer {
			background-color: #4fa3a3;
			color: white;
			text-align: center;
			padding: 10px 0;
			margin-top: 20px;
		}

		/* Table Styling */
		.table-container {
			max-width: 90%;
			margin: 20px auto;
			padding: 15px;
			background: #ffffff;
			border-radius: 8px;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 20px;
		}

		table th, table td {
			border: 1px solid #dee2e6;
			padding: 12px;
			text-align: center;
			font-size: 0.95rem;
		}

		table th {
			background-color: #6bc1c3;
			color: white;
			font-weight: bold;
		}

		table tr:nth-child(even) {
			background-color: #f8f9fa;
		}

		table tr:hover {
			background-color: #e9f7f6;
		}

		/* Form Styling */
		form {
			background: #ffffff;
			padding: 20px;
			border: 2px solid #6bc1c3;
			border-radius: 8px;
			max-width: 500px;
			margin: 20px auto;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
		}

		form label {
			display: block;
			margin-bottom: 8px;
			font-weight: bold;
			color: #333;
		}

		form input[type="text"], input[type="email"] {
			width: 95%;
			padding: 10px;
			margin-bottom: 15px;
			border: 1px solid #dee2e6;
			border-radius: 5px;
			font-size: 0.95rem;
		}

		form input[type="submit"] {
			background-color: #6bc1c3;
			color: white;
			border: none;
			padding: 10px 15px;
			border-radius: 5px;
			cursor: pointer;
			font-size: 1rem;
			transition: background-color 0.3s ease;
		}

		form input[type="submit"]:hover {
			background-color: #4fa3a3;
		}

		/* Headings */
		h1, h2 {
			text-align: center;
			margin: 10px 0;
		}

		h2 {
			color: #4fa3a3;
			font-size: 1.5rem;
		}

		/* Responsiveness */
		@media (max-width: 600px) {
			body {
				padding: 10px;
			}

			table th, table td {
				font-size: 0.85rem;
			}

			form {
				padding: 15px;
			}
		}


    </style>
</head>
<body>

    <h1>Internet Cafe</h1>

    <!-- Pricing Table -->
    <h2>Pricing Table</h2>
	<table>
		<tr>
			<th>PRICE_ID</th>
			<th>Type</th>
			<th>Duration</th>
			<th>Amount</th>

		</tr>
		<?php 
			while($row1=$result1->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $row1['PRICE_ID'];?></td>
			<td><?php echo $row1['PRICE_TYPE'];?></td>
			<td><?php echo $row1['PRICE_DURATION'];?></td>
			<td>₱ <?php echo $row1['PRICE_AMOUNT'];?></td>
		</tr>
		<?php
			}
		?>
	</table>
		<br>
		<div style="text-align: center;">
			<span style="color: #8B2500; font-weight: bold;"><?php echo $priceout; ?></span>
		</div>
		<br><br>
		<form action="" method="POST">
			<label for "price_id">Pricing ID: </label>
			<input  type="text" name="price_id" ><br>
			
			<label for "price_amt">Pricing Amount: </label>
			<input  type="text" name="price_amt" ><br>
			
			<label for "price_type">Pricing Type: </label>
			<input  type="text" name="price_type" ><br>
			
			<label for "price_dur">Pricing Duration: </label>
			<input  type="text" name="price_dur" ><br>
			
			<input type = "submit" name ="add1" value="Add">
			<br><br>
			<input type = "submit" name ="update1" value="Update">
			<br><br>
			<input type = "submit" name ="delete1" value="Delete">
			<br><br>			
		</form>
    <h2>Computer Table</h2>
	<table>
		<tr>
			<th>Computer No.</th>
			<th>Description</th>
			<th>Status</th>
			<th>Room No.</th>

		</tr>
		<?php 
			while($row2=$result2->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $row2['COMP_NUM'];?></td>
			<td><?php echo $row2['COMP_DESC'];?></td>
			<td><?php echo $row2['COMP_STATUS'];?></td>
			<td><?php echo $row2['ROOM_NUM'];?></td>
		</tr>
		<?php
			}
		?>
	</table>
		<br>
		<div style="text-align: center;">
			<span style="color: #8B2500; font-weight: bold;"><?php echo $compout; ?></span>
		</div>
		<br><br>
	<form action="" method="POST">
		<label for "comp_num">Computer Number: </label>
		<input  type="text" name="comp_num"><br>
		
		<label for "comp_desc">Computer Description: </label>
		<input  type="text" name="comp_desc"><br>
	 
		<label for "comp_stat">Computer Status: </label>
		<input  type="text" name="comp_stat"><br>
		
		<label for "room_num2">Room No: </label>
		<input  type="text" name="room_num2"><br>

		<input type = "submit" name ="add2" value="Add">
		<br><br>
		<input type = "submit" name ="update2" value="Update">
		<br><br>
		<input type = "submit" name ="delete2" value="Delete">
		<br><br>
	</form>
    <h2>Room Table</h2>
	<table>
		<tr>
			<th>Room No.</th>
			<th>Type</th>
			<th>Wifi_ID</th>

		</tr>
		<?php 
			while($row4=$result4->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $row4['ROOM_NUM'];?></td>
			<td><?php echo $row4['ROOM_TYPE'];?></td>
			<td><?php echo $row4['WIFI_ID'];?></td>
		</tr>
		<?php
			}
		?>
	</table>
		<br>
		<div style="text-align: center;">
			<span style="color: #8B2500; font-weight: bold;"><?php echo $roomout; ?></span>
		</div>
		<br><br>
		<form action="" method="POST">	
			<label for "room_num">Room No.: </label>
			<input  type="text" name="room_num"><br>
			
			<label for "room_type">Room Type: </label>
			<input  type="text" name="room_type"><br>
			
			<label for "wifi_id">Wifi ID: </label>
			<input  type="text" name="wifi_id"><br>

			<input type = "submit" name ="add3" value="Add">
			<br><br>
			<input type = "submit" name ="update3" value="Update">
			<br><br>
			<input type = "submit" name ="delete3" value="Delete">
			<br><br>
		</form>
    <h2>Session Table</h2>
	<table>
		<tr>
			<th>Session ID</th>
			<th>Start</th>
			<th>End</th>
			<th>Cus_ID</th>
			<th>Comp_Num</th>

		</tr>
		<?php 
			while($row3=$result3->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $row3['SESSION_ID'];?></td>
			<td><?php echo $row3['SESSION_START'];?></td>
			<td><?php echo $row3['SESSION_END'];?></td>
			<td><?php echo $row3['CUS_ID'];?></td>
			<td><?php echo $row3['COMP_NUM'];?></td>
		</tr>
		<?php
			}
		?>
	</table>
		<br>
		<div style="text-align: center;">
			<span style="color: #8B2500; font-weight: bold;"><?php echo $seshout; ?></span>
		</div>
		<br><br>
	<form action="" method="POST">
		<label for "session_id">Session ID: </label>
		<input  type="text" name="session_id"><br>
		
		<label for "sesh_start">Session Start: </label>
		<input  type="text" name="sesh_start"><br>
	 
		<label for "sesh_end">Session End: </label>
		<input  type="text" name="sesh_end"><br>
		
		<label for "id_cus3">Customer ID: </label>
		<input  type="text" name="id_cus3"><br>
		
		<label for "comp_num2">Computer Number: </label>
		<input  type="text" name="comp_num2"><br>
		
		<input type = "submit" name ="add4" value="Add">
		<br><br>
		<input type = "submit" name ="update4" value="Update">
		<br><br>
		<input type = "submit" name ="delete4" value="Delete">
		<br><br>
	</form>
	
    <h2>Wifi Table</h2>
	<table>
		<tr>
			<th>Wifi ID</th>
			<th>Wifi Name</th>
			<th>Wifi Code</th>

		</tr>
		<?php 
			while($row5=$result5->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $row5['WIFI_ID'];?></td>
			<td><?php echo $row5['WIFI_NAME'];?></td>
			<td><?php echo $row5['WIFI_CODE'];?></td>
		</tr>
		<?php
			}
		?>
	</table>
		<br>
		<div style="text-align: center;">
			<span style="color: #8B2500; font-weight: bold;"><?php echo $wifiout; ?></span>
		</div>
		<br><br>
	<form action="" method="POST">
		<label for "wifi_id2">WiFi ID: </label>
		<input  type="text" name="wifi_id2"><br>
		
		<label for "wifi_code">WiFi Code: </label>
		<input  type="text" name="wifi_code"><br>
		
		<label for "wifi_name">WiFi Name: </label>
		<input  type="text" name="wifi_name"><br>
		
		<input type = "submit" name ="add5" value="Add">
		<br><br>
		<input type = "submit" name ="update5" value="Update">
		<br><br>
		<input type = "submit" name ="delete5" value="Delete">
		<br><br>
	</form>
    <h2>Employee Table</h2>
	<table>
		<tr>
			<th>Employee ID</th>
			<th>Name</th>
			<th>Role</th>
			<th>Phone</th>
			<th>Password</th>

		</tr>
		<?php 
			while($row6=$result6->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $row6['EMP_ID'];?></td>
			<td><?php echo $row6['EMP_NAME'];?></td>
			<td><?php echo $row6['EMP_ROLE'];?></td>
			<td><?php echo $row6['EMP_PHONE'];?></td>
			<td><?php echo $row6['EMP_PASSWORD'];?></td>
		</tr>
		<?php
			}
		?>
	</table>
		<br>
		<div style="text-align: center;">
			<span style="color: #8B2500; font-weight: bold;"><?php echo $empout; ?></span>
		</div>
		<br><br>
		<form action="" method="POST">
		<label for "emp_id">Employee ID: </label>
		<input  type="text" name="emp_id"><br>
	
		<label for "emp_name">Employee Name: </label>
		<input  type="text" name="emp_name"><br>
		
		<label for "emp_role">Employee Role: </label>
		<input  type="text" name="emp_role" ><br>
		
		<label for "emp_phone">Employee Phone: </label>
		<input  type="text" name="emp_phone" >
		<br>

		<label for "emp_pass">Employee Password: </label>
		<input  type="text" name="emp_pass" >
		<br>
		
		<input type = "submit" name ="add6" value="Add">
		<br><br>
		<input type = "submit" name ="update6" value="Update">
		<br><br>
		<input type = "submit" name ="delete6" value="Delete">
		<br><br>
		</form>
    <h2>Customer Table</h2>
	<table>
		<tr>
			<th>Customer ID</th>
			<th>Name</th>
			<th>Phone</th>
			<th>Email</th>

		</tr>
		<?php 
			while($row7=$result7->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $row7['CUS_ID'];?></td>
			<td><?php echo $row7['CUS_NAME'];?></td>
			<td><?php echo $row7['CUS_PHONE'];?></td>
			<td><?php echo $row7['CUS_EMAIL'];?></td>
		</tr>
		<?php
			}
		?>
	</table>
		<br>
		<div style="text-align: center;">
			<span style="color: #8B2500; font-weight: bold;"><?php echo $cusout; ?></span>
		</div>
		<br><br>
		<form action="" method="POST">
		<label for "cus_id">Customer ID *: </label>
		<input  type="text" name="cus_id"><br>
	
		<label for "cus_name">Customer Name *: </label>
		<input  type="text" name="cus_name"><br>
		
		<label for "cus_phone">Customer Phone: </label>
		<input  type="text" name="cus_phone" ><br>
		
		<label for "cus_email">Customer Email: </label>
		<input  type="email" name="cus_email" >
		<br>
		
		<input type = "submit" name ="add7" value="Add">
		<br><br>
		<input type = "submit" name ="update7" value="Update">
		<br><br>
		<input type = "submit" name ="delete7" value="Delete">
		<br><br>
		</form>
	<table>
		<tr>
			<th>Payment ID</th>
			<th>Initial Amount</th>
			<th>Discount Type</th>
			<th>Discount Amount</th>
			<th>Final Amount</th>
			<th>Payment Mode</th>
			<th>Date of Purchase</th>
			<th>Employee Assigned</th>
			<th>Customer ID</th>
			<th>Price ID</th>

		</tr>
		<?php 
			while($row8=$result8->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $row8['PAYMENT_ID'];?></td>
			<td>₱ <?php echo $row8['PAYMENT_INIT_AMT'];?></td>
			<td><?php echo $row8['PAYMENT_DISC_TYPE'];?></td>
			<td><?php echo $row8['PAYMENT_DISC_AMT'];?></td>
			<td>₱ <?php echo $row8['PAYMENT_FINAL_AMT'];?></td>
			<td><?php echo $row8['PAYMENT_TYPE'];?></td>
			<td><?php echo $row8['PAYMENT_DATE'];?></td>
			<td><?php echo $row8['EMP_ID'];?></td>
			<td><?php echo $row8['CUS_ID'];?></td>
			<td><?php echo $row8['PRICE_ID'];?></td>
		</tr>
		<?php
			}
		?>
	<h2>Payment Table</h2>
	</table>
		<br>
		<div style="text-align: center;">
			<span style="color: #8B2500; font-weight: bold;"><?php echo $payout; ?></span>
		</div>
		<br><br>
		<form action="" method="POST">
		<label for "pay_id">Payment ID *: </label>
		<input  type="text" name="pay_id" ><br>
		
		<label for "pay_init_amt">Initial Amount *: </label>
		<input  type="text" name="pay_init_amt" ><br>

		<label for "pay_disc_type">Discount Type: </label>
		<input  type="text" name="pay_disc_type" ><br>
		
		<label for "pay_disc_amt">Discount Amount: </label>
		<input  type="text" name="pay_disc_amt" ><br>

		<label for "pay_final_amt">Final Amount *: </label>
		<input  type="text" name="pay_final_amt" ><br>
		
		<label for "pay_type">Payment Mode *: </label>
		<input  type="text" name="pay_type" ><br>

		<label for "pay_date">Date of Purchase *: </label>
		<input  type="text" name="pay_date" ><br>
		
		<label for "emp_id">Employee ID *: </label>
		<input  type="text" name="emp_id" ><br>
		
		<label for "id_cus">Customer ID *: </label>
		<input  type="text" name="id_cus" ><br>

		<label for "price_id">Price ID *: </label>
		<input  type="text" name="price_id" >
		<br>
		<br>
	
		<input type = "submit" name ="add8" value="Add">
		<br><br>
		<input type = "submit" name ="update8" value="Update">
		<br><br>
		<input type = "submit" name ="delete8" value="Delete">
		<br><br>

	</form>
</body>
</html>
































