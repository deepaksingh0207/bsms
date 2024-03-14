<?php $polickActive = ($controller == 'PurchaseOrders') ? 'active' : ''; ?>
<?php $prlickActive = ($controller == 'PurchaseRequisitions') ? 'active' : ''; ?>
<?php $dashactive = ($controller == 'Dashboard') ? 'active' : ''; ?>
<?php $intrasactive = ($controller == 'DeliveryDetails') ? 'active' : ''; ?>
<?php $settingactive = ($controller == 'Settings') ? 'active' : ''; ?>
<?php $temvenactive = ($controller == 'VendorTemps') ? 'active' : ''; ?>
<?php $vendorIndex = ($controller == 'VendorTemps' && $action == 'index') ? 'active' : ''; ?>
<?php $createvendactive = ($controller == 'VendorTemps' && $action == 'add') ? 'active' : ''; ?>
<?php $creatsaevendactive = ($controller == 'VendorTemps' && $action == 'sapAdd') ? 'active' : ''; ?>
<?php $rfqactive = ($controller == 'Rfqs') ? 'active' : ''; ?>
<?php $stocksUpload = ($controller == 'StockUploads') ? 'active' : ''; ?>
<?php $temvenmenuopen = ($controller == 'VendorTemps') ? 'menu-open' : ''; ?>
<?php $settingmenuopen = ($controller == 'Settings') ? 'menu-open' : ''; ?>
<?php $temvenactive = ($controller == 'buyervendor-temps') ? 'active' : ''; ?>
<?php $buyvendaddactive = ($controller == 'buyervendor-temps' && $action == 'add') ? 'menu-open' : ''; ?>
<?php $buyvendsaevendactive = ($controller == 'VendorTemps' && $action == 'sapAdd') ? 'menu-open' : ''; ?>
<?php $asnactive = ($controller == 'Asn') ? 'active' : ''; ?>

<li class="nav-item">
  <?= $this->Html->link(__('<i class="fas fa-tachometer-alt nav-icon"></i><p>Dashboard</p>'), ['controller' => 'dashboard', 'action' => 'index'], ['class' => "nav-link $dashactive", 'escape' => false]) ?>
</li>

<li class="nav-item <?= $temvenmenuopen ?> <?= $buyvendaddactive ?>">
  <a href="#" class="nav-link <?= $vendorIndex ?> <?= $createvendactive ?> <?= $creatsaevendactive ?>">
    <i class="nav-icon fas fa-user-alt"></i>
    <p>
      Vendor Management
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item ">
      <a href="<?= $this->Url->build('/') ?>buyer/vendor-temps" class="nav-link vendor_material <?= $vendorIndex ?>">
        <i class="nav-icon fas fa-users"></i>
        <p>Vendors</p>
      </a>
    </li>
    <li class="nav-item ">
      <a href="<?= $this->Url->build('/') ?>buyer/vendor-temps/add" class="nav-link <?= $createvendactive ?>">
        <i class="fa fa-solid fa-plus nav-icon"></i>
        <p>Add Vendor</p>
      </a>
    </li>
    <li class="nav-item ">
      <a href="<?= $this->Url->build('/') ?>buyer/vendor-temps/sap-add" class="nav-link <?= $creatsaevendactive ?>">
        <i class="fas fa-file-import nav-icon"></i>
        <p>SAP Vendor Import</p>
      </a>
    </li>
  </ul>
</li>

<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-file-invoice-dollar"></i>
    <p>
      Purchase Orders
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item ">
      <a href="<?= $this->Url->build('/') ?>buyer/purchase-orders" class="nav-link vendor_material">
        <i class="nav-icon fas fa-file-code"></i>
        <p>Report</p>
      </a>
    </li>
    <li class="nav-item ">
      <a href="<?= $this->Url->build('/') ?>buyer/purchase-orders/view" class="nav-link">
        <i class="fa fa-solid fa-calendar nav-icon"></i>
        <p>Create Schedule</p>
      </a>
    </li>
  </ul>
</li>


<li class="nav-item">
  <?= $this->Html->link(__('<i class="fas fa-truck nav-icon"></i><p>Intransit ASN</p>'), ['controller' => 'delivery-details', 'action' => 'index'], ['class' => "nav-link $intrasactive", 'escape' => false]) ?>
</li>

<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-clone"></i>
    <p>
    Masters
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item ">
      <a href="<?= $this->Url->build('/') ?>buyer/materials" class="nav-link vendor_material">
        <i class="nav-icon fas fa-users"></i>
        <p>MSL Stock</p>
      </a>
    </li>
    <li class="nav-item ">
      <a href="<?= $this->Url->build('/') ?>buyer/stock-uploads/" class="nav-link">
        <i class="fa fa-solid fa-plus nav-icon"></i>
        <p>Opening stock</p>
      </a>
    </li>
  </ul>
</li>

<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-paste"></i>
    <p>
      Reports
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item ">
      <a href="<?= $this->Url->build('/') ?>buyer/purchase-orders/secondaryAgeingReport" class="nav-link vendor_material">
        <i class="nav-icon fas fa-users"></i>
        <p>Ageing Report</p>
      </a>
    </li>
    <li class="nav-item ">
      <a href="<?= $this->Url->build('/') ?>buyer/purchase-orders/productionplanVsActual" class="nav-link">
        <i class="fa fa-solid fa-plus nav-icon"></i>
        <p>PRD Plan / Actual</p>
      </a>
    </li>
  </ul>
</li>