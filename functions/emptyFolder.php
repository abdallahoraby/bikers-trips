<?php


	function empty_folder($folder_path){
		//function to delete all files inside a folder after check

		//The name of the folder.
		$folder = $folder_path;

		//Get a list of all of the file names in the folder.
		$files = glob($folder . '/*');

		//Loop through the file list.
		foreach($files as $file){
			//Make sure that this is a file and not a directory.
			if(is_file($file)){
				//Use the unlink function to delete the file.
				unlink($file);
			}
		}
	}