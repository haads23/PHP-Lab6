<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    include "./Lab6Common/Header.php";
    include('./Lab6Common/Functions.php');
      session_start();
      
      // if try entering page without logging-in will redirect to login page. 
      if($_SESSION['StudentId'] != true)
    {
          $_SESSION['returnUrl'] = "CurrentRegistration.php";
        header("Location: Login.php ");
    }
    
?>
<html>
    <head>
        <meta charset="UTF-8">
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>Current Registration</title>
        
    </head>
    <body>
          <h1>Current Registration</h1>
          <?php
          
            $stName= $_SESSION['StudentName'];
                       
             echo "<p>Welcome $stName ! (Not you? Change user <a href='logout.php'>here</a>) , the followings are your current registrations</p> ";
          ?>
       
           <form method="post">
                 
            <br>
            <br>
            <table class=table> 
                <tr>
                    <th>Year</th>
                    <th>Term</th>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Hours</th>
                     <th>Select</th>
                                    
                </tr>            
               
                <tr>
                    <?php
                    
                     // when the checkbox is selected and the delete btn is clicked will delete registered course selected
                        if(isset($_POST['select']))
                        {
                             $stID =   $_SESSION['StudentId']; 
                    
                            // saves the selected checkbox into session. 
                            $select = $_POST['select'];
                            
                            
                            foreach ($select as $value) 
                            {
                             // NEED to only delete when click ok button  and should automatically refersh page 
                               global $myPdo; 
                     
        
                               $sql_delete = "DELETE FROM Registration WHERE CourseCode= :code AND StudentId=:studentid"; 
                               $stmt= $myPdo->prepare($sql_delete);
                           
                               $stmt->execute(['code' => $value, 'studentid'=>$stID]);              
                            }

                        } //END OF if(isset($_POST['select']))
                        
                        
                    $stID =   $_SESSION['StudentId']; 
                    
                     $sqlGetSemester = "SELECT DISTINCT Registration.SemesterCode FROM Registration "
                        . "INNER JOIN Semester ON Semester.SemesterCode = Registration.SemesterCode "
                        . "WHERE Registration.StudentId = :studentID "
                        . "ORDER BY Registration.SemesterCode";
                        
                        $stmtSemester = $myPdo->prepare($sqlGetSemester);
                        $stmtSemester->execute(['studentID'=>$stID]);
                        global $selectedSemesterCode;
                        $selectedSemesterCode = $stmtSemester->fetchAll();
                        
                        foreach ($selectedSemesterCode as $code) 
                        {
                            $semesterCategory = $code["SemesterCode"];
                            global $myPdo;
                          $stID =   $_SESSION['StudentId']; 

                         // 
                          $sql_Year = "SELECT Registration.SemesterCode, Semester.Term, Semester.Year
                                        FROM Semester
                                        INNER JOIN Registration
                                        ON Semester.SemesterCode=Registration.SemesterCode
                                        WHERE Registration.StudentId = " . $myPdo->quote($stID)." AND Registration.SemesterCode =". $myPdo->quote($semesterCategory)."
                                        ORDER BY Registration.SemesterCode" ;                                             
                          $sql_Term = "SELECT Registration.SemesterCode, Semester.Term, Semester.Year
                                        FROM Semester
                                        INNER JOIN Registration
                                        ON Semester.SemesterCode=Registration.SemesterCode
                                        WHERE Registration.StudentId = " . $myPdo->quote($stID)." AND Registration.SemesterCode = ". $myPdo->quote($semesterCategory)."
                                        ORDER BY Registration.SemesterCode";      
                         $sql_Code = "SELECT Registration.CourseCode, Course.Title, Course.WeeklyHours
                                 FROM Course
                                 INNER JOIN  Registration
                                 ON Course.CourseCode=Registration.CourseCode
                                 WHERE Registration.StudentId = " . $myPdo->quote($stID)." AND Registration.SemesterCode = ". $myPdo->quote($semesterCategory)."
                                 ORDER BY Registration.SemesterCode";      
                          $sql_Title= "SELECT Registration.CourseCode, Course.Title, Course.WeeklyHours
                                 FROM Course
                                 INNER JOIN  Registration
                                 ON Course.CourseCode=Registration.CourseCode
                                 WHERE Registration.StudentId = " . $myPdo->quote($stID)." AND Registration.SemesterCode = ". $myPdo->quote($semesterCategory)." 
                                  ORDER BY Registration.SemesterCode";      
                          $sql_Hours= "SELECT Registration.CourseCode, Course.Title, Course.WeeklyHours
                                 FROM Course
                                 INNER JOIN  Registration
                                 ON Course.CourseCode=Registration.CourseCode
                                 WHERE Registration.StudentId = " . $myPdo->quote($stID)." AND Registration.SemesterCode = ". $myPdo->quote($semesterCategory)." 
                                  ORDER BY Registration.SemesterCode";      
                         
                         $stmtYear = $myPdo->query($sql_Year);
                         $stmtTerm = $myPdo->query($sql_Term);
                         $stmtCode= $myPdo->query($sql_Code);
                         $stmtTitle = $myPdo->query($sql_Title);
                         $stmtHours = $myPdo->query($sql_Hours);
             
                         $totalHours = 0; 
                         
                        
                        
                         while(($rowCode=$stmtCode->fetch()) && ($rowTitle=$stmtTitle->fetch()) && ($rowHours=$stmtHours->fetch()) &&
                                 ($rowYear=$stmtYear->fetch()) && ($rowTerm=$stmtTerm->fetch()))
                        {                 
                            echo "<tr>";
                            $year = $rowYear['Year'];
                            $Term = $rowTerm['Term'];     
                            $code= $rowCode['CourseCode'];
                            $Title = $rowTitle['Title'];
                            $Hours = $rowHours['WeeklyHours'];
                            
                            
                            echo "<td> $year </td>";
                            echo "<td> $Term </td>";
                            echo "<td> $code</td>";
                            echo "<td>$Title</td>";
                            echo "<td>$Hours</td>";
                            $totalHours += $Hours; 
                                                      
                            echo "<td> <input type='checkbox' name='select[]' value='$code'  > </td>";
                            echo"</tr>";                       
                        }
                                             
                        echo "<tr>";
                         echo "<th></th>";
                         echo "<th></th>";
                         echo "<th></th>";
                         echo "<th style='text-align: right;'>Total Weekly Hours</th><td>$totalHours</td>";
                       echo "</tr>";
                        } //end of semester loop
                    
                        
                       
                    ?>
                    
                </tr> 
                
            </table>
            
            <br>
            <input class="btn btn-success" type="submit" value="Delete Selected" name="delete" onclick="return myFunctionDelete()"  >
            <input class="btn btn-primary" type="reset" value="Clear">
            
        </form>
          
    <script>
        function myFunctionDelete() 
        {
            if( confirm("The selected registrations will be deleted!"))
            {
                return true;
            }
            else
            {
                return false; 
            }
           
           
      
        }
        
         
    </script>
    </body>
</html>
<?php
     include './Lab6Common/Footer.php';
?>
