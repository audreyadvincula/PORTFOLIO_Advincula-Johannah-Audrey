<?php
$user='root';
$password='010704';
$database='Internet_Cafe';
$servername='localhost:3310';

$mysqli= new mysqli($servername,$user,$password,$database);

if($mysqli->connect_error){
	die('Connect Error('.$mysqli->maxdb_connect_errno.')'.$mysqli->maxdb_connect_error);
}


$EMP_NAME = $EMP_ID = $EMP_PASSWORD = "";
$NameError = $IDError = $PassError = "";
$imageUrl = 'bg2.jpg'; 

if (isset($_POST['Login']))
{
	$isValid = true;
	
	if (empty($_POST["emp_id"])) {
		$IDError = "Error Found: EMPLOYEE ID IS REQUIRED";
		$isValid = false;
	} else { 
		$EMP_ID = $_POST['emp_id'];
		$origin="SELECT * FROM Internet_Cafe.EMPLOYEE WHERE EMP_ID='$EMP_ID'";
		$result = mysqli_query($mysqli, $origin);
		if ($result) {
			$row=$result->fetch_assoc();
			if ($row == null) {
			$IDError = "Error found: No Record of Employee ID detected";
			$isValid = false;
		}
		    else {
				if (empty($_POST["emp_name"])) {
					$NameError = "Error Found: EMPLOYEE NAME IS REQUIRED";
					$isValid = false;
				} else {
					$EMP_NAME =  htmlspecialchars($_POST["emp_name"]);
					if($EMP_NAME != $row['EMP_NAME'])
					{
						$NameError = "Error found: EMPLOYEE NAME DOES NOT MATCH EMPLOYEE ID";
						$isValid = false;
					}
				}
				if (empty($_POST["emp_pass"])) {
					$PassError = "Error Found: PASSWORD IS REQUIRED";
					$isValid = false;
				} else {
					$EMP_PASSWORD =  htmlspecialchars($_POST["emp_pass"]);
					if($EMP_PASSWORD != $row['EMP_PASSWORD'])
					{
						$PassError = "Error found: Invalid Password, Please try again ";
						$isValid = false;
					}
				}
			} 
		}


	if ($isValid) {
        if($row['EMP_ROLE'] == 'Manager')
		{
			header("Location: INTCAFE_purpose2.php");
		}
		else {
			header("Location: INTCAFE_purpose.php");
		}
        exit();
	}
	}
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTERNET CAFE DATABASE SYSTEM</title>
	<style>
		body {
			background-image: url('<?php echo $imageUrl; ?>');
			background-size: cover;
			background-repeat: no-repeat;
			line-height: 1.5;
			margin: 20px;
			align-items: flex-end; /
			height: 100vh;
			text-align: center;
			color: #FFFFFF;
			font-family: 'Roboto', Arial, sans-serif; 
		}

		.animated-button {
			background-color: #6D4C41;
			color: white;
			border: none;
			padding: 10px 20px;
			font-size: 16px;
			border-radius: 5px;
			position: relative;
			overflow: hidden;
			cursor: pointer;
		}

		.animated-button::after {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(255, 255, 255, 0.3);
			transform: scale(0);
			transition: transform 0.3s ease;
			border-radius: 5px;
		}

		.animated-button:hover::after {
			transform: scale(1);
		}

		/* Form Styling */
		form {
			background: rgba(255, 255, 255, 0.6); 
			padding: 20px;
			border: 1px solid rgba(255, 255, 255, 0.5); 
			border-radius: 10px;
			max-width: 400px;
			margin: 20px auto;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
			backdrop-filter: blur(400px);
			font-family: 'Roboto', Arial, sans-serif;
			margin-top: 290px;
		}

		form label {
			display: block;
			margin-bottom: 8px;
			font-weight: 500; /* Medium weight */
			color: #333;
			font-size: 16px;
		}

		form button {
			background-color: #6D4C41;
			color: white;
			border: none;
			padding: 10px 15px;
			border-radius: 5px;
			cursor: pointer;
			font-size: 1rem;
			transition: background-color 0.3s ease;
			font-family: 'Roboto', Arial, sans-serif;
		}

		form button:hover {
			background-color: #5A3A32; /* Slightly darker brown */
		}
		.separator {
			color: #292929; /* Change this to your desired font color */
			font-size: 16px; /* Optional: Adjust the font size */
		}
	</style>

</head>
<body>
    <form method="POST" action="">
        <!-- Employee ID -->
        <label for="emp_id"><strong>Employee ID:<strong></label>
        <input type="text" id="emp_id" name="emp_id" value="<?php echo htmlspecialchars($EMP_ID); ?>">
        <br>
		<span style="color: #8B2500;"><strong><?php echo $IDError; ?></strong></span>
        <br>

        <!-- Employee Name -->
        <label for="emp_name"><strong>Employee Name:<strong></label>
        <input type="text" id="emp_name" name="emp_name" value="<?php echo htmlspecialchars($EMP_NAME); ?>">
        <br>
		<span style="color: #8B2500;"><strong><?php echo $NameError; ?></strong></span>
        <br>

        <!-- Password -->
        <label for="emp_pass"><strong>Password:<strong></label>
        <input type="password" id="emp_pass" name="emp_pass" value="<?php echo htmlspecialchars($EMP_PASSWORD); ?>">
		<br>
        <span style="color: #8B2500;"><strong><?php echo $PassError; ?></strong></span>
        <br><br>

        <!-- Submit Button -->
        <button class="animated-button" type="submit" name="Login">Login</button>
    </form>
</body>
</html>












