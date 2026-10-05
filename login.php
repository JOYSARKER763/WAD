<?php require 'inc.php'; $e='';
if($_POST){$u=q("SELECT * FROM users WHERE email=?",[$_POST['email']])->fetch();
 if($u&&password_verify($_POST['password'],$u['password'])){session_regenerate_id(true);$_SESSION['u']=$u;header("Location: {$u['role']}.php");exit;}
 $e='Email বা password ভুল';}
top(); ?>
<div class="col-md-5 mx-auto card card-body"><h4>Login</h4><?php if($e)echo "<div class='alert alert-danger'>$e</div>"; ?>
<form method="post"><input class="form-control mb-2" type="email" name="email" placeholder="Email" required><input class="form-control mb-3" type="password" name="password" placeholder="Password" required><button class="btn btn-primary w-100">Login</button></form>
<small class="text-muted mt-3">Demo: admin@campusq.test / staff@campusq.test / joy@campusq.test — password: <b>password</b></small></div>
<?php bottom();
