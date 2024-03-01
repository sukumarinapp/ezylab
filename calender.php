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

?>

<?php include ("css.php"); ?>
<title>Dashtrans</title>
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
        <div class="content-heading">
            <div>Full Calendar
                <small>View Your Events</small>
            </div>
        </div>
        <!-- START row-->
        <div class="calendar-app">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <!-- START card-->
                    <div class="card card-default">
                        <div class="card-body">
                            <!-- START calendar-->
                            <div id="calendar"></div>
                            <!-- END calendar-->
                        </div>
                    </div>
                    <!-- END card-->
                </div>
            </div>
            <!-- END row-->
        </div>
    </div>
</section>	  <?php include_once 'footer.php'; ?>
</div>

   <?php include ("js.php"); ?>
<script>
    function getevents() {
        var events;
        var json_events;
        $.ajax({
            url: 'GetEvents.php',
            type: 'POST',
            data: 'type=fetch',
            async: false,
            success: function (response) {
                json_events = response;
                events = JSON.parse(json_events);
                //events = json_events;
            }
        });
        return events;
    }
</script>
</body>
</html>