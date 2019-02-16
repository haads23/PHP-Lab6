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
            $anyErr = false;
            
              //database connection using PDO
                    $dbConnection = parse_ini_file("Lab6Common/db_connection.ini");	
                    extract($dbConnection);
                    $myPdo = new PDO($dsn, $user, $password);
            
            function student_exist($studnetId) 
            {
                global $msg, $myPdo;
                $sql_select = "SELECT StudentId
                    FROM student
                    WHERE StudentId = " . $myPdo->quote($studnetId) . "
                    LIMIT 1";
                $stmt = $myPdo->query($sql_select);
                $r = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($r !== false) 
                {
                    $msg = "Student with id: $studnetId already exists.";
                  //  return true;
                } 
                else
                {
                    $msg = "Success";
                }
                    
                return $msg;
            }
            function insert_student($studnetId, $name, $phone,$password1 ) 
            {
                global $myPdo; //$msg;
                $sql = "INSERT INTO student (StudentId, Name, Phone, Password)
                    VALUES (?,?, ?, ?)";
                $stmt= $myPdo->prepare($sql);
                $stmt->execute([$studnetId, $name, $phone,$password1]);
            }
            if ($_SERVER["REQUEST_METHOD"]=="POST")
            {
            
                $studnetIdErr = ValidateStudnetId($studnetId); 
                $nameErr= ValidateName($name);
                $phoneErr= ValidatePhone($phone);
                
//                $passwordErr = ValidatePassword($password1);               
//                // only validate second password if first pasword function returns null
//                if ($passwordErr == null)
//                {
//                   $passwordAErr = ValidatePasswordAgain($passwordA);
//                }
                
                    $studnetIdErr= student_exist($studnetId); 
                
                if ($studnetIdErr == $phoneErr && $nameErr == $passwordErr) 
                {
                     $studnetIdErr = student_exist($studnetId);
                      if ($studnetIdErr == "") 
                      { 
                          insert_student($studnetIdErr, $name, $phone, $password1);
                      }                    
                }
                   
                   
              
//                if($studnetIdErr!='' && $nameErr!='' && $phoneErr!='' && $passwordErr!='')
//                {
//                    
//                    $stmt = $myPdo->prepare('SELECT * FROM Student WHERE StudentId = :StudentId' );
//                    $stmt->execute(['StudentId' => $studnetId]);
//                    $user = $stmt->fetch();
//                    
//                    // $user == true
//                    if($user)
//                    {
//                         //return new Student($row['StudentId'] );  
//                         $studnetIdErr = " Studnet with this ID has already signed up. \n ";
//                         echo $studnetIdErr;
//                    }
//                    else
//                    {
//                        //var_dump($_POST);
//                        $sql = "INSERT INTO Student VALUES (:StudentId, :Name, :Phone, :Password)";
//                        $stmt = $myPdo ->prepare($sql) ;
//                        $stmt->execute(array ('StudentId' => $studnetId, 'Name'=> $name, 'Phone'=> $phone, 'Password'=> $password1));
//                        $stmt->commit; 
//                         // redirect to course selection page
//                         header("Location: http://localhost/CST8257Lab7/CourseSelection.php ");
//                         exit();
//                        
//                        
//                    }
//                }
//                
//                if ($anyErr)
//                {
//                    // Create sessions for both name and email to save the values of both. 
//                    $_SESSION['name'] = $name;
//                    $_SESSION['stId'] = $studnetId;
//                     
//                    // redirect to course selection page
//                     header("Location: http://localhost/CST8257Lab7/CourseSelection.php ");
//                }                
            }
        ?>
        
        <form method = "POST" >  
            <hr>
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
                    <td><span class="error" style="color: red"> <?php ?></span></td>
                </tr>

            </table>

            <br>
            <hr> 
            
            <input name="submit" type = "submit" value = "Submit"  class="btn btn-info" />
            <button type="reset" class="btn btn-info" >Clear</button>
        </form>
    </body>
    </body>
</html>
<?php
     include './Lab6Common/Footer.php';
?>
