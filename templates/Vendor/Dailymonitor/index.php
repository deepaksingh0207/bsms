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
<div class="row dailymonitor">
    <div class="col-12">
        <div class="card mb-2">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-sm-6 col-lg-6 d-flex justify-content-start">
                        PRODUCTION PLANNER
                    </div>
                    <div class="col-sm-6 col-lg-6 d-flex justify-content-end add-monitor-btn">
                        <a href="<?= $this->Url->build('/') ?>vendor/dailymonitor/add"><button type="button"
                                id="continueSub" class="btn continue_btn bg-gradient-button">Add Plan</button></a>
                    </div>
                </div>
            </div>
            <div class="card-header" id="id_pohead">
                <?= $this->Form->create(null, ['id' => 'addvendorform']) ?>
                <div class="row">

                    <div class="col-sm-12 col-md-3 col-lg-2">
                        <label for="id_plan_date">Plan Date</label><br>
                        <input class="form-control" type="date" name="plan_date" id="id_plan_date">
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-2">
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
                        <div class="form-group mt-4">
                            <button class="btn bg-gradient-button" type="submit" id="id_sub">Search</button>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end() ?>
                </div>
                
            
        </div>
    </div>
</div>
<div class="card">
<div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id="example1">
                            <thead>
                                <tr>
                                    <th>Factory Code</th>
                                    <th>Production Line</th>
                                    <th>Material</th>
                                    <th>Material Desc.</th>
                                    <th>Target Production</th>
                                    <th>UOM</th>
                                    <th>Plan Date</th>
                                    <th>Confirmed Production</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
</div>

<script>
    var url_list = `<?php echo \Cake\Routing\Router::url(array('controller' => '/dailymonitor', 'action' => 'index')); ?>`;
    var url_cancel = `<?php echo \Cake\Routing\Router::url(array('controller' => '/dailymonitor', 'action' => 'change-status')); ?>/`
</script>
<?= $this->Html->script('a_vekpro/vendor/v_dailymonitor_index') ?>