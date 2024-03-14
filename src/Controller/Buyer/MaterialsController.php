<?php

declare(strict_types=1);

namespace App\Controller\Buyer;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Http\Client;

/**
 * VendorMaterial Controller
 *
 * @property \App\Model\Table\VendorMaterialTable $VendorMaterial
 * @method \App\Model\Entity\VendorMaterial[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MaterialsController extends BuyerAppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];  
        $this->set('flash', $flash);
    }
    
    public function index()
    {
        $this->loadModel("VendorTemps");
        $this->loadModel('VendorTypes');
        $this->loadModel('Materials');
        $materials = $this->Materials->find('all')->select(['code', 'description'])->distinct(['code'])->toArray();
        $segment = $this->Materials->find('all')->select(['segment'])->distinct(['segment'])->where(['segment IS NOT NULL' ])->toArray();
        $vendor = $this->VendorTemps->find('all')->select(['sap_vendor_code', 'name'])->distinct(['sap_vendor_code'])->where(['sap_vendor_code IS NOT NULL' ])->toArray();
        $vendortype = $this->Materials->find('all')->distinct(['type'])->where(['type IS NOT NULL' ])->toArray();
        $this->set(compact('materials', 'vendor', 'vendortype', 'segment'));
    }

    public function getvendor()
    {
        $this->autoRender = false;
        $this->loadModel('VendorTemps');
        $vendors = $this->VendorTemps->find('all')->where(['sap_vendor_code IS NOT NULL' ])->toArray();
        $response = ['status' => 1, 'data' => $vendors];
        echo json_encode($response); exit();
    }

    public function getvendormaterial($code)
    {
        // echo '<pre>'; print_r($code);exit;
        $this->autoRender = false;
        $this->loadModel('Materials');
        $materials = $this->Materials->find('all')->where(['sap_vendor_code' =>$code, 'minimum_stock' => 0])->toArray();
        $response = ['status' => 1, 'data' => $materials];
        echo json_encode($response); exit();
    }

    public function getvendorsmaterial($code)
    {
        // echo '<pre>'; print_r($code);exit;
        $this->autoRender = false;
        $this->loadModel('Materials');
        $materials = $this->Materials->find('all')->where(['sap_vendor_code' =>$code])->toArray();
        $response = ['status' => 1, 'data' => $materials];
        echo json_encode($response); exit();
    }

    public function postmsl()
    {
        $this->autoRender = false;
        $this->loadModel('Materials');
        $response = ['status' => 0, 'data' => "MSL Updated Failed"];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $request = $this->request->getData();
            // echo '<pre>'; print_r($request);exit;
            if(isset($request['sap_vendor_code']) && isset($request['minimum_stock']) && isset($request['code']))
            {
                $existingStockUpload = $this->Materials->find('all')->where(['sap_vendor_code' => $request['sap_vendor_code'], 'code' => $request['code']])->first();
                if (!$existingStockUpload){
                    $vendorMaterial = $this->Materials->newEmptyEntity();
                    $mslvalue = array();
                    $mslvalue['minimum_stock']  = $request['minimum_stock'];
                    $mslvalue['code']  = $request['code'];
                    $mslvalue['description']  = ' ';
                    $mslvalue['sap_vendor_code']  = $request['sap_vendor_code'];
                    $vendorMaterial = $this->Materials->patchEntity($vendorMaterial, $mslvalue);
                    if ($this->Materials->save($vendorMaterial)) { $response = ['status' => 1, 'data' => "MSL Updated"]; }
                    else { $response = ['status' => 0, 'data' => "MSL Update Failed"];}
                } else { $response = ['status' => 0, 'data' => "MSL Exist"];}
            }
        }
        echo json_encode($response); exit();
    }

    public function materiallist(){
        $this->autoRender = false;
        $this->loadModel("VendorTemps");
        $this->loadModel('VendorTypes');
        $this->loadModel('Materials');
        $response = array('status'=>0, 'message'=>'fail', 'data'=>'');

        $conditions = " where 1=1 and vendor_temps.sap_vendor_code is not NULL ";
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
                if(!isset($request['material']) and !isset($request['vendor']) and !isset($request['vendortype'])){ $conditions .= " and materials.segment in (".$search.")"; }
                else{ $conditions .= " and materials.segment in (".$search.")"; }
            }
        }
        
        $conn = ConnectionManager::get('default');
        $material = $conn->execute("select
        vendor_temps.id as 'v_id', IFNULL(vendor_temps.sap_vendor_code,'-') as 'v_code', vendor_temps.name as 'v_name',
        materials.id as 'mt_id', materials.code as 'mt_code', materials.description as 'mt_description', materials.minimum_stock as 'mt_ms', materials.uom as 'mt_uom', IFNULL(materials.segment, '-') as 'mt_segment', materials.type as 'vt_id' from materials left join vendor_temps on materials.sap_vendor_code = vendor_temps.sap_vendor_code". $conditions);
        // echo '<pre>'; print_r($request);print_r($material);
        $materialist = $material->fetchAll('assoc');

        $results = [];
        foreach ($materialist as $mat) {
            $tmp = [];
            $tmp[] = $mat['v_code'];
            $tmp[] = $mat['mt_code'];
            $tmp[] = $mat['mt_description'];
            $tmp[] = $mat['vt_id'];
            $tmp[] = $mat['mt_segment'];
            $tmp[] = $mat['mt_ms'];
            $tmp[] = $mat['mt_uom'];
            $results[] = $tmp;
        }

        $response = array('status'=>1, 'message'=>'success', 'data'=>$results);
        echo json_encode($response); exit;
    }

    /**
     * View method
     *
     * @param string|null $id Vendor Material id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vendorMaterial = $this->VendorMaterial->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('vendorMaterial'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $materials = $this->Materials->newEmptyEntity();
        $this->set('materials', $materials);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vendor Material id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Uoms');
        $flash = [];
        $vendorMaterial = $this->VendorMaterial->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vendorMaterial = $this->VendorMaterial->patchEntity($vendorMaterial, $this->request->getData());
            if ($this->VendorMaterial->save($vendorMaterial)) {
                $flash = ['type'=>'success', 'msg'=>'The vendor material has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'success', 'msg'=>'The vendor material has been saved'];
            $this->set('flash', $flash);
        }

        $uom = $this->Uoms->find('list', ['keyField' => 'id', 'valueField' => 'code'])->all();

        $this->set(compact('vendorMaterial', 'uom'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Vendor Material id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $flash = [];
        $this->request->allowMethod(['post', 'delete']);
        $vendorMaterial = $this->VendorMaterial->get($id);
        if ($this->VendorMaterial->delete($vendorMaterial)) {
            $flash = ['type'=>'success', 'msg'=>'The vendor material has been deleted'];
        } else {
            $flash = ['type'=>'error', 'msg'=>'The vendor material could not be deleted. Please, try again'];
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
            // Import File To array
            $importFile = $this->request->getData('upload_file');
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

                $tmp = [];
                $datas = [];

                for ($row = 2; $row <= $highestRow; $row++) {
                    $vendorError = false;
                    $matError = false;
                    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                        
                        $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                        if($col == 1) {
                            
                            $tmp['sap_vendor_code'] = str_pad((string)$value, 10, "0", STR_PAD_LEFT);
                            $datas['sap_vendor_code'] = str_pad((string)$value, 10, "0", STR_PAD_LEFT);
                            if(empty($value)) {
                                $vendorError = true;
                            }
                        }
                        else if($col == 2) {
                            $tmp['code'] = $value;
                            $datas['material_code'] = $value;
                        } else if($col == 3) {
                            $tmp['description'] = $value;
                            $datas['description'] = $value;
                        }
                        else if($col == 4) {
                            $tmp['minimum_stock'] = $value;
                            $datas['minimum_stock'] = $value;
                        }
                         else if ($col == 5) {
                            $tmp['uom'] = $value; 
                            $datas['uom'] = $value;
                        }
                    }

                    $datas['error'] = '';
                    if($vendorError) {
                        $datas['error'] = 'Invalid Vendor code';
                    }

                    $stockData[] = $datas;
                    if(empty($datas['error'])) {
                        $uploadData[] = $tmp;   
                    }
                    if(!empty($uploadData)) {
                        $columns = array_keys($uploadData[0]);
                        $upsertQuery = $this->Materials->query();
                        $upsertQuery->insert($columns);
                        foreach ($uploadData as $data) {
                            $upsertQuery->values($data);
                        }
                        $upsertQuery->epilog('ON DUPLICATE KEY UPDATE description=VALUES(description), minimum_stock=VALUES(minimum_stock), uom=VALUES(uom)')
                            ->execute();
                    }
                }

                $response['status'] = 1;
                $response['data'] = $stockData;
                $response['message'] = 'uploaded Successfully';

            } else {
                $response['status'] = 0;
                $response['message'] = 'file not uploaded';
            }

        }
        echo json_encode($response); exit;
    }

}
