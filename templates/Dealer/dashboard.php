<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BuyerSellerUser $buyerSellerUser
 */
?>
<section id="content">
    <div class="row p-2">
        <div class="col-3 p-0">
            <div class="row">
                <img src="<?= $this->Url->build('/') ?>img/side.png" style="float: right; padding-left: 2vw;">
                <div class="col-12" style="text-align: center;">
                    <a href="<?= $this->Url->build('/') ?>dealer/addproduct/buyer">
                        <img class="login" src="<?= $this->Url->build('/') ?>img/button/1.png" style="width: 15vw;"></a>
                    <a href="<?= $this->Url->build('/') ?>dealer/addproduct/seller">
                        <img class="login" src="<?= $this->Url->build('/') ?>img/button/5.png" style="width: 15vw;"></a>
                </div>
            </div>
        </div>
        <div class="col-9 p-0">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>RFQ No.</th>
                                <th>Date Raised</th>
                                <th>Suppliers Reached</th>
                                <th>Suppliers Responded </th>
                                <th>Queries pending</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rfqDetails as $rfqDetail): ?>
                            <tr>
                                <td>
                                    <?= h($rfqDetail['id']) ?>
                                </td>
                                <td>
                                    <?= h($rfqDetail['added_date']) ?>
                                </td>
                                <td>
                                    <?= ($rfqDetail['reach']) ? h($rfqDetail['reach']) : 0 ?>
                                </td>
                                <td>
                                    <?= ($rfqDetail['respond']) ? h($rfqDetail['respond']) : 0 ?>
                                </td>
                                <td>0</td>
                                <td>
                                    <a href="/bsms/dealer/view/<?= h($rfqDetail['id']) ?>">
                                        <i class="icon-line2-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br>
                    <?php if (count($rfqsummary) > 0) : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>RFQ No.</th>
                                <th>Seller</th>
                                <th>Min. Rate</th>
                                <th>Respond Date</th>


                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rfqsummary as $rfq): ?>
                            <tr>
                                <td>
                                    <?= h($rfq['rfq_id']) ?>
                                </td>
                                <td>
                                    <?= h($rfq['company_name']) ?>
                                </td>
                                <td>
                                    <?= h($rfq['rate']) ?>
                                </td>
                                <td>
                                    <?= h($rfq['created_date']) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <!-- <div class="col-9 p-0">
            <img src="<?= $this->Url->build('/') ?>img/base.png" style="float: right;width: 84%;">
        </div> -->
    </div>
</section>