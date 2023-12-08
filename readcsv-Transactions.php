<?php
//$filenames=array('20231018','20231019','20231020','20231021','20231022','20231023','20231024','20231025',
//'20231026','20231027','20231028','20231029','20231030','20231031');
//foreach($filenames as $filename){
$recordcount = 0;
$insertcount = 0;
$innerloop = 0;
$mainloop = 0;
$datefile = date('Ymd');
//$fname = '/var/www/html/reports/readcsv/'.$filename.'-TransactionReportAdmin.csv';
//$fname = '/var/www/html/reports/readcsv/'.$datefile.'-TransactionReportAdmin.csv';
$fname = '/home/ec2-user/csv/'.$datefile.'-TransactionReportAdmin.csv';
//count the number of lines in the file
$logfilename='/home/ec2-user/readcsv/'.$datefile."log.txt";
//$logfilename='/var/www/html/reports/readcsv/'.$datefile."log.txt";
$logfile = fopen($logfilename, "a");
fwrite($logfile,PHP_EOL. "Process Started at :".date('Ymdhis'). PHP_EOL);

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
      fclose($open);
    }
else  {
  fwrite($logfile, "ERROR! Could not open the CSV file for Champion Scannings ERROR!". PHP_EOL); 
}      
 
      require("dbconnect.php");
      
      //data sanitisation
 //data insert
 $insert_sql = "INSERT INTO `tbl_champion_transactions`
 (`uniquereference`, `userid`, `username`, `FirstName`, `LastName`, `points`, `debitcredit`,
  `BalanceAfter`, `DateTransaction`, `Type`, `UserType`, `Reference`) VALUES ";
  foreach($array as $arrays){
    if ($arrays[8]!="")
      {
      $transactiondate = "'". substr($arrays[8], 6, 4). "-". substr($arrays[8], 0, 2). "-". substr($arrays[8], 3, 2). " ". substr($arrays[8], 11, 8) . "'";
      $uniquereference= substr($arrays[8], 6, 4). substr($arrays[8], 0, 2). substr($arrays[8], 3, 2) . $arrays[0];
    }
    else
      {
        $transactiondate = 'null';
        $uniquereference = 'T0000' . $arrays[0]; 
      }
      //if ($arrays[11]!="")
      //{
      //$campaignstart = "'". substr($arrays[11], 6, 4). "-". substr($arrays[11], 0, 2). "-". substr($arrays[11], 3, 2). " ". substr($arrays[11], 11, 8) . "'";
      //}
    //else
     // {
       // $campaignstart = 'null';
      //}
    //   if ($arrays[12]!="")
    //   {
    //     $campaignend = "'". substr($arrays[12], 6, 4). "-". substr($arrays[12], 0, 2). "-". substr($arrays[12], 3, 2). " ". substr($arrays[12], 11, 8) . "'";
    //   }
    // else
    //   {
    //     $campaignend = 'null';
    //   }
   
   // if ($arrays[21]!="") { $pincode = $arrays[21]; } else  {   $pincode = 'null'; }
    //if (strlen($arrays[21]==6)) { $pincode = $arrays[21]; } else  {   $pincode = intval(substr($arrays[21],0,6)); }

   if ($insertvalue=="") {    }   else   {    $insertvalue=$insertvalue . ", ";   }


if($arrays[3]!=""){ $firstname=rtrim($arrays[3]);} else {  $firstname='null';}

if($arrays[4]!=""){  $lastname=rtrim($arrays[4]);} else {  $lastname='null';}
//if($arrays[6]!=""){  $mobile=$arrays[6];} else {  $mobile='null';}
//if($arrays[7]!=""){  $email=$arrays[7];} else {  $email='null';}
//if($arrays[9]!=""){  $statestatus=$arrays[9];} else {  $statestatus='null';}
//if($arrays[10]!=""){  $bonusmultiplier=$arrays[10];} else {  $bonusmultiplier='null';}
//if($arrays[11]!=""){  $campaignstart=$arrays[11];} else {  $campaignstart='null';}
//if($arrays[12]!=""){  $campaignend=$arrays[12];} else {  $campaignend='null';}
//if($arrays[13]!=""){  $sp=$arrays[13];} else {  $sp='null';}
//if($arrays[14]!=""){  $sse=$arrays[14];} else {  $sse='null';}
//if($arrays[15]!=""){  $asstsm=$arrays[15];} else {$asstsm='null';}
//if($arrays[16]!=""){  $asm=$arrays[16];} else {  $asm='null';}
//if($arrays[17]!=""){  $sh=$arrays[17];} else {  $sh='null';}
//if($arrays[18]!=""){  $nh=$arrays[18];} else {  $nh='null';}
//if($arrays[19]!=""){  $address1=str_replace("'", "", $arrays[19]);} else {  $address1='null';}
//if($arrays[20]!=""){  $address1=str_replace("'", "", $arrays[20]);} else {  $address2='null';}
//if($arrays[22]!=""){  $city=$arrays[22];} else {  $city='null';}
//if($arrays[23]!=""){  $state=$arrays[23];} else {  $state='null';}


$insertvalue=$insertvalue .PHP_EOL. " ('". $uniquereference. "'," . $arrays[1]. ",'" . $arrays[2]. "','" . $firstname .
 "','" . $lastname . "'," . $arrays[5] .",'" . $arrays[6] ."'," . $arrays[7] . "," . $transactiondate .
 ",'" . $arrays[9] . "','" . $arrays[10]."','" .$arrays[11]."')";
//echo $insertvalue;
echo PHP_EOL;
$insertcount++;
$innerloop++;
    if($insertcount%200==0){
        $insert_sql = "INSERT INTO `tbl_champion_transactions`
        (`uniquereference`, `userid`, `username`, `FirstName`, `LastName`, `points`, `debitcredit`,
         `BalanceAfter`, `DateTransaction`, `Type`, `UserType`, `Reference`) VALUES ";
             $insert_sql = $insert_sql . $insertvalue;
            //echo $insert_sql . PHP_EOL;
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
  $insert_sql = "INSERT INTO `tbl_champion_transactions`
  (`uniquereference`, `userid`, `username`, `FirstName`, `LastName`, `points`, `debitcredit`,
   `BalanceAfter`, `DateTransaction`, `Type`, `UserType`, `Reference`) VALUES ";
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

//}//filename array loop ends here

?>
