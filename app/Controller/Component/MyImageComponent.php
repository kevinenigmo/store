<?php

App::uses('Component', 'Controller');

class MyImageComponent extends Component {
public function imageresize($imagePath, $thumb_path, $destinationWidth, $destinationHeight) {
		// The file has to exist to be resized
		if (file_exists($imagePath)) {
			// Gather some info about the image
			$imageInfo = getimagesize($imagePath);
	
			// Find the intial size of the image
			$sourceWidth = $imageInfo[0];
			$sourceHeight = $imageInfo[1];
	
			if ($sourceWidth > $sourceHeight) {
				$temp = $destinationWidth;
				$destinationWidth = $destinationHeight;
				$destinationHeight = $temp;
			}
	
			// Find the mime type of the image
			$mimeType = $imageInfo['mime'];
	
			// Create the destination for the new image
			$destination = imagecreatetruecolor($destinationWidth, $destinationHeight);
	
			// Now determine what kind of image it is and resize it appropriately
			if ($mimeType == 'image/jpeg' || $mimeType == 'image/jpg' || $mimeType == 'image/pjpeg') {
				$source = imagecreatefromjpeg($imagePath);
				imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);
				imagejpeg($destination, $thumb_path);
			} else if ($mimeType == 'image/gif') {
				$source = imagecreatefromgif($imagePath);
				imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);
				imagegif($destination, $thumb_path);
			} else if ($mimeType == 'image/png' || $mimeType == 'image/x-png') {
				$source = imagecreatefrompng($imagePath);
				imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);
				imagepng($destination, $thumb_path);
			} else {
				return 0;
			}
	
			// Free up memory
			imagedestroy($source);
			imagedestroy($destination);
			
			return 1;
		}
	}
}