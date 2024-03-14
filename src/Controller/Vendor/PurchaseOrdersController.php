<?php

declare(strict_types=1);

namespace App\Controller\Vendor;

use Cake\View\Helper\HtmlHelper;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Mailer;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * PoHeaders Controller
 *
 * @property \App\Model\Table\PoHeadersTable $PoHeaders
 * @method \App\Model\Entity\PoHeader[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PurchaseOrdersController extends VendorAppController
{
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];  
        $this->set('flash', $flash);
    }
    
    var $uses = array('PoHeaders', 'DeliveryDetails');
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('headTitle', 'Purchase Order List');
        $this->loadModel('PoHeaders');
        $this->loadModel('PoItemSchedules');
        $this->loadModel('VendorFactories');


        $session = $this->getRequest()->getSession();
        $poHeaders = $this->paginate($this->PoHeaders->find()
            ->where(['sap_vendor_code' => $session->read('vendor_code'), '(select count(1) from po_item_schedules PoItemSchedules where po_header_id = PoHeaders.id) > 0']));

        $venFac = $this->VendorFactories->find()->where(['vendor_temp_id' => $session->read('vendor_id')]);
        $factoryExists = $venFac->count() ? 1 :  0;

        $this->set(compact('poHeaders', 'factoryExists'));
    }
    
    public function report()
    {
        $this->set('headTitle', 'Purchase Order List');
        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');
        $this->loadModel('AsnHeaders');
        $this->loadModel('AsnFooters');
        $this->loadModel("VendorTemps");
        $this->loadModel('VendorTypes');
        $this->loadModel('Materials');
        $session = $this->getRequest()->getSession();
        $vendorList = $this->VendorTemps->find('all')->select(['sap_vendor_code'])->distinct(['sap_vendor_code'])->where(['sap_vendor_code IS NOT NULL' ])->toArray();
        $poList = $this->PoHeaders->find('all')->where(['sap_vendor_code="'.$session->read('vendor_code').'"' ])->toArray();
        $materialList = $this->Materials->find('all')->select(['code', 'description'])->distinct(['code', 'description'])->where(['sap_vendor_code="'.$session->read('vendor_code').'"' ])->toArray();
        $segment = $this->Materials->find('all')->select(['segment'])->distinct(['segment'])->where(['segment IS NOT NULL' ])->toArray();
        $vendortype = $this->Materials->find('all')->distinct(['type'])->where(['type IS NOT NULL' ])->toArray();

        $this->set(compact('vendorList','poList', 'materialList', 'vendortype', 'segment'));
    }

    public function purchaseorderlist(){
        $this->autoRender = false;
        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');
        $this->loadModel('AsnHeaders');
        $this->loadModel('AsnFooters');
        $this->loadModel("VendorTemps");
        $this->loadModel('VendorTypes');
        $this->loadModel('Materials');
        $session = $this->getRequest()->getSession();
        $response = array('status'=>0, 'message'=>'fail', 'data'=>'');

        $conditions = " where 1=1 AND po_footers.deleted_indication = '' ";
        $statusconditions = "";
        if ($this->request->is(['patch', 'post', 'put'])) {
            $request = $this->request->getData();
            $conditions .= " and po_headers.sap_vendor_code='".$session->read('vendor_code')."'";
            if(isset($request['material'])) {
                $search = '';
                foreach ($request['material'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                if(!isset($request['vendor'])){ $conditions .= " and materials.code in (".$search.")"; }
                else{ $conditions .= " and materials.code in (".$search.")"; }
            }
            if(isset($request['vendortype'])) {
                $search = '';
                foreach ($request['vendortype'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                if(!isset($request['material']) and !isset($request['vendor'])){ $conditions .= " and materials.type in (".$search.")"; }
                else{ $conditions .= " and materials.type in (".$search.")"; }
            }
            if(isset($request['segment'])) {
                $search = '';
                foreach ($request['segment'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                if(!isset($request['material']) and !isset($request['vendor']) and !isset($request['vendortype'])){ $conditions .= " and materials.segment in (".$search.")"; }
                else{ $conditions .= " and materials.segment in (".$search.")"; }
            }
            if(isset($request['po_no'])) {
                $search = '';
                foreach ($request['po_no'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                if(!isset($request['material']) and !isset($request['vendor']) and !isset($request['vendortype']) and !isset($request['segment']) and !isset($request['status'])){ $conditions .= " and po_headers.po_no in (".$search.")"; }
                else{ $conditions .= " and po_headers.po_no in (".$search.")"; }
            }
            if(isset($request['po_no_date']) && !empty($request['po_no_date'])) {
                $search = $request['po_no_date'];
                if(!isset($request['material']) and !isset($request['vendor']) and !isset($request['vendortype']) and !isset($request['segment']) and !isset($request['status']) and !isset($request['po_no'])){ $conditions .= " and po_headers.created_on>='".$search." 00:00:00' and po_headers.created_on<='".$search." 23:59:59'"; }
                else{ $conditions .= " and po_headers.created_on>='".$search." 00:00:00' and po_headers.created_on<='".$search." 23:59:59'"; }
            }
            if(isset($request['delivery_date']) && !empty($request['delivery_date'])) {
                $search = $request['delivery_date'];
                $conditions .= " and po_item_schedules.delivery_date ='".$search."'";
            }
            if(isset($request['status'])) {
                $search = '';
                foreach ($request['status'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                if(!isset($request['material']) and !isset($request['vendor']) and !isset($request['vendortype']) and !isset($request['segment'])){ $statusconditions .= " where status in (".$search.")"; }
                else{ $statusconditions .= " where status in (".$search.")"; }
            }
            $conn = ConnectionManager::get('default');
        }

        $conn = ConnectionManager::get('default');
        $material = $conn->execute("select distinct * from (select po_headers.id, po_headers.sap_vendor_code, po_headers.po_no, item, materials.type, materials.segment, po_footers.material, po_footers.short_text, po_qty, grn_qty, pending_qty, po_footers.order_unit, po_footers.net_price, po_footers.net_value, po_footers.gross_value,po_footers.price_unit, po_item_schedules.actual_qty, asn_footers.qty as received_qty, DATE_FORMAT(po_item_schedules.delivery_date, '%d-%m-%Y') as 'delivery_date', a.asn_no,
        case
            when a.status = 3 then 'Received' else
            case when a.status = 2 then 'In-Transit' else
                case when po_item_schedules.delivery_date is null then '' else
                    case when po_item_schedules.received_qty = 0 then 'Scheduled' else
                        case when po_item_schedules.received_qty < po_item_schedules.actual_qty then 'Partial ASN created' else 'ASN created'
                        end
                    end
                end
            end
        end as 'status'
        from po_headers
        join po_footers on po_footers.po_header_id = po_headers.id
        left join vendor_temps on vendor_temps.sap_vendor_code = po_headers.sap_vendor_code
        left join materials on materials.code = po_footers.material and materials.sap_vendor_code = vendor_temps.sap_vendor_code
        left join po_item_schedules on po_item_schedules.po_header_id = po_headers.id and po_item_schedules.po_footer_id = po_footers.id
        left join asn_footers on asn_footers.po_schedule_id=po_item_schedules.id  and asn_footers.po_footer_id = po_footers.id
        left join (select asn_headers.status, asn_no, po_header_id, asn_footers.id as asn_footer_id, po_schedule_id from asn_headers left join asn_footers on asn_footers.asn_header_id = asn_headers.id) as a on a.po_header_id = po_headers.id and asn_footer_id = asn_footers.id ".$conditions." ) as a ". $statusconditions);
        $materialist = $material->fetchAll('assoc');

        $results = [];
        foreach ($materialist as $mat) {
            $tmp = [];
            $tmp[] = $mat['sap_vendor_code'];
            $tmp[] = $mat['po_no'];
            $tmp[] = $mat['item'];
            $tmp[] = $mat['type'];
            $tmp[] = $mat['segment'];
            $tmp[] = $mat['material'];
            $tmp[] = $mat['short_text'];
            $tmp[] = $mat['po_qty'];
            $tmp[] = $mat['grn_qty'];
            $tmp[] = $mat['pending_qty'];
            $tmp[] = $mat['order_unit'];
            $tmp[] = $mat['net_price'];
            $tmp[] = $mat['net_value'];
            $tmp[] = $mat['gross_value'];
            $tmp[] = $mat['price_unit'];
            $tmp[] = $mat['actual_qty'];
            $tmp[] = $mat['received_qty'];
            $tmp[] = $mat['asn_no'];
            $tmp[] = $mat['delivery_date'];
            $tmp[] = $mat['status'];
            $results[] = $tmp;
        }

        $response = array('status'=>1, 'message'=>'success', 'data'=>$results);
        echo json_encode($response); exit;
    }

    public function poNotify($id = null)
    {

        $response = array();
        $response['status'] = '0';
        $response['message'] = '';
        $this->autoRender = false;

        $session = $this->getRequest()->getSession();

        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');
        $this->loadModel('VendorTemps');
        $this->loadModel('Users');
        $this->loadModel('Buyers');
        $this->loadModel('Notifications');

        $poHeader = $this->PoHeaders->get($id, [
            'contain' => [],
        ]);

        if ($poHeader->acknowledge == 0) {
            $visit_url = Router::url('/', true);
            $poNumber  = $poHeader->po_no;
            $poHeader->acknowledge = 1; // Set acknowledge value to 1
            $poHeader->acknowledge_no = time(); 
            $poHeader->acknowledge_date = date('Y-m-d H:i:s'); 
            if($this->PoHeaders->save($poHeader)) {
                $this->PoFooters->updateAll(['is_updated' => 0], ['po_header_id' => $id]);
                $filteredBuyers = $this->Buyers->find()
                ->select(['Buyers.id','user_id'=> 'Users.id', 'email', 'first_name', 'last_name'])
                ->innerJoin(['Users' => 'users'], ['Users.username = Buyers.email'])
                ->innerJoin(['VendorTemps' => 'vendor_temps'], ['VendorTemps.purchasing_organization_id = Buyers.purchasing_organization_id', 'VendorTemps.company_code_id = Buyers.company_code_id'])
                ->where(['VendorTemps.sap_vendor_code' => $poHeader['sap_vendor_code'], 'Users.status' => 1]);
                
                $vendor = $this->VendorTemps->find()->where(['VendorTemps.sap_vendor_code' => $poHeader['sap_vendor_code']])->first();
                
                foreach ($filteredBuyers as $buyer) {
                    $n = $this->Notifications->find()->where(['user_id' => $buyer->user_id, 'notification_type'=>'PO Acknowledge'])->first();
                    if ($n) {
                        $n->notification_type = 'PO Acknowledge';
                        $n->message_count = $n->message_count+1;
                    } else {
                        $n = $this->Notifications->newEntity([
                            'user_id' => $buyer->user_id,
                            'notification_type' => 'PO Acknowledge',
                            'message_count' => '1',
                        ]);
                    }
                    $this->Notifications->save($n);

                    if ($buyer->email !== "") {
                        $mailer = new Mailer('default');
                        $mailer
                            ->setTransport('smtp')
                            ->setViewVars([
                                'poNumber' => $poNumber,
                                'buyer' => $buyer,
                                'vendor' => $vendor,
                                'spt_email' => $vendor->email,
                                ])
                            ->setFrom(Configure::read('MAIL_FROM'))
                            ->setTo($buyer->email)
                            ->setEmailFormat('html')
                            ->setSubject('VENDOR PORTAL - ORDER ACKNOWLEDGEMENT ('.$poNumber.')')
                            ->viewBuilder()
                                ->setTemplate('acknowledge');
                        $mailer->deliver();
                    }

                }

                
                $response['status'] = '1';
                $response['message'] = 'PO Acknowledged successfully';
            }
        }  else {
            $response['status'] = '0';
            $response['message'] = 'Already Acknowledged successfully';
        }

        echo json_encode($response);
    }
    public function poIgnore($id = null)
    {

        $response = array();
        $response['status'] = '0';
        $response['message'] = '';
        $this->autoRender = false;

        $session = $this->getRequest()->getSession();

        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');
        $this->loadModel('VendorTemps');
        $this->loadModel('Users');
        $this->loadModel('Buyers');
        $this->loadModel('Notifications');

        $poHeader = $this->PoHeaders->get($id, [ 'contain' => []]);

        if ($poHeader->acknowledge == 0) {
            $visit_url = Router::url('/', true);
            $poNumber  = $poHeader->po_no;
            $poHeader->acknowledge = 2; // Set reject value to 2
            $poHeader->acknowledge_date = date('Y-m-d H:i:s'); 
            $poHeader->remark = $this->request->getQuery('remark');
            if($this->PoHeaders->save($poHeader)) {
                $filteredBuyers = $this->Buyers->find()
                ->select(['Buyers.id','user_id'=> 'Users.id', 'email', 'first_name', 'last_name'])
                ->innerJoin(['Users' => 'users'], ['Users.username = Buyers.email'])
                ->innerJoin(['VendorTemps' => 'vendor_temps'], ['VendorTemps.purchasing_organization_id = Buyers.purchasing_organization_id', 'VendorTemps.company_code_id = Buyers.company_code_id'])
                ->where(['VendorTemps.sap_vendor_code' => $poHeader['sap_vendor_code'], 'Users.status' => 1]);
                
                $vendor = $this->VendorTemps->find()->where(['VendorTemps.sap_vendor_code' => $poHeader['sap_vendor_code']])->first();
                $po_footer = $this->PoFooters->find('all')->where(['PoFooters.po_header_id' => $poHeader['id'], 'deleted_indication' => ''])->toArray();
                foreach ($filteredBuyers as $buyer) {
                    if ($buyer->email !== "") {

                        $conn = ConnectionManager::get('default');
                        $query = $conn->execute("select buyers.email from buyers
                        left join po_headers on po_headers.created_user = buyers.sap_user
                        where po_headers.id=".$poHeader['id']);
                        $response = $query->fetchAll('assoc');
                        $mailer = new Mailer('default');
                        $mailer
                            ->setTransport('smtp')
                            ->setViewVars([
                                'poNumber' => $poNumber,
                                'buyer' => $buyer,
                                'vendor' => $vendor,
                                'po_footer' => $po_footer,
                                'spt_email' => $response[0]['email'],
                                'remark' => $poHeader->remark,
                                ])
                            ->setFrom(Configure::read('MAIL_FROM'))
                            ->setTo($buyer->email)
                            ->setEmailFormat('html')
                            ->setSubject('VENDOR PORTAL - ORDER REJECTED ('.$poNumber.')')
                            ->viewBuilder()
                                ->setTemplate('non_acknowledge');
                        $mailer->deliver();
                    }
                }

                $response['status'] = '1';
                $response['message'] = 'PO Rejected successfully';
            }
        } else {
            $response['status'] = '1';
            $response['message'] = 'PO Rejected successfully';
        }

        echo json_encode($response);
    }

    public function poApi($search = null, $createAsn = null)
    {
        $response = array();
        $response['status'] = '0';
        $response['message'] = '';
        $this->autoRender = false;

        $this->loadModel('PoHeaders');
        $this->loadModel('PoItemSchedules');

        $session = $this->getRequest()->getSession();

            $data = $this->PoHeaders->find('all')
                ->select(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.sap_vendor_code','PoHeaders.acknowledge'])
                ->distinct(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.sap_vendor_code','PoHeaders.acknowledge'])
                ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id'])
                ->where([
                    'sap_vendor_code' => $session->read('vendor_code'),
                    'acknowledge <' => 2,
                    'OR' => [
                        ['PoHeaders.po_no LIKE' => '%' . $search . '%'],
                        ['PoFooters.material LIKE' => '%' . $search . '%'],
                        ['PoFooters.short_text LIKE' => '%' . $search . '%'],
                    ]
                ])
                ->order(['PoHeaders.id' => 'DESC']);

        //  print_r($data);exit;

        if ($data->count() > 0) {
            $response['status'] = '1';
            $response['data'] = $data;
        } else {
            $response['status'] = '0';
            $response['message'] = 'Order not found';
        }
        echo json_encode($response);
    }

    public function asntocreate()
    {
        $this->autoRender = false;
        $conn = ConnectionManager::get('default');
        $session = $this->getRequest()->getSession();
        $response = array('status'=>0, 'message'=>'', 'data'=>'');
        $fromdate = '2023/01/01';
        $todate = date('Y/m/d');

        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');
        $this->loadModel("StockUploads");
        $this->loadModel("Materials");
        $this->loadModel('Users');
        $this->loadModel('PoItemSchedules');
        $this->loadModel('VendorFactories');
        
        $conditions = ' WHERE po_item_schedules.status = 1 AND po_headers.acknowledge=1 AND po_headers.sap_vendor_code='.$session->read('vendor_code').' AND (po_item_schedules.actual_qty - po_item_schedules.received_qty) > 0 AND po_footers.deleted_indication = "" '; 
        
        $data = $conn->execute('SELECT DISTINCT po_headers.id, po_headers.sap_vendor_code, po_headers.acknowledge, po_headers.po_no
        FROM po_headers
        INNER JOIN po_footers ON po_footers.po_header_id = po_headers.id
        INNER JOIN po_item_schedules ON po_item_schedules.po_footer_id = po_footers.id
        LEFT JOIN materials ON materials.code = po_footers.material AND po_headers.sap_vendor_code = materials.sap_vendor_code
        LEFT JOIN stock_uploads ON stock_uploads.material_id = materials.id AND po_headers.sap_vendor_code = stock_uploads.sap_vendor_code'.$conditions);
        
        if ($this->request->is(['post'])) {
            $request = $this->request->getData();
            if(isset($request['material'])) {
                $search = '';
                foreach ($request['material'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                $conditions .= " and po_footers.material IN (".$search.")";
            }
            if(isset($request['schqty'])) {
                $search = '';
                foreach ($request['schqty'] as $mat) { $search .= $mat . ","; }
                $search = rtrim($search, ',');
                $conditions .= " and po_item_schedules.actual_qty - po_item_schedules.received_qty IN (".$search.")";
            }
            if(isset($request['delfrom']) && !empty($request['delfrom'])) {
                $search = $request['delfrom'];
                $conditions .= " and po_item_schedules.delivery_date>='".$search." 00:00:00'";
            }
            if(isset($request['deltill']) && !empty($request['deltill'])) {
                $search = $request['deltill'];
                $conditions .= " and po_item_schedules.delivery_date<='".$search." 23:59:59'";
            }
            $data = $conn->execute('SELECT DISTINCT po_headers.id, po_headers.sap_vendor_code, po_headers.acknowledge, po_headers.po_no
            FROM po_headers
            INNER JOIN po_footers ON po_footers.po_header_id = po_headers.id
            INNER JOIN po_item_schedules ON po_item_schedules.po_footer_id = po_footers.id
            LEFT JOIN materials ON materials.code = po_footers.material AND po_headers.sap_vendor_code = materials.sap_vendor_code
            LEFT JOIN stock_uploads ON stock_uploads.material_id = materials.id AND po_headers.sap_vendor_code = stock_uploads.sap_vendor_code'.$conditions.' order by po_item_schedules.id desc');
        }

        $data = $data->fetchAll('assoc');
        //echo '<pre>'; print_r($data); exit;

        $response = array('status'=>1, 'message'=>'', 'data'=>$data);
        echo json_encode($response);
    }

    public function poForAsn($search = null)
    {
        $response = array();
        $response['status'] = 0;
        $response['message'] = '';
        $this->autoRender = false;

        $this->loadModel('PoHeaders');
        $this->loadModel('PoItemSchedules');

        $session = $this->getRequest()->getSession();
        
        $data = $this->PoHeaders->find('all')
            ->select(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.sap_vendor_code','PoHeaders.acknowledge'])
            ->distinct(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.sap_vendor_code','PoHeaders.acknowledge'])
            ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id', "PoFooters.deleted_indication = ''"])
            ->innerJoin(['PoItemSchedules' => 'po_item_schedules'], ['PoItemSchedules.po_footer_id = PoFooters.id'])
                        ->where([
                                'sap_vendor_code' => $session->read('vendor_code'), 
                'acknowledge' => 1,
                'PoItemSchedules.status' => 1,
                '(select count(1) from po_item_schedules PoItemSchedules where po_header_id = PoHeaders.id ) > 0',
                '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0',
                'OR' => [
                    ['PoHeaders.po_no LIKE' => '%' . $search . '%'],
                    ['PoFooters.material LIKE' => '%' . $search . '%'],
                    ['PoFooters.short_text LIKE' => '%' . $search . '%'],
                ],
            ])
            ->order(['PoHeaders.id' => 'DESC']);

            //echo '<pre>'; print_r($data); exit;
        if ($data->count() > 0) {
            $response['status'] = 1;
            $response['data'] = $data;
        } else {
            $response['status'] = 0;
            $response['message'] = 'Order not found';
        }
        echo json_encode($response);
    }


    public function poDetails($id =null)
    {
        $response = array();
        $response['status'] = 0;
        $response['message'] = '';
        $this->autoRender = false;

        $this->loadModel('PoHeaders');

        //$data = $this->PoFooters->find('all', ['conditions' => ['po_header_id' => $id]]);
        
        $poHeader = $this->PoHeaders->get($id, [
            'contain' => [],
        ]); 
        

        $html = '';
        if ($poHeader) {
            $html .= '<table class="table table-bordered material-list">
            <thead>
                <tr>   
                <th>Sap Vendor Code</th>
                <th>Po No</th>
                <th>Document Type</th>
                <th>Created By</th>
                <th>Created On</th>
                </tr>
            </thead>
            <tbody>';
           
            // print_r($row); exit;
       
                $html .= '<tr>
                 <td>' . $poHeader['sap_vendor_code'] . '</td>
                 <td>' . $poHeader['po_no'] . '</td>
                 <td>' . $poHeader['document_type'] . '</td>
                 <td>' . $poHeader['created_by'] . '</td>
                 <td>' . $poHeader['created_on']->i18nFormat('dd-MM-YYYY') . '</td>
                </tr>';
            

            $html .= "</tbody>
            </table>";

            $response['status'] = 1;
            $response['message'] = 'success';
            $response['html'] = $html;
        } else {
            $response['status'] = 0;
            $response['message'] = 'Material not found';
        }


        // echo '<pre>';
        // print_r($html);
        // exit;

       // header('Content-Type: application/json');
        echo json_encode($response);
    }


    public function createAsn()
    {
        $this->set('headTitle', 'Create ASN');
        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');
        $this->loadModel('PoItemSchedules');
        $this->loadModel('VendorFactories');
        $this->loadModel('Materials');

        $session = $this->getRequest()->getSession();
        $poHeaders = $this->paginate($this->PoHeaders->find()
            ->where(['sap_vendor_code' => $session->read('vendor_code'), '(select count(1) from po_item_schedules PoItemSchedules where po_header_id = PoHeaders.id) > 0']));

        $data = $this->PoHeaders->find('all')
        ->select(['PoHeaders.id', 'PoHeaders.sap_vendor_code', 'PoHeaders.po_no', 'PoHeaders.document_type', 'PoHeaders.created_by', 'created_date' => 'date_format(PoHeaders.created_on, "%d-%m-%Y")', 'PoHeaders.po_no', 'PoHeaders.currency', 'PoFooters.id', 'PoFooters.item', 'PoFooters.material', 'PoFooters.short_text', 'PoFooters.order_unit', 'PoFooters.net_price', 'PoItemSchedules.id', 'actual_qty' => '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty)', 'delivery_date' => 'date_format(PoItemSchedules.delivery_date, "%d-%m-%Y")', 'opening_stock' => 'StockUploads.opening_stock', 'production_stock' => 'StockUploads.production_stock', 'current_stock' => 'StockUploads.current_stock', 'minimum_stock' => 'Materials.minimum_stock', 'is_expired' => 'if(delivery_date < CURDATE() , "1" , "0")'])
        ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id', "PoFooters.deleted_indication = ''"])
        ->innerJoin(['PoItemSchedules' => 'po_item_schedules'], ['PoItemSchedules.po_footer_id = PoFooters.id'])
        ->leftJoin(['Materials' => 'materials'], ['Materials.code = PoFooters.material', 'PoHeaders.sap_vendor_code = Materials.sap_vendor_code'])
        ->leftJoin(['StockUploads' => 'stock_uploads'], ['StockUploads.material_id = Materials.id', 'PoHeaders.sap_vendor_code = StockUploads.sap_vendor_code'])
        ->where([
            'PoHeaders.sap_vendor_code' => $session->read('vendor_code'),
            'PoItemSchedules.status' => 1,
            '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0',
            ])
        ->order(['delivery_date' => 'DESC'])->toArray();
        
        $materials = [];
        $mattmp = [];
        $scheduleqty = [];
        foreach ($data as $mat) {
            if (!in_array($mat['PoFooters']['material'], $mattmp)) {
                $materials[] = [$mat['PoFooters']['material'], $mat['PoFooters']['short_text']];
                $mattmp[] = $mat['PoFooters']['material'];
            }
            if (!in_array($mat['actual_qty'], $scheduleqty)) { $scheduleqty[] = $mat['actual_qty']; }
        }


        //  print_r($materials); exit;
        // $materials = $this->Materials->find()
        // ->where(['sap_vendor_code' => $session->read('vendor_code')])->toArray();

        // $scheduleqty = $this->PoItemSchedules->find('all')->select(['actual_qty'])->distinct(['actual_qty'])->order(['actual_qty' => 'ASC'])->toArray();


        $factoryset = $this->VendorFactories->find('all')->where(['vendor_temp_id' => $session->read('vendor_id')])->all();

        $this->set(compact('poHeaders', 'factoryset', 'materials', 'scheduleqty'));
    }

    /**
     * View method
     *
     * @param string|null $id Po Header id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $flash = [];
        $this->loadModel('PoHeaders');
        $this->loadModel('PoItemSchedules');
        $this->loadModel('Notifications');
        $this->loadModel('Buyers');
        $this->loadModel('VendorTemps');

        /*$poHeader = $this->PoHeaders->get($id, [
            'contain' => ['PoFooters'],
        ]); */
        $poHeader = $this->PoHeaders->find('all')
            ->select(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.sap_vendor_code', 'PoHeaders.currency', 'PoFooters.id', 'PoFooters.item', 'PoFooters.material', 'PoFooters.short_text', 'PoFooters.order_unit', 'PoFooters.net_price', 'PoItemSchedules.id', 'actual_qty' => '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty)', 'PoItemSchedules.delivery_date'])
            ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id', "PoFooters.deleted_indication = ''"])
            ->innerJoin(['PoItemSchedules' => 'po_item_schedules'], ['PoItemSchedules.po_footer_id = PoFooters.id'])
            //->innerJoin(['dateDe' => '(select min(delivery_date) date from po_item_schedules PoItemSchedules where (PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0  group by po_footer_id )'], ['dateDe.date = PoItemSchedules.delivery_date'])

            ->where(['PoHeaders.id' => $id, '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0'])->toArray();

        // echo '<pre>'; print_r($poHeader); exit;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->loadModel('AsnHeaders');
            $this->loadModel('AsnFooters');
            try {
                $request = $this->request->getData();

                //echo '<pre>'; print_r($request); exit;

                $conn = ConnectionManager::get('default');
                $maxrfq = $conn->execute("SELECT MAX(asn_no) maxrfq FROM asn_headers");

                foreach ($maxrfq as $maxid) {
                    $asnNo = $maxid['maxrfq'];
                    if ($asnNo == 0) {
                        $asnNo = date('y') . str_pad($request['po_header_id'], 5, '0', STR_PAD_LEFT) . '1';
                    } else {
                        $asnNo = $asnNo + 1;
                    }
                }


                $invoiceUpload = $request["invoice"];
                $ewaybillUpload = $request["ewaybill"];
                $otherUploads = $request["others"];

                //print_r($productImage);exit;
                $uploads["uploads"] = array();
                // file uploaded
                
                if($invoiceUpload->getSize() > 0) {
                    $fileName = $asnNo . '_invoice_' . time() . '_' . $invoiceUpload->getClientFilename();
                    $fileType = $invoiceUpload->getClientMediaType();

                    if ($fileType == "application/pdf" || $fileType == "image/*") {
                        $imagePath = WWW_ROOT . "uploads/" . $fileName;
                        $invoiceUpload->moveTo($imagePath);
                        $uploads["uploads"]['invoice'] = "uploads/" . $fileName;
                    }
                }
                if($ewaybillUpload->getSize() > 0) {
                    $fileName = $asnNo . '_ewaybill_' . time() . '_' . $ewaybillUpload->getClientFilename();
                    $fileType = $ewaybillUpload->getClientMediaType();

                    if ($fileType == "application/pdf" || $fileType == "image/*") {
                        $imagePath = WWW_ROOT . "uploads/" . $fileName;
                        $ewaybillUpload->moveTo($imagePath);
                        $uploads["uploads"]['ewaybill'] = "uploads/" . $fileName;
                    }
                }
                foreach($otherUploads as $otherUpload) {
                    if($otherUpload->getSize() > 0) {
                        $fileName = $asnNo . '_other_' . time() . '_' . $otherUpload->getClientFilename();
                        $fileType = $otherUpload->getClientMediaType();

                        if ($fileType == "application/pdf" || $fileType == "image/*") {
                            $imagePath = WWW_ROOT . "uploads/" . $fileName;
                            $otherUpload->moveTo($imagePath);
                            $uploads["uploads"]['other'][] = "uploads/" . $fileName;
                        }
                    }
                }


                // print_r($uploads["invoices"]);exit;
                $asnData = array();
                $asnData['asn_no'] = $asnNo;
                $asnData['vendor_factory_id'] = $request['vendor_factory_id'];
                $asnData['po_header_id'] = $request['po_header_id'];
                $asnData['invoice_path'] = json_encode($uploads["uploads"]);
                $asnData['invoice_no'] = $request['invoice_no'];
                $asnData['invoice_date'] = $request['invoice_date'];
                $asnData['invoice_value'] = $request['invoice_value'];
                $asnData['vehicle_no'] = $request['vehicle_no'];
                $asnData['driver_name'] = $request['driver_name'];
                $asnData['driver_contact'] = $request['driver_contact'];
                $asnData['transporter_name'] = $request['transporter_name'];

                $asnObj = $this->AsnHeaders->newEmptyEntity();
                $asnObj = $this->AsnHeaders->patchEntity($asnObj, $asnData);

                if ($this->AsnHeaders->save($asnObj)) {
                    $filteredBuyers = $this->Buyers->find()
                    ->select(['Buyers.id','user_id'=> 'Users.id'])
                    ->innerJoin(['Users' => 'users'], ['Users.username = Buyers.email'])
                    ->innerJoin(['VendorTemps' => 'vendor_temps'], ['VendorTemps.purchasing_organization_id = Buyers.purchasing_organization_id', 'VendorTemps.company_code_id = Buyers.company_code_id'])
                    ->where(['VendorTemps.sap_vendor_code' => $poHeader[0]['sap_vendor_code'], 'Users.status' => 1]);

                    foreach ($filteredBuyers as $buyer) {
                        $n = $this->Notifications->find()->where(['user_id' => $buyer->user_id, 'notification_type'=>'New ASN'])->first();
                        if ($n) {
                            $n->notification_type = 'New ASN';
                            $n->message_count = $n->message_count+1;
                        } else {
                            $n = $this->Notifications->newEntity([
                                'user_id' => $buyer->user_id,
                                'notification_type' => 'New ASN',
                                'message_count' => '1',
                            ]);
                        }
                        $this->Notifications->save($n);
                    }    


                    $asnSchedueData = array();
                    foreach ($request['schedule_id'] as $key => $val) {
                        $tmp = array();
                        $tmp['asn_header_id'] = $asnObj->id;
                        $tmp['po_footer_id'] = $request['po_footer_id'][$key];
                        $tmp['po_schedule_id'] = $val;
                        $tmp['qty'] = $request['qty'][$key];
                        $asnSchedueData[] = $tmp;

                        $schedueData = $this->PoItemSchedules->get($val);
                        $schedueData->received_qty = $schedueData->received_qty + $request['qty'][$key];
                        $this->PoItemSchedules->save($schedueData);
                    }

                    $asnFooter = $this->AsnFooters->newEntities($asnSchedueData);
                    if ($this->AsnFooters->saveMany($asnFooter)) {
                        $response['status'] = 'success';
                        $response['message'] = 'Record save successfully';
                        $flash = ['type'=>'success', 'msg'=>(__("ASN-$asnNo has been created successfully", 30))];
                        $this->set('flash', $flash);
                        return $this->redirect(['controller' => 'asn', 'action' => 'index']);
                    } else {
                    }
                } else {
                    $flash = ['type'=>'error', 'msg'=>'fail'];
                    $this->set('flash', $flash);
                }
            } catch (\PDOException $e) {
                $flash = ['type'=>'error', 'msg'=>($e->getMessage())];
                $this->set('flash', $flash);
            } catch (\Exception $e) {
                $response['status'] = 'fail';
                $response['message'] = $e->getMessage();
            }
        }

        //$data = $this->PoItemSchedules->find('all', ['conditions' => ['po_footer_id' => $id]]);

        //echo '<pre>'; print_r($poHeader); exit;

        $this->set(compact('poHeader'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $flash = [];
        $poHeader = $this->PoHeaders->newEmptyEntity();
        if ($this->request->is('post')) {
            $poHeader = $this->PoHeaders->patchEntity($poHeader, $this->request->getData());
            if ($this->PoHeaders->save($poHeader)) {
                $flash = ['type'=>'success', 'msg'=>'The po header has been saved'];
                $this->set('flash', $flash);
                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The po header could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $this->set(compact('poHeader'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Po Header id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $flash = [];
        $poHeader = $this->PoHeaders->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $poHeader = $this->PoHeaders->patchEntity($poHeader, $this->request->getData());
            if ($this->PoHeaders->save($poHeader)) {
                $flash = ['type'=>'success', 'msg'=>'The record has been saved'];
                $this->set('flash', $flash);
                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The po header could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $this->set(compact('poHeader'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Po Header id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $flash = [];
        $this->request->allowMethod(['post', 'delete']);
        $poHeader = $this->PoHeaders->get($id);
        if ($this->PoHeaders->delete($poHeader)) {
            $flash = ['type'=>'success', 'msg'=>'The po header has been deleted'];
            $this->set('flash', $flash);
        } else {
            $flash = ['type'=>'error', 'msg'=>'The po header could not be deleted. Please, try again'];
            $this->set('flash', $flash);
        }

        return $this->redirect(['action' => 'index']);
    }


    public function adddelivery()
    {
        $response = array();
        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;
        $this->loadModel("DeliveryDetails");
        //echo '<pre>'; print_r($this->request->getData()); exit;
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $DeliveryDetail = $this->DeliveryDetails->newEmptyEntity();
                $DeliveryDetail = $this->DeliveryDetails->patchEntity($DeliveryDetail, $this->request->getData());
                if ($this->DeliveryDetails->save($DeliveryDetail)) {
                    $response['status'] = 'success';
                    $response['message'] = 'Record save successfully';
                }
            } catch (\Exception $e) {
                $response['status'] = 'fail';
                $response['message'] = $e->getMessage();
            }
        }

        echo json_encode($response);
    }


    public function getDeliveryDetails($id = null)
    {
        $response = array();
        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;
        $this->loadModel("DeliveryDetails");
        $data = $this->DeliveryDetails->find('all', ['conditions' => ['po_footer_id' => $id]]);

        $html = '';

        if ($data->count() > 0) {
            $html .= '<table class="table table-bordered table-hover" id="example1">
            <thead>
                    <tr>
                        <th>Challan No</th>
                        <th>Qty</th>
                        <th>Eway Bill No.</th>
                        <th>Einvoice No</th>
                        <th class="actions">Actions</th>
                    </tr>
            </thead>
            <tbody>';
            $totalQty = 0;
            foreach ($data as $row) {
                $totalQty = $totalQty + $row->qty;

                $html .= "<tr>
                            <td>$row->challan_no</td>
                            <td>$row->qty</td>
                            <td>$row->eway_bill_no</td>
                            <td>$row->einvoice_no</td>
                            <td class=\"actions\">
                                
                            </td>
                        </tr>";
            }

            $html .= "</tbody>
            </table>

            <div>Delivered Qty :$totalQty </div>";

            $response['status'] = 'success';
            $response['message'] = 'sucees';
            $response['html'] = $html;
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'no record found';
        }


        //echo '<pre>'; print_r($data); exit;


        echo json_encode($response);
    }

    public function getSchedules($id = null)
    {
        $response = array();
        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;
        $this->loadModel("PoItemSchedules");
        $data = $this->PoItemSchedules->find('all', ['conditions' => ['po_footer_id' => $id]]);

        $html = '';


        if ($data->count() > 0) {
            $html .= '<table class="table table-bordered table-hover" id="example2">
            <thead>
                    <tr>
                        <th>Required Qty</th>
                        <th>Delivery Date</th>
                        <th class="actions">Actions</th>
                    </tr>
            </thead>
            <tbody>';
            $totalQty = 0;
            foreach ($data as $row) {

                //$link = $this->Html->link(__('Dispatch'), "#", ['class' => 'dispatch_item', 'data-toggle'=> "modal", 'data-target' => "#exampleModal" ,'header-id' => $row->po_header_id, 'footer-id' => $id, 'schedule-id' => $row->id]);
                //echo $link; exit;
                $html .= "<tr>
                            <td>$row->actual_qty</td>
                            <td>$row->delivery_date</td>
                            <td class=\"actions\">
                                <a href='#' class='dispatch_item' data-toggle='modal' data-target='#exampleModal' header-id='$row->po_header_id'  footer-id='$id' schedule-id='$row->id'>Dispatch</a>
                            </td>
                        </tr>";
            }

            $html .= "</tbody>
            </table>";

            $response['status'] = 'success';
            $response['message'] = 'success';
            $response['html'] = $html;
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'No schedules';
        }


        //echo '<pre>'; print_r($data); exit;


        echo json_encode($response);
    }


    public function getPoHeadersWithItems($id = null, $factoryId=null){
        $this->autoRender = false;
        $session = $this->getRequest()->getSession();
        $response = array('status'=>0, 'message'=>'', 'data'=>'');
        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');
        $this->loadModel("StockUploads");
        $this->loadModel("Materials");
        $this->loadModel('PoItemSchedules');
        $this->loadModel('VendorFactories');
        $conn = ConnectionManager::get('default');
        $matarr = [];
        $fromdate = '2023/01/01';
        $todate = date('Y/m/d');
        $matCode = $this->Materials->find('all')->select(['code'])->distinct(['code'])->where(['sap_vendor_code="'.$session->read('vendor_code').'"' ])->toArray();
        foreach($matCode as $qty) { $matarr[] = $qty['code']; }
        // echo '<pre>'; print_r($matarr); exit;
        $scharr = [];
        $actqty = $this->PoItemSchedules->find('all')->select(['actual_qty'])->distinct(['actual_qty'])->toArray();
        foreach($actqty as $qty) { $scharr[] = $qty['actual_qty']; }

        
        $conditions = ' WHERE po_item_schedules.status = 1 AND po_headers.id='.$id.' AND stock_uploads.vendor_factory_id='.$factoryId.' AND(po_item_schedules.actual_qty - po_item_schedules.received_qty) > 0 AND po_footers.deleted_indication = "" '; 
        if ($this->request->is(['post'])) {
            $request = $this->request->getData();
            if(isset($request['material'])) {
                $search = '';
                foreach ($request['material'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                $conditions .= " and po_footers.material IN (".$search.")";
            }
            if(isset($request['schqty'])) {
                $search = '';
                foreach ($request['schqty'] as $mat) { $search .= $mat . ","; }
                $search = rtrim($search, ',');
                $conditions .= " and po_item_schedules.actual_qty - po_item_schedules.received_qty IN (".$search.")";
            }
            if(isset($request['delfrom']) && !empty($request['delfrom'])) {
                $search = $request['delfrom'];
                $conditions .= " and po_item_schedules.delivery_date>='".$search." 00:00:00'";
            }
            if(isset($request['deltill']) && !empty($request['deltill'])) {
                $search = $request['deltill'];
                $conditions .= " and po_item_schedules.delivery_date<='".$search." 23:59:59'";
            }
        }

        $data = $conn->execute('SELECT po_headers.id, po_headers.sap_vendor_code, po_headers.acknowledge, po_headers.po_no, po_headers.document_type, po_headers.created_by, DATE_FORMAT(po_headers.created_on, "%d-%m-%Y") AS created_date, po_headers.currency, po_footers.id as fid, po_footers.item, po_footers.material, po_footers.short_text, po_footers.order_unit,    po_footers.net_price, po_item_schedules.id, (po_item_schedules.actual_qty - po_item_schedules.received_qty) AS actual_qty, DATE_FORMAT(po_item_schedules.delivery_date, "%d-%m-%Y") AS delivery_date, stock_uploads.opening_stock, stock_uploads.production_stock, stock_uploads.current_stock, materials.minimum_stock, IF(po_item_schedules.delivery_date < CURDATE(), "1", "0") AS is_expired
        FROM po_headers
        INNER JOIN po_footers ON po_footers.po_header_id = po_headers.id
        INNER JOIN po_item_schedules ON po_item_schedules.po_footer_id = po_footers.id
        LEFT JOIN materials ON materials.code = po_footers.material AND po_headers.sap_vendor_code = materials.sap_vendor_code
        LEFT JOIN stock_uploads ON stock_uploads.material_id = materials.id AND po_headers.sap_vendor_code = stock_uploads.sap_vendor_code'.$conditions);
        $data = $data->fetchAll('assoc');
        // echo '<pre>'; print_r($data); exit;
        
        // $data = $this->PoHeaders->find('all')
        //     ->select(['PoHeaders.id', 'PoHeaders.sap_vendor_code', 'PoHeaders.po_no', 'PoHeaders.document_type', 'PoHeaders.created_by', 'created_date' => 'date_format(PoHeaders.created_on, "%d-%m-%Y")', 'PoHeaders.po_no', 'PoHeaders.currency', 'PoFooters.id', 'PoFooters.item', 'PoFooters.material', 'PoFooters.short_text', 'PoFooters.order_unit', 'PoFooters.net_price', 'PoItemSchedules.id', 'actual_qty' => '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty)', 'delivery_date' => 'date_format(PoItemSchedules.delivery_date, "%d-%m-%Y")', 'opening_stock' => 'StockUploads.opening_stock', 'production_stock' => 'StockUploads.production_stock', 'current_stock' => 'StockUploads.current_stock', 'minimum_stock' => 'Materials.minimum_stock', 'is_expired' => 'if(delivery_date < CURDATE() , "1" , "0")'])
        //     ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id', "PoFooters.deleted_indication = ''"])
        //     ->innerJoin(['PoItemSchedules' => 'po_item_schedules'], ['PoItemSchedules.po_footer_id = PoFooters.id'])
        //     ->leftJoin(['Materials' => 'materials'], ['Materials.code = PoFooters.material', 'PoHeaders.sap_vendor_code = Materials.sap_vendor_code'])
        //     ->leftJoin(['StockUploads' => 'stock_uploads'], ['StockUploads.material_id = Materials.id', 'PoHeaders.sap_vendor_code = StockUploads.sap_vendor_code', "vendor_factory_id = $factoryId"])
        //     //->innerJoin(['dateDe' => '(select min(delivery_date) date, po_footer_id from po_item_schedules PoItemSchedules where (PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0  group by po_footer_id )'], ['dateDe.date = PoItemSchedules.delivery_date', 'dateDe.po_footer_id = PoItemSchedules.po_footer_id'])
        //     ->where([
        //         'PoHeaders.id' => $id,
        //         'PoItemSchedules.status' => 1,
        //         '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0',
        //         'Materials.code IN' => $matarr,
        //         'PoItemSchedules.delivery_date >'=> $fromdate,
        //         'PoItemSchedules.delivery_date <'=> $todate,
        //         'PoItemSchedules.actual_qty IN'=> $scharr,
        //         ])
        //     ->order(['delivery_date' => 'ASC']);

        // echo '<pre>'; print_r($data); exit;

        
        $asnQty = $conn->execute("select item, sum(po_item_schedules.received_qty) as qty from po_headers
        join po_footers on po_footers.po_header_id = po_headers.id
        left join po_item_schedules on po_item_schedules.po_header_id = po_headers.id and po_item_schedules.po_footer_id = po_footers.id
        where po_headers.id=$id group by po_footers.item");

        $matAsnQty = [];
        foreach($asnQty as $qty) { $matAsnQty[$qty['item']] = doubleval($qty['qty']); }

        //echo '<pre>'; print_r($matarr); exit;
        $itemOldCurrentStock = [];
        foreach ($data as $row) {
            if (in_array($row['item'], $matAsnQty)) {
                $row['asn_qty'] = $matAsnQty[$row['item']];
            } else {
                $row['asn_qty'] = 0;
            }
            if(isset($itemOldCurrentStock[$row['item']])) {
                $row['current_stock'] = $itemOldCurrentStock[$row['item']];
            } else {
                $row['current_stock'] = ($row['opening_stock'] + $row['production_stock']);
                if (in_array($row['item'], $matAsnQty)) {
                    $row['current_stock'] = $row['current_stock'] - $matAsnQty[$row['item']];
                }
            }
            
            $itemOldCurrentStock[$row['item']]= $row['current_stock'] - $row['actual_qty'];
            if($row['current_stock'] < 0) { $row['current_stock'] = 0; }
        }

        // if ($data->count() > 0) { 
            //print_r($data); exit;
            $response = array('status'=>1, 'message'=>'Data Found', 'data'=>$data); 
        // }
        echo json_encode($response); exit;
    }
    
    public function getItems($id = null)
    {
        $response = array();
        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;
 
        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');

        //$data = $this->PoFooters->find('all', ['conditions' => ['po_header_id' => $id]]);

        $data = $this->PoHeaders->find('all')

            ->select(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.currency', 'PoFooters.id', 'PoFooters.item', 'PoFooters.material', 'PoFooters.short_text', 'PoFooters.order_unit', 'PoFooters.net_price', 'PoItemSchedules.id', 'actual_qty' => '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty)', 'delivery_date' => 'date_format(PoItemSchedules.delivery_date, "%d-%m-%Y")'])
            ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id'])
            ->innerJoin(['PoItemSchedules' => 'po_item_schedules'], ['PoItemSchedules.po_footer_id = PoFooters.id'])
            //->innerJoin(['dateDe' => '(select min(delivery_date) date, po_footer_id from po_item_schedules PoItemSchedules where (PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0  group by po_footer_id )'], ['dateDe.date = PoItemSchedules.delivery_date', 'dateDe.po_footer_id = PoItemSchedules.po_footer_id'])

            ->where(['PoHeaders.id' => $id, '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0']);
            //->limit(1);

        // echo '<pre>'; print_r($data); exit;

        $html = '';
        if ($data->count() > 0) {
            $html .= '<table class="table table-bordered material-list" id="example2">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="form-control form-control-sm" style="max-width: 20px;" id="ckbCheckAll">
                    </th>
                    <th>Item</th>
                    <th>Material</th>
                    <th>Delivery Date</th>
                    <th>Short Text</th>
                    <th>Pending Qty</th>
                    <th>Set Delivery Qty</th>
                </tr>
            </thead>
            <tbody>';
            $totalQty = 0;
            foreach ($data as $row) {
                // print_r($row); 
                $maxqty = $row->actual_qty+$row->actual_qty*0.05;
                $html .= '<tr>
                <td><input type="checkbox" name="schedule_id[]" value="' . $row->PoItemSchedules['id'] . '" style="max-width: 20px;" class="form-control form-control-sm checkBoxClass"  data-pendingqty="' . $row->actual_qty . '" data-id="' . $row->PoItemSchedules['id'] . '"></td>
                 <td>' . $row->PoFooters['item'] . '</td>
                 <td>' . $row->PoFooters['material'] . '</td>
                 <td>' . $row->delivery_date->i18nFormat('dd-MM-YYYY') . '</td>
                 <td>' . $row->PoFooters['short_text'] . '</td>
                 <td>' . $row->actual_qty . ' ' . $row->PoFooters['order_unit'] . '</td>
                 <td><input type="number" name="" class="form-control form-control-sm check_qty" data-max="'. $maxqty .'" max="'. $maxqty .'" required="required" data-item="' . $row->PoFooters['item'] . '" id="qty' . $row->PoItemSchedules['id'] . '" value="0"></td>
                 
                </tr>';
            }
            // exit;
            $html .= "</tbody>
            </table>";

            $response['status'] = 'success';
            $response['message'] = 'success';
            $response['html'] = $html;
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Material not found';
        }


        //echo '<pre>'; print_r($data); exit;
        echo json_encode($response); exit;
    }

    public function getPurchaseItem($id = null)
    {
        $response = array();
        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;

        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');

        //$data = $this->PoFooters->find('all', ['conditions' => ['po_header_id' => $id]]);

        $data = $this->PoHeaders->find('all')
            ->select(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.currency', 'PoFooters.id', 'PoFooters.item', 'PoFooters.material', 'PoFooters.short_text','PoFooters.grn_qty','PoFooters.pending_qty', 'PoFooters.order_unit', 'PoFooters.po_qty', 'PoFooters.net_price', 'PoFooters.net_value', 'PoFooters.is_updated'])
            ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id', "PoFooters.deleted_indication = ''"])
            ->where(['PoHeaders.id' => $id])
            ->order(['PoFooters.is_updated DESC']);

            $conn = ConnectionManager::get('default');
            $asnQty = $conn->execute("select item, sum(po_item_schedules.received_qty) as qty from po_headers
        join po_footers on po_footers.po_header_id = po_headers.id
        left join po_item_schedules on po_item_schedules.po_header_id = po_headers.id and po_item_schedules.po_footer_id = po_footers.id
        where po_headers.id=$id group by po_footers.item");

        $matAsnQty = [];
        foreach($asnQty as $qty) {
            $matAsnQty[$qty['item']] = doubleval($qty['qty']);
        }

        $html = '';
        if ($data->count() > 0) {
            $html .= '<table class="table table-bordered material-list" id="example2">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Material</th>
                    <th>Short Text</th>
                    <th>Quantity</th>
                    <th>Delivered</th>
                    <th>Pending</th>
                    <th>ASN In-transit</th>
                    <th>Base Value</th>
                    <th>Net Value</th>
                </tr>
            </thead>
            <tbody>';
            $totalQty = 0;
            foreach ($data as $row) {
                //print_r($row); exit;
                $style="";
                if($row->PoFooters['is_updated']) {
                    $style = "style='background-color:#FAA0A0;'";
                }

                $pendQty = $row->PoFooters['po_qty'] - $row->PoFooters['grn_qty'] - $matAsnQty[$row->PoFooters['item']];
                $html .= '<tr '.$style.'>
                 <td>' . $row->PoFooters['item'] . '</td>
                 <td>' . $row->PoFooters['material'] . '</td>
                 <td>' . $row->PoFooters['short_text'] . '</td>
                 <td>' . $row->PoFooters['po_qty'] . ' ' . $row->PoFooters['order_unit'] . '</td>
                 <td>' . $row->PoFooters['grn_qty'] . ' ' . $row->PoFooters['order_unit'] . '</td>
                 <td>' . $row->PoFooters['pending_qty'] . ' ' . $row->PoFooters['order_unit'] . '</td>
                 <td>' . ($matAsnQty[$row->PoFooters['item']] - $row->PoFooters['grn_qty']) . ' ' . $row->PoFooters['order_unit'] . '</td>
                 <td>' . $row->PoFooters['net_price'] . '</td>
                 <td>' . $row->PoFooters['net_value'] . '</td>
                 
                </tr>';
            }

            $html .= "</tbody>
            </table>";

            $response['status'] = 'success';
            $response['message'] = 'success';
            $response['html'] = $html;
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Material not found';
        }


        //echo '<pre>'; print_r($data); exit;


        echo json_encode($response);
    }


    public function asnMaterials($id = null)
    {
        $this->set('headTitle', 'Create ASN Review');
        
        $session = $this->getRequest()->getSession();

        if ($this->request->is(['patch', 'post', 'put'])) {

            $request = $this->request->getData();

            //echo '<pre>'; print_r($request); exit;

            $id = $request['po_header_id'];
            $this->loadModel('PoHeaders');
            $this->loadModel('PoItemSchedules');
            $this->loadModel("StockUploads");
            $this->loadModel("Materials");
            $this->loadModel('VendorFactories');
            

            $conditions = array();
            $whereFooterIds = "";

            //$conditions['PoHeaders.id']  = $id;
            $conditions[]  = 'PoHeaders.id = '. $id;
            $conditions[]  = '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0';


            if (!empty($request['footer_id'])) {
                $conditions[]  = "PoFooters.id in (" . implode(',', $request['footer_id']) . ")";
            }
            if (!empty($request['po_schedule_id'])) {
                $conditions[]  = "PoItemSchedules.id in (" . implode(',', $request['po_schedule_id']) . ")";
            }

            $poHeader = $this->PoHeaders->find('all')
                ->select(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.currency', 'PoFooters.id', 'PoFooters.item', 'PoFooters.material', 'PoFooters.short_text', 'PoFooters.order_unit', 'PoFooters.net_price', 'PoItemSchedules.id', 'actual_qty' => '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty)', 'delivery_date' => 'PoItemSchedules.delivery_date', 'current_stock'=>'StockUploads.current_stock', 'min_stock'=>'Materials.minimum_stock'])
                ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id'])
                ->leftJoin(['PoItemSchedules' => 'po_item_schedules'], ['PoItemSchedules.po_footer_id = PoFooters.id'])
                //->innerJoin(['dateDe' => '(select min(delivery_date) date, po_footer_id from po_item_schedules PoItemSchedules where (PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0  group by po_footer_id )'], ['dateDe.date = PoItemSchedules.delivery_date', 'dateDe.po_footer_id = PoItemSchedules.po_footer_id'])
                ->leftJoin(['Materials' => 'materials'], ['Materials.code = PoFooters.material', 'PoHeaders.sap_vendor_code = Materials.sap_vendor_code'])
                ->leftJoin(['StockUploads' => 'stock_uploads'], ['StockUploads.material_id = Materials.id'])
                ->where($conditions)
                ->order(['PoItemSchedules.id' => 'ASC'])
                //->limit(1)
            ->toArray();
    
            //echo '<pre>'; print_r($poHeader); exit;
            //echo '<pre>'; print_r($request);  exit;
            
            foreach ($poHeader as &$row) {
                foreach ($request['footer_id'] as $key => $footer_id) {
                    if ($row->PoFooters['id'] == $footer_id && $row->PoItemSchedules['id'] == $request['po_schedule_id'][$key]) {
                        $row->ship_qty = $request['footer_id_qty'][$key];
                    }
                }
            }

            //echo '<pre>'; print_r($poHeader); exit;
            $vendorFactories = $this->VendorFactories->find('list', ['keyField' => 'id', 'valueField' => 'factory_code'])->where(['vendor_temp_id' => $session->read('vendor_id')])->all();
        

            // $this->set(compact('poHeader', 'materialStock', 'vendorFactories'));
            $this->set(compact('poHeader', 'vendorFactories'));
        } else {
            return $this->redirect(['action' => 'create-asn']);
        }
    }

    public function saveAsn($id = null)
    {

        $response = array();
        $response['status'] = 0;
        $response['message'] = '';
        $this->autoRender = false;

        $this->loadModel('PoHeaders');
        $this->loadModel('PoItemSchedules');
        $this->loadModel('Notifications');
        $this->loadModel('Buyers');
        $this->loadModel('VendorTemps');

        /*$poHeader = $this->PoHeaders->get($id, [
            'contain' => ['PoFooters'],
        ]); */
        $poHeader = $this->PoHeaders->find('all')
            ->select(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.sap_vendor_code', 'PoHeaders.currency', 'PoFooters.id', 'PoFooters.item', 'PoFooters.material', 'PoFooters.short_text', 'PoFooters.order_unit', 'PoFooters.net_price', 'PoItemSchedules.id', 'actual_qty' => '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty)', 'PoItemSchedules.delivery_date'])
            ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id', "PoFooters.deleted_indication = ''"])
            ->innerJoin(['PoItemSchedules' => 'po_item_schedules'], ['PoItemSchedules.po_footer_id = PoFooters.id'])
            ->where(['PoHeaders.id' => $id, '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0'])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->loadModel('AsnHeaders');
            $this->loadModel('AsnFooters');
            try {
                $request = $this->request->getData();

                //echo '<pre>'; print_r($request); exit;

                $conn = ConnectionManager::get('default');
                $maxrfq = $conn->execute("SELECT MAX(asn_no) maxrfq FROM asn_headers");

                foreach ($maxrfq as $maxid) {
                    $asnNo = $maxid['maxrfq'];
                    if ($asnNo == 0) {
                        $asnNo = date('y') . str_pad($request['po_header_id'], 5, '0', STR_PAD_LEFT) . '1';
                    } else {
                        $asnNo = $asnNo + 1;
                    }
                }


                $invoiceUpload = $request["invoice"];
                $ewaybillUpload = $request["ewaybill"];
                $otherUploads = $request["others"];

                //print_r($productImage);exit;
                $uploads["uploads"] = array();
                // file uploaded
                
                if($invoiceUpload->getSize() > 0) {
                    $fileName = $asnNo . '_invoice_' . time() . '_' . $invoiceUpload->getClientFilename();
                    $fileType = $invoiceUpload->getClientMediaType();

                    if ($fileType == "application/pdf" || $fileType == "image/*") {
                        $imagePath = WWW_ROOT . "uploads/" . $fileName;
                        $invoiceUpload->moveTo($imagePath);
                        $uploads["uploads"]['invoice'] = "uploads/" . $fileName;
                    }
                }
                if($ewaybillUpload->getSize() > 0) {
                    $fileName = $asnNo . '_ewaybill_' . time() . '_' . $ewaybillUpload->getClientFilename();
                    $fileType = $ewaybillUpload->getClientMediaType();

                    if ($fileType == "application/pdf" || $fileType == "image/*") {
                        $imagePath = WWW_ROOT . "uploads/" . $fileName;
                        $ewaybillUpload->moveTo($imagePath);
                        $uploads["uploads"]['ewaybill'] = "uploads/" . $fileName;
                    }
                }
                foreach($otherUploads as $otherUpload) {
                    if($otherUpload->getSize() > 0) {
                        $fileName = $asnNo . '_other_' . time() . '_' . $otherUpload->getClientFilename();
                        $fileType = $otherUpload->getClientMediaType();

                        if ($fileType == "application/pdf" || $fileType == "image/*") {
                            $imagePath = WWW_ROOT . "uploads/" . $fileName;
                            $otherUpload->moveTo($imagePath);
                            $uploads["uploads"]['other'][] = "uploads/" . $fileName;
                        }
                    }
                }


                // print_r($uploads["invoices"]);exit;
                $asnData = array();
                $asnData['asn_no'] = $asnNo;
                $asnData['vendor_factory_id'] = $request['vendor_factory_id'];
                $asnData['po_header_id'] = $request['po_header_id'];
                $asnData['invoice_path'] = json_encode($uploads["uploads"]);
                $asnData['invoice_no'] = $request['invoice_no'];
                $asnData['invoice_date'] = $request['invoice_date'];
                $asnData['invoice_value'] = $request['invoice_value'];
                $asnData['vehicle_no'] = $request['vehicle_no'];
                $asnData['driver_name'] = $request['driver_name'];
                $asnData['driver_contact'] = $request['driver_contact'];
                $asnData['transporter_name'] = $request['transporter_name'];

                //if ($this->AsnHeaders->validate($asnData)) {
                $asnObj = $this->AsnHeaders->newEmptyEntity();
                $asnObj = $this->AsnHeaders->patchEntity($asnObj, $asnData);

                if (!$asnObj->getErrors()) {
                    if ($this->AsnHeaders->save($asnObj)) {
                        $filteredBuyers = $this->Buyers->find()
                        ->select(['Buyers.id','user_id'=> 'Users.id'])
                        ->innerJoin(['Users' => 'users'], ['Users.username = Buyers.email'])
                        ->innerJoin(['VendorTemps' => 'vendor_temps'], ['VendorTemps.purchasing_organization_id = Buyers.purchasing_organization_id', 'VendorTemps.company_code_id = Buyers.company_code_id'])
                        ->where(['VendorTemps.sap_vendor_code' => $poHeader[0]['sap_vendor_code'], 'Users.status' => 1]);

                        foreach ($filteredBuyers as $buyer) {
                            $n = $this->Notifications->find()->where(['user_id' => $buyer->user_id, 'notification_type'=>'New ASN'])->first();
                            if ($n) {
                                $n->notification_type = 'New ASN';
                                $n->message_count = $n->message_count+1;
                            } else {
                                $n = $this->Notifications->newEntity([
                                    'user_id' => $buyer->user_id,
                                    'notification_type' => 'New ASN',
                                    'message_count' => '1',
                                ]);
                            }
                            $this->Notifications->save($n);
                        }    


                        $asnSchedueData = array();
                        foreach ($request['schedule_id'] as $key => $val) {
                            $tmp = array();
                            $tmp['asn_header_id'] = $asnObj->id;
                            $tmp['po_footer_id'] = $request['po_footer_id'][$key];
                            $tmp['po_schedule_id'] = $val;
                            $tmp['qty'] = $request['qty'][$key];
                            $asnSchedueData[] = $tmp;

                            $schedueData = $this->PoItemSchedules->get($val);
                            $schedueData->received_qty = $schedueData->received_qty + $request['qty'][$key];
                            $this->PoItemSchedules->save($schedueData);
                        }

                        $asnFooter = $this->AsnFooters->newEntities($asnSchedueData);
                        if ($this->AsnFooters->saveMany($asnFooter)) {
                            $response['status'] = 1;
                            $response['message'] = "ASN-$asnNo has been created successfully";
                        }
                    } 
                } else {
                    $msg = '';
                    foreach($asnObj->getErrors() as $key => $er) {
                        foreach($er as $mssage) {
                            $msg .= "$key :: $mssage</br>";
                        }
                    }
                    $response['message'] = $msg;
                }
            } catch (\PDOException $e) {
                $response['message'] = $e->getMessage();
            } catch (\Exception $e) {
                $response['message'] = $e->getMessage();
            }
        }

        echo json_encode($response); exit;
    }
}
