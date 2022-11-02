<?php ?>
<section id="content">
    <div class="container clearfix">
        <div class="row my-3">
            <div class="col-3">
                <img src="<?= $this->Url->build('/') ?>webroot/img/sale.jpg" alt="ftspl">
            </div>
            <div class="col-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                            <table class="table">
                            <tr>
                                <th>
                                    <?= __('RFQ No.') ?>
                                </th>
                                <td>
                                    <?= h($rfqDetails->id) ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <?= __('Product Category') ?>
                                </th>
                                <td>
                                    <?= $rfqDetails->has('product') ? $rfqDetails->product->name : '' ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <?= __('Sub Category') ?>
                                </th>
                                <td>
                                    <?= $rfqDetails->has('product_sub_category') ? $rfqDetails->product_sub_category->name : '' ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <?= __('Make') ?>
                                </th>
                                <td>
                                    <?= $rfqDetails->make?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <?= __('Part Name') ?>
                                </th>
                                <td>
                                    <?= $rfqDetails->part_name?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <?= __('Quantity') ?>
                                </th>
                                <td>
                                    <?= $rfqDetails->qty?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <?= __('UOM') ?>
                                </th>
                                <td>
                                    <?= $rfqDetails->has('uom') ? $rfqDetails->uom->description : '' ?>
                                </td>
                            </tr>
                            
                            <?php if($userType == 'seller') : ?>
                            <tr>
                                <td colspan="2">
                                    <div class="row">
                                        <div class="col-6">
                                            <?= $this->Form->create(null, ['url' => ['controller' => 'dealer','action' => 'inquiry',$rfqDetails->id]]); ?>
                                            <div class="row">
                                                <div class="col-6">
                                                    Quantity
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" class="form-control" name="qty" required />
                                                </div>
                                                <div class="col-6">
                                                    Rate
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" class="form-control" name="rate" required />
                                                </div>
                                                <div class="col-6">
                                                    Delivery Date
                                                </div>
                                                <div class="col-6">
                                                    <input type="date" class="form-control" name="delivery_date" required />
                                                </div>
                                            </div>
                                            <?= $this->Form->button(__('Save'), [
                                            'label' => 'Save',
                                            'class' => 'mt-3 btn btn-danger w-100',
                                        ]); ?>
                                            <?= $this->Form->end() ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <?php if($userType == 'buyer') : ?>
                                <table class="table">
                                    <tr>
                                        <th>Company</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Delivery Date</th>
                                        <th>respond Date</th>
                                    </tr>
                                    <?php foreach($results as $key => $val) : ?>
                                    <tr>
                                        <td>
                                            <?=$val['buyer_seller_user']->company_name ?>
                                        </td>
                                        <td>
                                            <?=$val['qty'] ?>
                                        </td>
                                        <td>
                                            <?=$val['rate'] ?>
                                        </td>
                                        <td>
                                            <?=$val['delivery_date'] ?>
                                        </td>
                                        <td>
                                            <?=$val['created_date'] ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>

                                </table>
                                <?php endif; ?>
                            </tr>
                        </table>
                            </div>
                            <div class="col-3" style="align-self: center;">
                            <img src="<?= $this->Url->build('/').$attrParams[0] ?>" style="width:100%;">
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>