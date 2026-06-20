<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
	function adminer_object()
	{
		$aLoad = [];

		require "plugins/CSqlite.php";
		# overwrite defaults
		$aOpt = [
			 #root path to search for files
			 #'vPath' => dirname(__DIR__).'/database'
			 #we search for *.sqlite / *.db files
			 #'vSearch' => "#(.+\.sqlite|.+\.db)$#",
			 #write access!
			 #'vPwdFile' => __DIR__ . "/plugins/CSqlite.pwd",
		];
		$aLoad[] = new CSqlite($aOpt);

		class AdminerCustomization extends Adminer\Plugins
		{
		}
		return new AdminerCustomization($aLoad);
	}
	include "./adminer.php";