<?php
require_once 'parseDocx.php';
require_once 'parseDoc.php';
require_once 'ToDocx.php';
require_once 'Resume.php';
require_once 'include/database.php';
global $uid;
$uid =1;


if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
 
  $file_name_explode_array = explode(".",$_FILES["file"]["name"]);
  $file_extention = $file_name_explode_array[1];  
  
  
  echo $file_extention;
   if($file_extention === 'docx')
      {
          echo "docx";
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "files/1.zip");
      
      
      $zip = new ZipArchive;
     $res = $zip->open('files/1.zip');
     if ($res === TRUE) {
         $zip->extractTo('files/unzipped/');
         $zip->close();
         echo 'ok';
         $parser = new parseDocx('files/unzipped/word/document.xml');

persist_resume($parser);
        



     } else {
         echo 'failed';
     }
      }
     
     if($file_extention === 'rtf')
     {
         echo "extention is rtf";
         move_uploaded_file($_FILES["file"]["tmp_name"],"files/1.rtf");
         convertToDocx("files/1.rtf",".rtf");
         $zip = new ZipArchive;
     $res = $zip->open('files/resume.zip');
     if ($res === TRUE) {
         $zip->extractTo('files/unzipped/');
         $zip->close();
         echo 'ok';
         $parser = new parseDoc('files/unzipped/word/document.xml');
     } else {
         echo 'failed';
     }
     }
     
     if($file_extention === 'doc')
     {
         echo "extention is doc";
         move_uploaded_file($_FILES["file"]["tmp_name"],"files/1.doc");
         convertToDocx("files/1.doc",".doc");
         $zip = new ZipArchive;
     $res = $zip->open('files/resume.zip');
     if ($res === TRUE) {
         $zip->extractTo('files/unzipped/');
         $zip->close();
         echo 'ok';
         $parser = new parseDoc('files/unzipped/word/document.xml');
     } else {
         echo 'failed';
      }
         
     
     
     
      }
      
     function persist_resume($parser)
     {
         $uid = 1;
          $res = new Resume($uid);
$res->firstname = $parser->firstname;
$res->lastname =  $parser->lastname;
$res->address = $parser->address;
$res->city = $parser->city;
$res->state = $parser->state;
$res->zip = $parser->zip;
$res->phone = $parser->phone;
$res->email = $parser->email;
$res->company= $parser->company;
$res->school=$parser->school;
$res->awards = $parser->awards;
$res->skills = $parser->skills;

if(mysql_query("insert into resume (uid) values ($res->uid)"))
{
$rid = mysql_insert_id();
var_dump($rid);
$school = $res->school;


 $college_dates= $res->school['dates'];
 $degree = $res->school['degree'];
 $school = $res->school['school'];

var_dump(mysql_query("update resume set first_name = '$res->firstname',last_name = '$res->lastname',address = '$res->address'
        ,city = '$res->city',state = '$res->state', zip = '$res->zip', phone = '$res->phone', email = '$res->email', degree = '$degree',
        college = '$school', college_dates = '$college_dates'
        where rid = '$rid'"));


     }
     }
    
  
?> 