<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center" id="page-title">
                <h2 class="form-title"><?php echo ucwords($user['User']['name']); ?></h2>                
            </div>

            <div class="col-sm-2"></div>
            <div class="col-sm-8" id="message-table-box">   
                <div class="col-sm-12 text-right" id="reply-box">                    
                    <?php 
                        echo $this->Form->input(
                            'content',
                            array('rows' => '3', 'label' => false, 'placeholder' => 'Reply message here..', 'id' => 'content-id')
                        );
                    ?> 
                    <input type="hidden" id="to_id" value="<?php echo $user['User']['id']; ?>"></input>
                    <button class="btn btn-sm btn-primary" id="reply">[ Reply ]</button>
                </div>
                
                <table class="table">
                    <thead></thead>
                    <tbody id="message-body">
                        <?php foreach($messages as $message) : ?>
                            <tr id="tr<?php echo $message['Message']['id']; ?>">
                                <!-- message if from login user -->
                                <?php if ( $message['Message']['from_id'] == AuthComponent::user('id')) : ?>
                                    <td><i class="fa fa-trash" onclick="deleteMessage(<?php echo $message['Message']['id']; ?>)"></i></td>
                                    <td><?php echo ucwords($message['Sender']['sendername']); ?></td>
                                    <td>
                                        <p><?php echo $message['Message']['content']; ?></p>
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
                                    <td><i class="fa fa-trash" onclick="deleteMessage(<?php echo $message['Message']['id']; ?>)"></i></td>
                                    <td><?php echo ucwords($message['Sender']['sendername']); ?></td>
                                    <td class="text-center">
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
                
            </div>
            <div class="col-sm-2"></div>        
        </div>
    </div>
</section>

<script>
$(document).ready(function() {   
    const REPLY = {
        content: '',
        to_id: ''
    }

    $('#reply').click(function() {
        let url = "<?php echo $this->Html->url(array('controller' => 'messages','action' => 'reply')); ?>";
        REPLY.content = $('#content-id').val();
        REPLY.to_id = $('#to_id').val();
        if(REPLY.content != "") {
            $.ajax({
                'type': "post",
                'url': url,
                evalScripts: true,
                data:(REPLY),
                success: function (data, status) {  
                    $('#content-id').val("")
                    addRow(data)
                }
            });
        } else {
            alert('Please type your message.')
        }
    });
});

function addRow(result) {
    var data = jQuery.parseJSON(result);
    console.log(result);
    var row = 
        '<tr id=tr'+data.id+'>' +
            '<td><i class="fa fa-trash" onclick="deleteMessage('+data.id+')"></i></td>' +
            '<td>'+data.sendername+'</td>' + 
            '<td><p>'+data.content+'</p><span>'+data.created+'</span></td>' +
            '<td class="text-center"><img src='+data.senderimage+'></td>' +
        '</tr>'
    $(row).prependTo('#message-body')
}

function deleteMessage(id) {
    let url = "<?php echo $this->Html->url(array('controller' => 'messages','action' => 'deleteById')); ?>";
    if (!confirm('Are you sure that you want to delete message?')) {
        e.preventDefault();
    } else {
        $.ajax({
            'type': "post",
            'url': url,
            evalScripts: true,
            data:({id:id}),
            success: function (data, status) { 
                console.log(data);  
                $('#tr'+id).fadeOut();       
            }
        });
    } 
}
</script>
