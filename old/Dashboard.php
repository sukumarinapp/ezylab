<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include "header.php";
include_once "Menu.php";
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

<!-- Main section-->
<section class="section-container"  >
    <!-- Page content-->
    <div class="content-wrapper" >
        <div class="content-heading">
            <div>Dashboard
                <small data-localize="dashboard.WELCOME"></small>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo $patient_count ?></h3>
                        <p>Patients</p>
                        <h1>&nbsp;</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $visit_count ?></h3>
                        <p>Today's Visit</p>
                        <h1>&nbsp;</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo $pending_count ?></h3>
                        <p>Pending Report</p>
                        <h1>&nbsp;</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
 <div class="row">
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $completed_count ?></h3>
                        <p>Completed Report</p>
                        <h1>&nbsp;</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                         <h3><?php echo $today_amount ?></h3>
                        <p>Today's Collection</p>
                        <h1>&nbsp;</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $month_amount ?></h3>
                        <p>Monthly Collection</p>
                        <h1>&nbsp;</h1>
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
    <!-- END Multiple List group-->
</div>
</section>
<!-- Page footer-->
<?php include_once "footer.php"; ?>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.min.js"></script>
<!-- BOOTSTRAP-->
<script src="<?php echo VENDOR; ?>popper.js/dist/umd/popper.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap/dist/js/bootstrap.js"></script>
<!-- STORAGE API-->
<script src="<?php echo VENDOR; ?>js-storage/js.storage.js"></script>
<!-- JQUERY EASING-->
<script src="<?php echo VENDOR; ?>jquery.easing/jquery.easing.js"></script>
<!-- ANIMO-->
<script src="<?php echo VENDOR; ?>animo/animo.js"></script>
<!-- SCREENFULL-->
<script src="<?php echo VENDOR; ?>screenfull/dist/screenfull.js"></script>
<!-- LOCALIZE-->
<script src="<?php echo VENDOR; ?>jquery-localize/dist/jquery.localize.js"></script>
<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<script src="vendor/jquery-sparkline/jquery.sparkline.js"></script>
<!-- JQUERY UI-->
<script src="<?php echo VENDOR; ?>components-jqueryui/jquery-ui.js"></script>
<script src="<?php echo VENDOR; ?>jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
<!-- MOMENT JS-->
<script src="<?php echo VENDOR; ?>moment/min/moment-with-locales.js"></script>
<!-- FULLCALENDAR-->
<script src="<?php echo VENDOR; ?>fullcalendar/dist/fullcalendar.js"></script>
<script src="<?php echo VENDOR; ?>fullcalendar/dist/gcal.js"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script>
    //var calender_events =<?php //echo json_encode($events, JSON_NUMERIC_CHECK);?>;
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
            }
        });
        return events;
    }
</script>
</body>
</html>