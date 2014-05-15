
	<?php $this->title('Log In'); ?>
	
	<h2>Log In</h2>
	
	<?= $this->form->create($user); ?>
		<?= $this->security->requestToken(); ?>
		
		<?= $this->form->hidden('to', ['value' => $user->to]); ?>
		
		<?= $this->form->field('email', [
			'type' => 'email',
			'label' => 'Email Address'
		]); ?>
		
		<?= $this->form->field('password', [
			'type' => 'password',
			'label' => 'Password'
		]); ?>
		
		<?= $this->form->submit('Log In', ['class' => 'btn']); ?>
		
	<?= $this->form->end(); ?>
	
	
	<p>Forgotten your password? No problem, <?= $this->html->link('reset it here', ['Users::password']); ?>.</p>

	