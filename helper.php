<?php
	$hash = password_hash('admin', PASSWORD_DEFAULT);
	echo $hash;

	$verify = password_verify('apple', $hash);
	
?>