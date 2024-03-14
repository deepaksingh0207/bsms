<?php

declare(strict_types=1);

namespace App\Controller\Vendor;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;

class DailymonitorController extends VendorAppController
{
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];  
        $this->set('flash', $flash);
    }
    
    public function index()
    {
        $session = $this->getRequest()->getSession();
        $vendorId = $session->read('id');
        $this->loadModel('Materials');
        $this->loadModel('VendorTypes');
        $this->loadModel("ProductionLines");
        $this->loadModel("LineMasters");

        $conditions = ' where dailymonitor.sap_vendor_code="'.$session->read('vendor_code').'" ';
        if ($this->request->is(['patch', 'post', 'put', 'ajax'])) {
            $request = $this->request->getData();
            if(isset($request['material'])) {
                $search = '';
                foreach ($request['material'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                $conditions .= " and materials.id in (".$search.")";
            }
            if(isset($request['line'])) {
                $search = '';
                foreach ($request['line'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                $conditions .= " and line_masters.id in (".$search.")";
            }
            if(isset($request['plan_date']) && !empty($request['plan_date'])) {
                $search = $request['plan_date'];
                $conditions .= " and dailymonitor.plan_date='".$search."'";
            }
        }
        $prd_lines = $this->LineMasters->find('all')->where(['sap_vendor_code="'.$session->read('vendor_code').'"' ])->toArray();
        
        $conn = ConnectionManager::get('default');
        $query = $conn->execute('select  distinct materials.id, materials.code, materials.description from dailymonitor
        left join materials on materials.id = dailymonitor.material_id
        where materials.sap_vendor_code = "'.$session->read('vendor_code').'"');
        $materials = $query->fetchAll('assoc');
        
        // $materials = $this->Materials->find('all')->where(['sap_vendor_code="'.$session->read('vendor_code').'"' ])->toArray();

        $query = $conn->execute('select dailymonitor.id, dailymonitor.plan_date, vendor_factories.factory_code, line_masters.name as production_line_id, materials.code as material_id,
        materials.description as material, materials.uom, dailymonitor.target_production, dailymonitor.confirm_production,
        case when dailymonitor.status = 2 then "Cancelled" else case when dailymonitor.status = 3 then "Production Confirmed" else "Active" end end as status
        from dailymonitor
        left join production_lines on production_lines.id = dailymonitor.production_line_id
        left join line_masters on line_masters.id = production_lines.line_master_id
        left join vendor_factories on vendor_factories.id = production_lines.vendor_factory_id
        left join materials on materials.id = dailymonitor.material_id'. $conditions);
        $dailymonitor = $query->fetchAll('assoc');
        // echo '<pre>';  print_r($query); exit;
        
        if ($this->request->is(['patch', 'post', 'put', 'ajax'])) {
            $results = [];
            foreach ($dailymonitor as $mat) {
                $tmp = [];
                $tmp[] = $mat["factory_code"];
                $tmp[] = $mat["production_line_id"];
                $tmp[] = $mat["material_id"];
                $tmp[] = $mat["material"];
                $tmp[] = $mat["target_production"];
                $tmp[] = $mat["uom"];
                $tmp[] = date("d-m-Y", strtotime($mat["plan_date"]));;
                $tmp[] = $mat["confirm_production"];
                $tmp[] = $mat["status"];
                if ($mat["status"] == 'Active'){
                    $url = Router::url(['controller' => '/dailymonitor', 'action' => 'edit', $mat["id"]]);
                    $tmp[] = '<a class="btn btn-info btn-sm mb-0" href="'.$url.'">Edit</a>';
                } else { $tmp[] = ""; }
                $results[] = $tmp;
            }
            $this->autoRender = false;
            $response = array('status'=>1, 'message'=>'success', 'data'=>$results);
            echo json_encode($response); exit;
        }

        $this->set(compact('dailymonitor', 'materials', 'prd_lines'));
        
    }

    public function view($id = null)
    {
        $dailymonitor = $this->Dailymonitor->get($id);
        $this->set(compact('dailymonitor'));
    }

    public function confirmedproduction($id=null, $confirm_production=null)
    {
        $this->loadModel("StockUploads");
        $session = $this->getRequest()->getSession();

        $response = ['status'=>0,'message'=>''];
        $dailymonitor = $this->Dailymonitor->get($id);
        $dailymonitor->confirm_production = $confirm_production;
        $dailymonitor->status= 3;
        
        $hasStock = $this->StockUploads->find()
                            ->where([
                                'sap_vendor_code' => $session->read('vendor_code'),
                                'material_id' => $dailymonitor->material_id
                            ])->count();

        if($hasStock) {
            if ($this->Dailymonitor->save($dailymonitor)) { 
            
                $stockUpload = $this->StockUploads->find()
                    ->where([
                        'sap_vendor_code' => $session->read('vendor_code'),
                        'material_id' => $dailymonitor->material_id
                    ])
                    ->first();
    
                $stockUpload->current_stock = $stockUpload->current_stock + $confirm_production;
                $stockUpload->production_stock = $stockUpload->production_stock + $confirm_production;
                $this->StockUploads->save($stockUpload);
    
                $response = ['status' => 1, 'message' => "Production confirmed successfully"];
            } else {
                $response = ['status' => 0, 'message' => 'Failed'];
            }
            
        } else {
            $response = ['status' => 0, 'message' => 'Stock not found'];
        }

        
        echo json_encode($response);
        exit;
    }

    public function dailyentry()
    {
        $session = $this->getRequest()->getSession();
        $vendorId = $session->read('id');
        $this->loadModel('Materials');
        $this->loadModel("ProductionLines");
        $this->loadModel("VendorTemps");
        $this->loadModel("LineMasters");
        $this->loadModel("VendorFactories");

        $conditions = ' where dailymonitor.sap_vendor_code="'.$session->read('vendor_code').'" and dailymonitor.plan_date <="'.date('y-m-d').'"';
        if ($this->request->is(['patch', 'post', 'put', 'ajax'])) {
            $request = $this->request->getData();
            if(isset($request['material'])) {
                $search = '';
                foreach ($request['material'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                $conditions .= " and materials.id in (".$search.")";
            }
            if(isset($request['line'])) {
                $search = '';
                foreach ($request['line'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                $conditions .= " and line_masters.id in (".$search.")";
            }
            if(isset($request['factory'])) {
                $search = '';
                foreach ($request['factory'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                $conditions .= " and factory.id in (".$search.")";
            }
            if(isset($request['plan_date']) && !empty($request['plan_date'])) {
                $search = $request['plan_date'];
                $conditions .= " and dailymonitor.plan_date='".$search."'";
            }
        }

        $prd_lines = $this->LineMasters->find('all')->where(['sap_vendor_code="'.$session->read('vendor_code').'"' ])->toArray();
        $vendor = $this->VendorTemps->find('all')->where(['sap_vendor_code="'.$session->read('vendor_code').'"' ])->toArray();
        $vendor_fty = $this->VendorFactories->find('all')->where(['vendor_temp_id="'.$vendor[0]->id.'"' ])->toArray();

        $conn = ConnectionManager::get('default');
        $query = $conn->execute('select distinct materials.id, materials.code, materials.description from dailymonitor
        left join materials on materials.id = dailymonitor.material_id
        where materials.sap_vendor_code = "'.$session->read('vendor_code').'"');
        $materials = $query->fetchAll('assoc');

        // $materials = $this->Materials->find('all')->where(['sap_vendor_code="'.$session->read('vendor_code').'"' ])->toArray();

        $query = $conn->execute('select dailymonitor.id, vendor_factories.factory_code, line_masters.name, materials.code, materials.description, materials.uom, dailymonitor.plan_date, dailymonitor.target_production, dailymonitor.status, dailymonitor.confirm_production
        from dailymonitor
        left join production_lines on production_lines.id = dailymonitor.production_line_id
        left join vendor_factories on vendor_factories.id = production_lines.vendor_factory_id
        left join line_masters on line_masters.id = production_lines.line_master_id
        left join materials on materials.id = dailymonitor.material_id'. $conditions.' order by dailymonitor.plan_date desc');
        $dailymonitor = $query->fetchAll('assoc');

        if ($this->request->is(['patch', 'post', 'put', 'ajax'])) {
            $results = [];
            foreach ($dailymonitor as $mat) {
                $tmp = [];
                $tmp[] = $mat["factory_code"];
                $tmp[] = $mat["name"];
                $tmp[] = $mat["code"];
                $tmp[] = $mat["description"];
                $tmp[] = $mat["target_production"].'<input type="hidden" value="'.$mat["target_production"].'" id="plan_qty_'.$mat["id"].'" data-id="'.$mat["id"].'">';
                $tmp[] = $mat["uom"];
                $tmp[] = date("d-m-Y", strtotime($mat["plan_date"]));;
                if ($mat["status"] == 1){
                    $tmp[] = '<input type="number" maxlength="20" class="form-control form-control-sm confirm-input" id="confirmprd'.$mat["id"].'" data-id="'.$mat["id"].'"><span id="validationMessage'.$mat["id"].'" class="text-danger" style="display: none;"></span>';
                    $tmp[] = '<button class="btn btn-success save btn-sm mb-0" id="confirmsave'.$mat["id"].'" data-id="'.$mat["id"].'">Save</button>';
                } else if ($mat["status"] == 1){
                    $tmp[] = 'Plan Cancelled';
                    $tmp[] = '';
                } else {
                    $tmp[] = $mat["confirm_production"];
                    $tmp[] = '';
                }
                $results[] = $tmp;
            }
            $this->autoRender = false;
            $response = array('status'=>1, 'message'=>'success', 'data'=>$results);
            echo json_encode($response); exit;
        }
        
        $this->set(compact('dailymonitor', 'materials', 'prd_lines', 'vendor_fty'));
    }

    public function upload()
    {
        $this->loadModel("Materials");
        $this->loadModel("ProductionLines");
        $this->loadModel("LineMasters");
        $this->loadModel("VendorFactories");
        $this->loadModel("StockUploads");

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

                    for ($row = 2; $row <= $highestRow; $row++) {
                        $tmp['sap_vendor_code']  = $session->read('vendor_code');
                        $tmp['status'] = 1;
                        $status = true;
                        $facError = false;
                        $target = true;
                        $confirm = true;
                        $validDate = true;
                        $lm = null;
                        for ($col = 1; $col <= $highestColumnIndex; $col++) {
                            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            
                            if($col == 1) {
                                if($value) {
                                    $factory = $this->VendorFactories->find('list')
                                    ->select(['id'])
                                    ->where(['factory_code' => $value, 'vendor_temp_id' => $session->read('vendor_id')])
                                    ->first();
                                    $tmp['vendor_factory_id'] = $factory ? $factory : null;
                                    $datas['factory_code'] = $value;
                                    if(!$factory) { $facError = true; }
                                } else { $facError = true; }
                            } 
                            
                            if ($col == 2) {
                                if(!$facError) {
                                    $lm = $this->LineMasters->find()
                                        ->where([
                                            'sap_vendor_code' => $session->read('vendor_code'),
                                            'vendor_factory_id' => $tmp['vendor_factory_id'],
                                            'name' => $value
                                        ])->first();
                                }
                                $datas['line'] = $value;
                            }

                            if ($col == 3) {
                                $mat = $this->Materials->find()
                                    ->where([
                                        'sap_vendor_code' => $session->read('vendor_code'),
                                        'code' => $value
                                    ])->first();
                                
                                $tmp['material_id'] = $mat ? $mat->id : null;
                                $datas['material'] = $value;
                                $datas['material_description'] = $mat ? $mat->description : '-';
                                $datas['uom'] = $mat ? $mat->uom : '-';

                                if ($lm) {
                                    $pl = $this->ProductionLines->find()
                                        ->where([
                                            'sap_vendor_code' => $session->read('vendor_code'),
                                            'line_master_id' => $lm->id,
                                            'vendor_factory_id' => $tmp['vendor_factory_id'],
                                            'material_id' => $tmp['material_id']
                                        ])->first();
                                    $tmp['production_line_id'] = $pl ? $pl->id : null;
                                }
                                unset($tmp['vendor_factory_id']);

                            }

                            if ($col == 4) {
                                $value = intval($value);
                                $tmp['target_production'] = $value;
                                $datas['target_production'] = $value;
                                if ($value < 1 || $value == "" || $value == null) {
                                    $target = false;
                                } 
                            }

                            if ($col == 5) {
                                $tmp['plan_date'] = date('Y-m-d', strtotime($value));
                                $datas['plan_date'] = date('d-m-Y', strtotime($value));
                            }

                            if($highestColumnIndex >= 6 && $col == 6) {
                                $value = intval($value);
                                $tmp['confirm_production'] = $value;
                                $datas['confirm_production'] = $value;
                                if ($value === "" || $value === null) {
                                    $confirm = false;
                                }
                            }
                        }
                        
                        $datas['error'] = '';
                        if($facError) { $datas['error'] = 'Invalid factory code'; }
                        if(!$target) { $datas['error'] = 'Invalid target value'; }
                        if($highestColumnIndex >= 6 && !$confirm) { $datas['error'] = 'Invalid confirm value'; }
                        if(!$validDate) { $datas['error'] = 'Only today\'s confirmation allowed'; }

                        if($highestColumnIndex >= 6) {
                            $cont = $this->Dailymonitor->find()->where(['sap_vendor_code' => $session->read('vendor_code'),
                            'production_line_id' => $tmp['production_line_id'],
                            'material_id' => $tmp['material_id'], 'plan_date' => $tmp['plan_date'], 'status' => 3])->count();
                            if($cont) { $datas['error'] = 'Production Already Confirmed'; }

                            $stockUpload = $this->StockUploads->find()
                            ->where([ 'sap_vendor_code' => $session->read('vendor_code'),
                                'material_id' => $tmp['material_id']
                            ])->count();

                            if(!$stockUpload) { $datas['error'] = 'Stock not found'; }
                        }

                        $planner[] = $datas;

                        if(empty($datas['error'])) { $uploadData[] = $tmp; }
                    }

                    // echo '<pre>'; print_r($uploadData);

                    if(!empty($uploadData)) {
                        if($highestColumnIndex >= 6) {
                            foreach($uploadData as $row) {
                                    $rec = $this->Dailymonitor->find()->where(['sap_vendor_code' => $row['sap_vendor_code'],
                                    'production_line_id' => $row['production_line_id'],
                                    'material_id' => $row['material_id'],
                                    'plan_date' => $row['plan_date']
                                    ])->first();
                                    
                                    if($rec) {
                                        $rec->confirm_production = $row['confirm_production'];
                                        $rec->status = 3;
                                    if($this->Dailymonitor->save($rec)) {
                                        $stockUpload = $this->StockUploads->find()
                                        ->where([
                                            'sap_vendor_code' => $session->read('vendor_code'),
                                            'material_id' => $row['material_id']
                                        ])
                                        ->first();
                                        $stockUpload->current_stock = $stockUpload->current_stock + $row['confirm_production'];
                                        $stockUpload->production_stock = $stockUpload->production_stock + $row['confirm_production'];
                                        $this->StockUploads->save($stockUpload);
                                    }
                                }
                            }
                        } else {
                            $columns = array_keys($uploadData[0]);
                            $upsertQuery = $this->Dailymonitor->query();
                            $upsertQuery->insert($columns);

                            foreach($uploadData as $row) {
                                $upsertQuery->values($row);
                            }
                            $upsertQuery->epilog('ON DUPLICATE KEY UPDATE `target_production`=VALUES(`target_production`)')
                            ->execute();
                        }
                    }

                    $response['status'] = 1;
                    $response['data'] = $planner;
                    $response['message'] = 'file uploaded successfully';

                } else {
                    $response['status'] = 0;
                    $response['message'] = 'file not uploaded';
                }
            } catch (\Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }
        }

        echo json_encode($response);
    }


    public function uploadPlan()
    {
        $this->loadModel("Materials");
        $this->loadModel("ProductionLines");
        $this->loadModel("LineMasters");
        $this->loadModel("VendorFactories");
        $this->loadModel("StockUploads");

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

                    for ($row = 2; $row <= $highestRow; $row++) {
                        $tmp['sap_vendor_code']  = $session->read('vendor_code');
                        $tmp['status'] = 1;
                        $status = true;
                        $facError = false;
                        $pldError = false;
                        $target = true;
                        for ($col = 1; $col <= $highestColumnIndex; $col++) {
                            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            if($col == 1) {
                                if($value) {
                                    $factory = $this->VendorFactories->find('list')
                                    ->select(['id'])
                                    ->where(['factory_code' => $value, 'vendor_temp_id' => $session->read('vendor_id')])
                                    ->first();
                                    $tmp['vendor_factory_id'] = $factory ? $factory : null;
                                    $datas['factory_code'] = $value;
                                    if(!$factory) {
                                        $facError = true;
                                    }
                                } else {
                                    $facError = true;
                                }

                            } 
                            if ($col == 2) {
                                if(!$facError) {
                                    $lm = $this->LineMasters->find()
                                        ->where([
                                            'sap_vendor_code' => $session->read('vendor_code'),
                                            'vendor_factory_id' => $tmp['vendor_factory_id'],
                                            'name' => $value
                                        ])->first();
                                }
                                $datas['line'] = $value;
                            } 
                            if ($col == 3) {
                                $mat = $this->Materials->find()
                                    ->where([
                                        'sap_vendor_code' => $session->read('vendor_code'),
                                        'code' => $value
                                    ])->first();
                                
                                $tmp['material_id'] = $mat ? $mat->id : null;
                                $datas['material'] = $value;
                                $datas['material_description'] = $mat ? $mat->description : '-';
                                $datas['uom'] = $mat ? $mat->uom : '-';

                                if ($lm) {
                                    $pl = $this->ProductionLines->find()
                                        ->where([
                                            'sap_vendor_code' => $session->read('vendor_code'),
                                            'line_master_id' => $lm->id,
                                            'vendor_factory_id' => $tmp['vendor_factory_id'],
                                            'material_id' => $tmp['material_id']
                                        ])->first();

                                        $tmp['production_line_id'] = $pl ? $pl->id : null;
                                }
                                unset($tmp['vendor_factory_id']);

                            } 
                            if ($col == 4) {
                                $value = intval($value);
                                $tmp['target_production'] = $value;
                                $datas['target_production'] = $value;
                                if ($value < 1 || $value == "" || $value == null) {
                                    $target = false;
                                } 
                            }  
                            if ($col == 5) {
                                $tmp['plan_date'] = date('Y-m-d', strtotime($value));
                                $datas['plan_date'] = date('d-m-Y', strtotime($value));
                                if(date('Y-m-d', strtotime($value)) < date('Y-m-d')) {
                                    $pldError = true;
                                }
                            }
                        }
                        
                        $datas['error'] = '';
                        if($facError) {
                            $datas['error'] = 'Invalid factory code';
                        }
                        if($pldError) {
                            $datas['error'] = 'Past Date Not Allowed';
                        }
                        if(!$target) {
                            $datas['error'] = 'Invalid target value';
                        }

                        if(empty($datas['error'])) {
                            $cont = $this->Dailymonitor->find()->where(['sap_vendor_code' => $session->read('vendor_code'),
                            'production_line_id' => $tmp['production_line_id'],
                            'material_id' => $tmp['material_id'], 'plan_date' => $tmp['plan_date']])->count();

                            if($cont) {
                                $datas['error'] = 'Production Plan Exists';
                            }
                        }

                        $planner[] = $datas;

                        if(empty($datas['error'])) {
                            $uploadData[] = $tmp;   
                        }
                    }

                    if(!empty($uploadData)) {
                        $columns = array_keys($uploadData[0]);
                        $upsertQuery = $this->Dailymonitor->query();
                        $upsertQuery->insert($columns);

                        foreach($uploadData as $row) {
                            $upsertQuery->values($row);
                        }
                        $upsertQuery->epilog('ON DUPLICATE KEY UPDATE `target_production`=VALUES(`target_production`)')
                        ->execute();
                    }

                    $response['status'] = 1;
                    $response['data'] = $planner;
                    $response['message'] = 'file uploaded successfully';

                } else {
                    $response['status'] = 0;
                    $response['message'] = 'file not uploaded';
                }
            } catch (\Exception $e) {
                $response['status'] = 0;
                $response['message'] = $e->getMessage();
            }
        }

        echo json_encode($response);
    }

    public function add()
    {
        $session = $this->getRequest()->getSession();
        $vendorId = $session->read('vendor_id');

        
        $flash = [];
        $this->loadModel("Materials");
        $this->loadModel("LineMasters");
        $this->loadModel("Dailymonitor");
        $this->loadModel('VendorFactories');

        $factory = $this->VendorFactories->find('list', ['conditions' => ['vendor_temp_id' => $vendorId], 'keyField' => 'id', 'valueField' => 'factory_code'])->all();

        $dailymonitor = $this->Dailymonitor->newEmptyEntity();
        
        if ($this->request->is('post')) {
            try {
                $requestData = $this->request->getData();
                $requestData['sap_vendor_code'] = $session->read('vendor_code');
                $requestData['production_line_id'] = $requestData['prod_line'];
                $requestData['status'] = 1;
                if($requestData['plan_date'] < date('Y-m-d')) {
                    $flash = ['type' => 'error', 'msg' => 'Past date not allowed'];
                    $this->set('flash', $flash);
                } else {
                    $dailymonitor = $this->Dailymonitor->patchEntity($dailymonitor, $requestData);
                    if ($this->Dailymonitor->save($dailymonitor)) {
                        // echo '<pre>';  print_r($requestData); exit;
                      
                        $flash = ['type' => 'success', 'msg' => 'The dailymonitor has been saved'];
                        $this->set('flash', $flash);
                        return $this->redirect(['action' => 'index']);
                    }
                    // echo '<pre>';  print_r($dailymonitor); exit;
                    $flash = ['type' => 'error', 'msg' => 'The dailymonitor could not be saved. Please, try again'];
                }
            } catch (\Exception $e) {
                // $flash['msg'] = 0;
                // $flash['type'] = $e->getMessage();
                $flash = ['type' => 'error', 'msg' => 'Duplicate entry'];
            }
            $this->set('flash', $flash);
        }
        $this->set(compact('dailymonitor', 'factory'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dailymonitor id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $session = $this->getRequest()->getSession();
        $flash = [];
        $this->loadModel("Materials");
        $this->loadModel("LineMasters");
        $this->loadModel("ProductionLines");
        $dailymonitor = $this->Dailymonitor->get($id);
        $productionLine = $this->ProductionLines->find()->where(['id' => $dailymonitor->production_line_id])->first();
        $dailymonitor->line_id = $productionLine->line_master_id;
        

        if ($this->request->is(['patch', 'post', 'put'])) {
            $dailymonitor = $this->Dailymonitor->patchEntity($dailymonitor, $this->request->getData());
            if ($this->Dailymonitor->save($dailymonitor)) {
                $flash = ['type' => 'success', 'msg' => 'The dailymonitor has been saved'];
                $this->set('flash', $flash);
                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type' => 'success', 'msg' => 'The dailymonitor could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $vendor_mateial = $this->Materials->find('list', ['conditions' => ['Materials.sap_vendor_code' => $session->read('vendor_code')], 'keyField' => 'id', 'valueField' => 'description'])->all();
        $line = $this->LineMasters->find('list', ['conditions' => ['sap_vendor_code' => $session->read('vendor_code')], 'keyField' => 'id', 'valueField' => 'name'])->all();
        $this->set(compact('dailymonitor', 'vendor_mateial', 'line'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dailymonitor id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $flash = [];
        $this->request->allowMethod(['post', 'delete']);
        $dailymonitor = $this->Dailymonitor->get($id);
        if ($this->Dailymonitor->delete($dailymonitor)) {
            $flash = ['type' => 'success', 'msg' => 'The dailymonitor has been deleted'];
        } else {
            $flash = ['type' => 'error', 'msg' => 'The dailymonitor could not be deleted. Please, try again'];
        }

        $this->set('flash', $flash);
        return $this->redirect(['action' => 'index']);
    }

    public function changeStatus($action = null, $id = null)
    {
        $this->autoRender = false;
        $this->loadModel("Dailymonitor");

        $status = 1;
        if ($action && $action == 'cancel') {
            $status = 2;
        }

        $response = ['status' => 0, 'message' => ''];
        $dailymonitor = $this->Dailymonitor->get($id);
        $dailymonitor = $this->Dailymonitor->patchEntity($dailymonitor, ['status' => $status]);
        if ($this->Dailymonitor->save($dailymonitor)) {
            $response = ['status' => 1, 'message' => 'plant Successgully cancelled'];
        } else {
            $response = ['status' => 0, 'message' => 'Something went wrong'];
        }

        echo json_encode($response);
    }
}
