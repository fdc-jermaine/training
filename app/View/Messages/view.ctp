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
                <div class="message-loading text-center">
                    <?php echo $this->Html->image('200.gif', array('class' => 'loading')); ?>
                </div>

                <div id="message-here"></div>

            <div class="col-sm-2"></div>        
        </div>
    </div>
</section>

<script>
$(document).ready(function() {   
    let user_id = "<?php echo $user['User']['id']; ?>";
    let count = "<?php echo $count; ?>"
    getMessages(user_id, count); 

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
                success: function (result, status) {  
                    var data = jQuery.parseJSON(result);
                    if(data.success) {
                        getMessages(user_id, count); 
                    }
                    $('#content-id').val("")
                }
            });
        } else {
            alert('Please type your message.')
        }
    });
});


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
function paginate(count) {
    let user_id = "<?php echo $user['User']['id']; ?>";
    $('#message-here').html('');
    $('.loading').fadeIn();
    getMessages(user_id, count)
}
function getMessages(id, count) {
    let url = "<?php echo $this->Html->url(array('controller' => 'messages','action' => 'ajax', )); ?>";
    url = url+'/'+id+'/'+count
    $.ajax({
        'type': "get",
        'url': url,
        evalScripts: true,
        success: function (data, status) {  
            console.log(data)
            $('.loading').fadeOut('slow', function() {                
                $('#message-here').html(data);
            })
        }
    });
}
</script>
