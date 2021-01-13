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
                <div class="message-loading text-center">
                    <?php echo $this->Html->image('200.gif', array('class' => 'loading')); ?>
                </div>
                <!-- data must go here -->
                <div class="message-ajax-box"></div>
                
            </div>
            <div class="col-sm-2"></div>        
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    count = "<?php echo $count; ?>";
    getUserMessage(count);   
});

function deleteMessage(id) {
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
                $('#td'+id).closest('tr').fadeOut();      
            }
        });
    }     
}

function paginate(count) {
    $('.message-ajax-box').html('');
    $('.loading').fadeIn();
    getUserMessage(count)
}

function getUserMessage(count) {
    let url = "<?php echo $this->Html->url(array('controller' => 'relations','action' => 'list')); ?>";
    url = url+'/'+count;
    $.ajax({
        'type': "get",
        'url': url,
        evalScripts: true,
        success: function (data, status) { 
            $('.loading').fadeOut('fast', function() {                
                $('.message-ajax-box').html(data);
            })
        }
    });
}
</script>
