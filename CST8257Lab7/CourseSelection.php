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
        header("Location: Login.php ");
              
    }
   
?>
<html>
    <head>
        <meta charset="UTF-8">
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>Course Selection</title>
    </head>
    <body>
    
       <h1>Course Selection</h1>
            
        <?php
        
             // save studentId that that is being retrived from Login page into variable. 
             $sessionStudentId=$_SESSION['StudentId'];
             $stName= $_SESSION['StudentName'];
             
             //print welcome message. 
             echo "<p>Welcome $stName ! (Not you? Change user <a href='logout.php'>here</a>)</p> ";
                       
             // save the semester into a session
             $semes = $_POST["semester"];
           
            // retreive selected semester session, where is it being retreived from ?
           
             $_SESSION["selectedSem"] = $semes;
            //echo $semes; 
                           
        ?>
       
        
        <?php

           if ($_POST['submitBtn'])
           {
               global $myPdo;
               $hoursSql = "SELECT Course.WeeklyHours FROM Course "
                        . "INNER JOIN Registration ON Course.CourseCode = Registration.CourseCode "
                        . "WHERE Registration.StudentId = :studentID AND Registration.SemesterCode = :semesterCode";
            
            $totalHoursStmnt = $myPdo->prepare($hoursSql);
            $totalHoursStmnt->execute([ 'studentID'=>$sessionStudentId, 'semesterCode'=> $semes] );
            global $courseHours;
            $courseHours = $totalHoursStmnt->fetchAll(); // data array
            
            $studentHours = 0;
            // 
            foreach ($courseHours as $hours)
            {
                $myHours = $hours['WeeklyHours'];
                $studentHours += $myHours;      
            }
            $maxhourss= 16;
            
            
               // if checkbox is not selected and try to submit will give error
               if(!isset($_POST['select']))
               {
                   $contactErr =" You need to select at least one course.\n ";
                    $anyErr=true;
               }
                // if checkbox is selected    
                 if(isset($_POST['select']))
                {
                  // saves the selected checkbox into session. 
                    $select = $_POST['select'];
                    $getSemester= $_SESSION["selectedSem"];
                    global $myPdo; 
                    
                    // 
                    foreach ($select as $course)
                    {
                        $exceedHoursSql = "SELECT WeeklyHours FROM Course "
                        . "WHERE Course.CourseCode = :courseCode ";
            
                        $totalHoursExStmnt = $myPdo->prepare($exceedHoursSql);
                        $totalHoursExStmnt->execute([ 'courseCode'=> $course] );
                        global $courseExHours;
                        $courseExHours = $totalHoursExStmnt->fetchAll(); 

                        $hoursPossible = 0 + $studentHours; 
                        foreach($courseExHours as $Exhours)
                        {
                            $myExHours = $Exhours['WeeklyHours'];
                            $hoursPossible += $myExHours;       
                        }
                        
                        if($hoursPossible <=16)
                        {   
                             global $myPdo; 
                             $sql_insert = "INSERT INTO Registration (StudentId, CourseCode, SemesterCode)
                            VALUES (?,?, ?)";

                           $stmt= $myPdo->prepare($sql_insert);
                           $stmt->execute([$sessionStudentId, $course,$getSemester]);
                        }
                        else
                        {
                            $msgEx= 'Selection excceds max weekly hours!'; 
                        }
                    } // END OF foreach ($select as $course)               
                }// END OF if(isset($_POST['select']))
           }// END OF  if ($_POST['submitBtn'])
            
             global $myPdo;
             // Will get the WeeklyHours you are registered from Database. 
            $hoursSql = "SELECT Course.WeeklyHours FROM Course "
                        . "INNER JOIN Registration ON Course.CourseCode = Registration.CourseCode "
                        . "WHERE Registration.StudentId = :studentID AND Registration.SemesterCode = :semesterCode";
            
            $totalHoursStmnt = $myPdo->prepare($hoursSql);
            $totalHoursStmnt->execute([ 'studentID'=>$sessionStudentId, 'semesterCode'=> $semes] );
            global $courseHours;
            $courseHours = $totalHoursStmnt->fetchAll(); // data array
            
            $studentHours = 0;
            // 
            foreach ($courseHours as $hours)
            {
                $myHours = $hours['WeeklyHours'];
                $studentHours += $myHours;      
            }
            $maxhourss= 16;
            $hoursLeft=  ($maxhourss- $studentHours);
            // save selected checkbox into session
            $select = $_POST['select'];
            
           echo "<p>You have registered  $studentHours hours for the selected semester.</p>"; 
        
            echo "<p>You can register   $hoursLeft  more hours of course(s) for the semester.</p>";
        
         echo "<p>Please note that the courses you have registered for will not be displayed in the list.</p>";
        ?>
        
        <form method="post">
            
            <?php
            //echo $_POST["semester"]
            //echo $semes; 
            
                global $myPdo;
                $smt = $myPdo->prepare('select year, term, SemesterCode From semester');
                $smt->execute();
                global $semester; 
                $semester = $smt->fetchAll(); // data is an array, fetchAll() creates an array.          
            ?>
            
            <!--dropdownlist with the populated semesters-->
            <select id="selectSemester" name="semester" style="float: right" onchange="myFunction(this.form.submit())">    
                <option value="" disabled selected hidden>Select Semester</option>
                <?php foreach ($semester as $row): ?>
            <option value="<?=$row["SemesterCode"]?>" <?php if(isset($semes) && $semes == $row["SemesterCode"]) echo 'selected="selected"';?>>
                        <?=$row["year"]." ".$row["term"]?>
            </option>
                <?php $row["SemesterCode"]; endforeach?>
            </select>

            <p id="demo"></p>    
             
            <br>
            <br>
            <td><span class="error" style="color: red"> <?php echo $msgEx; ?></span></td>
            <td><span class="error" style="color: red"> <?php echo $contactErr; ?></span></td>
            <table class=table> 
                <tr>
                    <th>Code</th>                                
                    <th>Course Title</th>                
                    <th>Hours</th>
                    <th>Select</th>                                 
                </tr>            
               
                <tr>
                    <?php 
                      
                        global $myPdo;
                        // reteriving saved selected semester 
                         $semseterS=$_SESSION["selectedSem"];
                         
                         // will insert coursecode, weeklyhours and title, once course is registered, will not be displayed 
                        $sql="SELECT Course.CourseCode, Course.Title,  Course.WeeklyHours "
                          ."FROM Course INNER JOIN CourseOffer ON Course.CourseCode = CourseOffer.CourseCode "
                          ."WHERE CourseOffer.SemesterCode = :semesterCode "
                          . "AND Course.CourseCode NOT IN (SELECT CourseCode From Registration "
                        . "WHERE StudentId = :studentId AND Registration.SemesterCode = :semesterCode)";
                        
                        $stmt =$myPdo->prepare($sql);
                        $stmt->execute(['semesterCode'=>$semseterS, 'studentId'=> $sessionStudentId, 'semesterCode'=>$semseterS]);
                        global $arrayCousres; 
                        $arrayCousres= $stmt->fetchAll();

                    foreach ($arrayCousres as $row) 
                    {
                         echo "<tr>";
                            $courseCode = $row['CourseCode'];
                            $title = $row['Title'];
                            $hours= $row['WeeklyHours'];
                            echo "<td> $courseCode </td>";
                            echo "<td> $title </td>";
                            echo "<td> $hours </td>";
                            echo "<td> <input type='checkbox' name='select[]' value='$courseCode'> </td>";
                        echo"</tr>";      
                    }
                    ?>
                </tr> 
                
            </table>
            
            <br>
            <input class="btn btn-success" type="submit" value="Submit" name="submitBtn">
            <input class="btn btn-primary" type="reset" value="Clear">
            
            
        </form>
        
 <script>
    function myFunction() 
    {
//        var x = document.getElementById("selectSemester").submit();//.values  // this.form.submit()
//        document.getElementById("demo").innerHTML = "You selected: " + x;
    }
</script>
    </body>
</html>
<?php
    include './Lab6Common/Footer.php';
?>
