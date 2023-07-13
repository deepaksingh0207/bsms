<?php
declare(strict_types=1);

namespace App\Controller\Vendor;


use App\Model\Table\VendorMaterialTable;

/**
 * Productionline Controller
 *
 * @property \App\Model\Table\ProductionlineTable $Productionline
 * @method \App\Model\Entity\Productionline[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductionlineController extends VendorAppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

       $this->loadModel("productionline");
        $session = $this->getRequest()->getSession();
        $vendorId = $session->read('id');
        // $productionline = $this->paginate($this->Productionline->find('all', [
        //     'conditions' => ['Productionline.vendor_id' => $vendorId]
        // ]));

        $productionline = $this->productionline->find('all', [
            'conditions' => ['productionline.vendor_id' => $vendorId]
        ])->select([
            'id','prdline_description', 'prdline_capacity','vm_description' => 'vm.description', 'vm_vendor_code' => 'vm.vendor_material_code', 'uom_desp' => 'um.code',
        ])->join([
            'table' => 'vendor_material',
            'alias' => 'vm',
            'type' => 'LEFT',
            'conditions' => 'vm.id = productionline.vendormaterial_id',
        ])->join([
            'table' => 'uoms',
            'alias' => 'um',
            'type' => 'LEFT',
            'conditions' => 'um.id = vm.uom',
        ])->toArray();


        // productionline

       // print_r($productionline);exit;


    
        $this->set(compact('productionline'));
    }

    /**
     * View method
     *
     * @param string|null $id Productionline id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productionline = $this->Productionline->get($id, [
            'contain' => ['Dailymonitor'],
        ]);

        $this->set(compact('productionline'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel("VendorMaterial");
        $this->loadModel("VendorTemps");
        $this->loadModel('Notifications');
        $productionline = $this->Productionline->newEmptyEntity();

        // exit;

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            $vendorMaterialCode = $requestData['vendor_material_code'];
           // print_r($vendorMaterialCode);exit;

            $VendorMaterials = $this->paginate($this->VendorMaterial->find('all', [
                'conditions' => ['VendorMaterial.vendor_material_code' => $vendorMaterialCode]
            ]))->first();

            $requestData['vendor_id'] = $VendorMaterials->vendor_id;
            $requestData['vendormaterial_id'] = $VendorMaterials->id;
            $requestData['status'] = 0;

            $session = $this->getRequest()->getSession();
            $sapVendor = $session->read('vendor_code');
            $buyer = $this->VendorTemps->find()
            ->select(['buyer_id'])
            ->where(['sap_vendor_code' => $sapVendor])
            ->first();



            $productionline = $this->Productionline->patchEntity($productionline, $requestData);
            if ($this->Productionline->save($productionline)) {

                if ($this->Notifications->exists(['Notifications.user_id' => $buyer->buyer_id, 'Notifications.notification_type' => 'production_line'])) {
                    $this->Notifications->updateAll(
                        ['message_count' => $this->Notifications->query()->newExpr('message_count + 1')],
                        ['user_id' => $buyer->buyer_id, 'notification_type' => 'production_line']
                    );
                } else {
                    $notification = $this->Notifications->newEmptyEntity();
                    $notification->user_id = $buyer->buyer_id;
                    $notification->notification_type = 'production_line';
                    $notification->message_count = 1;
                    $this->Notifications->save($notification);
                } 

                $this->Flash->success(__('The productionline has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The productionline could not be saved. Please, try again.'));
        }
        
         $session = $this->getRequest()->getSession();
        $vendorId = $session->read('id');
        $vendor_mateial = $this->VendorMaterial->find('list', [ 'conditions' => ['vendor_id' => $vendorId],'keyField' => 'id', 'valueField' => 'description'])->all();


        $this->set(compact('productionline','vendor_mateial'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Productionline id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel("VendorMaterial");
        $productionline = $this->Productionline->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productionline = $this->Productionline->patchEntity($productionline, $this->request->getData());

            if ($this->Productionline->save($productionline)) {
                $this->Flash->success(__('The productionline has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The productionline could not be saved. Please, try again.'));
        }

        $session = $this->getRequest()->getSession();
        $vendorId = $session->read('id');


        $vendor_mateial = $this->VendorMaterial->find('list', [ 'conditions' => ['vendor_id' => $vendorId],'keyField' => 'id', 'valueField' => 'description'])->all();

        $this->set(compact('productionline','vendor_mateial'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Productionline id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productionline = $this->Productionline->get($id);
        if ($this->Productionline->delete($productionline)) {
            $this->Flash->success(__('The productionline has been deleted.'));
        } else {
            $this->Flash->error(__('The productionline could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
