<?php
///TODO: Reparar la inserción de fechas al añadir adicciones
///TODO: Añadir tiempo exacto en el datetime


///TODO: Modificar div para mostrar cuenta en 
/* Displays user information and some useful messages */
session_start();
require('db.php');
//print_r($user);
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
		//print_r($_SESSION);
		$consulta = "INSERT INTO `addictions`(`id`,`name`,`datetime`) VALUES ('$id','$addName','$addDateTime')";
		$result = $mysqli->query($consulta);
		$_SESSION['info'] = "$addName insertado correctamente";
		
		//$result = $mysqli->query("INSERT INTO `addictions`(`id`, `name`, `datetime`) VALUES (".$_SESSION['id'].",'$addName','$addTime')");
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
	function showForm()
	{
		document.getElementById("addForm").style.display = "block";
		document.getElementById("btn_add_adict").style.display = "none";
		
	}
</script>
 <style>
.addiction
	 {
		text-align: center;
		 border-style:groove;
	 }
	</style>
  <meta charset="UTF-8">
  <title>Welcome <?= $first_name.' '.$last_name ?></title>
  <!--
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>
<script src='http://momentjs.com/downloads/moment.min.js'></script>
<script src="combodate/src/combodate.js"></script>
  <script language="javascript">
      
  </script>
  -->
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
	//Modificar para quienes no tienen nada agregado
			$id = $mysqli->escape_string($_SESSION['id']);
			$consulta = "SELECT * FROM `addictions` WHERE `id` = '$id'";
			$result = $mysqli->query($consulta) or die($mysqli->error());
			//console.log("PRUEBA PRUEBA PRUEBA");
			  //Row será cada fila
                        if($result->num_rows > 0)
                        {
                            while($row = $result->fetch_array())
                          {
                                  $rows[] = $row; 
                                  //Guardamos cada fila en un arreglo de filas
                          }
                           //TODO: modificar 
                           foreach($rows as $row)
                           {

                                   print("<div class='addiction'>");
                                   print($row[1]);
                                   print("<br>");
                                   print($row[2]);
                                   print("<br>");
                                   print("</div>");
                                            print("<br>");

  //				 for($n = 0;$n<3;$n++) //Todas las tablas tienen del 0 al 2 -> ID, Add Name y Datetime
  //			  {
  //
  //			  }
                           }
                        }
			 
			  //print_r($rows[1]);
			  
			  //$data = $result->fetch_assoc();
			  //print_r($data);  
		?>
          <button class="button button-block" name="new_addiction" id="btn_add_adict" onClick="showForm()">Add addiction</button><br>
          <div style="display: none;" id="addForm">
  <form>
      <input type="text" class="add_form" placeholder="Addiction name" name="addName">
      <input type="date" class="add_form" placeholder="Day of last relapse" name="addDate">
      <input type="time" class="add_form" placeholder="Time of relapse" name="addTime">
      <br>
      <!--<button class="button button-block">Save</button>-->
      <input type="submit" class="button button-block" value="Send" formaction="profile.php" formmethod="post">
      <br>

  </form>
         </div>
          <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>
          
    </div>
    


</body>
</html>
