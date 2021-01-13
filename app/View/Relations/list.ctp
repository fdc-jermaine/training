<table class="table">
    <thead></thead>
    <tbody>
        <?php foreach($messages as $message) : ?>
            <tr>
                <!-- message if from login user -->
                <?php if ( $message['Relation']['sender_id'] == AuthComponent::user('id')) : ?>
                    <td id="td<?php echo $message['Relation']['receiver_id']; ?>">
                        <i class="fa fa-trash" onclick="deleteMessage(<?php echo $message['Relation']['receiver_id']; ?>)"></i>
                    </td>
                    <td><?php echo ucwords($message[0]['receiver_name']); ?></td>
                    <td>
                        <p>
                        <?php 
                            echo $this->Html->link(
                                $message['Message']['content'],
                                array('controller' => 'messages', 'action' => 'view', $message['Relation']['receiver_id']),
                                array('class' => 'message-link')
                            );
                        ?>
                        </p>
                        <span id="message-date"><?php echo date('Y/m/d H:i A', strtotime($message['Message']['created'])); ?></span>
                    </td>
                    <td class="text-center">
                        <?php 
                            $image = $message[0]['sender_image'] ? '/profile/'.$message[0]['sender_image'] : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png';
                            echo $this->Html->image($image);     
                        ?>
                    </td>
                <!-- message is not from login user -->
                <?php else: ?>
                    <td id="td<?php echo $message['Relation']['sender_id']; ?>">
                        <i class="fa fa-trash" onclick="deleteMessage(<?php echo $message['Relation']['sender_id']; ?>)"></i>
                    </td> 
                    <td><?php echo ucwords($message[0]['receiver_name']); ?></td>
                    <td class="text-center">
                        <?php 
                            $image = $message[0]['receiver_image'] ? '/profile/'.$message[0]['receiver_image'] : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png';
                            echo $this->Html->image($image);     
                        ?>
                    </td>    
                    <td>
                        <p>
                        <?php 
                            echo $this->Html->link(
                                $message['Message']['content'],
                                array('controller' => 'messages', 'action' => 'view', $message['Relation']['sender_id']),
                                array('class' => 'message-link')
                            );
                        ?>
                        </p>
                        <span id="message-date"><?php echo date('Y/m/d H:i A', strtotime($message['Message']['created'])); ?></span>
                    </td>                                
                <?php endif; ?>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
<div class="col-md-12 text-center">
    <?php if ($this->Paginator->hasNext()): ?>
    <button class="btn btn-sm btn-primary" onclick="paginate(<?php echo $count + $perpage; ?>)">See More</button>
    <?php endif; ?>
</div>