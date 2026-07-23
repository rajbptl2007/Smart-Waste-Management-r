<?php
require_once 'includes/config.php';
$msg='';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
$email=trim($_POST['email']??'');
$p=$_POST['password']??'';$c=$_POST['confirm_password']??'';
if($p!==$c){$err='Passwords do not match.';}
else{
$stmt=$conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$stmt->bind_param('s',$email);$stmt->execute();$r=$stmt->get_result();
if($u=$r->fetch_assoc()){
$hash=password_hash($p,PASSWORD_DEFAULT);
$up=$conn->prepare("UPDATE users SET password=? WHERE id=?");
$up->bind_param('si',$hash,$u['id']);$up->execute();
$msg='Password reset successfully. <a href="main.php">Login</a>';
}else{$err='Email not found.';}
}}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reset Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;
background:linear-gradient(rgba(3,40,12,.65),rgba(24,120,54,.55)),url('/waste_management/log.png') center/cover fixed;font-family:Arial,sans-serif}
.card-box{width:100%;max-width:430px;background:rgba(255,255,255,.15);backdrop-filter:blur(18px);padding:35px;border-radius:20px;box-shadow:0 20px 40px rgba(0,0,0,.35)}
h2{color:#fff;text-align:center;font-weight:700;margin-bottom:8px}
p{color:#eaf7ec;text-align:center;margin-bottom:25px}
.form-control{border-radius:12px;padding:12px}
.btn-success{background:#0a5c2d;border:none;border-radius:12px;padding:12px;font-weight:600}
.btn-success:hover{background:#0d7a3b}
.back{text-align:center;margin-top:15px}
.back a{text-decoration:none;color:#fff;font-size:14px}
label{color:#fff;font-weight:600}
</style>
</head>
<body>
<div class="card-box">
<h2><i class="bi bi-shield-lock-fill"></i> Reset Password</h2>
<p>Enter your registered email and create a new password.</p>
<?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; if($err) echo "<div class='alert alert-danger'>$err</div>";?>
<form method="post">
<label>Email Address</label>
<input class="form-control mb-3" name="email" type="email" placeholder="Registered Email" required>
<label>New Password</label>
<input class="form-control mb-3" name="password" type="password" placeholder="New Password" required>
<label>Confirm Password</label>
<input class="form-control mb-3" name="confirm_password" type="password" placeholder="Confirm Password" required>
<button class="btn btn-success w-100"><i class="bi bi-arrow-repeat"></i> Reset Password</button>
</form>
<div class="back"><a href="main.php"><i class="bi bi-arrow-left"></i> Back to Login</a></div>
</div>
</body></html>
