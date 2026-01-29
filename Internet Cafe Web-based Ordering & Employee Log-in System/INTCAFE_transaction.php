<?php

session_start();
$user = 'root';
$password = '010704';
$database = 'Internet_Cafe';
$servername = 'localhost:3310';

$mysqli = new mysqli($servername, $user, $password, $database);
date_default_timezone_set('Asia/Manila');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

function sanitize_input($input) {
    global $mysqli;
    return htmlspecialchars(trim($mysqli->real_escape_string($input)));
}

$sql1 = "SELECT * FROM Pricing";
$result1 = $mysqli->query($sql1);
$sql2 = "SELECT * FROM Computer";
$result2 = $mysqli->query($sql2);
$sql3 = "SELECT * FROM Session";
$result3 = $mysqli->query($sql3);
$sql4 = "SELECT * FROM Room";
$result4 = $mysqli->query($sql4);
$sql5 = "SELECT * FROM Wifi";
$result5 = $mysqli->query($sql5);
$sql8= "SELECT * FROM Internet_Cafe.Payment";
$result8=$mysqli->query($sql8);

$PAYOutput = $SESHOutput = "";

if (isset($_POST['add'])) {
    $CUS_ID = sanitize_input($_POST['id_cus']);
    $PAYMENT_TYPE = sanitize_input($_POST['pay_type']);
    $EMP_ID = sanitize_input($_POST['emp_id']);
    $PRICE_ID = sanitize_input($_POST['price_id']);
    $COMP_NUM = sanitize_input($_POST['comp_num']);
    $PAYMENT_INIT_AMT = sanitize_input($_POST['pay_init_amt']);
    $PAYMENT_FINAL_AMT = sanitize_input($_POST['pay_final_amt']);
    $PAYMENT_DISC_TYPE = isset($_POST['pay_disc_type']) ? sanitize_input($_POST['pay_disc_type']) : null;
    $PAYMENT_DISC_AMT = isset($_POST['pay_disc_amt']) ? sanitize_input($_POST['pay_disc_amt']) : null;

    if (
        empty($CUS_ID) || empty($PAYMENT_INIT_AMT) || empty($PAYMENT_FINAL_AMT) ||
        empty($PAYMENT_TYPE) || empty($EMP_ID) || empty($PRICE_ID) || empty($COMP_NUM)
    ) {
        $PAYOutput = "Error: All fields are required for adding a payment.";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO Payment 
            (PAYMENT_INIT_AMT, PAYMENT_DISC_TYPE, PAYMENT_DISC_AMT, PAYMENT_FINAL_AMT, PAYMENT_TYPE, PAYMENT_DATE, EMP_ID, CUS_ID, PRICE_ID) 
            VALUES (?, ?, ?, ?, ?, CURDATE(), ?, ?, ?)");
        $stmt->bind_param(
            "dssdsssi",
            $PAYMENT_INIT_AMT,
            $PAYMENT_DISC_TYPE,
            $PAYMENT_DISC_AMT,
            $PAYMENT_FINAL_AMT,
            $PAYMENT_TYPE,
            $EMP_ID,
            $CUS_ID,
            $PRICE_ID
        );

        if ($stmt->execute()) {
            $PAYMENT_ID = $mysqli->insert_id; // Get last inserted ID
            $_SESSION['PAYMENT_ID'] = $PAYMENT_ID;
            $PAYOutput = "Payment successfully added. PAYMENT_ID: $PAYMENT_ID";

            $SESSION_START = date('Y-m-d H:i:s');
            $SESSION_END = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($SESSION_START)));
            $stmt = $mysqli->prepare("INSERT INTO Session (SESSION_START, SESSION_END, CUS_ID, COMP_NUM) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssii", $SESSION_START, $SESSION_END, $CUS_ID, $COMP_NUM);

            if ($stmt->execute()) {
                $SESHOutput = "Session created successfully. Starts: $SESSION_START | Ends: $SESSION_END";
            } else {
                $SESHOutput = "Error creating session: " . $mysqli->error;
            }
        } else {
            $PAYOutput = "Error: " . $mysqli->error;
        }
    }
}

if (isset($_POST['delete'])) {
    $PAYMENT_ID = trim($_POST['id_pay']);
    $stmt = $mysqli->prepare("DELETE FROM Payment WHERE PAYMENT_ID = ?");
    $stmt->bind_param("i", $PAYMENT_ID);

	if($stmt->execute())
	{
		$PAYOutput = "Data deleted from Database succcessful!";
	}
	else
	{
		$PAYOutput =  mysqli_error($mysqli);
	}
}

if (isset($_POST['update'])) {
    $PAYMENT_ID = sanitize_input($_POST['id_pay']);
    $PAYMENT_TYPE = sanitize_input($_POST['pay_type']);
    $PRICE_ID = sanitize_input($_POST['price_id']);
    $PAYMENT_INIT_AMT = sanitize_input($_POST['pay_init_amt']);
    $PAYMENT_FINAL_AMT = sanitize_input($_POST['pay_final_amt']);
    $PAYMENT_DISC_TYPE = sanitize_input($_POST['pay_disc_type']);
    $PAYMENT_DISC_AMT = sanitize_input($_POST['pay_disc_amt']);
    $EMP_ID = sanitize_input($_POST['emp_id']);

    if (
        empty($PAYMENT_ID) || empty($PAYMENT_INIT_AMT) || empty($PAYMENT_FINAL_AMT) ||
      empty($PAYMENT_TYPE) || empty($EMP_ID) || empty($PRICE_ID))
    {
       $PAYOutput = "Error: All fields are required for updating a payment.";
    } else {
        $stmt = $mysqli->prepare("UPDATE Payment SET 
            PAYMENT_TYPE = ?, PRICE_ID = ?, PAYMENT_INIT_AMT = ?, PAYMENT_FINAL_AMT = ?, 
            PAYMENT_DISC_TYPE = ?, PAYMENT_DISC_AMT = ?, EMP_ID = ? 
            WHERE PAYMENT_ID = ?");
        $stmt->bind_param(
           "ssiisisi",
            $PAYMENT_TYPE,
            $PRICE_ID,
            $PAYMENT_INIT_AMT,
            $PAYMENT_FINAL_AMT,
            $PAYMENT_DISC_TYPE,
            $PAYMENT_DISC_AMT,
            $EMP_ID,
            $PAYMENT_ID
        );

        if ($stmt->execute()) {
            $PAYOutput = "Payment record updated successfully! PAYMENT_ID: $PAYMENT_ID";
        } else {
            $PAYOutput = "Error: " . $mysqli->error;
        }
    }
}


$mysqli->close();
?>


<!DOCTYPE html>
<html>
<head>
    <style>
        body {
			background-color: #FFF8DC;
			font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #8B8878;
        }

        th, td {
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #EE6A50;
        }

        h2 {
            color: #000000;
        }

        form {
			display: flex;
			flex-direction: column; 
			text-align: left;
			color: #FFFFFF;
			align-items: center;
            background-color: #EE6A50;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            margin: 20px auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
		select {
            margin-bottom: 15px;
            padding: 8px;
            width: 90%;
        }

        input[type="submit"] {
            background-color: #ddd; 
            color: #1A1A1A;
            cursor: pointer;
            padding: 10px;
			width: 60%;

        }

        input[type="submit"]:hover {
            background-color: #fff3f7;
        }
		
        .confirmation {
            background-color: #fff3f7;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
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
    <h2>Payment Table</h2>
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
	</table>
<form action="" method="POST">

    <label for "id_cus">Customer ID *: </label>
    <input  type="text" name="id_cus" ><br>
	
	<label for "comp_num">Computer No. *: </label>
    <input  type="text" name="comp_num"><br>
	
	
    <label for "pay_init_amt">Initial Amount *: </label>
    <input  type="text" name="pay_init_amt" ><br>

    <label for "pay_disc_type">Discount Type: </label>
    <input  type="text" name="pay_disc_type" ><br>
	
	<label for "pay_disc_amt">Discount Amount: </label>
    <input  type="text" name="pay_disc_amt" ><br>

	<label for "pay_final_amt">Final Amount *: </label>
    <input  type="text" name="pay_final_amt" ><br>
	
	<label for "pay_type">Payment Type *: </label>
    <input  type="text" name="pay_type" ><br>

	<label for "emp_id">Employee ID *: </label>
    <input  type="text" name="emp_id" ><br>
	
	<label for "price_id">Price ID *: </label>
    <input  type="text" name="price_id" ><br>
	
	<label for "id_pay">Payment ID *: </label>
    <input  type="text" name="id_pay" ><br>
    <br>
	<span style="color: #8B2500;"><strong><?php echo $SESHOutput; ?></strong></span><br>
	<span style="color: #8B2500;"><strong><?php echo $PAYOutput; ?></strong></span>   
	<br>
	<br>
	
    <input type = "submit" name ="add" value="Add">
    <input type = "submit" name ="delete" value="Delete">
    <input type = "submit" name ="update" value="Update">


</form>
</body>
</html>































