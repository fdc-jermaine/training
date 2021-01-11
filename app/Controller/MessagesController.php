<?php 

class MessagesController extends AppController {
    public $components = array('Paginator');
    public $paginate = array();

    public function create() {
        $this->loadModel('User');
        $users = $this->User->find('all');
        if ($this->request->is('post')) {
            if ($this->request->data['Message']['to_id'] == 0) {
                $this->Session->setFlash(__('Please select recipient'));
            } else {
                $id = $this->request->data['Message']['to_id'];
                $authId = $this->Auth->user('id');
                $this->request->data['Message']['from_id'] = $this->Auth->user('id');
                $update = "
                    UPDATE messages as Message
                    SET is_new='0'
                    WHERE ((Message.from_id = ".$id." && Message.to_id = ".$authId.") 
                        || (Message.from_id = ".$authId." && Message.to_id = ".$id.")) && Message.is_new = '1'
                ";
                $update = $this->Message->query($update);
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
                ),
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
                ),
            )
        );

        $messages = $this->Paginator->paginate();
        $this->set(compact('messages', 'user'));
    }

    public function delete() {
        $authId = $this->Auth->user('id');   
        $id = $this->request->data['id']; 
        if ($this->request->is('post')) {
            $query = "
                DELETE FROM messages 
                WHERE ((from_id = ".$id." && to_id = ".$authId.") 
                    || (from_id = ".$authId." && to_id = ".$id."))
            ";
            $this->Message->query($query);
        } else {
            // code if not deleted
        }
        exit;
    }

    public function deleteById() {
        $id = $this->request->data['id']; 
        $query = "
                DELETE FROM messages 
                WHERE id=".$id."
            ";
        $query = $this->Message->query($query);
        $this->Message->query("UPDATE messages SET is_new='1' WHERE id = (SELECT max(id) FROM messages)");
       
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
            $update = "
                UPDATE messages as Message
                SET is_new='0'
                WHERE ((Message.from_id = ".$id." && Message.to_id = ".$authId.") 
                    || (Message.from_id = ".$authId." && Message.to_id = ".$id.")) && Message.is_new = '1'
            ";
            $update = $this->Message->query($update);

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
        $query = "
            SELECT 
                Message.*, 
                Sender.id senderid, 
                Sender.name as sendername, 
                Sender.image senderimage, 
                Receiver.id receiverid,
                Receiver.name receivername, 
                Receiver.image receiverimage
            FROM messages AS Message 
            LEFT JOIN users as Sender 
            ON Sender.id = Message.from_id
            LEFT JOIN users as Receiver
            ON Receiver.id = Message.to_id 
            WHERE Message.id = ".$id."
        ";     
        $query = $this->Message->query($query);
        if(count($query) > 0) {
            $return = array(
                'success' => true,
                'id' => $query[0]['Message']['id'],
                'to_id' => $query[0]['Message']['to_id'],
                'content' => $query[0]['Message']['content'],
                'created' => date('Y/m/d h:i A', strtotime($query[0]['Message']['created'])),
                'sendername' => $query[0]['Sender']['sendername'],
                'senderimage' => $query[0]['Sender']['senderimage'] ? '/cakephp/profile/'.$query[0]['Sender']['senderimage'] : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png'
            );
        } 
        
        return json_encode($return);
    }
}