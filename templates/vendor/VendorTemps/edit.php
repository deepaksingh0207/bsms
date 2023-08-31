<!-- <?= $this->Html->css('cstyle.css') ?> -->
<!-- <?= $this->Html->css('table.css') ?> -->
<!-- <?= $this->Html->css('listing.css') ?> -->
<!-- <?= $this->Html->css('v_index.css') ?> -->
<?= $this->Html->css('v_vendorCustom') ?>
<?= $this->Html->css('v_vendortemp_view') ?>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" /> -->


<div class="row">
    <div class="col-sm-12 col-md-3 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="prof-img text-center"><i class="fas fa-user-circle"></i></div>
                <div class="desc">
                    <ul>
                        <li>
                            <p>Name :
                                <b>
                                    <input type="hidden" id="vendor_id" value="<?= h($vendorTemp->id) ?>"
                                        disabled="true">
                                    <?= h($vendorTemp->name) ?>
                                </b>
                            </p>
                        </li>
                        <li>
                            <p>Mobile No :
                                <b>
                                    <?= h($vendorTemp->mobile) ?>
                                </b>
                            </p>
                        </li>
                        <li>
                            <p>Email ID :
                                <b>
                                    <?= h($vendorTemp->email) ?>
                                </b>
                            </p>
                        </li>
                        <li>
                            <p>SAP Vendor Code :
                                <b>
                                    <?= !empty($vendorTemp->sap_vendor_code) ? $vendorTemp->sap_vendor_code : '' ?>
                                </b>
                            </p>
                        </li>
                        <li>
                            <p>Status :
                                <b>
                                    <?= $vendorTemp->vendor_status->description ?>
                                </b>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-9 col-lg-9">
        
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
                                <?php echo $this->Form->control('company_code', ['disabled' => 'disabled', 'value' => $vendorTemp->company_code->name, 'class' => 'form-control']); ?>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
                                <?php echo $this->Form->control('purchasing_organization', ['disabled' => 'disabled', 'value' => $vendorTemp->purchasing_organization->name, 'class' => 'form-control']); ?>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
                                <?php echo $this->Form->control('account_group', ['disabled' => 'disabled', 'value' => $vendorTemp->account_group->name, 'class' => 'form-control']); ?>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
                                <?php echo $this->Form->control('reconciliation_account', ['disabled' => 'disabled', 'value' => $vendorTemp->reconciliation_account->name, 'class' => 'form-control']); ?>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
                                <?php echo $this->Form->control('schema_group', ['disabled' => 'disabled', 'value' => $vendorTemp->schema_group->name, 'class' => 'form-control']); ?>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
                                <?php echo $this->Form->control('payment_term', ['disabled' => 'disabled','class' => 'form-control', 'value' => $vendorTemp->payment_term->description]); ?>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
                                <div class="form-group">
                                    <?php
                                    $businessTypes  = [
                                        'PROPRIETARY' => 'Proprietary',
                                        'PARTNERSHIP' => 'Partnership Concern',
                                        'PUBLIC_LIMITED' => 'Public Limited Company',
                                        'PRIVATE_LIMITED' => 'Private Limited Company'
                                    ];
                                    echo $this->Form->control('status', ['name'=>'vendor[business_type]', 'class' => 'form-control', 'options' => $businessTypes, 'label' => 'Status']);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card card-outline card-outline-tabs">
                    <div class="card-header p-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab_address" data-toggle="pill"
                                    href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile"
                                    aria-selected="true">Address</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab_branchoffice" data-toggle="pill"
                                    href="#custom-tabs-four-branch" role="tab" aria-controls="custom-tabs-four-branch"
                                    aria-selected="false">Branch Office</a>
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
                                    Contact Person</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="tab_paymentdetails" data-toggle="pill"
                                    href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                                    aria-selected="false">
                                    Payment Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab_certificate" data-toggle="pill"
                                    href="#custom-tabs-four-certificate" role="tab"
                                    aria-controls="custom-tabs-four-certificate" aria-selected="false">Certificate</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="tab_questionnaire " data-toggle="pill"
                                    href="#custom-tabs-four-questionnaire" role="tab"
                                    aria-controls="custom-tabs-four-questionnaire"
                                    aria-selected="false">Questionnaire</a>
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
                                <?= $this->Form->create($vendorTemp, ['type' => 'file', 'enctype'=>'multipart/form-data', 'id' => 'id_form_address', 'class' => 'mb-0']) ?>
                                <h5>Permanent Address</h5>
                                <div class="row">
                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('address', ['name' => 'vendor[address]', 'class' => 'form-control id_address permanent_address_address', 'id' => 'id_permanent_address_address', 'label' => "Address"]); ?>
                                        </div>
                                    </div>
                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('address_2', ['name' => 'vendor[address_2]', 'label' => 'Address 1', 'id' => 'id_permanent_address_address_2', 'class' => 'form-control permanent_address_address_2']); ?>
                                        </div>
                                    </div>
                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('pincode', ['name' => 'vendor[pincode]', 'class' => 'form-control id_pincode permanent_address_pincode', 'id' => 'id_permanent_address_pincode']); ?>
                                        </div>
                                    </div>

                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('city', ['type' => 'text', 'name' => 'vendor[city]', 'class' => 'form-control alphaonly capitalize id_city', 'id' => 'id_permanent_address_city']); ?>
                                        </div>
                                    </div>

                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('country', ['name' => 'vendor[country]', 'id' => 'id_permanent_address_country','data-state' =>'id_permanent_address_state', 'class' => 'selectpicker form-control my-select my-country id_country_id', 'options' => $vt_countries, 'data-live-search' => 'true', 'empty' => 'Select Country', 'title' => 'Select Country']); ?>
                                        </div>
                                    </div>

                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('state', ['name' => 'vendor[state]', 'id' => 'id_permanent_address_state', 'class' => 'selectpicker form-control my-select id_state_id', 'options' => $vt_state, 'data-live-search' => 'true', 'empty' => 'Select State', 'title' => 'Select State']); ?>
                                        </div>
                                    </div>
                                </div>
                                <hr style="border: revert;">
                                <h5>Registered Office Address</h5>
                                <div class="icheck-primary">
                                    <input type="checkbox" id="copypermanant">
                                    <label for="copypermanant">Same as Permanent Address</label>
                                </div>
                                <div class="row">
                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('registered_office[address]', ['id' => 'register_office_address', 'class' => 'form-control registered_office_address', 'label' => "Address"]); ?>
                                        </div>
                                    </div>
                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('registered_office[address_2]', ['label' => 'Address 1', 'class' => 'form-control registered_office_address_2', 'id' => 'register_office_address_2']); ?>
                                        </div>
                                    </div>
                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control(
                                                'registered_office[pincode]',
                                                ['type' => 'number', 'name' => 'registered_office[pincode]', 'label' => 'Pincode', 'class' => 'form-control maxlength_validation registered_office_pincode', 'id' => 'register_office_pincode', 'maxlength' => '6']
                                            ); ?>
                                        </div>

                                    </div>
                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('registered_office[city]', ['type' => 'text', 'name' => 'registered_office[city]', 'class' => 'form-control alphaonly capitalize registered_office_city', 'label' => 'City', 'id' => 'register_office_city']); ?>
                                        </div>
                                    </div>
                                    <div class="col-3 mt-3 col-md-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('registered_office[country]', ['data-state' =>'register_office_state', 'class' => 'selectpicker show-menu-arrow form-control my-select1 my-country registered_office_country', 'options' => $countries, 'empty' => 'Select Country', 'label' => 'Country', 'data-live-search' => 'true', 'title' => 'Select Country', 'id' => 'register_office_country']); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 mt-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('registered_office[state]', ['class' => 'selectpicker registered_office_state form-control my-select1', 'options' => $states, 'label' => 'State', 'data-live-search' => 'true', 'empty' => 'Select State', 'title' => 'Select State', 'id' => 'register_office_state']); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 mt-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('registered_office[telephone]', [ 'type' => 'number', 'class' => 'form-control registered_office_telephone maxlength_validation', 'id' => 'register_office_telephone', 'label' => 'Telephone', 'maxlength' => '10']); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 mt-3">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('registered_office[fax_no]', ['name' => 'registered_office[fax_no]', 'id' => 'register_office_faxno', 'type' => 'number', 'class' => 'form-control maxlength_validation registered_office_fax_no', 'label' => 'Fax No.', 'maxlength' => '10']); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'button', 'data-id' => 'address')); ?>
                                <?= $this->Form->end() ?>
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-branch" role="tabpanel"
                            aria-labelledby="tab_branchoffice" style="background-color: white;">
                                <?= $this->Form->create($vendorTemp, ['type' => 'file', 'enctype'=>'multipart/form-data', 'id' => 'id_form_branch_office', 'class' => 'mb-0']) ?>
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h5>Branch Office Address</h5>
                                        <span data-class="branch_office" class="badge lgreenbadge mt-2 add"
                                            id="id_branch_office_add" data-toggle="tooltip" data-placement="right"
                                            title="Add Address">
                                            <i class="fas fa-plus-circle"></i>
                                        </span>
                                    </div>
                                    <div class="card-body branch_office_card_body">
                                        <div class="row branch_office branch_office_0" data-id="0" id="branch_office_0">
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('branch_office_address', ['type' => 'text', 'name' => 'branch[branch_office][0][address]', 'id' => 'branch_office_0_address', 'class' => 'form-control branch_office_0_address', 'label' => "Address"]); ?>
                                                </div>
                                            </div>
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('branch_office_address2', ['type' => 'text', 'name' => 'branch[branch_office][0][address_2]', 'id' => 'branch_office_0_address2', 'label' => 'Address 1',  'class' => 'form-control branch_office_0_address_2']); ?>
                                                </div>
                                            </div>
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('branch_office_pincode', ['type' => 'number', 'name' => 'branch[branch_office][0][pincode]', 'label' => 'Pincode', 'class' => 'form-control pincode-input maxlength_validation branch_office_0_pincode',  'id' => 'branch_office_0_pincode', 'maxlength' => '6']); ?>
                                                </div>
                                            </div>
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('branch_office_city', ['type' => 'text', 'name' => 'branch[branch_office][0][city]', 'class' => 'form-control alphaonly capitalize branch_office_0_city', 'label' => 'City',  'id' => 'branch_office_0_city']); ?>
                                                </div>
                                            </div>
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('branch_office_country', ['name' => 'branch[branch_office][0][country]','data-state' =>'branch_office_0_state', 'class' => 'selectpicker form-control my-select my-country branch_office_0_country_name', 'options' => $countries, 'empty' => 'Select Country', 'label' => 'Country', 'data-live-search' => 'true', 'title' => 'Select Country',  'id' => 'branch_office_0_country']); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-3 mt-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('branch_office_state', ['name' => 'branch[branch_office][0][state]', 'class' => 'selectpicker form-control my-select branch_office_0_state_name', 'options' => $states, 'label' => 'State', 'data-live-search' => 'true', 'empty' => 'Select State', 'title' => 'Select State',  'id' => 'branch_office_0_state']); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-3 mt-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('branch_office_telephone', ['name' => 'branch[branch_office][0][telephone]', 'type' => 'number', 'class' => 'form-control maxlength_validation branch_office_0_telephone', 'id' => 'branch_office_0_telephone', 'label' => 'Tel No', 'maxlength' => '10']); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-3 mt-3">
                                                <label>Year of Registration:</label>
                                                <input name="branch[branch_office][0][registration_year]" type="number"
                                                    class="form-control maxlength_validation branch_office_0_registration_year"
                                                    maxlength="4">
                                            </div>

                                            <div class="col-sm-12 col-md-3 mt-3">
                                                <label>Registration No.</label>
                                                <input name="branch[branch_office][0][registration_no]" type="text"
                                                    class="form-control branch_office_0_registration_no">
                                            </div>

                                            <div class="col-sm-12 col-md-3 mt-3">
                                                <label class="form-label">Registration Certificate</label>
                                                <div class="custom-file">
                                                    <input name="branch[branch_office][0][registration_certificate]"
                                                        type="file" accept=".pdf"
                                                        class="custom-file-input branch_office_0_registration_certificate">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                            <div class="col-3 col-md-3 mt-4 pt-4 hide">
                                                <span class="badge redbadge delete" data-toggle="tooltip"
                                                    data-class="branch_office" data-placement="right" data-id="0"
                                                    data-original-title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <hr class="branch_office_0" style="border: revert;">
                                    </div>
                                </div>

                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h5>Small Scale Industry</h5>
                                    </div>
                                    <div class="card-body address-card">
                                        <div class="row">
                                            <div class="col-sm-12 col-lg-4">
                                                <label>Year:</label>
                                                <input type="number" name="small_scale[year]"
                                                    class="form-control maxlength_validation small_scale_year"
                                                    maxlength="4">
                                            </div>

                                            <div class="col-sm-4 col-lg-4">
                                                <label>Registration No.</label>
                                                <input type="text" name="small_scale[registration_no]"
                                                    class="form-control small_scale_registration_no">
                                            </div>

                                            <div class="col-sm-4 col-lg-4">
                                                <label class="form-label">Upload File</label>
                                                <div class="custom-file">
                                                    <input name="small_scale[certificate_file]" type="file"
                                                        accept=".pdf"
                                                        class="custom-file-input small_scale_certificate_file">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'button', 'data-id' => 'branch_office')); ?>
                                <?= $this->Form->end() ?>
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-productionfaculty" role="tabpanel"
                            aria-labelledby="tab_productionfaculty" style="background-color: white;">
                                <?= $this->Form->create($vendorTemp, ['type' => 'file', 'enctype'=>'multipart/form-data', 'id' => 'id_form_production_facility', 'class' => 'mb-0']) ?>
                                <h5>Facility Details</h5>
                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-lg-4 mb-5">
                                        <label>Laboratory facilities available:</label><br>
                                        <input type="radio" id="facility_lab_facility_yes"
                                            name="production_facility[lab_facility]" value="yes"
                                            class="showme facility_lab_facility_yes" data-trigger="yes"
                                            data-show="lab_facilities">
                                        <label>Yes</label>&nbsp; &nbsp; &nbsp; &nbsp;
                                        <input type="radio" id="facility_lab_facility_no"
                                            name="production_facility[lab_facility]" value="no"
                                            class="showme facility_lab_facility_no" data-trigger="yes"
                                            data-show="lab_facilities">
                                        <label>No</label><br>
                                        <div id="lab_facilities" style="display: none;">
                                            <div class="text-container" id="lab_facilities_text">
                                                <div class="custom-file">
                                                    <input name="production_facility[lab_facility_file]" type="file"
                                                        accept=".pdf" class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4 col-lg-4 mb-5">
                                        <label>Whether there is any ISI registration :</label><br>
                                        <input type="radio" id="facility_isi_registration_yes"
                                            name="production_facility[isi_registration]" value="yes"
                                            class="showme facility_isi_registration_yes" data-trigger="yes"
                                            data-show="isi_registration">
                                        <label>Yes</label>&nbsp; &nbsp; &nbsp; &nbsp;
                                        <input type="radio" id="facility_isi_registration_no"
                                            name="production_facility[isi_registration]" value="no"
                                            class="showme facility_isi_registration_no" data-trigger="yes"
                                            data-show="isi_registration">
                                        <label>No</label>
                                        <div id="isi_registration" style="display: none;">
                                            <div class="text-container" id="isi_registration-text">
                                                <div class="custom-file">
                                                    <input name="production_facility[isi_registration_file]" type="file"
                                                        accept=".pdf" class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4 col-lg-4 mb-5">
                                        <label>Test facilities available</label><br>
                                        <input type="radio" id="facility_test_facility_yes"
                                            name="production_facility[test_facility]" value="yes"
                                            class="showme facility_test_facility_yes" data-trigger="yes"
                                            data-show="test_facilities">
                                        <label>Yes</label>&nbsp; &nbsp; &nbsp; &nbsp;
                                        <input type="radio" id="facility_test_facility_no"
                                            name="production_facility[test_facility]" value="no"
                                            class="showme facility_test_facility_no" data-trigger="yes"
                                            data-show="test_facilities">
                                        <label>No</label>
                                        <div id="test_facilities" style="display: none;">
                                            <div class="text-container" id="test_facilities-info">
                                                <div class="custom-file">
                                                    <input name="production_facility[test_facility_file]" type="file"
                                                        accept=".pdf" class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4 col-lg-4 mb-5">
                                        <label>Facilities for effective after sales services</label><br>
                                        <input type="radio" id="facility_sales_services_yes"
                                            name="production_facility[sales_services]" value="yes"
                                            class="showme facility_sales_services_yes" data-trigger="yes"
                                            data-show="sales_services">
                                        <label>Yes</label>&nbsp; &nbsp; &nbsp; &nbsp;
                                        <input type="radio" id="facility_sales_services_no"
                                            name="production_facility[sales_services]" value="no"
                                            class="showme facility_sales_services_no" data-trigger="yes"
                                            data-show="sales_services">
                                        <label>No</label>
                                        <div id="sales_services" style="display: none;">
                                            <div class="text-container" id="sales_services_text">
                                                <div class="custom-file">
                                                    <input name="production_facility[sales_services_file]" type="file"
                                                        accept=".pdf" class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4 col-lg-4 mb-5">
                                        <label>Quality control procedure adopted.</label><br>
                                        <input type="radio" id="facility_quality_control_yes"
                                            name="production_facility[quality_control]" value="yes"
                                            class="showme facility_quality_control_yes" data-trigger="yes"
                                            data-show="quality_control">
                                        <label>Yes</label>&nbsp; &nbsp; &nbsp; &nbsp;
                                        <input type="radio" id="facility_quality_control_no"
                                            name="production_facility[quality_control]" value="no"
                                            class="showme facility_quality_control_no" data-trigger="yes"
                                            data-show="quality_control">
                                        <label>No</label>
                                        <div id="quality_control" style="display: none;">
                                            <div class="text-container" id="quality-control_text">
                                                <div class="custom-file">
                                                    <input name="production_facility[quality_control_file]" type="file"
                                                        accept=".pdf" class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <label>Annual turn over in last 3 years (In Rupee):</label>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <input type="hidden" name="annual_turnover[first_year]"
                                            class="year1 turnover_first_year">
                                        <input type="number"
                                            class="form-control placeholder1 turnover_first_year_turnover"
                                            name="annual_turnover[first_year_turnover]">
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <input type="hidden" name="annual_turnover[second_year]"
                                            class="year2 annual_turnover_second_year">
                                        <input type="number"
                                            class="form-control placeholder2 turnover_second_year_turnover"
                                            name="annual_turnover[second_year_turnover]">
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <input type="hidden" name="annual_turnover[third_year]"
                                            class="year3 turnover_third_year">
                                        <input type="number"
                                            class="form-control placeholder3 turnover_third_year_turnover"
                                            name="annual_turnover[third_year_turnover]">
                                    </div>
                                </div>
                                
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        Income tax cleaning certificate
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <label>Certificate No</label>
                                                <input type="number" name="income_tax[certificate_no]"
                                                    class="form-control income_tax_certificate_no">
                                            </div>
                                            <div class="col-lg-3">
                                                <label>Date</label>
                                                <input type="date" id="datePickerId" name="income_tax[certificate_date]"
                                                    class="form-control income_tax_certificate_date">
                                            </div>
                                            <div class="col-lg-3">
                                                <label class="form-label">Documents</label>
                                                <div class="custom-file">
                                                    <input name="income_tax[certificate_file]" type="file" accept=".pdf"
                                                        class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-4">
                                                <label class="form-label">Latest Copy of Balance Sheet</label>
                                                <div class="custom-file">
                                                    <input name="income_tax[balance_sheet_file]" type="file"
                                                        accept=".pdf" class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                                <!-- <a href="/bsms/webroot/templates/stock_upload.xlsx"
                                                    download="">sample_file_template</a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        Factory Address
                                        <span class="badge lgreenbadge float-right add" id="id_factory_office_add"
                                            data-toggle="tooltip" data-class="factory_office" data-id="0" data-sub="0"
                                            data-havesub="1" data-subclass="commencement" data-placement="right"
                                            title="Add Address">
                                            <i class="fas fa-plus-circle"></i>
                                        </span>
                                    </div>
                                    <div class="card-body factory_office_card_body">
                                        <div class="row factory_office factory_office_0" data-id="0" data-sub="0"
                                            id="factory_office_0">
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('factory_office_address', ['name' => 'prdflt[factory_office][0][address]', 'id' => 'factory_0_address', 'required' => 'true', 'class' => 'form-control', 'label' => "Address"]); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('factory_office_address_2', ['name' => 'prdflt[factory_office][0][address_2]', 'id' => 'factory_0_address_2', 'required' => 'true', 'label' => 'Address 1', 'class' => 'form-control']); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('factory_office_pincode', ['type' => 'number', 'name' => 'prdflt[factory_office][0][pincode]', 'label' => 'Pincode', 'required' => 'true', 'class' => 'form-control maxlength_validation', 'id' => 'factory_0_pincode', 'maxlength' => '6']); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('factory_office_city', ['type' => 'text', 'name' => 'prdflt[factory_office][0][city]', 'class' => 'form-control alphaonly capitalize', 'required' => 'true', 'label' => 'City', 'id' => 'factory_0_city']); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('register_office_country', ['name' => 'prdflt[factory_office][0][country]','data-state' =>'factory_0_state', 'class' => 'selectpicker form-control factory_0_country my-select my-country', 'options' => $countries, 'label' => 'Country', 'required' => 'true', 'data-live-search' => 'true', 'empty' => 'Please select', 'title' => 'Select Country', 'empty' => 'Select Country', 'id' => 'factory_0_country']); ?>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-3 mt-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('factory_office_state', ['name' => 'prdflt[factory_office][0][state]', 'class' => 'selectpicker form-control my-select factory_0_state', 'required' => 'true', 'options' => $states, 'label' => 'State', 'data-live-search' => 'true', 'empty' => 'Select State', 'title' => 'Select State', 'id' => 'factory_0_state']); ?>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-3 mt-4 pt-4 hide">
                                                <span class="badge redbadge delete" data-toggle="tooltip" data-id="0"
                                                    data-placement="right" data-class="factory_office"
                                                    data-original-title="Delete Address" required="true">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </div>

                                            <div class="col-sm-12 col-md-3 mt-4 pt-4"></div>

                                            <div class="col-12 col-md-6 col-lg-6 mt-4">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                                        <label class="text-info">Installed Capacity</label>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <input type="text" class="form-control"
                                                            name="prdflt[factory_office][0][installed_capacity]"
                                                            placeholder="Installed Capacity" required="true">
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <div class="custom-file">
                                                            <input
                                                                name="prdflt[factory_office][0][installed_capacity_file]"
                                                                type="file" accept=".pdf" class="custom-file-input"
                                                                required="true">
                                                            <label class="custom-file-label">Choose
                                                                File</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6 col-lg-6 mt-4">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                                        <label class="text-info">Power Available</label>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <input type="text" class="form-control"
                                                            name="prdflt[factory_office][0][power_available]"
                                                            placeholder="Power Available" required="true">
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <div class="custom-file">
                                                            <input
                                                                name="prdflt[factory_office][0][power_available_file]"
                                                                type="file" accept=".pdf" class="custom-file-input"
                                                                required="true">
                                                            <label class="custom-file-label">Choose
                                                                File</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6 col-lg-6 mt-4">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                                        <label class="text-info">Machinery Available</label>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <input type="text" class="form-control"
                                                            name="prdflt[factory_office][0][machinery_available]"
                                                            placeholder="Machinery Available" required="true">
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <div class="custom-file">
                                                            <input
                                                                name="prdflt[factory_office][0][machinery_available_file]"
                                                                type="file" accept=".pdf" class="custom-file-input"
                                                                required="true">
                                                            <label class="custom-file-label">Choose
                                                                File</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6 col-lg-6 mt-4">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                                        <label class="text-info">Raw Material Avi. and Source</label>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <input type="text" class="form-control"
                                                            name="prdflt[factory_office][0][raw_material]"
                                                            placeholder="Raw Material Avi. and Source" required="true">
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                                        <div class="custom-file">
                                                            <input name="prdflt[factory_office][0][raw_material_file]"
                                                                type="file" accept=".pdf" class="custom-file-input"
                                                                required="true">
                                                            <label class="custom-file-label">Choose
                                                                File</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-lg-12 mt-4">
                                                <div class="card card-primary card-outline">
                                                    <div class="card-header">
                                                        <div class="row">
                                                            <div class="col-10">
                                                                <h5>Actual production during preceding 3 years</h5>
                                                            </div>
                                                            <div class="col-2">
                                                                <span class="badge lgreenbadge add float-right"
                                                                    data-id="0" data-sub="1" data-sub_id="0"
                                                                    data-toggle="tooltip"
                                                                    data-class="factory_office_0_commencement"
                                                                    data-placement="right" id="id_commencement_add"
                                                                    title="" data-original-title="Add Commencement">
                                                                    <i class="fas fa-plus-circle"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body factory_office_0_commencement_card_body">
                                                        <div class="row mb-3 factory_office_0_commencement" data-id="0"
                                                            data-sub="1" data-sub_id="0"
                                                            id="factory_office_0_commencement_0">
                                                            <div class="col-sm-12 col-md-3 col-lg-4">
                                                                <label for="">Year Of Commencement Of Production</label>
                                                                <input type="number" class="form-control maxlength_validation"
                                                                    name="prdflt[factory_office][0][commencement][0][commencement_year]"
                                                                    id="factory_office_0_commencement_0_commencement_year"
                                                                    required="true" maxlength="6">
                                                            </div>
                                                            <div class="col-sm-12 col-md-2 col-lg-2">
                                                                <label for="">Material</label>
                                                                <input type="text" class="form-control"
                                                                    name="prdflt[factory_office][0][commencement][0][commencement_material]"
                                                                    id="factory_office_0_commencement_0_commencement_material"
                                                                    placeholder="Material" required="true">
                                                            </div>
                                                            <div class="col-sm-12 col-md-2 col-lg-2">
                                                                <label id="productionyear1">2020-2021</label>
                                                                <input type="hidden" class="year1"
                                                                    name="prdflt[factory_office][0][commencement][0][first_year]"
                                                                    id="factory_office_0_commencement_0_first_year"
                                                                    required="true">
                                                                <input type="number" class="form-control placeholder1"
                                                                    name="prdflt[factory_office][0][commencement][0][first_year_qty]"
                                                                    id="factory_office_0_commencement_0_first_year_qty"
                                                                    required="true">
                                                            </div>
                                                            <div class="col-sm-12 col-md-2 col-lg-2">
                                                                <label id="productionyear2">2021-2022</label>
                                                                <input type="hidden" class="year2"
                                                                    name="prdflt[factory_office][0][commencement][0][second_year]"
                                                                    id="" required="true">
                                                                <input type="number" class="form-control placeholder2"
                                                                    name="prdflt[factory_office][0][commencement][0][second_year_qty]"
                                                                    id="factory_office_0_commencement_0_second_year_qty"
                                                                    required="true">
                                                            </div>
                                                            <div class="col-sm-12 col-md-2 col-lg-2">
                                                                <label id="productionyear3">2022-2023</label>
                                                                <input type="hidden" class="year3"
                                                                    name="prdflt[factory_office][0][commencement][0][third_year]"
                                                                    id="factory_office_0_commencement_0_third_year"
                                                                    required="true">
                                                                <input type="number" class="form-control placeholder3"
                                                                    name="prdflt[factory_office][0][commencement][0][third_year_qty]"
                                                                    id="factory_office_0_commencement_0_third_year_qty"
                                                                    required="true">
                                                            </div>
                                                            <div class="col-sm-12 col-md-1 col-lg-1 mt-3 pt-3 hide">
                                                                <span class="badge redbadge delete"
                                                                    data-toggle="tooltip" data-id="0"
                                                                    data-placement="right"
                                                                    data-class="factory_office_0_commencement"
                                                                    data-original-title="Delete Address">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <!-- <hr class="factory_office_0_commencement_0"> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <hr class="factory_office_0" > -->
                                    </div>
                                </div>
                                <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'button', 'data-id' => 'production_facility')); ?>
                                <?= $this->Form->end() ?>
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-contactperson" role="tabpanel"
                            aria-labelledby="tab_contactperson" style="background-color: white;">
                                <?= $this->Form->create($vendorTemp, ['type' => 'file', 'enctype'=>'multipart/form-data', 'id' => 'id_form_contact_person', 'class' => 'mb-0']) ?>
                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-lg-4 mt-1">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('contact_person', ['name' => 'vendor[contact_person]', 'class' => 'form-control capitalize alphaonly', 'label' => 'Name']); ?>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4 col-lg-4 mt-1">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('contact_email', ['type' => 'email', 'name' => 'vendor[contact_email]', 'class' => 'form-control', 'label' => 'Email']); ?>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4 col-lg-4 mt-1">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('contact_mobile', ['type' => 'number', 'name' => 'vendor[contact_mobile]', 'class' => 'form-control maxlength_validation', 'label' => 'Mobile', 'maxlength' => '10']); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4 mt-1">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('contact_department', ['name' => 'vendor[contact_department]', 'class' => 'form-control capitalize alphaafternumberonly', 'label' => 'Department']); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4 mt-1">
                                        <div class="form-group">
                                            <?php echo $this->Form->control('contact_designation', ['name' => 'vendor[contact_designation]', 'class' => 'form-control capitalize alphaafternumberonly', 'label' => 'Designation']); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        Address of Proprietor / Partner / Director
                                        <span data-class="partner" class="badge lgreenbadge mt-2 add"
                                            id="id_partner_add" data-toggle="tooltip" data-placement="right" title=""
                                            data-original-title="Add Proprietor / Partner / Director">
                                            <i class="fas fa-plus-circle"></i>
                                        </span>
                                    </div>
                                    <div class="card-body partner_card_body">
                                        <div class="row partner partner_0" data-id="0" id="partner_0">
                                            <div class="col-2 mt-1">
                                                <input type="radio" name="other_address[partner][0][type]"
                                                    id="partner_0_type1" value="Proprietor">
                                                <label>Proprietor</label>
                                            </div>
                                            <div class="col-2 mt-1">
                                                <input type="radio" name="other_address[partner][0][type]"
                                                    id="partner_0_type2" value="Partner">
                                                <label>Partner</label>
                                            </div>
                                            <div class="col-2 mt-1">
                                                <input type="radio" name="other_address[partner][0][type]"
                                                    id="partner_0_type3" checked value="Director">
                                                <label>Director</label>
                                            </div>

                                            <div class="col-3 col-md-3 hide">
                                                <span class="badge redbadge delete" data-toggle="tooltip" data-id="0"
                                                    data-class="partner" data-placement="right"
                                                    data-original-title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </div>

                                            <div class="col-12 mt-1">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('other_address[partner][0][name]', ['class' => 'form-control form-control-sm alphaonly capitalize', 'label' => "Name"]); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('other_address[partner][0][address]', ['class' => 'form-control', 'id' => 'id_address', 'label' => "Address"]); ?>
                                                </div>
                                            </div>
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('other_address[partner][0][address_2]', ['label' => 'Address 1', 'id' => 'id_address_2', 'class' => 'form-control']); ?>
                                                </div>
                                            </div>
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('other_address[partner][0][pincode]', ['type' => 'number', 'class' => 'form-control maxlength_validation', 'id' => 'id_pincode', 'label' => 'Pincode', 'maxlength' => '6']); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('other_address[partner][0][city]', ['class' => 'form-control alphaonly capitalize', 'id' => 'id_city', 'label' => 'City']); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('other_address[partner][0][country]', ['id' => 'id_country','data-state' =>'partner_0_state', 'class' => 'selectpicker form-control my-select my-country', 'options' => $countries, 'data-live-search' => 'true', 'empty' => 'Select Country', 'title' => 'Please select', 'label' => 'Country', 'empty' => 'Please select']); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('other_address[partner][0][state]', ['id' => 'partner_0_state', 'class' => 'selectpicker form-control my-select', 'empty' => 'Select State', 'options' => $states, 'data-live-search' => 'true', 'title' => 'Select State', 'label' => 'State']); ?>
                                                </div>
                                            </div>
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('other_address[partner][0][telephone]', ['id' => 'id_telephone', 'type' => 'number', 'class' => 'form-control maxlength_validation', 'label' => 'Telephone', 'maxlength' => '10']); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-3 mt-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('other_address[partner][0][fax_no]', ['id' => 'id_faxno', 'type' => 'number', 'class' => 'form-control maxlength_validation', 'label' => 'Fax No.', 'maxlength' => '10']); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <hr class="other_address_0" style="border: revert;"> -->
                                    </div>
                                </div>
                                <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'button', 'data-id' => 'contact_person')); ?>
                                <?= $this->Form->end() ?>
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-home" role="tabpanel"
                            aria-labelledby="tab_paymentdetails" style="background-color: white;">
                                <?= $this->Form->create($vendorTemp, ['type' => 'file', 'enctype'=>'multipart/form-data', 'id' => 'id_form_payment_details', 'class' => 'mb-0']) ?>
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        Bank Details
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3 mb-3">
                                                <label for="id_bank_name">Bank name</label>
                                                <input type="text" name="vendor[bank_name]"
                                                    class="form-control alphaonly capitalize id_bank_name"
                                                    id="id_bank_name">
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="id_bank_branch">Bank Branch</label>
                                                <input type="text"
                                                    class="form-control alphaonly capitalize id_bank_branch"
                                                    id="id_bank_branch" name="vendor[bank_branch]">
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="id_bank_no">Bank number</label>
                                                <input type="number" maxlength="18"
                                                    class="form-control maxlength_validation id_bank_number"
                                                    id="id_bank_no" name="vendor[bank_number]">
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="id_bank_ifsc">IFSC Code</label>
                                                <input type="text" maxlength="11" name="vendor[bank_ifsc]"
                                                    class="form-control maxlength_validation UpperCase id_bank_ifsc"
                                                    id="id_bank_ifsc">
                                            </div>

                                            <div class="col-3 mb-3">
                                                <?php echo $this->Form->control('bank_country', ['name' => 'vendor[bank_country]', 'id' => 'id_bank_country','data-state' =>'id_permanent_address_state', 'class' => 'selectpicker form-control my-select id_bank_country_name', 'options' => $countries, 'data-live-search' => 'true', 'title' => 'Select Country', 'empty' => 'Please select']); ?>
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="id_bank_city">City</label>
                                                <input type="text" class="form-control capitalize id_bank_city"
                                                    id="id_bank_city" name="vendor[bank_city]">
                                            </div>

                                            <div class="col-3 mb-3">
                                                <?php echo $this->Form->control('order_currency', ['name' => 'vendor[order_currency]', 'class' => 'selectpicker form-control my-select id_order_currency', 'options' => $currencies, 'data-live-search' => 'true', 'title' => 'Select Country', 'empty' => 'Please select']); ?>
                                            </div>

                                            <!-- <div class="col-3 mb-3">
                                                <?php echo $this->Form->control('order_currency', ['name' => 'order_currency', 'readonly' => 'readonly', 'class' => 'form-control id_order_currency']); ?>
                                            </div> -->

                                            <div class="col-3 mb-3">
                                                <label for="id_swift_bic">SWIFT/BIC</label>
                                                <input type="text" class="form-control id_bank_swift" id="id_swift_bic"
                                                    name="vendor[bank_swift]">
                                            </div>

                                            <div class="col-3 mb-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('tan_no', ['name' => 'vendor[tan_no]', 'class' => 'form-control UpperCase id_tan_no', 'label' => 'TAN No']); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mb-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('cin_no', ['name' => 'vendor[cin_no]', 'class' => 'form-control UpperCase id_cin_no', 'label' => 'CIN No.']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-4 mt-3">
                                        <div class="card card-primary card-outline">
                                            <div class="card-body p-2">
                                                <label for="">GST No</label>
                                                <input type="text" name="vendor[gst_no]"
                                                    class="form-control UpperCase id_gst_no" id='gst-no'>
                                            </div>
                                            <div class="card-footer p-2" style="background-color: whitesmoke;">
                                                <div class="custom-file">
                                                    <input name="vendor[gst_file]" type="file" accept=".pdf"
                                                        class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 mt-3">
                                        <div class="card card-primary card-outline">
                                            <div class="card-body p-2">
                                                <label for="">PAN No</label>
                                                <input type="text" name="vendor[pan_no]"
                                                    class="form-control UpperCase id_pan_no" id="pan-no">
                                            </div>
                                            <div class="card-footer p-2" style="background-color: whitesmoke;">
                                                <div class="custom-file">
                                                    <input name="vendor[pan_file]" type="file" accept=".pdf"
                                                        class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 mt-3">
                                        <div class="card card-primary card-outline">
                                            <div class="card-footer p-2" style="background-color: whitesmoke;">
                                                <label for="">Cancelled Cheque</label>
                                                <div class="custom-file">
                                                    <input type="hidden" name="vendor[bank_file]">
                                                    <input name="vendor[bank_file]" type="file"
                                                        accept=".pdf,image/jpeg, image/png" class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'button', 'data-id' => 'payment_details')); ?>
                                <?= $this->Form->end() ?>
                            </div>
                            
                            <div class="tab-pane fade" id="custom-tabs-four-certificate" role="tabpanel"
                            aria-labelledby="tab_certificate" style="background-color: white;">
                                <?= $this->Form->create($vendorTemp, ['type' => 'file', 'enctype'=>'multipart/form-data', 'id' => 'id_form_certificate', 'class' => 'mb-0']) ?>
                                <div class="row">
                                    <div class="col-sm-12 col-md-3 col-lg-3 mt-3">
                                        <div class="form-group">
                                            <label for="id_sigma">Six Sigma</label>
                                            <textarea id="id_sigma" name="other[six_sigma]" cols="30" rows="1"
                                                class="form-control other_details_six_sigma"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-lg-3 mt-3">
                                        <label class="form-label">Upload File</label>
                                        <div class="custom-file">
                                            <input name="other[six_sigma_file]" type="file" accept=".pdf"
                                                class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-lg-3 mt-3">
                                        <label>Registration No.</label>
                                        <input type="number" class="form-control other_details_iso" name="other[iso]">
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-lg-3 mt-3">
                                        <label class="form-label">ISO Registration / Certificate</label>
                                        <div class="custom-file">
                                            <input name="other[iso_file]" type="file" accept=".pdf"
                                                class="custom-file-input">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        HALAL Registration / certificate
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6 mt-3"
                                                style="border-right: 1px solid #dee2e6;">
                                                <label class="form-label">Certificate File</label>
                                                <div class="custom-file">
                                                    <input name="other[halal_file]" type="file" accept=".pdf"
                                                        class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 mt-3">
                                                <label class="form-label">Declaration</label>
                                                <div class="custom-file">
                                                    <input name="other[declaration_file]" type="file" accept=".pdf"
                                                        class="custom-file-input">
                                                    <label class="custom-file-label">Choose File</label>
                                                </div>
                                                <i class="mt-2" style="color: black;">
                                                    <a href="/bsms/webroot/templates/stock_upload.xlsx"
                                                        download="">sample_file_template</a>
                                                </i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <h5>Other Quality Certification</h5>
                                    <div class="col-lg-12 mt-3">
                                        <p>Whether the item is completely manufactured in applicant's
                                            factory?</p>
                                        <input class="fully_manufactured_radio" type="radio"
                                            name="other[fully_manufactured]" value="yes">
                                        <label>Yes</label>
                                        <input class="fully_manufactured_radio ml-5" type="radio"
                                            name="other[fully_manufactured]" value="no">
                                        <label>No</label>
                                    </div>

                                    <div class="col-lg-12 mt-1">
                                        <div class="sub-contractors-info" style="display: none;">
                                            <div class="form-group">
                                                <?php echo $this->Form->control('sub-contractor', ['id' => 'other_manufacturer', 'name' => 'other[suppliers_name]', 'class' => 'form-control other_details_suppliers_name', 'label' => 'Suppliers Name']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'button', 'data-id' => 'certificate')); ?>
                                <?= $this->Form->end() ?>
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-questionnaire" role="tabpanel"
                            aria-labelledby="tab_questionnaire" style="background-color: white;">
                                <?= $this->Form->create($vendorTemp, ['type' => 'file', 'enctype'=>'multipart/form-data', 'id' => 'id_form_questionnaire', 'class' => 'mb-0']) ?>
                                <h5>Other information considered relevent to be furnished by supplier</h5>
                                <div class="row questionnaire">
                                    <div class="col-lg-12 mt-3">
                                        <label>Does the company have any policy wrt to child labour appoint in
                                            work
                                            place</label>
                                        <input type="hidden" name="questionnaire[0][question]"
                                            value="Does the company have any policy wrt to child labour appoint in work place">
                                        <textarea placeholder="" name="questionnaire[0][answer]" class="form-control"
                                            cols="30" rows="3"></textarea>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <label>Does your company follow any anit - corruption policy (zero
                                            corruption )
                                            &
                                            has follow ethical code of code / corporate social
                                            responsibilities:-</label>
                                        <input type="hidden" name="questionnaire[1][question]"
                                            value="Does your company follow any anit - corruption policy (zero corruption ) & has follow ethical code of code / corporate social responsibilities">
                                        <textarea placeholder="" name="questionnaire[1][answer]" class="form-control"
                                            cols="30" rows="3"></textarea>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <label>Does the company have policy & decimate between sexual worker wrt
                                            cast,
                                            gender, religion and harassment at work place</label>
                                        <input type="hidden" name="questionnaire[2][question]"
                                            value="Does the company have policy & decimate between sexual worker wrt cast, gender, religion and harassment at work place">
                                        <textarea placeholder="" name="questionnaire[2][answer]" class="form-control"
                                            cols="30" rows="3"></textarea>
                                    </div>
                                    <div class="col-lg-12 my-3">
                                        <label>Does the company use any product in the manufacturing of material
                                            through
                                            recycled material :-</label>
                                        <input type="hidden" name="questionnaire[3][question]"
                                            value="Does the company use any product in the manufacturing of material through recycled material">
                                        <textarea placeholder="" name="questionnaire[3][answer]" class="form-control"
                                            cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                                <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'button', 'data-id' => 'questionnaire')); ?>
                                <?= $this->Form->end() ?>
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-customerAddress" role="tabpanel"
                            aria-labelledby="tab_customerAddress" style="background-color: white;">
                                <?= $this->Form->create($vendorTemp, ['type' => 'file', 'enctype'=>'multipart/form-data', 'id' => 'id_form_reputed_customer', 'class' => 'mb-0']) ?>
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h5 class="modal-title" style="text-transform: inherit;">
                                            Address of your reputed customers to whom reference can be made if necessary
                                            <span data-class="customer" class="badge lgreenbadge mt-2 add"
                                                id="id_customer_add" data-toggle="tooltip" data-placement="right"
                                                title="Add Reputed Customer">
                                                <i class="fas fa-plus-circle"></i>
                                            </span>
                                        </h5>
                                    </div>
                                    <div class="card-body customer_card_body">
                                        <div class="row customer customer_0" data-id="0" id="customer_0">
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('reputed[customer][0][customer_name]', ['class' => 'form-control alphaonly capitalize', 'id' => 'id_name', 'label' => "Customer Name"]); ?>
                                                </div>
                                            </div>
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('reputed[customer][0][address]', ['label' => 'Address', 'class' => 'form-control']); ?>
                                                </div>
                                            </div>
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('reputed[customer][0][pincode]', ['type' => 'number', 'class' => 'form-control maxlength_validation', 'id' => 'reputed_pincode', 'label' => 'Pincode', 'maxlength' => '6']); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('reputed[customer][0][city]', ['class' => 'form-control alphaonly capitalize', 'id' => '', 'label' => 'City']); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('reputed[customer][0][country]', ['class' => 'selectpicker form-control my-select my-country','data-state' =>'reputed_customer_0_state', 'options' => $countries, 'data-live-search' => 'true', 'title' => 'Select Country', 'label' => 'Country', 'empty' => 'Please select']); ?>
                                                </div>
                                            </div>

                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('reputed[customer][0][state]', ['name' => 'reputed[customer][0][state]', 'id' => 'reputed_customer_0_state', 'class' => 'selectpicker form-control my-select', 'options' => $states, 'data-live-search' => 'true', 'title' => 'Select State', 'empty' => 'Select State', 'label' => 'State']); ?>
                                                </div>
                                            </div>
                                            <div class="col-3 mt-3 col-md-3">
                                                <div class="form-group">
                                                    <label for="id_telephone">Telephone</label>
                                                    <input type="number" id="reputed_telephone"
                                                        name="reputed[customer][0][telephone]"
                                                        class="form-control maxlength_validation" maxlength="10">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-2 mt-3">
                                                <div class="form-group">
                                                    <?php echo $this->Form->control('register_office_faxno', ['name' => 'reputed[customer][0][fax_no]', 'id' => 'reputed_faxno', 'type' => 'number', 'class' => 'form-control maxlength_validation', 'label' => 'Fax No.', 'maxlength' => '10']); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-1 mt-4 pt-4 hide">
                                                <span class="badge redbadge delete" data-toggle="tooltip" data-id="0"
                                                    data-class="customer" data-placement="right"
                                                    data-original-title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- <hr class="customer_0" style="border: revert;"> -->
                                    </div>
                                </div>
                                <?php echo $this->Form->button('Save', array('class' => 'btn bg-gradient-submit mt-4 profile_submit', 'type' => 'button', 'data-id' => 'reputed_customer')); ?>
                                <?= $this->Form->end() ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal fade" id="modal-sm" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <h6>Are you sure you want to proceed? This action cannot be edit.</h6>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn cancelButton" style="border:1px solid #6610f2"
                                    data-dismiss="modal">Cancel</button>
                                <?php echo $this->Form->button('Ok', array('class' => 'btn mt-3', 'style' => "border:1px solid #28a745", 'type'=>'submit', 'id' => 'id_ogsubmit')); ?>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        
    </div>
</div>
<script>
    var stateByCountry = '<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'stateByCountryCode')); ?>';
    var vendorView = '<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'vendor')); ?>';
    var getCountries = '<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'countries')); ?>';
    var getCountryCodeById = '<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'getCountryCodeById')); ?>';
    var getStateRegioncodeById = '<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'getStateRegioncodeById')); ?>';
    $(document).on("click", "#id_fksubmit", function () {
        $("#tab_productionfaculty").trigger('click');
        $('#id_ogsubmit').trigger('click');
    });
</script>
<?= $this->Html->script('v_vendortemps_edit') ?>