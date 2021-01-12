<table class="table">
    <thead></thead>
    <tbody>
        <?php foreach($messages as $message) : ?>
            <tr>
                <!-- message if from login user -->
                <?php if ( $message['Message']['from_id'] == AuthComponent::user('id')) : ?>
                    <td id="td<?php echo $message['Message']['to_id']; ?>">
                        <i class="fa fa-trash" onclick="deleteMessage(<?php echo $message['Message']['to_id']; ?>)"></i>
                    </td>
                    <td><?php echo ucwords($message['Receiver']['receivername']); ?></td>
                    <td>
                        <p>
                        <?php 
                            echo $this->Html->link(
                                $message['Message']['content'],
                                array('controller' => 'messages', 'action' => 'view', $message['Message']['to_id']),
                                array('class' => 'message-link')
                            );
                        ?>
                        </p>
                        <span id="message-date"><?php echo date('Y/m/d H:i A', strtotime($message['Message']['created'])); ?></span>
                    </td>
                    <td class="text-center">
                        <?php 
                            $image = $message['Sender']['senderimage'] ? '/profile/'.$message['Sender']['senderimage'] : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png';
                            echo $this->Html->image($image);     
                        ?>
                    </td>
                <!-- message is not from login user -->
                <?php else: ?>
                    <td id="td<?php echo $message['Message']['to_id']; ?>">
                        <i class="fa fa-trash" onclick="deleteMessage(<?php echo $message['Message']['from_id']; ?>)"></i>
                    </td> 
                    <td><?php echo ucwords($message['Sender']['sendername']); ?></td>
                    <td class="text-center">
                        <?php 
                            $image = $message['Sender']['senderimage'] ? '/profile/'.$message['Sender']['senderimage'] : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png';
                            echo $this->Html->image($image);     
                        ?>
                    </td>    
                    <td>
                        <p>
                        <?php 
                            echo $this->Html->link(
                                $message['Message']['content'],
                                array('controller' => 'messages', 'action' => 'view', $message['Message']['from_id']),
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
    <button class="btn btn-sm btn-primary" onclick="paginate(<?php echo $count + 10; ?>)">See More</button>
    <?php endif; ?>
</div>