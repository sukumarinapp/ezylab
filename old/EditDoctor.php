<?php
session_start();
include_once 'booster/bridge.php';
IsAjaxRequest();
$patient_id = Filter($_POST['id']);

$patientData = SelectParticularRow('doctors', 'id', $patient_id);
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="Doctors">
            <input type="hidden" name="dept_id" id="dept_id" value="1" />
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID Number</label>
                                <input type="hidden" name="doctor_id" id="doctor_id" value="<?= $patient_id; ?>">
                                <input type="text"
                                       class="form-control"
                                       name="id_number"
                                       id="id_number" value="<?= $patientData['id_number']; ?>"
                                       tabindex="1">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                    <textarea rows="1" class="form-control"
                                              name="address"
                                              id="address"
                                              maxlength="100"
                                              tabindex="7"><?= $patientData['address']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Mobile </label>
                                <input type="text"
                                       class="form-control"
                                       name="mobile"
                                       id="mobile" value="<?= $patientData['mobile']; ?>"
                                       maxlength="10" onkeypress="return isNumberKey(event)"
                                       tabindex="5">
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Name</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control" name="prefix" id="prefix"
                                                tabindex="2"
                                                required>
                                            <option value="Mr. " <?php if ($patientData['prefix'] == 'Mr. ') echo 'selected'; ?>>Mr.</option>
                                            <option value="Mrs. " <?php if ($patientData['prefix'] == 'Mrs. ') echo 'selected'; ?>>Mrs.</option>
                                            <option value="Miss. " <?php if ($patientData['prefix'] == 'Miss. ') echo 'selected'; ?>>Miss.</option>
                                            <option value="Ms. " <?php if ($patientData['prefix'] == 'Ms. ') echo 'selected'; ?>>Ms.</option>
                                            <option value="Master. " <?php if ($patientData['prefix'] == 'Master. ') echo 'selected'; ?>>Master.</option>
                                            <option value="Baby. " <?php if ($patientData['prefix'] == 'Baby. ') echo 'selected'; ?>>Baby.</option>
                                            <option value="Selvi. " <?php if ($patientData['prefix'] == 'Selvi. ') echo 'selected'; ?>>Selvi.</option>
                                            <option value="Sr. " <?php if ($patientData['prefix'] == 'Sr. ') echo 'selected'; ?>>Sr.</option>
                                            <option value="Rev.Fr. " <?php if ($patientData['prefix'] == 'Rev.Fr. ') echo 'selected'; ?>>Rev.Fr.</option>
                                            <option value="Dr. " <?php if ($patientData['prefix'] == 'Dr. ') echo 'selected'; ?>>Dr.</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text"
                                               class="form-control"
                                               name="d_name"
                                               id="d_name" value="<?= $patientData['d_name']; ?>"
                                               maxlength="100"
                                               tabindex="2">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Qualification</label>
                                <input type="text"
                                       class="form-control"
                                       name="qualification"
                                       id="qualification" value="<?= $patientData['qualification']; ?>"
                                       maxlength="100"
                                       tabindex="4">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email"
                                       class="form-control"
                                       name="email"
                                       id="email"
                                       value="<?= $patientData['email']; ?>" maxlength="100"
                                       tabindex="6">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="clearfix">
                        <div class="float-right">
                            <button class="btn btn-warning" type="submit" name="update" tabindex="8">
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