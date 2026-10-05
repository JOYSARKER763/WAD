<?php
session_start();
$db=new PDO('mysql:host=localhost;dbname=campusq;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
function q($s,$p=[]){global $db;$r=$db->prepare($s);$r->execute($p);return $r;}
function h($s){return htmlspecialchars((string)$s);}
function need($role){if(empty($_SESSION['u'])||$_SESSION['u']['role']!==$role){header('Location: login.php');exit;}}
function badge($s){$c=['waiting'=>'warning','serving'=>'success','done'=>'secondary','cancelled'=>'danger','skipped'=>'dark'];return "<span class='badge bg-{$c[$s]}'>".h($s)."</span>";}
function top($refresh=false){$u=$_SESSION['u']??null;$r=$u['role']??'';
echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>CampusQ</title>'.($refresh?'<meta http-equiv="refresh" content="10">':'').'<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><nav class="navbar navbar-dark bg-primary mb-4"><div class="container"><a class="navbar-brand fw-bold" href="index.php">🎓 CampusQ</a><div>';
if($u){echo "<span class='text-white me-3'>".h($u['name'])." ($r)</span><a class='btn btn-light btn-sm me-2' href='$r.php'>Dashboard</a><a class='btn btn-outline-light btn-sm' href='logout.php'>Logout</a>";}
else echo '<a class="btn btn-light btn-sm me-2" href="login.php">Login</a><a class="btn btn-outline-light btn-sm" href="register.php">Register</a>';
echo '</div></div></nav><div class="container">';}
function bottom(){echo '</div><footer class="text-center text-muted my-5 small">CampusQ — Join the Queue, Not the Line.</footer></body></html>';}
