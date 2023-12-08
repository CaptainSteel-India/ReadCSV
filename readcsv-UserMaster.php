<?php
$recordcount = 0;
$insertcount = 0;
$innerloop = 0;
$mainloop = 0;
$datefile = date('Ymd');
$fname = "/home/ec2-user/csv/". $datefile.'-UserMaster.csv';
//count the number of lines in the file
$logfilename="/home/ec2-user/readcsv/".$datefile."usermasterlog.txt";
$logfile = fopen($logfilename, "w");
fwrite($logfile,"Process Started at :".date('Ymdhis'). PHP_EOL);

try
{  
  if ( !file_exists($fname) ) {
    fwrite($logfile, "CSV File Not found !");
        fwrite($logfile, PHP_EOL);
  
    throw new Exception('File not found.');
  }
    

$handleFile = fopen($fname, "r"); 
if ( !$handleFile ) {
  fwrite($logfile, "Could not open CSV File !");
        fwrite($logfile, PHP_EOL);
  
  throw new Exception('File open failed.');
}

while(!feof($handleFile)){ 
  // We are loading only 4096 bytes of data at a time. 
  $line = fgets($handleFile,1000); 
  $recordcount++;
} 

fclose($handleFile); 
//echo "Total number of lines is :".  $recordcount. PHP_EOL ;
fwrite($logfile, "Total number of lines is :".  ($recordcount-2). PHP_EOL);
        //fwrite($logfile, PHP_EOL);
  



$firstline=True;
$insertvalue = "";

if ($recordcount<100000)
  {
    //data to be inserted into the database
if (($open = fopen($fname, "r")) !== false) {
  while (($data = fgetcsv($open, 1000, ",")) !== false) {

      if($firstline){
        $data = fgetcsv($open, 1000, ",");
        $firstline = False;
      }

      $array[] = $data;
      
      
      }
    }
      fclose($open);
 
      require("dbconnect.php");
      $result = $con->query("TRUNCATE `bandhan`.`tbl_champion_master`");
      //data sanitisation
 //data insert
 $insert_sql = "INSERT INTO `tbl_champion_master`(`SrNo`, `UserID`, `Username`, `UserType`, `FirstName`, 
 `LastName`, `Mobile`, `Email`, `DOB`, `SecretCode`, `DealerCode`, `DealerName`, `SA`, `SP`, `SSE`,
  `AsstSM`, `ASM`, `SH`, `NH`, `Address1`, `Address2`, `Pincode`, `City`, `State`, `Status`,
   `InsertedDate`, `UpdatedDate`) VALUES ";
  foreach($array as $arrays){
    if ($arrays[8]!="")
      {
      $dobstring = "'". substr($arrays[8], 6, 4). "-". substr($arrays[8], 0, 2). "-". substr($arrays[8], 3, 2). " ". substr($arrays[8], 11, 8) . "'";
      }
    else
      {
        $dobstring = 'null';
      }
    if ($arrays[25]!="")
    {
    $insertdate = substr($arrays[25], 6, 4). "-". substr($arrays[25], 0, 2). "-". substr($arrays[25], 3, 2). " ". substr($arrays[25], 11, 8);
    }
  else
    {
      $insertdate = '1970-01-01 12:00:00';
    }
    if ($arrays[21]!="") { $pincode = $arrays[21]; } else  {   $pincode = 'null'; }
    if (strlen($arrays[21]==6)) { $pincode = $arrays[21]; } else  {   $pincode = intval(substr($arrays[21],0,6)); }

    if ($insertvalue=="") {    }   else   {    $insertvalue=$insertvalue . ", ";   }


if($arrays[4]!=""){ $firstname=$arrays[4];} else {  $firstname='null';}
if($arrays[5]!=""){  $lastname=$arrays[5];} else {  $lastname='null';}
if($arrays[6]!=""){  $mobile=$arrays[6];} else {  $mobile='null';}
if($arrays[7]!=""){  $email=$arrays[7];} else {  $email='null';}
if($arrays[9]!=""){  $secretcode=$arrays[9];} else {  $secretcode='null';}
if($arrays[10]!=""){  $dealercode=$arrays[10];} else {  $dealercode='null';}
if($arrays[11]!=""){  $dealername=$arrays[11];} else {  $dealername='null';}
if($arrays[12]!=""){  $sa=$arrays[12];} else {  $sa='null';}
if($arrays[13]!=""){  $sp=$arrays[13];} else {  $sp='null';}
if($arrays[14]!=""){  $sse=$arrays[14];} else {  $sse='null';}
if($arrays[15]!=""){  $asstsm=$arrays[15];} else {$asstsm='null';}
if($arrays[16]!=""){  $asm=$arrays[16];} else {  $asm='null';}
if($arrays[17]!=""){  $sh=$arrays[17];} else {  $sh='null';}
if($arrays[18]!=""){  $nh=$arrays[18];} else {  $nh='null';}
if($arrays[19]!=""){  $address1=str_replace("'", "", $arrays[19]);} else {  $address1='null';}
if($arrays[20]!=""){  $address1=str_replace("'", "", $arrays[20]);} else {  $address2='null';}
if($arrays[22]!=""){  $city=$arrays[22];} else {  $city='null';}
if($arrays[23]!=""){  $state=$arrays[23];} else {  $state='null';}


$insertvalue=$insertvalue .PHP_EOL. " (". $arrays[0]. "," . $arrays[1]. ",'" . $arrays[2]. "','" . $arrays[3] . "','" . $firstname .
"','" . $lastname ."','" . $mobile."','" . $email . "'," . $dobstring . "," . $secretcode . "," . $dealercode.
",'" . $dealername. "'," . $sa. "," .$sp. "," .$sse. "," .$asstsm. ",".
$asm. ",".$sh. ",".$nh. ",'" .$address1. "','".$address2.
"'," .$pincode. ",'".$city."','".$state. "','".$arrays[24]. "','" .$insertdate."','".$insertdate."')";
//echo $insertvalue;
//echo PHP_EOL;
$insertcount++;
$innerloop++;
    if($insertcount%500==0){
      $insert_sql = "INSERT INTO `tbl_champion_master`(`SrNo`, `UserID`, `Username`, `UserType`, `FirstName`, 
      `LastName`, `Mobile`, `Email`, `DOB`, `SecretCode`, `DealerCode`, `DealerName`, `SA`, `SP`, `SSE`,
      `AsstSM`, `ASM`, `SH`, `NH`, `Address1`, `Address2`, `Pincode`, `City`, `State`, `Status`,
        `InsertedDate`, `UpdatedDate`) VALUES ";
      $insert_sql = $insert_sql . $insertvalue;
      $mainloop++;
      $result = $con->query($insert_sql);
      if($result)
        {
            

        }
      else
        {
        //echo "Error at " . $mainloop . PHP_EOL;
        fwrite($logfile, "Error at LoopCount:" . $mainloop );
        fwrite($logfile, PHP_EOL);
        fwrite($logfile, $insert_sql);
        fwrite($logfile, PHP_EOL);
  
        }
      
      //echo "Inner Loop:" . $innerloop . " Main Loop:" . $mainloop . " " ;
      //echo $insertcount . PHP_EOL;
      
      //echo $insert_sql;
      $insertvalue = "";
      $innerloop = 0;
      
      }

    

  }
    $insert_sql = "INSERT INTO `tbl_champion_master`(`SrNo`, `UserID`, `Username`, `UserType`, `FirstName`, 
  `LastName`, `Mobile`, `Email`, `DOB`, `SecretCode`, `DealerCode`, `DealerName`, `SA`, `SP`, `SSE`,
   `AsstSM`, `ASM`, `SH`, `NH`, `Address1`, `Address2`, `Pincode`, `City`, `State`, `Status`,
    `InsertedDate`, `UpdatedDate`) VALUES ";
    $insert_sql = $insert_sql . $insertvalue;
    $result = $con->query($insert_sql);
    //echo $insert_sql;
    //echo $insertcount . "-" . $recordcount;
    fwrite($logfile, "Total number of records inserted:". $insertcount. PHP_EOL);
    fwrite($logfile,"Process Ended at :".date('Ymdhis'));
        fwrite($logfile, PHP_EOL);
  
    fclose($logfile);
  }
  else{
//error message to be generated informing that the text file is too large

  
  
   //print_r ($array);   
  }
}
  catch ( Exception $e ) {
    // send error message if you can
  } 

?>
