<!DOCTYPE html>
<html>
<head>
	<title>POST-LOGIN</title>
	<!--Declare the character encoding-->
	<meta charset="UTF-8">
	<?php
		session_start();
		ini_set('display_errors', 'ON');
		
		if(!isset($_SESSION['test_value']))
		{
			echo "You are not logged in yet.<br>";
			echo '<a href = "login.php">Login here.</a>';
			die();
		}
		else
		{	
			//Connect to database and test the connection
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "anderjen-db", "Fd9lqRLTkguN4PNO", "anderjen-db");
			if ($mysqli->connect_errno) 
			{
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
	
			//Prepare the statement, which returns a handle to the statement.
			$correctuser = $_SESSION['username'];
			
			if(isset($_POST['addtask']) && $_POST['addtask'] != NULL)
			{
				$addedtask = $_POST['addtask'];
				$addedtask = mysqli_real_escape_string($mysqli, $addedtask);
				
				if($_POST['addTaskNum'] == "task1")
				{
					if(!($stmt = $mysqli->prepare("UPDATE logins SET task1 = '$addedtask' WHERE username = '$correctuser'")))
					{
						echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
					}
					//Run the statement
					if (!$stmt->execute()) 
					{
						echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
					}
				}
				else if($_POST['addTaskNum'] == "task2")
				{
					if(!($stmt = $mysqli->prepare("UPDATE logins SET task2 = '$addedtask' WHERE username = '$correctuser'")))
					{
						echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
					}
					//Run the statement
					if (!$stmt->execute()) 
					{
						echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
					}
				}
				else
				{
					if(!($stmt = $mysqli->prepare("UPDATE logins SET task3 = '$addedtask' WHERE username = '$correctuser'")))
					{
						echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
					}
					//Run the statement
					if (!$stmt->execute()) 
					{
						echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
					}
				}
				
			}
			
			//Check for remove data
			if(isset($_POST['removetask0']))
			{
				if(!($stmt = $mysqli->prepare("UPDATE logins SET task1 = NULL WHERE username = '$correctuser'")))
				{
					echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
				}
				//Run the statement
				if (!$stmt->execute()) 
				{
					echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				}
			}
			
			if(isset($_POST['removetask1']))
			{
				if(!($stmt = $mysqli->prepare("UPDATE logins SET task2 = NULL WHERE username = '$correctuser'")))
				{
					echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
				}
				//Run the statement
				if (!$stmt->execute()) 
				{
					echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				}
			}
			
			if(isset($_POST['removetask2']))
			{
				if(!($stmt = $mysqli->prepare("UPDATE logins SET task3 = NULL WHERE username = '$correctuser'")))
				{
					echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
				}
				//Run the statement
				if (!$stmt->execute()) 
				{
					echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				}
			}
			
			if ( !($stmt = $mysqli->prepare("SELECT task1, task2, task3 FROM logins WHERE username = '$correctuser'")))
			{
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
		
			//Run the statement
			if (!$stmt->execute()) 
			{
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			if (!$stmt->bind_result($task1, $task2, $task3))
			{
				echo "Error binding result: (" . $stmt->errno . ") " . $stmt->error;
			}
			else 
			{
				//Buffer data
				$stmt->store_result();
				$tasks = array();
				//Gets rows from buffered data
				
				while ($stmt->fetch())
				{	
					//puts rows into an array named users with the key being the username and the password being the value
					if($task1 != null)
						$tasks[0] = $task1;
					if($task2 != null)
						$tasks[1] = $task2;
					if($task3 != null)
						$tasks[2] = $task3;
				}
				//Frees memory allocated for buffer
				$stmt->free_result();
				//Dealocates statement handle
				$stmt->close();
			}
		}
	?>
</head>
<body style = "background-color: #CC99FF">
	<form action = "logout.php" method = "POST" style = "margin-left: 34cm">
	<div style = "line-height: 0; padding: 0; margin-bottom: 0"><input type = "submit" value = "logout" ></div>
	<div style = "line-height: 0; margin-top: 0"><h5><?php echo $correctuser ?></h5></div>
	</form>
	
	<h2 style = "margin-top:1cm; margin-left: 16cm">Your to-do list:</h2>	
	<ul>
		<?php
			if (isset($tasks[0]))
			{
		?>
			<form action = "todolist.php" method = "POST">
			<li style = "margin-left: 14cm">1. 
		<?php 
			echo $tasks[0] 
		?>
			<input type = "submit" name = "removetask0" value = "Remove this item"></input></li></form>
		<?php		
			}
			if (isset($tasks[1]))
			{
		?>
			<form action = "todolist.php" method = "POST">
			<li style = "margin-left: 14cm">2. 
		<?php 
			echo $tasks[1] 
		?>  
			<input type = "submit" name = "removetask1" value = "Remove this item"></input></li></form>
		<?php		
			}	
			if (isset($tasks[2]))
			{
		?>
			<form action = "todolist.php" method = "POST">
			<li style = "margin-left: 14cm">3. 
		<?php 
			echo $tasks[2] 
		?>  
			<input type = "submit" name = "removetask2" value = "Remove this item"></input></li></form>
		<?php
			}
		?>
	</ul>
	<h4 style = "margin-left: 15cm; margin-top: 5cm">Add a to-do list item</h4>
	<!--Text input for item-->
		<form action = "todolist.php" method = "POST" style = "margin-left: 15cm">
			Task to add:<input type = "text" name = "addtask"></input>
	<!--Which position to enter it--> 
		<br>Position of added text: <br>
		&nbsp <input type="radio" name="addTaskNum" value="task1">Task 1<br>
		&nbsp <input type="radio" name="addTaskNum" value="task2">Task 2<br>
		&nbsp <input type="radio" name="addTaskNum" value="task3"checked >Task 3<br>
	<!--Submit new item-->
		<input type = "submit" name = "addsubmit" value = "Submit your add"></input>
	</form>
</body>
<html>
