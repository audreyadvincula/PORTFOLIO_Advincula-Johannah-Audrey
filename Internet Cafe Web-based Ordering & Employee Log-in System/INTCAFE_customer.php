<?php
session_start(); 
$user = 'root';
$password = '010704';
$database = 'Internet_Cafe';
$servername = 'localhost:3310';

$mysqli = new mysqli($servername, $user, $password, $database);
date_default_timezone_set('Asia/Manila');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
}

$sql1 = "SELECT * FROM Internet_Cafe.Pricing";
$result1 = $mysqli->query($sql1);

$CUSOutput = "";
$CUS_ID = 0;

// Start the session

if (isset($_POST['add'])) {
    $CUS_NAME = trim($_POST['cus_name']);
    $CUS_PHONE = empty($_POST['cus_phone']) ? NULL : trim($_POST['cus_phone']);
	if (!empty($CUS_PHONE) && !is_numeric($CUS_PHONE)) {
		$CUSOutput = "Must enter a valid phone number";
	}
	else {
		$CUS_EMAIL = empty($_POST['cus_email']) ? NULL : trim($_POST['cus_email']);
		if (!empty($CUS_EMAIL) && !filter_var($CUS_EMAIL, FILTER_VALIDATE_EMAIL)) {
		$CUSOutput = "Must enter a valid email address";
		} else {
			$PRICE_ID = trim($_POST['price_id']);
			$PAYMENT_TYPE = trim($_POST['pay_type']);

			if (empty($CUS_NAME) || empty($PRICE_ID) || empty($PAYMENT_TYPE)) {
				$CUSOutput = "Error: Please input correct values for all required fields.";
			} else {
				$stmt = $mysqli->prepare("INSERT INTO Internet_Cafe.CUSTOMER (CUS_NAME, CUS_PHONE, CUS_EMAIL) VALUES (?, ?, ?)");
				$stmt->bind_param("sss", $CUS_NAME, $CUS_PHONE, $CUS_EMAIL);

				if ($stmt->execute()) {
					$stmt = $mysqli->prepare("SELECT CUS_ID FROM Internet_Cafe.CUSTOMER ORDER BY CUS_ID DESC LIMIT 1");
					$stmt->execute();
					$result = $stmt->get_result();
					$row1 = $result->fetch_assoc();

					if ($row1) {
						$_SESSION['CUS_ID'] = $row1['CUS_ID']; // Store CUS_ID in session
						$CUSOutput = "SUMMARY OF ORDER:<br><br>"
								   . "CUSTOMER ID: " . $row1['CUS_ID'] . "<br>"
								   . "CUSTOMER NAME: " . $CUS_NAME . "<br>"
								   . "PAYMENT TYPE: " . $PAYMENT_TYPE . "<br>"
								   . "PRICE ID: " . $PRICE_ID;
					} else {
						$CUSOutput = "Failed to fetch Customer ID: " . $mysqli->error;
					}
				} else {
					$CUSOutput = "Error: Could not add customer. Please try again.";
					error_log("Database error: " . $mysqli->error);
				}
			}
		}
	}
}

elseif (isset($_POST['delete'])) {
    if (isset($_SESSION['CUS_ID'])) { // Retrieve CUS_ID from session
        $CUS_ID = $_SESSION['CUS_ID'];
        $sql = "DELETE FROM Internet_Cafe.CUSTOMER WHERE CUS_ID = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $CUS_ID);

        if ($stmt->execute()) {
            $CUSOutput = "Data deleted from Database successfully!";
            unset($_SESSION['CUS_ID']); // Clear the session variable
        } else {
            $CUSOutput = "Error: " . $mysqli->error;
        }
    } else {
        $CUSOutput = "Error: No customer to delete.";
    }
}


elseif (isset($_POST['update'])) {
    if (isset($_SESSION['CUS_ID'])) { // Retrieve CUS_ID from session
        $CUS_ID = $_SESSION['CUS_ID'];

        $CUS_NAME = trim($_POST['cus_name']);
        $CUS_PHONE = empty($_POST['cus_phone']) ? NULL : trim($_POST['cus_phone']);
        $CUS_EMAIL = empty($_POST['cus_email']) ? NULL : trim($_POST['cus_email']);
		$PRICE_ID = trim($_POST['price_id']);
		$PAYMENT_TYPE = trim($_POST['pay_type']);
		
        $stmt = $mysqli->prepare("SELECT * FROM Internet_Cafe.CUSTOMER WHERE CUS_ID = ?");
        $stmt->bind_param("i", $CUS_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $CUS_NAME = !empty($CUS_NAME) && $CUS_NAME != $row['CUS_NAME'] ? $CUS_NAME : $row['CUS_NAME'];
            $CUS_PHONE = !empty($CUS_PHONE) && $CUS_PHONE != $row['CUS_PHONE'] ? $CUS_PHONE : $row['CUS_PHONE'];
            $CUS_EMAIL = !empty($CUS_EMAIL) && $CUS_EMAIL != $row['CUS_EMAIL'] ? $CUS_EMAIL : $row['CUS_EMAIL'];

            $stmt = $mysqli->prepare("UPDATE Internet_Cafe.CUSTOMER SET CUS_NAME = ?, CUS_PHONE = ?, CUS_EMAIL = ? WHERE CUS_ID = ?");
            $stmt->bind_param("sssi", $CUS_NAME, $CUS_PHONE, $CUS_EMAIL, $CUS_ID);

            if ($stmt->execute()) {
                $CUSOutput = "Data updated successfully! <br><br>SUMMARY OF UPDATE:<br><br>"
                           . "CUSTOMER ID: " . $CUS_ID . "<br>"
                           . "CUSTOMER NAME: " . $CUS_NAME . "<br>"
                           . "PHONE: " . $CUS_PHONE . "<br>"
                           . "EMAIL: " . $CUS_EMAIL . "<br>"
						   . "PRICE ID: " . $PRICE_ID . "<br>"
						   . "PAYMENT TYPE: " . $PAYMENT_TYPE;
            } else {
                $CUSOutput = "Error: " . $mysqli->error;
            }
        } else {
            $CUSOutput = "Error: Customer not found.";
        }
    } else {
        $CUSOutput = "Error: No customer selected for update.";
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
		<div style="text-align: center;">
			<span style="color: #8B2500; font-weight: bold;"><?php echo $CUSOutput; ?></span>
		</div>
<form action="" method="POST">

	<label for "cus_name">Customer Name *: </label>
    <input  type="text" name="cus_name"><br>
	
	<label for "cus_phone">Customer Phone: </label>
    <input  type="text" name="cus_phone" ><br>
	
	<label for "cus_email">Customer Email: </label>
    <input  type="text" name="cus_email" >
    <br>

	<label for "price_id">Price ID *: </label>
    <input  type="text" name="price_id" ><br>

	<label for "pay_type">Payment Type *: </label>
    <input  type="text" name="pay_type" ><br>
   	<br>
	<br>
	
    <input type = "submit" name ="add" value="Add">
    <input type = "submit" name ="update" value="Update">
    <input type = "submit" name ="delete" value="Delete">


</form>
</body>
</html>































