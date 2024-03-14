<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;

/**
 * Home Controller
 *
 * @method \App\Model\Entity\Home[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ApiController extends ApiAppController
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
    }

    public function stateByCountryId($id = null)
    {
        $this->autoRender = false;
        $this->loadModel("Countries");
        $this->loadModel("States");
        $states = $this->States->find('all')->innerJoin(['Countries'=>'countries'],['Countries.country_code = States.country_code'])->where(['Countries.id' => $id])->toArray();
        $response = ["status"=> 1, 'message' =>$states];
        echo json_encode($response); exit;
    }

    public function stateByCountryCode($country_code = null)
    {
        $this->autoRender = false;
        $this->loadModel("Countries");
        $this->loadModel("States");
        $states = $this->States->find('all')->innerJoin(['Countries'=>'countries'],['Countries.country_code = States.country_code'])->where(['Countries.country_code' => $country_code])->toArray();
        $response = ["status"=> 1, 'message' =>$states];
        echo json_encode($response); exit;
    }

    public function postPo()
    {
        $response = array();
        $response['status'] = 0;
        $response['message'] = 'Empty request';
        $request = $this->request->getData();

        $this->loadModel("PoHeaders");
        $this->loadModel("PoFooters");
        $this->loadModel('Users');
        $this->loadModel("VendorTemps");

        if (!empty($request) && count($request['DATA'])) {

            try {
                foreach ($request['DATA'] as $key => $row) {
                    $hederData = array();
                    $footerData = array();

                    $hederData['sap_vendor_code'] = $row['LIFNR'];
                    $hederData['po_no'] = $row['EBELN'];
                    $hederData['document_type'] = $row['BSART'];
                    $hederData['created_on'] = date("Y-m-d H:i:s", strtotime($row['AEDAT']));
                    $hederData['created_by'] = $row['ERNAM'];
                    $hederData['pay_terms'] = $row['ZTERM'];
                    $hederData['currency'] = $row['WAERS'];
                    $hederData['exchange_rate'] = $row['WKURS'];
                    $hederData['release_status'] = $row['FRGZU'];

                    $poInstance = $this->PoHeaders->newEmptyEntity();
                    $poInstance = $this->PoHeaders->patchEntity($poInstance, $hederData);

                    if ($the_po = $this->PoHeaders->save($poInstance)) {
                        $po_header_id = $poInstance->id;

                        foreach ($row['ITEMS'] as $no => $item) {
                            $tmp = array();
                            $tmp['po_header_id'] = $po_header_id;
                            $tmp['item'] = $item['EBELP'];
                            $tmp['deleted_indication'] = $item['LOEKZ'];
                            $tmp['material'] = $item['MATNR'];
                            $tmp['short_text'] = $item['TXZ01'];
                            $tmp['po_qty'] = $item['MENGE'];
                            $tmp['grn_qty'] = $item['R_QTY'];
                            $tmp['pending_qty'] = $item['P_QTY'];
                            $tmp['order_unit'] = $item['MEINS'];
                            $tmp['net_price'] = $item['NETPR'];
                            $tmp['price_unit'] = $item['PEINH'];
                            $tmp['net_value'] = $item['NETWR'];
                            $tmp['gross_value'] = $item['BRTWR'];

                            $footerData[] = $tmp;
                        }

                        $poItemsInstance = $this->PoFooters->newEntities($footerData);
                        if ($this->PoFooters->saveMany($poItemsInstance)) {
                            $response['status'] = 1;
                            $response['message'] = 'PO saved successfully';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'PO Items save fail';
                        }
                    } else {
                        $response['status'] = 0;
                        $response['message'] = 'PO header save fail';
                    }

                    // Mail Me
                    $vendorTemps = $this->VendorTemps->find('all')->where(['sap_vendor_code' => $the_po->sap_vendor_code ])->toArray();
                    $po_ftr = $this->PoFooters->find('all')->where(['PoFooters' => $the_po->id ])->toArray();

                    $conn = ConnectionManager::get('default');
                    $query = $conn->execute("select buyers.email from buyers
                    left join po_headers on po_headers.created_user = buyers.sap_user
                    where po_headers.id=".$the_po->id);
                    $response = $query->fetchAll('assoc');
                    $mailer = new Mailer('default');
                    $mailer
                        ->setTransport('smtp')
                        ->setViewVars([
                            'vendor_name' => $vendorTemps->name,
                            'vendor_email' => $vendorTemps->email,
                            'po_header' => $the_po,
                            'po_footer' => $po_ftr,
                            'spt_email' => $response[0]['email'],
                            'spt_contact' => '7718801906',
                            'ttlamt' => 900,
                            ]) 
                        ->setFrom(Configure::read('MAIL_FROM'))
                        ->setTo($data['email'])
                        ->setEmailFormat('html')
                        ->setSubject('PURCHASE ORDER DETAILS')
                        ->viewBuilder()
                        ->setTemplate('purchase_order');
                    $mailer->deliver();

                }
            } catch (\PDOException $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            } catch (\Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }
        }

        echo json_encode($response); exit;
    }

    public function notification()
    {
        $response = array();
        $response['status'] = 0;
        $response['message'] = '';
        $session = $this->getRequest()->getSession();


        $userId =  $session->read('id');

        
        $this->loadModel('Notifications');

        $conn = ConnectionManager::get('default');
        $notificationsQuery = $conn->execute("SELECT * FROM notifications WHERE message_count > 0 and user_id = $userId");

        $notifications = $notificationsQuery->fetchAll('assoc');

        $response['status'] = '1';
        $response['message'] = 'success';
        $response['notifications'] = $notifications;


        echo json_encode($response); exit;
    }

    public function countries()
    {
        $this->autoRender = false;
        $this->loadModel("Countries");
        $countries = $this->Countries->find('all')->toArray();
        $response = array('status'=>1, 'message'=>$countries);
        echo json_encode($response); exit;
    }

    public function getCountryCodeById($country_code=null)
    {
        $this->autoRender = false;
        $this->loadModel("Countries");
        $countries = $this->Countries->find('all')->where(['id' => $country_code])->toArray()[0];
        $response = array('status'=>1, 'message'=>$countries);
        echo json_encode($countries->country_code); exit;
    }

    public function getStateRegioncodeById($region_code=null)
    {
        $this->autoRender = false;
        $this->loadModel("States");
        $states = $this->States->find('all')->where(['id' => $region_code])->toArray()[0];
        $response = array('status'=>1, 'message'=>$states);
        echo json_encode($states->region_code); exit;
    }

    public function vendor($id = null)
    {
        $this->autoRender = false;
        $this->loadModel("VendorBranchOffices");
        $this->loadModel("VendorCommencements");
        $this->loadModel("VendorFacilities");
        $this->loadModel("VendorFactories");
        $this->loadModel("VendorIncometaxes");
        $this->loadModel("VendorOtherdetails");
        $this->loadModel("VendorPartnerAddress");
        $this->loadModel("VendorQuestionnaires");
        $this->loadModel("VendorRegisteredOffices");
        $this->loadModel("VendorReputedCustomers");
        $this->loadModel("VendorSmallScales");
        $this->loadModel("VendorTemps");
        $this->loadModel("VendorTurnovers");
        $this->loadModel("CompanyCodes");
        $this->loadModel("Countries");
        $this->loadModel("States");

        $conn = ConnectionManager::get('default');
        $vendortemp = $conn->execute("select vt.*, cc.name as company_code_name, pz.name as purchasing_organization_name, ag.name as account_group_name, sg.name as schema_group_name, ra.name as reconciliation_account_name, concat(pt.code, ' ', pt.description) as payment_term_name,  vs.description as status_name, ct.country_name as country_name, st.name as state_name
        from vendor_temps vt 
        left join company_codes cc on cc.id=vt.company_code_id
        left join purchasing_organizations pz on pz.id=vt.purchasing_organization_id
        left join account_groups ag on ag.id=vt.account_group_id
        left join schema_groups sg on sg.id=vt.schema_group_id
        left join reconciliation_accounts ra on ra.id=vt.reconciliation_account_id
        left join payment_terms pt on pt.id=vt.payment_term_id
        left join states st on st.id=vt.state_id
        left join countries ct on ct.id=vt.country_id
        left join vendor_status vs on vs.id=vt.status where vt.id =".$id." limit 1");
        $vendortemp = ['vendor_temps'=>$vendortemp->fetchAll('assoc')[0]];

        // echo '<pre>'; print_r($vendortemp); exit;
        $query = $conn->execute("select vb.*,st.name as state_name from vendor_branch_offices vb left join states st on st.id=vb.state left join countries ct on ct.country_name=vb.country where vb.vendor_temp_id =".$id);
        $vendortemp['vendor_branch_offices'] = $query->fetchAll('assoc');

        $query = $conn->execute("select vf.*,st.name as state_name from vendor_factories vf left join states st on st.id=vf.state left join countries ct on ct.country_name=vf.country where vf.vendor_temp_id =".$id);
        $vendortemp['vendor_factories'] = $query->fetchAll('assoc');

        $query = $conn->execute("select vf.*,st.name as state_name from vendor_partner_address vf left join states st on st.id=vf.state left join countries ct on ct.country_name=vf.country where vf.vendor_temp_id =".$id);
        $vendortemp['vendor_partner_address'] = $query->fetchAll('assoc');

        $query = $conn->execute("select vf.*,st.name as state_name from vendor_reputed_customers vf left join states st on st.id=vf.state left join countries ct on ct.country_name=vf.country where vf.vendor_temp_id =".$id);
        $vendortemp['vendor_reputed_customers'] = $query->fetchAll('assoc');
        $vendortemp['vendor_commencements'] = $this->VendorCommencements->find('all')->where(['vendor_temp_id' => $id])->toArray();
        $vendortemp['vendor_facilities'] = $this->VendorFacilities->find('all')->where(['vendor_temp_id' => $id])->toArray();
        $vendortemp['vendor_factories'] = $this->VendorFactories->find('all')->contain(['VendorCommencements'])->where(['vendor_temp_id' => $id])->toArray();
        $vendortemp['vendor_questionnaires'] = $this->VendorQuestionnaires->find('all')->where(['vendor_temp_id' => $id])->toArray();
        
        $income_tax = $this->VendorIncometaxes->find('all')->where(['vendor_temp_id' => $id])->toArray();
        if(count($income_tax) > 0){$vendortemp['vendor_incometaxes']= $income_tax[0]; } else {$vendortemp['vendor_incometaxes']=[];}
        
        $other_details =$this->VendorOtherdetails->find('all')->where(['vendor_temp_id' => $id])->toArray();
        if(count($other_details) > 0){$vendortemp['vendor_otherdetails']= $other_details[0]; } else {$vendortemp['vendor_otherdetails']=[];}
        
        $registered_office = $this->VendorRegisteredOffices->find('all')->where(['vendor_temp_id' => $id])->toArray();
        if(count($registered_office) > 0){$vendortemp['vendor_registered_offices']= $registered_office[0]; } else {$vendortemp['vendor_registered_offices']=[];}
        
        $small_scale = $this->VendorSmallScales->find('all')->where(['vendor_temp_id' => $id])->toArray();
        if(count($small_scale) > 0){$vendortemp['vendor_small_scales']= $small_scale[0]; } else {$vendortemp['vendor_small_scales']=[];}

        $turnover = $this->VendorTurnovers->find('all')->where(['vendor_temp_id' => $id])->toArray();
        if(count($turnover) > 0){$vendortemp['vendor_turnovers']= $turnover[0]; } else {$vendortemp['vendor_turnovers']=[];}

        $response = array('status'=>1, 'message'=>$vendortemp);
        echo json_encode($response); exit;
    }

    public function getMaterialMasters()
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
                $temp['minimum_stock'] = $row->MIN_STOCK;
                $temp['uom'] = $row->MEINS;
                $matStock[] = $tmp;
            }

            $columns = array_keys($rows[0]);
            $upsertQuery = $this->Materials->query();
            $upsertQuery->insert($columns);

            foreach($rows as $row) {
                $upsertQuery->values($row);
            }
            $upsertQuery->epilog('ON DUPLICATE KEY UPDATE `sap_vendor_code`=VALUES(`sap_vendor_code`), `code`=VALUES(`code`),
                `description`=VALUES(`description`), `minimum_stock`=VALUES(`minimum_stock`), `uom`=VALUES(`uom`)')
                ->execute();

            $materialHistories = $this->MaterialHistories->newEntities($rows);
            $this->MaterialHistories->saveMany($materialHistories);

            
            $response['status'] = '1';
            $response['message'] = 'Success';
            $response['data'] = $materialHistories;
        }

        echo json_encode($response); exit;
    }


    public function masterByCompanyCode($id = null)
    {
        $response = ["status"=>0, 'message' =>'Empty request'];
        $this->loadModel("CompanyCodes");
        $this->loadModel("PurchasingOrganizations");
        
        /*$purchasingOrganizations = $this->PurchasingOrganizations->find('list', ['keyField' => 'id', 'valueField' => function ($row) {
            return $row->code.' - '.$row->name;
        }])->where(['company_code_id =' => $id])->all(); */
        $purchasingOrganizations = $this->PurchasingOrganizations->find()->select(['id', 'name' => 'CONCAT(code, " - ", name)'])->where(['company_code_id =' => $id])->toArray();
        
        $response = ["status"=>1, 'message' =>['PurchasingOrganizations'=>$purchasingOrganizations]];
        echo json_encode($response); exit;
    }

}