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
                $this->Session->setFlash(__('Incorrect User name or password.'));
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
                $this->Session->setFlash(__('Failed to save user.'));
            }
        }
    }

    public function profile() {
        
    }

    public function edit() {
        if ($this->request->is('post')) {            
            $this->User->set($this->request->data);
           
            $birthdate = $this->request->data['User']['birthdate'] ? date('Y-m-d', strtotime($this->request->data['User']['birthdate'])) : null;
            $gender = $this->request->data['User']['gender'] ? $this->request->data['User']['gender'] : null;
            $hubby = $this->request->data['User']['hubby'] ? $this->request->data['User']['hubby'] : null;
            $name = $this->request->data['User']['name'];
            $ip = $this->request->clientIp();       
                 
            if($this->request->data['User']['image'] == '') {
                unset($this->User->validate['image']);
            }
            // remove image validation if no image found
            if (empty($this->request->data['User']['image']['tmp_name'])) {
                echo 'here';
                unset($this->User->validate['image']);
            }            
            // form validation
            if ($this->User->validates()) { 
                $data = array(
                    'name' => "'$name'",
                    'birthdate' => "'$birthdate'",
                    'gender' => "'$gender'",
                    'hubby' => "'$hubby'",
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
}