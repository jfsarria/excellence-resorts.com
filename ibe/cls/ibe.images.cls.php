<?php
/*
 * Revised: Apr 25, 2011
 */

/*
 * File: SimpleImage.php
 * Author: Simon Jarvis
 * Copyright: 2006 Simon Jarvis
 * Date: 08/11/06
 * Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
 * 
 * This program is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 2 
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details: 
 * http://www.gnu.org/licenses/gpl.html
 *
*/
 
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
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
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
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
   }
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;   
   }      
}

class images extends SimpleImage {

    function upload($FIELDNAME, $LOCATION, $ID, $TOWIDTH=0) {
        global $clsUploads;
        global $db;
        $arg = array();

        if ($_FILES[$FIELDNAME]['name'] != "") {
            $FILENAME = str_replace(".","_{$ID}.",$_FILES[$FIELDNAME]['name']);
            $THUMBNAIL = str_replace(".","_{$ID}.","T_".$_FILES[$FIELDNAME]['name']);
            $PATHINFO = $_SERVER["DOCUMENT_ROOT"].$LOCATION.$FILENAME;
            move_uploaded_file($_FILES[$FIELDNAME]['tmp_name'], $PATHINFO);
            /*
            $OLDFILE = isset($_POST['OLD_'.$FIELDNAME]) ? $_POST['OLD_'.$FIELDNAME] : "";
            if ($OLDFILE != "" && $OLDFILE != $FILENAME && file_exists($_SERVER["DOCUMENT_ROOT"].$OLDFILE)) unlink($_SERVER["DOCUMENT_ROOT"].$OLDFILE);
            */
            $this->load($_SERVER["DOCUMENT_ROOT"].$LOCATION.$FILENAME);
            $this->resizeToWidth(250);
            $this->save($_SERVER["DOCUMENT_ROOT"].$LOCATION.$THUMBNAIL);

            $PATH_PARTS = pathinfo($PATHINFO);
            $arg = array(
                'ID' => dbNextId($db),
                'PARENT_ID' => $ID,
                'NAME' => $FILENAME,
                'TYPE' => "image" //$_FILES[$FIELDNAME]['type']
            );

            $clsUploads->save($db, $arg);
        }

        return $arg;
    }

}

global $clsImage;
$clsImage = new images();

/*
The first example below will load a file named picture.jpg resize it to 250 pixels wide and 400 pixels high and resave it as picture2.jpg

   include('SimpleImage.php');
   $clsImage = new SimpleImage();
   $clsImage->load('picture.jpg');
   $clsImage->resize(250,400);
   $clsImage->save('picture2.jpg');


If you want to resize to a specifed width but keep the dimensions ratio the same then the script can work out the required height for you, just use the resizeToWidth function.

   include('SimpleImage.php');
   $clsImage = new SimpleImage();
   $clsImage->load('picture.jpg');
   $clsImage->resizeToWidth(250);
   $clsImage->save('picture2.jpg');


You may wish to scale an image to a specified percentage like the following which will resize the image to 50% of its original width and height

   include('SimpleImage.php');
   $clsImage = new SimpleImage();
   $clsImage->load('picture.jpg');
   $clsImage->scale(50);
   $clsImage->save('picture2.jpg');


You can of course do more than one thing at once. The following example will create two new images with heights of 200 pixels and 500 pixels

   include('SimpleImage.php');
   $clsImage = new SimpleImage();
   $clsImage->load('picture.jpg');
   $clsImage->resizeToHeight(500);
   $clsImage->save('picture2.jpg');
   $clsImage->resizeToHeight(200);
   $clsImage->save('picture3.jpg');


The output function lets you output the image straight to the browser without having to save the file. Its useful for on the fly thumbnail generation

   header('Content-Type: image/jpeg');
   include('SimpleImage.php');
   $clsImage = new SimpleImage();
   $clsImage->load('picture.jpg');
   $clsImage->resizeToWidth(150);
   $clsImage->output();


The following example will resize and save an image which has been uploaded via a form

   if( isset($_POST['submit']) ) {

      include('SimpleImage.php');
      $clsImage = new SimpleImage();
      $clsImage->load($_FILES['uploaded_image']['tmp_name']);
      $clsImage->resizeToWidth(150);
      $clsImage->output();

   } else {

       <form action="upload.php" method="post" enctype="multipart/form-data">
          <input type="file" name="uploaded_image" />
          <input type="submit" name="submit" value="Upload" />
       </form>
   }
*/
?>