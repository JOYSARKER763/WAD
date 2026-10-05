<?php require 'inc.php'; need('staff'); $sid=(int)$_SESSION['u']['service_id'];
if($_POST){$a=$_POST['a'];
 if($a=='call'&&!q("SELECT id FROM queue WHERE service_id=? AND qdate=CURDATE() AND status='serving'",[$sid])->fetch())
  q("UPDATE queue SET status='serving',served_at=NOW() WHERE id=(SELECT id FROM(SELECT id FROM queue WHERE service_id=? AND qdate=CURDATE() AND status='waiting' ORDER BY token_no LIMIT 1)t)",[$sid]);
 if($a=='done')q("UPDATE queue SET status='done' WHERE id=? AND service_id=?",[(int)$_POST['id'],$sid]);
 if($a=='skip')q("UPDATE queue SET status='skipped' WHERE id=? AND service_id=?",[(int)$_POST['id'],$sid]);
 header('Location: staff.php');exit;}
$sv=q("SELECT * FROM services WHERE id=?",[$sid])->fetch();
if(!$sv){top();echo "<div class='alert alert-warning'>আপনাকে কোনো service-এ assign করা হয়নি। Admin-কে বলুন।</div>";bottom();exit;}
$cur=q("SELECT q.*,u.name FROM queue q JOIN users u ON u.id=q.user_id WHERE q.service_id=? AND q.qdate=CURDATE() AND q.status='serving'",[$sid])->fetch();
$f=$_GET['f']??'';$s=trim($_GET['s']??'');
$sql="SELECT q.*,u.name FROM queue q JOIN users u ON u.id=q.user_id WHERE q.service_id=? AND q.qdate=CURDATE()";$p=[$sid];
if(in_array($f,['waiting','serving','done','cancelled','skipped'])){$sql.=" AND q.status=?";$p[]=$f;}
if($s!==''){$sql.=" AND (q.token_no=? OR u.name LIKE ?)";$p[]=(int)preg_replace('/\D/','',$s);$p[]="%$s%";}
$rows=q($sql." ORDER BY q.token_no",$p)->fetchAll();
top(true); ?>
<h4><?=h($sv['department'])?> — <?=h($sv['name'])?></h4>
<div class="card card-body mb-4 text-center"><small>Now Serving</small><h1><?=$cur?$sv['code'].'-'.$cur['token_no']:'—'?></h1><?php if($cur):?><p><?=h($cur['name'])?></p><form method="post" class="d-inline"><input type="hidden" name="id" value="<?=$cur['id']?>"><button name="a" value="done" class="btn btn-success">Complete</button> <button name="a" value="skip" class="btn btn-dark">Skip</button></form><?php else:?><form method="post"><button name="a" value="call" class="btn btn-primary btn-lg">Call Next</button></form><?php endif;?></div>
<form class="row g-2 mb-3"><div class="col-md-4"><input class="form-control" name="s" placeholder="Search token / name" value="<?=h($s)?>"></div><div class="col-md-3"><select class="form-select" name="f"><option value="">All</option><?php foreach(['waiting','serving','done','cancelled','skipped'] as $o)echo "<option ".($f==$o?'selected':'').">$o</option>";?></select></div><div class="col"><button class="btn btn-outline-primary">Filter</button></div></form>
<table class="table table-sm bg-white"><tr><th>Token</th><th>Student</th><th>Time</th><th>Status</th></tr><?php foreach($rows as $r):?><tr><td><?=$sv['code'].'-'.$r['token_no']?></td><td><?=h($r['name'])?></td><td><?=$r['created_at']?></td><td><?=badge($r['status'])?></td></tr><?php endforeach;?></table>
<?php bottom();
