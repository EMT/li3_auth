<?php 
   /**
    * To customise markup, override this template by creating:
    * app/views/sessions/add.html.php
    */
    $this->title('Logged Out'); 
?>
	
	<h2>Logged Out</h2>
	
	<p>You’re all logged out. See you soon!</p>
		
	<p><?= $this->html->link('Log back in again', ['Sessions::add']); ?></p>

	