<?php

declare(strict_types=1);

namespace App\Controller\Buyer;

use App\Model\Table\VendorMaterialTable;
use Cake\Datasource\ConnectionManager;


/**
 * Stockupload Controller
 *
 * @property \App\Model\Table\StockuploadTable $Stockupload
 * @method \App\Model\Entity\Stockupload[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StockUploadsController extends BuyerAppController
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
        $this->loadModel("VendorTemps");
        $this->loadModel('VendorTypes');
        $this->loadModel('Materials');
        $materials = $this->Materials->find('all')->select(['id', 'code', 'description'])->distinct(['code'])->order(['code' => 'ASC'])->toArray();
        
        $segment = $this->Materials->find('all')->select(['segment'])->distinct(['segment'])->where(['segment IS NOT NULL' ])->toArray();
        $vendor = $this->VendorTemps->find('all')->select(['sap_vendor_code', 'name'])->distinct(['sap_vendor_code'])->where(['sap_vendor_code IS NOT NULL' ])->toArray();
        $vendortype = $this->Materials->find('all')->distinct(['type'])->where(['type IS NOT NULL' ])->toArray();
        $this->set(compact('materials', 'vendor', 'vendortype', 'segment'));
    }

    public function stocklist(){
        $this->autoRender = false;
        $this->loadModel("VendorTemps");
        $this->loadModel('VendorTypes');
        $this->loadModel('Materials');
        $response = array('status'=>0, 'message'=>'fail', 'data'=>'');

        $conditions = " where 1=1 ";
        if ($this->request->is(['patch', 'post', 'put'])) {
            $request = $this->request->getData();
            if(isset($request['vendor'])) {
                $search = '';
                foreach ($request['vendor'] as $mat) { $search .= "'" . $mat . "',"; }
                $search = rtrim($search, ',');
                $conditions .= " and vendor_temps.sap_vendor_code in (".$search.")";
            }
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
                $conditions .= " or materials.segment in (".$search.")";
                if(!isset($request['material']) and !isset($request['vendor']) and !isset($request['vendortype'])){ $conditions .= " and materials.segment in (".$search.")"; }
                else{ $conditions .= " and materials.segment in (".$search.")"; }
            }
            $conn = ConnectionManager::get('default');
        }
        
        $conn = ConnectionManager::get('default');
        $material = $conn->execute("SELECT
        vendor_temps.sap_vendor_code as 'v_code', vendor_factories.factory_code as 'f_code', '-' as 'po_no',
        materials.type as 'vt_id', materials.segment as 'mt_segment', '-' as 'line_item',
        materials.code as 'mt_code', materials.description as 'mt_description',
        stock_uploads.opening_stock, materials.uom as 'mt_uom' FROM stock_uploads
        left join vendor_temps on vendor_temps.sap_vendor_code = stock_uploads.sap_vendor_code
        left join materials on materials.id = stock_uploads.material_id
        left join vendor_factories on vendor_factories.id = stock_uploads.vendor_factory_id". $conditions);
        // echo '<pre>'; print_r($request);print_r($material);
        $materialist = $material->fetchAll('assoc');

        $results = [];
        foreach ($materialist as $mat) {
            $tmp = [];
            $tmp[] = $mat['v_code'];
            $tmp[] = $mat['f_code'];
            $tmp[] = $mat['po_no'];
            $tmp[] = $mat['vt_id'];
            $tmp[] = $mat['mt_segment'];
            $tmp[] = $mat['line_item'];
            $tmp[] = $mat['mt_code'];
            $tmp[] = $mat['mt_description'];
            $tmp[] = $mat['opening_stock'];
            $tmp[] = $mat['mt_uom'];
            $results[] = $tmp;
        }

        $response = array('status'=>1, 'message'=>'success', 'data'=>$results);
        echo json_encode($response); exit;
    }

    public function getvendorfactory($code)
    {
        // echo '<pre>'; print_r($code);exit;
        $this->autoRender = false;
        $this->loadModel('VendorFactories');
        $venfac = $this->VendorFactories->find('all')
        ->innerJoin(['VendorTemps'=> 'vendor_temps'], ['VendorTemps.id = VendorFactories.vendor_temp_id'])
        ->where(['VendorTemps.sap_vendor_code' =>$code])->toArray();
        $response = ['status' => 1, 'data' => $venfac];
        echo json_encode($response); exit();
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

    public function poststockupload()
    {
        $this->autoRender = false;
        $this->loadModel('Materials');
        $this->loadModel("StockUploads");
        $response = ['status' => 0, 'msg' => "Stock Upload Failed"];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $res = $this->request->getData();
            // echo '<pre>'; print_r($request);exit;
            if ($res["sap_vendor_code"] && $res["vendor_factory_id"] && $res["material_id"] && ($res["opening_stock"] >= 0))
            {
                $existingStockUpload = $this->StockUploads->find('all')->where(['material_id' => $res['material_id'], 'sap_vendor_code' => $res['sap_vendor_code'], 'vendor_factory_id' => $res["vendor_factory_id"]])->first();

                if (!$existingStockUpload) {
                    $stockupload = $this->StockUploads->newEmptyEntity();
                    $mslvalue = array();
                    $mslvalue['sap_vendor_code']  = $res['sap_vendor_code'];
                    $mslvalue['vendor_factory_id']  = $res['vendor_factory_id'];
                    $mslvalue['material_id']  = $res['material_id'];
                    $mslvalue['opening_stock']  = doubleval($res['opening_stock']);
                    $mslvalue['production_stock']  = 0;
                    $mslvalue['current_stock']  = 0;
                    $mslvalue['asn_stock']  = 0;
                    $stockupload = $this->StockUploads->patchEntity($stockupload, $mslvalue);
                    if ($this->StockUploads->save($stockupload))
                    { $response = ['status' => 1, 'msg' => "Stock Upload Updated"]; }
                    else { $response = ['status' => 0, 'msg' => "Stock Upload Update Failed"]; }
                }
                else { $response = ['status' => 0, 'msg' => "Stock Upload Exist"]; }
            }
        }
        echo json_encode($response); exit();
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
        $stockupload = $this->StockUploads->newEmptyEntity();

        $stockUpload = [];
        $stockView = [];
        $flash = [];
        $session = $this->getRequest()->getSession();
        $vendorId = $session->read('id');
        $sapVendor = $session->read('vendor_code');

        if ($this->request->is('post')) {

            $importFile = $this->request->getData('vendor_code');
            if (isset($_FILES['vendor_code']) && $_FILES['vendor_code']['name'] != "" && $importFile !== null && isset($_FILES['vendor_code']['name'])) {
                $destination = "uploads/";
                $filename = $_FILES['vendor_code']['name'];
                $path = $destination . $filename;
                move_uploaded_file($_FILES['vendor_code']['tmp_name'], $path);
                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($path);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $spreadsheet = $reader->load($path);
                $worksheet = $spreadsheet->getActiveSheet();

                foreach ($worksheet->getRowIterator(2) as $row) {
                    $minivendor = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $cellval = $cell->getValue();
                        if (!empty($cellval)) {
                            $minivendor[] = $cellval;
                        }
                    }

                    array_push($stockUpload, $minivendor);
                }
            }



            if (empty($minivendor)) {
                $minivendor = [];
                $res = $this->request->getData();
                //  print_r($res);exit;

                if ($res["code"] && $res["description"] && $res["opening_stock"] && $res["uom"]) {

                    $VendorMaterials = $this->paginate($this->Materials->find('all', [
                        'conditions' => ['Materials.id' => $res['code']]
                    ]))->first();
                    array_push($minivendor, $VendorMaterials->code);
                    array_push($minivendor, $res["opening_stock"]);
                    array_push($stockUpload, $minivendor);
                }
            }


            foreach ($stockUpload as $stockuploads) {
                if (!empty($stockuploads)) {
                    $materialCode = $stockuploads[0];
                    $VendorMaterials = $this->paginate($this->Materials->find('all', [
                        'conditions' => ['Materials.code' => $materialCode]
                    ]))->first();

                    $stockData = array();
                    $stockData['sap_vendor_code'] = $sapVendor;
                    $stockData['material_id'] = $VendorMaterials->id;
                    $stockData['opening_stock'] = $stockuploads[1];
                    $stockData['desc'] =  $VendorMaterials->description;
                    $stockData['material_code'] =  $VendorMaterials->code;
                    $stockData['uoms'] =  $VendorMaterials->uom;



                    $existingStockUpload = $this->StockUploads->find('all', [
                        'conditions' => [
                            'StockUploads.material_id' => $VendorMaterials->id,
                        ]
                    ])->first();

                 if (!$existingStockUpload) {
                    $stockupload = $this->StockUploads->newEmptyEntity();
                    $stockupload = $this->StockUploads->patchEntity($stockupload, $stockData);
                    if ($this->StockUploads->save($stockupload)) {
                        $flash = ['type' => 'success', 'msg' => 'The stock Upload has been saved'];
                        $this->set('flash', $flash);
                        array_push($stockView, ['status' => true, 'msg' => "The vendor material has been saved.", 'data' => $stockData]);
                    } else {
                        array_push($stockView, ['status' => false, 'msg' => "The vendor material could not be saved. Please, try again.", 'data' => $stockData]);
                    }
                    } 
                    else {
                        array_push($stockView, ['status' => false, 'msg' => "Vendor material code already exits in stock upload", 'data' => $stockData]);
                    }
                }
            }
        }
        $this->set('stockuploadData', $stockView);

        $vendor_mateial = $this->Materials->find('list', ['keyField' => 'id', 'valueField' => 'code'])->all();
        $this->set(compact('stockupload', 'vendor_mateial'));
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
                    $this->loadModel("VendorTemps");
                    $this->loadModel("Materials");
                    $this->loadModel("VendorFactories");
                    $this->loadModel("ProductionLines");
                    $this->loadModel("Dailymonitor");

                    
                    // echo "<pre>";
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $tmp = [];
                        $datas = [];
                        $facError = false;
                        $matError = false;
                        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                            
                            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            if($col == 1) {
                                $tmp['sap_vendor_code'] = str_pad((string)$value, 10, "0", STR_PAD_LEFT);
                                $datas['sap_vendor_code'] = str_pad((string)$value, 10, "0", STR_PAD_LEFT);
                            }
                            else if($col == 2) {
                                $factory = $this->VendorFactories->find('list')
                                ->select(['id'])
                                ->innerJoin(['VendorTemps'=> 'vendor_temps'], ['VendorTemps.id = VendorFactories.vendor_temp_id'])
                                ->where(['factory_code' => $value, 'VendorTemps.sap_vendor_code' => $tmp['sap_vendor_code']])
                                ->first();

                                //echo '<pre>';  print_r($factory); exit;
                                $tmp['vendor_factory_id'] = $factory ? $factory : null;
                                $datas['factory_code'] = $value;
                                if(!$factory) {
                                    $facError = true;
                                }
                            } else if($col == 3) {
                                $datas['po_no'] = $value;
                            }
                            else if($col == 4) {
                                $datas['line_item'] = $value;
                            }
                             else if ($col == 5) {
                                $value = trim($value);
                                $materials = $this->Materials->find('all')
                                ->select(['id', 'code'])
                                ->where(['code' => $value, 'sap_vendor_code' => $tmp['sap_vendor_code']])->first();
    
                                $tmp['material_id'] = isset($materials['id']) ? $materials['id'] : null;
                                $datas['material'] = $value;
                                if(!$tmp['material_id']) {
                                    $matError = true;
                                    $datas['error'] = 'Material not found';
                                } else {
                                    if ($this->StockUploads->exists(['sap_vendor_code' => $tmp['sap_vendor_code'],'vendor_factory_id' => $tmp['vendor_factory_id'], 'material_id' => $tmp['material_id']])) { 
                                        $matError = true;
                                        $datas['error'] = 'Already stock exists';
                                    } else {
                                        $productionLineIds = $this->ProductionLines->find()
                                        ->select(['id'])
                                        ->where(['material_id' => $tmp['material_id'], 'sap_vendor_code' => $tmp['sap_vendor_code'], 'vendor_factory_id' => $tmp['vendor_factory_id']])->first();

                                        if($productionLineIds) {
                                            if ($this->Dailymonitor->exists(['sap_vendor_code' => $tmp['sap_vendor_code'], 'production_line_id' => $productionLineIds,'material_id' => $tmp['material_id'], 'status' => 3])) { 
                                                $matError = true;
                                                $datas['error'] = 'Production Detail Exists';
                                            }
                                        }
                                    }
                                } 
                            }
                            else if($col == 6) {
                            
                                $datas['description'] = $value;
                            }
                            else if($col == 7) {
                                $tmp['opening_stock'] = $value;
                                $datas['opening_stock'] = $value;
                                $tmp['current_stock'] = $value;
                            }
                            else if($col == 8) {
                                $datas['uom'] = $value;
                            }
                            
                        }

                        if($facError) {
                            $datas['error'] = 'Invalid factory code';
                        } 

                        /*if($this->StockUploads->exists(['sap_vendor_code' => $tmp['sap_vendor_code'], 'vendor_factory_id' => $tmp['vendor_factory_id'], 'material_id' => $tmp['material_id']])) {
                            $datas['error'] = "Stock exists";
                        } */

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
