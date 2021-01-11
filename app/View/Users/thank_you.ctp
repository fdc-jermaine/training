<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center" id="page-title">
                <h2 class="form-title">Thank You!</h2>                
            </div>
            <div class="col-sm-3"></div>
            <div class="col-sm-6 text-center">
                <?php 
                    echo $this->Html->link(
                        __('[ Back to Homepage ]'), 
                        array('controller' => 'messages', 'action' => 'messageList'),
                        array('class' => 'btn btn-primary')
                    );
                ?>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
</section>
