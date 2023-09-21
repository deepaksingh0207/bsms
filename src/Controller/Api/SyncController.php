<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;



/**
 * Home Controller
 *
 * @method \App\Model\Entity\Home[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SyncController extends ApiAppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    var $uses = false;

    public function initialize(): void
    {
        parent::initialize();

        $this->autoRender = false;

        date_default_timezone_set('Asia/Kolkata');

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);

        $this->loadComponent('Ftp');

        ini_set('default_socket_timeout', '120');
    }


    public function master() {
        set_time_limit(300);
        $response = array();
        $response['message'] = [];
        
        try {
            $ftpConn = $this->Ftp->connection();
            //$list = $this->Ftp->getList($ftpConn);
            $data  = $this->Ftp->downloadFile($ftpConn, 'MASTER.JSON');
            
            if($data) {

                $data = trim(preg_replace('/\s+/', ' ', $data));
                $d = json_decode($data);

                foreach($d->ORG_MASTER as $mKey => $mVal) {
                    if($mKey == 'M_TITLE') {
                        $response['message'][] = $this->saveTitles((array)$mVal);
                    }
                    if($mKey == 'CC_LIST') {
                        $response['message'][] =$this->saveCompanyCodes((array)$mVal);
                    }
                    if($mKey == 'PAY_TERM') {
                        $response['message'][] =$this->savePayTerms((array)$mVal);
                    }
                    if($mKey == 'PUR_ORG') {
                        $response['message'][] =$this->savePurchasingOrganizations((array)$mVal);
                    }
                    if($mKey == 'ACC_GRP') {
                        $response['message'][] =$this->saveAccountGroups((array)$mVal);
                    }
                    if($mKey == 'COUNTRY') {
                        $response['message'][] =$this->saveCountries((array)$mVal);
                    }
                    if($mKey == 'REGION') {
                        $response['message'][] =$this->saveRegions((array)$mVal);
                    }
                    if($mKey == 'SCH_GRP') {
                        $response['message'][] =$this->saveSchemaGroups((array)$mVal);
                    }

                    if($mKey == 'REC_ACC') {
                        $response['message'][] =$this->saveReconciliationAccounts((array)$mVal);
                    }
                    if($mKey == 'CURRENCY') {
                        $response['message'][] =$this->saveCurrencies((array)$mVal);
                    }
                    if($mKey == 'UOM') {
                        $response['message'][] =$this->saveUOMs((array)$mVal);
                    }
                }
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        
        //$ftpConn->close();
        echo json_encode($response);
    }

    function saveCompanyCodes($CompanyCodeMaster = array()) {
        if(!empty($CompanyCodeMaster)) {
            //echo '<pre>'; print_r($CompanyCodeMaster); exit;
            $this->loadModel("CompanyCodes");
            $columns = array('code', 'name');
            $upsertQuery = $this->CompanyCodes->query();
            $upsertQuery->insert($columns);
            foreach($CompanyCodeMaster as $k => $v) {
                $upsertQuery->values(array('code' => $v->BUKRS, 'name' => $v->BUTXT));
            }
            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `name`=VALUES(`name`)')->execute()) {
                return 'Company Code sync successfully!';
            } else {
                return 'Company Code sync fail!';
            }
            
        } 
    }

    function savePurchasingOrganizations($purchOrgMaster) {
        if(!empty($purchOrgMaster)) {
            $this->loadModel("CompanyCodes");
            $this->loadModel("PurchasingOrganizations");

            $company_codes = $this->CompanyCodes->find('list', ['keyField' => 'code', 'valueField' => 'id'])->toArray();
            $columns = array('code', 'name', 'company_code_id');
            $upsertQuery = $this->PurchasingOrganizations->query();
            $upsertQuery->insert($columns);

            foreach($purchOrgMaster as $k => $v) {
                $upsertQuery->values(array('code' => $v->EKORG, 'name' => $v->EKOTX, 'company_code_id' => $company_codes[$v->BUKRS]));
            }
            
            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `name`=VALUES(`name`)')->execute()) {
                return 'Purchase Organization sync successfully!';
            } else {
                return 'Purchase Organization sync fail!';
            }
        }
    }

    function saveReconciliationAccounts($reconcAcMaster) {
        if(!empty($reconcAcMaster)) {
            $this->loadModel("CompanyCodes");
            $this->loadModel("ReconciliationAccounts");

            $company_codes = $this->CompanyCodes->find('list', ['keyField' => 'code', 'valueField' => 'id'])->toArray();

            //echo '<pre>'; print_r($company_codes); exit;
            $columns = array('code', 'name', 'company_code_id');
            $upsertQuery = $this->ReconciliationAccounts->query();
            $upsertQuery->insert($columns);

            foreach($reconcAcMaster as $k => $v) {
                //echo '<pre>'; echo $v->BUKRS, '='; print_r($reconcAcMaster[$k]); echo '<br />';
                $upsertQuery->values(array('code' => $v->SAKNR, 'name' => $v->TXT20, 'company_code_id' => $company_codes[$v->BUKRS]));
            }
            
            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `name`=VALUES(`name`)')->execute()) {
                return 'Reconciliation Accounts sync successfully!';
            } else {
                return 'Reconciliation Accounts sync fail!';
            }
        }
    }

    function saveAccountGroups($acctGroupMaster = array()) {
        //echo '<pre>'; print_r($acctGroupMaster); exit;
        if(!empty($acctGroupMaster)) {
            $this->loadModel("AccountGroups");
            $columns = array('code', 'name');
            $upsertQuery = $this->AccountGroups->query();
            $upsertQuery->insert($columns);
            foreach($acctGroupMaster as $k => $v) {
                $upsertQuery->values(array('code' => $v->KTOKK, 'name' => $v->TXT30));
            }

            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `name`=VALUES(`name`)')->execute()){
                return 'Account Groups sync successfully!';
            } else {
                return 'Account Groups sync fail!';
            }
        } 
    }

    function savePayTerms($payTermMaster = array()) {
        //echo '<pre>'; print_r($payTermMaster); exit;
        if(!empty($payTermMaster)) {
            $this->loadModel("PaymentTerms");
            $columns = array('code', 'description');
            $upsertQuery = $this->PaymentTerms->query();
            $upsertQuery->insert($columns);
            foreach($payTermMaster as $k => $v) {
                $upsertQuery->values(array('code' => $v->ZTERM, 'description' => $v->TEXT1));
            }

            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `description`=VALUES(`description`)')->execute()) {
                return 'Payment Terms sync successfully!';
            } else {
                return 'Payment Terms sync fail!';
            }
        } 
    }

    function saveCountries($countryMaster = array()) {
        //echo '<pre>'; print_r($countryMaster); exit;
        if(!empty($countryMaster)) {
            $this->loadModel("Countries");
            $columns = array('country_code', 'country_name');
            $upsertQuery = $this->Countries->query();
            $upsertQuery->insert($columns);
            foreach($countryMaster as $k => $v) {
                $upsertQuery->values(array('country_code' => $v->LAND1, 'country_name' => $v->LANDX));
            }

            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `country_name`=VALUES(`country_name`)')->execute()) {
                return 'Country sync successfully!';
            } else {
                return 'Country sync fail!';
            }
        } 
    }

    function saveSchemaGroups($schimaGroupMaster = array()) {
        //echo '<pre>'; print_r($schimaGroupMaster); exit;
        if(!empty($schimaGroupMaster)) {
            $this->loadModel("SchemaGroups");
            $columns = array('code', 'name');
            $upsertQuery = $this->SchemaGroups->query();
            $upsertQuery->insert($columns);
            foreach($schimaGroupMaster as $k => $v) {
                $upsertQuery->values(array('code' => $v->KALSK, 'name' => $v->KALSB));
            }

            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `name`=VALUES(`name`)')->execute()) {
                return 'Schema Groups sync successfully!';
            } else {
                return 'Schema Groups sync fail!';
            }
        } 
    }

    function saveRegions($regionMaster = array()) {
        //echo '<pre>'; print_r($regionMaster); exit;
        if(!empty($regionMaster)) {
            $this->loadModel("States");
            $upsertQuery = $this->States->query();
            $columns = array('region_code','name', 'country_code');
            $upsertQuery->insert($columns);
            foreach($regionMaster as $k => $v) {
                $upsertQuery->values(array('region_code' => $v->BLAND, 'name' => $v->BEZEI, 'country_code' => $v->LAND1));
            }
            
            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `name`=VALUES(`name`)')->execute()) {
                return 'Region sync successfully!';
            } else {
                return 'Region sync fail!';
            }
        } 
    }

    function saveTitles($titleMaster = array()) {
        //echo '<pre>'; print_r($schimaGroupMaster); exit;
        if(!empty($titleMaster)) {
            $this->loadModel("Titles");
            $columns = array('code', 'name');
            $upsertQuery = $this->Titles->query();
            $upsertQuery->insert($columns);
            foreach($titleMaster as $k => $v) {
                $upsertQuery->values(array('code' => $v->TITLE, 'name' => $v->TITLE_MEDI));
            }

            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `name`=VALUES(`name`)')->execute()) {
                return 'Titles sync successfully!';
            } else {
                return 'Titles sync fail!';
            }
        } 
    }

    function savecurrencies($CurrencyMaster = array()) {
        //echo '<pre>'; print_r($schimaGroupMaster); exit;
        if(!empty($CurrencyMaster)) {
            $this->loadModel("Currencies");
            $columns = array('code', 'name');
            $upsertQuery = $this->Currencies->query();
            $upsertQuery->insert($columns);
            foreach($CurrencyMaster as $k => $v) {
                $upsertQuery->values(array('code' => $v->WAERS, 'name' => $v->KTEXT));
            }

            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `name`=VALUES(`name`)')->execute()) {
                return 'Currencies sync successfully!';
            } else {
                return 'Currencies sync fail!';
            }
        } 
    }

    function saveUOMs($uomMaster = array()) {
        //echo '<pre>'; print_r($schimaGroupMaster); exit;
        if(!empty($uomMaster)) {
            $this->loadModel("Uoms");
            $columns = array('code', 'description');
            $upsertQuery = $this->Uoms->query();
            $upsertQuery->insert($columns);
            foreach($uomMaster as $k => $v) {
                $upsertQuery->values(array('code' => $v->MSEHI, 'description' => $v->MSEHL));
            }

            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE `description`=VALUES(`description`)')->execute()) {
                return 'UOM sync successfully!';
            } else {
                return 'UOM sync fail!';
            }
        } 
    }
    

    public function purchaseOrder()  {
        set_time_limit(300);
        $response = array();
        $response['message'] = [];
        
        $ftpConn = $this->Ftp->connection();
        $list = $this->Ftp->getList($ftpConn);

        $this->loadModel("PoHeaders");
        $this->loadModel("PoFooters");
        $this->loadModel("Materials");

        foreach($list as $fileKey => $val) {
            if(str_starts_with($fileKey, 'PO_')) {
                $data  = $this->Ftp->downloadFile($ftpConn, $fileKey);
                
                if($data) {
                    $data = trim(preg_replace('/\s+/', ' ', $data));
                    $d = json_decode($data);

                    foreach($d->PO_LIST as $key => $row) {
                        $hederData = array();
                        $footerData = array();

                        $hederData['sap_vendor_code'] = $row->LIFNR;
                        $hederData['po_no'] = $row->EBELN;
                        $hederData['document_type'] = $row->BSART;
                        $hederData['created_on'] = date("Y-m-d H:i:s", strtotime($row->AEDAT));
                        $hederData['created_by'] = $row->ERNAM;
                        $hederData['pay_terms'] = $row->ZTERM;
                        $hederData['currency'] = $row->WAERS;
                        $hederData['exchange_rate'] = $row->WKURS;
                        $hederData['release_status'] = $row->FRGZU;

                        if($this->PoHeaders->exists(['po_no' => $row->EBELN])) {
                            $poInstance = $this->PoHeaders->find()->where(['po_no' => $row->EBELN])->first();
                            $poInstance = $this->PoHeaders->patchEntity($poInstance, $hederData);
                        } else {
                            $poInstance = $this->PoHeaders->newEmptyEntity();
                            $poInstance = $this->PoHeaders->patchEntity($poInstance, $hederData);
                        }

                        try {
                            if ($this->PoHeaders->save($poInstance)) {
                                $po_header_id = $poInstance->id;

                                foreach ($row->ITEM as $no => $item) {
                                    $tmp = array();
                                    $tmp['po_header_id'] = $po_header_id;
                                    $tmp['item'] = $item->EBELP;
                                    $tmp['deleted_indication'] = $item->LOEKZ;
                                    $tmp['material'] = $item->MATNR;
                                    $tmp['short_text'] = $item->TXZ01;
                                    $tmp['po_qty'] = $item->MENGE;
                                    $tmp['grn_qty'] = $item->R_QTY;
                                    $tmp['pending_qty'] = $item->P_QTY;
                                    $tmp['order_unit'] = $item->MEINS;
                                    $tmp['net_price'] = $item->NETPR;
                                    $tmp['price_unit'] = $item->PEINH;
                                    $tmp['net_value'] = $item->NETWR;
                                    $tmp['gross_value'] = $item->BRTWR;

                                    $footerData = $tmp;
                                    if($this->PoFooters->exists(['po_header_id' => $po_header_id, 'item' => $item->EBELP])) {
                                        $poItemsInstance = $this->PoFooters->find()->where(['po_header_id' => $po_header_id, 'item' => $item->EBELP])->first();
                                        $poItemsInstance = $this->PoFooters->patchEntity($poItemsInstance, $hederData);
                                    }  else {
                                        $poItemsInstance = $this->PoFooters->newEmptyEntity();
                                        $poItemsInstance = $this->PoFooters->patchEntity($poItemsInstance, $footerData);
                                    }

                                    //sync material master
                                    $upsertQuery = $this->Materials->query();
                                    $upsertQuery->insert(['sap_vendor_code', 'code', 'description', 'uom']);
                                    $upsertQuery->values(['sap_vendor_code' => $row->LIFNR, 'code' => $item->MATNR, 'description' => $item->TXZ01, 'uom' => $item->MEINS]);
                                    $upsertQuery->epilog('ON DUPLICATE KEY UPDATE `sap_vendor_code`=VALUES(`sap_vendor_code`), `code`=VALUES(`code`),
                                        `description`=VALUES(`description`), `uom`=VALUES(`uom`)')
                                        ->execute();

                                    if ($this->PoFooters->save($poItemsInstance)) {
                                        $response['message'][] = 'PO : '.$row->EBELN.' Item : '.$item->EBELP.' saved successfully';
                                    } else {
                                        $response['message'][] = 'PO :'.$row->EBELN.' Item : '.$item->EBELP.' save fail';
                                    }
                                }

                                $this->Ftp->removeFile($ftpConn, $fileKey);
                                
                            } else if($poInstance->getError('po_no')) {
                                $response['message'][] = $poInstance->getError('po_no');
                            }else {
                                $response['message'][] = 'PO :'.$row->EBELN.' save fail';
                            }
                        } catch (\PDOException $e) {
                            $response['message'][] = $e->getMessage();
                        } catch (\Exception $e) {
                            $response['message'][] = $e->getMessage();
                        }
                    }
                }
            }
        }
        echo json_encode($response);
    }

    public function vendorData() {
        set_time_limit(300);
        $response = array();
        $response['message'] = [];
        
        $ftpConn = $this->Ftp->connection();
        //$list = $this->Ftp->getList($ftpConn);
        $data  = $this->Ftp->downloadFile($ftpConn, 'VENDOR_PULL_LIST.JSON');
        if($data) {
            $this->loadModel("VendorTemps");

            $data = trim(preg_replace('/\s+/', ' ', $data));
            $d = json_decode($data);

            foreach($d->VENDOR_LIST as $key => $row) {
                $companyCode = $this->VendorTemps->CompanyCodes->findByCode($row->BUKRS)->first();
                $puOrg = $this->VendorTemps->PurchasingOrganizations->findByCode($row->EKORG)->first();
                $accGrp = $this->VendorTemps->AccountGroups->findByCode($row->KTOKK)->first();
                $reconAccount = $this->VendorTemps->ReconciliationAccounts->findByCode($row->AKONT)->first();
                $companyCode = $this->VendorTemps->CompanyCodes->findByCode($row->BUKRS)->first();
                $schemaGroup = $this->VendorTemps->SchemaGroups->findByCode($row->KALSK)->first();
                $region = $this->VendorTemps->States->findByRegionCode($row->REGION)->first();
                $country = $this->VendorTemps->Countries->findByCountryCode($row->COUNTRY)->first();
                $payTerm = $this->VendorTemps->PaymentTerms->findByCode($row->ZTERM)->first();
                
                $vendorExists = false;
                if($this->VendorTemps->exists(['sap_vendor_code' => $row->LIFNR])) {
                    $vendor = $this->VendorTemps->find()->where(['sap_vendor_code' => $row->LIFNR])->first();
                    $vendorExists = true;
                } else {
                    $vendor = $this->VendorTemps->newEmptyEntity();
                    $vendor->status = 5;
                    $vendorExists = false;
                }

                $vendor->sap_vendor_code = $row->LIFNR;
                $vendor->company_code_id = $companyCode->id;
                $vendor->purchasing_organization_id = $puOrg->id;
                $vendor->account_group_id = $accGrp->id;
                $vendor->reconciliation_account_id = $reconAccount->id;
                $vendor->payment_term_id = $payTerm->id;
                if($schemaGroup) {
                    $vendor->schema_group_id = $schemaGroup->id;
                }
                if($region) {
                    $vendor->state_id = $region->id;
                }
                if($country) {
                    $vendor->country_id = $country->id;
                }
                $vendor->title = $row->TITLE_MEDI;
                $vendor->name = $row->NAME1;
                $vendor->address = $row->NAME2;
                $vendor->address_2 = $row->NAME3;
                $vendor->city = $row->CITY1;
                $vendor->pincode = $row->POST_CODE1;
                $vendor->email = $row->SMTP_ADDR;
                $vendor->mobile = $row->MOB_NUMBER;
                $vendor->gst_no = $row->GSIN;
                $vendor->pan_no = $row->PAN;
                //$vendor->buyer_id = 8;

                if($this->VendorTemps->save($vendor)) {
                    $response['message'][] = 'Vendor '.$row->LIFNR.' saved successfully!';
                    if(!$vendorExists) {
                        $this->loadModel("Users");
                        $user = $this->Users->newEmptyEntity();
                        
                        $data = array();
                        $data['first_name'] = $vendor->name;
                        $data['last_name'] = $vendor->name;
                        $data['username'] = $vendor->email;
                        $data['mobile'] = $vendor->mobile;
                        $data['password'] = $vendor->mobile;
                        $data['group_id'] = 3;

                        $user = $this->Users->patchEntity($user, $data);

                        if ($this->Users->save($user)) {
                           
                            /*$visit_url = Router::url(['prefix' => false, 'controller' => 'users', 'action' => 'login', '_full' => true, 'escape' => true]);
                            $mailer = new Mailer('default');
                            $mailer
                                ->setTransport('smtp')
                                ->setViewVars([ 'subject' => 'Hi ' . $data['first_name'], 'mailbody' => 'Welcome to Vendor portal. <br/> <br/> Username: ' . $data['username'] .
                                '<br/>Password:' . $data['password'], 'link' => $visit_url, 'linktext' => 'Click Here' ])
                                ->setFrom(['vekpro@fts-pl.com' => 'FT Portal'])
                                ->setTo($data['username'])
                                ->setEmailFormat('html')
                                ->setSubject('Vendor Portal - Account created')
                                ->viewBuilder()
                                ->setTemplate('mail_template');
                            $mailer->deliver(); */
                        }
                    }
                } else {
                    $response['message'][] = 'Vendor '.$row->LIFNR.' save fail';
                }
            }

            echo json_encode($response);
        }
    }

    public function getMaterialMinStock()
    {
        $response = array();
        $response['status'] = 0;
        $response['message'] = 'Empty request';
        
        $this->loadModel("Materials");
        $this->loadModel("MaterialHistories");
        
        set_time_limit(300);
        $response = array();
        $response['message'] = [];

        $matStock = [];
        
        $ftpConn = $this->Ftp->connection();
        $data  = $this->Ftp->downloadFile($ftpConn, "MATERIAL_MIN_STOCK.JSON");
        if($data) {
            $data = trim(preg_replace('/\s+/', ' ', $data));
            $d = json_decode($data);

            foreach($d->MIN_STOCK as $key => $row) {
                $temp = [];
                $temp['sap_vendor_code'] = $row->LIFNR;
                $temp['code'] = $row->MATNR;
                $temp['description'] = $row->MAKTX;
                $temp['minimum_stock'] = $row->MIN_STOCK;
                $temp['uom'] = $row->MEINS;
                $matStock[] = $temp;
            }

            $columns = array_keys($matStock[0]);
            $upsertQuery = $this->Materials->query();
            $upsertQuery->insert($columns);

            foreach($matStock as $row) {
                $upsertQuery->values($row);
            }
            $upsertQuery->epilog('ON DUPLICATE KEY UPDATE `sap_vendor_code`=VALUES(`sap_vendor_code`), `code`=VALUES(`code`),
                `description`=VALUES(`description`), `minimum_stock`=VALUES(`minimum_stock`), `uom`=VALUES(`uom`)')
                ->execute();

            $materialHistories = $this->MaterialHistories->newEntities($matStock);
            $this->MaterialHistories->saveMany($materialHistories);

            $response['status'] = '1';
            $response['message'] = 'Success';
            $response['data'] = $materialHistories;
        }

        echo json_encode($response);
    }

    function getVendors() {
        set_time_limit(300);
        $response = array();
        try{
            $ftpConn = $this->Ftp->connection();
            $list = $this->Ftp->getList($ftpConn);
            $this->getRequestedVendor($ftpConn, $list);
            $this->getCreatedVendor($ftpConn, $list);
            $this->getRequestedBuyer($ftpConn, $list);
            $this->asnUpdates($ftpConn, $list);
            $response['status'] = 1;
            $response['message'] = 'Vendor sync done';
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response); exit;

    }

    function getRequestedVendor($ftpConn, $list) {

        $this->loadModel("Users");
        $response = array();
        $response['status'] = 0;
        $response['message'] = [];
        
        foreach($list as $fileKey => $val) {
            if(str_starts_with($fileKey, 'VENDOR_GET_')) {
                $data  = $this->Ftp->downloadFile($ftpConn, $fileKey);
                if($data) {
                    $this->loadModel("VendorTemps");
        
                    $data = trim(preg_replace('/\s+/', ' ', $data));
                    $d = json_decode($data);
        
                    foreach($d->VENDOR_LIST as $key => $row) {
                        $companyCode = $this->VendorTemps->CompanyCodes->findByCode($row->BUKRS)->first();
                        $puOrg = $this->VendorTemps->PurchasingOrganizations->findByCode($row->EKORG)->first();
                        $accGrp = $this->VendorTemps->AccountGroups->findByCode($row->KTOKK)->first();
                        $reconAccount = $this->VendorTemps->ReconciliationAccounts->findByCode($row->AKONT)->first();
                        $companyCode = $this->VendorTemps->CompanyCodes->findByCode($row->BUKRS)->first();
                        $schemaGroup = $this->VendorTemps->SchemaGroups->findByCode($row->KALSK)->first();
                        $region = $this->VendorTemps->States->findByRegionCode($row->REGION)->first();
                        $country = $this->VendorTemps->Countries->findByCountryCode($row->COUNTRY)->first();
                        $payTerm = $this->VendorTemps->PaymentTerms->findByCode($row->ZTERM)->first();
                        
                        if(trim($row->SUCCESS) == "0") { continue; }
                        $vendorExists = false;
                        $createUser = false;
                        if($this->VendorTemps->exists(['sap_vendor_code' => str_pad($row->LIFNR, 10, "0", STR_PAD_LEFT)])) {
                            $vendor = $this->VendorTemps->find()->where(['sap_vendor_code' => str_pad($row->LIFNR, 10, "0", STR_PAD_LEFT)])->first();
                            $vendorExists = true;
                        } else {
                            $vendor = $this->VendorTemps->newEmptyEntity();
                            $vendor->status = 5;
                            $vendor->from_sap = 1;
                            $vendorExists = false;
                        }
                        
                        if(!$vendorExists && $this->VendorTemps->exists(['name' => $row->NAME1, 'email' => $row->SMTP_ADDR, 'mobile' => $row->MOB_NUMBER])) {
                            $vendor = $this->VendorTemps->find()->where(['name' => $row->NAME1, 'email' => $row->SMTP_ADDR, 'mobile' => $row->MOB_NUMBER])->first();
                            $vendor->status = 5;
                            $vendorExists = true;
                            $createUser = true;
                        }

                        $vendor->sap_vendor_code = $row->LIFNR;
                        $vendor->company_code_id = $companyCode->id;
                        $vendor->purchasing_organization_id = $puOrg->id;
                        $vendor->account_group_id = $accGrp->id;
                        $vendor->reconciliation_account_id = $reconAccount->id;
                        $vendor->payment_term_id = $payTerm->id;
                        if($schemaGroup) {
                            $vendor->schema_group_id = $schemaGroup->id;
                        }
                        if($region) {
                            $vendor->state_id = $region->id;
                        }
                        if($country) {
                            $vendor->country_id = $country->id;
                        }
                        $vendor->title = $row->TITLE_MEDI;
                        $vendor->name = $row->NAME1;
                        $vendor->address = $row->NAME2;
                        $vendor->address_2 = $row->NAME3;
                        $vendor->city = $row->CITY1;
                        $vendor->pincode = $row->POST_CODE1;
                        $vendor->email = $row->SMTP_ADDR;
                        $vendor->mobile = $row->MOB_NUMBER;
                        $vendor->gst_no = $row->GSIN;
                        $vendor->pan_no = $row->PAN;
                        //$vendor->buyer_id = 8;
        
                        if($this->VendorTemps->save($vendor)) {
                            $response['message'][] = 'Vendor '.$row->LIFNR.' saved successfully!';
                            if(!$vendorExists || $createUser) {
                                $this->loadModel("Users");
                                $user = $this->Users->newEmptyEntity();
                                
                                $data = array();
                                $data['first_name'] = $vendor->name;
                                $data['last_name'] = $vendor->name;
                                $data['username'] = $vendor->email;
                                $data['mobile'] = $vendor->mobile;
                                $data['password'] = $vendor->mobile;
                                $data['group_id'] = 3;
        
                                $user = $this->Users->patchEntity($user, $data);
        
                                try{
                                    if ($this->Users->save($user)) {
                                        $response['message'][] = 'User '.$row->LIFNR.' created successfully!';
                                        $this->Ftp->removeFile($ftpConn, $fileKey);
                                    }
                                } catch (\Exception $e) {
                                    $response['message'][] = 'Vendor fail - '.$e->getMessage();
                                } 
                            } else {
                                $this->Ftp->removeFile($ftpConn, $fileKey);
                            }
                        } else {
                            $response['message'][] = 'Vendor '.$row->LIFNR.' save fail';
                        }
                    }
                }
            }
        }

        echo json_encode($response);
    }


    function getCreatedVendor($ftpConn, $list) {

        $response = array();
        $response['status'] = 0;
        $response['message'] = [];

        foreach($list as $fileKey => $val) {
            if(str_starts_with($fileKey, 'VENDOR_CR_')) {
                $data  = $this->Ftp->downloadFile($ftpConn, $fileKey);
                if($data) {
                    $this->loadModel("VendorTemps");
        
                    $data = trim(preg_replace('/\s+/', ' ', $data));
                    $d = json_decode($data);
        
                    foreach($d->VENDOR_LIST as $key => $row) {
                        $companyCode = $this->VendorTemps->CompanyCodes->findByCode($row->BUKRS)->first();
                        $puOrg = $this->VendorTemps->PurchasingOrganizations->findByCode($row->EKORG)->first();
                        $accGrp = $this->VendorTemps->AccountGroups->findByCode($row->KTOKK)->first();
                        $reconAccount = $this->VendorTemps->ReconciliationAccounts->findByCode($row->AKONT)->first();
                        $companyCode = $this->VendorTemps->CompanyCodes->findByCode($row->BUKRS)->first();
                        $schemaGroup = $this->VendorTemps->SchemaGroups->findByCode($row->KALSK)->first();
                        $region = $this->VendorTemps->States->findByRegionCode($row->REGION)->first();
                        $country = $this->VendorTemps->Countries->findByCountryCode($row->COUNTRY)->first();
                        $payTerm = $this->VendorTemps->PaymentTerms->findByCode($row->ZTERM)->first();
                        
                        
                        if($this->VendorTemps->exists(['id' => $row->VENDOR_PORTAL_ID])) {
                            $vendor = $this->VendorTemps->get($row->VENDOR_PORTAL_ID);
                            if(trim($row->SUCCESS) == "0") { 
                                $vendor->remark = $row->MESSAGE;
                                $this->VendorTemps->save($vendor);
                                continue; 
                            } else {
                            
                                $vendor->status = 5;
                                $vendor->sap_vendor_code = $row->LIFNR;
                                $vendor->company_code_id = $companyCode->id;
                                $vendor->purchasing_organization_id = $puOrg->id;
                                $vendor->account_group_id = $accGrp->id;
                                $vendor->reconciliation_account_id = $reconAccount->id;
                                $vendor->payment_term_id = $payTerm->id;
                                if($schemaGroup) {
                                    $vendor->schema_group_id = $schemaGroup->id;
                                }
                                if($region) {
                                    $vendor->state_id = $region->id;
                                }
                                if($country) {
                                    $vendor->country_id = $country->id;
                                }
                                $vendor->title = $row->TITLE_MEDI;
                                $vendor->name = $row->NAME1;
                                $vendor->address = $row->NAME2;
                                $vendor->address_2 = $row->NAME3;
                                $vendor->city = $row->CITY1;
                                $vendor->pincode = $row->POST_CODE1;
                                $vendor->email = $row->SMTP_ADDR;
                                $vendor->mobile = $row->MOB_NUMBER;
                                $vendor->gst_no = $row->GSIN;
                                $vendor->pan_no = $row->PAN;
                                
                                if($this->VendorTemps->save($vendor)) {
                                    $response['message'][] = 'Vendor '.$row->LIFNR.' saved successfully!';
                                    
                                    $this->loadModel("Users");
                                    $user = $this->Users->newEmptyEntity();
                                    
                                    $data = array();
                                    $data['first_name'] = $vendor->name;
                                    $data['last_name'] = $vendor->name;
                                    $data['username'] = $vendor->email;
                                    $data['mobile'] = $vendor->mobile;
                                    $data['password'] = $vendor->mobile;
                                    $data['group_id'] = 3;
            
                                    $user = $this->Users->patchEntity($user, $data);
            
                                    try {
                                        if ($this->Users->save($user)) {
                                            $response['message'][] = 'User '.$row->LIFNR.' created successfully';
                                            $this->Ftp->removeFile($ftpConn, $fileKey);
                                        }
                                    } catch (\Exception $e) {
                                        $response['message'][] = 'User '.$row->LIFNR.' fail - '.$e->getMessage();
                                    }
                                } else {
                                    $response['message'][] = 'Vendor 2 '.$row->LIFNR.' save fail';
                                }
                            }
                        }
                    }
                }
            }
        }

        echo json_encode($response);
    }

    function getBuyers() {
        set_time_limit(300);
        $response = array();
        try{
            $ftpConn = $this->Ftp->connection();
            $list = $this->Ftp->getList($ftpConn);
            $this->getRequestedBuyer($ftpConn, $list);
            $response['status'] = 1;
            $response['message'] = 'Buyer sync done';
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response); exit;

    }

    function getRequestedBuyer($ftpConn, $list) {

        $this->loadModel("Users");
        $this->loadModel("Buyers");
        $response = array();
        $response['status'] = 0;
        $response['message'] = [];
        
        foreach($list as $fileKey => $val) {
            if(str_starts_with($fileKey, 'BUYER_GET_')) {
                $data  = $this->Ftp->downloadFile($ftpConn, $fileKey);
                if($data) {
                    $data = trim(preg_replace('/\s+/', ' ', $data));
                    $d = json_decode($data);
        
                    foreach($d->BUYER_LIST as $key => $row) {
                        $companyCode = $this->Buyers->CompanyCodes->findByCode($row->BUKRS)->first();
                        $puOrg = $this->Buyers->PurchasingOrganizations->findByCode($row->EKORG)->first();
                        
                        if(trim($row->SUCCESS) == "0") { continue; }
                        $buyerExists = false;
                        if($this->Buyers->exists(['sap_user' => $row->UNAME])) {
                            $buyer = $this->Buyers->find()->where(['sap_user' => $row->UNAME])->first();
                            $buyerExists = true;
                        } else {
                            $buyer = $this->Buyers->newEmptyEntity();
                            $buyer->status = 1;
                            $buyerExists = false;
                        }
                        
                        $buyer->sap_user = $row->UNAME;
                        $buyer->company_code_id = $companyCode->id;
                        $buyer->purchasing_organization_id = $puOrg->id;
                        
                        $buyer->first_name = $row->FIRSTNAME;
                        $buyer->last_name = $row->LASTNAME;
                        $buyer->email = $row->E_MAIL;
                        $buyer->mobile = $row->TEL_NUMBER;
                        
                        if($this->Buyers->save($buyer)) {
                            $response['message'][] = 'Buyer '.$row->UNAME.' saved successfully!';
                            if(!$buyerExists) {
                                $this->loadModel("Users");
                                $user = $this->Users->newEmptyEntity();
                                
                                $data = array();
                                $data['first_name'] = $buyer->first_name;
                                $data['last_name'] = $buyer->last_name;
                                $data['username'] = $buyer->email;
                                $data['mobile'] = $buyer->mobile;
                                $data['password'] = $buyer->mobile;
                                $data['group_id'] = 2;
        
                                $user = $this->Users->patchEntity($user, $data);
                                try{
                                    if ($this->Users->save($user)) {
                                        $this->Ftp->removeFile($ftpConn, $fileKey);
                                    }
                                } catch (\Exception $e) {
                                    $response['message'][] = 'User creation failed - '.$e->getMessage();
                                }
                            } else {
                                $this->Ftp->removeFile($ftpConn, $fileKey);
                            }
                        } else {
                            $response['message'][] = 'Buyer '.$row->UNAME.' save fail';
                        }
                    }
                }
            }
        }

        echo json_encode($response);
    }

    public function asnUpdates()  {
        set_time_limit(300);
        $response = array();
        $response['message'] = [];
        
        $ftpConn = $this->Ftp->connection();
        $list = $this->Ftp->getList($ftpConn);

        $this->loadModel("AsnHeaders");
        $this->loadModel("AsnFooters");

        foreach($list as $fileKey => $val) {
            if(str_starts_with($fileKey, 'ASN_')) {
                $data  = $this->Ftp->downloadFile($ftpConn, $fileKey);
                
                if($data) {
                    $data = trim(preg_replace('/\s+/', ' ', $data));
                    $d = json_decode($data);

                    foreach($d as $key => $row) {
                        $hederData = array();
                        $footerData = array();

                        if($row->SUCCESS == "1") {
                            if($this->AsnHeaders->exists(['asn_no' => $row->ASN_NO])) {
                                $hederData['status'] = 3;
                                $poInstance = $this->AsnHeaders->find()->where(['asn_no' => $row->ASN_NO])->first();
                                $poInstance = $this->AsnHeaders->patchEntity($poInstance, $hederData);
                            
                                try {
                                    if ($this->AsnHeaders->save($poInstance)) {
                                        $po_header_id = $poInstance->id;
        
                                        /*if($this->AsnFooters->exists(['po_header_id' => $po_header_id])) {
                                            $poItemsInstance = $this->PoFooters->find()->where(['po_header_id' => $po_header_id, 'item' => $item->EBELP])->first();
                                            $poItemsInstance = $this->PoFooters->patchEntity($poItemsInstance, $hederData);
                                        }*/
                                        $response['message'][] = 'ASN-' . $row->ASN_NO . ' received successfully';
                                        $this->Ftp->removeFile($ftpConn, $fileKey);
                                    }
                                } catch (\PDOException $e) {
                                    $response['message'][] = $e->getMessage();
                                } catch (\Exception $e) {
                                    $response['message'][] = $e->getMessage();
                                }
                            } else {
                                $response['message'][] = 'ASN-' . $row->ASN_NO . ' not found';
                                $this->Ftp->removeFile($ftpConn, $fileKey);
                            }
                        } else {
                            $response['message'][] = 'ASN-' . $row->ASN_NO . ' fail - ';
                        }
                    }
                }
            }
        }
        echo json_encode($response);
    }
}