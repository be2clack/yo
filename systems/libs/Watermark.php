<?php

class Watermark{

    function create( $from = "", $to = "" ) {

        global $settings;

        if( !$settings["watermark_status"] ) return false;

        if( $settings["watermark_type"] == "img" ){
           $this->img($from, $to);
        }else{
           $this->caption($from, $to);
        }

    }

    function caption($from = "", $to = ""){
       
       global $settings, $config;

       $r = 128;
       $g = 128;
       $b = 128;

       $alpha = $settings["watermark_caption_opacity"];

       $text = $settings["watermark_caption"];

       $font = $config["basePath"] . "/" . $config["folder_admin"] . "/files/fonts/watermark/" . $settings["watermark_caption_font"];

       if(!file_exists($font)) return false;
       if(!$text) return false;

       $source_info = getimagesize($from);

       if( $source_info["mime"] == "image/jpeg" ) {
           $img = imagecreatefromjpeg($from);
       } elseif( $source_info["mime"] == "image/gif" ) {
           $img = imagecreatefromgif($from);
       } elseif( $source_info["mime"] == "image/png" ) {
           $img = imagecreatefrompng($from);
       } elseif( $source_info["mime"] == "image/webp" ) {
           $img = imagecreatefromwebp($from);
       }

       $width = imagesx($img);
       $height = imagesy($img);

       $angle =  -rad2deg(atan2((-$height),($width))); 
       
       if( $settings["watermark_caption_size"] == "big" ){
          $text = " ".$text." ";
       }elseif( $settings["watermark_caption_size"] == "medium" ){
          $text = "     ".$text."     ";
       }elseif( $settings["watermark_caption_size"] == "small" ){
          $text = "           ".$text."           ";
       }
     
       $c = imagecolorallocatealpha($img, $r, $g, $b, $alpha);

       $size = (($width+$height)/2)*2/strlen($text);
       $box  = imagettfbbox ( $size, $angle, $font, $text );
       $x = $width/2 - abs($box[4] - $box[0])/2;
       $y = $height/2 + abs($box[5] - $box[1])/2;

     
       imagettftext($img,$size,$angle, $x, $y, $c, $font, $text);

       if($to) @imagejpeg($img, $to, 100); else return @imagejpeg($img);

     }

     function img( $from = "", $to = "" ) {
        global $settings, $config;

        $watermark_img = $config["basePath"] . "/" . $config["media"]["other"] . "/" . $settings["watermark_img"];

        if( !file_exists($watermark_img) ) return false;

        $watermark_info = getimagesize($watermark_img);
        $source_info = getimagesize($from);

        if( intval($watermark_info[0]) >= intval($source_info[0]) ){

            $imagecreate = imagecreatefrompng($watermark_img);

            $new_h = $watermark_info[1] * (($source_info[0] - 150) / $watermark_info[0]);
            $new_w = $watermark_info[0] * ($new_h / $watermark_info[1]);

            $new_image = imagecreatetruecolor($new_w, $new_h);
            imageAlphaBlending($new_image, false);
            imageSaveAlpha($new_image, true);
            imagecopyresampled($new_image, $imagecreate, 0, 0, 0, 0, $new_w, $new_h, $watermark_info[0], $watermark_info[1]);

            $stamp = $new_image;

         }else{

            $stamp = imagecreatefrompng($watermark_img);

         }


        if( $source_info["mime"] == "image/jpeg" ) {
           $img = imagecreatefromjpeg($from);
        } elseif( $source_info["mime"] == "image/gif" ) {
           $img = imagecreatefromgif($from);
        } elseif( $source_info["mime"] == "image/png" ) {
           $img = imagecreatefrompng($from);
        } elseif( $source_info["mime"] == "image/webp" ) {
           $img = imagecreatefromwebp($from);
        }    
        
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);    
        
        if($settings["watermark_pos"] == 1){
          $marge_left = 10;
          $marge_top = 10;        
        }elseif($settings["watermark_pos"] == 2){
          $marge_left = imagesx($img)-$sx-10;
          $marge_top = 10;         
        }elseif($settings["watermark_pos"] == 3){
          $marge_left = 10;
          $marge_top = imagesy($img)-$sy-10;         
        }elseif($settings["watermark_pos"] == 4){
          $marge_left = imagesx($img)-$sx-10;
          $marge_top = imagesy($img)-$sy-10;         
        }elseif($settings["watermark_pos"] == 5){
          $marge_left = imagesx($img)/2-$sx/2;
          $marge_top = imagesy($img)/2-$sy/2;         
        }else{
          $marge_left = imagesx($img)-$sx-10;
          $marge_top = imagesy($img)-$sy-10;         
        }

        imageSaveAlpha($img, true);
        imagecopy($img, $stamp, $marge_left, $marge_top, 0, 0, imagesx($stamp), 
        imagesy($stamp));
        
        if($to) @imagejpeg($img, $to, 100); else return @imagejpeg($img);

     }


}



?>