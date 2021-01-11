<?php

class Message extends AppModel {
    public $validate = array(
        'content' => array(
            'rule' => 'notBlank',
            'Message' => 'Please enter your message.'
        )
    );
}