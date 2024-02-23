<?php
session_start();
include_once "booster/bridge.php";
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
$PageAccessible = IsPageAccessible($user_id, $page);
$today = date("Y-m-d");
$created = date("Y-m-d H:i:s");
$modified = date("Y-m-d H:i:s");


?>
<?php
$validation = false;
$today = date("Y-m-d");

$sql = "select * from  software_validation where from_date <= '$today' and to_date >= '$today'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    $validation = true;
}

//echo "<pre>";print_r($from_date);print_r($to_date);print_r($validation);echo "</pre>";die;

?>
<!doctype html>
<html lang="en">

<head>

<style>
    .ellipsis {
        max-width: 40px;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
</style>
<?php include ("headercss.php"); ?>
<title>Test</title>
</head>
<body class="bg-theme bg-theme2">
    <?php 
    if (isset($_POST['excel_import'])) {
    $Filepath = "excel".DIRECTORY_SEPARATOR.$_FILES["test_import"]["name"];
    move_uploaded_file($_FILES["test_import"]["tmp_name"], $Filepath);
    require('library/php-excel-reader/excel_reader2.php');
    require('library/SpreadsheetReader.php');
    $Spreadsheet = new SpreadsheetReader($Filepath);
    $Sheets = $Spreadsheet -> Sheets();
    foreach ($Sheets as $Index => $Name){
        if($Index == 0){
            $Spreadsheet -> ChangeSheet($Index);
            foreach ($Spreadsheet as $Key => $Row){
                if($Key > 0 ){
                    $test_category = trim($Row[0]);
                    $test_code = GetTestCode();
                    $test_name     = trim($Row[1]);
                    $price         = trim($Row[2]);
                    $lower_limit = trim($Row[3]);
                    $upper_limit = trim($Row[4]);
                    $units         = trim($Row[5]);
                    $type_test     = trim($Row[6]);
                    $sub_head = "";
                    $table_input  = "";
                    if(strtolower($type_test) == "sub heading"){
                        $sub_head     = trim($Row[7]);
                    }
                    if(strtolower($type_test) == "table"){
                        $table_input     = trim($Row[8]);
                    }
                    $critical_info = trim($Row[9]);
                    $interpretation = trim($Row[10]);
                   
                    $sql = "insert into macho_test_type  (test_category,test_code,test_name,units,price,type_test,sub_head,sample_type,table_input,critical_info,interpretation,lower_limit,upper_limit) values ('$test_category','$test_code','$test_name','$units','$price','$type_test','$sub_head','$sample_type','$table_input','$critical_info','$interpretation','$lower_limit','$upper_limit')";
                    //echo $sql;die;
                    mysqli_query($GLOBALS['conn'], $sql) or die(mysqli_error($GLOBALS['conn']));
                }
            }
        }
    }
    echo '<span id="import_success"></span>';
}

if (isset($_POST['submit'])) {

    $test_category = $_POST['test_category'];
    $TestCategoryData = SelectParticularRow('macho_test_category', 'id', $test_category);
    $dept_id = $TestCategoryData['dept_id'];
    $show_critical_info = 0;
    $show_interpretation = 0;
    if(isset($_POST['show_critical_info'])) $show_critical_info = 1;
    if(isset($_POST['show_interpretation'])) $show_interpretation = 1;
    $insert_Test_type = Insert(
        'macho_test_type',
        array(

            'test_code' => Filter($_POST['test_code']),
            'test_name' => Filter($_POST['test_name']),
            'price' => Filter($_POST['price']),
            'lower_limit' => Filter($_POST['lower_limit']),
            'upper_limit' => Filter($_POST['upper_limit']),
            'remarks' => Filter($_POST['remarks']),
            'method' => Filter($_POST['method']),
            'dept_id' => $dept_id,
            'test_category' => Filter($_POST['test_category']),
            'interpretation' => Filter($_POST['interpretation']),
            'type_test' => Filter($_POST['type_test']),
            'table_input' => Filter($_POST['table_input']),
            'units' => Filter($_POST['units']),
            'critical_info' => Filter($_POST['critical_info']),
            'comments' => Filter($_POST['comments']),
            'sub_head' => Filter($_POST['sub_head']),
            'sample_type' => Filter($_POST['sample_type']),
            'show_critical_info' => $show_critical_info,
            'show_interpretation' => $show_interpretation,
            'created' => $created,
            'modified' => $modified 
        )
    );


    if (is_int($insert_Test_type)) {

        $notes = $_POST['test_name'] . ' Test Type added by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="insert_success"></span>';
    } else {
        echo '<span  id="insert_failure"></span>';
    }
}

if (isset($_POST['update'])) {
    $test_type_id = Filter($_POST['id']);
    $test_category = $_POST['test_category'];
    $show_critical_info = 0;
    $show_interpretation = 0;
    if(isset($_POST['show_critical_info'])) $show_critical_info = 1;
    if(isset($_POST['show_interpretation'])) $show_interpretation = 1;
    $TestCategoryData = SelectParticularRow('macho_test_category', 'id', $test_category);
    $dept_id = $TestCategoryData['dept_id'];

    $update = Update(
        'macho_test_type',
        'id',
        $test_type_id,
        array(
            'test_name' => Filter($_POST['test_name']),
            'price' => Filter($_POST['price']),
            'lower_limit' => Filter($_POST['lower_limit']),
            'upper_limit' => Filter($_POST['upper_limit']),
            'remarks' => Filter($_POST['remarks']),
            'method' => Filter($_POST['method']),
            'dept_id' => $dept_id,
            'test_category' => Filter($_POST['test_category']),
            'interpretation' => Filter($_POST['interpretation']),
            'type_test' => Filter($_POST['type_test']),
            'table_input' => Filter($_POST['table_input']),
            'units' => Filter($_POST['units']),
            'critical_info' => Filter($_POST['critical_info']),
            'comments' => Filter($_POST['comments']),
            'sub_head' => Filter($_POST['sub_head']),
            'sample_type' => Filter($_POST['sample_type']),
            'show_critical_info' => $show_critical_info,
            'show_interpretation' => $show_interpretation,
            'modified' => $modified
        )
    );
    if ($update) {

        $notes = $_POST['test_name'] . ' Test Type modified by ' . $user;
        $receive_id = '1';
        $receive_role_id = GetRoleOfUser($receive_id);
        InsertNotification($notes, $user_id, $role_id, $receive_role_id, $receive_id);

        echo '<span id="update_success"></span>';
    } else {
        echo '<span  id="update_failure"></span>';
    }
}
    ?>
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
            <h6>Test</h6>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <!-- START card-->
                <div class="card card-default">
                    <div class="card-header">
                        <form method="post" action="TestType" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <input required class="form-control" type="file" name="test_import" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                                </div>
                                <div class="col-md-4">
                                    <input class="btn btn-danger" type="submit" name="excel_import" value="Import Test Data">
                                </div>
                                <div class="col-md-4">
                                <?php if ($validation) { ?>
                                    <?php if ($PageAccessible['is_write'] == 1) { ?>
                                        <div class="card-title pull-right">
                                            <button class="btn btn-labeled btn-danger" type="button" data-bs-toggle="modal"
                                            data-bs-target="#add_test_type">Create New
                                            <span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>
                                            </span></button>
                                        </div>
                                    <?php } ?>
                                    <?php } ?>
                                    <div class="text-sm"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example2" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="thead_data">Dept</th>
                                    <th class="thead_data">Test Name</th>
                                    <th width="25" class="thead_data">Price</th>
                                    <th class="thead_data">Lower Limit</th>
                                    <th class="thead_data">Upper Limit</th>
                                    <th class="thead_data">Unit</th>
                                    <th class="thead_data">Type</th>
                                    <th class="thead_data">Sub Heading</th>
                                    <th class="thead_data">Sample Type</th>
                                    <th class="thead_data">Table Input</th>
                                    <th class="thead_data">Critical Info</th>
                                    <th class="thead_data">Interpretation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                $TestTypeQuery = "SELECT * FROM macho_test_type ORDER BY id";
                                $TestTypeResult = GetAllRows($TestTypeQuery);
                                $TestTypeCounts = count($TestTypeResult);
                                if ($TestTypeCounts > 0) {
                                    foreach ($TestTypeResult as $TestTypeData) { ?>
                                        <tr>
                                          <td class="tbody_data"><?= $TestTypeData['test_category']; ?></td>
                                            <td width="25" ><?= $TestTypeData['test_name']; ?></td>
                                            <td class="tbody_data"><?= $TestTypeData['price']; ?></td>
                                            <td class="tbody_data"><?= $TestTypeData['lower_limit']; ?></td>
                                            <td class="tbody_data"><?= $TestTypeData['upper_limit']; ?></td>
                                            <td class="tbody_data"><?= $TestTypeData['units']; ?></td>
                                            <td class="tbody_data"><?= $TestTypeData['type_test']; ?></td>
                                            <td class="tbody_data"><?= $TestTypeData['sub_head']; ?></td>
                                            <td class="tbody_data"><?= $TestTypeData['sample_type']; ?></td>
                                            <td class="tbody_data"><?= $TestTypeData['table_input']; ?></td>
                                            <td class="ellipsis"><?= $TestTypeData['critical_info']; ?></td>
                                            <td class="ellipsis"><?= $TestTypeData['interpretation']; ?></td>
                                            <td>

                                                <div class="btn-group">
                                                    <?php if ($PageAccessible['is_read'] == 1) { ?>
                                                        <button class="btn btn-sm btn-info" type="button" title="View"
                                                        onclick="ModalView(<?php echo $TestTypeData['id']; ?>);">
                                                        <i class="fa fa-eye"></i></button>
                                                    <?php }
                                                    if ($PageAccessible['is_modify'] == 1) { ?>
                                                        <button class="btn btn-sm btn-info" type="button" title="Edit"
                                                        onclick="ModalEdit(<?php echo $TestTypeData['id']; ?>);">
                                                        <i class="fa fa-edit"></i></button>
                                                    <?php }
                                                    if ($PageAccessible['is_delete'] == 1) { ?>
                                                        <button class="btn btn-sm btn-danger" type="button" title="Delete"
                                                        onclick="Delete('macho_test_type','id',<?php echo $TestTypeData['id']; ?>);">
                                                        <i class="fa fa-trash"></i></button>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                            </div>
                            </div>
                </div>
                <!-- END card-->
            </div>
        </div>
    </div>
</section>
<!-- Page footer-->
</div>
<div class="modal fade" id="add_test_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Create New Test</h4>
            <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xl-12">
                    <form method="post" action="TestType">
                        <!-- START card-->
                        <div class="card card-default">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Test Code </label>
                                            <input class="form-control" type="text" name="test_code" id="test_code"
                                            value="<?= GetTestCode(); ?>" maxlength="100" tabindex="1" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Price </label>
                                            <input class="form-control" type="text" name="price" id="price"
                                            maxlength="100" onkeypress="return isNumberDecimalKey(event)"
                                            tabindex="3" required>
                                        </div>
                            
                                        <div class="form-group">
                                            <label class="col-form-label">Method </label>
                                            <input class="form-control" type="text" name="method" id="method"
                                            maxlength="100" tabindex="5">
                                        </div>
                                        <div class="form-group">
                                             <label class="col-form-label">Sample Type</label>
                                             <input maxlength="100" class="form-control" name="sample_type" id="sample_type" tabindex="8" />
                                       </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Type of Test </label>
                                            <select class="form-control" name="type_test" id="type_test"
                                            tabindex="7">
                                            <option value='Normal'>Normal</option>
                                            <option value='Sub Heading'>Sub Heading</option>
                                            <option value='Paragraph'>Paragraph</option>
                                            <option value='Table'>Table</option>
                                            <option value='Date'>Date</option>
                                            <option value='Time'>Time</option>
                                            <option value='Image'>Image</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Units </label>
                                        <input maxlength="10" class="form-control" name="units" id="units" tabindex="8" />
                                    </div>
                                    <div class="form-group form-check">
                                      <input name="show_critical_info" class="form-check-input" type="checkbox" value="1" id="show_critical_info" >
                                      <label class="form-check-label" for="show_critical_info">
                                        Show Critical Info
                                      </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Critical Info </label>
                                        <textarea class="form-control" name="critical_info" id="critical_info"
                                        maxlength="500" rows="9" tabindex="10"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Test Name </label>
                                        <input class="form-control" type="text" name="test_name" id="test_name"
                                        maxlength="100" tabindex="2" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Formula</label>
                                        <input class="form-control" type="text" name="remarks" id="remarks"
                                        maxlength="100" tabindex="4">
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Department</label>
                                        <select class="form-control" name="test_category" id="test_category"
                                        tabindex="6">
                                        <?php
                                        $TestCategoryQuery = "SELECT * FROM macho_test_category where type='single' ORDER BY id";
                                        $TestCategoryResult = GetAllRows($TestCategoryQuery);
                                        foreach ($TestCategoryResult as $TestCategoryData) {
                                            echo "<option value='" . $TestCategoryData['id'] . "'>" . $TestCategoryData['category_name'] . "</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div id='table_tab' style="display:none;">
                                    <div class="form-group">
                                        <label class="col-form-label">Table Input </label>
                                        <textarea class="form-control" name="table_input" id="table_input"
                                        maxlength="500" rows="5" tabindex="9"></textarea>
                                    </div>
                                </div>
                                <div id='others_tab'>
                                    <div class="form-group">
                                        <label class="col-form-label">Lower Limit </label>
                                        <input class="form-control" type="text" name="lower_limit"
                                        id="lower_limit" maxlength="100" tabindex="9">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Upper Limit </label>
                                        <input class="form-control" type="text" name="upper_limit"
                                        id="upper_limit" maxlength="100" tabindex="9">
                                    </div>
                                    <div class="form-group">
                                    <label class="col-form-label">Sub Heading Name</label>
                                    <input maxlength="100" class="form-control" name="sub_head" id="sub_head" tabindex="8" />
                                   </div>
                                </div>
                                <div class="form-group form-check">
                                      <input name="show_interpretation" class="form-check-input" type="checkbox" value="1" id="show_interpretation" >
                                      <label class="form-check-label" for="show_interpretation">
                                        Show Interpretation
                                      </label>
                                    </div>
                                <div class="form-group">
                                    <label class="col-form-label">Interpretation</label>
                                    <textarea class="form-control" name="interpretation" id="interpretation"
                                    maxlength="500" rows="9" tabindex="11"></textarea>
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Comments </label>
                                    <textarea class="form-control" name="comments" id="comments"
                                    maxlength="500" rows="4" tabindex="12"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="clearfix">
                            <div class="float-right">
                                <button class="btn btn-primary" type="submit" name="submit" tabindex="13">
                                    Save
                                </button>
                                <button class="btn btn-secondary" class="close" type="button" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END card-->
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="view_test_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Test Type Details</h4>
            <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post" action="TestType">
            <div class="modal-body" id="view_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
</div>

<div class="modal fade" id="edit_test_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Update Test Details</h4>
            <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="post" action="TestType">
            <div class="modal-body" id="edit_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" class="close" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-primary m-t-n-xs" type="submit" name="update" tabindex="11">
                    <strong>Save Changes</strong>
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>

   <?php include ("js.php"); ?>

<script>

     $( document ).ready(function() {
        //Date picker

        $('#type_test').change(function () {
            var type_test = $(this).val();
            if (type_test == 'Table') {
                $('#table_tab').show();
                $('#others_tab').hide();
            } else {
                $('#others_tab').show();
                $('#table_tab').hide();
            }
        });

        $('#datatable5').DataTable({
            dom: 'Bfrtip',
             buttons: [
                'excel'
            ],
             scrollX: true, 
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
        });
    });



    
    function isNumberDecimalKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function ModalView(id) {
        $.ajax({
            type: "POST",
            url: "EditTestType.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#view_body').html(response);
                $('#view_test_type').modal('show');
            }
        });
    }

    function ModalEdit(id) {
        $.ajax({
            type: "POST",
            url: "EditTestType.php",
            data: {
                id: id
            },
            success: function (response) {
                $('#edit_body').html(response);
                $('#edit_test_type').modal('show');
            }
        });
    }

   function Delete(table, key, id) {
        swal({
          title: 'Are you sure?',
          text: "You will not be able to recover this Entry!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!'
      }).then(function(result) {
        if(result.value){
          $.ajax({
            type: "POST",
            url: "Delete.php",
            data: {
                table: table,
                key: key,
                id: id
            },
            success: function (response) {
                if (response == '1') {
                    swal("Deleted!", "Selected Data has been deleted!", "success");
                    location.href = "TestType";
                } else {
                    swal({
                        title: "Oops!",
                        text: "Something Wrong...",
                        imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
                    });
                }
            }
        });
      }else{
        swal("Cancelled", "Your Entry Data is safe :)", "error");
    }
})
  }


    window.onload = function () {

        if (document.getElementById('insert_success')) {
            swal("Success!", "New Test has been Added!", "success");
        }

         if (document.getElementById('import_success')) {
            swal("Success!", "Test Data Imported", "success");
        }

        if (document.getElementById('insert_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

        if (document.getElementById('update_success')) {
            swal("Success!", "Test has been Updated!", "success");
        }

        if (document.getElementById('update_failure')) {
            swal({
                title: "Oops!",
                text: "Something Wrong...",
                imageUrl: 'vendor/bootstrap-sweetalert/assets/error_icon.png'
            });
        }

    }
</script>

<script>
$(document).ready(function() {
	  $('#example').DataTable()
	});
	
		$(document).ready(function() {
			var table = $('#example2').DataTable( {
				lengthChange: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
		 
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
</script>

</body>
</html>