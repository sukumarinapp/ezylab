<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php'; ?>
<link rel="stylesheet" href="<?php echo VENDOR; ?>fullcalendar/dist/fullcalendar.css">
<!-- Main section-->
<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">
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