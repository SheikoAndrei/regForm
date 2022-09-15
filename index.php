<?php
error_reporting(1);
$time1 = microtime(true);
$date_time = date("Y-m-d H:i:s");

if (strpos($_SERVER['SCRIPT_FILENAME'],'/')===0 || strpos($_SERVER['SCRIPT_FILENAME'],'/')>=1) { define('DS', '/'); } else { define('DS', '\\'); }
$path_arr = explode(DS,$_SERVER['SCRIPT_FILENAME']);
$sfn = array_pop($path_arr);
define('HOST_URL', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']);
define('SITE_URL', HOST_URL . str_replace($sfn,'',$_SERVER['SCRIPT_NAME']));
define('SITE_PATH', join(DS,$path_arr));
define('LOG_URL', SITE_URL.'x-log');
define('LOG_PATH', SITE_PATH.DS.'x-log');
define('DB_URL', SITE_URL.'x-db');
define('DB_PATH', SITE_PATH.DS.'x-db');
if (!is_dir(LOG_PATH)) { mkdir(LOG_PATH); }
if (!is_dir(DB_PATH)) { mkdir(DB_PATH); }

if ($_POST['id']=='ok')
	{
	sleep(2);
	$db = json_decode(file_get_contents(DB_PATH.DS.'users.json'),true);
	$p = $_POST;
	$json = array(
		'ans'=>'',
		'class_alert'=>'',
		'view_text'=>array()
		);
	if (!filter_var($p['email'], FILTER_VALIDATE_EMAIL))
		{
		$msg = 'email error';
		$json['view_text'][] = '<p>- The email address is not correct.</p>';
		}
	if ($p['password']!=$p['passwordr'])
		{
		$msg = 'pass error';
		$json['view_text'][] = '<p>- The second password has not passed the test for identity.</p>';
		}
	if ($db[$p['email']])
		{
		$msg = 'email duble';
		$json['view_text'][] = '<p>- This email has already been registered.</p>';
		}
	if (count($json['view_text'])>=1)
		{
		$json['ans'] = 'warning';
		$json['class_alert'] = 'alert alert-warning';
		array_unshift($json['view_text'],'<h5>WARNING!</h5>');
		}
	else
		{
		$msg = 'user add';
		$p['time_change'] = $p['time_add'] = $date_time;
		$p['id'] = number_format(count($db)+1,0,'','');
		$p['password'] = md5($p['password']);
		$db[$p['email']] = $p;
		file_put_contents(DB_PATH.DS.'users.json',json_encode($db,JSON_UNESCAPED_UNICODE));
		$json['ans'] = 'useradd';
		$json['class_alert'] = 'alert alert-success';
		$json['view_text'] = array(
			'<h5>CONGRATULATIONS!</h5>',
			'<p>- The new user is added.</p>'
			);
		}
	addLog($time1,$date_time,$p,$msg);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($json);
	die;
	}

?>
<!doctype html>
<html lang="ru">
	<head>
		<title>Test</title>
		<meta name="description" content="">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="theme-color" content="#999999">
		<link rel="apple-touch-icon" href="tmpl/img/favicon.png" sizes="64x64">
		<link rel="icon" href="tmpl/img/favicon.png" sizes="64x64" type="image/png">
		<link rel="icon" href="tmpl/img/favicon.ico">
		<link href="tmpl/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
		<link href="tmpl/css/bootstrap.add.css" rel="stylesheet" crossorigin="anonymous">
	</head>
	<body class="d-flex flex-column h-100">
	
		<header>
			<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
				<div class="container">
					<a href="<?php echo SITE_URL; ?>" class="navbar-brand">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-square" viewBox="0 0 20 20"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12z"/></svg>
						<strong>Test</strong>
					</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
					<div class="collapse navbar-collapse" id="navbarCollapse">
						<ul class="navbar-nav me-auto mb-2 mb-md-0">
							<li class="nav-item">
								<a class="nav-link" aria-current="page" href="https://www.notion.so/Backend-f863a6666e9f40f99f41254a1fffe450" target="_blank">Task</a>
							</li>
						</ul>
						<div class="d-flex">
						<button class="btn btn-light" type="button" data-bs-toggle="modal" data-bs-target="#modalform">Sign in</button>
						</div>
					</div>
				</div>
			</nav>
		</header>

		<main class="flex-shrink-0 mt-3">
			<div class="container col-lg-3 mx-auto">
				<h1>Marcus Tullius Cicero</h1>
				<img class="rounded mx-auto d-block my-4" alt="Marcus Tullius Cicero" src="//upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Bust_of_Cicero_%281st-cent._BC%29_-_Palazzo_Nuovo_-_Musei_Capitolini_-_Rome_2016.jpg/200px-Bust_of_Cicero_%281st-cent._BC%29_-_Palazzo_Nuovo_-_Musei_Capitolini_-_Rome_2016.jpg" decoding="async" width="200" height="300" srcset="//upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Bust_of_Cicero_%281st-cent._BC%29_-_Palazzo_Nuovo_-_Musei_Capitolini_-_Rome_2016.jpg/300px-Bust_of_Cicero_%281st-cent._BC%29_-_Palazzo_Nuovo_-_Musei_Capitolini_-_Rome_2016.jpg 1.5x, //upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Bust_of_Cicero_%281st-cent._BC%29_-_Palazzo_Nuovo_-_Musei_Capitolini_-_Rome_2016.jpg/400px-Bust_of_Cicero_%281st-cent._BC%29_-_Palazzo_Nuovo_-_Musei_Capitolini_-_Rome_2016.jpg 2x">
				<div class="mb-4 text-center"><button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalform">Sign in</button></div>
				<p>Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est, qui do<b>lorem ipsum</b>, quia <b>dolor sit, amet, consectetur, adipisci</b> v<b>elit, sed</b> quia non numquam <b>eius mod</b>i <b>tempor</b>a <b>incidunt, ut labore et dolore magna</b>m <b>aliqua</b>m quaerat voluptatem. <b>Ut enim ad minim</b>a <b>veniam, quis nostru</b>m <b>exercitation</b>em <b>ullam co</b>rporis suscipit<b> labori</b>o<b>s</b>am, <b>nisi ut aliquid ex ea commod</b>i <b>consequat</b>ur? <b>Quis aute</b>m vel eum <b>iure reprehenderit,</b> qui <b>in</b> ea <b>voluptate velit esse</b>, quam nihil molestiae <b>c</b>onsequatur, vel <b>illum</b>, qui <b>dolore</b>m <b>eu</b>m <b>fugiat</b>, quo voluptas <b>nulla pariatur</b>? At vero eos et accusamus et iusto odio dignissimos ducimus, qui blanditiis praesentium voluptatum deleniti atque corrupti, quos dolores et quas molestias <b>exceptur</b>i <b>sint, obcaecat</b>i <b>cupiditat</b>e <b>non pro</b>v<b>ident</b>, similique <b>sunt in culpa</b>, <b>qui officia deserunt mollit</b>ia <b>anim</b>i, <b>id est laborum</b> et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet, ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>
				<div class="text-end mt-2">
					<pre>load: <?php echo number_format(microtime(true)-$time1,6,'.',''); ?> s.</pre>
				</div>
			</div>
		</main>

		<div class="modal fade" id="modalform" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<form id="form" class="needs-validation" target="#" method="POST" novalidate>
						<input type="hidden" name="id" value="ok">
						<div class="modal-header">
							<h5 class="modal-title">
								<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/></svg>
								Please sign in
							</h5>
							<button type="button" class="btn-close" id="tclose" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="input-group mb-3">
								<input type="text" class="form-control" name="name" id="name" placeholder="Name" aria-label="Name" aria-describedby="name" required>
								<span class="input-group-text">Name</span>
								<div class="invalid-feedback">Please provide a valid Name.</div>
							</div>
							<div class="input-group mb-3">
								<input type="text" class="form-control" name="surname" id="surname" placeholder="Surname" aria-label="Surname" aria-describedby="surname" required>
								<span class="input-group-text">Surname</span>
								<div class="invalid-feedback">Please provide a valid Surname.</div>
							</div>
							<div class="input-group mb-3">
								<input type="email" class="form-control" name="email" id="email" placeholder="Email" aria-label="Email" aria-describedby="email" required>
								<span class="input-group-text">Email</span>
								<div class="invalid-feedback">Please provide a valid Email.</div>
							</div>
							<div class="input-group mb-3">
								<input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-label="Password" aria-describedby="Password" required>
								<span class="input-group-text">Password</span>
								<div class="invalid-feedback">Please provide a valid Password.</div>
							</div>
							<div class="input-group mb-3">
								<input type="password" class="form-control" name="passwordr" id="passwordr" placeholder="Repeat Password" aria-label="Repeat Password" aria-describedby="Repeat Password" required>
								<span class="input-group-text">Repeat Password</span>
								<div class="invalid-feedback">Please provide a valid Repeat Password.</div>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="password_view">
								<label class="form-check-label" for="password_view">View Passwords</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="email_required">
								<label class="form-check-label" for="email_required">Check the email of PHP</label>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" id="dclose" data-bs-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary" id="signin">Sign in</button>
						</div>
					</form>
					<div class="align-self-center rounded" id="roller">
						<div id="roller_box" class="align-middle text-center">
							<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
						</div>
					</div>
					<div class="align-self-center rounded" id="message">
						<div id="message_box" class="align-middle text-center" role="alert"></div>
					</div>
				</div>
			</div>
		</div>

		<script src="tmpl/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="tmpl/js/bootstrap.add.js" crossorigin="anonymous"></script>

	</body>
</html>
<?

function addLog($time1,$date_time,$p,$msg)
	{
	unset($p['password']);
	unset($p['passwordr']);
	file_put_contents(LOG_PATH.DS.'log.txt',$time1."\t".$date_time."\t".$msg."\t".json_encode($p,JSON_UNESCAPED_UNICODE)."\n",FILE_APPEND);
	}

?>