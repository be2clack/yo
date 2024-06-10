<?php
class picture {
	
	private $image_file;
	
	public $image;
	public $image_type;
	public $image_width;
	public $image_height;
	
	
	public function __construct($image_file) {
		$this->image_file=$image_file;
		$image_info = getimagesize($this->image_file);
		$this->image_width = $image_info[0];
		$this->image_height = $image_info[1];
		switch($image_info[2]) {
			case 1: $this->image_type = 'gif'; break;
			case 2: $this->image_type = 'jpeg'; break;
			case 3: $this->image_type = 'png'; break;
			case 4: $this->image_type = 'swf'; break;
			case 5: $this->image_type = 'psd'; break;
			case 6: $this->image_type = 'bmp'; break;
			case 7: $this->image_type = 'tiffi'; break;
			case 8: $this->image_type = 'tiffm'; break;
			case 9: $this->image_type = 'jpc'; break;
			case 10: $this->image_type = 'jp2'; break;
			case 11: $this->image_type = 'jpx'; break;
			case 12: $this->image_type = 'jb2'; break;
			case 13: $this->image_type = 'swc'; break;
			case 14: $this->image_type = 'iff'; break;
			case 15: $this->image_type = 'wbmp'; break;
			case 16: $this->image_type = 'xbm'; break;
			case 17: $this->image_type = 'ico'; break;
			case 18: $this->image_type = 'webp'; break;
			default: $this->image_type = ''; break;
		}
		$this->fotoimage();
	}
	
	private function fotoimage() {
		switch($this->image_type) {
			case 'gif': $this->image = imagecreatefromgif($this->image_file); break;
			case 'jpeg': $this->image = imagecreatefromjpeg($this->image_file); break;
			case 'png': $this->image = imagecreatefrompng($this->image_file); break;
			case 'webp': $this->image = imagecreatefromwebp($this->image_file); break;
		}
	}
	
	public function autoimageresize($new_w=0, $new_h=0) {
		$difference_w = 0;
		$difference_h = 0;
		if($this->image_width < $new_w && $this->image_height < $new_h) {
			$this->imageresize($this->image_width, $this->image_height);
		}
		else {
			if($this->image_width > $new_w) {
				$difference_w = $this->image_width - $new_w;
			}
			if($this->image_height > $new_h) {
				$difference_h = $this->image_height - $new_h;
			}
				if($difference_w > $difference_h) {
					$this->imageresizewidth($new_w);
				}
				elseif($difference_w < $difference_h) {
					$this->imageresizeheight($new_h);
				}
				else {
					$this->imageresize($new_w, $new_h);
				}
		}
	}
	
	public function percentimagereduce($percent) {
		$new_w = $this->image_width * $percent / 100;
		$new_h = $this->image_height * $percent / 100;
		$this->imageresize($new_w, $new_h);
	}
	
	public function imageresizewidth($new_w=0) {
		$new_h = $this->image_height * ($new_w / $this->image_width);
		$this->imageresize($new_w, $new_h);
	}
	
	public function imageresizeheight($new_h=0) {
		$new_w = $this->image_width * ($new_h / $this->image_height);
		$this->imageresize($new_w, $new_h);
	}
	
	public function imageresize($new_w=0, $new_h=0) {
		$new_image = imagecreatetruecolor($new_w, $new_h);
        imageAlphaBlending($new_image, false);
        imageSaveAlpha($new_image, true);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $new_w, $new_h, $this->image_width, $this->image_height);
		$this->image_width = $new_w;
		$this->image_height = $new_h;
		$this->image = $new_image;
	}
	
	public function imagesave($image_type='jpeg', $image_file=NULL, $image_compress=100, $type = '') {
		
		if( !$type ){

			if($image_file==NULL) {
				switch($this->image_type) {
					case 'gif': header("Content-type: image/gif"); break;
					case 'jpeg': header("Content-type: image/jpeg"); break;
					case 'png': header("Content-type: image/png"); break;
					case 'webp': header("Content-type: image/webp"); break;
				}
			}

			switch($this->image_type) {
				case 'gif': imagegif($this->image, $image_file); break;
				case 'jpeg': imagejpeg($this->image, $image_file, $image_compress); break;
				case 'png': imagepng($this->image, $image_file); break;
				case 'webp': imagewebp($this->image, $image_file, $image_compress); break;
			}

	    }else{
            
            $pathinfo = pathinfo($image_file);

            if( $type == "webp" ){
                imagewebp($this->image, $pathinfo["dirname"] . "/" . $pathinfo["filename"] . ".webp", $image_compress);
            }else{
            	imagejpeg($this->image, $pathinfo["dirname"] . "/" . $pathinfo["filename"] . ".jpg", $image_compress);
            }           

	    }

	}
	
	public function imageout() {
		imagedestroy($this->image);
	}
	
	public function __destruct() {
		
	}
	
}
?>
