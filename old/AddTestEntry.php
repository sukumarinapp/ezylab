<?php
$page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
include 'header.php';
include_once 'Menu.php';
$PageAccessible = IsPageAccessible($user_id, 'TestEntry');
$today = date("Y-m-d");
$EntryId = DecodeVariable($_GET['eID']);

$EntryData = SelectParticularRow('patient_entry', 'id', $EntryId);
$patient_id = $EntryData['patient_id'];

$PatientInfo = SelectParticularRow('macho_patient', 'id', $patient_id);

$TestQuery = "SELECT a.*,b.test_code,b.remarks,b.test_category FROM macho_bill_items a,macho_test_type b WHERE a.item_id=b.id and bill_id='$EntryId' ORDER BY b.test_category,b.sub_head,b.id";
$TestResult = GetAllRows($TestQuery);
?>

<!-- Main section-->
<section class="section-container no-print">
    <!-- Page content-->
    <div class="content-wrapper">
        <div class="content-heading">
            <div><?= $PatientInfo['prefix'] . $PatientInfo['P_name']; ?>
                <small><?= $PatientInfo['P_code']; ?></small>
            </div>
        </div>
        <!-- start  -->
        <div class="container-fluid">
            <!-- DATATABLE DEMO 1-->
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th width="20">#</th>
                                        <th>Test Name</th>
                                        <th colspan="3">Result</th>
                                        <th>Unit</th>
                                        <th>Lower Limit</th>
                                        <th>Upper Limit</th>
                                        <th>Interpretation/Comments</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    $no = 0;
                                    $TestCounts = count($TestResult);
                                    if ($TestCounts > 0) {
                                        $catid = -1;
                                        $subhead = "-1";
                                        foreach ($TestResult as $TestData) {
                                            $TestID = $TestData['item_id'];
                                            $formula = $TestData['remarks'];
                                            $test_code = $TestData['test_code'];

                                            $TestTypeQuery = "SELECT * FROM macho_test_type WHERE id ='$TestID'";
                                            $TestTypeResult = mysqli_query($GLOBALS['conn'], $TestTypeQuery) or die(mysqli_error($GLOBALS['conn']));
                                            $TestTypeData = mysqli_fetch_assoc($TestTypeResult);
                                            $type_test = $TestTypeData['type_test'];
                                            if($catid != $TestData['test_category']){
                                                echo "<tr><td style='font-weight:bold' colspan='9' align='center'>".TestCategoryName($TestData['test_category'])."</td></tr>";
                                            }
                                            if($subhead != $TestTypeData['sub_head'] && trim($TestTypeData['sub_head']) != ""){
                                                echo "<tr><td style='text-decoration: underline;font-weight:bold'' colspan='9'>".$TestTypeData['sub_head']."</td></tr>";
                                            }
                                            if ($type_test == 'Normal') {
                                                ?>
                                                <tr>
                                                    <td width="20"><?= ++$no; ?></td>
                                                    <td><input type="hidden" class="form-control"
                                                               name="test_id[]"
                                                               id="test_id<?= $TestID; ?>"
                                                               value="<?= $TestID; ?>">
                                                        <?= $TestTypeData['test_name']; ?>
                                                    </td>
                                                    <td colspan="3"><input class="test_class" data-id="<?= $test_code; ?>" data-formula="<?= $formula; ?>" type="text" class="form-control"
                                                                           name="test_result[]"
                                                                           id="test_result<?= $TestID; ?>"
                                                                           value=""
                                                                           onkeypress="return isNumberDecimalKey(event)">
                                                    </td>
                                                    <td><?= $TestTypeData['units']; ?></td>
                                                    <td><?= $TestTypeData['lower_limit']; ?></td>
                                                    <td><?= $TestTypeData['upper_limit']; ?></td>
                                                    <td><input style="vertical-align: middle" id="inter<?= $TestID; ?>" type="checkbox" value="1" name="interpretation[]" /><label for="inter<?= $TestID; ?>"><label>&nbsp;Inter</label>&nbsp;<input style="vertical-align: middle" id="comm<?= $TestID; ?>" type="checkbox" value="1" name="comments[]" /><label for="comm<?= $TestID; ?>"><label>&nbsp;Cmt</label></label></td>
                                                </tr>
                                              
                                            <?php } elseif ($type_test == 'Sub Heading') { ?>
                                                <tr>
                                                    <td width="20"><?= ++$no; ?></td>
                                                    <td><input type="hidden" class="form-control"
                                                               name="test_id[]"
                                                               id="test_id<?= $TestID; ?>"
                                                               value="<?= $TestID; ?>">
                                                        <?= $TestTypeData['test_name']; ?>
                                                    </td>
                                                    <td colspan="6"><input data-id="<?= $test_code; ?>" data-formula="<?= $formula; ?>" type="text" class="form-control test_class"
                                                                           name="sub_head[]"
                                                                           id="sub_head<?= $TestID; ?>"></td>
                                                </tr>
                                           
                                            <?php } elseif ($type_test == 'Paragraph') { ?>
                                                <tr>
                                                    <td width="20"><?= ++$no; ?></td>
                                                    <td><input type="hidden" class="form-control"
                                                               name="test_id[]"
                                                               id="test_id<?= $TestID; ?>"
                                                               value="<?= $TestID; ?>">
                                                        <?= $TestTypeData['test_name']; ?>
                                                    </td>
                                                    <td colspan="6"><textarea type="text" class="form-control" rows="3"
                                                                              name="paragraph[]"
                                                                              id="paragraph<?= $TestID; ?>"></textarea>
                                                    </td>
                                                </tr>
                                              
                                            <?php } elseif ($type_test == 'Table') { 
                                                
                                                ?>
                                                <tr>
                                                    <td width="20"><?= ++$no; ?></td>
                                                    <td><input type="hidden" class="form-control"
                                                                           name="test_id[]"
                                                                           id="test_id<?= $TestID; ?>"
                                                                           value="<?= $TestID; ?>">
                                                        <?= $TestTypeData['test_name']; ?>
                                                    </td>
                                                    <td colspan="3"><input type="text" class="form-control"
                                                                           name="test_result[]"
                                                                           id="test_result<?= $TestID; ?>"
                                                                           value=""
                                                                           readonly>
                                                    </td>
                                                    <td><?= $TestTypeData['units']; ?></td>
                                                    <td colspan="2"><select class="form-control" name="table_input" id="table_input<?= $TestID; ?>" onchange="feed_data(<?= $TestID; ?>);">
                                                    <option value=""> Result Value </option>
                                                    <?php
                                                    $value = $TestTypeData['table_input'];
                                                
                                                    $data_count = WordCount($value);
                                                    for ($i = 0; $i < $data_count; $i++) {
                                                        $parts = explode(",", $value);
                                                        echo "<option value='" . $parts[$i] . "'>" . $parts[$i] . "</option>";
                                                    } ?>
                                                </select></td>
                                                    <!-- <td><input type="text" class="form-control"
                                                               name="head_1[]"
                                                               id="head_1<//?= $TestID; ?>"
                                                               value="">
                                                    </td>
                                                    <td><input type="text" class="form-control"
                                                               name="head_2[]"
                                                               id="head_2<//?= $TestID; ?>"
                                                               value="">
                                                    </td>
                                                    <td><input type="text" class="form-control"
                                                               name="head_3[]"
                                                               id="head_3<//?= $TestID; ?>"
                                                               value="">
                                                    </td>
                                                    <td><input type="text" class="form-control"
                                                               name="head_4[]"
                                                               id="head_4<//?= $TestID; ?>"
                                                               value="">
                                                    </td>
                                                    <td><input type="text" class="form-control"
                                                               name="head_5[]"
                                                               id="head_5<//?= $TestID; ?>"
                                                               value="">
                                                    </td>
                                                    <td><input type="text" class="form-control"
                                                               name="head_6[]"
                                                               id="head_6<//?= $TestID; ?>"
                                                               value="">
                                                    </td> -->
                                                </tr>
                                                <!-- <tr>
                                                    <td><input type="text" class="form-control"
                                                               name="result_1[]"
                                                               id="result_1<//?= $TestID; ?>"
                                                               value="" onkeypress="return isNumberDecimalKey(event)">
                                                    </td>
                                                    <td><input type="text" class="form-control"
                                                               name="result_2[]"
                                                               id="result_2<//?= $TestID; ?>"
                                                               value="" onkeypress="return isNumberDecimalKey(event)">
                                                    </td>
                                                    <td><input type="text" class="form-control"
                                                               name="result_3[]"
                                                               id="result_3<//?= $TestID; ?>"
                                                               value="" onkeypress="return isNumberDecimalKey(event)">
                                                    </td>
                                                    <td><input type="text" class="form-control"
                                                               name="result_4[]"
                                                               id="result_4<//?= $TestID; ?>"
                                                               value="" onkeypress="return isNumberDecimalKey(event)">
                                                    </td>
                                                    <td><input type="text" class="form-control"
                                                               name="result_5[]"
                                                               id="result_5<//?= $TestID; ?>"
                                                               value="" onkeypress="return isNumberDecimalKey(event)">
                                                    </td>
                                                    <td><input type="text" class="form-control"
                                                               name="result_6[]"
                                                               id="result_6<//?= $TestID; ?>"
                                                               value="" onkeypress="return isNumberDecimalKey(event)">
                                                    </td>
                                                </tr> -->
                                               
                                            <?php } elseif ($type_test == 'Date') { ?>
                                                <tr>
                                                    <td width="20"><?= ++$no; ?></td>
                                                    <td><input type="hidden" class="form-control"
                                                               name="test_id[]"
                                                               id="test_id<?= $TestID; ?>"
                                                               value="<?= $TestID; ?>">
                                                        <?= $TestTypeData['test_name']; ?>
                                                    </td>
                                                    <td colspan="6"><input type="date" class="form-control"
                                                                           data-date-format="dd-mm-yyyy" name="date[]"
                                                                           id="date<?= $TestID; ?>"></td>
                                                </tr>
                                               
                                            <?php } elseif ($type_test == 'Time') { ?>
                                                <tr>
                                                    <td width="20"><?= ++$no; ?></td>
                                                    <td><input type="hidden" class="form-control"
                                                               name="test_id[]"
                                                               id="test_id<?= $TestID; ?>"
                                                               value="<?= $TestID; ?>">
                                                        <?= $TestTypeData['test_name']; ?>
                                                    </td>
                                                    <td colspan="6"><input type="time" class="form-control"
                                                                           name="time[]"
                                                                           id="time<?= $TestID; ?>"></td>
                                                </tr>
                                               
                                            <?php } else { ?>
                                                <tr>
                                                    <td width="20"><?= ++$no; ?></td>
                                                    <td><input type="hidden" class="form-control"
                                                               name="test_id[]"
                                                               id="test_id<?= $TestID; ?>"
                                                               value="<?= $TestID; ?>">
                                                        <?= $TestTypeData['test_name']; ?>
                                                    </td>
                                                    <td colspan="6">
                                                        <button type="button" class="btn btn-info" title="Document Upload Now"
                                                                onclick="Upload_Doc(<?php echo $EntryId; ?>,<?php echo $TestID; ?>);"><i class="fa fa-upload"></i> File Upload
                                                        </button>
                                                    </td>
                                                </tr>

                                            <?php }
                                            $catid = $TestData['test_category'];
                                            $subhead = $TestTypeData['sub_head'];
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="float-right">
                        <input type="hidden" name="entry_id" id="entry_id"
                               value="<?php echo $EntryId; ?>">
                        <button onclick="calculate()" class="btn btn-danger" type="button" >Calculate</button>       
                        <button class="btn btn-labeled btn-secondary" type="button"
                                onclick="location.href='TestEntry';">
                           <span class="btn-label"><i class="fa fa-arrow-left"></i>
                           </span>Back to List
                        </button>
                        <button class="btn btn-labeled btn-primary" type="button" name="submit"
                                id="save_button"
                                onclick="submit_data();" tabindex="9">
                           <span class="btn-label"><i class="fa fa-check"></i>
                           </span>Save Data
                        </button>
                    </div>
                    <br>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Page footer-->
<?php include_once 'footer.php' ?>
</div>

<div class="modal fade" id="add_doc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Upload Documents</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="doc_body">
            </div>
        </div>
    </div>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="<?php echo VENDOR; ?>modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.js"></script>
<script src="<?php echo VENDOR; ?>jquery/dist/jquery.min.js"></script>
<script src="<?php echo JS; ?>jquery.redirect.js"></script>
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
<script src="<?php echo VENDOR; ?>bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<!-- Datatables-->
<!-- =============== APP SCRIPTS ===============-->
<script src="<?php echo JS; ?>app.js"></script>
<script src="<?php echo VENDOR; ?>bootstrap-sweetalert/dist/sweetalert.js"></script>
<script>
    function calculate(){
        var formula = [];
        var $formulael = [];
        $('.test_class').each(function(){
            var $el = $(this); 
            var n = $el.val();
            if($el.data("formula") != ""){
                $formulael.push($(this)); 
                formula.push($el.data("formula"));
            }
        });
        for (let i = 0; i < formula.length; i++) {
            $('.test_class').each(function(){
                var $el = $(this); 
                var test_result = $el.val();
                var test_code = $el.data("id");
                formula[i] = formula[i].replace(test_code,test_result);
            });
            var res = eval(formula[i]).toFixed(2);
            res = res.replace(".00","");
            $formulael[i].val(res);
        }
    }
   
    $(function () {
        //Date picker
        $('#date').datepicker({
            autoclose: true
        });
    });

    function feed_data(test_id) {
        var table_input = $('#table_input'+test_id).val();
        $('#test_result'+test_id).val(table_input);
    }

    function Upload_Doc(entry_id,test_id) {
        $.ajax({
            type: "POST",
            url: "UploadTestDocs.php",
            data: {
                entry_id: entry_id,
                test_id: test_id
            },
            success: function (response) {
                $('#doc_body').html(response);
                $('#add_doc').modal('show');
                get_documents(entry_id,test_id);
            }
        });
    }

    function get_documents(entry_id,test_id) {
        $.ajax({
            type: 'post',
            url: 'LoadTestDocs.php',
            data: {
                entry_id: entry_id,
                test_id: test_id
            },
            success: function (data) {
                $("table_data").html(data);
            }
        });
    }

    function docupload(e, entry_id, test_id) {
        e.preventDefault();
        $('#save_doc').prop('disabled', true);
        var formData = new FormData($("#save_upload")[0]);
        formData.append("entry_id", entry_id);
        formData.append("test_id", test_id);
        $.ajax({
            type: 'post',
            url: 'SaveUploadTestDocs.php',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (response) {
                get_documents(entry_id,test_id);
                if (response == 1) {
                    $('#save_doc').prop('disabled', false);
                    alert("Oops something happened. Contact Administrator");
                } else {
                    $('#save_doc').prop('disabled', false);
                    $('#file_name').val('');
                    $('#file').val('');
                }
            }
        });
    }

    function update_document(id) {

        $.ajax({
            type: 'post',
            url: 'UpdateTestDocs.php',
            data: {
                id: id
            },
            success: function (response) {
                $('#add_doc').modal('hide');
            }
        });
    }

    function update_cancel(id) {

        $.ajax({
            type: 'post',
            url: 'CancelTestDocs.php',
            data: {
                id: id
            },
            success: function (response) {
                $('#add_doc').modal('hide');
            }
        });
    }

    function document_delete(id, file_url) {
        $.ajax({
            type: 'post',
            url: 'DeleteTestDocs.php',
            data: {
                id: id,
                file_url: file_url
            },
            success: function (response) {
                if (response == 1) {
                    $("#document_row_" + (id)).remove();
                } else {
                    alert("oops something wrong");
                }
            }
        });
    }
</script>
<script>
    $(function () {
        //Date picker
        $('#date').datepicker({
            autoclose: true
        });
    });

    function DecimalPoint(x) {
        return Number.parseFloat(x).toFixed(2);
    }

    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function submit_data() {

        $("#save_button").prop("disabled", true);

        var entry_id = $('#entry_id').val();

        var test_id = new Array();
        $('input[name^="test_id"]').each(function () {
            test_id.push($(this).val());
        });

        var obj = new Array();
        for (var i = 0; i < test_id.length; i++) {
            var id = test_id[i];
            var inter = $('#inter' + id).is(":checked") ? 1 : 0;
            var comm = $('#comm' + id).is(":checked") ? 1 : 0;

            obj[i] = id + ',' + $('#test_result' + id).val() + ',' + $('#sub_head' + id).val() + ',' + $('#paragraph' + id).val() + ',' + $('#head_1' + id).val() + ',' + $('#head_2' + id).val() + ',' + $('#head_3' + id).val() + ',' + $('#head_4' + id).val() + ',' + $('#head_5' + id).val() + ',' + $('#head_6' + id).val() + ',' + $('#result_1' + id).val() + ',' + $('#result_2' + id).val() + ',' + $('#result_3' + id).val() + ',' + $('#result_4' + id).val() + ',' + $('#result_5' + id).val() + ',' + $('#result_6' + id).val() + ',' + $('#date' + id).val() + ',' + $('#time' + id).val() + ',' + inter + ',' + comm;

        }

        var test_data = JSON.stringify(obj);
        $.ajax({
            type: 'POST',
            url: 'SaveTestEntry.php',
            data: {
                entry_id: entry_id,
                test_data: test_data
            },
            success: function (entry_id) {
                $("#save_button").prop("disabled", false);

                swal({
                        title: "Success",
                        text: "Test Details Added Successfully!",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "OK",
                        closeOnConfirm: false
                    },
                    function () {
                        location.href = "TestEntry";
                    });
            }
        });
    }
</script>
</body>
</html>