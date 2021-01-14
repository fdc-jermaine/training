<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6" id="create-message">
            <?php echo $this->Flash->render(); ?>
            <?php echo $this->Form->create('Message'); ?>
                <div class="form-group">                    
                    <label>Send To:</label>
                    <?php 
                        echo $this->Form->input(
                            'Relation.receiver_id',
                            array('label' => false, 'class' => 'users form-control')
                        );
                    ?>
                </div>

                <div class="form-group">
                    <?php 
                        echo $this->Form->input(
                            'content',
                            array(
                                'rows' => 5, 
                                'id' => 'message-area', 
                                'class' => 'form-control', 
                                'label' => false, 
                                'placeholder' => 'Type your message here...'
                            )
                        ); 
                    ?>
                </div>
                <?php echo $this->Form->end('Send'); ?>
            </div>   
            <div class="col-sm-3"></div>     
        </div>
    </div>
</section>
<style>
form div {
    padding-bottom: 0px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    let url = "<?php echo $this->Html->url(array('controller' => 'users','action' => 'userList')); ?>";
    $('.users').select2({
        placeholder: 'Enter user\'s name here',
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,
            data: function (data) {
                return {
                    searchTerm: data.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results:response
                };
            },
            cache: true
        }
    });
});
</script>
