<?php

$signatures_grep = "grep -r -E '^-( |)\(.*\)?.*$' * --include='*.j'";
$output_path = "./Tools/Editors/BBEdit/Objective-J.j/";
@mkdir($output_path);

// First see if we have any templates we can move over and do so.

$template_path = "./Tools/Editors/BBEdit/Clippings/";
$dir = opendir($template_path);
while($node = readdir($dir)){
  if( strlen($node) > 2 ){
    $item_path = $template_path.$node;
    copy($item_path,$output_path.$node);
  }
}

$signatures_array = array();

$result = exec($signatures_grep,&$signatures_array);

print sizeOf($signatures_array)."\n";

$signatures_array = array_unique($signatures_array);

print sizeOf($signatures_array)."\n";

foreach($signatures_array as $signature){
  list($file,$signature) = explode(".j:",$signature);
  $signature = trim($signature);
  $file = basename($file);
  print "\nFILE:$file Signature:$signature\n";
  
  $pattern = "/^-[\s|]+?\(.*?\)/";
  $matches = array();
  preg_match($pattern,$signature,$matches);
  
  if( stristr($signature,"-(void)") > -1 ){
    $msignature       = trim(str_replace("-(void)","",$signature));
    $return_value = "void";
  }elseif( stristr($signature,"-(BOOL)") > -1 ){
    $msignature       = trim(str_replace("-(BOOL)","",$signature));
    $return_value = "BOOL";    
  }elseif( stristr($signature,"-(id)") > -1 ){
    $msignature       = trim(str_replace("-(id)","",$signature));
    $return_value = "id";    
  }elseif( stristr($signature,"-(float)") > -1 ){
    $msignature       = trim(str_replace("-(float)","",$signature));
    $return_value = "float";    
  }elseif( stristr($signature,"-(int)") > -1 ){
    $msignature       = trim(str_replace("-(int)","",$signature));
    $return_value = "int";    
  }elseif( stristr($signature,"-(CPString)") > -1 ){
    $msignature       = trim(str_replace("-(CPString)","",$signature));
    $return_value = "CPString";    
  }else{
    $return_value     = $matches[0];
    $msignature       = trim(str_replace($return_value,"",$signature));
    $return_value     = str_replace("-","",$return_value);
    $return_value     = str_replace(" ","",$return_value);
    $return_value     = str_replace("(","",$return_value);
    $return_value     = str_replace(")","",$return_value);
  }
  
  if( trim($return_value) == "" ){
    $return_value = "~";
  }
  
  if( substr ( $msignature, 0, 1) == "_" ){
    // private function
    continue;
  }

  
  $args             = explode(" ",$msignature);
  
  $argKeyArray      = array();
  $argsString       = "";
    
  foreach($args as $arg){
    list($k,$v) = explode(":",$arg);
    $argKeyArray[$k] = $v;
  }
  
  
  $output_signature = "";
  $ps = "#PLACEHOLDERSTART#";
  $pe = "#PLACEHOLDEREND# ";
  
  $filename = implode(" ",array_keys($argKeyArray)).(($return_value=="~")?"":" From ($file) Return ($return_value)");

  foreach( $argKeyArray as $key=>$value ){
    $output_signature .= $key.":".$ps.$value.$pe;
  }
  print "\t\t".$output_signature."\n";
  
  $handle = fopen($output_path.$filename,'w');
  fwrite($handle, $output_signature);
  fclose($handle);
  
  $argKeyArray = null;
  $argString = null;
  $pattern = null;
  $matches = null;
  $return_value = null;
  $method_signature = null;
  $method_proto = null;
  $args = null;
  
}

?>