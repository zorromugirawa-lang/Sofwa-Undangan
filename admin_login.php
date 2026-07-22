<?php
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_index.php");
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sofwa Undangan</title>
    <link rel="icon" href="img/SF.png" type="image/png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #8b5e3c;
            --primary-light: #b5835a;
            --primary-dark: #6b4226;
            --secondary: #e6d3c1;
            --bg: #f7f5f2;
            --text: #333;
            --white: #fff;
            --gradient: linear-gradient(135deg, #8b5e3c 0%, #b5835a 100%);
            --gradient-dark: linear-gradient(135deg, #6b4226 0%, #8b5e3c 100%);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            background-image: 
                radial-gradient(ellipse at 20% 50%, rgba(139, 94, 60, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(181, 131, 90, 0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(139, 94, 60, 0.04) 0%, transparent 50%);
            overflow: hidden;
            position: relative;
        }

        .bg-decor {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            pointer-events: none;
        }
        .bg-decor-1 { width: 400px; height: 400px; background: rgba(139, 94, 60, 0.1); top: -100px; right: -100px; }
        .bg-decor-2 { width: 300px; height: 300px; background: rgba(181, 131, 90, 0.08); bottom: -80px; left: -80px; }

        .login-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 28px;
            padding: 48px 40px;
            box-shadow: 
                0 20px 60px rgba(139, 94, 60, 0.1),
                0 1px 3px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.5);
            text-align: center;
        }

        .login-logo {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(139, 94, 60, 0.2);
            margin-bottom: 20px;
        }

        .login-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: var(--primary);
            margin-bottom: 6px;
        }

        .login-card .subtitle {
            font-size: 14px;
            color: #888;
            margin-bottom: 32px;
        }

        .error {
            background: #fef2f2;
            color: #dc2626;
            font-size: 13px;
            font-weight: 500;
            padding: 10px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-light);
            font-size: 16px;
            transition: color 0.3s;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border: 1.5px solid #e5e1dc;
            border-radius: 14px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            background: rgba(250, 249, 248, 0.7);
            transition: all 0.3s ease;
            outline: none;
        }

        .input-wrapper input:focus {
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(139, 94, 60, 0.1);
        }

        .input-wrapper input:focus + i,
        .input-wrapper:focus-within i {
            color: var(--primary);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 14px;
            background: var(--gradient);
            color: var(--white);
            font-size: 15px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(139, 94, 60, 0.3);
            transition: all 0.3s ease;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 94, 60, 0.4);
            background: var(--gradient-dark);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 24px;
            font-size: 13px;
            color: #999;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-link:hover { color: var(--primary); }

        @media (max-width: 480px) {
            .login-card { padding: 36px 24px; border-radius: 22px; }
            .login-card h2 { font-size: 22px; }
        }
    </style>
</head>
<body>

    <div class="bg-decor bg-decor-1"></div>
    <div class="bg-decor bg-decor-2"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <img src="img/SF.png" alt="Logo Sofwa" class="login-logo">
            <h2>Admin Panel</h2>
            <p class="subtitle">Masuk ke dashboard Sofwa Undangan</p>

            <?php if ($error): ?>
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>

            <a href="index.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>
