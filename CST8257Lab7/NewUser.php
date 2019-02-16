<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    include "./Lab6Common/Header.php";
    include "./Lab6Common/Functions.php";
  
?>
<html>
    <head>
        <meta charset="UTF-8">
        
        <title></title>
    </head>
    <h3>Sign Up</h3>
    <p>All Fields are Required</p>
    <body>
        <?php
            $studnetId=$_POST['StudentId'];
            $name= $_POST['Name'];
            $phone = $_POST['phone'];
            $password1 =  $_POST['Password'];
            $passwordA =  $_POST['PasswordAgain'];

            $studnetIdErr = "";
            $nameErr ="";
            $phoneErr = "";
            $passwordErr = "";          
            $passwordAErr = "";
            $msg = "";
            $anyErr = false;

            //Database connection
            $dbConnection = parse_ini_file("Lab6Common/db_connection.ini");	
            extract($dbConnection);
            $myPdo = new PDO($dsn, $user, $password);
            
            
            function student_exist($studnetId) 
            {
                global $msg, $myPdo;
                // compares StudetId from database to the Id that is entered by user. 
                $sql_select = "SELECT StudentId
                    FROM student
                    WHERE StudentId = " . $myPdo->quote($studnetId) . "
                    LIMIT 1";
                $stmt = $myPdo->query($sql_select); 
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // If user exist in the database, error msg will be displayed.
                if ($user !== false) 
                {
                    $msg = "Student with id: $studnetId already exists.";
                } 
                else
                {
                    //$msg = "Success";
                    // redirect to course selection page
                   header("Location: http://localhost/CST8257Lab7/CourseSelection.php ");
                }                   
                return $msg;
            }// function student_exist($studnetId)
            
            //sql stmt to Add entered New user info into the database. 
            function insert_student($studnetId, $name, $phone,$password1 ) 
            {
                global $myPdo; 
                // Insert the info the user entered into the Studnet table. 
                $sql = "INSERT INTO student (StudentId, Name, Phone, Password)
                    VALUES (?,?, ?, ?)";
                $stmt= $myPdo->prepare($sql);
                $stmt->execute([$studnetId, $name, $phone,$password1]);
            }// function insert_student($studnetId, $name, $phone,$password1 ) 
            
            
            if ($_SERVER["REQUEST_METHOD"]=="POST")
            {
            // set validation functions 
                $studnetIdErr = ValidateStudnetId($studnetId); 
                $nameErr= ValidateName($name);
                $phoneErr= ValidatePhone($phone);
                $passwordErr= ValidatePassword($password1); 
                            
               if ((!$studnetIdErr)&&(!$phoneErr)&& (!$nameErr)&&(!$passwordErr)) 
               {
                    // call student_exist function 
                     $msg = student_exist($studnetId);
                   //call insert_student function and add the user entered varibles. 
                     insert_student($studnetId, $name, $phone, $password1);
                                 
                }//  if ($studnetIdErr == $phoneErr && $nameErr == $passwordErr)  
                
            }// if ($_SERVER["REQUEST_METHOD"]=="POST")
        ?>
        
        <form method = "POST" >  
            <hr>
            
            <!--displays error msg: studnetId already exist in database-->
            <td><span class="error" style="color: red"> <?php echo $msg; ?></span></td>
            
            <table>
                 <tr>
                     <td> Student ID:</td><td><input type = "text" name = "StudentId" 
                                                     value="<?php if (isset($studnetId)) {echo htmlentities($studnetId);}?>" ></td>
                    <td><span class="error" style="color: red"> <?php echo $studnetIdErr; ?></span></td>
                </tr>

                <tr>
                    <td>Name:</td><td><input type = "text" name = "Name"  
                                             value="<?php if (isset($name)) {echo htmlentities($name);}?>" ></td>
                    <td> <span class="error" style="color: red"> <?php echo $nameErr; ?></span></td>
                </tr>

                <tr>
                    <td>Phone Number: <br>(nnn-nnn-nnn)</td><td><input type = "text" name = "phone" 
                                                                       value="<?php if (isset($phone)) {echo htmlentities($phone);}?>"></td>
                    <td><span class="error" style="color: red"> <?php echo $phoneErr; ?></span></td>
                </tr>

                <tr>
                    <td>Password:</td><td><input type = "password" name = "Password"  
                                                 value="<?php if (isset($password1)) {echo htmlentities($password1);}?>" ></td>
                    <td><span class="error" style="color: red"> <?php echo $passwordErr; ?></span></td>
                </tr>
                
                <tr>
                    <td>Password Again:</td><td><input type = "password" name = "PasswordAgain"  
                                                       value="<?php if (isset($passwordA)) {echo htmlentities($passwordA);}?>" ></td>
                    <td><span class="error" style="color: red"> <?php echo $passwordErr; ?></span></td>
                </tr>

            </table>

            <br>
            <hr> 
            
            <input name="submit" type = "submit" value = "Submit"  class="btn btn-info" />
            <!--<button type="reset" class="btn btn-info" >Clear</button>-->
            <input type="button" onclick="location.href='NewUser.php'; " value="Clear"class="btn btn-info"  />
        </form>
    </body>
    </body>
</html>
<?php
     include './Lab6Common/Footer.php';
?>
