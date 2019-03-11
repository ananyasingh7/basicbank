<?php
    include ("account.php");
    include ("assignment1functions.php");

    error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    ini_set ('display_errors', 1);

    $db = mysqli_connect ($hostname, $username, $password, $project);

    if (mysqli_connect_errno ()) 
    {
        echo "Failed to connect MySQL: " . mysqli_connect_error ();
        exit ();
    }
    
    echo "<br>Successfully connected to MySQL.";
    echo "<hr>Inputs:";
    mysqli_select_db ($db, $project);
    
    getData("ucid", $ucid);
    getData("pass", $pass);
    getData("account", $account);
    getData("amount", $amount);
    getData("number", $number);
    //$ucid = $_GET [ "ucid" ];
    echo "<br>UCID : $ucid";
    //$pass = $_GET [ "pass" ]; 
    echo "<br>PASS : $pass";
    echo "<br>ACCOUNT : $account";
    //$amount = $_GET["amount"]; echo "<br>amount: $amount";
    //$account = $_GET [ "account" ]; echo "<br>ACCOUNT : $account";
    if (! isset ($_GET ["all"])) {$box = NULL ;} else ($box = "on");
    if (! authenticate ( $ucid, $pass, $db ) ) {exit("<br>BAD CREDENTIALS");};
    echo "<br>Credentials are valid";
    //display ( $ucid, $account, $box, $number, $db);  

    $choice = $_GET["choice"];

    switch ($choice){
        case "t":
            transact($ucid, $account, $amount, $out, $db);
            break;
        case "d":
            display($ucid, $account, $box, $number, $db);
            break;
        
    }
    if (isset($_GET["mailer"])){
      mailer($ucid,$out);
    }

    
    mysqli_close($db);
    exit("Interaction complete");
?>
