<?php
declare(strict_types=1);

namespace App\Controller\Vendor;

class LineMastersController extends VendorAppController
{
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];  
        $this->loadModel('LineMasters');
        $this->set('flash', $flash);
    }

    public function index()
    {
        $session = $this->getRequest()->getSession();
        $lineMasters = $this->LineMasters->find('all', [
            'conditions' => ['LineMasters.sap_vendor_code' => $session->read('vendor_code')]
        ])->contain(['VendorFactories'])->toArray();

     //  echo "<pre>"; print_r($lineMasters);exit;
        
        // $lineMasters = $this->paginate($this->LineMasters);

        $this->set(compact('lineMasters'));
    }

    public function view($id = null)
    {
        $lineMaster = $this->LineMasters->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('lineMaster'));
    }

    public function add()
    {
        $flash = [];
        $this->loadModel('VendorFactories');
        $this->loadModel("Materials");
        $this->loadModel("Uoms");
        $session = $this->getRequest()->getSession();
        $lineMaster = $this->LineMasters->newEmptyEntity();
        
        $uom = $this->Uoms->find('list',['keyField' => 'code', 'valueField' => function ($row) {
            return $row->code.' - '.$row->description;
        }]);
        $factory = $this->VendorFactories->find('list',['keyField' => 'id', 'valueField' => 'factory_code'])->
        where(['vendor_temp_id' => $session->read('vendor_id')]);
        if ($this->request->is('post')) {
            $lineMaster = $this->LineMasters->patchEntity($lineMaster, $this->request->getData());
            $lineMaster->sap_vendor_code = $session->read('vendor_code');
             //echo '<pre>'; print_r($lineMaster); exit;
            try {
                if ($this->LineMasters->save($lineMaster)) {
                    $flash = ['type'=>'success' , 'msg'=>'The line master has been saved'];
                    return $this->redirect(['action' => 'index']);
                }
                $flash = ['type'=>'error', 'msg'=>'The line master could not be saved. Please, try again.'];
            } catch (\PDOException $e) {
                $this->Flash->error(__($e->getMessage()));
            } catch (\Exception $e) {
                $this->Flash->error(__($e->getMessage()));
            }
        }
        $this->set('flash', $flash);
        $this->set(compact('lineMaster','uom','factory'));
    }

    public function edit($id = null)
    {
        $lineMaster = $this->LineMasters->get($id);
          $this->loadModel('VendorFactories');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $lineMaster = $this->LineMasters->patchEntity($lineMaster, $this->request->getData());
            if ($this->LineMasters->save($lineMaster)) {
                $flash = ['type'=>'success' , 'msg'=>'The line master has been saved'];
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The line master could not be saved. Please, try again.'));
        }
        $factory = $this->VendorFactories->find('list',['keyField' => 'id', 'valueField' => 'factory_code']);
        $this->set(compact('lineMaster','factory'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $lineMaster = $this->LineMasters->get($id);
        if ($this->LineMasters->delete($lineMaster)) {
            // $this->Flash->success(__('The line master has been deleted.'));
            $flash = ['type'=>'success' , 'msg'=>'The line master has been deleted'];
        } else {
            $this->Flash->error(__('The line master could not be deleted. Please, try again.'));
        }

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
                if (isset($_FILES['upload_file']) && $_FILES['upload_file']['name'] != "" && isset($_FILES['upload_file']['name'])) {
                    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($_FILES['upload_file']['tmp_name']);
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $highestRow = $worksheet->getHighestRow(); 
                    $highestColumn = $worksheet->getHighestColumn();
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

                    $tmp = [];
                    $this->loadModel("VendorFactories");

                    for ($row = 2; $row <= $highestRow; ++$row) {
                        
                        $tmp['sap_vendor_code']  = $session->read('vendor_code');
                        $facError = false;
                        $datas = [];
                        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            if($col == 1) {
                                $tmp['name'] = $value;
                                $datas['name'] = $value;
                            } else if($col == 2) {
                                $factory = $this->VendorFactories->find('list')
                                ->select(['id'])
                                ->where(['factory_code' => $value, 'vendor_temp_id' => $session->read('vendor_id')])
                                ->first();
                                $tmp['vendor_factory_id'] = $factory ? $factory : null;
                                $datas['factory_code'] = $value;
                                if(!$factory) {
                                    $facError = true;
                                }

                            } else if($col == 3) {
                                $tmp['capacity'] = $value;
                                $datas['capacity'] = $value;
                            } else {
                                $tmp['uom'] = $value;
                                $datas['uom'] = $value;
                            }
                            
                        }

                        $datas['error'] = '';
                        if($facError) {
                            $datas['error'] = 'Invalid factory code';
                        } 

                        $stockData[] = $datas;
                        if(empty($datas['error'])) {
                            $uploadData[] = $tmp;   
                        }
                    }

                   // print_r($uploadData);exit;
                   if(!empty($uploadData)) {
                        $columns = array_keys($uploadData[0]);
                        $upsertQuery = $this->LineMasters->query();
                        $upsertQuery->insert($columns);

                        foreach($uploadData as $row) {
                            $upsertQuery->values($row);
                        }
                        $upsertQuery->epilog('ON DUPLICATE KEY UPDATE `capacity`=VALUES(`capacity`), `uom`=VALUES(`uom`)')
                            ->execute();
                    }

                    $response['status'] = 1;
                    $response['message'] = 'uploaded Successfully';
                    $response['data'] = $stockData;
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

    public function getDetail($id = null)
    {
        $this->autoRender = false;
        $this->loadModel('ProductionLines');
        $total = 0;
        $response = ['status' => 1, 'message' => ''];
        $lineMaster = $this->LineMasters->get($id);
        $totalResult = $this->ProductionLines->find()
        ->select(['total' => 'sum(capacity)'])
        ->where(['line_master_id' => $id])->first();
        
        $response['data']['capacity'] = $lineMaster->capacity;
        if(!$totalResult->isEmpty('total')) {
            $total = $totalResult->total;
        }
        
        $response['data']['total'] = $total;
        $response['data']['balance'] = $lineMaster->capacity - $total;

        echo json_encode($response); exit;
    }

    public function getFactoryLines($factoryId = null) {
        $this->autoRender = false;
        $this->loadModel("LineMasters");
        $this->loadModel("Materials");
        $this->loadModel("StockUploads");

        $session = $this->getRequest()->getSession();
        $sapVendor = $session->read('vendor_code');
        
        $lineMasterList = $this->LineMasters->find('all')
        ->select(['id', 'name'])
        ->where(['sap_vendor_code' => $sapVendor, 'vendor_factory_id' => $factoryId]);

        /*$materialList = $this->StockUploads->find('all')
        ->select(['Materials.id', 'Materials.code', 'Materials.description'])
        ->contain(['Materials'])
        ->where(['StockUploads.sap_vendor_code' => $sapVendor, 'vendor_factory_id' => $factoryId]);
        */

        $materialList = $this->Materials->find('all')
        ->select(['id', 'code', 'description'])
        ->where(['sap_vendor_code' => $sapVendor]);


        $materials = [];
        foreach($materialList as $mat) {
            $materials[] = ['id' => $mat->id, 'code'=>$mat->code, 'description' => $mat->code .' - '. $mat->description];
        }

        $response['status'] = 0;
        $response['message'] = 'Lines not found';
        if($lineMasterList) {
            $response['status'] = 1;
            $response['message'] = 'Lines found';
            $response['data']['lines'] = $lineMasterList;
            $response['data']['materials'] = $materials;
        }

        echo json_encode($response);exit();
    }
}
