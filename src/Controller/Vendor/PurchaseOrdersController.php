<?php
declare(strict_types=1);

namespace App\Controller\Vendor;
use Cake\View\Helper\HtmlHelper; 
use Cake\Datasource\ConnectionManager;

/**
 * PoHeaders Controller
 *
 * @property \App\Model\Table\PoHeadersTable $PoHeaders
 * @method \App\Model\Entity\PoHeader[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PurchaseOrdersController extends VendorAppController
{
    var $uses = array('PoHeaders', 'DeliveryDetails');
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    { 
        $this->loadModel('PoHeaders');
        $this->loadModel('PoItemSchedules');
        $session = $this->getRequest()->getSession();
        $poHeaders = $this->paginate($this->PoHeaders->find()
        ->where(['sap_vendor_code' => $session->read('vendor_code'), '(select count(1) from po_item_schedules PoItemSchedules where po_header_id = PoHeaders.id) > 0']));

        $this->set(compact('poHeaders'));
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
        $this->loadModel('PoHeaders');
        $this->loadModel('PoItemSchedules');


        /*$poHeader = $this->PoHeaders->get($id, [
            'contain' => ['PoFooters'],
        ]); */
        $poHeader = $this->PoHeaders->find('all')
        ->select(['PoHeaders.id', 'PoHeaders.po_no','PoHeaders.currency', 'PoFooters.id', 'PoFooters.item', 'PoFooters.material','PoFooters.short_text', 'PoFooters.order_unit','PoFooters.net_price', 'PoItemSchedules.id','actual_qty' => '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty)' , 'PoItemSchedules.delivery_date'])
        ->innerJoin(['PoFooters' => 'po_footers'],['PoFooters.po_header_id = PoHeaders.id'])
        ->innerJoin(['PoItemSchedules' => 'po_item_schedules'], ['PoItemSchedules.po_footer_id = PoFooters.id'])
        ->innerJoin(['dateDe' => '(select min(delivery_date) date from po_item_schedules PoItemSchedules where (PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0  group by po_footer_id )'], ['dateDe.date = PoItemSchedules.delivery_date'])
        
        ->where(['PoHeaders.id' => $id, '(PoItemSchedules.actual_qty - PoItemSchedules.received_qty) > 0'])->toArray();

        //echo '<pre>'; print_r($poHeader); exit;
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->loadModel('AsnHeaders');
            $this->loadModel('AsnFooters');
            try{
                $request = $this->request->getData();
                $conn = ConnectionManager::get('default');
                $maxrfq = $conn->execute("SELECT MAX(asn_no) maxrfq FROM asn_headers where po_header_id=".$request['po_header_id']);

                foreach ($maxrfq as $maxid) {
                    $asnNo = $maxid['maxrfq'];
                    if($asnNo == 0) {
                        $asnNo =date('y') .str_pad($request['po_header_id'], 5, '0', STR_PAD_LEFT) . '1';
                    } else {
                        $asnNo = $asnNo + 1; 
                    }
                    
                }   

                //echo '<pre>'; print_r($request); exit;

                $productImage = $request["invoices"];
                $uploads["invoices"] = array();
                // file uploaded
                //foreach($productImages as $productImage) {
                    $fileName = $asnNo.'_'.time().'_'.$productImage->getClientFilename();
                    $fileType = $productImage->getClientMediaType();

                    if ($fileType == "application/pdf" || $fileType == "image/*") {
                        $imagePath = WWW_ROOT . "uploads/" . $fileName;
                        $productImage->moveTo($imagePath);
                        $uploads["invoices"][]= "uploads/" . $fileName;
                    }
                //}
                
                $asnData = array();
                $asnData['asn_no'] = $asnNo;
                $asnData['po_header_id'] = $request['po_header_id'];
                $asnData['invoice_path'] = json_encode($uploads["invoices"]);
                $asnData['invoice_no'] = $request['invoice_no'];
                $asnData['invoice_date'] = $request['invoice_date'];
                $asnData['invoice_value'] = $request['invoice_value'];
                $asnData['vehicle_no'] = $request['vehicle_no'];
                $asnData['driver_name'] = $request['driver_name'];
                $asnData['driver_contact'] = $request['driver_contact'];

                $asnObj = $this->AsnHeaders->newEmptyEntity();
                $asnObj = $this->AsnHeaders->patchEntity($asnObj, $asnData);

                if ($this->AsnHeaders->save($asnObj)) {

                    $asnSchedueData = array();
                    foreach($request['schedule_id'] as $key => $val) {
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
                    if($this->AsnFooters->saveMany($asnFooter)) {  
                        $response['status'] = 'success';
                        $response['message'] = 'Record save successfully';
                        $this->Flash->success("ASN-$asnNo has been created successfully");
                        return $this->redirect(['controller' => 'asn', 'action' => 'index']);
                    }  else {

                    }
                } else {
                    $this->Flash->error("fail");
                }
            } catch (\PDOException $e) {
                $this->Flash->error($e->getMessage());
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
        $poHeader = $this->PoHeaders->newEmptyEntity();
        if ($this->request->is('post')) {
            $poHeader = $this->PoHeaders->patchEntity($poHeader, $this->request->getData());
            if ($this->PoHeaders->save($poHeader)) {
                $this->Flash->success(__('The po header has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The po header could not be saved. Please, try again.'));
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
        $poHeader = $this->PoHeaders->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $poHeader = $this->PoHeaders->patchEntity($poHeader, $this->request->getData());
            if ($this->PoHeaders->save($poHeader)) {
                $this->Flash->success(__('The record has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The po header could not be saved. Please, try again.'));
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

        $this->request->allowMethod(['post', 'delete']);
        $poHeader = $this->PoHeaders->get($id);
        if ($this->PoHeaders->delete($poHeader)) {
            $this->Flash->success(__('The po header has been deleted.'));
        } else {
            $this->Flash->error(__('The po header could not be deleted. Please, try again.'));
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
            try{
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

        if($data->count() > 0) {
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
            foreach($data as $row) {
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


        if($data->count() > 0) {
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
            foreach($data as $row) {
                
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
}
