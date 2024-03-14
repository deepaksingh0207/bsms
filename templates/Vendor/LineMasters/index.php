<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\LineMaster> $lineMasters
 */
?>
<?= $this->Html->css('v_linemasters_index') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header lineMasters d-flex justify-content-between align-items-center">
                <div class="col-lg-6 pl-0">
                
                    <?= __('LINE MASTER') ?>
                </div>
                <div class="col-lg-6 d-flex justify-content-end pr-0"><?= $this->Html->link(__('New Line Master'), ['action' => 'add'], ['class' => 'btn bg-gradient-button']) ?></div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Line Description</th>
                                <th>Factory</th>
                                <th>Capacity (Per Day)</th>
                                <th>UOM</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($lineMasters)) : ?>
                                <?php foreach ($lineMasters as $lineMaster): ?>
                                <tr class=""
                                    data-href="<?= $this->Url->build('/') ?>vendor/line-masters/edit/<?= $lineMaster->id ?>">
                                    <td>
                                        <?= $lineMaster->name ?>
                                    </td>
                                    <td>
                                        <?= h($lineMaster->vendor_factory->factory_code) ?>
                                    </td>
                                    <td>
                                        <?= $this->Number->format($lineMaster->capacity) ?>
                                    </td>
                                    <td>
                                        <?= h($lineMaster->uom) ?>
                                    </td>
                                    <td>
                                        <?php if($lineMaster->status == 1) : ?>
                                        Active
                                        <?php else : ?>
                                        Inactive
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        No Records Found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script('v_linemasters_index') ?>
