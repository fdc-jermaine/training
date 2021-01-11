<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
            <?php echo $this->Flash->render(); ?>
            <?php echo $this->Form->create('Message'); ?>
                <div class="form-group">                    
                    <label>Send To:</label>
                    <?php 
                        $options = array();
                        $options[0] = "Please type user name.";
                        foreach($users as $user) {
                            $options[$user['User']['id']] = $user['User']['name'];                }

                        // user list
                        echo $this->Form->input(
                            'to_id',
                            array(
                                'options' => $options,
                                'class' => 'usersList form-control',
                                'label' => false
                            )
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
        $('.usersList').select2({
            placeholder: "Select a user",
        });
        $(".usersList").select2({
            placeholder: "Select a customer",
            initSelection: function(element, callback) {                   
            }
        });
    });
</script>
