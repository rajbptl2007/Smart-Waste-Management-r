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
body{
margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;
background:
linear-gradient(rgba(3,32,16,.72),rgba(54,160,82,.45)),
url('/waste_management/log.png') center/cover fixed;
font-family:'Segoe UI',sans-serif;
overflow:hidden}
.wrap{max-width:1100px;width:100%;display:flex;
background:rgba(255, 255, 255, 0.08);
backdrop-filter:blur(3px);
-webkit-backdrop-filter:blur(8px);
border:3px solid rgba(3,32,16,.72);
border-radius:32px;
overflow:hidden;
box-shadow:0 40px 100px rgba(0,0,0,.55)}
.left{flex:1;background:linear-gradient(160deg,#073a1b,#166534,#0b3f20);color:#fff;padding:55px;position:relative}.left:before{content:'';position:absolute;width:340px;height:340px;border-radius:50%;background:rgba(255,255,255,.08);top:-100px;right:-90px;filter:blur(8px)}.left:after{content:'';position:absolute;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.05);bottom:-70px;left:-60px;filter:blur(3px)}.right{width:430px;background:rgba(82,145,74,.5);padding:45px;display:flex;flex-direction:column;justify-content:center}.logo{width:70px;height:70px}.form-control{border-radius:16px;padding:15px;border:1px solid #dce5dc;background:rgba(255,255,255,.85)}.form-control:focus{border-color:#198754;box-shadow:0 0 0 .2rem rgba(25,135,84,.15)}.btn{border-radius:14px;padding:13px;font-weight:600}.btn-success{background:linear-gradient(135deg,#0b5d2c,#2fa84f);border:none}.btn-success:hover{transform:translateY(-3px) scale(1.01);box-shadow:0 18px 35px rgba(0,0,0,.25)}.btn-outline-success:hover{background:#198754;color:#fff}
</style></head><body><div class="wrap"><div class="left">
<div style="height:100%;display:flex;flex-direction:column;justify-content:center;max-width:430px;margin:auto;">
<div style="background:rgba(255,255,255,.10);display:inline-block;padding:8px 18px;border-radius:30px;color:#d8ffe2;font-size:13px;font-weight:600;margin:0 auto 18px;">🌿 Smart City Initiative</div>

<h2 style="font-size:48px;font-weight:800;line-height:1.1;text-align:center;color:#fff;margin-bottom:18px;">Join SmartWaste</h2>

<p style="text-align:center;color:rgba(255,255,255,.88);font-size:17px;line-height:1.7;margin-bottom:30px;">
Become part of a cleaner, greener future. Register now to report complaints, monitor waste collection and access smart city services.
</p>

<div style="display:flex;flex-direction:column;gap:12px;">
<div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);padding:16px 20px;border-radius:18px;">♻️ <strong>Smart Complaint Registration</strong></div>
<div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);padding:16px 20px;border-radius:18px;">📍 <strong>Live Complaint Tracking</strong></div>
<div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);padding:16px 20px;border-radius:18px;">🚛 <strong>Real-time Collection Updates</strong></div>
<div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);padding:16px 20px;border-radius:18px;">📊 <strong>Analytics & Notifications</strong></div>
<div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);padding:16px 20px;border-radius:18px;">🔒 <strong>Secure Resident Account</strong></div>
</div>

<div style="display:flex;align-items:center;gap:15px;margin:28px 0 18px;">
<div style="height:1px;background:rgba(255,255,255,.2);flex:1"></div>
<div style="color:#8DFF9B;font-size:22px;">🌿</div>
<div style="height:1px;background:rgba(255,255,255,.2);flex:1"></div>
</div>

<p style="text-align:center;color:rgba(255,255,255,.75);margin:0;">Building a cleaner and smarter city together.</p>
</div></div><div class="right"><h2 class="fw-bold mb-2">Create Your SmartWaste Account</h2><p class='text-muted mb-4'>Register to report waste complaints, track requests and help build a cleaner city.</p><?php if($error):?><div class="alert alert-danger"><?=$error?></div><?php endif;?><form method="post"><input class="form-control mb-3" name="full_name" placeholder="Full Name" required><input type="email" class="form-control mb-3" name="email" placeholder="Email" required><input type="password" class="form-control mb-3" name="password" placeholder="Password" required><input type="password" class="form-control mb-3" name="confirm_password" placeholder="Confirm Password" required><button class="btn btn-success w-100">Create Account</button><a href="index.php" class="btn btn-success w-100 mt-3">Back to Login</a></form></div></div></body></html>