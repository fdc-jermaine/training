<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center" id="page-title">
                <h2 class="form-title">Login</h2>
            </div>
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <?php 
                    echo $this->Flash->render(); 
                    echo $this->Form->create('User');
                    echo $this->Form->input('email');
                    echo $this->Form->input('password');
                    echo $this->Form->end('Login');
                ?>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
</section>
