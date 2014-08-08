<?php 
   /**
    * Copy this template to:
    * app/views/users/add.html.php
    */
    $this->title('Sign Up'); 
?>
    
    <h2>Sign Up</h2>
    
    <?= $this->form->create($user); ?>
        <?= $this->security->requestToken(); ?>
        
        <?= $this->form->field('fname', [
            'label' => 'First Name'
        ]); ?>

        <?= $this->form->field('lname', [
            'label' => 'Last Name'
        ]); ?>

        <?= $this->form->field('email', [
            'type' => 'email',
            'label' => 'Email Address'
        ]); ?>
        
        <?= $this->form->field('password', [
            'type' => 'password',
            'label' => 'Password'
        ]); ?>
        
        <?= $this->form->submit('Sign Up', ['class' => 'btn']); ?>
        
    <?= $this->form->end(); ?>
    
    
    <p>Already have an account? <?= $this->html->link('Log in here', ['Sessions::add']); ?>.</p>

    