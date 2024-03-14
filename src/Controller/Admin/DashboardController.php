<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller\Admin;

use Cake\Utility\Security;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class DashboardController extends AdminAppController
{
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];  
        $this->set('flash', $flash);
    }
    
    public function index()
    {
        $this->loadModel('VendorTemps');
        $this->loadModel('Buyers');
        $this->loadModel('Users');

        $vendorStatus = $this->VendorTemps->find()
        ->select(['status' => 'VendorStatus.status','count' => 'count(VendorStatus.status)'])
        ->innerJoin(['VendorStatus' => 'vendor_status'], ['VendorStatus.status=VendorTemps.status'])
        ->group('VendorTemps.status')->toArray();

        $buyers = $this->Buyers->find()
        ->select(['status' => 'Buyers.status','count' => 'count(Buyers.status)'])
        ->group('Buyers.status')->toArray();

        $managers = $this->Users->find()
        ->select(['status' => 'Users.status','count' => 'count(Users.status)'])
        ->where(['Users.group_id' => 4])
        ->group('Users.status')->toArray();


        $vendorDashboardCount = [];
        $vendorDashboardCount['total'] = array_sum(array_column($vendorStatus,'count'));
        foreach($vendorStatus as $status) {
            $vendorDashboardCount[$status->status] = $status->count;
        }

        $buyerCounts = [];
        $buyerCounts['total'] = array_sum(array_column($buyers,'count'));
        foreach($buyers as $status) {
            $buyerCounts[$status->status] = $status->count;
        }

        $managerCounts = [];
        $managerCounts['total'] = array_sum(array_column($managers,'count'));
        foreach($managers as $status) {
            $managerCounts[$status->status] = $status->count;
        }


        $this->set(compact('vendorDashboardCount', 'buyerCounts', 'managerCounts'));

    }

    public function oldindex()
    {

        $this->loadModel('RfqDetails');
        $this->loadModel('RfqInquiries');
        $this->loadModel('BuyerSellerUsers');

        $query = $this->RfqDetails->find();
        $query->select([
            'company_name' => 'BuyerSellerUsers.company_name', 'buyer_id' => 'BuyerSellerUsers.Id',
            'rfq_count' => $query->func()->count('RfqDetails.Id'),
            'reached' => $query->func()->count('RfqInquiries.Id'),
            'new_rfq' => $query->func()->sum('case when RfqDetails.status = 0 then 1 else 0 end'),
            'responded' => $query->func()->sum('case when RfqInquiries.inquiry = 1 then 1 else 0 end')
        ])
            ->contain(['BuyerSellerUsers', 'Products'])
            ->leftJoin(
                ['RfqInquiries' => 'rfq_inquiries'],
                ['RfqInquiries.rfq_id = RfqDetails.id']
            )
            ->group(['RfqDetails.buyer_seller_user_id'])
            ->order(['responded asc']);

        $countDashboard = $this->paginate($query);
        $this->set('countDashboard', $countDashboard);

        $rfqNonResponded = array();
        $queryNonResponded = $this->RfqDetails->find()
            ->select([
                'buyer_id' => 'RfqDetails.buyer_seller_user_id',
                'rfq_non_responded' => $query->func()->count('RfqDetails.Id')
            ])
            ->where('RfqDetails.Id not in (select rfq_id from rfq_inquiries where inquiry = 1)')
            ->group(['RfqDetails.buyer_seller_user_id'])->toList();

        foreach ($queryNonResponded as $key => $val) {
            $rfqNonResponded[$val->buyer_id] = $val->rfq_non_responded;
        }

        $this->set('rfqNonResponded', $rfqNonResponded);
    }

    public function rfqList($buyerId = null, $responded = null)
    {

        $this->loadModel('RfqDetails');
        $this->loadModel('BuyerSellerUsers');

        $buyerSellerUser = $this->BuyerSellerUsers->get($buyerId, [
            'contain' => [],
        ]);


        $query = $this->RfqDetails->find()
            ->select(['RfqDetails.Id', 'RfqDetails.rfq_no', 'RfqDetails.part_name', 'RfqDetails.qty', 'Uoms.description', 'RfqDetails.status', 'RfqDetails.added_date', 'Products.name'])
            ->contain(['Products', 'Uoms'])
            ->where(['RfqDetails.buyer_seller_user_id' => $buyerId]);

        if (isset($responded) && !$responded) {
            $query = $this->RfqDetails->find()
                ->select(['RfqDetails.Id', 'RfqDetails.rfq_no', 'RfqDetails.part_name', 'RfqDetails.qty', 'Uoms.description', 'RfqDetails.status', 'RfqDetails.added_date', 'Products.name'])
                ->contain(['Products', 'Uoms'])
                ->where(['RfqDetails.buyer_seller_user_id' => $buyerId, 'RfqDetails.Id not in (select rfq_id from rfq_inquiries where inquiry = 1)']);
        }

        $rfqDetailsList = $this->paginate($query);



        $this->set(compact('buyerSellerUser'));
        $this->set('rfqList', $rfqDetailsList);
    }

    public function userAdd()
    {

        $response = array();
        $response['status'] = '0';
        $response['message'] = '';
        $this->autoRender = false;

        $this->loadModel('Users');
        $this->loadModel('Buyers');
        if ($this->request->is(['patch', 'post', 'put'])) {

            try {

                $data = $this->request->getData();

                $buyer = $this->Buyers->newEmptyEntity();
                $buyer = $this->Buyers->patchEntity($buyer, $data);
                if($this->Buyers->save($buyer)) {

                    $data['username'] = $data['email'];
                    $data['password'] = $data['mobile'];//Security::randomString(10);

                    $user = $this->Users->newEmptyEntity();
                    $user = $this->Users->patchEntity($user, $data);
                    if ($this->Users->save($user)) {
                        
                        $visit_url = Router::url(['prefix' => false, 'controller' => 'users', 'action' => 'login', '_full' => true, 'escape' => true]);
                        $mailer = new Mailer('default');
                        $mailer
                            ->setTransport('smtp')
                            ->setViewVars([ 'subject' => 'Hi ' . $data['first_name'], 'mailbody' => 'Welcome to Vendor portal. <br/> <br/> Username: ' . $data['username'] . '<br/>Password:' . $data['password'], 'link' => $visit_url, 'linktext' => 'Click Here' ])
                            ->setFrom(Configure::read('MAIL_FROM'))
                            ->setTo($data['username'])
                            ->setEmailFormat('html')
                            ->setSubject('BUYER ONBOARDING')
                            ->viewBuilder()
                                ->setTemplate('onboarding');
                        $mailer->deliver();

                        $response['status'] = '1';
                        $response['message'] = 'User Added successfully';
                    } else {
                        //throw new \Exception('Failed to Add User'); // Throw exception if the 
                        $response['status'] = '0';
                        $response['message'] = 'Failed to Add User';
                    }
                } else {
                    $response['status'] = '0';
                    $response['message'] = 'Failed to Add Buyer';
                }
            } catch (\Exception $e) {
                $response['status'] = '0';
                $response['message'] = $e->getMessage();
            }
        }


        echo json_encode($response);
    }


    public function userView()
    {
        $this->autoRender = false;
        $data = [];
        $conn = ConnectionManager::get('default');
        $data = $conn->execute('SELECT *, CONCAT(u.first_name, " ", u.last_name) as fullname FROM users u inner join user_groups ug on u.group_id = ug.id WHERE ug.name IN ("Admin", "Buyer")')->fetchAll('assoc');
        echo json_encode($data);
    }

    // User active and deactive api

    public function userAction()
    {
        $response = array();
        $response['status'] = '0';
        $response['message'] = '';
        $this->autoRender = false;

        $this->loadModel('Users');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

           
            $user = $this->Users->get($data['id']);

       
            if ($data['status'] === '1') {
                $user->status = '1';
            } elseif ($data['status'] === '0') {
                $user->status = '0';
            } else {
                $response['message'] = 'Invalid status value';
                echo json_encode($response);
                return;
            }

            if ($this->Users->save($user)) {
                
                $response['status'] = '1';
                $response['message'] = 'User status updated successfully';
            } else {
                
                $response['status'] = '0';
                $response['message'] = 'Failed to update user status';
            }
        } else {
            
            $response['status'] = '0';
            $response['message'] = 'Invalid request method';
        }

        echo json_encode($response);
    }
}
