<?php
include_once 'booster/bridge.php';
IsAjaxRequest();

$i = $_POST['table_id'];
$no = $_POST['sl_no'];
$CategoryID = $_POST['test_category'];

$CategoryData = SelectParticularRow('macho_test_category', 'id', $CategoryID);
$type = $CategoryData['type'];
if ($type == 'group') {
    $ProfileQuery = "SELECT * FROM macho_profile_tests WHERE profile_id='$CategoryID'";
    $ProfileResult = GetAllRows($ProfileQuery);
    foreach ($ProfileResult as $ProfileData) {
        $test_id = $ProfileData['item_id'];

        $TestTypeData = SelectParticularRow('macho_test_type', 'id', $test_id);
        ?>
        <tr class="row_class" id="addr<?= ++$i; ?>">
            <td style='text-align: center' class='serial_num'><span class='sl_no'>
                    <?= ++$no; ?>
                </span></td>
            <td style='text-align: left'><input value='<?= $TestTypeData['id']; ?>' name='item_id[]' type='hidden'>
                <input value='test' name='item_type[]' type='hidden'>
                <input value='<?= $TestTypeData['test_name']; ?>' name='item_name[]' type='hidden'>
                <input value='<?= $TestTypeData['test_category']; ?>' name='item_category[]' type='hidden'>
                <input value='<?= $TestTypeData['price']; ?>' name='item_rate[]' type='hidden'>
                <input value='0' name='item_gst2[]' type='hidden'>
                <input value='0' name='item_gst_amount[]' type='hidden'>
                <input value='1' name='item_quantity[]' type='hidden'>
                <input value='LS' name='item_uom[]' type='hidden'>
                <input value='<?= $TestTypeData['price']; ?>' name='item_amount[]' type='hidden'>
                <?= $TestTypeData['test_name']; ?>
            </td>
            <td style='text-align: right'>
                <?= $TestTypeData['price']; ?>
            </td>
            <td style='text-align: right'>1 LS</td>
            <td style='text-align: right'>
                <?= $TestTypeData['price']; ?>
            </td>
            <td width='50px' style='text-align: center' valign='middle'>
                <button title='Remove' class='btn btn-info btn-danger fa fa-remove' onclick="delete_row(<?= $i; ?>)"></button>
            </td>
        </tr>
        <?php
    }
} else {
    $TestTypeQuery = "SELECT * FROM macho_test_type WHERE test_category='$CategoryID'";
    $TestTypeResult = GetAllRows($TestTypeQuery);
    foreach ($TestTypeResult as $TestTypeData) {
        ?>
        <tr class="row_class" id="addr<?= ++$i; ?>">
            <td style='text-align: center' class='serial_num'><span class='sl_no'>
                    <?= ++$no; ?>
                </span></td>
            <td style='text-align: left'><input value='<?= $TestTypeData['id']; ?>' name='item_id[]' type='hidden'>
                <input value='test' name='item_type[]' type='hidden'>
                <input value='<?= $TestTypeData['test_name']; ?>' name='item_name[]' type='hidden'>
                <input value='<?= $TestTypeData['test_category']; ?>' name='item_category[]' type='hidden'>
                <input value='<?= $TestTypeData['price']; ?>' name='item_rate[]' type='hidden'>
                <input value='0' name='item_gst2[]' type='hidden'>
                <input value='0' name='item_gst_amount[]' type='hidden'>
                <input value='1' name='item_quantity[]' type='hidden'>
                <input value='LS' name='item_uom[]' type='hidden'>
                <input value='<?= $TestTypeData['price']; ?>' name='item_amount[]' type='hidden'>
                <?= $TestTypeData['test_name']; ?>
            </td>
            <td style='text-align: right'>
                <?= $TestTypeData['price']; ?>
            </td>
            <td style='text-align: right'>1 LS</td>
            <td style='text-align: right'>
                <?= $TestTypeData['price']; ?>
            </td>
            <td width='50px' style='text-align: center' valign='middle'>
                <button title='Remove' class='btn btn-info btn-danger fa fa-remove' onclick="delete_row(<?= $i; ?>)"></button>
            </td>
        </tr>
        <?php
    }
}
?>