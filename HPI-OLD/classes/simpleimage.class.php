<?php
 

 
class SimpleImage {
 
	   var $image;
	   var $image_type;
	 
	   function load($filename) {
	 
		  $image_info = getimagesize($filename);
		  $this->image_type = $image_info[2];
		  if( $this->image_type == IMAGETYPE_JPEG ) {
	 
			 $this->image = imagecreatefromjpeg($filename);
		  } elseif( $this->image_type == IMAGETYPE_GIF ) {
	 
			 $this->image = imagecreatefromgif($filename);
		  } elseif( $this->image_type == IMAGETYPE_PNG ) {
	 
			 $this->image = imagecreatefrompng($filename);
		  }
	   }
	   
	   
	   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=100, $permissions=null) {
	 
		  if( $image_type == IMAGETYPE_JPEG ) {
			 imagejpeg($this->image,$filename,$compression);
		  } elseif( $image_type == IMAGETYPE_GIF ) {
	 
			 imagegif($this->image,$filename);
		  } elseif( $image_type == IMAGETYPE_PNG ) {
	 
			 imagepng($this->image,$filename);
		  }
		  if( $permissions != null) {
	 
			 chmod($filename,$permissions);
		  }
	   }
	   
	   
	   function output($image_type=IMAGETYPE_JPEG) {
	 
		  if( $image_type == IMAGETYPE_JPEG ) {
			 imagejpeg($this->image);
		  } elseif( $image_type == IMAGETYPE_GIF ) {
	 
			 imagegif($this->image);
		  } elseif( $image_type == IMAGETYPE_PNG ) {
	 
			 imagepng($this->image);
		  }
	   }
	   
	   
	   function getWidth() {
	 
		  return imagesx($this->image);
	   }
	   
	   
	   function getHeight() {
	 
		  return imagesy($this->image);
	   }
	   
	   
	   function resizeToHeight($height, $imageStyle='', $isStamp='') {
	 
		  $ratio = $height / $this->getHeight();
		  $width = ceil( $this->getWidth() * $ratio );
		  $this->resize($width,$height, $imageStyle, $isStamp);
	   }
	 
	   
	   function resizeToWidth($width, $imageStyle='', $isStamp='') {
		  $ratio = $width / $this->getWidth();
		  $height = ceil( $this->getheight() * $ratio );
		  $this->resize($width,$height, $imageStyle, $isStamp);
	   }
	 
	   
	   function scale($scale, $imageStyle='', $isStamp='') {
		  $width = ceil( $this->getWidth() * $scale/100 );
		  $height = ceil( $this->getheight() * $scale/100 );
		  $this->resize($width,$height, $imageStyle, $isStamp);
	   }
	 
	   
	   function resize($width,$height, $imageStyle='', $isStamp='') {
	   	  
		  //$new_image = imagecreatetruecolor($width, $height);
		  
		  /* ------- Added by Sagar Kshatriya Date : 20 October 2012 ------- */
		  
		  $x =0; $y=0;
		  $bgwidth = $width; $bgheight = $height;
		  
		  if (isset($imageStyle) && $imageStyle == 'pt0') { 
		  		  
				  $bgwidth = 220; $bgheight = 134;
				  
				  if ($width > 220 && $height > 134) {
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($width < 220 && $height < 134) {
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($height == 134 && ( $width < 220 or $width > 220 ) ) { 
					$x = ($bgwidth - $width) /2;
				  }
				  else if ($width == 220 && ($height < 134 or $height > 134 ) ) {
					$y = ($bgheight - $height) /2;
				  }
				  else {
				  	//echo $x . " ". $y; exit();
				  }
				  
		  } // end if 
		  if (isset($imageStyle) && $imageStyle == 'pt6') { 
		  		  
				  $bgwidth = 660; $bgheight = 400;
				  
				  if ($width > 660 && $height > 400) {
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($width < 660 && $height < 400) {
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($height == 400 && ( $width < 660 or $width > 660 ) ) { 
					$x = ($bgwidth - $width) /2;
				  }
				  else if ($width == 660 && ($height < 400 or $height > 400 ) ) {
					$y = ($bgheight - $height) /2;
				  }
				  else {
				  	//echo $x . " ". $y; exit();
				  }
				  
		  } // end if 
		  if (isset($imageStyle) && $imageStyle == 'pt5') { 
		  		  
				  $bgwidth = 756; $bgheight = 501;
				  
				  if ($width > 756 && $height > 501) {
				//  	$bgwidth = 756; 	$bgheight = 501;
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($width < 756 && $height < 501) {
				  //	$bgwidth = 756; 	$bgheight = 501;
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($height == 501 && ( $width < 756 or $width > 756 ) ) { 
				//	$bgwidth = 756;
					$x = ($bgwidth - $width) /2;
				  }
				  else if ($width == 756 && ($height < 501 or $height > 501 ) ) {
				//	$bgheight = 501;
					$y = ($bgheight - $height) /2;
				  }
				  else {
				  	//echo $x . " ". $y; exit();
				  }
				  
				//echo $width . " ". $height . " = ". $bgwidth . " ". $bgheight . " = ". $x . " ". $y; exit();
				  
			} // end if 
			if (isset($imageStyle) && $imageStyle == 'pt4') { 
		  		  
				  $bgwidth = 450; $bgheight = 379;
				  
				  if ($width > 450 && $height > 379) {
				//  	$bgwidth = 450; 	$bgheight = 379;
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($width < 450 && $height < 379) {
				  //	$bgwidth = 450; 	$bgheight = 379;
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($height == 379 && ( $width < 450 or $width > 450 ) ) { 
				//	$bgwidth = 756;
					$x = ($bgwidth - $width) /2;
				  }
				  else if ($width == 450 && ($height < 379 or $height > 379 ) ) {
				//	$bgheight = 501;
					$y = ($bgheight - $height) /2;
				  }
				  else {
				  	//echo $x . " ". $y; exit();
				  }
				  
				//echo $width . " ". $height . " = ". $bgwidth . " ". $bgheight . " = ". $x . " ". $y; exit();
				  
			} // end if 
			else if (isset($imageStyle) && $imageStyle == 'pt3') { 
		  	  	
				  $bgwidth = 320; $bgheight = 271;
		  		  if ($width > 320 && $height > 271) {
				//  	$bgwidth = 320;  $bgheight = 271;
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($width < 320 && $height < 271) {
				 // 	$bgwidth = 320; $bgheight = 271;
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($height == 271 && ( $width < 320 or $width > 320 ) ) { 
				//	$bgwidth = 320;
					$x = ($bgwidth - $width) /2;
				  }
				  else if ($width == 320 && ($height < 271 or $height > 271 ) ) {
				//	$bgheight = 271;
					$y = ($bgheight - $height) /2;
				  }
				  
				//   echo $width . " ". $height . " = ". $bgwidth . " ". $bgheight; exit();
		  
		  } // end else
		  else if (isset($imageStyle) && $imageStyle == 'pt1') { 
		  	  	
				  $bgwidth = 158; $bgheight = 210;
		  		  if ($width > 158 && $height > 210) {
				//  	$bgwidth = 158;  $bgheight = 210;
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($width < 158 && $height < 210) {
				 // 	$bgwidth = 158; $bgheight = 210;
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($height == 210 && ( $width < 158 or $width > 158 ) ) { 
				//	$bgwidth = 320;
					$x = ($bgwidth - $width) /2;
				  }
				  else if ($width == 158 && ($height < 210 or $height > 210 ) ) {
				//	$bgheight = 210;
					$y = ($bgheight - $height) /2;
				  }
				  
				//   echo $width . " ". $height . " = ". $bgwidth . " ". $bgheight; exit();
		  
		  } // end else
		  else if (isset($imageStyle) && $imageStyle == 'pt2') { 
		  	  	
				  $bgwidth = 391; $bgheight = 528;
		  		  if ($width > 391 && $height > 528) {
				//  	$bgwidth = 391;  $bgheight = 528;
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($width < 391 && $height < 528) {
				 // 	$bgwidth = 158; $bgheight = 528;
					$x = ($bgwidth - $width) /2;
					$y = ($bgheight - $height) /2;
				  }
				  else if ($height == 528 && ( $width < 391 or $width > 391 ) ) { 
				//	$bgwidth = 391;
					$x = ($bgwidth - $width) /2;
				  }
				  else if ($width == 391 && ($height < 528 or $height > 528 ) ) {
				//	$bgheight = 528;
					$y = ($bgheight - $height) /2;
				  }
				  
				//   echo $width . " ". $height . " = ". $bgwidth . " ". $bgheight; exit();
		  
		  } // end else
		  
		  
		
//		  echo $width . " ". $height . " = ". $bgwidth . " ". $bgheight; exit();
		 
		  $new_image = imagecreatetruecolor($bgwidth, $bgheight);
		  $bgcolor = imagecolorallocate($new_image, 255, 255, 255);
  		  
		  imagefilledrectangle($new_image,0,0,$bgwidth,$bgheight,$bgcolor);
		  
		  /* ------------------------------------------------------------ */
			
		  imagecopyresampled($new_image, $this->image, $x, $y, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		  
		  // ------ place watermark on image ---------
		  	
			if (isset($imageStyle) && $imageStyle == 'pt5') { 
			
				if (isset($isStamp) && $isStamp == 1) { 
				
					$watermark = imagecreatefromgif('../images/watermark.gif');
					$marge_left = 10;
					$marge_right = 10;
					$marge_bottom = 10;
					$sx = imagesx($watermark);
					$sy = imagesy($watermark);
					imagecopymerge($new_image, $watermark, -20, 170, 0, 0, imagesx($watermark), imagesy($watermark), 40);
				}
			}
		  // -------------------------------------------
		  
		  $this->image = $new_image;
	   }    
	   
	   /*function resize($width,$height) {
		  $new_image = imagecreatetruecolor($width, $height);
		  imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		  $this->image = $new_image;
	   }   */   
 
}
?>