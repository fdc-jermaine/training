<?php 

App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    public $validate = array(
        'name' => array(
            'rule' => array('lengthBetween', 5, 20),
            'message' => 'Name should be at least 5 chars long'
        ),
        'email' => array(
            'required' => array(
                'rule'      => array('email'),
                'message'   => 'Kindly provide your email.'
            ),
            'unique' => array(
                'rule'      => 'isUnique',
                'message'   => 'Provided Email is already exist.'
            )
        ),
        'password' => array(
            'rule' => array('minLength', '6'),
            'message' => 'Minimum 6 characters long'
        ),
        'confirm_password' => array(
            'compare' => array(
                'rule'      => array('validate_password'),
                'message'   => 'The password you entered do not match' 
            )
        ),
        'image' => array(
            'rule' => array(
                'extension',
                array('gif', 'jpeg', 'png', 'jpg')
            ),
            'message' => 'Please supply valid image',
            'allowEmpty' => true,
            'required' => false
        )
    );


    public function validate_password() {
        return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
    }

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }
}