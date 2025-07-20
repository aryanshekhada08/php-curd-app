<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  WELCOME PHP CURD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.6/css/dataTables.dataTables.min.css">  
   
</head>
<body>
    <header>
        <!-- <nav class="navbar navbar-
        </nav> -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <img src="./logo.jpeg" alt="" style="width: 90px; height: 90px; object-fit: cover; margin-left:00px; border-redius:10px;">
                <a class="navbar-brand" href="#">PHP CURD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Moer
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Beack to home</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Something do here</a></li>
                    </ul>
                </li>
                </ul>
                <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
            </div>
        </nav>
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <strong>Wellcome !</strong> You should check in on some of those data below.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </header>
    <?php 
        $delete=false;
    ?>
         <button class='btn btn-primary'>
          <a href='index.php' class='text-light .mx-4'>Add New user</a>
        </button> 
    <?php
    if($delete){
        echo"<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Holy !</strong> You should check in on some of those fields below.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
    ?>  
    <?php
        // echo"hello world";
         $conn=mysqli_connect("localhost","root","","student");
        if(!$conn){
            echo"do not connected";
        } 
        ?>
       <div class="contanier1 my-5">
          
       </div>
    <contanier>
        <table class="table" id="myTable">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">First_Name</th>
                <th scope="col">Last_Name</th>
                <th scope="col">Email</th>
                <th scope="col">Password</th>
                <th scope="col">Course</th>
                <th scope="col">Gender</th>
                <th scope="col">Hobbies</th>
                <th scope="col">Image_Name</th>
                <th scope="col">Modify</th>
              </tr>
            </thead>
            <tbody>
            <?php   
                    $sqli = "SELECT * FROM student";
                    $result = mysqli_query($conn, $sqli);
                    echo mysqli_num_rows($result);
                    while ($row = mysqli_fetch_assoc($result)) { 
                        echo" <tr>
                                    <th scope='row'>".$row['ID']."</th>
                                    <td>". $row['First_Name']."</td>
                                    <td>". $row['Last_Name']."</td>
                                    <td>".$row['Email']."</td>
                                     <td>". $row['Password']."</td>
                                    <td>".$row['College_Course']."</td>
                                     <td>". $row['Gender']."</td>
                                    <td>".$row['Hobbies']."</td>
                                    <td>".$row['your_image']."</td>
                                    <td>
                                        <button class='btn btn-primary'>
                                             <a href='edit.php?id=".$row['ID']."' class='text-light'>Edit</a>
                                        </button> 
                                        <button class='btn btn-danger'>
                                            <a href='delete.php?id=".$row['ID']."' class='text-light'>Delete</a>
                                        </button>
                                    </td>    
                              </tr>";
                    }
                    if(!$result){
                        echo"not working";
                    }
            ?>
             
            </tbody>
          </table>
    </contanier>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.1.6/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('#myTable');
    </script>
</body>
</html>
