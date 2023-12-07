<?php

declare(strict_types=1);

namespace App\Controller\Buyer;

use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;

/**
 * PoHeaders Controller
 *
 * @property \App\Model\Table\PoHeadersTable $PoHeaders
 * @method \App\Model\Entity\PoHeader[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PurchaseOrdersController extends BuyerAppController
{
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];
        $this->set('flash', $flash);
    }

    var $uses = array('PoHeaders');
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('headTitle', 'Purchase Order List');
        $this->loadModel('PoFooters');
        $this->loadModel('AsnHeaders');
        $this->loadModel('AsnFooters');
        $conn = ConnectionManager::get('default');
        $poReportData = $conn->execute('select po_headers.id, po_headers.sap_vendor_code, po_no, item, po_footers.material, po_footers.short_text, po_qty, grn_qty, pending_qty, po_footers.order_unit, po_footers.net_price, po_footers.net_value, po_footers.gross_value,po_footers.price_unit, 
        po_item_schedules.actual_qty, po_item_schedules.received_qty,po_item_schedules.delivery_date, a.asn_no, a.status  from po_headers
        join po_footers on po_footers.po_header_id = po_headers.id
        left join materials on materials.code = po_footers.material
        left join po_item_schedules on po_item_schedules.po_header_id = po_headers.id and po_item_schedules.po_footer_id = po_footers.id
        left join asn_footers on asn_footers.po_schedule_id=po_item_schedules.id  and asn_footers.po_footer_id = po_footers.id
        left join (select asn_headers.status, asn_no, po_header_id, asn_footers.id as asn_footer_id, po_schedule_id from asn_headers left join asn_footers on asn_footers.asn_header_id = asn_headers.id) as a on a.po_header_id = po_headers.id and asn_footer_id = asn_footers.id ' );
        
        //echo '<prE>'; print_r($poReportData); exit;
        $vendorList = [];
        $poList = [];
        $materialList = [];
        $statusList = [];
        foreach($poReportData as $row) {
            $vendorList[$row['sap_vendor_code']] = $row['sap_vendor_code'];
            $poList[$row['po_no']] = $row['po_no'];
            $materialList[$row['material']] = $row['material'];

            $status = '';
            if($row['status'] == 3) {
                $status = 'Received';
            }else if($row['status'] == 2) {
                $status = 'In-Transit';
            } else if(!$row['delivery_date']) {
                $status = '';
            }else if($row['received_qty'] == 0) {
                $status = 'Scheduled';
            }else if($row['received_qty'] < $row['actual_qty']) {
                $status = 'Partial ASN created';
            } else {
                $status = 'ASN created';
            }
            if($status) {
                $statusList[$status] = $status ;
            }
        }

        $vendorList = array_unique($vendorList);
        $poList = array_unique($poList);
        $materialList = array_unique($materialList);
        $statusList = array_unique($statusList);


        $this->set(compact('poReportData', 'vendorList','poList', 'materialList', 'statusList'));
    }

    public function searchData()
    {
        $this->autoRender = false;
        $this->loadModel('PoFooters');
        $this->loadModel('AsnHeaders');
        $this->loadModel('AsnFooters');

        $response['status'] = 0;
        $response['message'] = '';


        $conditions = " where 1=1 ";
        if ($this->request->is(['patch', 'post', 'put'])) {

            $request = $this->request->getData();
            if($request['vendor_code']) {
                $conditions .= " and po_headers.sap_vendor_code='$request[vendor_code]' ";
            }
            if($request['po_no']) {
                $conditions .= " and po_headers.po_no='$request[po_no]' ";
            }
            if($request['material']) {
                $conditions .= " and po_footers.material='$request[material]' ";
            }
            //echo '<pre>'; print_r($request); exit;

            $conn = ConnectionManager::get('default');
            $poReportData = $conn->execute('select po_headers.id, po_headers.sap_vendor_code, po_no, item, po_footers.material, po_footers.short_text, po_qty, grn_qty, pending_qty, po_footers.order_unit, po_footers.net_price, po_footers.net_value, po_footers.gross_value,po_footers.price_unit, 
            po_item_schedules.actual_qty, po_item_schedules.received_qty,po_item_schedules.delivery_date, a.asn_no, a.status  from po_headers
            join po_footers on po_footers.po_header_id = po_headers.id
            left join materials on materials.code = po_footers.material
            left join po_item_schedules on po_item_schedules.po_header_id = po_headers.id and po_item_schedules.po_footer_id = po_footers.id
            left join asn_footers on asn_footers.po_schedule_id=po_item_schedules.id  and asn_footers.po_footer_id = po_footers.id
            left join (select asn_headers.status, asn_no, po_header_id, asn_footers.id as asn_footer_id, po_schedule_id from asn_headers left join asn_footers on asn_footers.asn_header_id = asn_headers.id) as a on a.po_header_id = po_headers.id and asn_footer_id = asn_footers.id '. $conditions );
            
            $results = [];
            foreach ($poReportData as $material) {
                $tmp = [];

                $status = '';
                if($material['status'] == 3) {
                    $status = 'Received';
                }else if($material['status'] == 2) {
                    $status = 'In-Transit';
                } else if(!$material['delivery_date']) {
                    $status = '';
                }else if($material['received_qty'] == 0) {
                    $status = 'Scheduled';
                }else if($material['received_qty'] < $material['actual_qty']) {
                    $status = 'Partial ASN created';
                } else {
                    $status = 'ASN created';
                }

                $tmp[] = $material['sap_vendor_code'];
                $tmp[] = $material['po_no'];
                $tmp[] = $material['item'];
                $tmp[] = $material['material'];
                $tmp[] = $material['short_text'];
                $tmp[] = $material['po_qty'];
                $tmp[] = $material['grn_qty'];
                $tmp[] = $material['pending_qty'];
                $tmp[] = $material['order_unit'];
                $tmp[] = $material['net_price'];
                $tmp[] = $material['price_unit'];
                $tmp[] = $material['net_value'];
                $tmp[] = $material['gross_value'];
                $tmp[] = $material['actual_qty'];
                $tmp[] = $material['received_qty'];
                $tmp[] = $material['asn_no'];
                $tmp[] = $material['delivery_date'] ? date('d-m-y', strtotime($material['delivery_date'])): '';
                $tmp[] = $status;


                if($request['status'] && $request['status'] == $status) {
                    $results[] = $tmp;
                } else if(!$request['status']) {
                    $results[] = $tmp;
                }

                
            }

            //echo '<pre>'; print_r(count($results)); exit;
            if(count($results) > 0) {
                $response['status'] = 1;
                $response['message'] = '';
                $response['data'] = $results;
            } else {
                $response['status'] = 0;
                $response['message'] = 'No record';
            }


            echo json_encode($response); exit;
        }
    }

    public function view()
    {
        $session = $this->getRequest()->getSession();
        $this->set('headTitle', 'PO Detail');
        $this->loadModel('PoHeaders');
        $poHeaders = $this->PoHeaders->find()
            ->select(['id', 'po_no', 'sap_vendor_code'])->toArray();

        $this->set(compact('poHeaders'));
    }

    


    public function poApi($search = null)
    {
        $response = array();
        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;

        $this->set('headTitle', 'Purchase Order List');
        $this->loadModel('PoHeaders');
        $this->loadModel('PoItemSchedules');
        $this->loadModel("VendorTemps");

        $session = $this->getRequest()->getSession();

        $data = $this->PoHeaders->find('all')
            ->select(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.sap_vendor_code'])
            ->distinct(['PoHeaders.id', 'PoHeaders.po_no', 'PoHeaders.sap_vendor_code'])
            ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id'])
            ->join([
                'table' => 'vendor_temps',
                'alias' => 'V',
                'type' => 'INNER',
                'conditions' => ['V.sap_vendor_code = PoHeaders.sap_vendor_code', 
                'V.company_code_id' => $session->read('company_code_id'),
                'V.purchasing_organization_id' => $session->read('purchasing_organization_id'), 
                'status' => '3']
            ])
            ->where([
                'OR' => [
                    ['PoHeaders.po_no LIKE' => '%' . $search . '%'],
                    ['PoFooters.material LIKE' => '%' . $search . '%'],
                    ['PoFooters.short_text LIKE' => '%' . $search . '%'],
                    ['V.name LIKE' => '%' . $search . '%'],
                    ['V.sap_vendor_code LIKE' => '%' . $search . '%'],
                ],
                ['PoHeaders.created_on >= now()-interval 3 month']
            ])->order(['PoHeaders.id' => 'desc']);

        //echo '<pre>';print_r($data);exit;

        if ($data->count() > 0) {
            $response['status'] = 'success';
            $response['message'] = $data;
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Order not found';
        }
        echo json_encode($response);
    }

    public function getPoFooters($id = null)
    {
        $this->autoRender = false;
        $response = array();
        $response['status'] = '0';
        $response['message'] = '';

        $this->loadModel('PoHeaders');
        $poHeader = $this->PoHeaders->get($id, [
            'contain' => ['PoFooters'],
        ]);

        
        if (!$poHeader->acknowledge) {
            $response['status'] = 0;
            $response['data'] = $poHeader;
            $response['message'] = 'PO not acknowledged by vendor';
        } else if(!$poHeader->po_footers) {
            $response['status'] = 0;
            $response['data'] = null;
            $response['message'] = 'Line item not found';
        }else {
            $response['status'] = 1;
            $response['data'] = $poHeader;
            $response['message'] = '';
        }


        // echo '<pre>'; print_r($data); exit;


        echo json_encode($response);
        exit;
    }


    public function update($id = null)
    {

        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');
        
        $this->loadModel("Notifications");
        $this->loadModel("PoItemSchedules");
        $this->loadModel("VendorTemps");

        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;
        $PoItemSchedule = $this->PoItemSchedules->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'get', 'put'])) {
          

            $schedule = $this->PoItemSchedules->get($id);
            if ($this->PoItemSchedules->delete($schedule)) {

                $poDetail = $this->PoHeaders->find()
                        ->select(['sap_vendor_code', 'po_no'])
                        ->where(['id' => $PoItemSchedule->po_header_id])
                        ->first();

                $poItem = $this->PoFooters->find()
                        ->select(['item'])
                        ->where(['id' => $PoItemSchedule->po_footer_id])
                        ->first();

                        $vendorRecord = $this->VendorTemps->find()
                        ->where(['sap_vendor_code' => $poDetail->sap_vendor_code])
                        ->first();

                $filteredBuyers = $this->VendorTemps->find()
                            ->select(['VendorTemps.id','user_id'=> 'Users.id'])
                            ->innerJoin(['Users' => 'users'], ['Users.username = VendorTemps.email'])
                            ->where(['VendorTemps.id' => $vendorRecord['id']]);

                            foreach ($filteredBuyers as $buyer) {
                                $n = $this->Notifications->find()->where(['user_id' => $buyer->user_id, 'notification_type'=>'Schedule Cancelled'])->first();
                                if ($n) {
                                    $n->Notifications = 'Schedule Cancelled';
                                    $n->message_count = $n->message_count+1;
                                } else {
                                    $n = $this->Notifications->newEntity([
                                        'user_id' => $buyer->user_id,
                                        'notification_type' => 'Schedule Cancelled',
                                        'message_count' => '1',
                                    ]);
                                }
                                $this->Notifications->save($n);
                            }


                            $visit_url = Router::url('/', true);
                            $mailer = new Mailer('default');
                            $mailer
                                ->setTransport('smtp')
                                ->setViewVars([ 'subject' => 'Hi ' . $vendorRecord->name, 'mailbody' => 'A schedule has been cancelled for PO : '.$poDetail->po_no.' and Item : '.$poItem->item.' . Visit Vekpro for more details.', 'link' => $visit_url, 'linktext' => 'Visit Vekpro' ])
                                ->setFrom(['vekpro@fts-pl.com' => 'FT Portal'])
                                ->setTo($vendorRecord->email)
                                ->setEmailFormat('html')
                                ->setSubject('Vendor Portal - Schedule Cancelled')
                                ->viewBuilder()
                                    ->setTemplate('mail_template');
                            $mailer->deliver();

                $response['status'] = 'success';
                $response['message'] = 'Schedule deleted successfully';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'failed to delete schedule';
            }
        }
        echo json_encode($response);
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
                $flash = ['type' => 'success', 'msg' => 'The po header has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type' => 'error', 'msg' => 'The po header could not be saved. Please, try again'];
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
                $flash = ['type' => 'error', 'msg' => 'The po header has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type' => 'error', 'msg' => 'The po header could not be saved. Please, try again'];
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
            $flash = ['type' => 'error', 'msg' => 'The po header has been deleted'];
            $this->set('flash', $flash);
        } else {
            $flash = ['type' => 'error', 'msg' => 'The po header could not be deleted. Please, try again'];
            $this->set('flash', $flash);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function createSchedule()
    {
        $response = array();
        $response['status'] = 0;
        $response['message'] = '';
        $this->autoRender = false;
        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');
        $this->loadModel("Notifications");
        $this->loadModel("PoItemSchedules");
        $this->loadModel("VendorTemps");
 

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            foreach ($data as $row) {
                try {

                    $poDetail = $this->PoHeaders->find()
                        ->select(['sap_vendor_code', 'po_no'])
                        ->where(['id' => $row['po_header_id']])->first();
                    
                $poItem = $this->PoFooters->find()
                        ->select(['item'])
                        ->where(['id' => $row['po_footer_id']])
                        ->first();

                        $vendorRecord = $this->VendorTemps->find()
                        ->where(['sap_vendor_code' => $poDetail->sap_vendor_code])
                        ->first();


                    if ($vendorRecord->update_flag) {
                        $response['status'] = 0;
                        $response['message'] = 'Vendor details pending for review';
                    } else {
                        $PoItemSchedule = $this->PoItemSchedules->newEmptyEntity();
                        $PoItemSchedule = $this->PoItemSchedules->patchEntity($PoItemSchedule, $row);
                        if ($this->PoItemSchedules->save($PoItemSchedule)) {
                            $filteredBuyers = $this->VendorTemps->find()
                            ->select(['VendorTemps.id','user_id'=> 'Users.id'])
                            ->innerJoin(['Users' => 'users'], ['Users.username = VendorTemps.email'])
                            ->where(['VendorTemps.id' => $vendorRecord['id']]);

                            foreach ($filteredBuyers as $buyer) {
                                $n = $this->Notifications->find()->where(['user_id' => $buyer->user_id, 'notification_type'=>'New Schedule'])->first();
                                if ($n) {
                                    $n->Notifications = 'New Schedule';
                                    $n->message_count = $n->message_count+1;
                                } else {
                                    $n = $this->Notifications->newEntity([
                                        'user_id' => $buyer->user_id,
                                        'notification_type' => 'New Schedule',
                                        'message_count' => '1',
                                    ]);
                                }
                                $this->Notifications->save($n);
                            }


                            $visit_url = Router::url('/', true);
                            $mailer = new Mailer('default');
                            $mailer
                                ->setTransport('smtp')
                                ->setViewVars([ 'subject' => 'Hi ' . $vendorRecord->name, 'mailbody' => 'A new schedule has been created for PO: '.$poDetail->po_no.' and Item : '.$poItem->item.'. Visit Vekpro for more details.', 'link' => $visit_url, 'linktext' => 'Visit Vekpro' ])
                                ->setFrom(['vekpro@fts-pl.com' => 'FT Portal'])
                                ->setTo($vendorRecord->email)
                                ->setEmailFormat('html')
                                ->setSubject('Vendor Portal - Schedule created')
                                ->viewBuilder()
                                    ->setTemplate('mail_template');
                            $mailer->deliver();
                            $response['status'] = 1;
                            $response['message'] = "Schedule created successfully";
                        } 
                    }
                } catch (\Exception $e) {
                    $response['status'] = 0;
                    $response['message'] = $e->getMessage();
                }
            }
            
        }
        echo json_encode($response);
    }


    public function createScheduleUpdate($id = null)
    {
        $response = array();
        $response['status'] = '';
        $response['message'] = '';
        $this->autoRender = false;

        $this->loadModel('PoHeaders');
        $this->loadModel('PoFooters');
        $this->loadModel("Notifications");
        $this->loadModel("PoItemSchedules");
        $this->loadModel("VendorTemps");


        // $flash = [];
        $PoItemSchedule = $this->PoItemSchedules->get($id, [
            'contain' => [],
        ]);


        if ($this->request->is(['patch', 'post', 'put'])) {
            $PoItemSchedule = $this->PoItemSchedules->patchEntity($PoItemSchedule, $this->request->getData());
            if ($this->PoItemSchedules->save($PoItemSchedule)) {
                
                $poDetail = $this->PoHeaders->find()
                        ->select(['sap_vendor_code', 'po_no'])
                        ->where(['id' => $PoItemSchedule->po_header_id])
                        ->first();

                $poItem = $this->PoFooters->find()
                        ->select(['item'])
                        ->where(['id' => $PoItemSchedule->po_footer_id])
                        ->first();

                        $vendorRecord = $this->VendorTemps->find()
                        ->where(['sap_vendor_code' => $poDetail->sap_vendor_code])
                        ->first();

                $filteredBuyers = $this->VendorTemps->find()
                            ->select(['VendorTemps.id','user_id'=> 'Users.id'])
                            ->innerJoin(['Users' => 'users'], ['Users.username = VendorTemps.email'])
                            ->where(['VendorTemps.id' => $vendorRecord['id']]);

                            foreach ($filteredBuyers as $buyer) {
                                $n = $this->Notifications->find()->where(['user_id' => $buyer->user_id, 'notification_type'=>'Schedule Updated'])->first();
                                if ($n) {
                                    $n->Notifications = 'Schedule Updated';
                                    $n->message_count = $n->message_count+1;
                                } else {
                                    $n = $this->Notifications->newEntity([
                                        'user_id' => $buyer->user_id,
                                        'notification_type' => 'Schedule Updated',
                                        'message_count' => '1',
                                    ]);
                                }
                                $this->Notifications->save($n);
                            }


                            $visit_url = Router::url('/', true);
                            $mailer = new Mailer('default');
                            $mailer
                                ->setTransport('smtp')
                                ->setViewVars([ 'subject' => 'Hi ' . $vendorRecord->name, 'mailbody' => 'Schedule has been changed for PO : '.$poDetail->po_no.' and Item : '.$poItem->item.'. Visit Vekpro for more details.', 'link' => $visit_url, 'linktext' => 'Visit Vekpro' ])
                                ->setFrom(['vekpro@fts-pl.com' => 'FT Portal'])
                                ->setTo($vendorRecord->email)
                                ->setEmailFormat('html')
                                ->setSubject('Vendor Portal - Schedule Updated')
                                ->viewBuilder()
                                    ->setTemplate('mail_template');
                            $mailer->deliver();

                $response['status'] = 'success';
                $response['message'] = 'Delivery Date Update.';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Failed';
            }
        }
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
            $html .= '<table class="table" id="example2">
            <thead>
                    <tr>
                        <th>Actual Qty</th>
                        <th>Received Qty</th>
                        <th>Delivery Date</th>
                        <th>&nbsp;</th>
                    </tr>
            </thead>
            <tbody>';
            $totalQty = 0;
            foreach ($data as $row) {
                //$link = $Html->link(__('Communication'), "#", ['class' => 'schedule_item btn btn-default', 'header-id' => $poHeader->id, 'footer-id' => $poFooters->id, 'item-no' => $poFooters->item]);
                $totalQty += $row->actual_qty;
                $html .= "<tr>
                            <td>$row->actual_qty</td>
                            <td>$row->received_qty</td>
                            <td>$row->delivery_date</td>
                            <td><a href='#' class='notify_item btn btn-default' schedue-id='$row->id' data-toggle='modal' data-target='#notifyModal'>Notify</a></td>
                        </tr>";
            }

            $html .= "</tbody>
            </table>";

            $response['status'] = 'success';
            $response['message'] = 'success';
            $response['html'] = $html;
            $response['totalQty'] = $totalQty;
        } else {
            $response['html'] = '';
            $response['status'] = 'fail';
            $response['message'] = 'No schedule data';
            $response['totalQty'] = '0';
        }


        //echo '<pre>'; print_r($data); exit;


        echo json_encode($response);
    }

    public function getSchedulelist($id = null)
    {
        $this->autoRender = false;
        $this->loadModel("PoItemSchedules");
        $response = ['status' => 0, 'message' => '', 'totalQty' => ''];
        $data = $this->PoItemSchedules->find('all', ['conditions' => ['po_footer_id' => $id, 'status' => 1, 
        //'PoItemSchedules.id not in (select po_schedule_id from asn_footers where po_footer_id ='.$id.')'
        ]]);
        
        //print_r($data); exit;
        if ($data->count() > 0) {
            $totalQty = 0;
            foreach ($data as $row) {
                $totalQty += $row->actual_qty;
                $row->delivery_date = $row->delivery_date->i18nFormat('dd-MM-YYYY');
            }
            $response['status'] = 1;
            $response['message'] = $data;
            $response['totalQty'] = $totalQty;
        } else {
            $response = ['status' => 0, 'message' => 'No schedule data', 'totalQty' => 0];
        }
        // echo '<pre>'; print_r(json_encode($response)); exit;
        echo json_encode($response);
    }

    public function getScheduleMessages($id = null)
    {
        $response = array();
        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;
        $this->loadModel("ItemScheduleMessages");

        $data = $this->ItemScheduleMessages->find()

            ->select(['ItemScheduleMessages.message', 'ItemScheduleMessages.added_date', 'fullname' => 'CONCAT(Users.first_name,  " ",  Users.last_name )'])
            ->Contain(['Users'])
            ->where(['ItemScheduleMessages.schedule_id' => $id]);


        if ($data->count() > 0) {

            $html = '';

            foreach ($data as $row) {
                $html .= "<div class='past-msg'>
                <div class='row m-2'>
                <div class='col-md-12'>
                <div class='d-flex justify-content-between'>
                <div class='c-name'><b>$row->fullname</b></div>
                <div class='c-adde-ddate'><i>$row->added_date</i></div>
                </div>
                <div class='c-msg'>$row->message</div>
                </div>
                </div>
                </div> ";
            }


            $response['status'] = 'success';
            $response['message'] = 'success';
            $response['html'] = $html;
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'no record';
            $response['html'] = '';
        }

        echo json_encode($response);
    }

    public function saveScheduleRemarks()
    {
        $session = $this->getRequest()->getSession();
        $response = array();
        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;
        $this->loadModel("ItemScheduleMessages");
        //echo '<pre>'; print_r($this->request->getData()); exit;
        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $data = array();
                $data['schedule_id'] = $this->request->getData('schedule_id');
                $data['user_id'] = $session->read('id');
                $data['message'] = $this->request->getData('message');
                $PoItemSchedule = $this->ItemScheduleMessages->newEmptyEntity();

                $PoItemSchedule = $this->ItemScheduleMessages->patchEntity($PoItemSchedule, $data);
                //echo '<pre>'; print_r($PoItemSchedule); exit();
                if ($this->ItemScheduleMessages->save($PoItemSchedule)) {
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

    public function upload()
    {
        $response['status'] = 0;
        $response['message'] = 'upload fail';
        $this->autoRender = false;
        
        if ($this->request->is(['patch', 'post', 'put', 'ajax'])) {
            try {
            
                $uploadData = [];
                if (isset($_FILES['upload_file']) && $_FILES['upload_file']['name'] != "" && isset($_FILES['upload_file']['name'])) {
                    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($_FILES['upload_file']['tmp_name']);
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = $worksheet->getHighestRow(); 
                    $highestColumn = $worksheet->getHighestColumn();
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

                    //echo '<pre>'; print_r($worksheet); exit;
                    $this->loadModel('VendorTemps');
                    $this->loadModel('PoHeaders');
                    $this->loadModel('PoFooters');
                    $this->loadModel('PoItemSchedules');
                    
                    $tmp = [];
                    
                    for ($row = 2; $row <= $highestRow; ++$row) {
                        $vendorError = false;
                        $poError = false;
                        $poItemError = false;
                        $datas = [];
                        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            if($value){
                                if($col == 1) {
                                    $tmp['sap_vendor_code'] = $value;
                                    $datas['sap_vendor_code'] = $value;

                                    if(!$this->VendorTemps->exists(['sap_vendor_code' => str_pad((string)$value, 10, "0", STR_PAD_LEFT)])) {
                                        $vendorError = true;
                                    }

                                } else if($col == 2) {
                                    $poHeaderId = $this->PoHeaders->find('list')
                                    ->select(['id'])
                                    ->where(['po_no' => $value])
                                    ->first();
                                    $tmp['po_header_id'] = $poHeaderId ? $poHeaderId : null;
                                    $datas['po_no'] = $value;
                                    if(!$poHeaderId) {
                                        $poError = true;
                                    }
                                } else if($col == 3) {
                                    if(!$poError) {
                                        $poFooterId = $this->PoFooters->find('list')
                                        ->select(['id'])
                                        ->where(['po_header_id' => $tmp['po_header_id'], 'item' => str_pad((string)$value, 5, "0", STR_PAD_LEFT)])
                                        ->first();
                                        $tmp['po_footer_id'] = $poFooterId ? $poFooterId : null;
                                        if(!$poFooterId) {
                                            $poItemError = true;
                                        }
                                    } else {
                                        $tmp['po_footer_id'] = null;
                                    }

                                    $datas['item_no'] = $value;
                                    
                                } else if($col == 4){
                                    $datas['material'] = $value;
                                } else if($col == 5){
                                    $tmp['actual_qty'] = $value;
                                    $datas['schedule_qty'] = $value;
                                } else if($col == 6){
                                    $tmp['delivery_date'] = date('Y-m-d', strtotime(trim($value)));
                                    $datas['delivery_date'] = $value;
                                }
                            }
                        }

                        $datas['error'] = '';
                        if($vendorError) {
                            $datas['error'] = 'Invalid Vendor code';
                        } 
                        if($poError) {
                            $datas['error'] = 'PO Detail not found';
                        } 
                        if($poItemError) {
                            $datas['error'] = 'Item Detail not found';
                        } 

                        if(empty($datas['error'])) {
                            $uploadData[] = $tmp; 
                            
                            $PoItemSchedule = $this->PoItemSchedules->newEmptyEntity();
                            $PoItemSchedule = $this->PoItemSchedules->patchEntity($PoItemSchedule, $tmp);
                            if ($this->PoItemSchedules->save($PoItemSchedule)) {
                                $datas['error'] = "Schedule created";
                            } else {
                                $datas['error'] = "Fail to create schedule";
                            }

                            //echo '<pre>'; print_r($uploadData); exit;
                            /*if($this->PoItemSchedules->exists(['po_header_id' => $tmp['po_header_id'], 'po_footer_id' => $tmp['po_footer_id'], 'delivery_date' => $tmp['delivery_date'], 'status' => 1])) {
                                $datas['error'] = 'Schedule already created';
                            } else {
                                $PoItemSchedule = $this->PoItemSchedules->newEmptyEntity();
                                $PoItemSchedule = $this->PoItemSchedules->patchEntity($PoItemSchedule, $tmp);
                                if ($this->PoItemSchedules->save($PoItemSchedule)) {
                                    $datas['error'] = "Schedule created";
                                } else {
                                    $datas['error'] = "Fail to create schedule";
                                }
                            } */
                        }

                        $showData[] = $datas;
                    }

                    $response['status'] = 1;
                    $response['message'] = 'uploaded Successfully';
                    $response['data'] = $showData;
                } else {
                    $response['status'] = 0;
                    $response['message'] = 'file not uploaded';
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

}
