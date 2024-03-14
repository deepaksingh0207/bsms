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
<!-- <?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css') ?>
<?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js') ?> -->
<?= $this->Html->css('select2.min.css') ?>
<?= $this->Html->script('select2.js') ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6 col-lg-6 d-flex justify-content-start align-items-center">
                       PRODUCTION CONFIRMATION
                    </div>
                    <div class="col-sm-6 col-lg-6 d-flex justify-content-end prod_confrm">
                        <?= $this->Form->create(null, ['id' => 'formUpload', 'url' => ['controller' => '/dailymonitor', 'action' => 'upload']]) ?>

                        <div class="row justify-content-end align-items-center pr-2">
                            <div class="template_file pr-2" data-toggle="tooltip"
                                data-original-title="Download Template" data-placement="left">
                                <a class="template_format"
                                    href="<?= $this->Url->build('/') ?>webroot/templates/production_confirmation.xlsx"
                                    target="_blank" rel="noopener noreferrer"><i
                                        class="fa fa-solid fa-file-download"></i>
                                </a>
                            </div>
                            <div class="pl-2 pr-2">
                                <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
                                <?= $this->Form->control('upload_file', ['type' => 'file', 'label' => false, 'class' => 'pt-1 rounded-0', 'style' => 'visibility: hidden; position: absolute;', 'div' => 'form-group', 'id' => 'bulk_file']); ?>
                                <?= $this->Form->button('Select File', [
                                    'id' => 'OpenImgUpload',
                                    'type' =>
                                        'button',
                                    'label' => 'Select File',
                                    'class' => 'd-block btn btn-block bg-gradient-button upld_btn mb-0 file-upld-btn'
                                ]); ?>
                                <!-- <span id="filessnames"></span> -->
                            </div>
                            <div>
                                <button type="button" class="import_btn btn bg-gradient-submit" id="id_exportme">Upload</button>
                            </div>
                    
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
            <div class="card-header pb-3" id="id_pohead">
                <?= $this->Form->create(null, ['id' => 'addvendorform']) ?>
                <div class="row">

                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <label for="id_factory">Factory</label><br>
                        <select name="factory[]" id="id_factory" multiple="multiple" class="form-control chosen">
                            <?php if (isset($vendor_fty)) : ?>
                            <?php foreach ($vendor_fty as $mat) : ?>
                            <option value="<?= h($mat->factory_code) ?>" data-select="<?= h($mat->factory_code) ?>">
                                <?= h($mat->factory_code) ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <label for="id_prod_line">Production Line</label><br>
                        <select name="line[]" id="id_prod_line" multiple="multiple" class="form-control chosen">
                            <?php if (isset($prd_lines)) : ?>
                            <?php foreach ($prd_lines as $mat) : ?>
                            <option value="<?= h($mat->id) ?>" data-select="<?= h($mat->name) ?>">
                                <?= h($mat->name) ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="col-sm-12 col-md-3 col-lg-2">
                        <label for="id_plan_date">Plan Date</label><br>
                        <input class="form-control" type="date" name="plan_date" id="id_plan_date">
                    </div>

                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <label for="id_material">Material</label><br>
                        <select name="material[]" id="id_material" multiple="multiple" class="form-control chosen">
                            <?php if (isset($materials)) : ?>
                            <?php foreach ($materials as $mat) : ?>
                            <option value="<?= h($mat['id']) ?>" data-select="<?= h($mat['code']) ?>">
                                <?= h($mat['code']) ?>
                                <?= h($mat['description']) ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-1 mt-2">
                        <button class="btn bg-gradient-button mt-4" type="submit" id="id_sub">Search</button>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
            
            <div class="card-footerr ">
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
<div class="card">
<div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped material-list" id="example1">
                        <thead>
                            <tr>
                                <th>Factory</th>
                                <th>Production Line</th>
                                <th>Material</th>
                                <th>Material Desc.</th>
                                <th>Target Production</th>
                                <th>UOM</th>
                                <th>Plan Date</th>
                                <th class="table_width">Confirm Production</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
    var url_list = "<?php echo \Cake\Routing\Router::url(array('controller' => '/dailymonitor', 'action' => 'dailyentry')); ?>"
    var getConfirmedProductionUrl = "<?php echo \Cake\Routing\Router::url(array('controller' => '/dailymonitor', 'action' => 'confirmedproduction')); ?>";
    var uploadConfirmedProductionUrl = "<?php echo \Cake\Routing\Router::url(array('controller' => '/dailymonitor', 'action' => 'upload')); ?>";
</script>
<?= $this->Html->script('a_vekpro/vendor/v_dailymonitor_dailyentry') ?>