<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'Training');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php	
		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
        echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css');
        echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css');
        echo $this->Html->css('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        echo $this->Html->css('https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css');
        echo $this->Html->css('custom.css');

		echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');
        echo $this->Html->script('https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js');   
        echo $this->Html->script('https://code.jquery.com/ui/1.12.1/jquery-ui.js');
        echo $this->Html->script('https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js');     
	?>
</head>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Traning</a>
            </div>
            <ul class="nav navbar-nav">
                <?php if ($this->Session->read('Auth.User')): ?>
                <li class="">
                    <?php 
                        echo $this->Html->link(
                            'Messages',
                            array('controller' => 'Messages', 'action' => 'messageList')
                        );
                    ?>
                </li>
                <li class="">
                    <?php 
                        echo $this->Html->link(
                            'Create Message',
                            array('controller' => 'Messages', 'action' => 'create')
                        );
                    ?>
                </li>                
                <?php endif; ?>
            </ul>
            <?php if ($this->Session->read('Auth.User')): ?>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo ucwords(AuthComponent::user('name')); ?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <?php 
                                echo $this->Html->link(
                                    'View Profile',
                                    array('controller' => 'users', 'action' => 'profile')
                                );
                            ?>
                        </li>  
                        <li>
                            <?php 
                                echo $this->Html->link(
                                    'Edit Profile',
                                    array('controller' => 'users', 'action' => 'edit')
                                );
                            ?>
                        </li>                      
                    </ul>
                </li>
                <li> 
                    <?php                        
                        echo $this->Html->link(      
                            "<span class='glyphicon glyphicon-log-i'></span>Logout",
                            array('controller' => 'users', 'action' => 'logout'),
                            array('escape' => false)                    
                        ); 
                    ?>
                </li>
            </ul>
            <?php else: ?>
                <ul class="nav navbar-nav navbar-right">
                    <li>    
                        <?php                        
                            echo $this->Html->link(      
                                "<span class='glyphicon glyphicon-user'></span>Sign up",
                                array('controller' => 'users', 'action' => 'add'),
                                array('escape' => false)                    
                            ); 
                        ?>
                    </li>
                    <li>
                        <?php 
                            echo $this->Html->link(      
                                "<span class='glyphicon glyphicon-user'></span>Login",
                                array('controller' => 'users', 'action' => 'login'),
                                array('escape' => false)                    
                            ); 
                        ?>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </nav>
	<div id="container">		
		<div id="content">
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
