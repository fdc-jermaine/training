<table class="table">
    <thead></thead>
    <tbody id="message-body">
        <?php foreach($messages as $message) : ?>
            <tr id="tr<?php echo $message['Message']['id']; ?>">
                <!-- message if from login user -->
                <?php if ( $message['Message']['from_id'] == AuthComponent::user('id')) : ?>
                    <td><i class="fa fa-trash" onclick="deleteMessage(<?php echo $message['Message']['id']; ?>)"></i></td>
                    <td>
                        <p><?php echo $message['Message']['content']; ?></p>
                        <span id="message-date"><?php echo date('Y/m/d h:i A', strtotime($message['Message']['created'])); ?></span>
                    </td>
                    <td class="text-right">
                        <?php 
                            $image = $message['Sender']['senderimage'] ? '/profile/'.$message['Sender']['senderimage'] : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png';
                            echo $this->Html->image($image);     
                        ?>
                    </td>
                    <!-- message is not from login user -->
                <?php else: ?>
                    <td><i class="fa fa-trash" onclick="deleteMessage(<?php echo $message['Message']['id']; ?>)"></i></td>                                    
                    <td class="text-left">
                        <?php 
                            $image = $message['Sender']['senderimage'] ? '/profile/'.$message['Sender']['senderimage'] : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png';
                            echo $this->Html->image($image);     
                        ?>
                    </td>    
                    <td>
                        <p><?php echo $message['Message']['content']; ?></p>
                        <span id="message-date"><?php echo date('Y/m/d h:i A', strtotime($message['Message']['created'])); ?></span>
                    </td>                                
                <?php endif; ?>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
<div class="col-md-12 text-center">
    <?php  if ($this->Paginator->hasNext()) : ?>
        <button class="btn btn-sm btn-primary" onclick="paginate(<?php echo $count + 10; ?>)">See More</button>
    <?php endif; ?>
</div>
