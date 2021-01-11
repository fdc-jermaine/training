<section class="content">
    <div class="container">
        <div class="row">
        <div class="col-sm-12 text-center" id="page-title">
            <h2 class="form-title">Edit Profile</h2>
        </div>           
        <div class="row">       
            <div class="col-md-4" id="profile-box" >
                <?php echo $this->Form->create('User', array('type' => 'file')); ?>  
                <?php 
                    echo $this->Form->input(
                        'image', 
                        array('type' => 'file', 'id' => 'profile', 'label' => false)
                    ); 
                    $image = AuthComponent::user('image') ? '/profile/'.AuthComponent::user('image') : 'https://www.pimacountyfair.com/wp-content/uploads/2016/07/user-icon-6.png';
                    echo $this->Html->image(
                        $image, 
                        array('class' => 'profile-image', 'id' => 'profile-image')
                    );
                ?>
            </div>
            <div class="col-md-8" id="profile-info">
                <div class="form-group>">
                <?php 
                    // name
                    echo $this->Form->input(
                        'name', 
                        array('id' => 'name', 'placeholder' => 'Name', 'value' => AuthComponent::user('name'))
                    );

                    // birthdate              
                    $birthdate = AuthComponent::user('birthdate') ? date('m/d/Y', strtotime(AuthComponent::user('birthdate'))) : '';
                    echo $this->Form->input(
                        'birthdate',
                        array('id' => 'datepicker', 'type' => 'text', 'placeholder' => 'birthdate', 'value' => $birthdate)
                    );

                    // gender
                    echo $this->Form->input('gender', array(
                        'separator'=> '<div></div>',
                        'options' => array('1' => 'Male', '2' => 'Female'),
                        'type' => 'radio',
                        'value' => AuthComponent::user('gender')                            
                    )); 

                    // hubby
                    $hubby = AuthComponent::user('hubby') ? AuthComponent::user('hubby') : '';
                    echo $this->Form->input(
                        'hubby', 
                        array('id' => 'name', 'placeholder' => 'Please type you hubby.', 'value' => $hubby, 'rows' => 4, 'id' => 'hubby')
                    );                     
                ?>
                <?php echo $this->Form->end('Save'); ?> 
            </div> 

           
        </div> 
           
    </div>
</section>

<script>
$(function() {
    $("#datepicker").datepicker();
});
$(document).ready(function() {
    $("#profile").change(function() {
        readURL(this);
    });
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#profile-image').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
