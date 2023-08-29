<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VendorTemp $vendorTemp
 */
?>

<style>
    #custom-tabs-one-tab .nav-link {
        font-size: 0.9rem;
    }

    #custom-tabs-one-tab .nav-item {
        width: 100%;
        margin: 4px 0;
        padding: 4px;
    }
</style>


<div class="row">
    <?php if (isset($vendorTempView)) : ?>
    <div class="col-sm-4 col-md-4 col-lg-4">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 ">
                <?= $this->Form->create(null, ['id' => $vendorTempView[0]->id,  'url' => '/buyer/vendor-temps/update/' . $vendorTemp->id]) ?>
                <div class="card">
                    <div class="card-body">
                        <table>
                            <!-- <h5 class="text-info">Vendor Update Details</h5> -->
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
                    <div class="card-footer">
                        <?= $this->Form->button(__('Approve'), ['class' => 'btn btn-success mb-0', 'value' => '1', 'name' => 'status']) ?>
                        <?= $this->Form->button(__('Reject'), ['class' => 'btn btn-danger mb-0', 'value' => '0', 'name' => 'status']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8">
        <?php else: ?>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <?php endif; ?>
            <div class="card card-tabs">
                <?php if ($vendorTemp->status == 1) : ?>
                <div class="card-header">
                    <div class="approve-reject row">
                        <div class="col-sm-12 col-md-1">
                            <button type="button" class="btn btn-block buyer_approve_btn p-2" 
                                data-toggle="modal" data-target="#modal-sm">
                                <i class="far fa-check-circle"></i> &nbsp; Approve
                            </button>
                        </div>
                        <div class="col-sm-12 col-md-1">
                            <a href="#" class="btn btn-block buyer_reject_btn  p-2" 
                                data-toggle="modal" data-target="#remarkModal"><i class="far fa-times-circle"></i>
                                &nbsp; Reject</a>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal-sm" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <h6>Are you sure you want to approve?</h6>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn" style="border:1px solid #6610f2"
                                    data-dismiss="modal">Cancel</button>
                                <?= $this->Html->link(__('Ok'), ['action' => 'approve-vendor', $vendorTemp->id, 'app'], ['class' => 'btn', 'style' => 'border:1px solid #28a745']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif ($vendorTemp->status == 5) : ?>
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-12 col-md-2">
                            <button type="button" data-id="<?= h($vendorTemp->id) ?>" class="btn btn-block p-2 notify"
                                style="font-size: 0.8rem;border:1px solid #28a745">
                                <i class="far fa-check-circle"></i> Send Credentials
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-2" style="border-right: 1px solid #eee;">
                        <div class=" p-0">
                            <ul class="nav" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                                        href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                        aria-selected="true">
                                        Personal Information
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                        href="#custom-tabs-one-profile" role="tab"
                                        aria-controls="custom-tabs-one-profile" aria-selected="false">
                                        Branch Office
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill"
                                        href="#custom-tabs-one-settings" role="tab"
                                        aria-controls="custom-tabs-one-settings" aria-selected="false">
                                        Production Facility
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab_contactperson" data-toggle="pill"
                                        href="#custom-tabs-four-messages" role="tab"
                                        aria-controls="custom-tabs-four-messages" aria-selected="false">
                                        Proprietor / Partner / Director
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab_paymentdetails" data-toggle="pill"
                                        href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                                        aria-selected="false">
                                        Payment Details
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab_certificate" data-toggle="pill"
                                        href="#custom-tabs-four-certificate" role="tab"
                                        aria-controls="custom-tabs-four-certificate" aria-selected="false">
                                        Certificate
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab_questionnaire " data-toggle="pill"
                                        href="#custom-tabs-four-questionnaire" role="tab"
                                        aria-controls="custom-tabs-four-questionnaire" aria-selected="false">
                                        Questionnaire
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab_customerAddress" data-toggle="pill"
                                        href="#custom-tabs-four-customerAddress" role="tab"
                                        aria-controls="custom-tabs-four-customerAddress" aria-selected="false">
                                        Reputed Customer
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-sm-9">
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-home-tab">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4 col-lg-6">
                                            <div class="card card_border">
                                                <div class="card-header">Primary Details</div>
                                                <div class="card-body p-0">
                                                    <table class="table table-hover table-striped table-bordered">
                                                        <input type="hidden" id="vendor_id" value=<?=h($vendorTemp->id)
                                                        ?>>
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
                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-profile-tab">
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
                                <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-settings-tab">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                                            <a class="btn btn-block bg-gradient-cancel" target="_blank"
                                                id="laboratoryFile" href="">Laboratory Facility Document</a>
                                        </div>
                                        <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                                            <a class="btn btn-block bg-gradient-cancel" target="_blank"
                                                id="isiRegistration" href="">ISI Registration Document</a>
                                        </div>
                                        <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                                            <a class="btn btn-block bg-gradient-cancel" target="_blank"
                                                id="testFacility" href="">Test facility Document</a>
                                        </div>
                                        <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                                            <a class="btn btn-block bg-gradient-cancel" target="_blank"
                                                id="facilitiesForSales" href="">Facilities for effective after sales
                                                services</a>
                                        </div>
                                        <div class="col-sm-12 col-md-3 col-lg-3 mb-3">
                                            <a class="btn btn-block bg-gradient-cancel" target="_blank"
                                                id="qualityControl" href="">Quality control procedure adopted</a>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 mb-3">
                                            <div class="card card_border">
                                                <div class="card-header">Annual turn over in last 3 years (In Rupee)
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <b>2022 - 23</b> &nbsp; - &nbsp; <span
                                                                id="turnover1"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            <b>2022 - 23</b> &nbsp; - &nbsp; <span
                                                                id="turnover2"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            <b>2022 - 23</b> &nbsp; - &nbsp; <span
                                                                id="turnover3"></span>
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
                                                            <a id="incomeTexCertificateDocu" target="_blank"
                                                                href="">Cleaning Certificate.pdf</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-12 mb-3" id="factoryCodeView">
                                            <!-- <div class="card card_border">
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

                                <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel"
                                    aria-labelledby="tab_contactperson" style="background-color: white;">
                                    <div class="row" id="contactPartner">
                                        <!-- <div class="col-4">
                                        <div class="card card_border">
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

                                <div class="tab-pane fade" id="custom-tabs-four-home" role="tabpanel"
                                    aria-labelledby="tab_paymentdetails" style="background-color: white;">
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
                                                            <a id="cancelledCheque" target="_blank" href="">Cleaning
                                                                Certificate</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-four-certificate" role="tabpanel"
                                    aria-labelledby="tab_certificate" style="background-color: white;">
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

                                <div class="tab-pane fade" id="custom-tabs-four-questionnaire" role="tabpanel"
                                    aria-labelledby="tab_questionnaire" style="background-color: white;">
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

                                <div class="tab-pane fade" id="custom-tabs-four-customerAddress" role="tabpanel"
                                    aria-labelledby="tab_customerAddress" style="background-color: white;">
                                    <div class="row" id="reputedCustomer">
                                        <!-- <div class="col-4">
                                        <div class="card card_border">
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

    <div class="modal fade" id="remarkModal" tabindex="-1" role="dialog" aria-labelledby="remarkModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?= $this->Form->create(null, ['id' => 'rejectRemarks', 'class' => ['mb-0'], 'url' => ['action' => 'approve-vendor', $vendorTemp->id, 'rej']]) ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Rejection Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                echo $this->Form->control('remarks', ['label' => false, 'type' => 'textarea', 'rows' => '3', 'class' => 'form-control rounded-0', 'div' => 'form-group']); ?>
                </div>
                <div id="error_msg"></div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn" style="border:1px solid #6610f2"
                        data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="border:1px solid red">Reject</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        var userView = '<?php echo \Cake\Routing\Router::url(array('controller' => '/VendorTemps', 'action' => 'user-credentials')); ?>';
        var vendorView = '<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'vendor')); ?>';
    </script>
    <?= $this->Html->script('b_vendortemps_view') ?>
