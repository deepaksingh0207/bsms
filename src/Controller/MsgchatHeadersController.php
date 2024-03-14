<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Datasource\ConnectionManager;

/**
 * MsgchatHeaders Controller
 *
 * @property \App\Model\Table\MsgchatHeadersTable $MsgchatHeaders
 * @method \App\Model\Entity\MsgchatHeader[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MsgchatHeadersController extends AppController
{

    public function index($app=null, $app_id=null)
    {
        // echo '<pre>';print_r($app);exit;
        $conn = ConnectionManager::get('default');
        $query = "SELECT mf.id, mh.table_name, mh.table_pk, mh.subject, mf.msgchat_header_id, mf.sender_id, mf.group_id,
        case when mf.group_id != 3 then concat(u.first_name,' ',u.last_name) else vt.name end as fullname, mf.message, mf.seen, mf.addeddate, mf.updateddate
        FROM msgchat_headers mh left join msgchat_footers mf on mh.id=mf.msgchat_header_id
        left join users u on u.id = mf.sender_id
        left join vendor_temps vt on mf.sender_id = vt.id ";
        if ($app!=null && $app_id!=null){ $query .= " where mh.table_name='".$app."' and mh.table_pk=".$app_id." order by mf.addeddate desc";}
        $rfqDetails = $conn->execute($query)->fetchAll('assoc');
        echo json_encode($rfqDetails);exit;
    }


    public function view($id = null)
    {
        $msgchatHeader = $this->MsgchatHeaders->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('msgchatHeader'));
    }


    public function add()
    {
        $response = ['status' => 0, 'message' => 'failed', 'data' => ''];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $headerStatus = true;
            $this->autoRender = false;
            $this->loadModel("MsgchatFooters");
            $this->loadModel("MsgchatHeaders");
            $this->loadModel("VendorTemps");
            $this->loadModel("Buyers");
            $this->loadModel("Notifications");

            $data = $this->request->getData();
            $msgchatHeader = $this->MsgchatHeaders->find()->where(['table_pk' => $data['table_pk'], 'table_name'=> $data['table_name']])->first();
            $MsgchatFooter = $this->MsgchatFooters->newEmptyEntity();
            // print_r($msgchatHeader->id);exit;

            // Save Header
            if (!$msgchatHeader) {
                $msgchatHeader = $this->MsgchatHeaders->newEmptyEntity();
                $msgchatHeader->table_name = $data['table_name'];
                $msgchatHeader->table_pk = $data['table_pk'];
                $msgchatHeader->subject = "Onboarding Process Ticket";

                if (!$this->MsgchatHeaders->save($msgchatHeader)) { $headerStatus = false; }
            }

            // Save Footer
            if ($headerStatus) {
                $msgFooter = array();
                $msgFooter['msgchat_header_id'] = $msgchatHeader->id;
                $msgFooter['group_id'] = $data['group_id'];
                $msgFooter['sender_id'] = $data['sender_id'];
                $msgFooter['message'] = $data['message'];
                $msgFooter['seen'] = "0";

                $MsgchatFooter = $this->MsgchatFooters->patchEntity($MsgchatFooter, $msgFooter);
                if ($this->MsgchatFooters->save($MsgchatFooter)) { 
                    $conn = ConnectionManager::get('default');
                    $query = "SELECT mf.id, mh.table_name, mh.table_pk, mh.subject, mf.msgchat_header_id, mf.sender_id, mf.group_id,
                    case when mf.group_id != 3 then concat(u.first_name,' ',u.last_name) else vt.name end as fullname, mf.message, mf.seen, mf.addeddate, mf.updateddate
                    FROM msgchat_headers mh left join msgchat_footers mf on mh.id=mf.msgchat_header_id
                    left join users u on u.id = mf.sender_id
                    left join vendor_temps vt on mf.sender_id = vt.id 
                    where mf.id=".$MsgchatFooter->id;
                    $rfqDetails = $conn->execute($query)->fetchAll('assoc');
                    $response = ['status' => 1, 'message' => 'success', 'data'=>$rfqDetails];

                    try{
                        $vendorTemp = $this->VendorTemps->get($data['sender_id']);
                        $filteredBuyers = $this->Buyers->find()
                            ->select(['Buyers.id','user_id'=> 'Users.id'])
                            ->innerJoin(['Users' => 'users'], ['Users.username = Buyers.email'])
                            ->where(['company_code_id' => $vendorTemp['company_code_id'], 'purchasing_organization_id' => $vendorTemp['purchasing_organization_id'], 'Users.status' => 1]);

                        foreach ($filteredBuyers as $buyer) {
                            $n = $this->Notifications->find()->where(['user_id' => $buyer->user_id, 'notification_type'=>'New Message'])->first();
                            if ($n) {
                                $n->notification_type = 'New Message';
                                $n->message_count = $n->message_count+1;
                            } else {
                                $n = $this->Notifications->newEntity([
                                    'user_id' => $buyer->user_id,
                                    'notification_type' => 'New Message',
                                    'message_count' => '1',
                                ]);
                            }
                            $this->Notifications->save($n);
                        }
                    } catch (\Exception $e) {
                        $response = ['status' => 0, 'message' => $e->getMessage(), 'data' => 'Invalid request'];
                    }
                }
            }
        } else { $response = ['status' => 0, 'message' => 'failed', 'data' => 'Invalid request']; }
        echo json_encode($response);
    }


    public function seenUpdate($app=null, $app_id=null, $sender_id=null)
    {
        $response = ['status' => 1, 'message' => 'seen messsage'];
        $this->loadModel('MsgchatHeaders');
        $this->loadModel('MsgchatFooters');
        $headid = $this->MsgchatHeaders->find('all')->where(['table_name' => $app, 'table_pk'=> $app_id])->first();
        if ($this->MsgchatFooters->updateAll(['seen' =>1 ],['msgchat_header_id'=>$headid->id, 'sender_id'=> $sender_id])) {
            $response = ['status' => 1, 'message' => 'seen messsage'];
            echo json_encode($response); exit();
        }
        echo json_encode($response); exit();
    }



    /**
     * Edit method
     *
     * @param string|null $id Msgchat Header id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $flash = [];
        $msgchatHeader = $this->MsgchatHeaders->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $msgchatHeader = $this->MsgchatHeaders->patchEntity($msgchatHeader, $this->request->getData());
            if ($this->MsgchatHeaders->save($msgchatHeader)) {
                $flash = ['type'=>'success', 'msg'=>'The msgchat header has been saved'];
                $this->set('flash', $flash);
                return $this->redirect(['action' => 'index']);
            }
            $flash = ['type'=>'error', 'msg'=>'The msgchat header could not be saved. Please, try again'];
            $this->set('flash', $flash);
        }
        $this->set(compact('msgchatHeader'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Msgchat Header id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $flash = [];
        $this->request->allowMethod(['post', 'delete']);
        $msgchatHeader = $this->MsgchatHeaders->get($id);
        if ($this->MsgchatHeaders->delete($msgchatHeader)) {
            $flash = ['type'=>'success', 'msg'=>'The msgchat header has been deleted'];
        } else {
            $flash = ['type'=>'error', 'msg'=>'The msgchat header could not be deleted. Please, try again'];
        }
        $this->set('flash', $flash);
        return $this->redirect(['action' => 'index']);
    }
}
