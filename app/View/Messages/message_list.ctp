<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center" id="page-title">
                <h2 class="form-title">Message List</h2> 
                <?php 
                    echo $this->Html->link(
                        __('[ New Message ]'), 
                        array('controller' => 'messages', 'action' => 'create'),
                        array('class' => 'btn btn-primary')
                    );
                ?>
            </div>

            <div class="col-sm-2"></div>
            <div class="col-sm-8" id="message-table-box">   
                <?php echo $this->Flash->render(); ?>
                <table class="table">
                    <thead></thead>
                    <tbody>
                        <?php foreach($messages as $message) : ?>
                            <tr>
                                <!-- message if from login user -->
                                <?php if ( $message['Message']['from_id'] == AuthComponent::user('id')) : ?>
                                <td><i class="fa fa-trash" id="<?php echo $message['Message']['to_id']; ?>"></i></td>
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
                                    <span id="message-date"><?php echo date('Y/m/d h:i A', strtotime($message['Message']['created'])); ?></span>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        $image = $message['Sender']['senderimage'] ? '/profile/'.$message['Sender']['senderimage'] : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png';
                                        echo $this->Html->image($image);     
                                    ?>
                                </td>
                                <!-- message is not from login user -->
                                <?php else: ?>
                                <td><i class="fa fa-trash" id="<?php echo $message['Message']['from_id']; ?>"></i></td>
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
                                    <span id="message-date"><?php echo date('Y/m/d h:i A', strtotime($message['Message']['created'])); ?></span>
                                </td>                                
                                <?php endif; ?>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <div class="col-md-12 text-center">
                <?php                         
                    if ($this->Paginator->hasNext()) {
                        echo $this->Paginator->next('Show More');
                    } 
                ?>
                </div>
            </div>
            <div class="col-sm-2"></div>        
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#message-table-box i').click(function(e) {
        let id = $(this).attr('id');
        let _this = $(this);
        let url = "<?php echo $this->Html->url(array('controller' => 'messages','action' => 'delete')); ?>";
        if (!confirm('Are you sure that you want to delete message?')) {
            e.preventDefault();
        } else {
            $.ajax({
                'type': "post",
                'url': url,
                evalScripts: true,
                data:({id:id}),
                success: function (data, status) {     
                    _this.closest('tr').fadeOut();           
                }
            });
        } 
    });
});
</script>
