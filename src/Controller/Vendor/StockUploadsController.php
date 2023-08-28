<?php

declare(strict_types=1);

namespace App\Controller\Vendor;

use App\Model\Table\VendorMaterialTable;


/**
 * Stockupload Controller
 *
 * @property \App\Model\Table\StockuploadTable $Stockupload
 * @method \App\Model\Entity\Stockupload[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StockUploadsController extends VendorAppController
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

        $session = $this->getRequest()->getSession();
        $vendorId = $session->read('vendor_id');
        /*$stockupload = $this->StockUploads->find('all', [
            'conditions' => ['StockUploads.sap_vendor_code' => $session->read('vendor_code')]
        ])->select([
            'id', 'opening_stock', 'material_id', 'sap_vendor_code', 'added_date', 'updated_date',
            'vm_description' => 'vm.description', 'vm_vendor_code' => 'vm.code', 'vm.uom',
        ])->join([
            'table' => 'materials',
            'alias' => 'vm',
            'type' => 'LEFT',
            'conditions' => 'vm.id = StockUploads.material_id',
        ])->toArray(); */
        // echo '<pre>';print_r($stockupload);exit;

        $stockupload = $this->StockUploads->find('all')->contain(['Materials', 'VendorFactories'])
        ->where(['StockUploads.sap_vendor_code' => $session->read('vendor_code')])
        ->toArray();


        $this->set(compact('stockupload'));
    }

    /**
     * View method
     *
     * @param string|null $id Stockupload id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $stockupload = $this->StockUploads->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('stockupload'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $this->loadModel('Uoms');

        $flash = [];
        $this->loadModel("Materials");
        $this->loadModel("StockUploads");
        $this->loadModel("VendorFactories");
        $stockupload = $this->StockUploads->newEmptyEntity();

        $stockUpload = [];
        $stockView = [];
        $flash = [];
        $session = $this->getRequest()->getSession();
        $vendorId = $session->read('vendor_id');
        $sapVendor = $session->read('vendor_code');

        if ($this->request->is('post')) {
            $minivendor = [];
            $res = $this->request->getData();
            $res['sap_vendor_code'] = $sapVendor;
            $res['current_stock'] = $res['opening_stock'];
            $res['asn_stock'] = 0;

            
            $columns = array_keys($res);
            $upsertQuery = $this->StockUploads->query();
            $upsertQuery->insert($columns);
            $upsertQuery->values($res);
            if($upsertQuery->epilog('ON DUPLICATE KEY UPDATE sap_vendor_code=VALUES(sap_vendor_code), vendor_factory_id=VALUES(vendor_factory_id), `material_id`=VALUES(`material_id`),`opening_stock`=VALUES(`opening_stock`)')
                ->execute()) {
                $flash = ['type' => 'success', 'msg' => 'The stockupload has been saved'];
                
            } else {
                $flash = ['type' => 'error', 'msg' => 'The stockupload could not be saved. Please, try again'];
            }
            $this->set('flash', $flash);
            return $this->redirect(['action' => 'index']);
        }

        $vendor_mateial = $this->Materials->find('list', ['keyField' => 'id', 'valueField' => 'description'])->where(['sap_vendor_code' => $sapVendor])->all();
        $vendor_factory = $this->VendorFactories->find('list', ['keyField' => 'id', 'valueField' => 'factory_code'])->where(['vendor_temp_id' => $vendorId])->all();
        

        //echo '<pre>'; print_r($this->VendorFactories); exit;

        $this->set(compact('stockupload', 'vendor_mateial', 'vendor_factory'));
    }


    public function material($id = null)
    {
        $response = array();
        $response['status'] = 0;
        $response['message'] = '';
        $this->autoRender = false;

        if ($this->request->is(['patch', 'get', 'put'])) {
            $this->loadModel("Materials");
            $vendorMaterial = $this->Materials->find('all')
                ->select([
                    'id', 'sap_vendor_code', 'code', 'description', 'minimum_stock',
                    'uom'
                ])
                ->where(['Materials.id' => $id])
                ->first();

            $response['status'] = 1;
            $response['message'] = 'success';
            $response['data'] = $vendorMaterial;
        } else {
            $response['message'] = 'error';
        }

        echo json_encode($response);
    }



    /**
     * Edit method
     *
     * @param string|null $id Stockupload id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $flash = [];
        $this->loadModel("Materials");
        $stockupload = $this->StockUploads->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stockupload = $this->StockUploads->patchEntity($stockupload, $this->request->getData());
            if ($this->StockUploads->save($stockupload)) {
                $flash = ['type' => 'success', 'msg' => 'The stockupload has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type' => 'success', 'msg' => 'The stockupload could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $session = $this->getRequest()->getSession();
        $sapVendor = $session->read('vendor_code');
      //  print_r($sapVendor);

        $vendor_mateial = $this->Materials->find('list', ['conditions' => ['sap_vendor_code' => $sapVendor],'keyField' => 'id', 'valueField' => 'code'])->all();
    
        $this->set(compact('stockupload', 'vendor_mateial'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Stockupload id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $flash = [];
        $this->request->allowMethod(['post', 'delete']);
        $stockupload = $this->Stockupload->get($id);
        if ($this->Stockupload->delete($stockupload)) {
            $flash = ['type' => 'success', 'msg' => 'The stockupload has been deleted'];
        } else {
            $flash = ['type' => 'success', 'msg' => 'The stockupload could not be deleted. Please, try again'];
        }
        $this->set('flash', $flash);

        return $this->redirect(['action' => 'index']);
    }

    public function upload()
    {
        $response['status'] = 0;
        $response['message'] = 'upload fail';
        $this->autoRender = false;
       
        $session = $this->getRequest()->getSession();

        if ($this->request->is(['patch', 'post', 'put', 'ajax'])) {
            try {
            

                $uploadData = [];
                $stockData =[];
                if (isset($_FILES['upload_file']) && $_FILES['upload_file']['name'] != "" && isset($_FILES['upload_file']['name'])) {

                    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($_FILES['upload_file']['tmp_name']);
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = $worksheet->getHighestRow(); 
                    $highestColumn = $worksheet->getHighestColumn();
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
                    $this->loadModel("Materials");
                    $this->loadModel("VendorFactories");

                    $tmp = [];
                    $datas = [];
                    

                    // echo "<pre>";
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $facError = false;
                        $matError = false;
                        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                            
                            $tmp['sap_vendor_code'] = $session->read('vendor_code');
                            
                            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            if($col == 1) {
                                $factory = $this->VendorFactories->find('list')
                                ->select(['id'])
                                ->where(['factory_code' => $value])
                                ->first();

                                //echo '<pre>';  print_r($factory); exit;
                                $tmp['vendor_factory_id'] = $factory ? $factory : null;
                                $datas['factory_code'] = $value;
                                if(!$factory) {
                                    $facError = true;
                                }
                            } else if ($col ==2) {
                                $materials = $this->Materials->find('all')
                                ->select(['id', 'code'])
                                ->where(['code IN' => $value])->first();
    
                                $tmp['material_id'] = $materials['id'] ? $materials['id'] : null;
                                $datas['material'] = $value;
                                if(!$materials['id']) {
                                    $matError = true;
                                    $datas['error'] = 'Invalid material';
                                }
                            }
                            else if($col == 3) {
                            
                                $datas['description'] = $value;
                            }
                            else if($col == 4) {
                                $tmp['opening_stock'] = $value;
                                $datas['opening_stock'] = $value;
                                $tmp['current_stock'] = $value;
                            }
                            else if($col == 5) {
                                $datas['uom'] = $value;
                            }
                            
                        }

                        $datas['error'] = '';
                        if($facError) {
                            $datas['error'] = 'Invalid factory code';
                        } else if($matError) {
                            $datas['error'] = 'Invalid Material';
                        }

                        $stockData[] = $datas;
                        $tmp['asn_stock'] = 0;
                        if(empty($datas['error'])) {
                            $uploadData[] = $tmp;   
                        }
                    }

      
                    if(!empty($uploadData)) {
                        $columns = array_keys($uploadData[0]);
                        $upsertQuery = $this->StockUploads->query();
                        $upsertQuery->insert($columns);
                        foreach ($uploadData as $data) {
                            $upsertQuery->values($data);
                        }
                        $upsertQuery->epilog('ON DUPLICATE KEY UPDATE sap_vendor_code=VALUES(sap_vendor_code), vendor_factory_id=VALUES(vendor_factory_id), `material_id`=VALUES(`material_id`),`opening_stock`=VALUES(`opening_stock`)')
                            ->execute();
                    }


                        $response['status'] = 1;
                        $response['data'] = $stockData;
                        $response['message'] = 'uploaded Successfully';
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
