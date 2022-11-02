<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Datasource\ConnectionManager;

/**
 * Home Controller
 *
 * @method \App\Model\Entity\Home[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DealerController extends AppController
{

    public function login() {
        $this->loadModel('BuyerSellerUsers');

        $this->loadModel('Products');
        $products = $this->Products->find('list')->toArray();
        $this->set(compact('buyerSellerUser', 'products'));
        $session = $this->getRequest()->getSession();
        if($session->read('user.id')) {
            $this->redirect(array('action' => 'dashboard'));
        }

        if($this->request->is('post')) {
            $result = $this->BuyerSellerUsers->find()
            //->select(['id', 'username', 'user_type'])
            ->where(['username' => $this->request->getData('username'),
                'password' => md5($this->request->getData('password'))])
                ->limit(1);
            
                $result = $result->toArray();

                if($result) {
                    $session = $this->getRequest()->getSession();
                    $session->write('user.username', $result[0]->username);
                    $session->write('user.id', $result[0]->id);
                    $session->write('user.user_type', $result[0]->user_type);
                    $session->write('user.details', $result[0]);
                    $this->redirect(array('controller' => 'dealer', 'action' => 'dashboard'));
                } else {
                    $this->Flash->error("Invalid Login details");
                }
                
        }
    }

    public function logout() {
        $session = $this->getRequest()->getSession();
        $session->destroy();
        $this->Flash->success("You've successfully logged out.");
        $this->redirect(array('controller' => 'home', 'action' => 'index'));
    }

    public function registration()
    {
        $this->loadModel('BuyerSellerUsers');
        $buyerSellerUser = $this->BuyerSellerUsers->newEmptyEntity();

        $this->loadModel('Products');
        $products = $this->Products->find('list')->toArray();

        if ($this->request->is('post')) {
            //print_r($this->request->getData()); exit;
            $data = $this->request->getData();
            $data['added_date'] = date('y-m-d H:i:s');
            $data['password'] = md5($data['password']);
            $buyerSellerUser = $this->BuyerSellerUsers->patchEntity($buyerSellerUser, $data);
            //print_r($buyerSellerUser); exit;
            if ($this->BuyerSellerUsers->save($buyerSellerUser)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'confirmation']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('buyerSellerUser', 'products'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    public function confirmation()
    {
        
    }

    
    public function dashboard() 
    {
        $session = $this->getRequest()->getSession();
        if(!$session->check('user.id')) {
            return $this->redirect(array('action' => 'login'));
        }

        if($session->read('user.user_type') == 'seller') {
            $this->redirect(['action' => 'productlist']);
        }

        $conn = ConnectionManager::get('default');
        //$this->loadModel('RfqDetails');
        //$this->loadModel('RfqInquiries');

        $rfqDetails = $conn->execute("select rfq_details.id, rfq_details.added_date, rfq_inquiries.reach, rfq_inquiries.respond
 from rfq_details 
 left join (select rfq_id, count(seller_id) reach, count(inquiry) respond FROM rfq_inquiries group by rfq_inquiries.rfq_id) rfq_inquiries on (rfq_inquiries.rfq_id = rfq_details.id)
 where rfq_details.buyer_seller_user_id=" .$session->read('user.id'));

 $rfqsummary = $conn->execute("SELECT rfq_id, U.company_name, rate, created_date FROM rfq_inquiries RI join buyer_seller_users U on (U.id = RI.seller_id) WHERE rate = ( SELECT MIN( RI2.rate ) FROM rfq_inquiries RI2 WHERE RI.rfq_id = RI2.rfq_id ) ORDER BY rfq_id");
 
 

    // Getting paginated result based on page #
    
    $this->set('rfqDetails', $rfqDetails);
    $this->set('rfqsummary', $rfqsummary);
        
    }

    public function view($id = null)
    {

        $this->loadModel('RfqDetails');
        $this->loadModel('RfqInquiries');

        $rfqDetails = $this->RfqDetails->get($id, [
            'contain' => ['Products', 'ProductSubCategories', 'Uoms'],
        ]);
        $attrParams = json_decode($rfqDetails->uploaded_files, true);

        $session = $this->getRequest()->getSession();
        $userType = $session->read('user.user_type');
        if($userType == 'seller') {
            $RfqInquiry = $this->RfqInquiries->newEmptyEntity();
            $data = array();
            $data['rfq_id'] = $id;
            $data['seller_id'] = $session->read('user.id');
            $RfqInquiry = $this->RfqInquiries->patchEntity($RfqInquiry, $data);
            $results = $this->RfqInquiries->save($RfqInquiry);
        }  else if($userType == 'buyer')  {
                $results = $this->RfqInquiries->find()->where(['rfq_id' => $id])->contain('BuyerSellerUsers')->toArray();

                /*$data = array();
                foreach($results as &$result) {
                    $t = array();
                    if(isset($result['inquiry_data']) && $result['inquiry_data'] != null ) {
                        $tmp = json_decode($result['inquiry_data'], true);
                        foreach($tmp as $k => $v) {
                            $t[$k] = $v;
                        }
                    }
                    $t['inquiry_date'] = $result['created_date'];
                    $t['company'] = $result->buyer_seller_user->company_name;

                    $data[] = $t;
                } */
                //echo '<pre>'; print_r($results); exit;
        }  

        $this->set(compact('rfqDetails', 'attrParams', 'userType', 'results'));
    }

    public function addproduct($type) {

        $this->loadModel("Products");
        $this->loadModel("Uoms");
        $products = $this->Products->find('list')->toArray();
        $uoms = $this->Uoms->find('list')->toArray();

        $this->set(compact('products', 'uoms'));

        if ($this->request->is('post')) {
            $this->loadModel("RfqDetails");
            $RfqDetail = $this->RfqDetails->newEmptyEntity();
            $request = $this->request->getData();

            $productImages = $this->request->getData("files");
            
            $uploads["files"] = array();
            
            // file uploaded
            foreach($productImages as $productImage) {
                $fileName = time().'_'.$productImage->getClientFilename();
                $fileType = $productImage->getClientMediaType();

                if ($fileType == "application/pdf" || $fileType == "image/jpeg" || $fileType == "image/jpg") {
                    $imagePath = WWW_ROOT . "uploads/" . $fileName;
                    $productImage->moveTo($imagePath);
                    $uploads["files"][] = "uploads/" . $fileName;
                }
            }

            //echo '<pre>';print_r($data); print_r($request); exit;

            $session = $this->getRequest()->getSession();
            $userId = $session->read('user.id');
            $data = array();
            $data['buyer_seller_user_id'] = $userId;
            $data['product_id'] = $request['product_id'];
            $data['product_sub_category_id'] = $request['product_sub_category_id'];
            $data['part_name'] = $request['part_name'];
            $data['qty'] = $request['qty'];
            $data['uom_code'] = $request['uom_code'];
            $data['remarks'] = $request['remarks'];
            $data['make'] = $request['make'];
            $data['added_date'] = date('Y-m-d H:i:s');
            $data['uploaded_files'] = json_encode($uploads["files"]);

            //print_r($data); exit;
            $RfqDetail = $this->RfqDetails->patchEntity($RfqDetail, $data);

            
            if($this->RfqDetails->save($RfqDetail)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'dashboard']);
            }
       
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
    }

    public function productlist() {
        $this->loadModel('RfqDetails');
        $session = $this->getRequest()->getSession();
        //print_r($session->read()); exit;
        $userType = $session->read('user.user_type');
        $productDeals = $session->read('user.details.product_deals');

        $rfqDetails = array();
        if($userType == 'seller') {
            $rfqDetails = $this->RfqDetails->find()->where()->contain(['Products' => function ($q) use ($productDeals)  {
                return $q->where(['Products.id in ' => $productDeals]);

            }, 'ProductSubCategories', 'Uoms'])->toList();

            foreach ($rfqDetails as &$rfqDetail) {
                $files = json_decode($rfqDetail['uploaded_files'], true);
                foreach($files as $file) {
                    $rfqDetail['image'] = $file;
                    break;
                }
            }
            //echo '<pre>'; print_r($rfqDetails);
        }


        $this->set(compact('rfqDetails'));
    }

    public function inquiry($id=null) {
        $session = $this->getRequest()->getSession();
        $userType = $session->read('user.user_type');
        if($userType == 'seller') {
            if($this->request->is('post')) {
                //print_r($this->request->getData()); exit;
            
                $this->loadModel('RfqInquiries');
                $request = array();
                $request['rfq_id'] = $id;
                $request['seller_id'] = $session->read('user.id');
                $RfqInquiry = $this->RfqInquiries->find()->where($request)->first();
                $RfqInquiry->inquiry = 1;
                $RfqInquiry->qty = $this->request->getData('qty');
                $RfqInquiry->rate = $this->request->getData('rate');
                $RfqInquiry->delivery_date = $this->request->getData('delivery_date');
                
                if($this->RfqInquiries->save($RfqInquiry)) {
                    $this->Flash->success(__('Inquiry send to Buyer.'));
                    return $this->redirect(['action' => 'productlist']);
                }
            }
        }

        $this->set('userType', $userType);
    }

    

}
