<?php
include_once 'booster/bridge.php';

IsAjaxRequest();

$product_id = Filter($_POST['id']);

$ProductData = SelectParticularRow('macho_master_products', 'id', $product_id);
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="Products" enctype="multipart/form-data">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="hidden" name="product_id" id="product_id" value="<?= $product_id; ?>">
                                <input type="text"
                                       class="form-control"
                                       name="product_code"
                                       id="product_code" value="<?= $ProductData['product_code'] ?>"
                                       readonly
                                       tabindex="1">
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text"
                                       class="form-control"
                                       name="product_name"
                                       id="product_name"
                                       value="<?= $ProductData['product_name'] ?>" maxlength="100"
                                       tabindex="2">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text"
                                       class="form-control"
                                       name="product_lang_name"
                                       id="product_lang_name"
                                       value="<?= $ProductData['product_lang_name'] ?>" maxlength="100"
                                       tabindex="3">
                            </div>
<!--                            <div class="form-group">-->
<!--                                <label>Unit </label>-->
<!--                                <select class="form-control"-->
<!--                                        name="uom"-->
<!--                                        id="uom"-->
<!--                                        tabindex="8">-->
<!--                                    <option value="0">Enter Unit</option>-->
<!--                                    --><?php
//                                    $MeasurementQuery = "SELECT * FROM macho_uom ORDER BY measurement";
//                                    $MeasurementData = GetAllRows($MeasurementQuery);
//                                    foreach ($MeasurementData as $Measurements) {
//                                        echo '<option ';
//                                        if ($ProductData['uom'] == $Measurements['symbol']) echo " selected ";
//                                        echo ' value="' . $Measurements['symbol'] . '">' . $Measurements['measurement'] . '</option>';
//                                    } ?>
<!--                                </select>-->
<!--                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="clearfix">
                        <div class="float-right">
                            <button class="btn btn-warning" type="submit" name="update" tabindex="24">
                                Save Changes
                            </button>
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">
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