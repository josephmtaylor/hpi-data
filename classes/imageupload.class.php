<?
class UploadImageClass { 

		# IMAGE FUNCTIONS  //// You do not need to alter these functions
		
			
			function resizeImage($image,$width,$height,$scale) {
				list($imagewidth, $imageheight, $imageType) = getimagesize($image);
				$imageType = image_type_to_mime_type($imageType);
				$newImageWidth = ceil($width * $scale);
				$newImageHeight = ceil($height * $scale);
				$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
				
				imagealphablending($newImage, false);
				imagesavealpha($newImage,true);
				$transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
				imagefilledrectangle($newImage, 0, 0, $newImageWidth, $newImageHeight, $transparent);


//				$transparent = imagecolorallocatealpha($sprite, 255, 255, 255, 127);
//				imagefilledrectangle( $newImage, $width, $height, $transparent ); 
//				imagealphablending($newImage, false);
//				imagesavealpha($newImage,true);
				
				switch($imageType) {
					case "image/gif":
						$source=imagecreatefromgif($image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						$source=imagecreatefromjpeg($image); 
						break;
					case "image/png":
					case "image/x-png":
						$source=imagecreatefrompng($image); 
						break;
				}
				imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
				
				switch($imageType) {
					case "image/gif":
						imagegif($newImage,$image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						imagejpeg($newImage,$image,90); 
						break;
					case "image/png":
					case "image/x-png":
						imagepng($newImage,$image);  
						break;
				}
				
				chmod($image, 0755);
				return $image;
			}
			//You do not need to alter these functions
			function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
				list($imagewidth, $imageheight, $imageType) = getimagesize($image);
				$imageType = image_type_to_mime_type($imageType);
				
				$newImageWidth = ceil($width * $scale);
				$newImageHeight = ceil($height * $scale);
				$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
				switch($imageType) {
					case "image/gif":
						$source=imagecreatefromgif($image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						$source=imagecreatefromjpeg($image); 
						break;
					case "image/png":
					case "image/x-png":
						$source=imagecreatefrompng($image); 
						break;
				}
				imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
				switch($imageType) {
					case "image/gif":
						imagegif($newImage,$thumb_image_name); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						imagejpeg($newImage,$thumb_image_name,90); 
						break;
					case "image/png":
					case "image/x-png":
						imagepng($newImage,$thumb_image_name);  
						break;
				}
				chmod($thumb_image_name, 0777);
				return $thumb_image_name;
			}
			//You do not need to alter these functions
			function getHeight($image) {
				$size = getimagesize($image);
				$height = $size[1];
				return $height;
			}
			//You do not need to alter these functions
			function getWidth($image) {
				$size = getimagesize($image);
				$width = $size[0];
				return $width;
			}

}

class PhotoUpload {
	
	var $file_vars;
	var $base_dir;
	var $report;
	var $image_directory;
	
	// Instantiate
	function PhotoUpload($file_vars,$max_size='') {
		$this->file_vars = $file_vars;
		$this->max_size = $max_size;
	}
	
	// Check for errors
	function checkErrors() {
		if ($this->file_vars['name'] == '') {
			$this->errors = 'Please choose a file and try again';
			return '1';
		}
		if ($this->file_vars['size'] == 0) {
			$this->errors = 'The file you chose has no size. Are you sure you chose a real file?';
			return '1';
		}
		if ($this->max_size != '') {
			if ($this->file_vars['size'] > $this->max_size) {
				$this->errors = 'This file is too large. Please make the file smaller and try again.';
				return '1';
			}
		}
	}
	
	function isValidExtension() {
		if ($this->image_extension != 'jpg' && $this->image_extension != 'JPG' && $this->image_extension != 'jpeg' && $this->image_extension != 'JPEG' && $this->image_extension != 'gif' && $this->image_extension != 'GIF' && $this->image_extension != 'png' && $this->image_extension != 'PNG') {
			return false;
		} else {
			return true;
		}
	}
	
	// Upload photo
	function uploadImage($dir,$new_image_name='') {
		$this->image_directory = $dir;
		if ($new_image_name != '') {
			$this->file_vars['name'] = strtolower($new_image_name . '.' . $this->image_extension);
		}
		$this->original_image_location = $this->image_directory . $this->file_vars['name'];
		if ($this->errors == '') {
			if ( @!copy($this->file_vars['tmp_name'], $this->original_image_location)) {
				$this->errors = 'ERROR: Could not move file into directory "' . $dir . '", please make sure you have write permissions in the directory';
			} else {
				$this->report .= '<p>The file was uploaded successfully:<br /><img src="' . $this->original_image_location . '" alt="" /></p>';
			}
		}
	}
	
	// Get Image Variables (size and extension)
	function getImageVariables($temp_name='') {
		$this->temp_name = $temp_name;
		if ($this->temp_name == '') {
			$this->temp_name = $this->original_image_location;
		}
		$this->image_name_array = explode('.',$this->file_vars['name']);
		$this->image_name = $this->image_name_array[0];
		$this->image_extension = $this->image_name_array[1];
		$this->image_size_array = @GetImageSize($this->temp_name);
		$this->image_filesize = $this->file_vars['size'];
		$this->image_width = $this->image_size_array[0];
		$this->image_height = $this->image_size_array[1];
		if ($this->image_width > $this->image_height) {
			$this->longest_side = $this->image_width;
		} else {
			$this->longest_side = $this->image_height;
		}
	}
	
	// Create resized version (to replace with new version, just set the $value to '' and type_of_renaming to 1)
	
	// For $type_of_renaming, 1 = $value added to beginning of name, 2 = $value added to end of name, 3 = $value replaces name

	function resizeImage($max_dimention, $max_hight, $value, $type_of_renaming, $new_dir) {
		$this->getImageVariables();
		if ($new_dir == '') {
			$new_dir = $this->image_directory;
		}
		// 1 = Same name, basically resizing the original
		// 2 = The $value is added to the name and a new image is created in addition to the original
		// 3 = The $value is used as the new name
		if ($type_of_renaming == 1) {
			$this->new_image_name = $value . $this->image_name . '.' . $this->image_extension;
		} elseif ($type_of_renaming == 2) {
			$this->new_image_name = $this->image_name . $value . '.' . $this->image_extension;
		} elseif ($type_of_renaming == 3) {
			$this->new_image_name = $value . '.' . $this->image_extension;
		}
		if ($this->longest_side > $max_dimention) {
			//$new_percent = $max_dimention / $this->longest_side;
			$new_percent = $max_dimention / $this->image_width;
			$new_width = ceil($this->image_width*$new_percent);
			if (empty($max_hight))
				$new_height = ceil($this->image_height*$new_percent);
			else if (!empty($max_hight))
				$new_height = $max_hight;
		} else {
			$new_percent = 100;
			$new_width = $this->image_width;
			$new_height = $this->image_height;
		}
		
		
//		$src = ImageCreateFromJpeg($this->original_image_location);

		if ($this->image_extension == 'jpg' || $this->image_extension == 'JPG' || $this->image_extension == 'jpeg' || $this->image_extension == 'JPEG')
		{
			$src = imagecreatefromjpeg($this->original_image_location);
			$new_image = imagecreatetruecolor($new_width,$new_height);
		    imagecopyresampled($new_image,$src,0,0,0,0,$new_width,$new_height,$this->image_width,$this->image_height);	
		} 
		else if ($this->image_extension == 'gif' || $this->image_extension == 'GIF')
		{
		//	return 0;
		  
		  $new_image = imagecreatetruecolor($new_width,$new_height);
		  
			imagealphablending($new_image, false);
			$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
			imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
		   
		    $src = imagecreatefromgif($this->original_image_location);
		    imagecopyresized($new_image,$src,0,0,0,0,$new_width,$new_height,$this->image_width,$this->image_height);
		}
		else if ($this->image_extension == 'png' || $this->image_extension == 'PNG')
		{
		  	$src = imagecreatefrompng($this->original_image_location);
		    $new_image = imagecreatetruecolor($new_width,$new_height);
			imagealphablending($new_image, false);
			imagesavealpha($new_image,true);
			$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
		//	imagecolortransparent($new_image,$transparent);
			imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
		    
		    imagecopyresampled($new_image,$src,0,0,0,0,$new_width,$new_height,$this->image_width,$this->image_height);
		}

		if ($this->errors == '') 
		{	
			$this->removeOriginal();
			if (imagejpeg($new_image,$new_dir . $this->new_image_name)) 
			{
				return 1;
				//$this->report .= '<p>Successfully resized image:<br /><img src="' . $this->image_directory . $new_image_name . '" alt="Resized Image" /><br />' . $new_dir . $this->new_image_name . '</p>';
			}
			else if (imagegif($new_image,$new_dir . $this->new_image_name)) 
			{
				return 1;
				//$this->report .= '<p>Successfully resized image:<br /><img src="' . $this->image_directory . $new_image_name . '" alt="Resized Image" /><br />' . $new_dir . $this->new_image_name . '</p>';
			}
			else if (imagepng($new_image,$new_dir . $this->new_image_name)) 
			{
				return 1;
				//$this->report .= '<p>Successfully resized image:<br /><img src="' . $this->image_directory . $new_image_name . '" alt="Resized Image" /><br />' . $new_dir . $this->new_image_name . '</p>';
			}
		}
	}
	
	function removeOriginal() {
		unlink($this->original_image_location);
	}
}
?>