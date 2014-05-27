
    <?php $this->title('Forgotten Password'); ?>
    
    <div class="padded container-narrow">
    
        <h2>Forgotten Your Password?</h2>
        
        <p>Enter your email address below and we’ll send you instructions on how to reset it.</p>
    
        <?= $this->form->create(null); ?>
            <?= $this->security->requestToken(); ?>
            
            <?= $this->form->field('email', [
                'type' => 'email',
                'label' => 'Email Address'
            ]); ?>
            
            <?= $this->form->submit('Send', ['class' => 'btn']); ?>
            
        <?= $this->form->end(); ?>
        
        
        <p class="separated">Or, <?= $this->html->link('Go back to the log in page', ['Sessions::add']); ?>.</p>
        
    </div>
    