
<div class="create">
	<div class="fields">
		<form method="post" action="" enctype="multipart/form-data">
			<label class="label">Name:</label> <input type="text" name="name"><?= $error_name ?? "" ?><br>
			<label class="label">Avatar:</label> <input type="file" name="avatar"><?= $err_avatar ?? "" ?><br>
			<input type="submit" value="Create" name="create" class="submit_btn"  <?php if(isset($_SESSION['fightStarted']) || isset($_SESSION['fightFinished']) ){echo 'disabled';}?> >
		</form>
	</div>
</div>















