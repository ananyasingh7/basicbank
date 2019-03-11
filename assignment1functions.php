<?php

function getData($name, &$result){
    global $bad;
    global $db;
    if (!isset ($_GET[ $name ])){
        $bad = true;
    }
    if ($_GET[$name] == ""){
        $bad = true;
    }
    
    $result = mysqli_real_escape_string ($db, $_GET[$name]);
    
}
function authenticate ( $ucid, $pass, $db) {
    $s = "select * from users where ucid='$ucid' and pass='$pass'";
    ($t = mysqli_query ($db, $s)) or die (mysql_error ($db) );
    $num = mysqli_num_rows($t);
    if ($num == 0) { return false; } else {return true;};
}

function display ( $ucid, $account, $box, $number, $db) {
    $s = "select * from accounts where ucid='$ucid'";
    ($t = mysqli_query ($db, $s)) or die (mysqli_error ($db) );
    $num = mysqli_num_rows($t); echo "<hr>Received $num account<br><hr>";

    $out = "Functions were displayed";

  if(!isset($box)){
    $s = "select * from transactions where ucid='$ucid' and account ='$account'";
    ($t = mysqli_query ($db, $s)) or die (mysqli_error ($db) );
    $accountCounter = 0;
    while ( $r = mysqli_fetch_array($t, MYSQLI_ASSOC) ) 
    {
        $account = $r["account"];
        $amount = $r["amount"];
        $recent = $r["timestamp"];
        echo "Account is $account<br>";
        echo "Amount balance is $$amount<br>"; 
        echo "Timestamp is: $recent<hr>";
        $accountCounter++; 
        if ($accountCounter >= $number){
          break;
        }
    }  
  }  
  else{
    echo"BOX WAS SET";
    $s = "select * from transactions where ucid='$ucid'";
    ($t = mysqli_query ($db, $s)) or die (mysqli_error ($db) );
    $num = mysqli_num_rows($t); echo "Received $num transaction<br><hr>";
    while ( $r = mysqli_fetch_array($t, MYSQLI_ASSOC) ) {
      $account = $r["account"];
      $amount = $r["amount"];
      $recent = $r["timestamp"];
      echo "Account is $account<br>";
      echo "Amount balance is $$amount<br>"; 
      echo "Timestamp is: $recent<hr>";
      }
  }
    
}



function transact ($ucid, $account, $amount, &$out, $db){
  $mail = "N";
  $s = "SELECT * FROM accounts WHERE ucid = '$ucid' and account = '$account' AND ('$amount' + balance) >= 0.00";
  
  ($t = mysqli_query($db, $s) ) or die (mysqli_error($db) );
  
  $num = mysqli_num_rows ($t);
  
  if ($num == 0){ $out .= "<br>Overdrawn"; return; }
  
  $out .= "Completed Transaction.";
  
  $s = "UPDATE accounts Set balance = '$amount' + balance, recent = NOW() where ucid='$ucid' and account ='$account'";
  ($t = mysqli_query($db, $s) ) or die (mysqli_error($db) );
  
  $s = "Insert into transactions values ('$ucid', '$account', '$amount', NOW(), '$mail')";
  ($t = mysqli_query($db, $s) ) or die (mysqli_error($db) );
  

  echo "<table border=2 cellpadding=10>";
  $s = "SELECT * from transactions WHERE ucid='$ucid' AND account='$account'";
  ($t = mysqli_query($db, $s) ) or die (mysqli_error($db) );

  while($r=mysqli_fetch_array($t,MYSQLI_ASSOC)){
    
    echo "<tr>";
    echo "<td>".  $r["ucid"]. "</td>";
    echo "<td>".  $r["account"]. "</td>";
    echo "<td>".  $r["amount"]. "</td>";
    echo "<td>".  $r["timestamp"]. "</td>";
    echo "<td>".  $r["mail"]. "</td>";
    echo "</tr>";
  }
  
  
  echo "</table>";
}



function mailer($user,&$out){
    global $db;
    $choice = $_GET["choice"];
    
    $timestamp = date_default_timezone_set("America/New_York");
    
    if ($choice != ""){
        $s = "SELECT * FROM transactions where ucid = '$user'";
        echo $s;
        $t = mysqli_query($db,$s) or die( mysqli_error($db));
          
          //time function
          $time = time();
          $message = $out;
          $to = "as2863@njit.edu";
          print "<br>Sending a mail copy to the email address: $to";
          $subject = "Assignment 1: " .date("Y-m-d H:i:s", $time) ;//.date("Y-m-d h:i:sa", $timestamp);
          $headers = 'From: as2863@njit.edu' . "\r\n" .'Reply-To: as2863@njit.edu' . "\r\n" .'X-Mailer: PHP/' . phpversion();
          mail($to, $subject, $message);
          
          $s = "UPDATE transactions Set mail = 'Y' where ucid = '$user'";
          $t = mysqli_query($db,$s) or die( mysqli_error($db));
    }
    else{
        print "Choice was not show...cannot send mail";
        die;
    }
}





?>
