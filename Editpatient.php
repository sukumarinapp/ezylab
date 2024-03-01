<?php
session_start();
include_once 'booster/bridge.php';
IsAjaxRequest();
$patient_id = Filter($_POST['id']);

$patientData = SelectParticularRow('macho_patient', 'id', $patient_id);
?>
<div class="row">
    <div class="col-xl-12">
        <form method="post" action="Patient">
            <!-- START card-->
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Patient ID</label>
                                <input type="hidden" name="patient_id" id="patient_id" value="<?= $patient_id; ?>">
                                <input type="text"
                                       class="form-control"
                                       name="P_code"
                                       id="P_code" value="<?= $patientData['P_code']; ?>"
                                       readonly
                                       tabindex="1">
                            </div>
                            <div class="form-group">
                                <label>Gender </label>
                                <select class="form-control" name="gender" id="gender" tabindex="3">
                                    <option>Select Gender</option>
                                    <option
                                        value="Male" <?php if ($patientData['gender'] == 'Male') echo 'selected'; ?> >
                                        Male
                                    </option>
                                    <option
                                        value="Female" <?php if ($patientData['gender'] == 'Female') echo 'selected'; ?> >
                                        Female
                                    </option>
                                    <option
                                        value="Trans Gender" <?php if ($patientData['gender'] == 'Trans Gender') echo 'selected'; ?> >
                                        Trans Gender
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                                <label>Data of Birth</label>
                                                <input type="date"
                                                       class="form-control"
                                                       name="dob"
                                                       id="dob"  value="<?= from_sql_date($patientData['dob']); ?>" autocomplete="off"
                                                       maxlength="100" 
                                                       tabindex="5">
                                            </div>
                                            <div class="form-group">
                                                <label>OP Number</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="ob_number"
                                                       id="ob_number" value="<?= $patientData['ob_number']; ?>"
                                                       maxlength="100"
                                                       tabindex="7">
                                            </div>
                                            <div class="form-group">
                                                <label>ID Card</label>
                                                <select class="form-control" tabindex="9"
                                                        id="id_card_type"
                                                        name="id_card_type" >
                                                    <option value="">Select Identity Card Type</option>
                                                    <option value="Aadhaar card" <?php if ($patientData['id_card_type'] == 'Aadhaar card') echo 'selected'; ?>>Aadhaar card</option>
                                                    <option value="Driving licence" <?php if ($patientData['id_card_type'] == 'Driving licence') echo 'selected'; ?>>Driving licence</option>
                                                    <option value="Electoral Photo Identity Card" <?php if ($patientData['id_card_type'] == 'Electoral Photo Identity Card') echo 'selected'; ?>>Electoral Photo
                                                        Identity Card
                                                    </option>
                                                    <option value="passport" <?php if ($patientData['id_card_type'] == 'passport') echo 'selected'; ?>>passport</option>
                                                    <option value="Permanent account number" <?php if ($patientData['id_card_type'] == 'Permanent account number') echo 'selected'; ?>>Permanent account number
                                                    </option>
                                                    <option value="Ration card" <?php if ($patientData['id_card_type'] == 'Ration card') echo 'selected'; ?>>Ration card</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                <label>Mobile </label>
                                <input type="text"
                                       class="form-control"
                                       name="mobile"
                                       id="mobile" onkeypress="return isNumberKey(event)"
                                       value="<?= $patientData['mobile']; ?>" maxlength="100"
                                       tabindex="11">
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
                                               name="P_name"
                                               id="P_name" value="<?= $patientData['P_name']; ?>"
                                               maxlength="100"
                                               tabindex="2">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Blood Group</label>
                                <select class="form-control" id="blood_group" name="blood_group" tabindex="4">
                                    <option value="0">Enter Blood Group</option>
                                    <?php
                                    $BGQuery = "SELECT blood_group,symbol FROM macho_bloodgroup ORDER BY blood_group";
                                    $BGResult = GetAllRows($BGQuery);
                                    $BGCounts = count($BGResult);
                                    if ($BGCounts > 0) {
                                        foreach ($BGResult as $BGData) {
                                            echo '<option ';
                                            if ($patientData['bg'] == $BGData['symbol']) echo " selected ";
                                            echo ' value="' . $BGData['symbol'] . '">' . $BGData['blood_group'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Age</label>

                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text"
                                               class="form-control"
                                               name="age"
                                               id="age" value="<?= $patientData['age']; ?>"
                                               maxlength="100"
                                               tabindex="6">
                                    </div>
                                    <div class="col-md-6">
                                    <input type="text"
                                               class="form-control"
                                               name="age_type"
                                               id="age_type" value="<?= $patientData['age_type']; ?>"
                                               maxlength="100"
                                               tabindex="6">
                                        <!-- <select class="form-control"
                                                tabindex="4" id="age_type"
                                                name="age_type">
                                            <option
                                                value="Yrs" <?php //if ($patientData['age_type'] == 'Yrs') echo 'selected'; ?>>
                                                Years
                                            </option>
                                            <option
                                                value="Mths" <?php //if ($patientData['age_type'] == 'Mths') echo 'selected'; ?>>
                                                Months
                                            </option>
                                            <option
                                                value="Wks" <?php //if ($patientData['age_type'] == 'Wks') echo 'selected'; ?>>
                                                Weeks
                                            </option>
                                            <option
                                                value="Days" <?php //if ($patientData['age_type'] == 'Days') echo 'selected'; ?>>
                                                Days
                                            </option>
                                        </select> -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                                <label>Room Number</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="room_number"
                                                       id="room_number" value="<?= $patientData['room_number']; ?>"
                                                       maxlength="100"
                                                       tabindex="8">
                                            </div>
                                            <div class="form-group">
                                                <label>ID Number</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="id_number"
                                                       id="id_number" value="<?= $patientData['id_number']; ?>"
                                                       maxlength="100" 
                                                       tabindex="10">
                                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email"
                                       class="form-control"
                                       name="email"
                                       id="email"
                                       value="<?= $patientData['email']; ?>" maxlength="100"
                                       tabindex="12">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address</label>
                                    <textarea class="form-control"
                                              name="address"
                                              id="address"
                                              maxlength="100"
                                              tabindex="13"><?= $patientData['address']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="clearfix">
                        <div class="float-right">
                            <button class="btn btn-warning" type="submit" name="update" tabindex="24">
                                Save Changes
                            </button>
                            <button class="btn btn-secondary" close="close" type="button" data-bs-dismiss="modal">
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