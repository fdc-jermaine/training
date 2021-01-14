<?php 
    
class RelationsController extends AppController {
    public function list($count = 1) {
        $authId = $this->Auth->user('id');          
        $perpage = 1;   

        $this->paginate = array('Relation' => array(
            'fields' => array(
                'Message.*',
                'Relation.*',
                'Sender.name as sender_name',
                'Sender.id as sender_id',
                'Sender.image as sender_image',
                'Receiver.name as receiver_name',
                'Receiver.id as receiver_id',
                'Receiver.image as receiver_image'
            ), 
            'conditions' => array(
                "Relation.sender_id = {$authId} 
                || Relation.receiver_id = {$authId}",
            ),
            'order' => 'Message.created DESC',
            'limit' => $count,
            'joins' => array(      
                array(
                    'type' => 'LEFT',
                    'table' => 'messages',
                    'alias' => 'Message',
                    'conditions' => 'Message.relation_id = Relation.id'
                ),         
                array(
                    'type' => 'INNER',
                    'table' => "(SELECT
                                LEAST(sender_id, receiver_id) AS sender_id,
                                GREATEST(sender_id, receiver_id) AS receiver_id,
                                MAX(r2.id) AS max_id
                                FROM relations as r2
                                LEFT JOIN messages as m2
                                ON m2.relation_id = r2.id
                                WHERE m2.status != 'deleted'
                                GROUP BY
                                    LEAST(sender_id, receiver_id),
                                    GREATEST(sender_id, receiver_id))",
                    'alias' => 't2',
                    'conditions' => 'Relation.id = t2.max_id'
                ),
                array(
                    'type' => 'LEFT',
                    'table' => 'users',
                    'alias' => 'Sender',
                    'conditions' => 'Sender.id = Relation.sender_id'
                ),
                array(
                    'type' => 'LEFT',
                    'table' => 'users',
                    'alias' => 'Receiver',
                    'conditions' => 'Receiver.id = Relation.receiver_id'
                )
            )
        ));

        $messages = $this->paginate('Relation');
       
        $this->layout = false;
        $this->set(compact('messages', 'count', 'perpage'));
    }
}