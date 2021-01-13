<?php 
    
class RelationsController extends AppController {
    public function list($count = 1) {
        $authId = $this->Auth->user('id');          
        $perpage = 1;   

        $this->paginate = array('Relation' => array(
            'fields' => array(
                't2.*',
                'Message.*',
                'Relation.*',
                '(SELECT id from users WHERE id = Relation.sender_id) AS sender_id',
                '(SELECT name from users WHERE id = Relation.sender_id) AS sender_name',
                '(SELECT image from users WHERE id = Relation.sender_id) AS sender_image',
                '(SELECT id from users WHERE id = Relation.receiver_id) AS receiver_id',    
                '(SELECT name from users WHERE id = Relation.receiver_id) AS receiver_name',    
                '(SELECT image from users WHERE id = Relation.receiver_id) AS receiver_image'          
            ), 
            'conditions' => array(
                "Relation.sender_id = {$authId} OR Relation.receiver_id = {$authId}",
            ),
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
                                MAX(id) AS max_id
                                FROM relations 
                                GROUP BY
                                    LEAST(sender_id, receiver_id),
                                    GREATEST(sender_id, receiver_id))",
                    'alias' => 't2',
                    'conditions' => 'LEAST(Relation.sender_id, Relation.receiver_id) = t2.sender_id AND
                        GREATEST(Relation.sender_id, Relation.receiver_id) = t2.receiver_id AND
                        Relation.id = t2.max_id'
                )
            )
        ));

        $messages = $this->paginate('Relation');
        $this->layout = false;
        $this->set(compact('messages', 'count', 'perpage'));
    }
}