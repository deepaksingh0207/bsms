<?php
declare(strict_types=1);

namespace App\Controller\Buyer;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;

/**
 * Buyer/PrHeaders Controller
 *
 * @method \App\Model\Entity\Buyer/PrHeader[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PurchaseRequisitionsController extends BuyerAppController
{
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];  
        $this->set('flash', $flash);
    }
    
    var $uses = array('PrHeaders');
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('headTitle', 'Purchase Requisitions List');
        $this->loadModel('PrHeaders');
        $prHeaders = $this->paginate($this->PrHeaders);

        $this->set(compact('prHeaders'));
    }

    /**
     * View method
     *
     * @param string|null $id Buyer/pr Header id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->set('headTitle', 'PR Detail');
        $this->loadModel('PrHeaders');
        $prHeader = $this->PrHeaders->get($id, [
            'contain' => ['PrFooters'],
        ]);

        $this->set(compact('prHeader'));
    }

    public function createRfq($id = null)
    {
        $flash = [];
        $this->set('headTitle', 'Create RFQ');
        $session = $this->getRequest()->getSession();
        $this->loadModel('PrHeaders');
        $this->loadModel('VendorTemps');
        $prHeader = $this->PrHeaders->get($id, [
            'contain' => ['PrFooters'],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            try {
                $this->loadModel('Rfqs');
                $this->loadModel('RfqItems');
                $this->loadModel('RfqCommunications');
                //echo '<pre>'; print_r($this->request->getData()); exit;

                $buyerId = $session->read('id');
                $prHeaderId = $this->request->getData('pr_header_id');
                $vendorIds = $this->request->getData('Suppliers');
                $prFooterIds = $this->request->getData('item');
                $message = $this->request->getData('Comments');

                $data = array();
                foreach($vendorIds as $vendorId) {
                    $conn = ConnectionManager::get('default');
                    $maxrfq = $conn->execute("SELECT MAX(rfq_no) maxrfq FROM rfqs");

                    foreach ($maxrfq as $maxid) {
                        $maxRfqId = $maxid['maxrfq'] + 1; 
                    }

                    $rfqData = array();
                    $newRfq = $this->Rfqs->newEmptyEntity();
                    $rfqData['rfq_no'] = $maxRfqId;
                    $rfqData['buyer_id'] = $buyerId;
                    $rfqData['vendor_temp_id'] = $vendorId;
                    $rfqData['pr_header_id'] = $prHeaderId;
                    $rfq= $this->Rfqs->patchEntity($newRfq, $rfqData);

                    if ($this->Rfqs->save($rfq)) {

                        $data = array();
                        foreach($prFooterIds as $prFooterId) {
                            $record = array();
                            $record['rfq_id'] = $rfq->id;;
                            $record['pr_footer_id'] = $prFooterId;
                            $data[] = $record;
                        }
                        $RfqRecords = $this->RfqItems->newEntities($data);
                        if($this->RfqItems->saveMany($RfqRecords)) {
                                
                        } 

                        $comm = array();
                        $comm['rfq_id'] = $rfq->id;
                        $comm['buyer_id'] = $buyerId;
                        $comm['message'] = $message;

                        $newRfqComm = $this->RfqCommunications->newEmptyEntity();
                        $rfqCommunication= $this->RfqCommunications->patchEntity($newRfqComm, $comm);

                        if ($this->RfqCommunications->save($rfqCommunication)) {
                        }
                    }
                }
             
                $flash = ['type'=>'error', 'msg'=>'The RFQ has been saved'];
                $this->set('flash', $flash);
                return $this->redirect(['action' => 'index']);
            } catch (\PDOException $e) {
                $flash = ['type'=>'error', 'msg'=>($e->getMessage())];
            }  catch (\Exception $e ) {
                $flash = ['type'=>'error', 'msg'=>($e->getMessage())];
            }    
            $this->set('flash', $flash);
        }

        $vendors = $this->VendorTemps->find('list', ['limit' => 200])->toArray();

        $this->set(compact('prHeader', 'vendors'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $flash = [];
        $prHeader = $this->PrHeaders->newEmptyEntity();
        if ($this->request->is('post')) {
            $prHeaders = $this->PrHeaders->patchEntity($prHeader, $this->request->getData());
            if ($this->PrHeaders->save($prHeader)) {
                $flash = ['type'=>'success', 'msg'=>'The buyer/pr header has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'success', 'msg'=>'The buyer/pr header could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $this->set(compact('prHeader'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Buyer/pr Header id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $flash = [];
        $prHeaders = $this->PrHeaders->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $prHeaders = $this->PrHeaders->patchEntity($prHeader, $this->request->getData());
            if ($this->PrHeaders->save($prHeader)) {
                $flash = ['type'=>'success', 'msg'=>'The buyer/pr header has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The buyer/pr header could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $this->set(compact('buyer/prHeader'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Buyer/pr Header id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $flash = [];
        $this->request->allowMethod(['post', 'delete']);
        $prHeader = $this->PrHeaders->get($id);
        if ($this->PrHeaders->delete($prHeader)) {
            $flash = ['type'=>'success', 'msg'=>'The buyer/pr header has been deleted'];
        } else {
            $flash = ['type'=>'error', 'msg'=>'The buyer/pr header could not be deleted. Please, try again'];
        }
        $this->set('flash', $flash);

        return $this->redirect(['action' => 'index']);
    }
}
