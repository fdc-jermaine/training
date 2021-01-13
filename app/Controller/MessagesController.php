<?php 

class MessagesController extends AppController {
    public $components = array('Paginator');
    public $pageLimit = 10;

    public function create() {
        $this->loadModel('User');
        $this->loadModel('Relation');        
        $users = $this->User->find('all');
        if ($this->request->is('post')) {
            // check if no recipient            

            if ($this->request->data['Relation']['receiver_id'] == 0) {
                $this->Session->setFlash(__('Please select recipient'));
            } else {
                $flag = true;
                $authId = $this->Auth->user('id');
                $dataSource = $this->Relation->getDataSource(); 
                $dataSource->begin();

                $this->request->data['Relation']['sender_id'] = $this->Auth->user('id');

                if (!$this->Relation->save($this->request->data['Relation'])) {
                    $flag = false;
                }
                $this->request->data['Message']['relation_id'] = $this->Relation->id;
                if (!$this->Message->save($this->request->data['Message'])) {
                    $flag = false;
                }

                if ($flag) {
                    $dataSource->commit();
                    $this->Session->setFlash(__('Message sent.'));
                    $this->redirect(array('action' => 'messageList'));
                } else {
                    $relationSource->rollback();
                    $this->Session->setFlash(__($ex->getMessage()));
                }              
            }            
        }
        $this->set('users', $users);
    }

    public function messageList() {       
        $count = $this->pageLimit;
        $this->set(compact('count'));
    }

    public function view($id = null) {
        $authId = $this->Auth->user('id');

        $count = $this->pageLimit;
        // get the query user
        $this->loadModel('User');
        $this->User->id = $id;
        $user = $this->User->read();
                
        
        $this->set(compact('user', 'count'));
    }

    public function delete() {
        $authId = $this->Auth->user('id');   
        $id = $this->request->data['id']; 
        if ($this->request->is('post')) {
            // update status to deleted
            $this->Message->updateAll(
                array('status' => '"deleted"'),
                array(
                    'OR' => array(
                        array('from_id' => $id, 'to_id' => $authId),
                        array('from_id' => $authId, 'to_id' => $id)
                    )
                )
            );     
              
        } else {
            // code if not deleted
        }
        exit;
    }

    public function deleteById() {
        $id = $this->request->data['id']; 
        $this->Message->id = $id;
        $message = $this->Message->read();        
       
        $this->Message->updateAll(
            array('status' => '"deleted"', 'is_new' => '"0"'),
            array('id' => $id)
        ); 
        $this->Message->query("
            UPDATE messages 
            SET is_new='1' 
            where id = (SELECT id
                from messages WHERE 
                (status != 'deleted') 
                && ((from_id =".$message['Message']['from_id']." && to_id = ".$message['Message']['to_id'].") 
                    || (from_id = ".$message['Message']['to_id']." && to_id = ".$message['Message']['from_id']."))
                ORDER BY created DESC
                LIMIT 1)
        ");        
        exit;        
    }

    public function reply() {
        if ($this->request->is('post')) {
            $id = $this->request->data['to_id']; 
            $authId = $this->Auth->user('id');           
            $flag = true;

            $this->request->data = array(
                'Message' => array(
                    'content' => $this->request->data['content']
                ),
                'Relation' => array(
                    'receiver_id' =>$id,
                    'from_id' => $this->Auth->user('id')
                )
            );
            
            $dataSource = $this->Relation->getDataSource(); 
            $dataSource->begin();

            if ($this->Message->save($this->request->data)) {                  
                echo json_encode(
                    array('success' => true)
                );
            } else {
                echo json_encode(
                    array('success' => false)
                );
            }            
        }
        exit;
    }

    public function ajax($id = null, $count = 10) {
        $this->layout = false;
        $authId = $this->Auth->user('id');
        $perpage = $this->pageLimit;
        $this->Paginator->settings = array(
            'fields' => array(
                'Message.*',
                'Relation.*',
                'Sender.id as senderid',
                'Sender.name as sendername',
                'Sender.image as senderimage',
                'Receiver.id as receiverid',
                'Receiver.name as receivername',
                'Receiver.image as receiverimage'
            ),
            'conditions' => array(
                'OR' => array(
                    array('Relation.sender_id' => $id, 'Relation.receiver_id' => $authId),
                    array('Relation.sender_id' => $authId, 'Relation.receiver_id' => $id)
                ),
                array('status !=' => 'deleted')
            ),
            'order' => 'Message.created DESC',
            'limit' => $count,
            'joins' => array(
                array(
                    'type' => 'Left',
                    'table' => 'relations',
                    'alias' => 'Relation',
                    'conditions' => 'Relation.id = Message.relation_id'
                ),
                array(
                    'type' => 'LEFT',
                    'table' => 'users',
                    'alias' => 'Sender',
                    'conditions' => 'Sender.id= Relation.sender_id'
                ),
                array(
                    'type' => 'LEFT',
                    'table' => 'users',
                    'alias' => 'Receiver',
                    'conditions' => 'Receiver.id= Relation.receiver_id'
                )
            )
        );

        $messages = $this->Paginator->paginate();
        
        $this->set(compact('messages', 'count', 'id', 'perpage'));
    }

    public function list($count = 10) {
        $authId = $this->Auth->user('id');  
        $perpage = $this->pageLimit;         
        $this->Paginator->settings = array(
            'fields' => array(
                't1.*',
                'm1.*',
                'Sender.name as sendername',
                'Sender.image as senderimage',
                'Receiver.id as receiverid',
                'Receiver.name as receivername',
                'Receiver.image as receiverimage'
            ),
            'conditions' => array(
                array(
                    'OR' => array('Message.from_id' => $authId, 'Message.to_id' => $authId),
                ),
                array('Message.is_new' => '1'),
                array('status !=' => 'deleted')        
            ),
            'order' => 'Message.created DESC',
            'limit' => $count,
            'joins' => array(
                array(
                    'type' => 'LEFT',
                    'table' => 'users',
                    'alias' => 'Sender',
                    'conditions' => 'Sender.id = Message.from_id'
                ),
                array(
                    'type' => 'LEFT',
                    'table' => 'users',
                    'alias' => 'Receiver',
                    'conditions' => 'Receiver.id = Message.to_id'
                )
            )
        );
        $messages = $this->Paginator->paginate();
        $this->layout = false;
        $this->set(compact('messages', 'count', 'perpage'));
    }
}