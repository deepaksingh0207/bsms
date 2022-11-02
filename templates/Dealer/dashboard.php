<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BuyerSellerUser $buyerSellerUser
 */
?>

<div class="row">
    <div class="column-responsive column-80">

        <div class="buyerSellerUsers view content">
        <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>RFQ No.</th>
                    <th>Date Raised</th>
                    <th>suppliers reached</th>
                    <th>suppliers responded </th>
                    <th>Queries pending</th>
                    <th>Action</th>


                </tr>
            </thead>
            <tbody>
                <?php foreach ($rfqDetails as $rfqDetail):
                      //print_r($rfqDetail); exit;
                    ?>
                <tr>
                    <td><?= h($rfqDetail['id']) ?></td>
                    <td><?= h($rfqDetail['added_date']) ?></td>
                    <td><?= ($rfqDetail['reach']) ? h($rfqDetail['reach']) : 0 ?></td>
                    <td><?= ($rfqDetail['respond']) ? h($rfqDetail['respond']) : 0 ?></td>
                    <td>0</td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $rfqDetail['id']]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    
</div>  
<?php if (count($rfqsummary) > 0) : ?>
        <div class="buyerSesummaryllerUsers view content" style="margin-top:50px;">
        <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>RFQ No.</th>
                    <th>Seller</th>
                    <th>Min. Rate</th>
                    <th>respond Date</th>


                </tr>
            </thead>
            <tbody>
                <?php foreach ($rfqsummary as $rfq):
                      //print_r($rfqDetail); exit;
                    ?>
                <tr>
                    <td><?= h($rfq['rfq_id']) ?></td>
                    <td><?= h($rfq['company_name']) ?></td>
                    <td><?= h($rfq['rate']) ?></td>
                    <td><?= h($rfq['created_date']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>
    </div>
</div>
