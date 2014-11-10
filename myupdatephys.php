<?php
  ob_start();
  session_start();   
  require_once('connectvars.php');
 
   if(isset($_SESSION['adminornot'])) {
     if($_SESSION['adminornot'] == '1') {
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( null==$id ) {
        header("Location: myemployees.php");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $nameError = null;
        $addError = null;
        $phoneError = null;
        $aadharError = null;
        $dobError = null;
        $dhError = null;
        $depError = null; 
        // keep track post values
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
        $lname = $_POST['lname'];
        $address = $_POST['address'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $dob = $_POST['dob'];
        $aadhar = $_POST['aadhar'];
        $dh = $_POST['dh'];
        $depm = $_POST['depm'];
        $depw = $_POST['depw'];
        $spec = $_POST['spec'];
        $deg = $_POST['deg'];
     $depw = explode(':', $depw);
        $depw = $depw[0];
 
        if($depm != 'None') {
            $depm = explode(':', $depm);
            $depm = $depm[0];
        }
 
        // validate input
        $valid = true;
        if (empty($fname)) {
            $nameError = 'Please enter Name';
            $valid = false;
        }
         
        if (empty($address)) {
            $addError = 'Please enter Email Address';
            $valid = false;
        }
         
        if (empty($phone)) {
            $phoneError = 'Please enter Mobile Number';
            $valid = false;
        }
        if (empty($dob)) {
           $dobError = 'Please enter the Date of Birth';
           $valid = false;    
        }
        if (empty($aadhar)) {
           $aadharError = 'Please enter the aadhar number';
        }
        if (((!empty($depm)) && (!empty($depw))) || ((empty($depm)) && (!empty($depw))) || (empty($depw) && !empty($depm)) ) {
             if ($depw != $depm && ($depm != 'None')) {
                  $depError = 'Working And Managing Dept. should be same';
                  $valid = false;
             }
        }
        if(!empty($depm) && ($depm != 'None')) {
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $query = "Select * from Physician where ManagesDep='$depm'";
            $result = mysqli_query($dbc, $query);
            if(mysqli_num_rows($result) >= 1) {
                $depError = 'A person already manages the Dept.';
                $valid = false;
            }
            mysqli_close($dbc);
         }
                 
        // update data
        if ($valid) {
         $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
         $query = "UPDATE Persons SET Fname='$fname', Mname='$mname', Lname='$lname', Address='$address', Gender='$gender', Phone='$phone', DOB='$dob', SSN='$aadhar' WHERE Id='$id'";
         mysqli_query($dbc, $query);
         $query = "UPDATE Employee SET DateHired='$dh' WHERE PId='$id'";
         mysqli_query($dbc, $query);
         $query = "Select * from Employee where PId='$id'";
         $result = mysqli_query($dbc, $query);
         $row = mysqli_fetch_array($result);
         $x = $row['Id'];
         $query = "UPDATE Physician SET ManagesDep = '$depm', WorksForDep = '$depw', Speciality = '$spec', Degree = '$deg' where EId = '$x'";
         mysqli_query($dbc, $query);
         mysqli_close($dbc);
         header("Location: myemployees.php");
      }
    } else {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * from Persons where Id='$id'";
        $result = mysqli_query($dbc, $query);
        $data = mysqli_fetch_array($result);
        $query = "SELECT * from Employee where PId='$id'";
        $result = mysqli_query($dbc, $query);
        $data2 = mysqli_fetch_array($result);
        $fname = $data['Fname'];
        $mname = $data['Mname'];
        $lname = $data['Lname'];
        $address = $data['Address'];
        $phone = $data['Phone'];
        $gender = $data['Gender'];
        $dob = $data['DOB'];
        $dh = $data2['DateHired'];
        $aadhar = $data['SSN'];
        $y = $data2['Id'];
        $query = "SELECT * from Physician where EId='$y'";
        $result2 = mysqli_query($dbc, $query);
        $data3 = mysqli_fetch_array($result2);
        $depm = $data3['ManagesDep'];
        $depw = $data3['WorksForDep'];
        $spec = $data3['Speciality'];
        $deg = $data3['Degree'];
        if($gender == 'Male') {
        $genm = 'checked="checked"';
        $genf = '';
        }
        else {
        $genm = '';
        $genf = 'checked="checked"';
        }
        $query = "Select * from Department where Id='$depw'";
        $disp = mysqli_query($dbc, $query);
        $disp = mysqli_fetch_array($disp);
        $rrr =  (!empty($disp)?$disp['Id'].':'.$disp['Name']:'');

        if($depm != 'None') {
            $check = 1;
        }
         else {
            $check = 2;
         }
                   
         
        mysqli_close($dbc);
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <link href="css/datepicker.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">
<?php include_once('header.php'); ?>

        <div id="page-wrapper">

            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Tables
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="admin_index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-table"></i> Manage Database
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

  
     <div class="container" style="display:none;">
     
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Update Physician Info.</h3>
                    </div>
             
                    <form class="form-horizontal" action="myupdatephys.php?id=<?php echo $id?>" method="post">
                     <div class="control-group <?php echo !empty($nameError)?'error':'';?>">
                        <label class="control-label">First Name</label>
                        <div class="controls">
                            <input name="fname" type="text" class="form-control" placeholder="First Name" value="<?php echo !empty($fname)?$fname:'';?>">
                            <?php if (!empty($nameError)): ?>
                                <span class="help-block" style="color:red;"><?php echo $nameError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                       <div class="control-group">
                        <label class="control-label">Middle Name</label>
                        <div class="controls">
                            <input name="mname" type="text" class="form-control" placeholder="Middle Name" value="<?php echo !empty($lname)?$mname:'';?>">
                            
                        </div>
                      </div>
                       <div class="control-group">
                        <label class="control-label">Last Name</label>
                        <div class="controls">
                            <input name="lname" type="text"   class="form-control" placeholder="Last Name" value="<?php echo !empty($lname)?$lname:'';?>">
                        </div>
                      </div>
                     

                      <div class="control-group <?php echo !empty($addError)?'error':'';?>">
                        <label class="control-label">Address</label>
                        <div class="controls">
                            <input name="address" type="text"  class="form-control" placeholder="Address" value="<?php echo !empty($address)?$address:'';?>">
                            <?php if (!empty($addError)): ?>
                                <span class="help-block" style="color:red;"><?php echo $addError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($phoneError)?'error':'';?>">
                        <label class="control-label">Phone Number</label>
                        <div class="controls">
                            <input name="phone" type="text" class="form-control" placeholder="Phone Number" value="<?php echo !empty($phone)?$phone:'';?>">
                            <?php if (!empty($phoneError)): ?>
                                <span class="help-block" style="color:red;"><?php echo $phoneError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Gender</label>
                        <div class="controls">
                            <input name="gender" type="radio" value="Male" <?php echo $genm;?>>Male
                           <input name="gender" type="radio" value="Female" <?php echo $genf;?>>Female
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($aadharError)?'error':'';?>">
                        <label class="control-label">Aadhar No.</label>
                        <div class="controls">
                            <input name="aadhar" type="text" class="form-control" placeholder="Aadhar Card No." value="<?php echo !empty($aadhar)?$aadhar:'';?>">
                            <?php if (!empty($aadharError)): ?>
                                <span class="help-block" style="color:red;"><?php echo $aadharError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($dobError)?'error':'';?>">
                       <label class="control-label">Date Of Birth</label>
                       <div class="controls">
                          <input  type="text" name="dob" class="form-control" placeholder="Click To Select Date"  id="example1" value="<?php echo !empty($dob)?$dob:'';?>">
                       </div>
                     </div>
                     <div class="control-group <?php echo !empty($dhError)?'error':'';?>">
                       <label class="control-label">Date Of Hiring</label>
                       <div class="controls">
                          <input  type="text" name="dh" class="form-control" placeholder="Click To Select Date"  id="example2" value="<?php echo !empty($dh)?$dh:'';?>">
                       </div>
                     </div>
                     <div class="control-group <?php echo !empty($depError)?'error':'';?>">
                        <label class="control-label">Department Manages</label>
                        <div class="controls">
                           <!-- <input name="depm" type="text"  placeholder="Dep. Manages" value="<?php //echo !empty($depm)?$depm:'';?>"> -->
                            <?php if (!empty($depError)): ?>
                                <span class="help-block" style="color:red;"><?php echo $depError;?></span>
                            <?php endif;?>
    
                       <select class="form-control" name="depm">
                         <?php
                              $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                              $query = "Select * from Department";
                              $getit = mysqli_query($dbc, $query);
                              if($check == 1) {
                              while($row = mysqli_fetch_array($getit)) {
                                  if($rrr != '') {
                                      if($row['Id'] == $disp['Id']) {
                                          echo '<option selected="selected">'.$rrr.'</option>';
                                      }
                                      else {
                                         echo '<option>'.$row['Id'].':'.$row['Name'].'</option>';
                                      }
                                  }
                                  else {
                                      echo '<option>'.$row['Id'].':'.$row['Name'].'</option>';
                                  }
                              }
                            }
                            else {
                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                              $query = "Select * from Department";
                               $getit = mysqli_query($dbc, $query);
                              echo '<option selected="selected">None</option>';
                                while($row = mysqli_fetch_array($getit)) {
                                      echo '<option>'.$row['Id'].':'.$row['Name'].'</option>';
                                }
                             }
                           ?>
                         </select>

                        </div>
                      </div>
                       <div class="control-group <?php echo !empty($depError)?'error':'';?>">
                        <label class="control-label">Department Works For</label>
                        <div class="controls">
                          <!-- <input name="depw" type="text"  placeholder="Dep. Works For" value="<?php //echo !empty($depw)?$depw:'';?>"> -->
                          
                    
                         <?php if (!empty($depError)): ?>
                                <span class="help-block" style="color:red;"><?php echo $depError;?></span>
                            <?php endif;?>
   

                        <select class="form-control" name="depw">
                        <?php
                             $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                             $query = "Select * from Department";
                             $getit = mysqli_query($dbc, $query);
                             while($row = mysqli_fetch_array($getit)) {
                                 if($rrr != '') {
                                     if($row['Id'] == $disp['Id']) {
                                         echo '<option selected="selected">'.$rrr.'</option>';
                                     }
                                     else {
                                         echo '<option>'.$row['Id'].':'.$row['Name'].'</option>';
                                     }
                                 }
                                 else {
                                     echo '<option>'.$row['Id'].':'.$row['Name'].'</option>';
                                 }
                             }
                          ?>
                              </select>

                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Degree</label>
                        <div class="controls">
                            <input name="deg" type="text"  class="form-control" placeholder="Degree" value="<?php echo !empty($deg)?$deg:'';?>">
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Speciality</label>
                        <div class="controls">
                            <input name="spec" type="text"  class="form-control" placeholder="Speciality" value="<?php echo !empty($spec)?$spec:'';?>">
                        </div>
                      </div>




                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Update</button>
                          <a class="btn" href="myemployees.php">Back To Employees</a>
                        </div>
                    </form>
                </div>
                 
    </div>


                 </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <script src="js/bootstrap-datepicker.js"></script>
    <script>
$(document).ready(function(){
//   if ($.browser.webkit) {
//     $('input[name="password"]').attr('autocomplete', 'off');
//     $('input[name="username"]').attr('autocomplete', 'off');
// }
    // to fade in on page load
    // $(".entire").css("display", "none");
     $('#example1').datepicker({
                    format: "yyyy-mm-dd"
                });  
                $('#example2').datepicker({
                   format: "yyyy-mm-dd"
                });
            

    $(".container").toggle("slide"); 
     });
</script>
</body>

</html>
<?php
 }
}
?>
