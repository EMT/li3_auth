
	<?php $this->title('Log In'); ?>
	
	<h2>Logged Out</h2>
	
	<p>You’re all logged out. See you soon!</p>
		
	<p><?= $this->html->link('Log back in again', ['Sessions::add']); ?></p>

	