<?php
	session_start();
	// разрегистрировали переменные
	unset($_SESSION['idsess']); 
	unset($_SESSION['name']);
	unset($_SESSION['secname']);
	unset($_SESSION['hashpasswd']);
	header('Location: ./'); // Пересылка на главную страницу
?>