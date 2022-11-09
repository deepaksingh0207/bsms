<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BuyerSellerUser $buyerSellerUser
 */
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <?= $this->Form->create($buyerSellerUser) ?>
            <div class="card-body">
                <?php $option = array('' => 'Type', 'buyer' => 'Buyer', 'seller' => 'Seller'); ?>
                <div class="row">
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('user_type', [
                                                'type' => 'select',
                                                'options' => $option,
                                                'empty' => 'Select',
                                                'id' => 'product',
                                                'label' => 'User Type',
                                                'class' => 'form-control',
                                            ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('username', [
                                                'label' => 'Username',
                                                'type' => 'text',
                                                'class' => 'form-control',
                                            ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('password', [
                                                'label' => 'Password',
                                                'type' => 'password',
                                                'class' => 'form-control',
                                            ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('re_password', [
                                                'label' => 'Confirm Password',
                                                'type' => 'password',
                                                'class' => 'form-control',
                                            ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('company_name', [
                                                'label' => 'Company',
                                                'type' => 'text',
                                                'class' => 'form-control',
                                            ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('address', [
                                                    'label' => 'text',
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('cities', [
                                                    'label' => 'Cities',
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('email', [
                                                    'label' => 'Email',
                                                    'type' => 'email',
                                                    'class' => 'form-control',
                                                ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('contact', [
                                                    'label' => 'Contact',
                                                    'type' => 'number',
                                                    'class' => 'form-control',
                                                ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('alt_contact', [
                                                    'label' => 'Alt. Contact',
                                                    'type' => 'number',
                                                    'class' => 'form-control',
                                                ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('business_type', [
                                                    'label' => 'Business',
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('product_deals', [
                                                    'type' => 'select',
                                                    'options' => $products,
                                                    'empty' => 'Select',
                                                    'id' => 'product',
                                                    'label' => 'Product Deals',
                                                    'class' => 'form-control',
                                                ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('TIN', [
                                                    'label' => 'TIN',
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                ]) ?>
                    </div>
                    <div class="col-3 mt-3">
                        <?= $this->Form->control('GST', [
                                                    'label' => 'GST',
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                ]) ?>
                    </div>
                    <div class="col-3 mt-4 pt-4">
                        <?= $this->Form->button(__('Submit'), [
                                            'label' => 'Signup',
                                            'class' => 'btn btn-danger',
                                        ]); ?>
                    </div>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>