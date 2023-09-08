<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->Auth->allow();
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $flash = [];
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $flash = ['type'=>'success', 'msg'=>'The user has been saved'];
                $this->set('flash', $flash);
                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The user could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $groups = $this->Users->Groups->find('list', ['limit' => 200])->all();
        $this->set(compact('user', 'groups'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $flash = [];
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $flash = ['type'=>'success', 'msg'=>'The user has been saved'];
                $this->set('flash', $flash);
                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The user could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $groups = $this->Users->UserGroups->find('list', ['limit' => 200])->all();
        $this->set(compact('user', 'groups'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $flash = [];
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $flash = ['type'=>'success', 'msg'=>'The user has been deleted'];
        } else {
            $flash = ['type'=>'error', 'msg'=>'The user could not be deleted. Please, try again'];
        }
        $this->set('flash', $flash);

        return $this->redirect(['action' => 'index']);
    }

    public function welcome()
    { }

    public function forgetPwd()
    { 
        if ($this->request->is(['patch', 'post', 'put'])) {
            //echo '<pre>'; print_r($this->request->getData('email')); exit;
            $user = $this->Users->findByUsername($this->request->getData('email'))->first();
            if ($user) {
                $user->password = $user->mobile;
                //$user = $this->Users->patchEntity($user);
                if ($this->Users->save($user)) {

                    $visit_url = Router::url(['prefix' => false, 'controller' => 'users', 'action' => 'login', '_full' => true, 'escape' => true]);
                    $mailer = new Mailer('default');
                    $mailer
                        ->setTransport('smtp')
                        ->setViewVars([ 'subject' => 'Hi ' . $user->first_name, 'mailbody' => 'Vendor portal password. <br/> <br/> ' .
                        '<br/>Password:' . $user->mobile, 'link' => $visit_url, 'linktext' => 'Click Here' ])
                        ->setFrom(['vekpro@fts-pl.com' => 'FT Portal'])
                        ->setTo($user->username)
                        ->setEmailFormat('html')
                        ->setSubject('Vendor Portal - Password changed')
                        ->viewBuilder()
                        ->setTemplate('mail_template');
                    $mailer->deliver(); 
                    
                    $flash = ['type'=>'success', 'msg'=>'Password mail sent'];
                    $this->set('flash', $flash);

                    return $this->redirect(['action' => 'login']);
                }
            } else {
                $flash = ['type'=>'error', 'msg'=>'Email id not found'];
                $this->set('flash', $flash);
            }
        }
    }

    public function login()
    {
        $session = $this->getRequest()->getSession();
      
        
        if ($session->check('id')) {
            $role = $session->read('role');
            if ($role == 1) {
                $this->redirect(['controller' => 'admin/dashboard', 'action' => 'index']);
            } else if ($role == 2) {
                $this->redirect(['controller' => 'buyer/dashboard', 'action' => 'index']);
            } else if ($role == 3) {
                $this->redirect(['controller' => 'vendor/dashboard', 'action' => 'index']);
            }
        }

    }

    public function apiLogin()
    {
        $response = array();
        $response['status'] = 0;
        $response['message'] = 'Empty request';
        $response['redirect'] = '';
        $this->autoRender = false;


        $this->loadModel("Users");
        $this->loadModel("VendorTemps");
        $this->loadModel('Buyers');
        

        $session = $this->getRequest()->getSession();
     
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->request->getData('logged_by') == 'email') {

                $result = $this->Users->find()
                    ->select($this->Users)
                    ->select(['group_name' => 'UserGroups.name'])
                    ->leftjoin(['UserGroups' => 'user_groups'], ['UserGroups.id = Users.group_id'])
                    ->where(['username' => $this->request->getData('username')])->limit(1)->toArray();

                if ($result) {
                    if (password_verify($this->request->getData('password'), $result[0]->password)) {
                        $session = $this->getRequest()->getSession();
                        $session->write('username', $result[0]->username);
                        $session->write('group_name', $result[0]->group_name);
                        $session->write('full_name', $result[0]->first_name . ' ' . $result[0]->last_name);
                        $session->write('first_name', $result[0]->first_name);
                        $session->write('last_name', $result[0]->last_name);
                        $session->write('id', $result[0]->id);
                        $session->write('role', $result[0]->group_id);
                        
                        $response['status'] = 1;
                        $response['message'] = '';
                        if ($result[0]->group_id == 1) {
                            $response['redirect'] = ['controller' => 'admin/dashboard', 'action' => 'index'];
                        } else if ($result[0]->group_id == 2) {
                            $result = $this->Buyers->find('all')->where(['email' => $result[0]->username])->limit(1)->toArray();
                            $session->write('company_code_id', $result[0]->company_code_id);
                            $session->write('purchasing_organization_id', $result[0]->purchasing_organization_id);
                            $session->write('buyer_id', $result[0]->id);
                            $response['redirect'] = ['controller' => 'buyer/dashboard', 'action' => 'index'];
                        } else if ($result[0]->group_id == 3) {
                            $result = $this->VendorTemps->find()->where(['email' => $result[0]->username])->limit(1)->toArray();
                            $session->write('vendor_code', $result[0]->sap_vendor_code);
                            $session->write('vendor_id', $result[0]->id);
                            $session->write('buyer_id', $result[0]->buyer_id);
                            $response['redirect'] = ['controller' => 'vendor/dashboard', 'action' => 'index'];
                        }
                    } else {
                        $response['message'] = 'Invalid password';
                    }
                } else {
                    $response['message'] = 'Invalid username';
                }
            } else {
                $result = $this->Users->find()
                    ->select($this->Users)
                    ->select(['group_name' => 'UserGroups.name'])
                    ->leftjoin(
                        ['UserGroups' => 'user_groups'],
                        ['UserGroups.id = Users.group_id']
                    )
                    ->where(['mobile' => $this->request->getData('mobile')])->limit(1)->toArray();
                //print_r($result); exit;
                if ($result) {
                    if ($this->request->getData('otp') == $result[0]->otp) {
                        $session = $this->getRequest()->getSession();
                        $session->write('username', $result[0]->username);
                        $session->write('group_name', $result[0]->group_name);
                        $session->write('full_name', $result[0]->first_name . ' ' . $result[0]->last_name);
                        $session->write('first_name', $result[0]->first_name);
                        $session->write('id', $result[0]->id);
                        $session->write('role', $result[0]->group_id);
                        $response['status'] = 1;
                        $response['message'] = '';
                        if ($result[0]->group_id == 1) {
                            $response['redirect'] = ['controller' => 'admin/dashboard', 'action' => 'index'];
                        } else if ($result[0]->group_id == 2) {
                            $result = $this->Buyers->find('all')->where(['email' => $result[0]->username])->limit(1)->toArray();
                            $session->write('company_code_id', $result[0]->company_code_id);
                            $session->write('purchasing_organization_id', $result[0]->purchasing_organization_id);
                            $session->write('buyer_id', $result[0]->id);
                            $response['redirect'] = ['controller' => 'buyer/dashboard', 'action' => 'index'];
                        } else if ($result[0]->group_id == 3) {
                            $result = $this->VendorTemps->find('all')->where(['email' => $result[0]->username])->limit(1)->toArray();
                            $session->write('vendor_code', $result[0]->sap_vendor_code);
                            $session->write('company_code_id', $result[0]->company_code_id);
                            $session->write('purchasing_organization_id', $result[0]->purchasing_organization_id);
                            $session->write('vendor_id', $result[0]->id);
                            $response['redirect'] = ['controller' => 'vendor/dashboard', 'action' => 'index'];
                        }
                    } else {
                        $response['message'] = 'Invalid OTP';
                    }
                } else {
                    $response['message'] = 'Invalid mobile';
                    
                }
            }
        }
        echo json_encode($response);
    }


    public function getOtp()
    {
        $this->autoRender = false;
        $this->loadModel("Users");

        $response = array();

        if ($this->request->is('post')) {
            $result = $this->Users->find()->where(['mobile' => $this->request->getData('mobile')])->limit(1)->toArray();
            //print_r($result); exit;
            if ($result) {
                $otp = random_int(100000, 999999);
                $user = $this->Users->get($result[0]->id);
                $user = $this->Users->patchEntity($user, array('otp' => $otp));
                if ($this->Users->save($user)) {
                    
                    $visit_url = Router::url('/', true);
                    $mailer = new Mailer('default');
                    $mailer
                        ->setTransport('smtp')
                        ->setViewVars([ 'subject' => 'Hi ' . $result[0]->username, 'mailbody' => 'OTP :: ' . $otp, 'link' => $visit_url, 'linktext' => 'Visit Vekpro' ])
                        ->setFrom(['vekpro@fts-pl.com' => 'FT Portal'])
                        ->setTo($result[0]->username)
                        ->setEmailFormat('html')
                        ->setSubject('Vendor Portal - Login OTP')
                        ->viewBuilder()
                            ->setTemplate('mail_template');
                    $mailer->deliver();
                }
                $response['status'] = 1;
                $response['message'] = 'OTP sent to register Mobile';
            } else {
                $response['status'] = 0;
                $response['message'] = 'Mobile number not found';
            }
        }

        echo json_encode($response);
    }

    public function logout()
    {
        //Leave empty for now.
        //$this->redirect($this->Auth->logout());
        $session = $this->getRequest()->getSession();
        $session->destroy();
        $this->redirect(array('controller' => 'users', 'action' => 'login'));
    }


    
}