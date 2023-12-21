<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Dailymonitor> $dailymonitor
 */
?>
<!-- <?= $this->Html->css('cstyle.css') ?> -->
<!-- <?= $this->Html->css('custom') ?> -->
<!-- <?= $this->Html->css('table.css') ?> -->
<!-- <?= $this->Html->css('listing.css') ?> -->
<!-- <?= $this->Html->css('v_index.css') ?> -->
<?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap4-theme/1.5.4/select2-bootstrap4.min.css') ?>
<?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css') ?>
<?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js') ?>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6 col-lg-6 d-flex justify-content-start align-items-center">
                        <h5 class="mb-0"><b>Production Confirmation</b></h5>
                    </div>
                    <div class="col-sm-6 col-lg-6 d-flex justify-content-end prod_confrm">
                    <?= $this->Form->create(null, ['id' => 'formUpload', 'url' => ['controller' => '/dailymonitor', 'action' => 'upload']]) ?>

                        <div class="row justify-content-end align-items-center pr-2">
                            <div class="template_file pr-2" data-toggle="tooltip" data-original-title="Download Template" data-placement="left">
                                <a class="template_format" 
                                    href="<?= $this->Url->build('/') ?>webroot/templates/production_confirmation.xlsx"
                                    target="_blank" rel="noopener noreferrer"><i class="fa fa-solid fa-file-download"></i>
                                </a>
                            </div>
                            <div class="pl-2 pr-2">
                                <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
                                <?= $this->Form->control('upload_file', ['type' => 'file', 'label' => false, 'class' => 'pt-1 rounded-0', 'style' => 'visibility: hidden; position: absolute;', 'div' => 'form-group', 'id' => 'bulk_file']); ?>
                                <?= $this->Form->button('Upload File', [
                                    'id' => 'OpenImgUpload',
                                    'type' =>
                                        'button',
                                    'label' => 'Upload File',
                                    'class' => 'd-block btn btn-block bg-gradient-button upld_btn mb-0 file-upld-btn'
                                ]); ?>
                                <!-- <span id="filessnames"></span> -->
                            </div>
                            <div>
                                <button type="button" class="import_btn btn bg-gradient-submit" id="id_exportme">Import File</button>
                            </div>
                    
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
            <div class="card-header" id="id_pohead">
            <?= $this->Form->create(null, ['id' => 'confirmatonform']) ?>
            <div class="row">
                
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <label for="id_factory">Factory</label><br>
                    <select name="segment[]" id="id_factory" multiple="multiple" class="form-control chosen">
                        <?php if (isset($segment)) : ?>
                        <?php foreach ($segment as $mat) : ?>
                        <option value="<?= h($mat->segment) ?>" data-select="<?= h($mat->segment) ?>">
                            <?= h($mat->segment) ?>
                        </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <label for="id_prod_line">Production Line</label><br>
                    <select name="segment[]" id="id_prod_line" multiple="multiple" class="form-control chosen">
                        <?php if (isset($segment)) : ?>
                        <?php foreach ($segment as $mat) : ?>
                        <option value="<?= h($mat->segment) ?>" data-select="<?= h($mat->segment) ?>">
                            <?= h($mat->segment) ?>
                        </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-4">
                    <label for="id_material">Material</label><br>
                    <select name="material[]" id="id_material" multiple="multiple" class="form-control chosen">
                        <?php if (isset($materialList)) : ?>
                        <?php foreach ($materialList as $mat) : ?>
                        <option value="<?= h($mat->id) ?>" data-select="<?= h($mat->code) ?>">
                            <?= h($mat->code) ?>
                            <?= h($mat->description) ?>
                        </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-1 mt-2">
                    <div class="form-group mt-4">
                        <?= $this->Form->button(__('Search'), ['class' => 'btn bg-gradient-submit', 'id' => 'id_sub', 'type' => 'submit']) ?>
                    </div>
                </div>
            </div>
            <?= $this->Form->end() ?>
                <div class="tabe-responsive">
                <table class="table table-bordered table-hover table-striped material-list" id="example1">
                    <thead>
                        <tr>
                            <th>Factory</th>
                            <th>Production Line</th>
                            <th>Material</th>
                            <th>Material Desc.</th>
                            <th>Plan Date</th>
                            <th>Target Production</th>
                            <th class="table_width">Confirm Production</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($dailymonitor)) : ?>
                        <?php foreach ($dailymonitor as $dailymonitors) : ?>
                        <tr>
                            <td>
                            <?= h($dailymonitors->production_line->line_master->vendor_factory->factory_code) ?>
                            </td>
                            <td>
                                <?= h($dailymonitors->production_line->line_master->name) ?>
                            </td>
                            <td>
                                <?= h($dailymonitors->material->code) ?>
                            </td>
                            <td>
                                <?= h($dailymonitors->material->description) ?>
                            </td>
                            <td>
                                <?= h($dailymonitors->plan_date->i18nFormat('dd-MM-YYYY')) ?>
                            </td>
                            <td>
                                <?= h($dailymonitors->target_production . ' '. $dailymonitors->material->uom) ?>
                                <input type="hidden" value="<?php echo $dailymonitors->target_production;?>"
                                    id="plan_qty_<?= h($dailymonitors->id) ?>" data-id="<?= h($dailymonitors->id) ?>">
                            </td>
                            
                            <?php if ($dailymonitors->status == 1) : ?>
                                <td>
                                    <input type="number" class="form-control form-control-sm confirm-input" id="confirmprd<?= h($dailymonitors->id) ?>" data-id="<?= h($dailymonitors->id) ?>">
                                    <span id="validationMessage<?= h($dailymonitors->id) ?>" class="text-danger" style="display: none;"></span>
                                </td>
                                <td>
                                    <button class="btn btn-success save btn-sm mb-0" id="confirmsave<?= h($dailymonitors->id) ?>" data-id="<?= h($dailymonitors->id) ?>">Save</button>
                                </td>
                            <?php elseif ($dailymonitors->status == 2) : ?>
                            <td colspan="2" class="text-center">
                                Plan Cancelled
                            </td>
                            <?php else: ?>
                            <td>
                                <input type="number" class="form-control form-control-sm"
                                    value="<?= h($dailymonitors->confirm_production) ?>" disabled>
                            </td>
                            <td></td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6">
                                No Records Found
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table></div>
            </div>
            <div class="card-footer">
            <!-- <?= $this->Form->create(null, ['id' => 'formUpload', 'url' => ['controller' => '/dailymonitor', 'action' => 'upload']]) ?>
                <div class="row">
                    <div class="col-sm-6 col-md-4 col-lg-2">
                        <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
                        <?= $this->Form->control('upload_file', ['type' => 'file', 'label' => false, 'class' => 'pt-1 rounded-0', 'style' => 'visibility: hidden; position: absolute;', 'div' => 'form-group', 'id' => 'bulk_file']); ?>
                        <?= $this->Form->button('Upload File', ['id' => 'OpenImgUpload', 'type' =>
                'button', 'label' => 'Upload File', 'class' => 'd-block btn btn-block bg-gradient-button mb-0 file-upld-btn']); ?>
                        <span id="filessnames"></span>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-2">
                    <button type="button" class="btn bg-gradient-submit" id="id_exportme">IMPORT FILE</button>
                    </div>
                    <div class="col-12 pt-2">
                        <i>
                            <a class="template_format" href="<?= $this->Url->build('/') ?>webroot/templates/production_confirmation.xlsx"
                                target="_blank" rel="noopener noreferrer">Production Confirmation.xlsx</a>
                        </i>
                    </div>
                </div>
                <?= $this->Form->end() ?> -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade confirmationModal" id="modal-sm" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h6>Are you sure you want to save Confirm Production?</h6>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn addCancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn addSubmit" id="confirmOkButton">Ok</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('.chosen').select2({
        closeOnSelect : false,
        placeholder: 'Select',
        allowClear: true,
        tags: false,
        tokenSeparators: [',', ' '],
        templateSelection: function(selection) {
            if (selection.element && $(selection.element).attr('data-select') !== undefined) {
                return $(selection.element).attr('data-select');
            } else {
                return selection.text;
            }
        }
    });
    var getConfirmedProductionUrl = "<?php echo \Cake\Routing\Router::url(array('controller' => '/dailymonitor', 'action' => 'confirmedproduction')); ?>"
    var uploadConfirmedProductionUrl = "<?php echo \Cake\Routing\Router::url(array('controller' => '/dailymonitor', 'action' => 'upload')); ?>";
    $(".confirm-input").keyup(function () {
        var id = $(this).attr('data-id');
        var val = parseFloat($(this).val().trim());
        var maxQty = parseFloat($("#plan_qty_" + id).val().trim());
        console.log(val + "=" + maxQty);
        if(val < 1) {
            $(this).val("");
        }
        /*if (val > maxQty) {
            $(this).val(maxQty);
        }*/
    });

    $('#OpenImgUpload').click(function() {
        $('#bulk_file').trigger('click');
    });
    $('#bulk_file').change(function() {
        var file = $(this).prop('files')[0].name;
        $("#filessnames").append(file);
    });

    $("#id_exportme").click(function() {
        var fd = new FormData($('#formUpload')[0]);

        $.ajax({
            url: "<?php echo \Cake\Routing\Router::url(array('controller' => '/dailymonitor', 'action' => 'upload')); ?>",
            type: "post",
            dataType: 'json',
            processData: false, // important
            contentType: false, // important
            data: fd,
            beforeSend: function () { $("#gif_loader").show(); },
            success: function(response) {
                if (response.status) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });

                    $("#example1 tbody").empty();

                    // Loop through the response data and build the table rows dynamically
                    $.each(response.data, function (key, val) { 
                        var rowHtml = `<tr>
                        <td> `+ val.factory_code + `</td>
                        <td> `+ val.line + `</td>
                        <td> `+ val.material +`</td>
                        <td> `+ val.material_description +`</td>
                        <td> `+ val.plan_date + `</td>
                        <td> `+ val.target_production + `</td>
                        <td> `+ val.confirm_production + `</td>
                        <td> `+ val.error + `</td>
                        </tr>`;
                        $("#example1 tbody").append(rowHtml);
                    });

                } else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    });
                }
            },
            error: function() {
                Toast.fire({
                    icon: 'error',
                    title: 'An error occured, please try again.'
                });
            },
            complete: function () { $("#gif_loader").hide(); }
        });
    });
</script>
<?= $this->Html->script('v_dailymonitor_dailyentry') ?>