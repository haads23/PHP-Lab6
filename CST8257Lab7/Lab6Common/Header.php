<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="en" style="position: relative; min-height: 100%;">
    <head>
        <meta charset="UTF-8">
        <title>Online Course Registration</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="/AlgCommon/Contents/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/AlgCommon/Contents/AlgCss/Site.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </head>
    <body style="padding-top: 50px; margin-bottom: 60px;">
       <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" 
                           data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" style="padding: 10px" href="http://www.algonquincollege.com">
                  <img src="/AlgCommon/Contents/img/AC.png" 
                       alt="Algonquin College" style="max-width:100%; max-height:100%;"/>
              </a>    
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                   <li class="active"><a href="Index.php">Home </a></li>
                   <li class="active"><a href="CourseSelection.php">Course Selection </a></li>
                   <li class="active"><a href="CurrentRegistration.php">Current Registration  </a></li>
<!--                   <li class="active"><a href="logout.php">logout </a></li>-->
                   
                     <?php
                     session_start(); 
                if($_SESSION['StudentId'] == "")
                {
                    echo "<li><a href='Login.php'>Login</a></li>";
                }
                else
                {
                    echo "<li><a href='logout.php'>Logout</a></li>";
                }
               ?>
                           
                </ul>
            </div>
          </div>  
        </nav>
    
