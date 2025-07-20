<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration from</title>
    <link rel="stylesheet" href="style.css">
         <link rel="icon" href="favicon-32x32.png" sizes="48x48" type="image/png">

</head>
<body>
     <?php
  // echo"hello world";
      $conn=mysqli_connect("localhost","root","","student");
      if(!$conn){
         echo"not connected";
      }
         if(isset($_POST['FirstName'])){
         $firstname=$_POST['FirstName'];
         $lastname=$_POST['LastName'];
         $email=$_POST['Email'];
         $password=$_POST['password']; 
         $coures=$_POST['course'];
         $gender=$_POST['gender'];
         $hobbies= $_POST['hobbies'];
         $string= implode(",",$hobbies);
         $filename = $_FILES['uploadfile']['name'];
         $tempname = $_FILES['uploadfile']['tmp_name'];
         $folder = 'images/';
         move_uploaded_file($tempname, $folder . $filename);

      $sql= "insert into student values( null,' $firstname','$lastname',' $email','$password','$coures','$gender','$string','$filename')";
            $re=mysqli_query($conn,$sql);
            if(!$re){
               echo"not";
            }
     }
?>
<?php if ($_SERVER["REQUEST_METHOD"] == "POST") { // Handle form submission here 
        // For example, save data to the database or process it in some way // Redirect to thankyou.html 
         echo "<script type='text/javascript'>window.location.href = 'thankyou.html';</script>"; exit(); } 
?>
    <div class="container">
        <div class="text">
            Student Registration
        </div>
        <form action="#" method="POST" enctype="multipart/form-data">
           <div class="form-row">
              <div class="input-data">
                 <input type="text" required name="FirstName">
                 <div class="underline"></div>
                 <label for="">First Name</label>
              </div>
              <div class="input-data">
                 <input type="text" required name="LastName">
                 <div class="underline"></div>
                 <label for="">Last Name</label>
              </div>
           </div>
           <div class="form-row">
              <div class="input-data">
                 <input type="text" name="Email" required>
                 <div class="underline"></div>
                 <label for="">Email Address</label>
              </div>
              <div class="input-data">
                 <input type="password" name="password" required>
                 <div class="underline"></div>
                 <label for=""> password</label>
              </div>
           </div>
            <div class="form-row padd">
                  <div class="input-data">
                      <h3 for="course">College Course:</h3>
                      <select id="course" name="course" required>
                          <option value="" disabled selected>Select your course</option>
                          <option value="computer_science">Computer Science</option>
                          <option value="business">Business Administration</option>
                          <option value="engineering">Engineering</option>
                          <option value="medicine">Medicine</option>
                          <option value="arts">Arts and Humanities</option>
                      </select>
                      <div class="underline"></div>
                  </div>
                      <h3 class="gender"  required  >Gender:</h3><br />
                      <h4 class="gender">Male<br><br>
                      <input type="radio" id="male" name="gender" value="male"></h4>
                      <h4 class="gender">Female<br><br>
                      <input type="radio" id="female" name="gender"value="female"></h4>
                      <h4 class="gender">Other<br><br>
                      <input type="radio" id="other" name="gender" value="other">
                      </h4>
                 </div>
            <div class="input-data hobb_gap" >
                <h3  required>Hobbies:</h3><br/>
                     <h4>Reading<br/>
                     <input type="checkbox" id="reading" name="hobbies[]" value="reading"></h4>
                     <h4>Traveling<br/>
                     <input type="checkbox" id="traveling" name="hobbies[]" value="traveling"></h4>
                     <h4>Cooking <br/>
                     <input type="checkbox" id="cooking" name="hobbies[]" value="cooking"></h4>
                     <h4>Sports<br/>
                     <input type="checkbox" id="sports" name="hobbies[]" value="sports"></h4>
                     <h4>Music<br/>
                     <input type="checkbox" id="music" name="hobbies[]" value="music"></h4>
                     <div style="  margin-top: 40px;">
                        <h3 >Upload your image:</h3>
                        <input type="file" name="uploadfile" required >
                     </div>
             </div>
              <div class="form-row submit-btn">
                 <div class="input-data">
                    <div class="inner"></div>
                    <input type="submit" value="submit" >
                 </div>
              </div>
        </form>
        </div>
</body>
</html>
