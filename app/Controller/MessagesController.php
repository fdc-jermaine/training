<?php 

class MessagesController extends AppController {
    public $components = array('Paginator');
    public $paginate = array();

    public function create() {
        $this->loadModel('User');
        $users = $this->User->find('all');
        if ($this->request->is('post')) {
            // check if no recipient
            if ($this->request->data['Message']['to_id'] == 0) {
                $this->Session->setFlash(__('Please select recipient'));
            } else {
                $id = $this->request->data['Message']['to_id'];
                $authId = $this->Auth->user('id');
                $this->request->data['Message']['from_id'] = $this->Auth->user('id');
                 //  update is_new to 0 before saving new message
                $this->Message->updateAll(
                    array('is_new' => '"0"'),
                    array(
                        'OR' => array(
                            array('from_id' => $id, 'to_id' => $authId),
                            array('from_id' => $authId, 'to_id' => $id)
                        ),
                        array('is_new' => '1')
                    )
                );
                if ($this->Message->save($this->request->data)) {                  
                    $this->Session->setFlash(__('Message sent.'));
                    $this->redirect(array('action' => 'messageList'));
                } else {
                    $this->Session->setFlash(__('Message not sent.'));
                }
            }            
        }
        $this->set('users', $users);
    }

    public function messageList() {        
        $authId = $this->Auth->user('id');           
        $this->Paginator->settings = array(
            'fields' => array(
                'Message.*',
                'Sender.id as senderid',
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
                array('Message.is_new' => '1')          
            ),
            'order' => 'Message.created DESC',
            'limit' => 10,
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
        $data = $this->Paginator->paginate();
        $this->set('messages', $data);
    }

    public function view($id = null) {
        $authId = $this->Auth->user('id'); 
        // get the query user
        $this->loadModel('User');
        $this->User->id = $id;
        $user = $this->User->read();

        $this->Paginator->settings = array(
            'fields' => array(
                'Message.*',
                'Sender.id as senderid',
                'Sender.name as sendername',
                'Sender.image as senderimage',
                'Receiver.id as receiverid',
                'Receiver.name as receivername',
                'Receiver.image as receiverimage'
            ),
            'conditions' => array(
                'OR' => array(
                    array('Message.from_id' => $id, 'Message.to_id' => $authId),
                    array('Message.from_id' => $authId, 'Message.to_id' => $id)
                )
            ),
            'order' => 'Message.created DESC',
            'limit' => 10,
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
        $this->set(compact('messages', 'user'));
    }

    public function delete() {
        $authId = $this->Auth->user('id');   
        $id = $this->request->data['id']; 
        if ($this->request->is('post')) {
            $this->Message->deleteAll(
                array(
                    'OR' => array(
                        array('from_id' => $id, 'to_id' => $authId),
                        array('from_id' => $authId, 'to_id' => $id)
                    )
                ),
                false
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
       
        $this->Message->delete($id);
        $this->Message->query("
            UPDATE messages 
            SET is_new='1' 
            WHERE id = (SELECT max(id) 
                FROM messages 
                WHERE (to_id = ".$message['Message']['to_id']." && from_id = ".$message['Message']['from_id'].") ||
                (to_id = ".$message['Message']['from_id']." && from_id = ".$message['Message']['to_id']."))
        ");
       
        exit;        
    }

    public function reply() {
        if ($this->request->is('post')) {
            $id = $this->request->data['to_id']; 
            $authId = $this->Auth->user('id'); 

            $this->request->data = array(
                'Message' => array(
                    'content' => $this->request->data['content'],
                    'from_id' => $this->Auth->user('id'),
                    'to_id' => $this->request->data['to_id']
                )
            );
            //  update is_new to 0 before saving new message
            $this->Message->updateAll(
                array('is_new' => '"0"'),
                array(
                    'OR' => array(
                        array('from_id' => $id, 'to_id' => $authId),
                        array('from_id' => $authId, 'to_id' => $id)
                    ),
                    array('is_new' => '1')
                )
            );           

            if ($this->Message->save($this->request->data)) {                  
                echo $this->selectReply($this->Message->id);
            } else {
                $this->Session->setFlash(__('Message not sent.'));
            }            
        }
        exit;
    }

    public function selectReply($id = null) {
        $id = $id;
        $return = array();
        
        $query = $this->Message->find('first', array(
            'fields' => array(
                'Message.*', 
                'Sender.id as senderid', 
                'Sender.name as sendername', 
                'Sender.image as senderimage', 
                'Receiver.id as receiverid',
                'Receiver.name as receivername', 
                'Receiver.image as receiverimage'
            ),
            'conditions' => array('Message.id' => $id),
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'Sender',
                    'type' => 'LEFT',
                    'conditions' => 'Sender.id = Message.from_id'
                ),
                array(
                    'table' => 'users',
                    'alias' => 'Receiver',
                    'type' => 'LEFT',
                    'conditions' =>'Receiver.id = Message.from_id'
                )
            )     
        ));
        if(count($query) > 0) {
            $return = array(
                'success' => true,
                'id' => $query['Message']['id'],
                'to_id' => $query['Message']['to_id'],
                'content' => $query['Message']['content'],
                'created' => date('Y/m/d h:i A', strtotime($query['Message']['created'])),
                'sendername' => $query['Sender']['sendername'],
                'senderimage' => $query['Sender']['senderimage'] ? '/cakephp/profile/'.$query['Sender']['senderimage'] : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png'
            );
        } 
        
        return json_encode($return);
    }
}