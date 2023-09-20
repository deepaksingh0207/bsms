<?php

declare(strict_types=1);

namespace App\Controller\Vendor;

/**
 * DeliveryDetails Controller
 *
 * @property \App\Model\Table\DeliveryDetailsTable $DeliveryDetails
 * @method \App\Model\Entity\DeliveryDetail[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AsnController extends VendorAppController
{
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];  
        $this->set('flash', $flash);
    }

    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('headTitle', 'ASN List');
        $this->loadModel('AsnHeaders');
        $session = $this->getRequest()->getSession();
        /*$this->paginate = [
            'contain' => ['PoHeaders'],
            'conditions' => ['AsnHeaders.status' => '1', 'PoHeaders.sap_vendor_code' => $session->read('vendor_code')] 
        ]; */


        $query = $this->AsnHeaders->find()
            ->select(['AsnHeaders.id', 'AsnHeaders.asn_no', 'AsnHeaders.invoice_no', 'AsnHeaders.invoice_date', 'PoHeaders.po_no', 'AsnHeaders.added_date', 'AsnHeaders.status', 'VendorFactories.factory_code'])
            ->contain(['PoHeaders', 'VendorFactories'])
            ->where(['PoHeaders.sap_vendor_code' => $session->read('vendor_code')]);

        //echo '<pre>'; print_r($query); exit;
        $deliveryDetails = $this->paginate($query);

        $this->set(compact('deliveryDetails'));
    }

    /**
     * View method
     *
     * @param string|null $id Delivery Detail id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->set('headTitle', 'ASN Detail');
        $this->loadModel('AsnHeaders');

        $deliveryDetails = $this->AsnHeaders->find('all')
            ->select(['AsnHeaders.id', 'AsnHeaders.status', 'AsnHeaders.asn_no', 'AsnHeaders.invoice_path', 'AsnHeaders.invoice_no', 'AsnHeaders.invoice_date', 'AsnHeaders.invoice_value', 'AsnHeaders.vehicle_no', 'AsnHeaders.driver_name', 'AsnHeaders.driver_contact', 'AsnHeaders.added_date', 'PoHeaders.po_no', 'PoFooters.item', 'PoFooters.material', 'PoFooters.order_unit', 'AsnFooters.qty', 'PoItemSchedules.actual_qty', 'PoItemSchedules.delivery_date', 'VendorFactories.factory_code'])
            ->innerJoin(['PoHeaders' => 'po_headers'], ['AsnHeaders.po_header_id = PoHeaders.id'])
            ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.po_header_id = PoHeaders.id'])
            ->innerJoin(['VendorFactories' => 'vendor_factories'], ['VendorFactories.id = AsnHeaders.vendor_factory_id'])
            ->innerJoin(['PoItemSchedules' => 'po_item_schedules'], ['PoItemSchedules.po_header_id = PoHeaders.id', 'PoItemSchedules.po_footer_id = PoFooters.id'])
            ->innerJoin(['AsnFooters' => 'asn_footers'], ['AsnFooters.asn_header_id = AsnHeaders.id', 'AsnFooters.po_footer_id = PoFooters.id'])
            ->innerJoin(['AsnFooters' => 'asn_footers'], ['AsnFooters.asn_header_id = AsnHeaders.id', 'AsnFooters.po_footer_id = PoFooters.id', 'AsnFooters.po_schedule_id = PoItemSchedules.id'])

            ->where(['AsnHeaders.id' => $id])->toArray();


        $this->set('deliveryDetails', $deliveryDetails);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $flash = [];
        $deliveryDetail = $this->DeliveryDetails->newEmptyEntity();
        if ($this->request->is('post')) {
            $deliveryDetail = $this->DeliveryDetails->patchEntity($deliveryDetail, $this->request->getData());
            if ($this->DeliveryDetails->save($deliveryDetail)) {
                $flash = ['type'=>'success', 'msg'=>'The delivery detail has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The delivery detail could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $poHeaders = $this->DeliveryDetails->PoHeaders->find('list', ['limit' => 200])->all();
        $poFooters = $this->DeliveryDetails->PoFooters->find('list', ['limit' => 200])->all();
        $this->set(compact('deliveryDetail', 'poHeaders', 'poFooters'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Delivery Detail id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $flash = [];
        $deliveryDetail = $this->DeliveryDetails->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $deliveryDetail = $this->DeliveryDetails->patchEntity($deliveryDetail, $this->request->getData());
            if ($this->DeliveryDetails->save($deliveryDetail)) {
                $flash = ['type'=>'success', 'msg'=>'The delivery detail has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'success', 'msg'=>'The delivery detail could not be saved. Please, try again'];
            $this->set('success', $flash);
        }
        $poHeaders = $this->DeliveryDetails->PoHeaders->find('list', ['limit' => 200])->all();
        $poFooters = $this->DeliveryDetails->PoFooters->find('list', ['limit' => 200])->all();
        $this->set(compact('deliveryDetail', 'poHeaders', 'poFooters'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Delivery Detail id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $flash = [];
        $this->request->allowMethod(['post', 'delete']);
        $deliveryDetail = $this->DeliveryDetails->get($id);
        if ($this->DeliveryDetails->delete($deliveryDetail)) {
            $flash = ['type'=>'success', 'msg'=>'The delivery detail has been deleted'];
        } else {
            $flash = ['type'=>'error', 'msg'=>'The delivery detail could not be deleted. Please, try again'];
        }
        $this->set('flash', $flash);

        return $this->redirect(['action' => 'index']);
    }

    public function markDelivered($id = null)
    {
        $response = array();
        $response['status'] = 'fail';
        $response['message'] = '';
        $this->autoRender = false;

        $this->loadModel('AsnHeaders');
        $this->loadModel('StockUploads');
        $session = $this->getRequest()->getSession();

        $deliveryDetail = $this->AsnHeaders->find('all')
        ->select($this->AsnHeaders)
        ->select(['material_id'=>'Materials.id', 'qty' => 'AsnFooters.qty'])
        ->innerJoin(['AsnFooters' => 'asn_footers'], ['AsnFooters.asn_header_id = AsnHeaders.id'])
        ->innerJoin(['PoFooters' => 'po_footers'], ['PoFooters.id = AsnFooters.po_footer_id'])
        ->innerJoin(['Materials' => 'materials'], ['Materials.code = PoFooters.material'])
        ->where(['AsnHeaders.id' => $id])->first();


        $deliveryDetail = $this->AsnHeaders->patchEntity($deliveryDetail, ['status' => 2, 'gateout_date'=>date('Y-m-d h:i:s')]);

        if ($this->AsnHeaders->save($deliveryDetail)) {

            $stockDetails = $this->StockUploads->find('all', 
            ['conditions' => array('StockUploads.sap_vendor_code' => $session->read('vendor_code'), 
            'material_id' => $deliveryDetail->material_id, 
            'vendor_factory_id' => $deliveryDetail->vendor_factory_id)])->first();

            if($stockDetails) {
                $stockDetails = $this->StockUploads->patchEntity($stockDetails, ['asn_stock' => ($stockDetails->asn_stock + $deliveryDetail->qty)]);
                if($this->StockUploads->save($stockDetails)) {
                    $response['status'] = 'success';
                    $response['message'] = 'success';
                }

            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Stock not found';
            }
            
            /*$asnUpload = [];
            $uploadFileContent = json_encode($data);
            $uploadfileName = 'ASN_('.$deliveryDetail->asn_no.')_REQ.JSON';
            $ftpConn = $this->Ftp->connection();
            if($this->Ftp->uploadFile($ftpConn, $uploadFileContent, $uploadfileName)) {
                $flash = ['type'=>'success', 'msg'=>' ASN sent to SAP'];
            } else {
                $flash = ['type'=>'error', 'msg'=>' ASN sent to SAP fail'];
            }*/

            $response['status'] = 'success';
            $response['message'] = 'success';
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'mark entry';
        }


        echo json_encode($response);
    }
}
