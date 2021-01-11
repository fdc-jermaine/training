<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center" id="page-title">
                <h2 class="form-title">User Profile</h2>
            </div>

            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-md-4" id="profile-box" >
                        <?php 
                            $image = AuthComponent::user('image') ? '/profile/'.AuthComponent::user('image') : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png';
                            echo $this->Html->image(
                                $image, 
                                array('class' =>'image-responsive')
                            ); 
                        ?>
                    </div>
                    <div class="col-md-8" id="profile-info">
                        <h4><?php echo ucwords(AuthComponent::user('name')); ?></h4>
                        <p>Gender: <span><?php echo AuthComponent::user('gender') == 1 ? 'Male' : 'Female'; ?></span></p>
                        <p>Birthdate: <span><?php echo date('F j, Y', strtotime(AuthComponent::user('birthdate'))); ?></span></p>
                        <p>Joined: <span><?php echo date('F j, Y', strtotime(AuthComponent::user('created'))); ?></span></p>
                        <p>Last Login: <span><?php echo date('F j, Y h:m A', strtotime(AuthComponent::user('last_login_time'))); ?></span></p>
                    </div>

                    <div class="col-md-12" id="profile-content">
                        <h6>Hubby:</h6>                        
                        <p class="content">
                            <?php echo AuthComponent::user('hubby'); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-2"></div>            
            
        </div>
    </div>
</section>
