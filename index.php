<?php require 'inc.php'; top(); $s=q("SELECT * FROM services")->fetchAll(); ?>
<div class="text-center py-5"><h1 class="display-5 fw-bold">CampusQ</h1><p class="lead">Smart University Queue Management and Service System</p><p class="text-muted">"Join the Queue, Not the Line."</p><a href="register.php" class="btn btn-primary btn-lg">Get Started</a></div>
<h4>Services</h4><div class="row"><?php foreach($s as $x): ?><div class="col-md-3 mb-3"><div class="card h-100 shadow-sm"><div class="card-body"><h6><?=h($x['department'])?></h6><p class="mb-1"><?=h($x['name'])?></p><small class="text-muted">~<?=$x['avg_min']?> min/person</small></div></div></div><?php endforeach; ?></div>
<h4 class="mt-4">About</h4><p>Students take an online token, track their position live, and staff call students from a dashboard. Admins monitor statistics and manage services.</p>
<?php bottom();
