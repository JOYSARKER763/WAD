<?php require 'inc.php'; need('student'); $uid=$_SESSION['u']['id'];
if($_POST){
 if(isset($_POST['take'])){$sv=(int)$_POST['take'];
  if(!q("SELECT id FROM queue WHERE user_id=? AND service_id=? AND status IN('waiting','serving')",[$uid,$sv])->fetch()){
   $n=q("SELECT COALESCE(MAX(token_no),0)+1 n FROM queue WHERE service_id=? AND qdate=CURDATE()",[$sv])->fetch()['n'];
   q("INSERT INTO queue(user_id,service_id,token_no,qdate) VALUES(?,?,?,CURDATE())",[$uid,$sv,$n]);}}
 if(isset($_POST['cancel']))q("UPDATE queue SET status='cancelled' WHERE id=? AND user_id=? AND status='waiting'",[(int)$_POST['cancel'],$uid]);
 header('Location: student.php');exit;}
$services=q("SELECT * FROM services")->fetchAll();
$active=q("SELECT q.*,s.code,s.name,s.department,s.avg_min FROM queue q JOIN services s ON s.id=q.service_id WHERE q.user_id=? AND q.qdate=CURDATE() AND q.status IN('waiting','serving')",[$uid])->fetchAll();
$hist=q("SELECT q.*,s.code,s.name FROM queue q JOIN services s ON s.id=q.service_id WHERE q.user_id=? ORDER BY q.id DESC LIMIT 20",[$uid])->fetchAll();
top(true); ?>
<h4>Welcome, <?=h($_SESSION['u']['name'])?> 👋</h4>
<h5 class="mt-4">My Queue</h5><?php if(!$active)echo "<p class='text-muted'>কোনো active token নেই।</p>";
foreach($active as $a){
 $cur=q("SELECT token_no FROM queue WHERE service_id=? AND qdate=CURDATE() AND status='serving'",[$a['service_id']])->fetch();
 $ahead=q("SELECT COUNT(*) c FROM queue WHERE service_id=? AND qdate=CURDATE() AND status='waiting' AND token_no<?",[$a['service_id'],$a['token_no']])->fetch()['c']+($cur&&$a['status']!='serving'?1:0);
 $st=$a['status']=='serving'?['🟢 YOUR TURN! কাউন্টারে যান','success']:($ahead<=1?['🟠 Almost Your Turn','warning']:['🟡 Waiting','info']); ?>
 <div class="card mb-3 border-<?=$st[1]?>"><div class="card-body"><div class="row text-center">
 <div class="col-md-3"><small>Your Token</small><h2><?=$a['code'].'-'.$a['token_no']?></h2><small><?=h($a['department'])?> — <?=h($a['name'])?></small></div>
 <div class="col-md-3"><small>Current Token</small><h2><?=$cur?$a['code'].'-'.$cur['token_no']:'—'?></h2></div>
 <div class="col-md-2"><small>People Ahead</small><h2><?=$a['status']=='serving'?0:$ahead?></h2></div>
 <div class="col-md-2"><small>Est. Wait</small><h2>~<?=$a['status']=='serving'?0:$ahead*$a['avg_min']?>m</h2></div>
 <div class="col-md-2"><span class="badge bg-<?=$st[1]?> fs-6"><?=$st[0]?></span><?php if($a['status']=='waiting'):?><form method="post" class="mt-2"><button name="cancel" value="<?=$a['id']?>" class="btn btn-sm btn-outline-danger">Cancel</button></form><?php endif;?></div>
 </div></div></div><?php } ?>
<h5 class="mt-4">Get a Token</h5><div class="row"><?php foreach($services as $s):?><div class="col-md-3 mb-3"><div class="card"><div class="card-body"><h6><?=h($s['department'])?></h6><p><?=h($s['name'])?></p><form method="post"><button name="take" value="<?=$s['id']?>" class="btn btn-primary btn-sm">Get Token</button></form></div></div></div><?php endforeach;?></div>
<h5 class="mt-4">History</h5><table class="table table-sm bg-white"><tr><th>Token</th><th>Service</th><th>Date</th><th>Status</th></tr><?php foreach($hist as $x):?><tr><td><?=$x['code'].'-'.$x['token_no']?></td><td><?=h($x['name'])?></td><td><?=$x['qdate']?></td><td><?=badge($x['status'])?></td></tr><?php endforeach;?></table>
<?php bottom();
