<?
require_once "simplexml.class.php";

//if you want to use it as a function;

if(!function_exists("simplexml_load_file")){
 function simplexml_load_file($file){
  $sx = new simplexml;
  return $sx->xml_load_file($file);
 }
}

//or directly:

echo "<pre>";
$file = "http://musicbrainz.org/ws/1/track/?query=metallica&type=xml";
$sxml = new simplexml;
$data = $sxml->xml_load_file($file);
print_r($data);
?>