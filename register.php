<?php require 'inc.php'; $e='';
if($_POST){ if(strlen($_POST['password'])<6)$e='Password কমপক্ষে ৬ অক্ষর হতে হবে';
 elseif(q("SELECT id FROM users WHERE email=?",[$_POST['email']])->fetch())$e='Email আগে থেকেই আছে';
 else{q("INSERT INTO users(name,email,password) VALUES(?,?,?)",[trim($_POST['name']),trim($_POST['email']),password_hash($_POST['password'],PASSWORD_DEFAULT)]);header('Location: login.php');exit;}}
top(); ?>
<div class="col-md-5 mx-auto card card-body"><h4>Student Register</h4><?php if($e)echo "<div class='alert alert-danger'>".h($e)."</div>"; ?>
<form method="post"><input class="form-control mb-2" name="name" placeholder="Name" required><input class="form-control mb-2" type="email" name="email" placeholder="Email" required><input class="form-control mb-3" type="password" name="password" placeholder="Password" required><button class="btn btn-primary w-100">Register</button></form></div>
<?php bottom();
