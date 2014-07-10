<?php

	require("../includes/config.php");
	
	// is there a valid code in the url?
	if ( !isset($_GET[ "code" ]) )
		redirect("/");

?>