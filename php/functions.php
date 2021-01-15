<?php
	function verifyLogin() {
		if($_SESSION['user'] == null) {
			header('Location: index.php');
		}
	}