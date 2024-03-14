<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VendorTemp $vendorTemp
 * @var \Cake\Collection\CollectionInterface|string[] $purchasingOrganizations
 * @var \Cake\Collection\CollectionInterface|string[] $accountGroups
 * @var \Cake\Collection\CollectionInterface|string[] $schemaGroups
 */
?>
  <?= $this->Html->css('custom') ?>
<div class="row">
    
    <div class="col-12">
        <div class="">
            <?= $this->Form->create() ?>
            <!-- <div class="card-header">
            <h4 class="p-2 text-info"><b>Gate Entry (GE)</b></h3>
            </div> -->
            <div class="gate-entry">
                <div class="row">
                <!-- <div class="col-sm-12 col-md-6">
                <h5>Enter ASN No</h5>
                </div> -->
                    <div class="col-sm-12 col-md-12">
                       <div class="content-box">
                      
                        <!-- <h5 class="mb-2 gate-entry-haed font-weight-bold">ENTER ASN NO :</h5> -->
                        
                            
                           <img  class="scanner-img" src="<?= $this->Url->build('/')  ?>img/barcode.gif"> 
                        
                           
                        <span>Scan your ASN barcode</span>  
                        <p><b>OR</b></p>
                        <br>
                           <div class="s-box">
                           <?php echo $this->Form->control('asn_no', array('label' => false, 'maxlength'=>'15', 'class' => 'form-control rounded-0', "placeholder"=>"Start entering ASN or Invoice Number",'div' => 'form-group', 'required')); ?>
                            <?= $this->Form->button(__('GO'), ['class' => 'btn btn-custom mt-3']) ?>
                           </div>
                           
                       
                       </div>
                    </div>
                       
                   
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
