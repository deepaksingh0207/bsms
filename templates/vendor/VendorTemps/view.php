<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VendorTemp $vendorTemp
 */
?>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<!-- <style>
    tr{
        border-bottom: 1px solid lightgrey;
    }
    td{
        padding: .2rem;
    }
</style> -->
<?= $this->Html->css('table.css') ?>
<?= $this->Html->css('listing.css') ?>
<?= $this->Html->css('v_index.css') ?>
<?= $this->Html->css('v_vendorCustom') ?>
<?= $this->Html->css('v_vendortemp_view') ?>
<div class="row">
    <div class="col-sm-12 col-md-3 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="prof-img text-center"><i class="fas fa-user-circle"></i></div>
                <div class="desc">
                    <ul>
                        <li>
                            <p>Name : <b>
                                    <?= h($vendorTemp->name) ?>
                                </b></p>
                        </li>
                        <li>
                            <p>Mobile No :<b>
                                    <?= h($vendorTemp->mobile) ?>
                                </b></p>
                        </li>
                        <li>
                            <p>Email ID : <b>
                                    <?= h($vendorTemp->email) ?>
                                </b></p>
                        </li>
                        <li>
                            <p>SAP Vendor Code : <b>
                                    <?= !empty($vendorTemp->sap_vendor_code) ? $vendorTemp->sap_vendor_code : '' ?>
                                </b></p>
                        </li>
                        <li>
                            <p>Status : <b>
                                    <?= $vendorTemp->vendor_status->description ?>
                                </b></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php if ($updatecount > 0) : ?>
            <div class="card">
                <div class="card-header">
                    <b class="text-info">Pending Request for Updation</b>
                </div>
                <div class="card-body">
                    <table style="width:100%">
                        <tr>
                            <?php if ($vendorTempView[0]->name != $vendorTemp->name) : ?>
                                <th>Name</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->name) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->address != $vendorTemp->address) : ?>
                                <th>Address 1</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->address) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->address_2 != $vendorTemp->address_2) : ?>
                                <th>Address 2</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->address_2) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->city != $vendorTemp->city) : ?>
                                <th>City</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->city) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->pincode != $vendorTemp->pincode) : ?>
                                <th>Pincode</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->pincode) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->state != $vendorTemp->state) : ?>
                                <th>State</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->state) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->country != $vendorTemp->country) : ?>
                                <th>Country</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->country) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->contact_person != $vendorTemp->contact_person) : ?>
                                <th>contact person Name</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->contact_person) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->contact_email != $vendorTemp->contact_email) : ?>
                                <th>contact Email</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->contact_email) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->contact_mobile != $vendorTemp->contact_mobile) : ?>
                                <th>contact mobile</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->contact_mobile) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->contact_department != $vendorTemp->contact_department) : ?>
                                <th>contact department</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->contact_department) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                        <tr>
                            <?php if ($vendorTempView[0]->contact_designation != $vendorTemp->contact_designation) : ?>
                                <th>contact Designation</th>
                                <td>: &nbsp;
                                    <?= h($vendorTempView[0]->contact_designation) ?>
                                </td>
                            <?php endif ?>
                        </tr>
                    </table>
                </div>
            </div>
        <?php endif ?>
    </div>
    <div class="col-sm-12 col-md-9 col-lg-9">
        <div class="card">
            <div class="card-header">
                <span class="User_head text-info d-flex justify-content-between align-items-center">USER DETAILS
                    <div class="float-right">
                        <?php if ($updatecount == 0) : ?>
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $vendorTemp->id], ['class' => 'btn btn-info mb-0']) ?>
                        <?php endif; ?>
                    </div>
                </span>
            </div>
            <div class="card card-tabs card_boxshadow">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12" style="border-bottom: 1px solid #eee;">
                        <div class=" p-0">
                            <ul class="nav" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">
                                        Personal Information
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">
                                        Branch Office
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">
                                        Production Facility
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab_contactperson" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">
                                        Proprietor / Partner / Director
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab_paymentdetails" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false">
                                        Payment Details
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab_certificate" data-toggle="pill" href="#custom-tabs-four-certificate" role="tab" aria-controls="custom-tabs-four-certificate" aria-selected="false">
                                        Certificate
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab_questionnaire " data-toggle="pill" href="#custom-tabs-four-questionnaire" role="tab" aria-controls="custom-tabs-four-questionnaire" aria-selected="false">
                                        Questionnaire
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab_customerAddress" data-toggle="pill" href="#custom-tabs-four-customerAddress" role="tab" aria-controls="custom-tabs-four-customerAddress" aria-selected="false">
                                        Reputed Customer
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-9">
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4 col-lg-6">
                                            <div class="card card_border">
                                                <div class="card-header">Primary Details</div>
                                                <div class="card-body p-0">
                                                    <table class="table table-hover table-striped table-bordered">
                                                        <input type="hidden" id="vendor_id" value=<?= h($vendorTemp->id) ?>>
                                                        <tr>
                                                            <td>Name</td>
                                                            <th><span id="primaryDetailsName"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Mobile No</td>
                                                            <th><span id="primaryDetailsMobileNo"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Email ID</td>
                                                            <th><span id="primaryDetailsEmailId"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>SAP Vendor Code</td>
                                                            <th><span id="primaryDetailsVendorCode"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Status</td>
                                                            <th><span id="primaryDetailsStatus"></span></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-6">
                                            <div class="card card_border">
                                                <div class="card-header">Contact Person</div>
                                                <div class="card-body p-0">
                                                    <table class="table table-hover table-striped table-bordered">
                                                        <tr>
                                                            <td>Name</td>
                                                            <th><span id="contactPersonName"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Email</td>
                                                            <th><span id="contactPersonEmail"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Mobile</td>
                                                            <th><span id="contactPersonMobile"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Department</td>
                                                            <th><span id="contactPersonDepart"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Designation</td>
                                                            <th><span id="contactPersonDesig"></span></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-sm-12 col-md-4 col-lg-4"></div> -->
                                        <div class="col-sm-12 col-md-4 col-lg-6">
                                            <div class="card card_border">
                                                <div class="card-header">Address</div>
                                                <div class="card-body p-0">
                                                    <table class="table table-hover table-striped table-bordered">
                                                        <tr>
                                                            <td>Address</td>
                                                            <th><span id="primaryDetailsAddress"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Address 1</td>
                                                            <th><span id="primaryDetailsAddress2"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>City</td>
                                                            <th><span id="primaryDetailsCity"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>State</td>
                                                            <th><span id="primaryDetailsState"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Country</td>
                                                            <th><span id="primaryDetailsCountry"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Pincode</td>
                                                            <th><span id="primaryDetailsPincode"></span></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-6">
                                            <div class="card card_border">
                                                <div class="card-header">Permenant Address</div>
                                                <div class="card-body p-0">
                                                    <table class="table table-hover table-striped table-bordered">
                                                        <tr>
                                                            <td>Address</td>
                                                            <th><span id="permanentAddress"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Address 1</td>
                                                            <th><span id="permanentAddress1"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>City</td>
                                                            <th><span id="permanentAddressCity"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>State</td>
                                                            <th><span id="permanentAddressState"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Country</td>
                                                            <th><span id="permanentAddressCountry"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Pincode</td>
                                                            <th><span id="permanentAddressPincode"></span></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-6">
                                            <div class="card card_border">
                                                <div class="card-header">Other Details</div>
                                                <div class="card-body p-0">
                                                    <table class="table table-hover table-striped table-bordered">
                                                        <tr>
                                                            <td>Company Code</td>
                                                            <th><span id="otherDetailsCompanyCode"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Purchase Organisation</td>
                                                            <th><span id="otherDetailsPurchaseOrga"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Account Group</td>
                                                            <th><span id="otherDetailsAccountGroup"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Schema Group</td>
                                                            <th><span id="otherDetailsSchema"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Reconciliation Account</td>
                                                            <th><span id="otherDetailsReconcili"></span></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Payment Term</td>
                                                            <th><span id="otherDetailsPaymentTemrs"></span></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="row" id="branchOffice">

                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="card card_border">
                                                <div class="card-header">
                                                    Small Scale Industry
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-hover table-striped">
                                                        <tr>
                                                            <td>Year:</td>
                                                            <th><span id="smallScaleYear"></span></th>
                                                            <td>Registration No.:</td>
                                                            <th><span id="smallScaleRegist"></span></th>
                                                            <td>Registration File:</td>
                                                            <th><a href="" id="smallScaleFile" target="_blank"></a></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                                            <a class="btn btn-block bg-gradient-cancel" target="_blank" id="laboratoryFile" href="">Laboratory Facility Document</a>
                                        </div>
                                        <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                                            <a class="btn btn-block bg-gradient-cancel" target="_blank" id="isiRegistration" href="">ISI Registration Document</a>
                                        </div>
                                        <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                                            <a class="btn btn-block bg-gradient-cancel" target="_blank" id="testFacility" href="">Test facility Document</a>
                                        </div>
                                        <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                                            <a class="btn btn-block bg-gradient-cancel" target="_blank" id="facilitiesForSales" href="">Facilities for effective after sales services</a>
                                        </div>
                                        <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                                            <a class="btn btn-block bg-gradient-cancel" target="_blank" id="qualityControl" href="">Quality control procedure adopted</a>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 mb-3">
                                            <div class="card card_border">
                                                <div class="card-header">Annual turn over in last 3 years (In Rupee)</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <b>2022 - 23</b> &nbsp; - &nbsp; <span id="turnover1"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            <b>2022 - 23</b> &nbsp; - &nbsp; <span id="turnover2"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            <b>2022 - 23</b> &nbsp; - &nbsp; <span id="turnover3"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 mb-3">
                                            <div class="card card_border">
                                                <div class="card-header">
                                                    Income Tax Cleaning Certificate
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            Certificate No<br>
                                                            <span id="incomeTexCertificate"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            Certificate Date<br>
                                                            <span id="incomeTexCertificateDate"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            Certificate Document<br>
                                                            <a id="incomeTexCertificateDocu" target="_blank" href="">Cleaning Certificate.pdf</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 mb-3" id="factoryCodeView">
                                            <!-- <div class="card">
                                            <div class="card-header">
                                                Factory Code : <span id="factoryCode"></span>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <table class="table table-hover table-striped" id="commOfProduction">
                                                           
                                                        </table>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <table class="table table-hover table-striped">
                                                                    <tr>
                                                                        <td>Address</td>
                                                                        <th><span id="commAddress"></span>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Address 1</td>
                                                                         <th><span id="commAddress2"></span>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>City</td>
                                                                         <th><span id="commCity"></span>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>State</td>
                                                                         <th><span id="commState"></span>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Country</td>
                                                                         <th><span id="commCountry"></span>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Pincode</td>
                                                                         <th><span id="commPincode"></span>
                                                                        </th>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <a class="btn btn-app btn-block">
                                                                            <b>Installed Capcity</b><br>
                                                                            5000
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <a class="btn btn-app btn-block">
                                                                            <b>Power Available</b><br>
                                                                            5000
                                                                          </a>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <a class="btn btn-app btn-block">
                                                                            <b>Raw Material Avi. and Source</b><br>
                                                                            5000
                                                                          </a>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <a class="btn btn-app btn-block">
                                                                            <b>Machinery Available</b><br>
                                                                            5000
                                                                          </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="tab_contactperson" style="background-color: white;">
                                    <div class="row" id="contactPartner">
                                        <!-- <div class="col-4">
                                        <div class="card">
                                            <div class="card-header">Partner : Jones Thayil</div>
                                            <div class="card-body p-0">
                                                <table class="table table-hover table-striped">
                                                    <tr>
                                                        <td>Address</td>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <td>Address 1</td>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <td>City</td>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <td>State</td>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <td>Country</td>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <td>Pincode</td>
                                                        <th></th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div> -->
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="tab_paymentdetails" style="background-color: white;">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="card card_border">
                                                <div class="card-header">
                                                    Bank Details
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <label for="">Bank name:</label>
                                                            <span id="bankName"></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="">Bank Branch:</label>
                                                            <span id="bankBranch"></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="">Bank number:</label>
                                                            <span id="bankNumber"></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="">IFSC Code:</label>
                                                            <span id="bankIfsc"></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="">Bank Key:</label>
                                                            <span id="bankKey"></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="">Bank Country:</label>
                                                            <span id="bankCountry"></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="">City:</label>
                                                            <span id="bankCity"></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="">Order Currency:</label>
                                                            <span id="bankCurrency"></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="">SWIFT/BIC:</label>
                                                            <span id="bankSwift"></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="">TAN No:</label>
                                                            <span id="bankTan"></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <label for="">CIN No.:</label>
                                                            <span id="bankCin"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="card card_border">
                                                <div class="card-header">
                                                    Other Payment Details
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            GST No:
                                                            <span id="gstNo"></span><br>
                                                            <a id="gstNoFile" target="_blank" href="">Gst File</a>
                                                        </div>
                                                        <div class="col-4">
                                                            PAN No:
                                                            <span id="panNo"></span><br>
                                                            <a id="panNoFile" target="_blank" href="">Pan File</a>
                                                        </div>
                                                        <div class="col-4">
                                                            Cancelled Cheque:
                                                            <a id="cancelledCheque" target="_blank" href="">Cleaning Certificate</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-four-certificate" role="tabpanel" aria-labelledby="tab_certificate" style="background-color: white;">
                                    <div class="card card_border">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    Six Sigma :
                                                    <span id="sixSigma"></span>
                                                    <a id="sixSigmaFile" target="_blank" href="">File</a>
                                                </div>
                                                <div class="col-4">
                                                    ISO Registration / Certificate :
                                                    <span id="isoRegi"></span>
                                                    <a id="isoRegiFile" target="_blank" href="">ISO Certificate</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card card_border">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    HALAL Registration / certificate:
                                                    <a id="hakaRegiFile" target="_blank" href="">File</a>
                                                </div>
                                                <div class="col-4">
                                                    Declaration:
                                                    <a id="isoDecleration" target="_blank" href="">File</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card card_border">
                                        <div class="card-body">
                                            <div class="col-4">
                                                Other Quality Certification:
                                                <span id="suppliersName"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-four-questionnaire" role="tabpanel" aria-labelledby="tab_questionnaire" style="background-color: white;">
                                    <div class="card card_border">
                                        <div class="card-header">
                                            <h5>Other information considered relevent to be furnished by supplier</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" id="questionnaireAll">
                                                <!-- <div class="col-12 mb-4">
                                                <h5 class="text-info">Does the company have any policy wrt to child labour appoint in work place</h5>
                                                <p><i>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                                    molestiae quas vel sint commodi repudiandae consequuntur voluptatum laborum
                                                    numquam blanditiis harum quisquam eius sed odit fugiat iusto fuga praesentium
                                                    optio, eaque rerum! Provident similique accusantium nemo autem.</i></p>
                                            </div>
                                            <div class="col-12 mb-4">
                                                <h5 class="text-info">Does your company follow any anit - corruption policy (zero corruption ) & has follow ethical code of code / corporate social responsibilities</h5>
                                                <p><i>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                                    molestiae quas vel sint commodi repudiandae consequuntur voluptatum laborum
                                                    numquam blanditiis harum quisquam eius sed odit fugiat iusto fuga praesentium
                                                    optio, eaque rerum! Provident similique accusantium nemo autem.</i></p>
                                            </div>
                                            <div class="col-12 mb-4">
                                                <h5 class="text-info">Does the company have policy & decimate between sexual worker wrt cast, gender, religion and harassment at work place</h5>
                                                <p><i>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                                    molestiae quas vel sint commodi repudiandae consequuntur voluptatum laborum
                                                    numquam blanditiis harum quisquam eius sed odit fugiat iusto fuga praesentium
                                                    optio, eaque rerum! Provident similique accusantium nemo autem.</i></p>
                                            </div>
                                            <div class="col-12">
                                                <h5 class="text-info">Does the company use any product in the manufacturing of material through recycled material</h5>
                                                <p><i>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia,
                                                    molestiae quas vel sint commodi repudiandae consequuntur voluptatum laborum
                                                    numquam blanditiis harum quisquam eius sed odit fugiat iusto fuga praesentium
                                                    optio, eaque rerum! Provident similique accusantium nemo autem.</i></p>
                                            </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-four-customerAddress" role="tabpanel" aria-labelledby="tab_customerAddress" style="background-color: white;">
                                    <div class="row" id="reputedCustomer">
                                        <!-- <div class="col-4">
                                        <div class="card">
                                            <div class="card-header">
                                                Customer 1
                                            </div>
                                            <div class="card-body p-0">
                                            <table class="table table-hover table-striped table-bordered">
                                                    <tr>
                                                        <td>Customer Name</td>
                                                        <th>Jones Thayil</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Address</td>
                                                        <th>9082207560</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Pincode</td>
                                                        <th>jonest@fts-pl.com</th>
                                                    </tr>
                                                    <tr>
                                                        <td>City</td>
                                                        <th>0000015483</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Country</td>
                                                        <th>Approved</th>
                                                    </tr>
                                                    <tr>
                                                        <td>State</td>
                                                        <th>Approved</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Telephone</td>
                                                        <th>Approved</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Fax No.</td>
                                                        <th>Approved</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var vendorView = '<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'vendor')); ?>';
</script>
<?= $this->Html->script('v_vendortemps_view') ?>