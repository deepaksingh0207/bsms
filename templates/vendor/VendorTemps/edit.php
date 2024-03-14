<?= $this->Html->css('v_vendorCustom') ?>
<?= $this->Html->css('v_vendortemp_view') ?>

<div class="row profile_row">
    <div class="col-sm-12 col-lg-3 v_profile">
        <div class="card">
            <div class="card-body">
                <div class="prof-img text-center">
                    <img width="100px" src="<?= $this->Url->build('/') ?>img/<?= strtoupper(substr($vendorTemp->name,0,1)) ?>.png" alt="Vendor">
                </div>
                <div class="mt-3">
                    <table class="table_centr">
                        <tr>
                            <td>Name</td>
                            <th><?= h($vendorTemp->name) ?></th>
                        </tr>
                        <tr>
                            <td>Mobile No</td>
                            <th><?= h($vendorTemp->mobile) ?></th>
                        </tr>
                        <tr>
                            <td>Email ID</td>
                            <th><?= h($vendorTemp->email) ?></th>
                        </tr>
                        <tr>
                            <td>SAP Vendor Code</td>
                            <th><?= !empty($vendorTemp->sap_vendor_code) ? $vendorTemp->sap_vendor_code : '' ?></th>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <th><?= $vendorTemp->vendor_status->description ?></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-lg-9 v_profile">

        <div class="card" style="display:none;">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-3 mb-4">
                        <label>Company Name</label><br>
                        <?= h($vendorTemp->company_code->name) ?>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 mb-4">
                        <label>Purchase Organisation</label><br>
                        <?= h($vendorTemp->purchasing_organization->name) ?>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 mb-4">
                        <label>Account Groups</label><br>
                        <?= h($vendorTemp->account_group->name) ?>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 mb-4">
                        <label>Reconciliation Accounts</label><br>
                        <?= h($vendorTemp->reconciliation_account->name) ?>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 mb-4">
                        <label>Schema Groups</label><br>
                        <?= h($vendorTemp->schema_group->name) ?>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 mb-4">
                        <label>Payment Terms</label><br>
                        <?= h($vendorTemp->payment_term->description) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-outline-tabs">
            <div class="card-header p-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab_address" data-toggle="pill" href="#custom-tabs-four-profile"
                            role="tab" aria-controls="custom-tabs-four-profile" aria-selected="true">Address</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab_branchoffice" data-toggle="pill" href="#custom-tabs-four-branch"
                            role="tab" aria-controls="custom-tabs-four-branch" aria-selected="false">Branch Office</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab_productionfaculty" data-toggle="pill"
                            href="#custom-tabs-four-productionfaculty" role="tab"
                            aria-controls="custom-tabs-four-productionfaculty" aria-selected="false">
                            Production Facility</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab_contactperson" data-toggle="pill"
                            href="#custom-tabs-four-contactperson" role="tab"
                            aria-controls="custom-tabs-four-contactperson" aria-selected="false">
                            Other Contacts</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab_paymentdetails" data-toggle="pill" href="#custom-tabs-four-home"
                            role="tab" aria-controls="custom-tabs-four-home" aria-selected="false">
                            Payment Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab_certificate" data-toggle="pill" href="#custom-tabs-four-certificate"
                            role="tab" aria-controls="custom-tabs-four-certificate"
                            aria-selected="false">Certificate</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tab_questionnaire " data-toggle="pill"
                            href="#custom-tabs-four-questionnaire" role="tab"
                            aria-controls="custom-tabs-four-questionnaire" aria-selected="false">Questionnaire</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab_customerAddress" data-toggle="pill"
                            href="#custom-tabs-four-customerAddress" role="tab"
                            aria-controls="custom-tabs-four-customerAddress" aria-selected="false">
                            Reputed Customer</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">

                    <div class="tab-pane fade active show" id="custom-tabs-four-profile" role="tabpanel"
                        aria-labelledby="tab_address" style="background-color: white;">

                        <?= $this->Form->create($vendorTemp, ['type' => 'post', 'enctype'=>'multipart/form-data', 'id' => 'id_form_address']) ?>
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>Permanent Address</h5>
                            </div>
                            <div class="card-body">
                                <input required="required" type="hidden" name="temps[id]" id="id_vendor_temps_id"
                                    class="vendor_temp_id" value="<?= h($vendorTemp->id) ?>">
                                <div class="row">
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_temps_address">Address</label>
                                        <input required="required" type="text" maxlength="255" class="form-control"
                                            name="temps[address]" id="id_vendor_temps_address">
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_temps_address_2">Address 1</label>
                                        <input required="required" type="text" class="form-control" maxlength="100"
                                            name="temps[address_2]" id="id_vendor_temps_address_2">
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_temps_pincode">Pincode</label>
                                        <input required="required" type="number" maxlength="6"
                                            class="form-control maxlength_validation" name="temps[pincode]"
                                            id="id_vendor_temps_pincode">
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_temps_city">City</label>
                                        <input required="required" type="text" maxlength="50" class="form-control alphaonly capitalize"
                                            name="temps[city]" id="id_vendor_temps_city">
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <?php echo $this->Form->control('country_id', ['name' => 'temps[country_id]', 'id' => 'id_vendor_temps_country', 'data-state'=>'id_vendor_temps_state','class' => 'country_id_option selectpicker form-control', 'options' => $vt_countries, 'data-live-search' => 'true', 'required'=>'true', 'empty' => 'Please Select', 'title' => 'Select Country']); ?>
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_temps_state">State</label>
                                        <select class="form-control" name="temps[state_id]"
                                            id="id_vendor_temps_state"></select>
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <div class="form-group required">
                                            <?php
                                            $businessTypes  = [
                                                'PROPRIETARY' => 'Proprietary',
                                                'PARTNERSHIP' => 'Partnership Concern',
                                                'PUBLIC_LIMITED' => 'Public Limited Company',
                                                'PRIVATE_LIMITED' => 'Private Limited Company'
                                            ];
                                            echo $this->Form->control('status', ['name'=>'temps[business_type]', 'class' => 'form-control', 'options' => $businessTypes, 'label' => 'Status']);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <span style="font-weight: 500;">REGISTERED OFFICE ADDRESS</span>
                                <span class="same_addr float-right">
                                    <input type="checkbox" id="copypermanant">
                                    <label for="copypermanant">Same as Permanent Address</label>
                                </span>
                            </div>
                            <div class="card-body">
                                <input required="required" type="hidden" name="registered_offices[id]"
                                    id="id_vendor_registered_offices_id">
                                <input required="required" type="hidden" name="registered_offices[vendor_temp_id]"
                                    id="id_vendor_registered_offices_vendor_temp_id" value="<?= h($vendorTemp->id) ?>"
                                    class="vendor_temp_id">
                                <div class="row">
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_registered_offices_address">Address</label>
                                        <input required="required" type="text" class="form-control"
                                            name="registered_offices[address]" maxlength="250"
                                            id="id_vendor_registered_offices_address">
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_registered_offices_address_2">Address 1</label>
                                        <input required="required" type="text" class="form-control"
                                            name="registered_offices[address_2]" maxlength="250"
                                            id="id_vendor_registered_offices_address_2">
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_registered_offices_pincode">Pincode</label>
                                        <input required="required" type="number" class="form-control maxlength_validation"
                                            maxlength="6" name="registered_offices[pincode]"
                                            id="id_vendor_registered_offices_pincode">
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_registered_offices_city">City</label>
                                        <input required="required" type="text" class="form-control alphaonly capitalize"
                                            name="registered_offices[city]" maxlength="200" id="id_vendor_registered_offices_city">
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <?php echo $this->Form->control('registered_offices[country]', ['data-state' =>'id_vendor_registered_offices_state', 'class' => 'selectpicker show-menu-arrow form-control my-select1 country_code_option', 'options' => $countries, 'empty' => 'Select Country', 'label' => 'Country', 'data-live-search' => 'true', 'title' => 'Select Country', 'id' => 'id_vendor_registered_offices_country']); ?>
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <?php echo $this->Form->control('registered_offices[state]', ['class' => 'selectpicker form-control my-select1', 'options' => $states, 'label' => 'State', 'data-live-search' => 'true', 'empty' => 'Select State', 'title' => 'Select State', 'id' => 'id_vendor_registered_offices_state']); ?>
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3">
                                        <label for="id_vendor_registered_offices_telephone">Telephone</label>
                                        <input type="text"
                                            class="form-control numberonly maxlength_validation"
                                            name="registered_offices[telephone]"
                                            id="id_vendor_registered_offices_telephone" minlength="10" maxlength="14">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="float-right">
                            <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'submit', 'data-id' => 'address')); ?>
                        </span>
                        <?= $this->Form->end() ?>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-four-branch" role="tabpanel"
                        maria-labelledby="tab_branchoffice" style="background-color: white;">
                        <?= $this->Form->create($vendorTemp, ['type' => 'post', 'enctype'=>'multipart/form-data', 'id' => 'id_form_branch_office']) ?>
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <p style="text-transform: uppercase; font-weight: 500; font-size: inherit;">
                                    Branch Office Address
                                </p>
                                <span class="badge lgreenbadge mt-1" id="id_branch_offices_add">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                            </div>
                            <div class="card-body" id="id_vendor_branch_offices_body">
                                <div class="card">
                                    <div class="card-body">
                                        <input required="required" type="hidden"
                                            name="branch_offices[0][vendor_temp_id]"
                                            id="id_vendor_branch_offices_0_vendor_temp_id" data-id="0"
                                            class="vendor_temp_id branch_offices" value="<?= h($vendorTemp->id) ?>">
                                        <input required="required" type="hidden" name="branch_offices[0][id]"
                                            id="id_vendor_branch_offices_0_id">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_branch_offices_address">Address</label>
                                                <input required="required" maxlength="45" type="text" class="form-control"
                                                    name="branch_offices[0][address]"
                                                    id="id_vendor_branch_offices_0_address">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_branch_offices_address_2">Address 1</label>
                                                <input required="required" maxlength="45" type="text" class="form-control"
                                                    name="branch_offices[0][address_2]"
                                                    id="id_vendor_branch_offices_0_address_2">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_branch_offices_pincode">Pincode</label>
                                                <input required="required" maxlength="6" type="number"
                                                    class="form-control maxlength_validation"
                                                    name="branch_offices[0][pincode]" maxlength="6"
                                                    id="id_vendor_branch_offices_0_pincode">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_branch_offices_city">City</label>
                                                <input required="required" maxlength="45" type="text"
                                                    class="form-control alphaonly capitalize"
                                                    name="branch_offices[0][city]" id="id_vendor_branch_offices_0_city">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <?php echo $this->Form->control('branch_office_country', ['name' => 'branch_offices[0][country]','data-state' =>'id_vendor_branch_offices_0_state', 'class' => 'selectpicker form-control my-select country_code_option', 'options' => $countries, 'empty' => 'Select Country', 'label' => 'Country', 'data-live-search' => 'true', 'title' => 'Select Country',  'id' => 'id_vendor_branch_offices_country']); ?>
                                                <!-- <label for="id_vendor_branch_offices_country">Country</label>
                                                <select class="form-control"
                                                    data-state="id_vendor_branch_offices_0_state"
                                                    name="branch_offices[0][country]"
                                                    id="id_vendor_branch_offices_0_country"></select> -->
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_branch_offices_state">State</label>
                                                <select class="form-control" maxlength="45" name="branch_offices[0][state]"
                                                    id="id_vendor_branch_offices_0_state">
                                                    <option>Please Select</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_branch_offices_telephone">Telephone</label>
                                                <input required="required" type="text" 
                                                    class="form-control numberonly maxlength_validation"
                                                    name="branch_offices[0][telephone]"
                                                    id="id_vendor_branch_offices_0_telephone" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_branch_offices_registration_year"> Year of
                                                    Registration</label>
                                                <input required="required" type="number" max="9999" 
                                                    class="form-control maxlength_validation"
                                                    name="branch_offices[0][registration_year]"
                                                    id="id_vendor_branch_offices_0_registration_year" maxlength="4">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_branch_offices_registration_no">Registration
                                                    No</label>
                                                <input required="required" type="text" maxlength="15" class="form-control"
                                                    name="branch_offices[0][registration_no]"
                                                    id="id_vendor_branch_offices_0_registration_no">
                                            </div>
                                            <div class="col-sm-12 col-md-4 mb-3">
                                                <label class="form-label">Registration Certificate</label>
                                                <div class="custom-file">
                                                    <input
                                                        name="branch_offices[0][registration_certificate]" type="file"
                                                        accept=".pdf" class="custom-file-input"
                                                        id="id_vendor_branch_offices_0_registration_certificate">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>Industry Registered Details</h5>
                            </div>
                            <div class="card-body">
                                <input required="required" type="hidden" name="small_scale[id]"
                                    id="id_vendor_small_scales_id">
                                <input required="required" type="hidden" name="small_scale[vendor_temp_id]"
                                    id="id_vendor_small_scales_vendor_temp_id" value="<?= h($vendorTemp->id) ?>"
                                    class="vendor_temp_id">
                                <div class="row">
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_small_scales_year">
                                            Year of Registration
                                        </label>
                                        <input required="required" type="number" class="form-control maxlength_validation"
                                            name="small_scale[year]" id="id_vendor_small_scales_year" maxlength="4">
                                    </div>
                                    <div class="col-sm-12 col-md-3 mb-3 required">
                                        <label for="id_vendor_small_scales_registration_no">
                                            Registration No
                                        </label>
                                        <input required="required" type="text" class="form-control"
                                            name="small_scale[registration_no]" maxlength="45"
                                            id="id_vendor_small_scales_registration_no">
                                    </div>
                                    <div class="col-sm-12 col-md-4 mb-3 required">
                                        <label class="form-label">
                                            Registration Certificate
                                        </label>
                                        <div class="custom-file">
                                            <input name="small_scale[certificate_file]" type="file"
                                                accept=".pdf" class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_small_scales_certificate_file"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="float-right">
                            <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'submit', 'data-id' => 'branch_office')); ?>
                        </span>
                        <?= $this->Form->end() ?>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-four-productionfaculty" role="tabpanel"
                        aria-labelledby="tab_productionfaculty" style="background-color: white;">

                        <?= $this->Form->create($vendorTemp, ['type' => 'post', 'enctype'=>'multipart/form-data', 'id' => 'id_form_production_facility']) ?>
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>Facility Details</h5>
                            </div>
                            <div class="card-body">
                                <input required="required" type="hidden" name="facilities[id]"
                                    id="id_vendor_facilities_id">
                                <input required="required" type="hidden" name="facilities[vendor_temp_id]"
                                    id="id_vendor_facilities_vendor_temp_id" class="vendor_temp_id"
                                    value="<?= h($vendorTemp->id) ?>">
                                <div class="row">
                                    <div class="col-sm-12 col-md-4 mb-3" required="required">
                                        <label for="">Laboratory facilities available</label><br>
                                        <input required="required" class="id_vendor_facilities_lab_facility"
                                            type="radio" name="facilities[lab_facility]" 
                                            id="id_vendor_facilities_lab_facility_yes" value="yes">
                                        &nbsp;Yes&nbsp;&nbsp;
                                        <input required="required" class="id_vendor_facilities_lab_facility"
                                            type="radio" name="facilities[lab_facility]"
                                            id="id_vendor_facilities_lab_facility_no" value="no">
                                        &nbsp;No
                                        <div class="custom-file mt-2 hide" id="id_vendor_facilities_lab_facility_file">
                                            <input name="facilities[lab_facility_file]"
                                                type="file" accept=".pdf" class="custom-file-input"
                                                id="id_vendor_facilities_lab_facility_file">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_facilities_lab_facility_file"></a>
                                    </div>
                                    <div class="col-sm-12 col-md-4 mb-3" required="required">
                                        <label for="">Whether there is any ISI registration</label><br>
                                        <input required="required" type="radio" name="facilities[isi_registration]"
                                            class="id_vendor_facilities_isi_registration"
                                            id="id_vendor_facilities_isi_registration_yes" value="yes">
                                        &nbsp;Yes&nbsp;&nbsp;
                                        <input required="required" type="radio" name="facilities[isi_registration]"
                                            class="id_vendor_facilities_isi_registration"
                                            id="id_vendor_facilities_isi_registration_no" value="no">
                                        &nbsp;No
                                        <div class="custom-file mt-2 hide"
                                            id="id_vendor_facilities_isi_registration_file">
                                            <input name="facilities[isi_registration_file]"
                                                type="file" accept=".pdf" class="custom-file-input"
                                                id="id_vendor_facilities_lab_facility_file">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_facilities_isi_registration_file"></a>
                                    </div>
                                    <div class="col-sm-12 col-md-4 mb-3" required="required">
                                        <label for="">Test facilities available</label><br>
                                        <input required="required" type="radio" name="facilities[test_facility]"
                                            class="id_vendor_facilities_test_facility"
                                            id="id_vendor_facilities_test_facility_yes" value="yes">
                                        &nbsp;Yes&nbsp;&nbsp;
                                        <input required="required" type="radio" name="facilities[test_facility]"
                                            class="id_vendor_facilities_test_facility"
                                            id="id_vendor_facilities_test_facility_no" value="no">
                                        &nbsp;No
                                        <div class="custom-file mt-2 hide" id="id_vendor_facilities_test_facility_file">
                                            <input name="facilities[test_facility_file]"
                                                type="file" accept=".pdf" class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_facilities_test_facility_file"></a>
                                    </div>
                                    <div class="col-sm-12 col-md-4 mb-3" required="required">
                                        <label for="">Facilities for effective after sales services</label><br>
                                        <input required="required" type="radio"
                                            class="id_vendor_facilities_sales_services"
                                            name="facilities[sales_services]"
                                            id="id_vendor_facilities_sales_services_yes" value="yes">
                                        &nbsp;Yes&nbsp;&nbsp;
                                        <input required="required" type="radio"
                                            class="id_vendor_facilities_sales_services"
                                            name="facilities[sales_services]"
                                            id="id_vendor_facilities_sales_services_no" value="no">
                                        &nbsp;No
                                        <div class="custom-file mt-2 hide"
                                            id="id_vendor_facilities_sales_services_file">
                                            <input name="facilities[sales_services_file]"
                                                type="file" accept=".pdf" class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_facilities_sales_services_file"></a>
                                    </div>
                                    <div class="col-sm-12 col-md-4 mb-3" required="required">
                                        <label for="">Quality control procedure adopted</label><br>
                                        <input required="required" type="radio"
                                            class="id_vendor_facilities_quality_control"
                                            name="facilities[quality_control]"
                                            id="id_vendor_facilities_quality_control_yes" value="yes">
                                        &nbsp;Yes&nbsp;&nbsp;
                                        <input required="required" type="radio"
                                            class="id_vendor_facilities_quality_control"
                                            name="facilities[quality_control]"
                                            id="id_vendor_facilities_quality_control_no" value="no">
                                        &nbsp;No
                                        <div class="custom-file mt-2 hide"
                                            id="id_vendor_facilities_quality_control_file">
                                            <input name="facilities[quality_control_file]"
                                                type="file" accept=".pdf" class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_facilities_quality_control_file"></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>Annual turn over in last 3 years (In Rupee)</h5>
                            </div>
                            <div class="card-body">
                                <input required="required" type="hidden" name="turnovers[id]"
                                    id="id_vendor_turnovers_id">
                                <input required="required" type="hidden" name="turnovers[vendor_temp_id]"
                                    id="id_vendor_turnovers_vendor_temp_id" class="vendor_temp_id"
                                    value="<?= h($vendorTemp->id) ?>">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="id_vendor_turnovers_first_year" class="first_year"></label>
                                        <input required="required" type="hidden" name="turnovers[first_year]"
                                            id="id_vendor_turnovers_first_year">
                                        <input required="required" type="text" class="form-control numberonly"
                                            name="turnovers[first_year_turnover]"
                                            id="id_vendor_turnovers_first_year_turnover">
                                    </div>
                                    <div class="col-4">
                                        <label for="id_vendor_turnovers_second_year" class="second_year"></label>
                                        <input required="required" type="hidden" name="turnovers[second_year]"
                                            id="id_vendor_turnovers_second_year">
                                        <input required="required" type="text" class="form-control numberonly"
                                            name="turnovers[second_year_turnover]"
                                            id="id_vendor_turnovers_second_year_turnover">
                                    </div>
                                    <div class="col-4">
                                        <label for="id_vendor_turnovers_third_year" class="third_year"></label>
                                        <input required="required" type="hidden" name="turnovers[third_year]"
                                            id="id_vendor_turnovers_third_year">
                                        <input required="required" type="text" class="form-control numberonly"
                                            name="turnovers[third_year_turnover]"
                                            id="id_vendor_turnovers_third_year_turnover">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>Income tax Clearing certificate</h5>
                            </div>
                            <div class="card-body">
                                <input required="required" type="hidden" name="incometaxes[id]"
                                    id="id_vendor_incometaxes_id">
                                <input required="required" type="hidden" name="incometaxes[vendor_temp_id]"
                                    id="id_vendor_incometaxes_vendor_temp_id" class="vendor_temp_id"
                                    value="<?= h($vendorTemp->id) ?>">
                                <div class="row">
                                    <div class="col-lg-3 required">
                                        <label>Certificate No</label>
                                        <input required="required" type="number" required="true"
                                            name="incometaxes[certificate_no]" class="form-control"
                                            id="id_vendor_incometaxes_certificate_no">
                                    </div>
                                    <div class="col-lg-3 required">
                                        <label>Certificate Date</label>
                                        <input required="required" type="date" required="true"
                                            id="id_vendor_incometaxes_certificate_date"
                                            name="incometaxes[certificate_date]" class="form-control">
                                    </div>
                                    <div class="col-lg-3 required">
                                        <label class="form-label">Certificate Document</label>
                                        <div class="custom-file">
                                            <input name="incometaxes[certificate_file]"
                                                type="file" accept=".pdf" class="custom-file-input"
                                                id="id_vendor_incometaxes_certificate_file">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_incometaxes_certificate_file"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-4 required">
                                        <label class="form-label">Latest Copy of Balance Sheet</label>
                                        <div class="custom-file">
                                            <input name="incometaxes[balance_sheet_file]"
                                                type="file" accept=".pdf" class="custom-file-input"
                                                id="id_vendor_incometaxes_balance_sheet_file">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_incometaxes_balance_sheet_file"></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <p style="text-transform: uppercase; font-weight: 500; font-size: inherit;">
                                    Factory Address
                                    <span class="badge lgreenbadge mt-1 float-right" id="id_vendor_factories_add">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                </p>
                            </div>
                            <div class="card-body p-0" id="id_vendor_factories_body">
                                <div class="card mb-0" id="vf_killme0">
                                    <div class="card-body">
                                        <div class="row" id="factory_office_0_row0">
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <input required="required" type="hidden" name="factories[0][id]"
                                                    id="id_vendor_factories_0_id">
                                                <input required="required" type="hidden"
                                                    name="factories[0][vendor_temp_id]"
                                                    id="id_vendor_factories_0_vendor_temp_id" data-id="0"
                                                    class="vendor_factories vendor_temp_id"
                                                    value="<?= h($vendorTemp->id) ?>">
                                                <label for="id_vendor_factories_0_address">Address</label>
                                                <input required="required" type="text" maxlength="255" class="form-control"
                                                    name="factories[0][address]" id="id_vendor_factories_0_address">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_factories_0_address_2">Address 1</label>
                                                <input required="required" type="text" maxlength="100" class="form-control"
                                                    name="factories[0][address_2]" id="id_vendor_factories_0_address_2">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_factories_0_pincode">Pincode</label>
                                                <input required="required" type="number"
                                                    class="form-control maxlength_validation"
                                                    name="factories[0][pincode]" maxlength="6"
                                                    id="id_vendor_factories_0_pincode">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_factories_0_city">City</label>
                                                <input required="required" type="text" maxlength="50"
                                                    class="form-control alphaonly capitalize" name="factories[0][city]"
                                                    id="id_vendor_factories_0_city">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <?php echo $this->Form->control('factories[0][country]', ['name' => 'factories[0][country]','data-state' =>'id_vendor_factories_0_state', 'class' => 'selectpicker form-control my-select country_code_option', 'options' => $countries, 'label' => 'Country', 'required' => 'true', 'data-live-search' => 'true', 'empty' => 'Please select', 'title' => 'Select Country', 'empty' => 'Select Country', 'id' => 'id_vendor_factories_0_country']); ?>
                                                <!-- <label for="id_vendor_factories_0_country">Country</label>
                                                <select class="form-control" name="factories[0][country]"
                                                    id="id_vendor_factories_0_country"></select> -->
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_factories_0_state">State</label>
                                                <select class="form-control" name="factories[0][state]"
                                                    id="id_vendor_factories_0_state"></select>
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 hide">
                                                <span class="badge redbadge delete" id="id_vendor_factories_0_delete"
                                                    data-toggle="tooltip" data-id="0" data-placement="right"
                                                    data-original-title="Delete Address" required="true">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row" id="factory_office_0_row1">
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                                        <label class="text-info">Installed Capacity</label>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <input required="required" type="text" class="form-control"
                                                            required="true" name="factories[0][installed_capacity]"
                                                            placeholder="Installed Capacity" maxlength="45"
                                                            id="id_vendor_factories_0_installed_capacity">
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <div class="custom-file">
                                                            <input
                                                                name="factories[0][installed_capacity_file]" type="file"
                                                                accept=".pdf" required="true" class="custom-file-input">
                                                            <label class="custom-file-label"
                                                                id="id_vendor_factories_0_installed_capacity_file">
                                                                Choose File
                                                            </label>
                                                        </div>
                                                        <a class="id_vendor_facilities_installed_capacity_file"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                                        <label class="text-info">Power Consumption(upload light bill)</label>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <input required="required" type="text" class="form-control"
                                                            name="factories[0][power_available]" maxlength="45"
                                                            placeholder="Power Consumption" required="true"
                                                            id="id_vendor_factories_0_power_available">
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <div class="custom-file">
                                                            <input
                                                                name="factories[0][power_available_file]" type="file"
                                                                accept=".pdf" class="custom-file-input">
                                                            <label class="custom-file-label"
                                                                id="id_vendor_factories_0_power_available_file">
                                                                Choose File
                                                            </label>
                                                        </div>
                                                        <a class="id_vendor_facilities_power_available_file"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                                        <label class="text-info">Machinery Available</label>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <input required="required" type="text" class="form-control"
                                                            name="factories[0][machinery_available]" maxlength="45"
                                                            placeholder="Machinery Available" required="true"
                                                            id="id_vendor_factories_0_machinery_available">
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <div class="custom-file">
                                                            <input 
                                                                name="factories[0][machinery_available_file]"
                                                                type="file" accept=".pdf" class="custom-file-input"
                                                                >
                                                            <label class="custom-file-label"
                                                                id="id_vendor_factories_0_machinery_available_file">
                                                                Choose File
                                                            </label>
                                                        </div>
                                                        <a class="id_vendor_facilities_machinery_available_file"></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center required">
                                                        <label class="text-info">Raw Material Avi. and Source</label>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <input required="required" type="text" class="form-control"
                                                            name="factories[0][raw_material]" maxlength="45"
                                                            placeholder="Raw Material Avi. and Source" required="true"
                                                            id="id_vendor_factories_0_raw_material">
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <div class="custom-file">
                                                            <input 
                                                                name="factories[0][raw_material_file]" type="file"
                                                                accept=".pdf" class="custom-file-input" >
                                                            <label class="custom-file-label"
                                                                id="id_vendor_factories_0_raw_material_file">
                                                                Choose File
                                                            </label>
                                                        </div>
                                                        <a class="id_vendor_facilities_raw_material_file"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card" id="factory_office_0_row3">
                                            <div class="card-header">
                                                <p
                                                    style="text-transform: uppercase; font-weight: 500; font-size: inherit;">
                                                    Actual production during preceding 3 years
                                                    <span class="badge lgreenbadge float-right" data-sup="0"
                                                        id="id_factory_commencement_add">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="card-body" id="id_vendor_commencements_0_body">
                                                <div class="row" id="vc_killme00">
                                                    <div class="col-sm-12 col-md-4 col-lg-4 required mb-3">
                                                        <input required="required" type="hidden" maxlength="45"
                                                            name="factories[0][commencements][0][id]"
                                                            id="id_vendor_factories_0_commencement_0_id">
                                                        <input required="required" type="hidden"
                                                            name="factories[0][commencements][0][vendor_temp_id]"
                                                            id="id_vendor_factories_0_commencement_0_vendor_temp_id"
                                                            data-id="0" data-sup="0"
                                                            class="factory_commencement vendor_temp_id"
                                                            value="<?= h($vendorTemp->id) ?>">
                                                        <label
                                                            for="id_vendor_factories_0_commencement_0_commencement_year">
                                                            Year Of Commencement Of Production</label>
                                                        <input required="required" type="number"
                                                            class="form-control maxlength_validation"
                                                            name="factories[0][commencements][0][commencement_year]"
                                                            id="id_vendor_factories_0_commencement_0_commencement_year"
                                                            required="true" maxlength="4">
                                                    </div>
                                                    <div class="col-sm-12 col-md-2 col-lg-2 required mb-3">
                                                        <label
                                                            for="id_vendor_factories_0_commencement_0_commencement_material">Material</label>
                                                        <input required="required" type="text" class="form-control"
                                                            name="factories[0][commencements][0][commencement_material]"
                                                            id="id_vendor_factories_0_commencement_0_commencement_material"
                                                            placeholder="Material" required="true" maxlength="45">
                                                    </div>
                                                    <div class="col-sm-12 col-md-2 col-lg-2 required mb-3">
                                                        <label id="id_vendor_factories_0_commencement_0_first_year"
                                                            class="first_year"></label>
                                                        <input required="required" type="hidden" class="year1"
                                                            name="factories[0][commencements][0][first_year]"
                                                            id="id_vendor_factories_0_commencement_0_first_year"
                                                            required="true">
                                                        <input required="required" type="number"
                                                            class="form-control placeholder1"
                                                            name="factories[0][commencements][0][first_year_qty]"
                                                            id="id_vendor_factories_0_commencement_0_first_year_qty"
                                                            required="true">
                                                    </div>
                                                    <div class="col-sm-12 col-md-2 col-lg-2 required mb-3">
                                                        <label id="id_vendor_factories_0_commencement_0_second_year_qty"
                                                            class="second_year"></label>
                                                        <input required="required" type="hidden" class="year2"
                                                            name="factories[0][commencements][0][second_year]"
                                                            id="id_vendor_factories_0_commencement_0_second_year"
                                                            required="true">
                                                        <input required="required" type="number"
                                                            class="form-control placeholder2"
                                                            name="factories[0][commencements][0][second_year_qty]"
                                                            id="id_vendor_factories_0_commencement_0_second_year_qty"
                                                            required="true">
                                                    </div>
                                                    <div class="col-sm-12 col-md-2 col-lg-2 required mb-3">
                                                        <label id="factory_office_0_commencement_0_third_year_qty"
                                                            class="third_year"></label>
                                                        <input required="required" type="hidden" class="year3"
                                                            name="factories[0][commencements][0][third_year]"
                                                            id="factory_office_0_commencement_0_third_year"
                                                            required="true">
                                                        <input required="required" type="number"
                                                            class="form-control placeholder3"
                                                            name="factories[0][commencements][0][third_year_qty]"
                                                            id="factory_office_0_commencement_0_third_year_qty"
                                                            required="true">
                                                    </div>
                                                    <div class="col-sm-12 col-md-1 col-lg-1 mt-2 hide">
                                                        <span class="badge redbadge delete" data-toggle="tooltip"
                                                            data-id="0" data-placement="right"
                                                            data-class="factory_office_0_commencement_0"
                                                            data-original-title="Delete Address">
                                                            <i class="fas fa-trash"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="float-right">
                            <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'submit', 'data-id' => 'production_facility')); ?>
                        </span>
                        <?= $this->Form->end() ?>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-four-contactperson" role="tabpanel"
                        aria-labelledby="tab_contactperson" style="background-color: white;">
                        <?= $this->Form->create($vendorTemp, ['type' => 'post', 'enctype'=>'multipart/form-data', 'id' => 'id_form_contact_person']) ?>
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>Contact Person</h5>
                            </div>
                            <div class="card-body">
                                <input required="required" type="hidden" name="temps[id]" id="id_vendor_temps_id"
                                    class="vendor_temp_id" value="<?= h($vendorTemp->id) ?>">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="id_contact_name">Full Name</label>
                                        <input required="required" type="text" class="form-control" maxlength="50"
                                            name="temps[contact_person]" id="id_vendor_temps_contact_person">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="id_contact_name">Email</label>
                                        <input required="required" type="text" class="form-control" maxlength="50"
                                            name="temps[contact_email]" id="id_vendor_temps_contact_email">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="id_contact_name">Mobile</label>
                                        <input required="required" type="text" class="form-control" maxlength="12"
                                            name="temps[contact_mobile]" id="id_vendor_temps_contact_mobile">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="id_contact_name">Department</label>
                                        <input required="required" type="text" class="form-control" maxlength="50"
                                            name="temps[contact_department]" id="id_vendor_temps_contact_department">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="id_contact_name">Designation</label>
                                        <input required="required" type="text" class="form-control" maxlength="50"
                                            name="temps[contact_designation]" id="id_vendor_temps_contact_designation">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <p>Address of Proprietor / Partner / Director
                                    <span class="badge lgreenbadge float-right" id="id_vendor_partner_add">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                </p>
                            </div>
                            <div class="card-body" id="id_vendor_partner_address_body">
                                <div class="card">
                                    <div class="card-body">
                                        <input required="required" type="hidden" name="partner_address[0][id]"
                                            id="id_vendor_partner_address_0_id">
                                        <input required="required" type="hidden" class="vendor_partner vendor_temp_id"
                                            name="partner_address[0][vendor_temp_id]" data-id="0"
                                            id="id_vendor_partner_address_0_vendor_temp_id"
                                            value="<?= h($vendorTemp->id) ?>">
                                        <div class="row">
                                            <div class="col-lg-2 mt-1">
                                                <input required="required" type="radio"
                                                    name="partner_address[0][type]"
                                                    id="id_vendor_partner_address_0_type1" value="Proprietor">
                                                <label>Proprietor</label>
                                            </div>
                                            <div class="col-lg-2 mt-1">
                                                <input required="required" type="radio"
                                                    name="partner_address[0][type]"
                                                    id="id_vendor_partner_address_0_type2" value="Partner">
                                                <label>Partner</label>
                                            </div>
                                            <div class="col-lg-2 mt-1">
                                                <input required="required" type="radio"
                                                    name="partner_address[0][type]"
                                                    id="id_vendor_partner_address_0_type3" checked="" value="Director">
                                                <label>Director</label>
                                            </div>
                                            <div class="col-3 col-md-3 hide">
                                                <span class="badge redbadge delete" data-toggle="tooltip" data-id="0"
                                                    data-class="partner" data-placement="right"
                                                    data-original-title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </div>
                                            <div class="col-sm-12 col-md-12 mb-3 required">
                                                <label for="id_vendor_partner_address_0_name">Name</label>
                                                <input required="required" type="text" class="form-control"
                                                    name="partner_address[0][name]" maxlength="45"
                                                    id="id_vendor_partner_address_0_name">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_partner_address_0_address">Address</label>
                                                <input required="required" type="text" class="form-control"
                                                    name="partner_address[0][address]" maxlength="250"
                                                    id="id_vendor_partner_address_0_address">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_partner_address_0_address_2">Address 1</label>
                                                <input required="required" type="text" class="form-control"
                                                    name="partner_address[0][address_2]" maxlength="250"
                                                    id="id_vendor_partner_address_0_address_2">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_partner_address_0_pincode">Pincode</label>
                                                <input required="required" type="number"
                                                    class="form-control maxlength_validation"
                                                    name="partner_address[0][pincode]" maxlength="6"
                                                    id="id_vendor_partner_address_0_pincode">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_partner_address_0_city">City</label>
                                                <input required="required" type="text" maxlength="250"
                                                    class="form-control alphaonly capitalize"
                                                    name="partner_address[0][city]"
                                                    id="id_vendor_partner_address_0_city">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <?php echo $this->Form->control('partner_address[0][country]', ['id' => 'id_vendor_partner_address_0_country','data-state' =>'id_vendor_partner_address_0_state', 'class' => 'selectpicker form-control my-select country_code_option', 'name'=>'partner_address[0][country]', 'options' => $countries, 'data-live-search' => 'true', 'empty' => 'Select Country', 'title' => 'Please select', 'label' => 'Country']); ?>
                                                <!-- <label for="id_vendor_partner_address_0_country">Country</label>
                                                <select class="form-control" name="partner_address[0][country]"
                                                    id="id_vendor_partner_address_0_country"></select> -->
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3 required">
                                                <label for="id_vendor_partner_address_0_state">State</label>
                                                <select class="form-control" name="partner_address[0][state]"
                                                    id="id_vendor_partner_address_0_state"></select>
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3">
                                                <label for="id_vendor_partner_address_0_telephone">Telephone</label>
                                                <input type="text" class="form-control numberonly maxlength_validation"
                                                    name="partner_address[0][telephone]"
                                                    id="id_vendor_partner_address_0_telephone"  minlength="10" maxlength="14">
                                            </div>
                                            <div class="col-sm-12 col-md-3 mb-3">
                                                <label for="id_vendor_partner_address_0_fax_no">Fax No.</label>
                                                <input  minlength="10" maxlength="14" type="text" class="form-control"
                                                    name="partner_address[0][fax_no]"
                                                    id="id_vendor_partner_address_0_fax_no">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <span class="float-right">
                            <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'submit', 'data-id' => 'contact_person')); ?>
                        </span>
                        <?= $this->Form->end() ?>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-four-home" role="tabpanel"
                        aria-labelledby="tab_paymentdetails" style="background-color: white;">
                        <?= $this->Form->create($vendorTemp, ['type' => 'post', 'enctype'=>'multipart/form-data', 'id' => 'id_form_payment_details']) ?>
                        <?php echo $this->Form->control('id', ['name' => 'temps[id]', 'class'=>'vendor_id', 'type' => "hidden"]); ?>
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>Bank Details</h5>
                            </div>
                            <div class="card-body">
                                <input required="required" type="hidden" name="temps[id]" id="id_vendor_temps_id"
                                    class="vendor_temp_id" value="<?= h($vendorTemp->id) ?>">
                                <div class="row">
                                    <div class="col-lg-3 mb-3 required">
                                        <label for="id_bank_name">Bank name</label>
                                        <input required="required" type="text" name="temps[bank_name]"
                                            class="form-control alphaonly capitalize id_bank_name" maxlength="45"
                                            id="id_vendor_temps_bank_name" required="true">
                                    </div>

                                    <div class="col-lg-3 mb-3 required">
                                        <label for="id_bank_branch">Bank Branch</label>
                                        <input required="required" type="text" maxlength="45"
                                            class="form-control alphaonly capitalize id_bank_branch"
                                            id="id_vendor_temps_bank_branch" name="temps[bank_branch]" required="true">
                                    </div>

                                    <div class="col-lg-3 mb-3 required">
                                        <label for="id_bank_no">Bank number</label>
                                        <input required="required" type="number" maxlength="18"
                                            class="form-control maxlength_validation id_bank_number"
                                            id="id_vendor_temps_bank_number" name="temps[bank_number]" required="true">
                                    </div>

                                    <div class="col-lg-3 mb-3 required">
                                        <label for="id_bank_ifsc">IFSC Code</label>
                                        <input required="required" type="text" maxlength="11" name="temps[bank_ifsc]"
                                            class="form-control maxlength_validation UpperCase id_bank_ifsc"
                                            id="id_vendor_temps_bank_ifsc" required="true">
                                    </div>

                                    <div class="col-lg-3 mb-3">
                                        <label for="id_vendor_temps_bank_country">Country</label>
                                        <select class="form-control" name="temps[bank_country]"
                                            id="id_vendor_temps_bank_country"></select>
                                    </div>

                                    <div class="col-lg-3 mb-3 required">
                                        <label for="id_vendor_temps_bank_city">City</label>
                                        <input required="required" type="text" maxlength="45" class="form-control alphaonly capitalize"
                                            id="id_vendor_temps_bank_city" name="temps[bank_city]" required="true">
                                    </div>

                                    <div class="col-lg-3 mb-3">
                                        <?php echo $this->Form->control('order_currency', ['name' => 'temps[order_currency]', 'class' => 'selectpicker form-control my-select id_order_currency', 'options' => $currencies, 'id'=>'id_vendor_temps_order_currency', 'data-live-search' => 'true', 'required'=>'true', 'title' => 'Select Country', 'empty' => 'Please select']); ?>
                                    </div>

                                    <div class="col-lg-3 mb-3 required">
                                        <label for="id_vendor_temps_bank_swift">SWIFT/BIC</label>
                                        <input required="required" type="text" maxlength="45" class="form-control"
                                            id="id_vendor_temps_bank_swift" name="temps[bank_swift]" required="true">
                                    </div>

                                    <div class="col-3 ">
                                        <label for="id_vendor_temps_tan_no">TAN No</label>
                                        <input  type="text" name="temps[tan_no]"
                                            class="form-control UpperCase" maxlength="45" 
                                            id="id_vendor_temps_tan_no"  maxlength="25">
                                    </div>

                                    <div class="col-3 ">
                                        <label for="id_vendor_temps_cin_no">CIN No</label>
                                        <input  type="text" name="temps[cin_no]"
                                            class="form-control UpperCase" maxlength="45" 
                                            id="id_vendor_temps_cin_no"  maxlength="25">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-4">
                                <div class="card card-primary card-outline">
                                    <div class="card-body p-2 required">
                                        <label for="">GST No</label>
                                        <input required="required" type="text" name="temps[gst_no]" maxlength="25"
                                            class="form-control UpperCase" id='id_vendor_temps_gst_no'>
                                    </div>

                                    <div class="card-footer p-2" style="background-color: whitesmoke;">
                                        <div class="custom-file">
                                            <input name="temps[gst_file]" type="file" accept=".pdf"
                                                class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_temps_gst_file"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card card-primary card-outline">
                                    <div class="card-body p-2 required">
                                        <label for="">PAN No</label>
                                        <input required="required" type="text" name="temps[pan_no]" maxlength="25"
                                            class="form-control UpperCase" id="id_vendor_temps_pan_no">
                                    </div>
                                    <div class="card-footer p-2" style="background-color: whitesmoke;">
                                        <div class="custom-file">
                                            <input name="temps[pan_file]" type="file" accept=".pdf"
                                                class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_temps_pan_file"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card card-primary card-outline">
                                    <div class="card-footer p-2 required" style="background-color: whitesmoke;">
                                        <label for="">Cancelled Cheque</label>
                                        <div class="custom-file">
                                            <input name="temps[bank_file]" type="file"
                                                accept=".pdf,image/jpeg, image/png" class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_temps_bank_file"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="float-right">
                            <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit profile_submit', 'type' => 'submit', 'data-id' => 'payment_details')); ?>
                        </span>
                        <?= $this->Form->end() ?>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-four-certificate" role="tabpanel"
                        aria-labelledby="tab_certificate" style="background-color: white;">
                        <?= $this->Form->create($vendorTemp, ['type' => 'post', 'enctype'=>'multipart/form-data', 'id' => 'id_form_certificate']) ?>
                        <input required="required" type="hidden" name="otherdetails[id]" id="id_vendor_otherdetails_id">
                        <input required="required" type="hidden" name="otherdetails[vendor_temp_id]"
                            value="<?= h($vendorTemp->id) ?>" id="id_vendor_otherdetails_vendor_temp_id"
                            class="vendor_temp_id">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <div class="form-group">
                                            <label for="id_sigma">Six Sigma / Any Other Certificate</label>
                                            <textarea id="id_vendor_otherdetails_six_sigma" name="otherdetails[six_sigma]"
                                                cols="30" rows="1" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <label class="form-label">Upload File</label>
                                        <div class="custom-file">
                                            <input name="otherdetails[six_sigma_file]"
                                                id="id_vendor_otherdetails_six_sigma_file" type="file" accept=".pdf"
                                                class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_otherdetails_six_sigma_file"></a>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <label>Registration No.</label>
                                        <input type="number" id="id_vendor_otherdetails_iso" maxlength="45"
                                            class="form-control other_details_iso" name="otherdetails[iso]">
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <label class="form-label">ISO Registration / Certificate</label>
                                        <div class="custom-file">
                                            <input name="otherdetails[iso_file]"
                                                id="id_vendor_otherdetails_iso_file" type="file" accept=".pdf"
                                                class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_otherdetails_iso_file"></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>HALAL Registration / certificate</h5>
                            </div>
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6 mt-3 "
                                        style="border-right: 1px solid #dee2e6;">
                                        <label class="form-label">Certificate File</label>
                                        <div class="custom-file">
                                            <input name="otherdetails[halal_file]"
                                                id="id_vendor_otherdetails_halal_file" type="file" accept=".pdf"
                                                class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                        <a class="id_vendor_otherdetails_halal_file"></a>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 mt-3">
                                        <label class="form-label">Declaration</label>
                                        <div class="custom-file">
                                            <input name="otherdetails[declaration_file]"
                                                id="id_vendor_otherdetails_declaration_file" type="file" accept=".pdf"
                                                class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>Other Quality Certification</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h5>Whether the item is completely manufactured in applicant's factory?</h5>
                                        <input id="id_vendor_otherdetails_fully_manufactured_yes" type="radio"
                                            name="otherdetails[fully_manufactured]" class="fully_manufactured_radio"
                                            value="yes">
                                        <label>Yes</label>
                                        <input id="id_vendor_otherdetails_fully_manufactured_no" type="radio"
                                            name="otherdetails[fully_manufactured]" class="fully_manufactured_radio"
                                            value="no">
                                        <label>No</label>
                                    </div>

                                    <div class="col-lg-4 mt-1 suppliers_name hide">
                                        <label for="id_vendor_otherdetails_suppliers_name">Suppliers Name</label>
                                        <input required="required" type="text" class="form-control"
                                            name="otherdetails[suppliers_name]" id="id_vendor_otherdetails_suppliers_name">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <span class="float-right">
                            <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit profile_submit', 'type' => 'submit', 'data-id' => 'certificate')); ?>
                        </span>
                        <?= $this->Form->end() ?>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-four-questionnaire" role="tabpanel"
                        aria-labelledby="tab_questionnaire" style="background-color: white;">
                        <?= $this->Form->create($vendorTemp, ['type' => 'post', 'enctype'=>'multipart/form-data', 'id' => 'id_form_questionnaire']) ?>
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>Other information considered relevent to be furnished by supplier</h5>
                            </div>
                            <div class="card-body" id="id_vendor_questionnaires_body">
                                <div class="row">
                                    <div class="col-lg-12 mt-3 required">
                                        <label>Does the company have any policy wrt to child labour appoint in work place</label>
                                        <input required="required" type="hidden" name="questionnaire[0][question]"
                                            value="Does the company have any policy wrt to child labour appoint in work place"
                                            id="id_vendor_questionnaires_0_question">
                                        <textarea id="id_vendor_questionnaires_0_answer" name="questionnaire[0][answer]"
                                            class="form-control" cols="30" rows="3"></textarea>
                                        <input required="required" type="hidden" name="questionnaire[0][id]"
                                            id="id_vendor_questionnaires_0_id">
                                        <input required="required" type="hidden" name="questionnaire[0][vendor_temp_id]"
                                            id="id_vendor_questionnaires_0_vendor_temp_id"
                                            value="<?= h($vendorTemp->id) ?>" class="vendor_temp_id">
                                    </div>
                                    <div class="col-lg-12 mt-3 required">
                                        <label>Does your company follow any Anti - corruption policy (zero corruption )
                                            & has follow ethical code of code / corporate social
                                            responsibilities:-</label>
                                        <input required="required" type="hidden" name="questionnaire[1][question]"
                                            value="Does your company follow any Anti - corruption policy (zero corruption ) & has follow ethical code of code / corporate social responsibilities"
                                            id="id_vendor_questionnaires_1_question">
                                        <textarea placeholder="" name="questionnaire[1][answer]" class="form-control"
                                            cols="30" rows="3" id="id_vendor_questionnaires_1_answer"></textarea>
                                        <input required="required" type="hidden" name="questionnaire[1][id]"
                                            id="id_vendor_questionnaires_1_id">
                                        <input required="required" type="hidden" name="questionnaire[1][vendor_temp_id]"
                                            id="id_vendor_questionnaires_1_vendor_temp_id"
                                            value="<?= h($vendorTemp->id) ?>" class="vendor_temp_id">
                                    </div>
                                    <div class="col-lg-12 mt-3 required">
                                        <label>Does the company have policy & decimate between sexual worker wrt caste,
                                            gender, religion and harassment at work place</label>
                                        <input required="required" type="hidden"
                                            id="id_vendor_questionnaires_2_question" name="questionnaire[2][question]"
                                            value="Does the company have policy & decimate between sexual worker wrt caste, gender, religion and harassment at work place">
                                        <textarea placeholder="" id="id_vendor_questionnaires_2_answer"
                                            name="questionnaire[2][answer]" class="form-control" cols="30"
                                            rows="3"></textarea>
                                        <input required="required" type="hidden" name="questionnaire[2][id]"
                                            id="id_vendor_questionnaires_2_id">
                                        <input required="required" type="hidden" name="questionnaire[2][vendor_temp_id]"
                                            id="id_vendor_questionnaires_2_vendor_temp_id"
                                            value="<?= h($vendorTemp->id) ?>" class="vendor_temp_id">
                                    </div>
                                    <div class="col-lg-12 my-3 required">
                                        <label>Does the company use any product in the manufacturing of material through
                                            recycled material :-</label>
                                        <input required="required" type="hidden"
                                            id="id_vendor_questionnaires_3_question" name="questionnaire[3][question]"
                                            value="Does the company use any product in the manufacturing of material through recycled material">
                                        <textarea placeholder="" id="id_vendor_questionnaires_3_answer"
                                            name="questionnaire[3][answer]" class="form-control" cols="30"
                                            rows="3"></textarea>
                                        <input required="required" type="hidden" name="questionnaire[3][id]"
                                            id="id_vendor_questionnaires_3_id">
                                        <input required="required" type="hidden" name="questionnaire[3][vendor_temp_id]"
                                            id="id_vendor_questionnaires_3_vendor_temp_id"
                                            value="<?= h($vendorTemp->id) ?>" class="vendor_temp_id">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="float-right">
                            <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit profile_submit', 'type' => 'submit', 'data-id' => 'questionnaire')); ?>
                        </span>
                        <?= $this->Form->end() ?>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-four-customerAddress" role="tabpanel"
                        aria-labelledby="tab_customerAddress" style="background-color: white;">
                        <?= $this->Form->create($vendorTemp, ['type' => 'post', 'enctype'=>'multipart/form-data', 'id' => 'id_form_reputed_customer']) ?>
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5>
                                    Address of your reputed customers to whom reference can be made if necessary
                                    <span class="badge lgreenbadge add float-right" id="id_reputed_customer_add"
                                        data-toggle="tooltip" data-placement="right" title="Add Reputed Customer">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                </h5>
                            </div>
                            <div class="card-body" id="id_vendor_reputed_customers_body">
                                <div class="row" id="rc_killme0">
                                    <div class="col-lg-3 mb-3 col-md-3">
                                        <input required="required" type="hidden" name="reputed_customers[0][id]"
                                            id="id_vendor_reputed_customers_0_id">
                                        <input required="required" type="hidden" data-id="0"
                                            name="reputed_customers[0][vendor_temp_id]"
                                            id="id_vendor_reputed_customers_0_vendor_temp_id"
                                            value="<?= h($vendorTemp->id) ?>" class="reputed_customer vendor_temp_id">
                                        <div class="form-group">
                                            <div class="input text required">
                                                <label for="id_name">Customer Name</label>
                                                <input required="required" type="text"
                                                    name="reputed_customers[0][customer_name]" maxlength="45"
                                                    class="form-control alphaonly capitalize" required="required"
                                                    id="id_vendor_reputed_customers_0_customer_name"
                                                    aria-required="true">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 mb-3 col-md-3">
                                        <div class="form-group">
                                            <div class="input text required">
                                                <label for="reputed-customer-0-address">Address</label>
                                                <input required="required" type="text" maxlength="250"
                                                    name="reputed_customers[0][address]" required="required"
                                                    class="form-control" id="id_vendor_reputed_customers_0_address"
                                                    aria-required="true">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 mb-3 col-md-3">
                                        <div class="form-group">
                                            <div class="input number required">
                                                <label for="reputed_pincode">Pincode</label>
                                                <input required="required" type="number"
                                                    name="reputed_customers[0][pincode]" required="required"
                                                    class="form-control maxlength_validation"
                                                    id="id_vendor_reputed_customers_0_pincode" maxlength="6"
                                                    aria-required="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 mb-3 col-md-3">
                                        <div class="form-group">
                                            <div class="input text required">
                                                <label for="">City</label>
                                                <input required="required" type="text" maxlength="250" name="reputed_customers[0][city]"
                                                    class="form-control alphaonly capitalize" required="required"
                                                    id="id_vendor_reputed_customers_0_city" aria-required="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 mb-3 col-md-3">
                                        <div class="form-group">
                                            <div class="input select required">
                                                <?php echo $this->Form->control('reputed_customers[0][country]', ['class' => 'selectpicker form-control my-select country_code_option','data-state' =>'id_vendor_reputed_customers_0_state', 'options' => $countries, 'data-live-search' => 'true', 'title' => 'Select Country', 'label' => 'Country','required'=>'required', 'id'=>'id_vendor_reputed_customers_0_country', 'empty' => 'Please select']); ?>
                                                <!-- <label for="reputed-customer-0-country">Country</label>
                                                <select name="reputed_customers[0][country]"
                                                    class="selectpicker form-control my-select country_code_option"
                                                    data-state="id_vendor_reputed_customers_0_state"
                                                    data-live-search="true" title="Select Country" required="required"
                                                    id="id_vendor_reputed_customers_0_country">
                                                    <option value="">Please select</option>
                                                </select> -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 mb-3 col-md-3">
                                        <div class="form-group">
                                            <div class="input select required">
                                                <label for="reputed_customer_0_state">State</label>
                                                <select name="reputed_customers[0][state]"
                                                    id="id_vendor_reputed_customers_0_state" maxlength="250"
                                                    class="selectpicker form-control my-select" data-live-search="true"
                                                    title="Select State" required="required">
                                                    <option value="">Select State</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 mb-3 col-md-3">
                                        <div class="form-group">
                                            <label for="id_telephone">Telephone</label>
                                            <input type="text"
                                                id="id_vendor_reputed_customers_0_telephone"
                                                name="reputed_customers[0][telephone]" maxlength="15"
                                                class="form-control numberonly maxlength_validation"
                                                minlength="10" maxlength="14">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 mb-3 col-md-3">
                                        <div class="form-group">
                                            <div class="input number">
                                                <label for="reputed_faxno">Fax No.</label>
                                                <input type="number"
                                                    name="reputed_customers[0][fax_no]" maxlength="15"
                                                    id="id_vendor_reputed_customers_0_fax_no"
                                                    class="form-control maxlength_validation"
                                                    minlength="10" maxlength="14">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-1 mt-4 pt-4 hide">
                                        <span class="badge redbadge delete" data-toggle="tooltip" data-id="0"
                                            data-class="customer" data-placement="right" data-original-title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="float-right">
                            <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit profile_submit', 'type' => 'submit', 'data-id' => 'reputed_customer')); ?>
                        </span>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    var stateByCountry = `<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'stateByCountryCode')); ?>`;
    var stateByCountryId = `<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'stateByCountryId')); ?>`;
    var vendorView = `<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'vendor')); ?>`;
    var getCountries = `<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'countries')); ?>`;
    var getCountryCodeById = `<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'getCountryCodeById')); ?>`;
    var getStateRegioncodeById = `<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'getStateRegioncodeById')); ?>`;
</script>
<?= $this->Html->script('v_vendortemps_edit') ?>