<?php
declare(strict_types=1);


namespace App\Controller\Buyer;
use App\Controller\Admin\AdminAppController;

use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Core\Configure;


/**
 * AdminUsers Controller
 *
 * @property \App\Model\Table\AdminUsersTable $AdminUsers
 * @method \App\Model\Entity\AdminUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AdminUsersController extends BuyerAppController
{
    public function initialize(): void
    {
        parent::initialize();
        $flash = [];  
        $this->set('flash', $flash);
    }
    
    public function login() {
        $flash = [];
        $this->viewBuilder()->setLayout('admin/login');  //admin is our new layout name
        $this->loadModel("AdminUsers");
        
        $session = $this->getRequest()->getSession();
        if($session->read('id')) {
            $this->redirect(array('controller' => 'adminusers', 'action' => 'index'));
        }

        if($this->request->is('post')) {
            $result = $this->AdminUsers->find()
            ->select(['id', 'username', 'role'])
            ->where(['username' => $this->request->getData('username'),
                'password' => md5($this->request->getData('password'))])
                ->limit(1);
            
                $result = $result->toArray();

                if($result) {
                    $session = $this->getRequest()->getSession();
                    $session->write('adminuser.username', $result[0]->username);
                    $session->write('adminuser.id', $result[0]->id);
                    $session->write('adminuser.role', $result[0]->role);
                    $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
                } else {
                    $flash = ['type'=>'success', 'msg'=>'Invalid Login details'];
                    $this->set('flash', $flash);
                }
        }
    }

    public function logout() {
        $session = $this->getRequest()->getSession();
        $session->destroy();
        $this->redirect(array('controller' => 'adminusers', 'action' => 'login'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index($type = null)
    {

        $this->set('user_type',$type);
        $sapUser = 0;
        if($type == "sap") {
            $this->set('title','SAP Users');
            $sapUser = 1;
        } else if($type == "portal") {
            $this->set('title','Portal Users');
        }

        $this->loadModel("Users");
        $this->paginate = ['contain' => ['Groups'], 'conditions' => ['sap_user' => $sapUser]];
        $adminUsers = $this->paginate($this->Users);
        //echo '<pre>'; print_r($adminUsers); exit();

        $this->set(compact('adminUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Admin User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $session = $this->getRequest()->getSession();
        $this->loadModel("Users");
        $adminUser = $this->Users->get($session->read('id'), [
            'contain' => [],
        ]);
        $this->set('headTitle', 'Profile');


        $this->set(compact('adminUser'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $flash = [];
        $this->loadModel("Users");
        $adminUser = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['group_id'] = 2;
            $data['password'] = $data['mobile'];
            $adminUser = $this->Users->patchEntity($adminUser, $data);
            if ($this->Users->save($adminUser)) {
              
                $visit_url = Router::url(['prefix' => false, 'controller' => 'users', 'action' => 'login', '_full' => true, 'escape' => true]);
                $mailer = new Mailer('default');
                $mailer
                    ->setTransport('smtp')
                    ->setViewVars([ 'subject' => 'Hi '.$data['first_name'], 'mailbody' => 'Welcome to Vendor portal. <br/> <br/> Username: '.$data['username'].
                    '<br/>Password:'.$data['password'], 'link' => $visit_url, 'linktext' => 'Click Here' ])
                    ->setFrom(Configure::read('MAIL_FROM'))
                    ->setTo($data['username'])
                    ->setEmailFormat('html')
                    ->setSubject('Vendor Portal - Account created')
                    ->viewBuilder()
                        ->setTemplate('mail_template');
                $mailer->deliver();

                $flash = ['type'=>'success', 'msg'=>'The User has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The admin user could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $this->set(compact('adminUser'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Admin User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $flash = [];
        $this->loadModel("AdminUsers");
        $adminUser = $this->AdminUsers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $adminUser = $this->AdminUsers->patchEntity($adminUser, $this->request->getData());
            if ($this->AdminUsers->save($adminUser)) {
                $flash = ['type'=>'success', 'msg'=>'The admin user has been saved'];
                $this->set('flash', $flash);

                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The admin user could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $this->set(compact('adminUser'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Admin User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $flash = [];
        $this->loadModel("AdminUsers");
        $this->request->allowMethod(['post', 'delete']);
        $adminUser = $this->AdminUsers->get($id);
        if ($this->AdminUsers->delete($adminUser)) {
            $flash = ['type'=>'success', 'msg'=>'The admin user could not be saved. Please, try again'];
        } else {
            $flash = ['type'=>'error', 'msg'=>'The admin user could not be deleted. Please, try again'];
        }
        $this->set('flash', $flash);

        return $this->redirect(['action' => 'index']);
    }

    public function changePassword() {
        $this->loadModel("Users");
        $this->set('headTitle', 'Profile');
        $session = $this->getRequest()->getSession();
        $user = $this->Users->get($session->read('id'));

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if ($data["password"] == "" || $data["current_password"] == "" || $data["password"] != $data["current_password"])
            { $flash = ['type'=>'error', 'msg'=>'Invalid Password']; }
            else { 
                $user->password = $data["password"];
                $this->Users->save($user);
                $flash = ['type'=>'success', 'msg'=>'Password Changed Successfully']; 
            }
            $this->set('flash', $flash);
        }
        $this->set(compact('user'));
    }
}
