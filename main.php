<?php
require_once 'includes/config.php';

if (isLoggedIn()) {
    redirectByRole();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'resident';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ? AND status = 'active' LIMIT 1");
        $stmt->bind_param('ss', $email, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['role'];
            redirectByRole();
        } else {
            $error = 'Invalid email or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SmartWaste Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            margin:0;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px;
            background:
                linear-gradient(rgba(1, 29, 1, 0.62), rgba(55, 187, 55, 0.55)),
                url('/waste_management/new.png');
            background-size:cover;
            background-position:center;
            background-repeat:no-repeat; 
            background-attachment:fixed;
        }

        .login-wrapper {
            display: flex;
            width: 900px;
            max-width: 100%;
            background: rgba(94, 184, 89, 0.47);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
        }

        .login-left {
            flex: 1;
            background: rgba(4, 36, 9, 0.57);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            top: -80px; right: -80px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            bottom: -50px; left: -50px;
        }

        .login-left .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .login-left .brand-icon {
            width: 50px; height: 50px;
            background: #f0a500;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
        }

        .login-left h2 {
            color: #fff;
            font-weight: 700;
        }

        .login-left p {
            color: rgba(255,255,255,0.7);
            font-size: 14px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .feature-item .icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }

        .feature-item p {
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            margin: 0;
        }

        .feature-item strong {
            color: #fff;
            font-size: 14px;
            display: block;
        }

        .login-right {
            width: 380px;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right h3 {
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .login-right p {
            color: #37383a;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: #374151;
        }

        .form-control {
            border-radius: 10px;
            border-color: #e5e7eb;
            padding: 12px 14px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #063920;
            box-shadow: 0 0 0 3px rgba(26,122,76,0.1);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            border-color: #e5e7eb;
            background: #f9fafb;
            color: #6b7280;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .btn-login {
            background: linear-gradient(135deg, #01361d, #218f52);
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-weight: 600;
            font-size: 15px;
            color: #fff;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgb(16, 72, 46);
        }
        
        .register-link{
            color: #014423;
            font-weight:1000;
            text-decoration:none;
            transition:0.3s;
        }
        
        .demo-accounts {
            margin-top: 24px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .demo-accounts p {
            font-size: 12px;
            color: #6b7280;
            margin: 0 0 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .demo-btn {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #374151;
            margin: 2px;
        }

        .demo-btn:hover { background: #03150c; color: #fff; border-color: #1a7a4c; }

        @media (max-width: 700px) {
            .login-left { display: none; }
            .login-right { width: 100%; }
        }
    
.role-selector{display:flex;gap:10px;margin-bottom:20px}
.role-card{flex:1;text-align:center;padding:12px;border:2px solid #e5e7eb;border-radius:10px;cursor:pointer;font-weight:600}
.role-card.active{background:linear-gradient(135deg, #06341e, #218f52);color:#fff;border-color: #011a0e}

</style>
</head>
<body>
<div class="login-wrapper">
    <!-- Left Panel -->
    <div class="login-left">
        <div>
            <div class="brand mb-4">
                <div class="brand-icon" style="width:60px;height:65px;display:flex;align-items:center;justify-content:center;background:yellow;">
    <img src="/waste_management/logo.png"
         alt="SmartWaste Logo"
         style="width:100px;height:100px;object-fit:contain;border-radius:50%;">
</div>
                <div>
                    <h5 style="color:#fff;margin:0;font-weight:700">SmartWaste</h5>
                    <small style="color:rgba(255,255,255,0.6)">Management System</small>
                </div>
            </div>
            <h2 class="mb-3">Smart City Waste<br>Collection Platform</h2>
            <p>A comprehensive system to manage waste collection routes, bins, vehicles, and citizen complaints efficiently.</p>
        </div>

        <div>
            <div class="feature-item">
                <div class="icon">🗺️</div>
                <div>
                    <strong>Smart Route Management</strong>
                    <p>Optimized collection routes for efficiency</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="icon">📊</div>
                <div>
                    <strong>Real-time Monitoring</strong>
                    <p>Track bin fill levels and truck locations</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="icon">📢</div>
                <div>
                    <strong>Citizen Complaints</strong>
                    <p>Instant complaint tracking & resolution</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="icon">📈</div>
                <div>
                    <strong>Analytics & Reports</strong>
                    <p>Monthly waste collection reports</p>
                </div>
            </div>
        </div>

        <div>
            <small style="color:rgba(255,255,255,0.4)">Smart City Initiative • Domain: Waste Management</small>
        </div>
    </div>

    <!-- Right Panel (Login Form) -->
    <div class="login-right">
        <h3>Welcome Back 👋</h3>
        <p>Sign in to your SmartWaste account</p>

        <?php if ($error): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="border-radius:10px;font-size:14px">
            <i class="bi bi-exclamation-circle-fill"></i> <?= $error ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            
            <div class="mb-3">
                <label class="form-label">I am a</label>
                <div class="role-selector">
                    <label class="role-card active"><input type="radio" name="role" value="resident" checked hidden>Resident</label>
                    <label class="role-card"><input type="radio" name="role" value="collector" hidden>Collector</label>
                    <label class="role-card"><input type="radio" name="role" value="admin" hidden>Admin</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Enter your password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()" style="border-radius:0 10px 10px 0;border-color:#e5e7eb">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-shield-check me-2"></i>Sign In Securely
            </button>
            <div class="text-center mt-3" id="registerLink"><small>New here? <a href="register.php" class="register-link">Create a New Account</a></small></div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
document.querySelectorAll(".role-card").forEach(c=>c.onclick=function(){document.querySelectorAll(".role-card").forEach(x=>x.classList.remove("active"));this.classList.add("active");this.querySelector("input").checked=true;});

</script>
<script>

document.querySelectorAll(".role-card").forEach(c=>c.onclick=function(){
document.querySelectorAll(".role-card").forEach(x=>x.classList.remove("active"));
this.classList.add("active");
this.querySelector("input").checked=true;
const reg=document.getElementById('registerLink');
reg.style.display=this.querySelector('input').value==='resident'?'block':'none';
});
document.addEventListener('DOMContentLoaded',()=>{
 const checked=document.querySelector('.role-card input:checked');
 document.getElementById('registerLink').style.display=checked&&checked.value==='resident'?'block':'none';
});

function fillLogin(email) {
    document.querySelector('[name=email]').value = email;
    document.querySelector('[name=password]').value = 'password';
}

function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

document.querySelectorAll(".role-card").forEach(c=>c.onclick=function(){document.querySelectorAll(".role-card").forEach(x=>x.classList.remove("active"));this.classList.add("active");this.querySelector("input").checked=true;});

</script>
</body>
</html>
