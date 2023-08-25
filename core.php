    <?php 

    spl_autoload_register(function ( $class_name ){
    include $class_name . '.class.php';
    });

    session_start();
    $dbManager = new Db_Manager();
    /* $cs = $dbManager->Get_All_Characters();

    for ($i=0; $i < count($cs) ; $i++) { 
            echo $cs[$i]->getName();
            echo "<br>";
    } */
    $successfulAdd = false;
    // ===== IF CREATE BUTTON IS CLICKED  ... =====
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $err_counter = 0;

        //validate name ...
        if (trim($name) != "") {
            if (is_numeric($name)){
            $err_counter++;
            $error_name = '<span style="font-size:12px;color:red;margin-left:75px;">Name cannot be number</span>';
            }else{
            if (strlen(trim($name)) < 2 || strlen(trim($name)) > 10) {
                $err_counter++;
                $error_name = '<span style="font-size:12px;color:red;margin-left:75px;">Must be  2-10 characters </span>';
            }else{
                //check if name already exist
                if ($dbManager->Verify_name($name)) {
                    $err_counter++;
                    $error_name = '<span style="font-size:12px;color:red;margin-left:75px;">Name already exist </span>';
                }
            }
            }
        }else{
            $err_counter++;
            $error_name = '<span style="font-size:12px;color:red;margin-left:75px;">Name cannot be empty</span>';
        }

        //===== validate avatar ... =====
            if ($_FILES['avatar']['error'] != UPLOAD_ERR_NO_FILE) {     
            $target_dir = "upload/";
            $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["avatar"]["tmp_name"]);
            if($check !== false){

                //Check if file already exists ...
                if(file_exists($target_file)){
                    $err_avatar = '<span style="font-size:12px;color:red;margin-left:75px;">File already exist</span>';
                    $err_counter++;         
                }
    /*
                // Check file size ...
                if($_FILES["avatar"]["size"] > 500000) {
                    $err_avatar = '<span style="font-size:12px;color:red;margin-left:75px;">Sorry, File is too large</span>';
                    $err_counter++; 
                }
    */

                $acceptableTypes = ["jpg", "png", "jpeg", "gif", "tiff", "webm"];

                // ===== Allow certain file formats  ... =====
                if(!in_array($imageFileType, $acceptableTypes)){
                    $err_avatar = '<span style="font-size:12px;color:red;margin-left:75px;">Only jpg, jpeg, png, Gif files are allowed</span>';
                    $err_counter++; 
                }

            }else{
                $err_avatar = '<span style="font-size:12px;color:red;margin-left:75px;">File is not an image</span>';
                $err_counter++;
            }
        }else{
            $err_avatar= '<span style="font-size:12px;color:red;margin-left:75px;">Please upload a file</span>';
            $err_counter++;
        }

        // ===== check err_counter if zero then save the new character created to database ... =====
        if ($err_counter == 0) {
            $newCharacter = new character(array("name"=>$_POST['name'],"avatar"=>basename($_FILES['avatar']['name'])));

            //call db manger add character function to add new character to the database .. 
            $dbManager->Add_Character( $newCharacter);

            $successfulAdd = true;
        }

    }

    // ===== left pan ready or first player ready button .. =====
    if (isset($_POST['ready'])) {
            unset($_SESSION['user']); 
        if (isset($_POST['carac_names'])) {
            $_SESSION['user'] = $dbManager->Get_Character($_POST['carac_names']);
        }else{
            $readyErr = 'Select a character before ready up';   
            }  
    }


    // ===== right pan ready or second player ready button .. ===== 
    $errStart = false;
    if (isset($_POST['ready2'])) {
                unset($_SESSION['user2']); 
        if (isset($_POST['carac_names2'])) {
            $_SESSION['user2'] = $dbManager->Get_Character($_POST['carac_names2']);
        }else{
            $readyErr2 = 'Select a character before ready up';   
            }  
    }

    // create action for x button in success pop up message after adding ... 
    if (isset($_POST['x'])) {
            $successfulAdd = false;
    }

    // ===== create action for start fight button ... =====
    if (isset($_POST['start_fight'])) {
        if (isset($_SESSION['user']) AND isset($_SESSION['user2'])) {
            if ($_SESSION['user']->getId() == $_SESSION['user2']->getId()) {
                $errStart = true;
                $errStartMessage = "The same character cannot be selected by both players";
            }else{
            $_SESSION['fightStarted'] = true;
            $display_Vs_Popup = 'style="display: block;"';
            }
        }else{
            $errStart = true;
            $errStartMessage = "For the game to be played, two players are required";
        }
    }

    //  ======== Create action for Punch Button1 ==========

    $_SESSION['p1_counter'] = isset($_SESSION['p1_counter']) ? $_SESSION['p1_counter'] : 0;
    if (isset($_POST['punch_btn1'])) {
        $_SESSION['p1_counter']++;

        if ( $_SESSION['p1_counter'] <= 3) {
            //call punch button1 function ...
            btn1_punch();

            //multiply by 2 to get the exact width of progress bar (health bar) ...
            $p1hbar= ($_SESSION['user']->getHp()*2);  
            $p2hbar = ($_SESSION['user2']->getHp()*2); 

            //if condition if its the third punch then update the last combo time ...
            if ($_SESSION['p1_counter'] == 3) {
                date_default_timezone_set('America/Montreal');
                $date_now = date('Y-m-d H:i:s');
                $dbManager->Update_last_combo_time($_SESSION['user']->getId(),$date_now);
            }

        }elseif ($_SESSION['p1_counter'] > 3) {// if punch button is clicked during the 60 secs cooldown .. show  a message that player cant punch yet ..

            //multiply by 2 to get the exact width of progress bar (health bar) ...
            $p1hbar= ($_SESSION['user']->getHp()*2);  
            $p2hbar = ($_SESSION['user2']->getHp()*2); 
        
        // check if 60secs have benn passed ...

        //set montreal time
        date_default_timezone_set('America/Montreal');
        $date_now = date('Y-m-d H:i:s');
        
        //get the last combo hit time ... 
        $last_combo_time = $dbManager->Get_last_combo_time_elapse($_SESSION['user']->getId());

        //seconds to be added to the time ...
        $seconds = 5;

        //time for the punch to be available ...
        $punchtime_available =  date("Y-m-d H:i:s", (strtotime(date($last_combo_time['Last_Combo_Hit'])) + $seconds));

        // make an if condition to check time if 60 seconds have been passed or not ...

        if ($date_now > $punchtime_available) {
            //call punch button1 function
            btn1_punch();

            //multiply by 2 to get the exact width of progress bar (health bar) ...
            $p1hbar= ($_SESSION['user']->getHp()*2);  
            $p2hbar = ($_SESSION['user2']->getHp()*2); 

            //if condition if its the third punch then update the last combo time ...
            if ($_SESSION['p1_counter'] == 3) {
                date_default_timezone_set('America/Montreal');
                $date_now = date('Y-m-d H:i:s');
                $dbManager->Update_last_combo_time($_SESSION['user']->getId(),$date_now);
            }

            //reset punch count to 1 .. 
            $_SESSION['p1_counter'] = 1;
        }else{
            $errPunching = 'style="display: block;"';

            //multiply by 2 to get the exact width of progress bar (health bar) ...
            $p1hbar= ($_SESSION['user']->getHp()*2);  
            $p2hbar = ($_SESSION['user2']->getHp()*2); 
        } 


            
        }// end of else if for the punch counter

    }//end of the condition if punch_btn1 is clicked


    //  ======== Create action for Punch Button2 ==========
    $_SESSION['p2_counter'] = isset($_SESSION['p2_counter']) ? $_SESSION['p2_counter'] : 0;
    if (isset($_POST['punch_btn2'])) {
        $_SESSION['p2_counter']++;

        if ( $_SESSION['p2_counter'] <= 3) {
            //call punch button1 function
            btn2_punch();

            //multiply by 2 to get the exact width of progress bar (health bar);
            $p1hbar= ($_SESSION['user']->getHp()*2);  
            $p2hbar = ($_SESSION['user2']->getHp()*2); 

            //if condition if its the third punch then update the last combo time
            if ($_SESSION['p2_counter'] == 3) {
                date_default_timezone_set('America/Montreal');
                $date_now = date('Y-m-d H:i:s');
                $dbManager->Update_last_combo_time($_SESSION['user2']->getId(),$date_now);
            }

        }elseif ($_SESSION['p2_counter'] > 3) {// if punch button is clicked during the 60 secs cooldown .. show  a message that player cant punch yet ...

            //multiply by 2 to get the exact width of progress bar (health bar) ...
            $p1hbar= ($_SESSION['user']->getHp()*2);  
            $p2hbar = ($_SESSION['user2']->getHp()*2); 
        
        // check if 60secs have benn passed  ...

        //set montreal time
        date_default_timezone_set('America/Montreal');
        $date_now = date('Y-m-d H:i:s');
        
        //get the last combo hit time ... 
        $last_combo_time = $dbManager->Get_last_combo_time_elapse($_SESSION['user2']->getId());

        //seconds to be added to the time
        $seconds = 5;

        //time for the punch to be available
        $punchtime_available =  date("Y-m-d H:i:s", (strtotime(date($last_combo_time['Last_Combo_Hit'])) + $seconds));

        // make an if condition to check time if 60 seconds have been passed or not ...

        if ($date_now > $punchtime_available) {
            //call punch button2 function
            btn2_punch();

            //multiply by 2 to get the exact width of progress bar (health bar) ...
            $p1hbar= ($_SESSION['user']->getHp()*2);  
            $p2hbar = ($_SESSION['user2']->getHp()*2); 

            //if condition if its the third punch then update the last combo time ...
            if ($_SESSION['p2_counter'] == 3) {
                date_default_timezone_set('America/Montreal');
                $date_now = date('Y-m-d H:i:s');
                $dbManager->Update_last_combo_time($_SESSION['user2']->getId(),$date_now);
            }

            //reset punch count to 1 .. 
            $_SESSION['p2_counter'] = 1;
        }else{
            $errPunching2 = 'style="display: block;"';

            //multiply by 2 to get the exact width of progress bar (health bar) ...
            $p1hbar= ($_SESSION['user']->getHp()*2);  
            $p2hbar = ($_SESSION['user2']->getHp()*2); 
        } 

            
        }// end of else if for the punch counter

    }//end of the condition if punch_btn1 is clicked


    // ====== create action for x button in success pop up message after adding ... ======
    if (isset($_POST['x1'])) {
            //get the hp and multiply by 2 to get the exact width since the maxiximum value is 50 ....
            //to show the health bar of the player
            unset($_SESSION['winner']);
            $p1hbar= ($_SESSION['user']->getHp()*2);  
            $p2hbar = ($_SESSION['user2']->getHp()*2); 
    }

    // ===== action for quit button ... =====

    if (isset($_POST['quit'])) {

        //reset hp
        $_SESSION['user']->setHp(50);
        $_SESSION['user2']->setHp(50);

        //call class dbManager Update Function to update the character
        $dbManager->Update_Character($_SESSION['user']);
        $dbManager->Update_Character($_SESSION['user2']);

        unset($_SESSION['user']);
        unset($_SESSION['user2']);
        unset($_SESSION['fightStarted']);
        unset( $_SESSION['p1_counter']);
        unset( $_SESSION['p2_counter']); 

    }

    // ===== action for quit button ... =====
    if (isset($_POST['change_character'])) {

        //reset hp
        $_SESSION['user']->setHp(50);
        $_SESSION['user2']->setHp(50);

        //call class dbManager Update Function to update the character
        $dbManager->Update_Character($_SESSION['user']);
        $dbManager->Update_Character($_SESSION['user2']);

        unset($_SESSION['user']);
        unset($_SESSION['user2']);
        unset($_SESSION['fightStarted']);
        unset( $_SESSION['p1_counter']);
        unset( $_SESSION['p2_counter']); 
        unset( $_SESSION['fightFinished']); 

    }

        // ===== action for quit button ... =====
    if (isset($_POST['rematch'])) {

        //reset hp
        $_SESSION['user']->setHp(50);
        $_SESSION['user2']->setHp(50);

        //call class dbManager Update Function to update the character
        $dbManager->Update_Character($_SESSION['user']);
        $dbManager->Update_Character($_SESSION['user2']);

        unset($_SESSION['fightStarted']);
        unset( $_SESSION['p1_counter']);
        unset( $_SESSION['p2_counter']); 
        unset( $_SESSION['fightFinished']); 

    }

    //  ===== function for the action of clicking punch_btn1 ... =====
    function btn1_punch(){

        $dbManager = new Db_Manager();
        //save current hp of user to a variable
        $player1currentHp = $_SESSION['user']->getHp();
        $player2currentHp = $_SESSION['user2']->getHp();

        //get hitcount and increment everypunch
        $p1HitCount = $_SESSION['user']->getHitCount();
        $p1HitCount++;

        //user2 == -5 for taking a punch and player 1 == +3 for hitting user2 
        $player2currentHp-=5;
        $player1currentHp+=3;

        //if condition to set hp maximum til 50 only ..
        if ($player1currentHp > 50) {
            $player1currentHp = 50;
        }

        //if condition to check if player 2 hp has been down to 0 hp 
        if ($player2currentHp <= 0) {
                $player2currentHp = 0;
                $_SESSION['fightFinished'] = true;
                // unset the session started when someone won the game already
                unset($_SESSION['fightStarted']);
                $_SESSION['winner'] = $_SESSION['user']; 

        } 

        //set player 1 hp and player 2 hp .. also set the hit count of player1(the one who did the punch)
        $_SESSION['user']->setHp($player1currentHp);
        $_SESSION['user2']->setHp($player2currentHp);
        $_SESSION['user']->setHitCount($p1HitCount);

        //call class dbManager Update Function to update the character
        $dbManager->Update_Character($_SESSION['user']);
        $dbManager->Update_Character($_SESSION['user2']);
    }

    // ===== function for the action of clicking punch_btn2 ... =====
    function btn2_punch(){

        $dbManager = new Db_Manager();
        //save current hp of user to a variable
        $player1currentHp = $_SESSION['user']->getHp();
        $player2currentHp = $_SESSION['user2']->getHp();

        //get hitcount and increment everypunch
        $p2HitCount = $_SESSION['user2']->getHitCount();
        $p2HitCount++;

        //user2 == -5 for taking a punch and player 1 == +3 for hitting user2 
        $player1currentHp-=5;
        $player2currentHp+=3;

        //if condition to set hp maximum til 50 only ..
        if ($player2currentHp > 50) {
            $player2currentHp = 50;
        }

        //if condition to check if player 2 hp has been down to 0 hp 
        if ($player1currentHp <= 0) {
                $player1currentHp = 0;
                $_SESSION['fightFinished'] = true;
                // unset the session['fightStarted'] started when someone won the game already
                unset($_SESSION['fightStarted']);
                $_SESSION['winner'] = $_SESSION['user2']; ;
        } 

        //set player 1 hp and player 2 hp .. also set the hit count of player1(the one who did the punch)
        $_SESSION['user2']->setHp($player2currentHp);
        $_SESSION['user']->setHp($player1currentHp);
        $_SESSION['user2']->setHitCount($p2HitCount);

        //call class dbManager Update Function to update the character
        $dbManager->Update_Character($_SESSION['user']);
        $dbManager->Update_Character($_SESSION['user2']);
    }









    ?>