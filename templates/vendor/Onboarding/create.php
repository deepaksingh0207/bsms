<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\vendorTemp $vendorTemp
 * @var string[]|\Cake\Collection\CollectionInterface $purchasingOrganizations
 * @var string[]|\Cake\Collection\CollectionInterface $accountGroups
 * @var string[]|\Cake\Collection\CollectionInterface $schemaGroups
 */

?>
<?= $this->Html->css('CakeLte./AdminLTE/dist/css/adminlte.min.css') ?>
<?= $this->Html->css('CakeLte./AdminLTE/plugins/summernote/summernote.min.css') ?>

<?= $this->Html->css('CakeLte./AdminLTE/plugins/fontawesome-free/css/all.min.css') ?>
<?= $this->Html->script('https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js') ?>
<?= $this->Html->css('custom.css') ?>
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css"> -->
<!-- <?= $this->Html->css('CakeLte.style') ?> -->
<style>
    .label {
        font-size: 10px
    }

    .row {
        margin-left: 0px;
        margin-right: 0px
    }

    img.vekpro-logo {
        width: 100px;
    }

    .info-msg {
        padding-left: 5px;
        font-size: 12px;
    }

    img.ft-icon {
        width: 40px;
        margin-right: -5px;
    }

    .vendorTemps.form.content {
        width: 70%;
        margin: 0 auto;
        background-color: #fff;
        margin-top: 20px;
    }

    .form-control {
        font-size: 14px;
    }

    .h #needButton { color: #F7941D !important; background-color: #fff !important;}

    label {
        font-size: 11px;
        color: #999;
    }
    .note-btn[data-bs-original-title="Video"],
.note-btn[data-bs-original-title="Help"],
.note-btn[data-bs-original-title="Code View"],
.note-btn[data-bs-original-title="Full Screen"]
{ display: none !important;}
    
</style>
<div class="row">
    <div class="column-responsive column-80">
        <?= $this->Form->create($vendorTemp, ['type' => 'file', 'id' => 'onbordingSubmit', 'class' => 'mb-0']) ?>
        <div class="vendorTemps form content">
            <div class="d-flex justify-content-between">
                <div class="h">
                    <h4 class="text-info">
                        <legend>
                            <?= __('Onboarding') ?>
                            <button type="button" id="needButton" data-modalbody="id_oldmsg" class="btn btn-outline-info btn-light chatload" data-sender_group_id="3" data-sender_id="<?= $vendorTemp->id ?>" data-sender_name="<?= $vendorTemp->name ?>" data-table_name="vendor_temps" data-table_pk="<?= $vendorTemp->id ?>" data-toggle="modal" data-target="#modal-lg" style="margin-left: 3em;">
                                <i class="fas fa-comments"></i> Need help
                                <span class="badge badge-info" id="unread<?= $vendorTemp->id ?>" style="transform: translate(19px, -15px);">0</span>
                            </button>
                        </legend>
                    </h4>
                </div>
                <div class="">
                    <!-- <img src="<?= $this->Url->build('/') ?>img/ft-icon.png" class="ft-icon"> -->
                    <img src="<?= $this->Url->build('/') ?>img/apar_logo.png" class="vekpro-logo" widht="50">
                </div>
            </div>
            <div class="card my-2">
                <div class="card-body">
                    <div class="row">

                        <div class="col-3 mb-2">
                            <?php echo $this->Form->control('title', ['disabled' => 'disabled', 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-3 mb-2">
                            <?php echo $this->Form->control('name', ['disabled' => 'disabled', 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-3 mb-2">
                            <?php echo $this->Form->control('email', ['disabled' => 'disabled', 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-3 mb-2">
                            <?php echo $this->Form->control('mobile', ['disabled' => 'disabled', 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-3 mb-2" style="display:none;">
                            <?php echo $this->Form->control('company_code', ['disabled' => 'disabled', 'value' => $vendorTemp->company_code->name, 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-3 mb-2" style="display:none;">
                            <?php echo $this->Form->control('purchasing_organization', ['disabled' => 'disabled', 'value' => $vendorTemp->purchasing_organization->name, 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-3 mb-2" style="display:none;">
                            <?php echo $this->Form->control('account_group', ['disabled' => 'disabled', 'value' => $vendorTemp->account_group->name, 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3 mb-3" style="display:none;">
                            <?php echo $this->Form->control('reconciliation_account', ['disabled' => 'disabled', 'value' => $vendorTemp->reconciliation_account->name, 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-3 mb-2" style="display:none;">
                            <?php echo $this->Form->control('schema_group', ['disabled' => 'disabled', 'value' => $vendorTemp->schema_group->name, 'class' => 'form-control']); ?>
                        </div>
                        <div class="col-3 mb-2" style="display:none;">
                            <?php echo $this->Form->control('payment_term', ['disabled' => 'disabled', 'class' => 'form-control', 'value' => $vendorTemp->payment_term->description]); ?>
                        </div>
                        <div class="col-3 mb-2" style="display:none;">
                        <?php
                            $businessTypes  = [
                                'PROPRIETARY' => 'Proprietary',
                                'PARTNERSHIP' => 'Partnership Concern',
                                'PUBLIC_LIMITED' => 'Public Limited Company',
                                'PRIVATE_LIMITED' => 'Private Limited Company'
                            ];
                            echo $this->Form->control('business_type', ['class' => 'form-control', 'options' => $businessTypes, 'label' => 'Status']);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab_address" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="true">Address</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab_contactperson" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Contact Person</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab_paymentdetails" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false">Payment
                                Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab_document" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Document</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="tab_address" style="background-color: white;">

                            <div class="row">

                                <div class="col-3 mt-3 col-md-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('address', ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="col-3 mt-3 col-md-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('address_2', ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="col-3 mt-3 col-md-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('pincode', ['class' => 'form-control maxlength_validation', 'maxlength' => "6", 'type'=>'number']); ?>
                                    </div>
                                </div>

                                <div class="col-3 mt-3 col-md-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('city', ['class' => 'form-control']); ?>
                                    </div>
                                </div>

                                <div class="col-3 mt-3 col-md-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('country_id', ['class' => ' form-control my-select my-country', 'data-state' => 'state-id','options' => $countries, 'data-live-search' => 'true', 'empty' => 'Select Country']); ?>
                                    </div>
                                </div>

                                <div class="col-3 mt-3 col-md-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('state_id', ['class' => ' form-control my-select', 'options' => $states, 'data-live-search' => 'true', 'empty' => 'Select State']); ?>
                                    </div>
                                </div>

                                <div class="col-3 mt-3 col-md-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('telephone', ['class' => 'form-control maxlength_validation', 'type'=>'number', 'minlength'=>'14', 'maxlength'=>'14']); ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="tab_contactperson" style="background-color: white;">

                            <div class="row">
                                <div class="col-3 mt-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('contact_person', ['class' => 'form-control', 'label' => 'Name']); ?>
                                    </div>
                                </div>

                                <div class="col-3 mt-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('contact_email', ['type'=>'email', 'class' => 'form-control', 'label' => 'Email']); ?>
                                    </div>
                                </div>

                                <div class="col-3 mt-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('contact_mobile', ['class' => 'form-control maxlength_validation', 'label' => 'Mobile', 'type'=>'number' ,'maxlength' => 10, 'minlength' => 10]); ?>
                                    </div>
                                </div>
                                <div class="col-3 mt-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('contact_department', ['class' => 'form-control alphaonly', 'label' => 'Department']); ?>
                                    </div>
                                </div>
                                <div class="col-3 mt-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('contact_designation', ['class' => 'form-control alphaonly', 'label' => 'Designation']); ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="tab_paymentdetails" style="background-color: white;">
                            <div class="row">
                                


                                <div class="col-3 mb-3">
                                    <?php echo $this->Form->control('order_currency', ['class' => ' form-control my-select ', 'options' => $currencies, 'title' => 'Select Country']); ?>
                                </div>

                                <div class="col-3 mb-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('tan_no', ['name' => 'tan_no', 'class' => 'form-control', 'label' => 'TAN No']); ?>
                                    </div>
                                </div>

                                <div class="col-3 mb-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('cin_no', ['name' => 'cin_no', 'class' => 'form-control', 'label' => 'CIN No.']); ?>
                                    </div>
                                </div>
                                <div class="col-3 mb-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('gst_no', ['class' => 'form-control']); ?>
                                    </div>
                                </div>

                                <div class="col-3 mb-3">
                                    <div class="form-group">
                                        <?php echo $this->Form->control('pan_no', ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="tab_document" style="background-color: white;">

                            <div class="row">
                                <div class="col-4 mt-3">
                                    <!-- <label for="formFileMultiple" class="form-label">Upload GST no</label> -->
                                    <?php echo $this->Form->control('gst_file', ['accept'=>'.pdf', 'required'=>'true', 'type' => 'file', 'class' => 'form-control', 'id'=>'formFileMultiple1']); ?>
                                    <?= $this->Html->link(__('<i class="fas fa-file-download"></i>'), '/' . $vendorTemp->gst_file, ['escape' => false, 'target'=>'_blank']) ?>
                                    <small class="text-warning info-msg">Upload only PDF file</small>
                                </div>
                                <div class="col-4 mt-3">
                                    <?php echo $this->Form->control('pan_file', ['accept'=>'.pdf', 'required'=>'true', 'type' => 'file', 'class' => 'form-control', 'id'=>'formFileMultiple2']); ?>
                                    <?= $this->Html->link(__('<i class="fas fa-file-download"></i>'), '/' . $vendorTemp->pan_file, ['escape' => false, 'target'=>'_blank']) ?>
                                </div>
                                <div class="col-4 mt-3">
                                    <?php echo $this->Form->control('bank_file', ['accept'=>'.pdf', 'required'=>'true', 'type' => 'file', 'class' => 'form-control', 'id'=>'formFileMultiple3']); ?>
                                    <?= $this->Html->link(__('<i class="fas fa-file-download"></i>'), '/' . $vendorTemp->bank_file, ['escape' => false, 'target'=>'_blank']) ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 col-md-12 text-center mt-1 pt-1">
                <!-- <?php echo $this->Form->button('Submit', array('class' => 'btn mt-3', 'style' => 'display:none;', 'id' => 'id_ogsubmit')); ?> -->

                <div class="modal fade" id="modal-sm" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <h6>Are you sure you want to proceed ?</h6>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn cancelButton" style="border:1px solid #F7941D; color: #F7941D!important;" data-dismiss="modal">Cancel</button>
                                <?php echo $this->Form->button('Ok', array('class' => 'btn ', 'style' => "color:#121212!important; background-color: #F7941D!important;", 'id' => 'id_ogsubmit')); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <?php echo $this->Form->button('Submit', array('class' => 'btn mt-3', 'type' => 'button', 'id' => 'id_fksubmit', 'style' => 'background-color: #F7941D; color: #121212!important; font-size: 14px; line-height: 1.1rem; padding: 10px 20px;')); ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-xl card card-primary card-outline direct-chat direct-chat-primary">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="card-title">Onboarding Process Ticket</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 65vh;min-height: 65vh; overflow-y: scroll;">
                <div class="direct-chat-messages" id="id_oldmsg" style="height:auto;">
                </div>
            </div>
            <div class="modal-footer">

                <?= $this->Form->create($vendorTemp, ['id' => 'communiSubmit', 'style' => 'width:100%']) ?>
                <div class="row">
                    <div class="col-sm-12 col-md-11 col-lg-11">
                        <div class="input-group">
                            <input type="hidden" id="id_table_pk" name="table_pk" value="">
                            <input type="hidden" id="id_sender_id" name="sender_id">
                            <input type="hidden" id="id_group_id" name="group_id">
                            <textarea id="summernote" name="message" placeholder="Message ..."></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-1 col-lg-1">
                        <span class="input-group-append mt-3">
                            <button type="button" id="add_comm" class="btn btn-primary">Send</button>
                        </span>
                    </div>
                </div>
                <?= $this->Form->end() ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<?= $this->Html->script('CakeLte./AdminLTE/plugins/jquery/jquery.min.js') ?>
<?= $this->Html->script("CakeLte./AdminLTE/plugins/jquery-validation/jquery.validate.min.js") ?>
<?= $this->Html->script('CakeLte./AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>
<?= $this->Html->script('CakeLte./AdminLTE/dist/js/adminlte.min.js') ?>
<?= $this->Html->script('CakeLte./AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js') ?>
<?= $this->Html->script('CakeLte./AdminLTE/plugins/summernote/summernote.min.js') ?>
<?= $this->Html->script('chat') ?>
<script>
    var getchaturl =
        "<?php echo \Cake\Routing\Router::url(array('prefix' => false, 'controller' => 'msgchat-headers', 'action' => 'index')); ?>";
    var postchaturl =
        "<?php echo \Cake\Routing\Router::url(array('prefix' => false, 'controller' => 'msgchat-headers', 'action' => 'add')); ?>";
    var seengeturl =
        "<?php echo \Cake\Routing\Router::url(array('prefix' => false, 'controller' => 'msgchat-headers', 'action' => 'seen-update')); ?>";
    var stateByCountry = `<?php echo \Cake\Routing\Router::url(array('prefix'=>false,'controller' => 'api/api', 'action' => 'stateByCountryId')); ?>`;
    var chatdata, user_id = "<?= h($vendorTemp->id) ?>",
        sender_id, table_pk;

    $(function () { $('[data-toggle="tooltip"]').tooltip(); $('#summernote').summernote({ width: 1000, }); });

    function getRemote(remote_url, method = "GET", type = "json", convertapi = true) {
        var resp = $.ajax({
            type: method,
            dataType: type,
            url: remote_url,
            async: false
        }).responseText;
        if (convertapi) {
            return JSON.parse(resp);
        }
        return resp;
    }

    $(document).on("change", '.my-country', function () {
        var id = $(this).val();
        var r = getRemote(stateByCountry + "/" + id);
        var state_options = "<option selected=''>Please Select</option>";
        $.each(r["message"], function (i, v) { state_options += `<option value="` + v.id + `">` + v.name + `</option>`; });
        $("#" + $(this).data("state")).empty().append(state_options);
    });


    $(document).on("click", "#add_comm", function () {
        var formdata = new FormData($("#communiSubmit")[0]);
        formdata.append("table_name", "vendor_temps");
        resp = sendchat(postchaturl, formdata, $(this).data('modal_body'), $(this).data('sender_id'), getchaturl);
    });


    $('.cancelButton').click(function () {
        $('#modal-sm').modal('hide');
    });

    $('#needButton').click(function () {
        table_pk = $(this).data('table_pk');
        sender_id = $(this).data('sender_id');
        $("#id_sender_id").val($(this).data('sender_id'));
        $("#id_group_id").val($(this).data('sender_group_id'));
        $("#id_table_pk").val($(this).data('table_pk'));
        $("#add_comm").attr('data-modal_body', $(this).data('modalbody')).attr('data-sender_id', $(this).data(
            'sender_id'));
        chat($(this).data('modalbody'), $(this).data('sender_id'), getchaturl, $(this).data('table_name'), $(this)
            .data('table_pk'));

        $.ajax({
            type: "GET",
            url: seengeturl + "/vendor_temps/" + table_pk + "/" + sender_id,
            dataType: 'json',
            beforeSend: function () { $("#gif_loader").show(); },
            success: function (resp) {
                if (resp.status == 1) {
                    $('#unread' + sender_id).hide();
                }
            },
            complete: function () { $("#gif_loader").hide(); }
        });
    });

    function validateMaxLength(inputElement) {
        var inputValue = inputElement.val();
        var maxLength = parseInt(inputElement.attr("maxlength"));
        if (inputValue.length > maxLength) { inputValue = inputValue.slice(0, maxLength); inputElement.val(inputValue); }
    }

    $(document).on("input", ".maxlength_validation", function () { validateMaxLength($(this)); });

    $(document).ready(function () {

        $(".chatload").each(function () {
            $('#unread' + $(this).data('sender_id')).empty();
            getbadge($(this).data('sender_id'), getchaturl, "vendor_temps", $(this).data('table_pk'),
                'unread' + $(this).data('sender_id'));
        });


        $("#tan-no,#cin-no,#gst-no,#pan-no").on("keyup", function () {
            var capitalizedText = $(this).val().toUpperCase();
            $(this).val(capitalizedText);
        });


        $("#contact-person,#contact-department,#contact-designation").on("keyup", function () {
            var capitalizedText = capitalizeFirstLetter($(this).val());
            $(this).val(capitalizedText);
        });


        function capitalizeFirstLetter(text) {
            if (typeof text !== 'string' || text.length === 0) {
                return text;
            }

            return text.charAt(0).toUpperCase() + text.slice(1);
        }

        function validateMaxLength(inputElement) {
            var inputValue = inputElement.val();
            var maxLength = parseInt(inputElement.attr('maxlength'));
            if (inputValue.length > maxLength) {
                inputValue = inputValue.slice(0, maxLength);
                inputElement.val(inputValue);
            }
        }

        $("#onbordingSubmit").validate({
            rules: {
                address: {
                    required: true
                },
                city: {
                    required: true
                },
                state_id: {
                    required: true
                },
                country_id: {
                    required: true
                },
                pincode: {
                    required: true,
                    digits: true
                },

                order_currency: {
                    required: true
                },
                tan_no: {
                     required: false,
                     minlength: 10,
                     maxlength: 10
                 },
                 cin_no: {
                     required: false,
                     minlength: 21,
                     maxlength: 21
                 },
                gst_no: {
                    required: true,
                    minlength: 16,
                    maxlength: 16
                },
                pan_no: {
                    required: true,
                    minlength: 10,
                    maxlength: 10
                },
                contact_person: {
                    required: true
                },
                contact_email: {
                    required: true,
                    email: true
                },
                contact_mobile: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10
                },
                contact_department: {
                    required: true
                },
                contact_designation: {
                    required: true
                },
                gst_file: {
                    required: true
                },
                pan_file: {
                    required: true
                },
                bank_file: {
                    required: true
                }

            },
            messages: {
                address: {
                    required: "Please enter a Address"
                },
                city: {
                    required: "Please enter a city"
                },
                state_id: {
                    required: "Please enter a state"
                },
                country_id: {
                    required: "Please select a country"
                },
                pincode: {
                    required: "Please enter a pincode",
                    digits: true
                },
                gst_no: {
                    required: "Please enter a gst no",
                },
                pan_no: {
                    required: "Please enter a pan no"
                },
                contact_person: {
                    required: "Please enter a contact person"
                },
                contact_email: {
                    required: "Please enter a contact email",
                    email: "Please enter a valid email address"
                },
                contact_mobile: {
                    required: "Please enter a contact mobile",
                    number: "Please enter a valid mobile number"
                },
                contact_department: {
                    required: "Please enter a contact department"
                },
                contact_designation: {
                    required: "Please enter a contact designation"
                },
                gst_file: {
                    required: "Please attached File"
                },
                pan_file: {
                    required: "Please attached File"
                },
                bank_file: {
                    required: "Please attached File"
                }
            },
            errorElement: "span",
            errorPlacement: function (error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            }
        });

        $(document).on("click", "#id_fksubmit", function () {
            var submitcall = true;
            var tab = {
                "tab_address": ["address", "address-2", "pincode", "city", "country-id", "state-id"],
                "tab_contactperson": ["contact-person", "contact-person", "contact-mobile", "contact-department",
                    "contact-designation"
                ],
                "tab_paymentdetails": ["gst-no", "pan-no"],
                "tab_document": ["formFileMultiple1", "formFileMultiple2", "formFileMultiple3"]
            }

            for (const [index, row] of Object.entries(tab)) {
                for (const [indexs, rows] of Object.entries(row)) {
                    var data = $("#" + rows).val();
                    console.log("#" + index);
                    console.log("#" + rows);
                    if (data == "" || data == null || data == undefined) {
                        $("#" + index).trigger('click');
                        submitcall = false;
                        break;
                    }
                }

                if (submitcall == false) {
                    setTimeout(function () {
                        if ($("#onbordingSubmit").valid()) {
                            //$("#id_ogsubmit").trigger('click');
                        }
                    }, 500);
                    break;
                }
            }

            if (submitcall) {
                if ($("#onbordingSubmit").valid()) {
                    $('#modal-sm').modal('show');
                    //$('#id_ogsubmit')[0].submit();
                }
            }
        });


        $(document).on("keypress", ".alphaonly", function (event) {
            var regex = new RegExp("^[A-Za-z ]+$");
            var key = String.fromCharCode(
                !event.charCode ? event.which : event.charCode
            );
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });
    });
</script>