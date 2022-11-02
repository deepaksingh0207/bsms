<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BuyerSellerUser $buyerSellerUser
 */
?>
<section id="content">
    <div class="container clearfix">
        <div class="row my-3">
            <div class="col-12">
                <h2>Product List</h2>
            </div>
            <?php foreach($rfqDetails as $key => $val) : ?>
            <?php $attrParams = json_decode($val->attribute_data, true); ?>
            <div class="col-5 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3" style="text-align-last: center;">
                                <img src="<?= $this->Url->build('/') . $val['image']?>" width="100%" style="">
                            </div>
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-4">
                                        RFQ No.
                                    </div>
                                    <div class="col-8">
                                        :
                                        <?=$val['id']?>
                                    </div>
                                    <div class="col-4">
                                        Category
                                    </div>
                                    <div class="col-8">
                                        :
                                        <?=$val['product']->name?>
                                    </div>
                                    <div class="col-4">
                                        Sub Category
                                    </div>
                                    <div class="col-8">
                                        :
                                        <?=$val['product_sub_category']->name?>
                                    </div>
                                    <div class="col-4">
                                        Part Name
                                    </div>
                                    <div class="col-8">
                                        :
                                        <?=$val['part_name']?>
                                    </div>
                                    <div class="col-4">
                                        Make
                                    </div>
                                    <div class="col-8">
                                        :
                                        <?=$val['make']?>
                                    </div>
                                    <div class="col-4">
                                        UOM
                                    </div>
                                    <div class="col-8">
                                        :
                                        <?=$val['uom']->description?>
                                    </div>
                                    <div class="col-4">
                                        Remarks
                                    </div>
                                    <div class="col-8">
                                        :
                                        <?=$val['remarks']?>
                                    </div>
                                    <div class="col-8 mt-3">
                                            <a
                                                href="<?= $this->Url->build('/') ?>dealer/view/<?=$val['id']?>" class="btn w-100 pale">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</section>