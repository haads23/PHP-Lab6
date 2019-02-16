<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            // database connection using PDO
            $dbConnection = parse_ini_file("Lab6Common/db_connection.ini");	
            extract($dbConnection);
            $myPdo = new PDO($dsn, $user, $password);
            
            


    
            function ValidateStudnetId($studnetId)
            {
                            
                // Check of name is blank
                if (empty($studnetId))
                { 
                    $studnetIdErr = " StudnetId is Required \n ";
                    $anyErr=true;
                }
      
                return $studnetIdErr;
            }// function ValidateStudnetId($studnetId)
            
            function ValidateName($name)
            {
            
                // Check of name is blank
                if (empty($name))
                {
                    
                    $nameErr = " Name is Required \n ";
                    $anyErr=true;
                }
                return $nameErr;
            }//function ValidateName($name)
                   
            // Phone Validation 
            function ValidatePhone($phone)
            {
                $phoneReg ="/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/";
                // Check if phone is blank
                if (empty($phone))
                {
                    $phoneErr="  Phone number is Required.\n ";
                    $anyErr=true;
                }
                elseif (!preg_match($phoneReg, $phone))
                {
                    $phoneErr="Incorrect Phone number \n ";
                    $anyErr=true;
                }

                return $phoneErr;             
            }// End of function ValidatePhone($phone)
            
            function ValidatePassword($password1)
            {
                $upperCase = preg_match('@[A-Z]@', $password1);
                $lowerCase = preg_match('@[a-z]@', $password1);
                $number    = preg_match('@[0-9]@', $password1);

                if ($password1 == "")
                {
                    $passwordErr = "Password required";
                    $anyErr=true;
                    //global  $errorMessages;
                   // array_push($errorMessages, $passwordErr);
                }
                elseif(!$upperCase || !$lowerCase || !$number || strlen($password1) < 6) 
                {
                    $passwordErr = "Invalid password. Password Must haveat least 1 upper case, 1 lower case, and 1 digit.";
                    $anyErr=true;
                    //global  $errorMessages;
                    //array_push($errorMessages, $passwordErr);
                }
                return $passwordErr;                        
            }//  function ValidatePassword($password1)
        ?>
    </body>
</html>
