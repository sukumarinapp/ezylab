<?php
   session_start();
   include "booster/bridge.php";
   $user_id = $_SESSION["user_id"];
   $role_id = $_SESSION["role_id"];
   $role = $_SESSION["role"];
   $user = $_SESSION["user"];
   $user_name = $_SESSION["user_name"];
   $email = $_SESSION["user_email"];
   $picture = $_SESSION["picture"];
   $access_token = $_SESSION["access_token"];
   ValidateAccessToken($user_id, $access_token);
   
   $page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
   $today = date("Y-m-d");
   $month = date("Y-m");
   $patient_count = 0;
   $sql = "SELECT count(*) as patient_count FROM macho_patient ";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_assoc($result);
   $patient_count = $row['patient_count'];
   $pending_count = 0;
   $sql = "SELECT count(*) as pending_count FROM patient_entry WHERE test_status  ='0' and entry_date='$today'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_assoc($result);
   if($row['pending_count'] != ""){
       $pending_count = $row['pending_count'];
   }
   $completed_count = 0;
   $sql = "SELECT count(*) as completed_count FROM patient_entry WHERE test_status  ='1' and entry_date='$today'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_assoc($result);
   if($row['completed_count'] != ""){
       $completed_count = $row['completed_count'];
   }
   $visit_count = 0;
   $sql = "SELECT count(*) as visit_count FROM patient_entry where entry_date='$today'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_assoc($result);
   if($row['visit_count'] != ""){
       $visit_count = $row['visit_count'];
   }
   $today_amount = 0;
   $sql = "SELECT sum(total_amount) as today_amount FROM patient_entry where entry_date='$today'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_assoc($result);
   if($row['today_amount'] != ""){
       $today_amount = $row['today_amount'];
   }
   $month_amount = 0;
   $sql = "SELECT sum(total_amount) as month_amount FROM patient_entry where entry_date like '$month%'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_assoc($result);
   if($row['month_amount'] != ""){
       $month_amount = $row['month_amount'];
   }

   ?>
<!doctype html>
<html lang="en">
<head>
<?php include ("headercss.php"); ?>
<title>Dashboard</title>
</head>
<body class="bg-theme bg-theme2">
   <!--wrapper-->
   <div class="wrapper">
   <!--sidebar wrapper -->
   <?php include ("Menu.php"); ?>
   <!--end sidebar wrapper -->
   <!--start header -->
   <?php include ("header.php"); ?>
   <!--end header -->
   <!--start page wrapper -->
   <div class="page-wrapper">
      <div class="page-content">
         <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
            <div class="col">
               <div class="card radius-10">
                  <div class="card-body">
                     <div class="text-center">
                        <div class="widgets-icons rounded-circle mx-auto mb-3"><i class='bx bx-user
                           '></i>
                        </div>
                        <h6 class="mb-0">Patient</h6>
                        <h4 class="my-1"><?php echo $patient_count ?></h4>
                        <p class="mb-0">Patients List</p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col">
               <div class="card radius-10">
                  <div class="card-body">
                     <div class="text-center">
                        <div class="widgets-icons rounded-circle mx-auto mb-3"><i class='bx bxs-group'></i>
                        </div>
                        <h6 class="my-1">Today's Visit</h6>
                        <h4 class="my-1"><?php echo $visit_count ?></h4>
                        <p class="mb-0">Today's Visit List</p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col">
               <div class="card radius-10">
                  <div class="card-body">
                     <div class="text-center">
                        <div class="widgets-icons rounded-circle mx-auto mb-3"><i class='bx bx-dislike
                           '></i>
                        </div>
                        <h6 class="my-1">Pending Report</h6>
                        <h4 class="my-1"><?php echo $pending_count ?></h4>
                        <p class="mb-0">Pending Report List</p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col">
               <div class="card radius-10">
                  <div class="card-body">
                     <div class="text-center">
                        <div class="widgets-icons rounded-circle mx-auto mb-3"><i class='bx bx-like'></i>
                        </div>
                        <h6 class="my-1">Complete Report</h6>
                        <h4 class="my-1"><?php echo $completed_count ?></h4>
                        <p class="mb-0">Completed Report List</p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col">
               <div class="card radius-10">
                  <div class="card-body">
                     <div class="text-center">
                        <div class="widgets-icons rounded-circle mx-auto mb-3"><i class='bx bxs-collection
                           '></i>
                        </div>
                        <h6 class="my-1">Today's Collection</h6>
                        <h4 class="my-1"><?php echo $today_amount ?></h4>
                        <p class="mb-0">Today's Collection List</p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col">
               <div class="card radius-10">
                  <div class="card-body">
                     <div class="text-center">
                        <div class="widgets-icons rounded-circle mx-auto mb-3"><i class='bx bxs-collection
                           '></i>
                        </div>
                        <h6 class="my-1">Monthly Collection</h6>
                        <h4 class="my-1"><?php echo $month_amount ?></h4>
                        <p class="mb-0">Monthly Collection List</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-4">
                <a href="backup" target="_blank" class="btn btn-danger">Backup</a>
            </div>
        </div>
      </div>
   </div>
   <?php include ("js.php"); ?>
</body>
</html>