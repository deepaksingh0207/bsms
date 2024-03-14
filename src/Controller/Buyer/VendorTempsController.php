<?php

declare(strict_types=1);

namespace App\Controller\Buyer;

use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Http\Client;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Cake\Datasource\ConnectionManager;
use SebastianBergmann\Environment\Console;
use Cake\Core\Configure;

/**
 * VendorTemps Controller
 *
 * @property \App\Model\Table\VendorTempsTable $VendorTemps
 * @method \App\Model\Entity\VendorTemp[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VendorTempsController extends BuyerAppController
{
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];  
        $this->set('flash', $flash);
        $this->loadComponent('Ftp');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        
        $session = $this->getRequest()->getSession();
        $this->set('headTitle', 'Vendor List');
        $this->loadModel("VendorTemps");
        $this->loadModel("Users");
        $vendorTemps = $this->VendorTemps
        ->find('all')
        ->contain(['PurchasingOrganizations', 'AccountGroups', 'SchemaGroups'])
        ->where(['update_flag' => 0, 'VendorTemps.company_code_id' => $session->read('company_code_id'), 
        'VendorTemps.purchasing_organization_id' => $session->read('purchasing_organization_id')])
        ->order(['VendorTemps.added_date' => 'DESC'])
        ->toArray();
        $user_id = $this->getRequest()->getSession()->read('id');
        $usr = $this->Users->get($user_id);

        $fromPortalVenor = [];
        $fromSapVendor = [];
        foreach($vendorTemps as $vendor) {
            if($vendor->from_sap) {
                $fromSapVendor[] = $vendor;
            } else {
                $fromPortalVenor[] = $vendor;
            }
            
        }
        $this->set('user_id', $usr);
        $this->set(compact('fromPortalVenor', 'fromSapVendor'));

    }

    public function getList($term = null)
    {
        $this->autoRender = false;
        $list = array();
        $term = isset($_GET['term']) ? $_GET['term'] : null;

        $response['status'] = 0;
        $response['message'] = 'no records';
        if ($term != null) {
            $this->loadModel("VendorTemps");

            $vendors = $this->VendorTemps->find()
                ->select(['id', 'name', 'city', 'email', 'mobile'])
                ->where(["name like '%$term%'"])
                ->order(['name asc'])->all();

            foreach ($vendors as $vendor) {
                $tmp = array();
                $tmp['id'] = $vendor->id;
                $tmp['value'] = $vendor->name . ' (' . $vendor->city . ')';
                //$tmp['email'] = $vendor->email;
                //$tmp['mobile'] = $vendor->mobile;
                $list[] = $tmp;
            }
        }

        if (count($list)) {
            $response['status'] = 1;
            $response['message'] = 'success';
            $response['data'] = $list;
        }

        echo json_encode($list);
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Temp id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $flash = [];
        $this->set('headTitle', 'Vendor Details');
        $this->loadModel('VendorTemps');

        if ($this->VendorTemps->exists(['update_flag' => $id])) {
            $vendorTempView = $this->VendorTemps->find('all')->where(['update_flag' => $id])->toArray();
            $this->set('vendorTempView', $vendorTempView);
        }

        $vendorTemp = $this->VendorTemps->get($id, [
            'contain' => ['VendorStatus','CompanyCodes','PurchasingOrganizations','ReconciliationAccounts', 'AccountGroups', 'SchemaGroups', 'PaymentTerms', 'VendorFacilities', 'VendorIncometaxes', 'VendorOtherdetails', 'VendorQuestionnaires', 'VendorSmallScales', 'VendorTurnovers', 'States', 'Countries']]);
        
        $this->loadModel("VendorRegisteredOffices");
        $vendorRegisterOffice = $this->VendorRegisteredOffices->find()
        ->select($this->VendorRegisteredOffices)
        ->select(['States.name', 'Countries.country_name'])
        ->innerJoin(['Countries'=> 'countries'], ['Countries.country_code = VendorRegisteredOffices.country'])
        ->innerJoin(['States'=> 'states'], ['States.region_code = VendorRegisteredOffices.state','States.country_code = VendorRegisteredOffices.country'])
        ->where(['States.country_code = VendorRegisteredOffices.country', 'VendorRegisteredOffices.vendor_temp_id' => $id])->first();
        
        $this->loadModel("VendorPartnerAddress");
        $vendorPartnerAddress = $this->VendorPartnerAddress->find()
        ->select($this->VendorPartnerAddress)
        ->select(['States.name', 'Countries.country_name'])
        ->innerJoin(['Countries'=> 'countries'], ['Countries.country_code = VendorPartnerAddress.country'])
        ->innerJoin(['States'=> 'states'], ['States.region_code = VendorPartnerAddress.state','States.country_code = VendorPartnerAddress.country'])
        ->where([ 'VendorPartnerAddress.vendor_temp_id' => $id])->toArray();
        
        $this->loadModel("VendorFactories");
        $vendorFactories = $this->VendorFactories->find()
        ->select($this->VendorFactories)
        ->select(['States.name', 'Countries.country_name'])
        ->contain(['VendorCommencements'])
        ->innerJoin(['Countries'=> 'countries'], ['Countries.country_code = VendorFactories.country'])
        ->innerJoin(['States'=> 'states'], ['States.region_code = VendorFactories.state', 'States.country_code = VendorFactories.country'])     
        ->where([  'VendorFactories.vendor_temp_id' => $id])->toArray();
        
        
        $this->loadModel("VendorReputedCustomers");
        $vendorReputedCustomers = $this->VendorReputedCustomers->find()
        ->select($this->VendorReputedCustomers)
        ->select(['States.name', 'Countries.country_name'])
        ->innerJoin(['Countries'=> 'countries'], ['Countries.country_code = VendorReputedCustomers.country'])
        ->innerJoin(['States'=> 'states'], ['States.region_code = VendorReputedCustomers.state', 'States.country_code = VendorReputedCustomers.country'])
        ->where(['VendorReputedCustomers.vendor_temp_id' => $id])->toArray();
        
        
        $this->loadModel("VendorBranchOffices");
        $vendorBranchOffices = $this->VendorBranchOffices->find()
        ->select($this->VendorBranchOffices)
        ->select(['States.name', 'Countries.country_name'])
        ->innerJoin(['Countries'=> 'countries'], ['Countries.country_code = VendorBranchOffices.country'])
        ->innerJoin(['States'=> 'states'], ['States.region_code = VendorBranchOffices.state', 'States.country_code = VendorBranchOffices.country'])
        ->where(['VendorBranchOffices.vendor_temp_id' => $id])->toArray();
        // echo '<pre>'; print_r($vendorRegisterOffice);
        // print_r($vendorPartnerAddress);
        // print_r($vendorFactories);
        // print_r($vendorReputedCustomers);
        // print_r($vendorBranchOffices);
        // print_r($vendorTemp ); exit;

        $this->set(compact('vendorTemp', 'vendorPartnerAddress', 'vendorRegisterOffice', 'vendorReputedCustomers', 'vendorFactories', 'vendorBranchOffices'));
    }

    public function update($id = null)
    {
        $flash = [];
        $this->loadModel("VendorTemps");
        $oldrecord = $this->VendorTemps->get($id);
        $newrecord = $this->VendorTemps->find('all')->where(['update_flag' => $id])->first();
        if ($this->request->is('post')) {
            $status = $this->request->getData('status');
            if ($status == 1){
                $newrecord->update_flag = '0';
                $oldrecord->update_flag = '-1';
                if ($this->VendorTemps->save($newrecord)) {
                    if ($this->VendorTemps->save($oldrecord)) {
                        $flash = ['type'=>'success', 'msg'=>'Vendor Details Updated'];
                    }
                        $flash = ['type'=>'error', 'msg'=>'Failed'];
                    }
                }
                else {
                    $newrecord->update_flag = '-2';
                    if ($this->VendorTemps->save($newrecord)) {
                        $flash = ['type'=>'success', 'msg'=>'Vendor Details Rejected'];
                    // } else { $this->Flash->error(__('Failed'));
                        $flash = ['type'=>'error', 'msg'=>'Failed']; }
                    }
                $this->set('flash', $flash);
        }
        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */

    public function sapView($id = null)
    {
        $this->loadModel("VendorTemps");
        $vendorTemp = $this->VendorTemps->get($id, [
            'contain' => ['PurchasingOrganizations', 'AccountGroups', 'SchemaGroups'],
        ]);
        $this->set('headTitle', 'Vendor Details');


        $this->set(compact('vendorTemp'));
    }
    
    public function masterByCompanyCode($id = null)
    {
        $this->autoRender = false;
        $response = ["status"=>0, 'message' =>'Empty request'];
        $this->loadModel("CompanyCodes");
        $this->loadModel("PurchasingOrganizations");
        $this->loadModel("ReconciliationAccounts");
        //$this->loadModel("AccountGroups");
        //$this->loadModel("PaymentTerms");
        //$this->loadModel("SchemaGroups");
        
        $po = $this->PurchasingOrganizations->find()->select(['id', 'name' => 'CONCAT(code, " - ", name)'])->where(['company_code_id =' => $id])->toArray();
        $ra = $this->ReconciliationAccounts->find()->select(['id', 'name' => 'CONCAT(code, " - ", name)'])->where(['company_code_id =' => $id])->toArray();
        //$ag = $this->AccountGroups->find()->select(['id', 'name'])->where(['company_code_id =' => $id])->toArray();
        
        //$pt = $this->PaymentTerms->find()->select(['id', 'description'])->where(['company_code_id =' => $id])->toArray();
        //$sg = $this->SchemaGroups->find()->select(['id', 'name'])->where(['company_code_id =' => $id])->toArray();
        $response = ["status"=>1, 'message' =>['PurchasingOrganizations'=>$po, 'ReconciliationAccounts' => $ra]];
        echo json_encode($response);
    }

    public function add()
    {
        $flash = [];
        $session = $this->getRequest()->getSession();

        $this->set('headTitle', 'Create Vendor');
        $this->loadModel("Titles");
        $this->loadModel("VendorTypes");
        $this->loadModel("VendorTemps");
        $this->loadModel("VendorStatus");
        $this->loadModel("PaymentTerms");
        $this->loadModel("CompanyCodes");
        $this->loadModel('AccountGroups');
        $this->loadModel('SchemaGroups');
        $this->loadModel("ReconciliationAccounts");
        $this->loadModel("PurchasingOrganizations");

        $latestVendors = $this->VendorTemps
        ->find('all')
        ->contain(['PurchasingOrganizations', 'AccountGroups', 'SchemaGroups'])
        ->where(['VendorTemps.company_code_id' => $session->read('company_code_id'), 'VendorTemps.purchasing_organization_id' => $session->read('purchasing_organization_id')])
        ->order(['VendorTemps.added_date' => 'DESC'])
        ->limit(5);

        //echo '<pre>'; print_r($latestVendors); exit;

        $vendorTemp = $this->VendorTemps->newEmptyEntity();
        $vendorCodes = [];
        
        $titles = $this->Titles->find('list', ['keyField' => 'name', 'valueField' => 'name'])->all();
        $vendorTypes = $this->VendorTemps->VendorTypes->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->name;
        }])->all();
        
        $accountGroups = $this->VendorTemps->AccountGroups->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->name;
        }])->all();
        $schemaGroups = $this->VendorTemps->SchemaGroups->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->name;
        }])->all();
        $payment_term = $this->PaymentTerms->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->description;
        }])->all();
        
        $reconciliation_account = $this->ReconciliationAccounts->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->name;
        }])->where(['company_code_id' => $session->read('company_code_id')])->all();

        $vendorTemp->account_group_id = 10;
        $this->set(compact('vendorTemp','titles', 'accountGroups', 'schemaGroups', 'payment_term', 'reconciliation_account', 'latestVendors', 'vendorTypes'));
    }

    public function sapAdd()
    {
        $flash = [];
        $this->set('headTitle', 'Import SAP Vendor');
        $this->loadModel("VendorTemps");

        if ($this->request->is('post')) {

            $inputCode = trim($this->request->getData('sap_vendor_code'));
            $importFile = $this->request->getData('vendor_code');
            
            $vendorView = [];
            $vendorCodes = [$inputCode];
            // Incase of Excel input
            if ($inputCode == "") { $vendorCodes = []; }

            foreach ($vendorCodes as $vendorCode) {
                if (!empty($vendorCode)) {
                    if (!$this->VendorTemps->exists(['sap_vendor_code' => str_pad($vendorCode, 10, "0", STR_PAD_LEFT)])) {
                        $data = [];
                        $data['LIFNR'] = $vendorCode;
                        $uploadFileContent = json_encode($data);
                        $uploadfileName = 'VENDOR_GET_('.$vendorCode.')_REQ.JSON';
                        $downloadfileName = 'VENDOR_GET_('.$vendorCode.')_RES.JSON';
                        $ftpConn = $this->Ftp->connection();
                        if($this->Ftp->uploadFile($ftpConn, $uploadFileContent, $uploadfileName)) {
                            $this->loadModel('VendorCodeFiles');
                            $vendorCodeFile = $this->VendorCodeFiles->newEmptyEntity();
                            $tm['sap_vendor_code'] = str_pad($vendorCode, 10, "0", STR_PAD_LEFT);
                            $tm['req_file_name'] = $uploadfileName;
                            $tm['res_file_name'] = $downloadfileName;
                            $tm['status'] = 'request sent';
                            $vendorCodeFile = $this->VendorCodeFiles->patchEntity($vendorCodeFile, $tm);
                            $this->VendorCodeFiles->save($vendorCodeFile);
                            $flash = ['type'=>'success', 'msg'=>' Request sent to SAP, please check after sometime.'];
                        } else {
                            $flash = ['type'=>'error', 'msg'=>' Something went wrong, please retry.'];
                        }
                    } else {
                        array_push($vendorView, ['status' => false, 'msg' => "Vendor for SAP code Exist", 'data' => ['sap_vendor_code' => $vendorCode]]);
                    }
                } else {
                    array_push($vendorView, ['status' => false, 'msg' => "Invalid SAP Vendor Code", 'data' => ['sap_vendor_code' => $vendorCode]]);
                }
            }
            // print_r($vendorView); exit;
            $this->set('vendorData', $vendorView);
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Temp id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $flash = [];
        $session = $this->getRequest()->getSession();

        $this->loadModel("Titles");
        $this->loadModel("Buyers");
        $this->loadModel('Users');
        $this->loadModel("VendorTypes");
        $this->loadModel("VendorTemps");
        $this->loadModel("VendorStatus");
        $this->loadModel("PaymentTerms");
        $this->loadModel("CompanyCodes");
        $this->loadModel('AccountGroups');
        $this->loadModel('SchemaGroups');
        $this->loadModel("ReconciliationAccounts");
        $this->loadModel("PurchasingOrganizations");

        $vendorTemp = $this->VendorTemps->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vendorTemp = $this->VendorTemps->patchEntity($vendorTemp, $this->request->getData());

            //echo '<pre>'; print_r($vendorTemp); exit;
            if ($this->VendorTemps->save($vendorTemp)) {
                $flash = ['type'=>'success', 'msg'=>'The vendor has been saved'];
                $this->set('flash', $flash);
                $this->Flash->success(__('The vendor has been saved'));
                $quryString = $vendorTemp->email . '||' . $vendorTemp->id;

                $buyerList = $this->Buyers->find()->select('email')->where(['company_code_id' => $vendorTemp->company_code_id, 'purchasing_organization_id' => $vendorTemp->purchasing_organization_id])->toArray();
                $buyersEmails = [];
                foreach($buyerList as $email) { $buyersEmails[] = $email->email; }

                $visit_url = Router::url(['prefix'=>false, 'controller' => 'vendor/onboarding', 'action' => 'verify', base64_encode($quryString), '_full' => true, 'escape' => true]);
                $mailer = new Mailer('default');
                $mailer
                    ->setTransport('smtp')
                    ->setViewVars([
                        'vendor_name' => $vendorTemp,
                        'spt_email' => 'support@apar.in',
                        'visit_url'=>$visit_url,
                        'spt_contact' => '7718801906',
                        ])
                    ->setFrom(Configure::read('MAIL_FROM'))
                    //->setTo($buyersEmails)
                    ->setTo($vendorTemp->email)

                    ->setEmailFormat('html')
                    ->setSubject('NEW VENDOR ONBOARDING UPDATED COMMUNICATION')
                    ->viewBuilder()
                        ->setTemplate('edit_vendor');
                $mailer->deliver();

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The vendor temp could not be saved. Please, try again'];
            $this->set('flash', $flash);
            $this->Flash->error(__('The vendor temp could not be saved. Please, try again'));
        }

        $titles = $this->Titles->find('list', ['keyField' => 'name', 'valueField' => 'name'])->all();
        $vendorTypes = $this->VendorTemps->VendorTypes->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->name;
        }])->all();
        
        $accountGroups = $this->VendorTemps->AccountGroups->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->name;
        }])->all();
        $schemaGroups = $this->VendorTemps->SchemaGroups->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->name;
        }])->all();
        $payment_term = $this->PaymentTerms->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->description;
        }])->all();
        
        $reconciliation_account = $this->ReconciliationAccounts->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->name;
        }])->where(['company_code_id' => $session->read('company_code_id')])->all();

        $vendorTemp->account_group_id = 10;
        $this->set(compact('vendorTemp','titles', 'accountGroups', 'schemaGroups', 'payment_term', 'reconciliation_account', 'vendorTypes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Temp id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $flash = [];
        $this->request->allowMethod(['post', 'delete']);
        $vendorTemp = $this->VendorTemps->get($id);
        if ($this->VendorTemps->delete($vendorTemp)) {
            $flash = ['type'=>'success', 'msg'=>'The vendor temp has been deleted'];
        } else {
            $flash = ['type'=>'error', 'msg'=>'The vendor temp could not be deleted. Please, try again'];
        }
        $this->set('flash', $flash);
        
        return $this->redirect(['action' => 'index']);
    }

    public function approveVendor($id = null, $action = null)
    {
        $session = $this->getRequest()->getSession();
        $flash = [];
        $this->loadModel('Users');
        $this->loadModel("VendorTemps");
        $vendor = $this->VendorTemps->get($id, ['contain' => ['CompanyCodes','SchemaGroups','PurchasingOrganizations','AccountGroups', 'ReconciliationAccounts', 'States', 'Countries', 'PaymentTerms']]);

        if ($action == 'rej') {
            if ($this->request->is(['patch', 'post', 'put'])) {
                $remarks = $this->request->getData('remarks');
                $vendor->status = 4;
                $vendor->remark = $remarks;
                $this->VendorTemps->save($vendor);
                $quryString = $vendor->email . '||' . $vendor->id;
               
                $visit_url = Router::url(['prefix'=>false, 'controller' => 'vendor/onboarding', 'action' => 'verify', base64_encode($quryString), '_full' => true, 'escape' => true]);
                $mailer = new Mailer('default');
                $mailer
                    ->setTransport('smtp')
                    ->setViewVars([ 'vendor' => $vendor, 'remarks' => $remarks, 'spt_email' => 'support@apar.in', ])
                    ->setFrom(Configure::read('MAIL_FROM'))
                    ->setTo($vendor->email)
                    ->setEmailFormat('html')
                    ->setSubject('VENDOR PORTAL - VENDOR KYC PROCESS')
                    ->viewBuilder()
                        ->setTemplate('rejected_onboarding');
                $mailer->deliver();

                $flash = ['type'=>'success', 'msg'=>'The Vendor successfully rejected'];
            } else {
                $flash = ['type'=>'success', 'msg'=>'Issue in vendor rejection'];
            }
            $this->set('flash', $flash);

            return $this->redirect(['action' => 'view', $id]);
        }

        $data['DATA'] = array();

        $data['DATA']['VENDOR_PORTAL_ID'] = $vendor->id;
        $data['DATA']['LIFNR'] = $vendor->sap_vendor_code;
        $data['DATA']['BUKRS'] = $vendor->company_code->code;
        $data['DATA']['EKORG'] = $vendor->purchasing_organization->code;
        $data['DATA']['KTOKK'] = $vendor->account_group->code;
        $data['DATA']['TITLE_MEDI'] = $vendor->title;
        $data['DATA']['NAME1'] = $vendor->name;
        $data['DATA']['NAME2'] = $vendor->address;
        $data['DATA']['NAME3'] = $vendor->address_2;
        $data['DATA']['NAME4'] = $vendor->city;

        $data['DATA']['SORT1'] = $vendor->name;
        $data['DATA']['STREET'] = $vendor->address;
        $data['DATA']['CITY1'] = $vendor->city;
        $data['DATA']['POST_CODE1'] = $vendor->pincode;

        $data['DATA']['REGION'] = $vendor->state->region_code;
        $data['DATA']['COUNTRY'] = $vendor->country->country_code;
        $data['DATA']['SMTP_ADDR'] = $vendor->email;
        $data['DATA']['MOB_NUMBER'] = $vendor->mobile;

        $data['DATA']['AKONT'] = $vendor->reconciliation_account->code;
        $data['DATA']['ZUAWA'] = '';
        $data['DATA']['SPRAS'] = '';
        $data['DATA']['TAXTYPE'] = '';
        $data['DATA']['KALSK'] = $vendor->schema_group->code;
        $data['DATA']['GSIN'] = $vendor->gst_no;
        $data['DATA']['PAN'] = $vendor->pan_no;
        $data['DATA']['ZTERM'] = $vendor->payment_term->code;
        $data['DATA']['WAERS'] = $vendor->order_currency;
        $data['DATA']['BUYER_ID'] = $session->read('id');


        try {
            $uploadFileContent = json_encode($data);
            $uploadfileName = 'VENDOR_CR_('.$vendor->id.')_REQ.JSON';
            $downloadfileName = 'VENDOR_CR_('.$vendor->id.')_RES.JSON';
        
            $ftpConn = $this->Ftp->connection();
            if($this->Ftp->uploadFile($ftpConn, $uploadFileContent, $uploadfileName)) {
                if ($action == 'app') { $vendor->status = 2; }
                $this->VendorTemps->save($vendor);
                $this->loadModel('VendorCodeFiles');
                $vendorCodeFile = $this->VendorCodeFiles->newEmptyEntity();
                $tm['vendor_temp_id'] = $vendor->id;
                $tm['req_file_name'] = $uploadfileName;
                $tm['res_file_name'] = $downloadfileName;
                $tm['status'] = 'request sent';
                $vendorCodeFile = $this->VendorCodeFiles->patchEntity($vendorCodeFile, $tm);
                $this->VendorCodeFiles->save($vendorCodeFile);
                $flash = ['type'=>'success', 'msg'=>' Vendor sent to SAP for approval'];
            } else {
                $flash = ['type'=>'error', 'msg'=>' Vendor sent to SAP fail'];
            }
        } catch (\Exception $e) {
            $flash = ['type'=>'error', 'msg'=> $e->getMessage()];
        }

        /*
        $http = new Client();
        $response = $http->post(
            'http://123.108.46.252:8000/sap/bc/sftmob/VENDER_UPD/?sap-client=300',
            json_encode($data),
            ['type' => 'json', 'auth' => ['username' => 'vcsupport1', 'password' => 'aarti@123']]
        );

        
        if ($response->isOk()) {
            $result = json_decode($response->getStringBody());

            if ($result->RESPONSE->SUCCESS) {

                $resultResponse = json_decode($result->RESPONSE->DATA);
                $newVendorCode = trim($resultResponse->DATA->LIFNR);

                if (!empty($newVendorCode)) {
                    $this->loadModel("Users");
                    $adminUser = $this->Users->newEmptyEntity();
                    // echo '<pre>';
                    // print_r($adminUser);

                    $data = array();
                    $data['first_name'] = $vendor->name;
                    $data['last_name'] = $vendor->name;
                    $data['username'] = $vendor->email;
                    $data['mobile'] = $vendor->mobile;
                    $data['password'] = $vendor->mobile;
                    $data['group_id'] = 3;

                    $adminUser = $this->Users->patchEntity($adminUser, $data);

                    if ($this->Users->save($adminUser)) {
                        
                        $visit_url = Router::url(['prefix' => false, 'controller' => 'users', 'action' => 'login', '_full' => true, 'escape' => true]);
                        $mailer = new Mailer('default');
                        $mailer
                            ->setTransport('smtp')
                            ->setViewVars([ 'subject' => 'Hi ' . $data['first_name'], 'mailbody' => 'Welcome to Vendor portal. <br/> <br/> Username: ' . $data['username'] .
                            '<br/>Password:' . $data['password'], 'link' => $visit_url, 'linktext' => 'Click Here' ])
                            ->setFrom(Configure::read('MAIL_FROM'))
                            ->setTo($data['username'])
                            ->setEmailFormat('html')
                            ->setSubject('Vendor Portal - Account created')
                            ->viewBuilder()
                            ->setTemplate('mail_template');
                        $mailer->deliver();
                    }
                }

                $vendor->status = 3; //Approved by SAP
                $vendor->sap_vendor_code = $newVendorCode;
                $this->VendorTemps->save($vendor);



                $this->redirect(['action' => 'index',]);
                $flash = ['type'=>'success', 'msg'=>__('The Vendor successfully approved', array('action' => 'index'), 30)];
            }
        } else {
            $flash = ['type'=>'success', 'msg'=>' Vendor sent to SAP for approval'];
        } */
        $this->set('flash', $flash);

        return $this->redirect(['action' => 'view', $id]);
    }

    public function addvendor()
    {
        $response = array();
        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;
        $this->loadModel("VendorTemps");
        $this->loadModel('Users');
        $this->loadModel("Notifications");

        $session = $this->getRequest()->getSession();

        //echo '<pre>'; print_r($this->request->getData()); exit;
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $VendorTemp = $this->VendorTemps->newEmptyEntity();
                $data = $this->request->getData();
                $data['company_code_id'] = $session->read('company_code_id');
                $data['purchasing_organization_id'] = $session->read('purchasing_organization_id');
                //$data['buyer_id'] = $session->read('id');
                $data['valid_date'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 day'));

                $VendorTemp = $this->VendorTemps->patchEntity($VendorTemp, $data);               
                $result = $this->VendorTemps->find('all')
                ->select(['title','name', 'mobile', 'email','purchasing_organization_id', 'status'])
                ->where([
                    'OR' => [
                        ['name' => trim($data['name'])],
                        ['mobile' => $data['mobile']],
                        ['email' => $data['email']],
                    ]
                    ])->toArray();

                $response['status'] = 'fail';
                if(count($result)) {
                    $response['message'] = 'Vendor already exists';
                    //$response['data'] = $result;
                }else if ($this->VendorTemps->exists(['VendorTemps.mobile' => $data['mobile']])) {
                    $response['message'] = 'Mobile Number Exist';
                } else if ($this->VendorTemps->exists(['VendorTemps.email' => $data['email']])) {
                    $response['message'] = 'Email ID Exist';
                } else if ($this->VendorTemps->save($VendorTemp)) {
                    $response['status'] = 'success';
                    $response['message'] = 'Record save successfully';
                    $quryString = $data['email'] . '||' . $VendorTemp->id;

                    $visit_url = Router::url(['prefix'=>false, 'controller' => 'vendor/onboarding', 'action' => 'verify', base64_encode($quryString), '_full' => true, 'escape' => true]);
                    $mailer = new Mailer('default');
                    $mailer
                        ->setTransport('smtp')
                        ->setViewVars([ 'vendor_name' => $data[ 'name' ], 'link' => $visit_url, 'vendor_email' => $data[ 'email' ],
                        'spt_email' => 'support@apar.in' , 'spt_contact' => '7718801906' ]) 
                        ->setFrom(Configure::read('MAIL_FROM'))
                        ->setTo($data['email'])
                        ->setEmailFormat('html')
                        ->setSubject('NEW VENDOR REGISTRATION FIRST COMMUNICATION')
                        ->viewBuilder()
                            ->setTemplate('new_communication');
                    $mailer->deliver();
                }
                //echo '<pre>'; print_r($VendorTemp); exit;
            } catch (\PDOException $e) {
                $response['status'] = 'fail';
                $response['message'] = $e->getMessage();
            } catch (\Exception $e) {
                $response['status'] = 'fail';
                $response['message'] = $e->getMessage();
            }
        }

        echo json_encode($response);
    }

    public function sapEdit($id = null)
    {

        $response = array();
        $response['status'] = '0';
        $response['message'] = '';
        $this->autoRender = false;


        $this->loadModel("VendorTemps");
        $vendorTemp = $this->VendorTemps->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            try {
                // $vendorTemp = $this->VendorTemps->newEmptyEntity();

                $data = $this->request->getData();


                $query = $this->VendorTemps->find('all')
                    ->where(['VendorTemps.id' => $id])
                    ->first();

                if ($query) {
                    if ($query['email'] != $data['email']) {
                        $emailCount = $this->VendorTemps->find()
                            ->where(['VendorTemps.email' => $data["email"]])
                            ->count();

                        if ($emailCount > 0) {
                            throw new \Exception('Already Exits Email ID');
                        }
                    } else if ($query['mobile'] != $data['mobile']) {
                        $mobileCount = $this->VendorTemps->find()
                            ->where(['VendorTemps.mobile' => $data["mobile"]])
                            ->count();

                        if ($mobileCount > 0) {
                            throw new \Exception('Already Exits Mobile No.');
                        }
                    }

                    $vendorTemp = $this->VendorTemps->patchEntity($query, $data);

                    if ($this->VendorTemps->save($vendorTemp)) {
                        $response['status'] = '1';
                        $response['message'] = 'Update Successfully';
                    } else {
                        throw new \Exception('Failed to Add User');
                    }
                } else {
                    throw new \Exception('Invalid ID');
                }
            } catch (\Exception $e) {
                $response['status'] = '0';
                $response['message'] = $e->getMessage();
            }
        }


        echo json_encode($response);
    }

    public function userCredentials($id = null)
    {
        $response = array();
        $response['status'] = 0;
        $response['message'] = '';
        $this->autoRender = false;

        $this->loadModel("Users");
        $this->loadModel("VendorTemps");


        $vendorTemp = $this->VendorTemps->get($id);

        if ($this->request->is(['patch', 'get', 'put'])) {

            $query = $this->Users->find()
                ->select(['first_name', 'mobile', 'username'])
                ->where(['username' => $vendorTemp->email])
                ->toList();

            if (count($query)) {
                foreach ($query as $val) {
                    $vendorTemp->status = 3;
                    if($this->VendorTemps->save($vendorTemp)) {
                        $visit_url = Router::url(['prefix' => false, 'controller' => 'users', 'action' => 'login', '_full' => true, 'escape' => true]);
                        $mailer = new Mailer('default');
                        $mailer
                            ->setTransport('smtp')
                            ->setViewVars([ 'vendor_name' => $val->first_name, 'username' => $val->username, 'password' => $val->mobile, 'link' => $visit_url ]) 

                            ->setFrom(Configure::read('MAIL_FROM'))
                            ->setTo($val->username)
                            ->setEmailFormat('html')
                            ->setSubject('NEW VENDOR ONBOARDING')
                            ->viewBuilder()
                                ->setTemplate('onboarding');
                        $mailer->deliver();
                        $response['status'] = 1;
                        $response['message'] = 'Credentials Mail Send successfully';
                    } else {
                        $response['status'] = 0;
                        $response['message'] = 'Issue in sending credentials details';
                    }
                }
            } else {
                $response['status'] = 0;
                $response['message'] = 'Credentials Not Send.';
            }
        }


        
        echo json_encode($response);
    }

    public function bulkupdate($status_id = null, $user_arr = array()){
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $data = $this->request->getData();
                echo '<pre>'; print_r($data);exit;
            } catch (\Exception $e) {
                $response['status'] = '0';
                $response['message'] = $e->getMessage();
            }
        }
    }
}

function importVendor($vendorCode)
{
    // $result = false;
    if (!empty($vendorCode) == "") {
        $data['DATA'] = array();
        $data['DATA']['LIFNR'] = $vendorCode;

        $http = new Client();
        $response = $http->post(
            'http://123.108.46.252:8000/sap/bc/sftmob/VENDER_UPD/?sap-client=300',
            json_encode($data),
            ['type' => 'json', 'auth' => ['username' => 'vcsupport1', 'password' => 'aarti@123']]
        );

        if ($response->isOk()) {
            $result = json_decode($response->getStringBody());
            if ($result->RESPONSE->SUCCESS) {
                $result = $resultResponse->DATA;
            }
        }
    }
    return $result;
}