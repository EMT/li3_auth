
    <?php $this->title('Reset Your Password'); ?>
    
    <div class="padding container-narrow">
    
        <h2>Reset Your Password</h2>

        <?= $this->form->create($user); ?>
            <?= $this->security->requestToken(); ?>
            
            <?= $this->form->hidden('code', ['value' => $user->code]); ?>
            
            <?= $this->form->field('password', array(
                'type' => 'password',
                'label' => 'New Password'
            )); ?>
            
            <?= $this->form->field('password_confirm', array(
                'type' => 'password',
                'label' => 'Confirm New Password'
            )); ?>
            
            
            <?= $this->form->submit('Reset', ['class' => 'btn']); ?>
        
        <?= $this->form->end(); ?>

        
    </div>