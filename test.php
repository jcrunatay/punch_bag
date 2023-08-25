<?php 

		date_default_timezone_set('America/Montreal');
		$date = date('Y-m-d H:i:s');
		echo "Current DateTime: " . $date;

		echo "<br><br>";
		$date_created = "2022-07-27 18:00:00";
		$date2 = date($date_created);
		echo $date2 . "<br>";

		//date comparison
		if ($date > $date2 ) {
			echo "Corrent";
		}else{
			echo "Wrong";
		}

		//to add time
		$seconds = 30;
		$date_now = "2016-06-02 18:14:30";
		$date_after = date("Y-m-d H:i:s", (strtotime(date($date_now)) + $seconds));
		echo date("Y-m-d H:i:s", (strtotime(date($date_now)) + $seconds));

		if ($date_now > $date_after ) {
			echo "\nCorrect";
		}else{
			echo "\nWrong";
		}


?>




