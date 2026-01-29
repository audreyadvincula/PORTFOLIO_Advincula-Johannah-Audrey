<?php 
$imageUrl = 'bg2.jpg'; 

if (isset($_POST['customer'])) {
    header("Location: INTCAFE_customer.php");
    exit();
}

if (isset($_POST['employee'])) {
    header("Location: INTCAFE_login.php");
    exit();
}
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internet Cafe Ordering System</title>
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
			font-family: 'Roboto', Arial, sans-serif; /* Minimalistic font */
		}

		.animated-button {
			background-color: #6D4C41; /* Softer brown to match the table */
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
			background: rgba(255, 255, 255, 0.6); /* More translucent white */
			padding: 20px;
			border: 1px solid rgba(255, 255, 255, 0.5); /* Subtle border */
			border-radius: 10px;
			max-width: 500px;
			margin: 20px auto;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Softer shadow */
			backdrop-filter: blur(10px); /* Subtle background blur */
			font-family: 'Roboto', Arial, sans-serif; /* Clean font */
			margin-top: 320px;
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
        <label>Please indicate whether you are a:</label>
        <button type="submit" name="customer">Customer</button>
        <div class="separator" >-- or a --</div>
        <button type="submit" name="employee">Employee</button>
    </form>
</body>
</html>

