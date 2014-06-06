<?php 
    $this->title('Get Another Verification Email'); 
?>
    


    <h2><?= $this->title() ?></h2>
    
    
    <p>Send a new verification email to <?= $user->email; ?>?</p>
        
                    
    <?= $this->form->create($user, array('class' => 'std-form')); ?>
        <?= $this->security->requestToken(); ?>
        
        <?= $this->form->submit('Send it!', ['class' => 'btn']); ?>
        
        
    <?=$this->form->end(); ?>
        

                

    
    