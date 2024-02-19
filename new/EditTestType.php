<?php
include_once 'booster/bridge.php';
IsAjaxRequest();
$id = Filter($_POST['id']);
$TestTypeData = SelectParticularRow('macho_test_type', 'id', $id);
//print_r($TestTypeData);die;
$today = date("Y-m-d");
?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">

            <label class="col-form-label">Test Code </label>
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
            <input class="form-control" type="text" name="test_code" id="test_code"
                value="<?= $TestTypeData['test_code']; ?>" maxlength="100" tabindex="1" readonly>
        </div>
        <div class="form-group">
            <label class="col-form-label">Price </label>
            <input class="form-control" type="text" name="price" id="price" value="<?= $TestTypeData['price']; ?>"
                maxlength="100" tabindex="3" onkeypress="return isNumberDecimalKey(event)" required>
        </div>

        <div class="form-group">
            <label class="col-form-label">Method </label>
            <input class="form-control" type="text" name="method" id="method" value="<?= $TestTypeData['method']; ?>"
                maxlength="100" tabindex="5">
        </div>
        <div class="form-group">
            <label class="col-form-label">Type of Test </label>
            <select class="form-control" name="type_test" id="type_test2" tabindex="7">
                <option value='Normal' <?php if ($TestTypeData['type_test'] == 'Normal')
                    echo 'selected'; ?>>
                    Normal
                </option>
                <option value='Sub Heading' <?php if ($TestTypeData['type_test'] == 'Sub Heading')
                    echo 'selected'; ?>>
                    Sub Heading
                </option>
                <option value='Paragraph' <?php if ($TestTypeData['type_test'] == 'Paragraph')
                    echo 'selected'; ?>>
                    Paragraph
                </option>
                <option value='Table' <?php if ($TestTypeData['type_test'] == 'Table')
                    echo 'selected'; ?>>
                    Table
                </option>
                <option value='Date' <?php if ($TestTypeData['type_test'] == 'Date')
                    echo 'selected'; ?>>
                    Date
                </option>
                <option value='Time' <?php if ($TestTypeData['type_test'] == 'Time')
                    echo 'selected'; ?>>
                    Time
                </option>
                <option value='Image' <?php if ($TestTypeData['type_test'] == 'Image')
                    echo 'selected'; ?>>
                    Image
                </option>
            </select>
        </div>
        <div class="form-group">
            <label class="col-form-label">Units </label>
            <input value="<?= $TestTypeData['units'] ?>" maxlength="10" class="form-control" name="units" id="units" tabindex="8" />
        </div>
        <div class="form-group form-check">
          <input <?php if($TestTypeData['show_critical_info'] == 1) echo "checked";  ?> name="show_critical_info" class="form-check-input" type="checkbox" value="1" id="show_critical_info2" >
          <label class="form-check-label" for="show_critical_info2">
            Show Critical Info
          </label>
        </div>
        <div class="form-group">
            <label class="col-form-label">Critical Info </label>
            <textarea class="form-control" name="critical_info" id="critical_info" maxlength="500" rows="9"
                tabindex="10"><?= $TestTypeData['critical_info']; ?></textarea>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-form-label">Test Name </label>
            <input class="form-control" type="text" name="test_name" id="test_name"
                value="<?= $TestTypeData['test_name']; ?>" maxlength="100" tabindex="2" required>
        </div>
        <div class="form-group">
            <label class="col-form-label">Formula </label>
            <input class="form-control" type="text" name="remarks" id="remarks" value="<?= $TestTypeData['remarks']; ?>"
                maxlength="100" tabindex="4">
        </div>

        <div class="form-group">
            <label class="col-form-label">Department</label>
            <select class="form-control" name="test_category" id="test_category" tabindex="6">
                <?php
                $TestCategoryQuery = "SELECT * FROM macho_test_category where type='single' ORDER BY id";
                $TestCategoryResult = GetAllRows($TestCategoryQuery);
                foreach ($TestCategoryResult as $TestCategoryData) {
                    echo '<option ';
                    if ($TestTypeData['test_category'] == $TestCategoryData['id'])
                        echo " selected ";
                    echo ' value="' . $TestCategoryData['id'] . '">' . $TestCategoryData['category_name'] . '</option>';
                } ?>
            </select>
        </div>
        
             <div id='table_tab2'>
                <div class="form-group">
                    <label class="col-form-label">Table Input </label>
                    <textarea class="form-control" name="table_input" id="table_input" maxlength="500" rows="5"
                        tabindex="9"><?php echo $TestTypeData['table_input']; ?></textarea>
                </div>
            </div> 
       
            <div id='others_tab2'>
                <div class="form-group">
                    <label class="col-form-label">Lower Limit </label>
                    <input class="form-control" type="text" name="lower_limit" id="lower_limit"
                        value="<?= $TestTypeData['lower_limit']; ?>" maxlength="100" tabindex="9">
                </div>
                <div class="form-group">
                    <label class="col-form-label">Upper Limit </label>
                    <input class="form-control" type="text" name="upper_limit" id="upper_limit"
                        value="<?= $TestTypeData['upper_limit']; ?>" maxlength="100" tabindex="9">
                </div>
            </div>
        <div class="form-group form-check">
              <input <?php if($TestTypeData['show_interpretation']==1) echo "checked";  ?> name="show_interpretation" class="form-check-input" type="checkbox" value="1" id="show_interpretation2" >
              <label class="form-check-label" for="show_interpretation2">
                Show Interpretation
              </label>
            </div>
        <div class="form-group">
            <label class="col-form-label">Interpretations </label>
            <textarea class="form-control" name="interpretation" id="interpretation" maxlength="500" rows="9"
                tabindex="11"><?= $TestTypeData['interpretation']; ?></textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="col-form-label">Sub Heading Name</label>
            <input value="<?= $TestTypeData['sub_head']; ?>" maxlength="100" class="form-control" name="sub_head" id="sub_head" tabindex="8" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="col-form-label">Sample Type</label>
            <input value="<?= $TestTypeData['sample_type']; ?>" maxlength="100" class="form-control" name="sample_type" id="sample_type" tabindex="8" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="col-form-label">Comments </label>
            <textarea class="form-control" name="comments" id="comments" maxlength="500" rows="4"
                tabindex="12"><?= $TestTypeData['comments']; ?></textarea>
        </div>
    </div>
</div>
</div>

<script>
     $( document ).ready(function() {
        var type_test = $('#type_test2').val();
        if (type_test == 'Table') {
            $('#table_tab2').show();
            $('#others_tab2').hide();
        } else {
            $('#others_tab2').show();
            $('#table_tab2').hide();
        }
    

    $('#type_test2').change(function () {
            var type_test = $(this).val();
            if (type_test == 'Table') {
                $('#table_tab2').show();
                $('#others_tab2').hide();
            } else {
                $('#others_tab2').show();
                $('#table_tab2').hide();
            }
        });
    });
</script>