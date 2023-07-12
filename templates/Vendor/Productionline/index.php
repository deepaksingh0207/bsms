<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Productionline> $productionline
 */
?>
</style>
<?= $this->Html->css('custom') ?>
<div class="card">
    <div class="card-header pb-1 pt-2">
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-start">
                <h5>Production Line</h5>
            </div>
            <div class="col-lg-6 d-flex justify-content-end text-align-end">
                <a href="<?= $this->Url->build('/') ?>vendor/productionline/add"><button type="button" id="continueSub"
                        class="btn mb-0 continue_btn btn-dark">Add Producation</button></a>
            </div>
        </div>
    </div>

    <div class="card-header p-0 mb-0 mt-3 ml-3 mr-3" id="id_pohead">
        <table class="table table-bordered material-list">
            <thead>
                <tr>
                    <th>status</th>
                    <th>Prod Line Capacity</th>
                    <th>Description</th>
                    <th>Added Date</th>
                    <th>Update Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($productionline)) : ?>
                    <?php foreach ($productionline as $productionlines) : ?>
                    <tr>
                        <td>
                            <?= $productionlines->status == 1 ? '<span class="badge lgreenbadge" data-toggle="tooltip" data-placement="right" title="Approved"><i class="fas fa-user-check"></i></span>' : ($productionlines->status == 0 ? '<span class="badge dbluebadge" data-toggle="tooltip" data-placement="right" title="Pending For Approval"><i class="fas fa-user-clock"></i></span>' : '<span class="badge redbadge" data-toggle="tooltip" data-placement="right" title="Rejected"><i class="fas fa-user-slash"></i></span>') ?>
                        </td>

                        <td>
                            <?= h($productionlines->prdline_capacity) ?>
                        </td>
                        <td>
                            <?= h($productionlines->prdline_description) ?>
                        </td>
                        <td>
                            <?= h($productionlines->added_date->format('d-m-Y')) ?>
                        </td>
                        <td>
                            <?= h($productionlines->updated_date->format('d-m-Y')) ?>
                        </td>
                        <td>
                            <div class="float-left">
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $productionlines->id], ['class' => 'btn btn-info btn-sm mb-0']) ?>
                            </div>
                        </td>
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
        </table>
    </div>
</div>