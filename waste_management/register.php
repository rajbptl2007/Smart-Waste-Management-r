<?php
require_once 'includes/config.php';
$error='';$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
$name=sanitize($_POST['full_name']??'');
$email=sanitize($_POST['email']??'');
$pass=$_POST['password']??'';
$cpass=$_POST['confirm_password']??'';
if(!$name||!$email||!$pass){$error='All fields are required.';}
elseif($pass!==$cpass){$error='Passwords do not match.';}
else{
$st=$conn->prepare("SELECT id FROM users WHERE email=?");
$st->bind_param('s',$email);$st->execute();$r=$st->get_result();
if($r->num_rows){$error='Email already registered.';}
else{
$hash=password_hash($pass,PASSWORD_DEFAULT);
$role='resident';$status='active';
$st=$conn->prepare("INSERT INTO users(full_name,email,password,role,status) VALUES(?,?,?,?,?)");
$st->bind_param('sssss',$name,$email,$hash,$role,$status);
if($st->execute()){header('Location:index.php?registered=1');exit();}
else{$error='Registration failed: '.$conn->error;}
}}}
?>
<!doctype html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Resident Registration</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(rgba(1, 36, 1, 0.58), rgba(55, 187, 55, 0.55)),url('/waste_management/log.png') center/cover fixed;font-family:Segoe UI,sans-serif}
.wrap{max-width:950px;width:100%;display:flex;background:rgba(13, 209, 72, 0.26);backdrop-filter:blur(10px);border-radius:24px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.35)}
.left{flex:1;background:rgba(5, 57, 13, 0.57);color:#fff;padding:45px}.right{width:420px;background: rgba(94, 184, 89, 0.47);;padding:40px}.logo{width:70px;height:70px}.form-control,.btn{border-radius:12px;padding:12px}
</style></head><body><div class="wrap"><div class="left">                <div class="brand-icon" style="width:60px;height:65px;display:flex;align-items:center;justify-content:center;background:yellow;">
    <img src="/waste_management/logo.png"
         alt="SmartWaste Logo"
         style="width:100px;height:100px;object-fit:contain;border-radius:50%;">
</div>
    <h2 class="mt-4">Join SmartWaste</h2><p>Create your resident account and access smart waste services.</p><p>♻️ Complaint Tracking</p><p>📍 Smart Bin Locations</p><p>🚛 Collection Updates</p><p>📊 Reports & Notifications</p></div><div class="right"><h2 class="fw-bold mb-4">Resident Registration</h2><?php if($error):?><div class="alert alert-danger"><?=$error?></div><?php endif;?><form method="post"><input class="form-control mb-3" name="full_name" placeholder="Full Name" required><input type="email" class="form-control mb-3" name="email" placeholder="Email" required><input type="password" class="form-control mb-3" name="password" placeholder="Password" required><input type="password" class="form-control mb-3" name="confirm_password" placeholder="Confirm Password" required><button class="btn btn-success w-100">Create Account</button><a href="index.php" class="btn btn-outline-success w-100 mt-3">Back to Login</a></form></div></div></body></html>