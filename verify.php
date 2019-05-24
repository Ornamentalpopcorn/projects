<?php
	date_default_timezone_set('Asia/Manila');
	//$ip = $_SERVER['HTTP_CLIENT_IP']?:($_SERVER['HTTP_X_FORWARDE‌​D_FOR']?:$_SERVER['REMOTE_ADDR']);
	include('connection.php');
	session_start();


	// $_SESSION['authUser'] = "MALLARI, AEROLLE";
	// $_SESSION['authRole'] = "USER";
	// $_SESSION['auth_usercode'] = "BK-243968";
	// $_SESSION['authPosition'] = "KASS" ;
	// header('Location:auth_directory/');

	/**
	  * ##
	  * ## @md GET INFORMATION
	  * ##
	 */
	if(isset($_POST['btnLogin'])){

		$username = $_POST['username'];
		$password = $_POST['password'];
		// $password = md5($password);

		$json = file_get_contents('http://bellkenz.com/md-profile-v2/webservice/SMPP/index.php?cmd=smpp_login&token=BKPI2017&username=' . $username . '&password=' . $password);
		$obj = json_decode($json);
		$arr = $obj[0]->data;

		// echo "<pre>";
		// print_r($obj) ;
		// echo "</pre>";
		// exit();
		foreach ($arr as $ar) {

					if ($ar->success == 1) {

								$full_name = $ar->lname . ", " . $ar->fname;
								$unique_id = $ar->unique_id;
								$user_level = $ar->user_level;

								$admin = array("admin", "data_admin", "head_overall-kash", "other_view-all", "iad", "other_view-kasd", "other", "other_view-sales") ;
								$manager = array("head_approval-kasm", "head_approval-rsm", "head_approval-sam", "head_overall-fsm");
								$rep = array("encoder-kass", "encoder-sar") ;

							 if ($user_level == "head_approval-kasm" ||
							 	   $user_level == "encoder-kass" ) {
							 		 	    $dept = "KASS" ;
							 } elseif ($user_level == "head_approval-rsm" ||
							 					 $user_level == "encoder-sar" ||
							 					 $user_level == "head_approval-sam" ||
							 					 $user_level == "head_approval-fsm") {
								 			  $dept = "SAR";
							 } else   $dept = "OTHER" ;


								// echo "$user_level";
								// exit();

								if (in_array($user_level , $admin) ) {

								    $auth_role = "ADMIN" ;

								} elseif (in_array($user_level , $manager) ) {

									  $auth_role = "MANAGER" ;

								} elseif (in_array($user_level , $rep) ) {

									  $auth_role = "USER" ;

								} else { // ACCESS HAS VALID CREDENTIALS BUT NOT ACCESSIBLE FOR THIS MODULE

												$_SESSION['error_login'] = 'true';
												header('Location:../');

								}


								$_SESSION['authUser'] = $full_name;
								$_SESSION['authRole'] = $auth_role;
								$_SESSION['auth_usercode'] = $unique_id;
								$_SESSION['authPosition'] = $user_level ;
								$_SESSION['dept'] = $dept ;
								header('Location:auth_directory/');

					} else {

									$_SESSION['error_login'] = 'true';
									header('Location:../');
					}


		}


		// echo "<pre>";
		// print_r($arr);
		// echo "</pre>";


		// $user_query = $mysqli -> query("SELECT auth_fullname,auth_role,auth_usercode FROM auth_users_tbl WHERE auth_username='$username' AND auth_password='$password'");
		// if(mysqli_num_rows($user_query)==0){
			// $_SESSION['error_login'] = 'true';
			// header('Location:../sales-md-productivity-portal/');
		// }else{
		// 	while($user_res = $user_query -> fetch_assoc()){
				// $_SESSION['authUser'] = $user_res['auth_fullname'];
				// $_SESSION['authRole'] = $user_res['auth_role'];
				// $_SESSION['auth_usercode'] = $user_res['auth_usercode'];
				// header('Location:auth_directory/');
		// 	}
		// }


	}
?>
