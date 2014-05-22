
    <?php $this->title('Change Your Password'); ?>
    
    <div class="padding container-narrow">
    
        <h2>Change Your Password</h2>

        <?= $this->form->create($user); ?>
            <?= $this->security->requestToken(); ?>
            
            <?= $this->form->field('current_password', array(
                'type' => 'password',
                'label' => 'Old Password'
            )); ?>
            
            <?= $this->form->field('password', array(
                'type' => 'password',
                'label' => 'New Password'
            )); ?>
            
            
            <?= $this->form->submit('Update', ['class' => 'btn']); ?>
        
        <?= $this->form->end(); ?>

        
    </div>