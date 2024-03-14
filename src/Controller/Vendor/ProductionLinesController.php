<?php
declare(strict_types=1);

namespace App\Controller\Vendor;


use App\Model\Table\VendorMaterialTable;
use Cake\Datasource\ConnectionManager;

/**
 * Productionline Controller
 *
 * @property \App\Model\Table\ProductionlineTable $Productionline
 * @method \App\Model\Entity\Productionline[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductionLinesController extends VendorAppController
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

        $this->loadModel("ProductionLines");
        $this->loadModel('Materials');
        $this->loadModel('LineMasters');
        $session = $this->getRequest()->getSession();

        $lines = $this->ProductionLines->find('all', [
            'conditions' => ['ProductionLines.sap_vendor_code' => $session->read('vendor_code')]
        ])->select(['LineMasters.name', 'LineMasters.id'])
        ->distinct(['LineMasters.name', 'LineMasters.id'])->contain(['LineMasters', 'Materials'])->toArray();

        $materials = $this->ProductionLines->find('all', [
            'conditions' => ['ProductionLines.sap_vendor_code' => $session->read('vendor_code')]
        ])->select(['Materials.code', 'Materials.description'])
        ->distinct(['Materials.code', 'Materials.description'])->contain(['LineMasters', 'Materials'])->toArray();

        $this->set(compact('materials', 'lines'));

       //echo '<pre>';  print_r($productionline);exit;

    }

    public function prdordlist(){
        $this->autoRender = false;
        $this->loadModel("ProductionLines");
        $this->loadModel('Materials');
        $this->loadModel('LineMasters');

        $session = $this->getRequest()->getSession();
        $vendorId = $session->read('vendor_code');
        $response = array('status'=>0, 'message'=>'fail', 'data'=>'');

        $conditions = " where 1=1 and production_lines.sap_vendor_code='".$session->read('vendor_code')."' ";
        if ($this->request->is(['patch', 'post', 'put'])) {
            $request = $this->request->getData();
            if(isset($request['lines'])) {
                $search = '';
                foreach ($request['lines'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                $conditions .= " and production_lines.line_master_id in (".$search.")";
            }
            if(isset($request['material'])) {
                $search = '';
                foreach ($request['material'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                if(!isset($request['lines'])){ $conditions .= " and materials.code in (".$search.")"; }
                else{ $conditions .= " and materials.code in (".$search.")"; }
            }
        }
        $conn = ConnectionManager::get('default');
        $material = $conn->execute("SELECT production_lines.id, line_masters.name, materials.code, materials.description, CONCAT(production_lines.capacity, ' ', materials.uom) as capacity
        FROM production_lines
        left join materials on materials.id = production_lines.material_id
        left join line_masters on line_masters.id = production_lines.line_master_id 
            and line_masters.sap_vendor_code = production_lines.sap_vendor_code". $conditions);
        
        // echo '<pre>'; print_r($material);exit;
        $materialist = $material->fetchAll('assoc');

        $results = [];
        foreach ($materialist as $mat) {
            $tmp = [];
            $tmp[] = $mat['name'];
            $tmp[] = $mat['code'];
            $tmp[] = $mat['description'];
            $tmp[] = $mat['capacity'];
            $results[] = $tmp;
        }

        $response = array('status'=>1, 'message'=>'success', 'data'=>$results);
        echo json_encode($response); exit;
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
        $session = $this->getRequest()->getSession();
        $sapVendor = $session->read('vendor_code');
        $this->loadModel('VendorFactories');
        $factory = $this->VendorFactories->find('list',['keyField' => 'id', 'valueField' => 'factory_code'])
        ->where(['vendor_temp_id' => $session->read('vendor_id')]);

        $flash = [];
        $this->loadModel("LineMasters");
        $this->loadModel("Materials");
        $this->loadModel("VendorTemps");
        $this->loadModel('Notifications');
        $this->loadModel('StockUploads');
        
        $productionline = $this->ProductionLines->newEmptyEntity();

        // exit;

        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            
            $requestData['sap_vendor_code'] = $sapVendor;

            
            /*$buyer = $this->VendorTemps->find()
            ->select(['buyer_id'])
            ->where(['sap_vendor_code' => $sapVendor])
            ->first(); */

            $productionline = $this->ProductionLines->patchEntity($productionline, $requestData);
            if ($this->ProductionLines->save($productionline)) {

                /*if ($this->Notifications->exists(['Notifications.user_id' => $buyer->buyer_id, 'Notifications.notification_type' => 'production_line'])) {
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
                }  */

                $flash = ['type'=>'success', 'msg'=>'The productionline has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            //echo '<pre>'; print_r($productionline); exit;
            $flash = ['type'=>'error', 'msg'=>'The productionline could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        
        $vendor_mateial = [];
        $lineMasterList = [];

        $this->set(compact('productionline','vendor_mateial', 'lineMasterList','factory'));
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
        $flash = [];
        $this->loadModel("Materials");
        $productionline = $this->ProductionLines->get($id, [
            'contain' => ['LineMasters'],
        ]);

        //echo '<pre>'; print_r($productionline); exit;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productionline = $this->ProductionLines->patchEntity($productionline, $this->request->getData());

            if ($this->ProductionLines->save($productionline)) {
                $flash = ['type'=>'success', 'msg'=>'The productionline has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The productionline could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }

        $session = $this->getRequest()->getSession();
        $vendorId = $session->read('id');

        $vendor_mateial = $this->Materials->find('list', [ 'conditions' => ['sap_vendor_code' => $session->read('vendor_code')],'keyField' => 'id', 'valueField' => 'code'])->all();

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
        $flash = [];
        $this->request->allowMethod(['post', 'delete']);
        $productionline = $this->Productionline->get($id);
        if ($this->Productionline->delete($productionline)) {
            $flash = ['type'=>'success', 'msg'=>'The productionline has been deleted'];
        } else {
            $flash = ['type'=>'error', 'msg'=>'The productionline could not be deleted. Please, try again'];
        }
        $this->set('flash', $flash);

        return $this->redirect(['action' => 'index']);
    }

    public function checkRecordExists() {
        $this->autoRender = false;
        
        $session = $this->getRequest()->getSession();
        $sapVendor = $session->read('vendor_code');
        
        $response['status'] = 0;
        $response['message'] = 'Empty request';
        
        if ($this->request->is(['patch', 'post', 'put', 'ajax'])) {
            $request = $this->getRequest()->getData();
            if($this->ProductionLines->exists(['sap_vendor_code' => $sapVendor, 'material_id' => $request['material'], 'line_master_id' => $request['line']])) {
                $response['status'] = 0;
                $response['message'] = 'Material already mapped to selected production line';
            } else {
                $response['status'] = 1;
                $response['message'] = 'Valid' ;
            }
        } 
        

        echo json_encode($response);
    }


    public function getLineMaterials($lineMasterId = null) {
        $this->autoRender = false;
        
        $session = $this->getRequest()->getSession();
        $sapVendor = $session->read('vendor_code');
        
        $materialList = $this->ProductionLines->find()
        ->select(['id','capacity', 'Materials.id', 'Materials.code', 'Materials.description'])
        ->contain(['Materials'])
        ->where(['line_master_id' => $lineMasterId]);

        $materials = [];
        foreach($materialList as $mat) {
            $materials[] = ['prod_line' => $mat->id,'id' => $mat->material->id, 'code' => $mat->material->code, 'description' => $mat->material->description, 'capacity' => $mat->capacity];
        }

        $response['status'] = 1;
        $response['data']['materials'] = $materials;

        echo json_encode($response);exit();
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
                    $this->loadModel("LineMasters");
                    $this->loadModel('Materials');

                    for ($row = 2; $row <= $highestRow; ++$row) {
                        
                        $facError = false;
                        $lineError = false;
                        $matError = false;
                        
                        $tmp['sap_vendor_code']  = $session->read('vendor_code');
                        
                        $datas = [];
                        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            if($col == 1) {
                                $factory = $this->VendorFactories->find('list')
                                ->select(['id'])
                                ->where(['factory_code' => $value, 'vendor_temp_id' => $session->read('vendor_id')])
                                ->first();
                                $tmp['vendor_factory_id'] = $factory ? $factory : null;
                                $datas['factory_code'] = $value;
                                if(!$factory) {
                                    $facError = true;
                                }

                            } else if($col == 2) {
                                if(!$facError){
                                    $lineData = $this->LineMasters->find()
                                    ->where(['vendor_factory_id' => $tmp['vendor_factory_id'], 'name' => trim($value)])
                                    ->first();

                                    //echo '<pre>'; print_r($lineData); exit;
                                    $tmp['line_master_id'] = $lineData ? $lineData->id : null;
                                    if(!$lineData) {
                                        $lineError = true;
                                    }
                                }

                                $datas['line'] = $value;

                            }else if($col == 3) {
                                $materials = $this->Materials->find('all')
                                ->select(['id', 'code'])
                                ->where(['code' => $value, 'sap_vendor_code' => $tmp['sap_vendor_code']])->first();
    
                                $tmp['material_id'] = $materials['id'] ? $materials['id'] : null;
                                $datas['material'] = $value;
                                if(!$materials['id']) {
                                    $matError = true;
                                    $datas['error'] = 'Invalid material';
                                }
                            }else if($col == 4) {
                                $tmp['capacity'] = $value;
                                $datas['capacity'] = $value;
                            }
                            
                        }

                        $datas['error'] = '';
                        if($facError) {
                            $datas['error'] = 'Invalid factory code';
                        } 
                        if($matError) {
                            $datas['error'] = 'Invalid material code';
                        } 
                        if($lineError) {
                            $datas['error'] = 'Invalid Line';
                        } 

                        $stockData[] = $datas;
                        if(empty($datas['error'])) {
                            $uploadData[] = $tmp;   
                        }
                    }

                   //echo '<pre>'; print_r($uploadData);exit;
                   if(!empty($uploadData)) {
                        $columns = array_keys($uploadData[0]);
                        $upsertQuery = $this->ProductionLines->query();
                        $upsertQuery->insert($columns);

                        foreach($uploadData as $row) {
                            $upsertQuery->values($row);
                        }
                        $upsertQuery->epilog('ON DUPLICATE KEY UPDATE `capacity`=VALUES(`capacity`)')
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
}
