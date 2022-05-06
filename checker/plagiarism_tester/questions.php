<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="">
<head>
<title>Questions</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
</head>
<body id="top">
<?php
#connection to the database
$conn = mysqli_connect("localhost:3306","root","") or die("Erreur: Connection Issue");
$bd = mysqli_select_db($conn,"checker") or die("Errreur: Database Issue");

$default_student_id = 1;
$default_assignment_id = 1;

if (isset($_POST['upload'])){
  #getting the file
  $file = rand(1000,100000)."-".$_FILES['file_up']['name'];
  $file_loc = $_FILES['file_up']['tmp_name'];
  $file_size = $_FILES['file_up']['size'];
  $file_type = $_FILES['file_up']['type'];
  $folder="upload/";


  $new_size = $file_size/1024;  
  $new_file_name = strtolower($file); 
  $final_file=str_replace(' ','-',$new_file_name);
  if(move_uploaded_file($file_loc,$folder.$final_file))
  {
    #checking if the file has the appropriate extension, if it does we store it in the database, if it doesn't we display an error and we redirect the student to the uplaod_file page
    $array = explode('.', $final_file);
    $extension = end($array);
      if($extension == "zip"){
        $request_insert_file = "insert into submission(student,assignment,file) values(".$default_student_id.",".$default_assignment_id.",'".$final_file."')";
        $result = mysqli_query($conn,$request_insert_file);
        $last_submission_id = mysqli_insert_id($conn);
        $_SESSION["submission_id"] = $last_submission_id;
        echo "<script type=\"text/javascript\">alert('Success : File Submited Successfully')</script>";
      }
      else {
        echo "<script type=\"text/javascript\">alert('Error : Upload a zip File')</script>";
        //header('Location: file_upload.php'); 
      }

 }
}
?>
<div class="bgded" style="background-image:url('images/demo/backgrounds/01.webp');"> 
  <div class="wrapper overlay row0">
    <div id="topbar" class="hoc clear">
      <div class="fl_left"> 

      </div>
    </div>
  </div>
</div>
<?php 
#getting the ids of all the questions in database
$req_get_question_ids = "SELECT question_id from question";
$res_ids = mysqli_query($conn,$req_get_question_ids);
$ids = array();
while($l = mysqli_fetch_row($res_ids)){
  array_push($ids,$l[0]);
}

$radom_5_questions = array();
for ($i=1;$i<=5;$i++){
    shuffle($ids);
    $selected = array_pop($ids);
    array_push($radom_5_questions,$selected);
}
?>
<div class="wrapper overlay">
    <div id="pageintro2" class="hoc"> 
      <article>
        <h3 class="heading">Welcome To Plagiarism Checker</h3>
        <br></br>
        <span class="center_title">Answer the questions to complete the test</span>
        <br></br><br>
      </article>
    </div>
  </div>
<div class="wrapper row3">
  <main class="hoc container clear"> 
    <section id="introblocks">
      <ul class="nospace group elements elements-three">
        <?php 
        //getting the question description for each of the 5 random questions
        $index = 0;
        foreach ($radom_5_questions as $question_id) {
          $index++;
          $request_get_question = "select description from question where question_id=".$question_id;
          $request_get_question_result = mysqli_query($conn,$request_get_question);

        //displaying the questions 
          while($l = mysqli_fetch_row($request_get_question_result)){
            $description = $l[0];
            echo "<li class='one_third'>
                    <article>
                      <footer><a href='answer_form.php?question_id=".$question_id."&question_number=".$index."'><i class='fas fa-question'></i></a></footer>
                      <h6 class='heading'>Question ".$index."</h6>
                      <p>".$description."</p>
                    </article>
                  </li>";
          }
        }
        ?>
      </ul>
    </section>
    <div class="clear"></div>
  </main>
</div>


</div>

  <div class="wrapper row5">
    <div id="copyright" class="hoc clear"> 
      <p class="fl_left">Copyright &copy; 2018 - All Rights Reserved - <a href="#">Domain Name</a></p>
      <p class="fl_right">Template by <a target="_blank" href="https://www.os-templates.com/" title="Free Website Templates">OS Templates</a></p>
    </div>
  </div>

<a id="backtotop" href="#top"><i class="fas fa-chevron-up"></i></a>

<script src="layout/scripts/jquery.min.js"></script>
<script src="layout/scripts/jquery.backtotop.js"></script>
<script src="layout/scripts/jquery.mobilemenu.js"></script>
<script src="layout/scripts/jquery.easypiechart.min.js"></script>

</body>
</html>