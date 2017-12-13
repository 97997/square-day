<?php
//Sesion y conexion
session_start();
require('db.php');
//Si el usuario añadió una adicción 
if(isset($_POST['addName']) || isset($_POST['addDate']) || isset($_POST['addTime']))
{
	if($_POST['addName'] == "" || $_POST['addDate'] == "" || $_POST['addTime'] == "")
	{
	$_SESSION['info'] = "You can't leave this empty";
	}	
	else
	{
		$addName = $mysqli->escape_string($_POST['addName']);
		$addDate = $mysqli->escape_string($_POST['addDate']);
		$addTime = $mysqli->escape_string($_POST['addTime']);
                echo($addDate." ".$addTime);
                $addDateTime = $addDate." ".$addTime;
		$id = $mysqli->escape_string($_SESSION['id']);
		$consulta = "INSERT INTO `addictions`(`id`,`name`,`datetime`) VALUES ('$id','$addName','$addDateTime')";
		$result = $mysqli->query($consulta);
		$_SESSION['info'] = "$addName insertado correctamente";
	}
}

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  header("location: error.php");    
}
else {
    // Makes it easier to read
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $active = $_SESSION['active'];
}
?>
<!DOCTYPE html>
<html >
<head>
<script language="javascript">
	//Muestra el formulario con el usuario da click en add addiction
	function showForm()
	{
		document.getElementById("addForm").style.display = "block";
		document.getElementById("btn_add_adict").style.display = "none";
		
	}
		function hideForm()
	{
		document.getElementById("addForm").style.display = "none";
		document.getElementById("btn_add_adict").style.display = "block";
		
	}
	function showEditForm(counter)
	{
		var str = "edit_form" + counter;
		console.log(str);
		document.getElementById(str).style.display = "block";
	}

	//Esta función crea el cronometro, datetime es la fecha desde el ultimo relapse, y clockNum el numero de cronometro (normalmente hay multiples cronometro)
function createTimer(datetime,clockNum)
	{
		console.log("Timer creado " + datetime + " "+clockNum);
					var timer;

			var compareDate = new Date(datetime);

			timer = setInterval(function() {
			  timeBetweenDates(compareDate);
			}, 1000);

			function timeBetweenDates(toDate) {
			  var dateEntered = toDate;
			  var now = new Date();
			  var difference = now.getTime() - dateEntered.getTime();

			  if (difference <= 0) {

				// Timer done
				clearInterval(timer);

			  } else {

				var seconds = Math.floor(difference / 1000);
				var minutes = Math.floor(seconds / 60);
				var hours = Math.floor(minutes / 60);
				var days = Math.floor(hours / 24);

				hours %= 24;
				minutes %= 60;
				seconds %= 60;

				$("#days".concat(clockNum)).text(days);
				$("#hours".concat(clockNum)).text(hours);
				$("#minutes".concat(clockNum)).text(minutes);
				$("#seconds".concat(clockNum)).text(seconds);
			  }
			}
	}

</script>
 <style>
.addiction
	 {
		text-align: center;
		 border-style:groove;
	 }
	 
	 body {
  background: #f5f5f5;
}
	 .timeLabel{
		 color: aqua;
	 }
	.timer {
	  font-family: Arial, sans-serif;
	  font-size: 20px;
	  color: #999;
	  letter-spacing: -1px;
	}
	.timer span {
	  font-size: 60px;
	  color: #333;
	  margin: 0 3px 0 15px;
	}
	.timer span:first-child {
	  margin-left: 0;
	}
	 .btn_add_edit
	 {
		 color: #1AB188;
		 background-color: white;
		 
	 }
  
	</style>
  <meta charset="UTF-8">
  <title>Welcome <?= $first_name.' '.$last_name ?></title>
  <!-- Se requiere de jquery para ejecutar la funciones de los cronometros -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>
<script src='http://momentjs.com/downloads/moment.min.js'></script>
<script src="combodate/src/combodate.js"></script>
  <script language="javascript">
      
  </script>
  
  <?php include 'css/css.html'; ?>
</head>

<body>
  <div class="form">

          <h1>Welcome <?= $first_name.' '.$last_name?></h1>
          
          <p>
          <?php 
     
          // Display message about account verification link only once
          if ( isset($_SESSION['message']) )
          {
              echo $_SESSION['message'];
              
              // Don't annoy the user with more messages upon page refresh
              unset( $_SESSION['message'] );
          }
			  //Muestra posibles errores al añadir adicciones
			  if(	isset($_SESSION['info'])	)
			  {
				  echo(
					 '<h1 style="color:red;">'. $_SESSION['info'].'</h1>'
				  );
			  }
          
          ?>
          </p>
          
          <?php
          
          // Keep reminding the user this account is not active, until they activate
          if ( !$active ){
              echo
              '<div class="info">
              Account is unverified, please confirm your email by clicking
              on the email link!
              </div>';
          }
          
          ?>
          
          <h2><?php echo $first_name.' '.$last_name; ?></h2>
          <p><?= $email ?></p>
          <?php
	$date = date('Y-m-d');
	$time = date('H:i');
			  //var_dump($date);
	//Modificar para quienes no tienen nada agregado
			$id = $mysqli->escape_string($_SESSION['id']);
			$consulta = "SELECT * FROM `addictions` WHERE `id` = '$id'";
			$result = $mysqli->query($consulta) or die($mysqli->error());
			  //Row será cada fila
                        if($result->num_rows > 0)
                        {
                            while($row = $result->fetch_array())
                          {
                                  $rows[] = $row; 
                                  //Guardamos cada fila en un arreglo de filas
                          }
                           //TODO: modificar 
							$counter = 0;
                           foreach($rows as $row)
                           {
							   		$counter += 1;
                                   print("<div class='addiction'><p>");
                                   print($row[1]);
                                   print("<br>");
							   		print("Addiction added: ");
                                   print($row[2]);
							   //Aqui debe de estar el contador
							   		print("
										<div id='timer{$counter}'>
									  <span id='days{$counter}'></span><span style='color:white'> days</span>
									  <span id='hours{$counter}'></span><span style='color:white'> hours</span>
									  <span id='minutes{$counter}'></span><span style='color:white'> minutes</span>
									  <span id='seconds{$counter}'></span><span style='color:white'> seconds</span>
									</div>
									<script>
									createTimer('{$row[2]}','{$counter}');
									</script>
									");
                                   print("<br><p>");
							   //Edición de vicios
							   		$class = "edit_form".$counter;
							   	   print("<button name='edit' id='btn_edit_add{$counter}' class='edit_form{$counter}' onClick='showEditForm($counter)'>
								   Edit time</button>");
							   //print($date);
							   print("
							   <div id='edit_form{$counter}' style='display:none;'>
		<input type='text' class='edit_form{$counter}' placeholder='Addiction name' name='updAddName' value='{$row[1]}'>
      <input type='date' class='edit_form{$counter}' placeholder='Day of last relapse' name='updAddDate' value=$date>
      <input type='time' class='edit_form{$counter}' placeholder='Time of relapse' name='updAddTime' value=$time>
	        <input type='submit' class='button button-block' value='Update' formaction='profile.php' formmethod='post'>
							
								</div>
	  ");

                                   print("</div>");
                                            print("<br>");

                           }
                        }
		?>
          <button class="button button-block" name="new_addiction" id="btn_add_adict" onClick="showForm()">Add addiction</button><br>
          <div style="display: none;" id="addForm">
  <form>
      <input type="text" class="add_form" placeholder="Addiction name" name="addName">
      <input type="date" class="add_form" placeholder="Day of last relapse" name="addDate">
      <input type="time" class="add_form" placeholder="Time of relapse" name="addTime">
      <br>
      <input type="submit" class="button button-block" value="Send" formaction="profile.php" formmethod="post">
      
	  <input type="submit" class="button button-block" value="Cancel" onClick="hideForm()"></button>

      <br>

  </form>
         </div>
          <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>
          
    </div>

</body>
</html>
