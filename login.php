<!DOCTYPE html>
<html>
<head>
	<title>LOGIN</title>
	<!--Declare the character encoding-->
	<meta charset="UTF-8">
<?php
	session_start();
	if(array_key_exists("username", $_REQUEST) && array_key_exists("password", $_REQUEST))
	{
		//TEST the password
		
		//Grab the values
		$myusername=$_POST['username'];
		$mypassword=$_POST['password']; 
			
		//Connect to database and test the connection
		$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "anderjen-db", "Fd9lqRLTkguN4PNO", "anderjen-db");
		if ($mysqli->connect_errno) 
		{
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
	
		//Prepare the statement, which returns a handle to the statement.
		if ( !($stmt = $mysqli->prepare("SELECT username, password FROM logins") ) ) 
		{
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		
		//Run the statement
		if (!$stmt->execute()) 
		{
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->bind_result($username, $password))
		{
			echo "Error binding result: (" . $stmt->errno . ") " . $stmt->error;
		}
		else 
		{
			//Buffer data
			$stmt->store_result();
			$users;
			//Gets rows from buffered data
			while ($stmt->fetch())
			{
				//puts rows into an array named users with the key being the username and the password being the value
				$users[$username] = $password;
			}
			//Frees memory allocated for buffer
			$stmt->free_result();
			//Dealocates statement handle
			$stmt->close();
		}
	
		//TEST the login.  if its correct, go to postlogin page and set session.  If not correct, set error message and reload page.  
		if (array_key_exists ($myusername, $users) && $mypassword === $users[$myusername])
		{
			$_SESSION['test_value'] = 1;
			$_SESSION['username'] = $myusername;
			?>
</head>
<body>
			<form action = "logout.php" method = "POST" style = "margin-left: 34cm">
				<div style = "line-height: 0; padding: 0; margin-bottom: 0"><input type = "submit" value = "logout" ></div>
				<div style = "line-height: 0; margin-top: 0"><h5><?php echo $myusername ?></h5></div>
			</form>
			<form action = "todolist.php" method = "POST">
				<input type = "submit" value = "View your to do list" style = "margin-left: 15cm"></input>
			</form>
			<?php
		}
		else
		{
			?>
			Incorrect login information.  Please login again.
			<form action = "login.php" method = "POST">
				<div> 
					username: <input type = "text" name = "username"></input>
				</div>
				<div>
					password: <input type = "password" name = "password"></input>
				</div>
				<div>
					<input type = "submit" value = "login"></input>
				</div>
			</form>
			<?php
		}
	}
	else
	{
?>
		<form action = "login.php" method = "POST">
			<div> 
				username: <input type = "text" name = "username"></input>
			</div>
			<div>
				password: <input type = "password" name = "password"></input>
			</div>
			<div>
				<input type = "submit" value = "login"></input>
			</div>
		</form>
<?php
	}
?>
</body>
</html>
