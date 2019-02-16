<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    include "./Lab6Common/Header.php";
    include "./Lab6Common/Functions.php";
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <h3>Log In</h3>
    <p>You need to <a href="http://localhost/CST8257Lab7/NewUser.php">sign up</a> if you are a new user.</p>
    <body>
        <?php
            $studnetId=$_POST['StudentId'];
            $password1 =  $_POST['Password'];
            
            $studnetIdErr = "";
            $passwordErr = "";  
            $msg="";
            
             function studnet_login($studnetId, $password1) 
            {
                global $msg, $myPdo;
                // compares StudetId from database to the Id that is entered by user.        
                $sql_select = "SELECT StudentId, Password
                     FROM student
                     WHERE StudentId = " . $myPdo->quote($studnetId) . " AND Password = ". $myPdo->quote($password1) ."
                     LIMIT 1";
                   $stmt = $myPdo->query($sql_select); 
                   $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                
                // If user exist in the database, error msg will be displayed.
                if ($user !== false) 
                {             
                   // gets the name of from the studnet table using the studentId that was entered. 
                    $sql_name = ("SELECT Name FROM student WHERE StudentId = '$studnetId' ");
                    $stmtName = $myPdo->query($sql_name);
                    $Stname = $stmtName->fetchColumn();
                    
                   $_SESSION['StudentName']= $Stname; 
                   $_SESSION['StudentId']= $studnetId; 
             
                   if(isset( $_SESSION['returnUrl']))
                   {
                      header("Location: ".$_SESSION['returnUrl']);
                   }
                   else
                   {
                        // Redirect to course selection page 
                    header("Location: http://localhost/CST8257Lab7/CourseSelection.php ");
                   }
                   
                } 
                else
                {
                    $msg = "Student with id: $studnetId and/or password does not exist.";              
                }                   
                return $msg;
            }// function studnet_login($studnetId, $passWrd)
            
            if ($_SERVER["REQUEST_METHOD"]=="POST")
            {
                // setting Validation Functions 
                $studnetIdErr = ValidateStudnetId($studnetId);
                $passwordErr = ValidatePassword($password1);    
               
                // If there are no errors, when studnetIdErr is not ture and passwordErr is not true. 
               if((!$studnetIdErr) &&(!$passwordErr))
               {
                  // will call studnet_login function, if user exist in db will redirect to course selec, else msgErr. 
                   $msg= studnet_login($studnetId, $password1);
                  
               }                                                
            }// if ($_SERVER["REQUEST_METHOD"]=="POST")                 
        ?>
        
        <form method = "POST">
            <hr>
             <!--displays error msg: studnetId does not exist in database-->
             <td><span class="error" style="color: red"> <?php echo $msg; ?></span></td>
             
            <table>
                 <tr>                  
                     <td> Student ID:</td><td><input type = "text" name = "StudentId" 
                                                     value="<?php if (isset($studnetId)) {echo htmlentities($studnetId);}?>"  ></td>
                    <td><span class="error" style="color: red"> <?php echo $studnetIdErr;?></span></td>
                </tr>
               
                 <tr>
                    <td>Password:</td><td><input type = "text" name = "Password" 
                                                 value="<?php if (isset($password1)) {echo htmlentities($password1);}?>"></td>
                    <td><span class="error" style="color: red"> <?php echo $passwordErr; ?></span></td>
                </tr>
            </table>
            
            <br>
            <hr>
            
            <input name="submit" type = "submit" value = "Submit"  class="btn btn-info" />
<!--            <button type="reset" onclick="location.href='.php'; class="btn btn-info" >Clear</button>                -->
            <input type="button" onclick="location.href='Login.php'; " value="Reset"class="btn btn-info"  />
        </form>
    </body>
</html>
<?php
     include './Lab6Common/Footer.php';
?>
