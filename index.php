<?php include('core.php');

/*if(isset($_POST["ready"])) {
	if (isset($_POST['carac_names'])) {
		echo "combobox value: " .  $_POST['carac_names'];
	}else{
		echo "Please select a character";
	}
}*/

?>
<html>
<head>
	<title>Punch Bag</title>
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/successMessage.css" />
	<link rel="stylesheet" href="css/no_punching.css" />
	<link rel="stylesheet" href="css/versus.css" />
	<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'/>
	<link href='http://fonts.googleapis.com/css?family=Fruktur' rel='stylesheet' type='text/css'/>

	<!-- Latest compiled and minified CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Latest compiled JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<h2>Punch Bag</h2>
	<?php include('create_caract.php');?>

	<div class="content">
		<?php if((isset($_SESSION['fightStarted']) || isset($_SESSION['fightFinished']))):?>
		<div class="progress" style="height:35px;width:350px;float: left;">
			  <div class="progress-bar pbar" role="progressbar" aria-valuenow="50"
			  aria-valuemin="0" aria-valuemax="50" style="width:<?= $p1hbar ?? 100 ?>%">
			  </div>
			  <p class="pbar_name"><?php if(isset($_SESSION['user'])){
				echo $_SESSION['user']->getName() . "(" .$_SESSION['user']->getHp() . ")" ;
			} ?></p>
		</div>
		<div class="progress" style="height:35px;width:350px;float: right;">
			  <div class="progress-bar pbar" role="progressbar" aria-valuenow="40"
			  aria-valuemin="0" aria-valuemax="50"  style="width:<?= $p2hbar ?? 100 ?>%">
			  </div>
			  <p class="pbar_name"><?php if(isset($_SESSION['user2'])){
				echo $_SESSION['user2']->getName() . "(" .$_SESSION['user2']->getHp() . ")" ;
			} ?></p>
			</div>
		<?php endif;?>	
		<div class="left_pan">
			<!--p class="rotate">mushu123</p-->
			<img src="<?php if(isset($_SESSION['user'])){
				echo 'upload/'.$_SESSION['user']->getAvatar();
			}else{
				echo 'images/default.jpg';
			}?>"/>



			<form method="post" action="">
				<select class="carac_names" name="carac_names"  <?php if(isset($_SESSION['fightStarted']) || isset($_SESSION['fightFinished']) ){echo 'disabled';}?>>
					<option value="test" selected disabled>Choose Character</option>

				<!-- ADD THE CHARACTERS NAMES FROM THE DATABASE TINTO THE COMBOBOX ... set id as a value to be use in getting the character from the database --> 		
				<?php 
					$characters = $dbManager->Get_All_Characters();
					for ($i=0; $i < count($characters) ; $i++) {
						echo '<option value = "'. $characters[$i]->getId() . '">' . $characters[$i]->getName()  . '</option>';
					}
					

				?>
				</select>
    			<input type="submit" value="Ready" name="ready" class="carac_names" <?php if(isset($_SESSION['fightStarted']) || isset($_SESSION['fightFinished']) ){echo 'disabled';}?>>
    			<br>
				<span class="ready-err"><?= $readyErr ?? ""?></span>
			</form>
		</div>
		<div class="between">
			<form method="post">

				<input type="submit" value="" name="punch_btn1" class="punch_btn1" <?php if(!isset($_SESSION['fightStarted'])){echo 'disabled';}?>>
			</form>	
			<form method="post">

				<input type="submit" value="" name="punch_btn2" class="punch_btn2" <?php if(!isset($_SESSION['fightStarted'])){echo 'disabled';}?>>
			</form>	
		</div>
		<div class="right_pan">
			<!--p class="rotate">mushu123</p-->
			<img src="<?php if(isset($_SESSION['user2'])){
				echo 'upload/'.$_SESSION['user2']->getAvatar();
			}else{
				echo 'images/default.jpg';
			}?>"/>
			<form method="post" action="">
				<select class="carac_names" name="carac_names2" <?php if(isset($_SESSION['fightStarted']) || isset($_SESSION['fightFinished']) ){echo 'disabled';}?>>
					<option value="test" selected disabled>Choose Character</option>

				<!-- ADD THE CHARACTERS NAMES FROM THE DATABASE TINTO THE COMBOBOX ... set id as a value to be use in getting the character from the database ...  --> 		
				<?php 
					$characters = $dbManager->Get_All_Characters();
					for ($i=0; $i < count($characters) ; $i++) {
						echo '<option value = "'. $characters[$i]->getId() . '">' . $characters[$i]->getName()  . '</option>';
					}
					
				?>
				</select>
    			<input type="submit" value="Ready" name="ready2" class="carac_names" <?php if(isset($_SESSION['fightStarted']) || isset($_SESSION['fightFinished']) ){echo 'disabled';}?>>
    			<br>
				<span class="ready-err"><?= $readyErr2 ?? ""?></span>
			</form>	
		</div>
		<div class="start-Div">
			<br>
			<form method="post">
				<input class="btn btn-success" type="submit" value="Start Fight" name="start_fight" <?php if(isset($_SESSION['fightStarted']) || isset($_SESSION['fightFinished']) ){echo 'disabled';}?>>
				<input class="btn btn-warning" type="submit" value="Rematch"  name="rematch"  <?php if(!isset($_SESSION['fightFinished']) ){echo 'disabled';}?>>
				<input class="btn btn-info" type="submit" value="Change Character"  name="change_character"  <?php if(!isset($_SESSION['fightFinished']) ){echo 'disabled';}?>>
				<input class="btn btn-danger" type="submit" value="Quit"  name="quit"  <?php if(!isset($_SESSION['fightStarted']) ){echo 'disabled';}?>>		
			</form>
			<br>
			<?php if($errStart): ?>
			<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
			  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
			    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
			  </symbol>
			</svg>
					<div class="alert alert-danger d-flex align-items-center" role="alert">
						  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
						  <div class="text-center">
						    <?= $errStartMessage ?? ""?>
						  </div>
				    </div>	
			<?php endif; ?>	
		</div>

	</div>

	<?php include('get_all_caracters.php');?>


	<!-- SUCCESS MESSAGE FOR ADDING A NEW CHARACTER -->
	<?php if ($successfulAdd == true) : ?>
	<div class="container bg-secondary div-container">
		<div class="container bg-primary">
				<div class="popup-bg">
						<div class="popup-content text-center">
									<div class="form-div">
									<form method="post">
										<input type="submit" class="btn-close float-end" value=" " name="x"></input>	
									</form>
									<!--img src="images/success.gif" class="popup-img"-->
									</div>
									<h3 class="display-6">Character Successfully Added</h3>
									<p class="lead">You can now check the new character you created</p>							
						</div>			
				</div>
		</div>
	</div>

	<?php endif; ?>

	<!-- Show an error message if user cant punch for player 1-->
	<div class="container bg-secondary div-container" <?= $errPunching ?? 'style="display: none;"'?>>
				<div class="popup-bg">
						<div class="popup-content text-center">
									
									<form method="post">
										<input type="submit" class="btn-close float-end" value=" " name="x1">
									</form>
									<img src="images/no_punching.jpg" class="popup-img">
									
									<h3 class="display-6">Unable to Attack !</h3>
									<p class="lead">There is 5 seconds cooldown every third combo hit </p>							
						</div>
				</div>
	</div>

	<!-- Show an error message if user cant punch for player 2-->
	<div class="container bg-secondary div-container" <?= $errPunching2 ?? 'style="display: none;"'?>>
				<div class="popup-bg">
						<div class="popup-content text-center">
									
									<form method="post">
										<input type="submit" class="btn-close float-end" value=" " name="x1">
									</form>
									<img src="images/no_punching.jpg" class="popup-img">
									
									<h3 class="display-6">Unable to Attack ! </h3>
									<p class="lead">There is 60 seconds cooldown every third hit </p>							
						</div>
				</div>
	</div>

	<!-- Show versus popup -->
	<div class="container bg-secondary div-container" <?= $display_Vs_Popup ?? 'style="display: none;"'?>>
				<div class="popup-bg">
						<div class="vs-popup-content text-center">						
									<div class="div-block"> 
										<h2 class="display-5  ms-8 text-center" style="color:yellow;" >Let the Battle Begin !!</h2>
										<img src="<?php echo 'upload/' . $_SESSION['user']->getAvatar()?>" class="player1-avatar float-start">
										<img src="<?php echo 'upload/' . $_SESSION['user2']->getAvatar()?>" class="player2-avatar float-end">
									</div>
									<form method="post">
										<input type="submit" class="vs-close" value=" Continue->" style="background-color:orange;">
									</form>
						</div>
				</div>
	</div>

	<!-- Show winner popup -->
	<div class="container bg-secondary div-container" <?php if (isset($_SESSION['winner'])){
															echo 'style="display: block;"';
														}else{
															echo 'style="display: none;"';
														} ?> >
				<div class="popup-bg">
						<div class="div-Winner text-center">						
									<div class="div-block"> 	
										<img src="<?php echo 'upload/' . $_SESSION['winner']->getAvatar() ?>" class="player1-avatar float-start ms-5">
										<h2 class="display-5 p-3  text-center" style="color:yellow;" ><?php echo $_SESSION['winner']->getName() ?> Wins The Fight !!!</h2>
									</div>
									<form method="post">
										<input type="submit" name='x1' class="btn-close winnerpopUp-close" value=" " style="background-color:orange;">
									</form>
						</div>
				</div>
	</div>
	
</body>
</html>