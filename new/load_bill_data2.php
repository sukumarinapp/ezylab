<?php
include_once 'booster/bridge.php';
IsAjaxRequest();

$profile_id = $_POST['profile_id'];
$i = 0;
$no = 0;

$ProfileQuery = "SELECT * FROM macho_profile_tests WHERE profile_id='$profile_id'";
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
        <td width='50px' style='text-align: center' valign='middle'>
            <button class='btn btn-danger' onclick="delete_row(<?= $i; ?>)"><em class="fa fa-trash"></em></button>
        </td>
    </tr>
    <?php
}
?>