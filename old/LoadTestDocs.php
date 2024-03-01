<?php
include_once 'booster/bridge.php';

IsAjaxRequest();

$id = Filter($_POST['entry_id']);
$test_id = Filter($_POST['test_id']);
?>
<h6>Upload Files</h6>
<table class="table table-striped my-4 w-100">
    <thead>
    <tr>
        <td>#</td>
        <td>File Name</td>
        <td>File Type</td>
        <td>File Size</td>
        <td>Action</td>
    </tr>
    </thead>
    <tbody>

    <?php
    $no = 0;
    $LoanQuery = "SELECT * FROM test_documents WHERE entry_id='$id' ORDER BY document_id DESC ";
    $LoanResult = GetAllRows($LoanQuery);
    $LoanCounts = count($LoanResult);
    if ($LoanCounts > 0) {
        foreach ($LoanResult as $LoanData) {
            ?>
            <tr id="document_row_<?= $LoanData['document_id']; ?>">
                <td><?= ++$no; ?></td>
                <td><?= $LoanData['description']; ?></td>
                <td><?= $LoanData['file_type']; ?></td>
                <td><?= $LoanData['file_size']; ?></td>
                <td><?php if ($LoanData['delete_status'] == 1) { ?><img src="images/delete.png" height="25px"
                                                                        width="25px"
                                                                        onclick="document_delete(<?= $LoanData['document_id']; ?>,'<?= $LoanData['file_url']; ?>');" /> <?php
                    } else { ?>
                    <button class="btn btn-success btn-xs" type="button"
                            title="View / Download Now"
                            onclick="window.open('<?php echo $LoanData['file_url']; ?>', '_blank');">
                            <i class="fa fa-file"></i></button><?php } ?></td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>

</table>



