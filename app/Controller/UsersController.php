<?php 

class UsersController extends AppController {    
    public $helpers = array('Js' => array('Jquery'), 'Paginator');

    public function beforeFilter() {
        parent::beforeFilter();        
        $this->Auth->allow('add', 'logout');
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {               
                $this->User->id = $this->Auth->user('id');
                $this->User->saveField('last_login_time', date('Y-m-d H:i:s'));
                $this->redirect(
                    array('controller' => 'messages', 'action' => 'messageList')
                );
                $this->Session->setFlash(__('User successfully login.'));
            } else {
                $this->Session->setFlash(__('Incorrect Email or password.'));
            }
        }
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->request->data['User']['created_ip'] = $this->request->ClientIp();
            if ($this->User->save($this->request->data)) {
                if ($this->Auth->login()) {
                    $this->User->id = $this->Auth->user('id');
                    $this->User->saveField('last_login_time', date('Y-m-d H:i:s'));
                    $this->redirect(
                        array('action' => 'thankYou')
                    );
                    $this->Session->setFlash(__('User successfully login.'));
                } else {
                    $this->Session->setFlash(__('User not login'));
                }
               
            } else {
                $this->Session->setFlash(__('Unable to register.'));
            }
        }
    }

    public function profile() {
        
    }

    public function edit() {
        if ($this->request->is('post')) {         
            $data = array();   
            $this->User->set($this->request->data);  

            if ($this->request->data['User']['birthdate'] != '')  {
                $data['birthdate'] = $this->request->data['User']['birthdate'];
            }    
            if ($this->request->data['User']['gender'] != '') {
                $data['gender'] = $this->request->data['gender'];
            }
            if ($this->request->data['User']['hubby']) {
                $data['hubby'] = $this->request->data['User']['hubby'];
            }

            if($this->request->data['User']['image'] == '') {
                unset($this->User->validate['image']);
            }
            // remove image validation if no image found
            if (empty($this->request->data['User']['image']['tmp_name'])) {
                unset($this->User->validate['image']);
            }            
            // form validation
            if ($this->User->validates()) { 
                $name = $this->request->data['User']['name'];
                $ip = $this->request->clientIp();      
                $data = array(
                    'name' => "'$name'",    
                    'modified_ip' => "'$ip'"
                );
                // check if post image is present
                if (!empty($this->request->data['User']['image']['tmp_name'])
                    && is_uploaded_file($this->request->data['User']['image']['tmp_name'])) {
                    
                    $temp = explode('.', $this->request->data['User']['image']['name']);
                    $newFileName = round(microtime(true)).'.'.end($temp);
                    move_uploaded_file(
                        $this->request->data['User']['image']['tmp_name'],
                        WWW_ROOT . DS . 'profile/' . $newFileName
                    );   
                    $data['image'] = "'$newFileName'";
                } 
                // updating data
                $this->User->updateAll(
                    $data,
                    array('id' => $this->Auth->user('id'))
                );
                // resave data to session 
                $this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));   
                $this->redirect(
                    array('action' => 'profile')                    
                );            
            } 
        }
    }
    
    public function thankYou() {

    }

    public function logout() {
        $this->Auth->logout();
        $this->redirect(
            array('action' => 'login')
        );
    }

    public function create() {

    }

    public function userList() {
        $result = array();
        if($this->request->is('get')) {
            $term = $this->request->query['searchTerm'];
            $users = $this->User->find('all', array(
                'conditions' => array(
                    'User.name LIKE' => '%'.$term.'%'
                )
            ));

            $result = array();
            foreach($users as $key => $user) {
                $result[$key]['id'] = (int) $user['User']['id'];
                $result[$key]['text'] = $user['User']['name'];
            }
        }
        
        echo json_encode($result);
        exit;
    }
    
}