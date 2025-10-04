<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventHub - College Event Portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <div class="brand">EventHub</div>
        <nav>
            <a href="#hero">Home</a>
            <a href="#features">Features</a>
            <a href="#login">Login / Register</a>
        </nav>
    </header>

    <main>
        <section id="hero">
            <h1>Your Campus Life, Elevated.</h1>
            <p>The one-stop portal for every event, workshop, and festival at your college.</p>
            <a href="#login" class="btn">Explore Events</a>
        </section>

        <section id="features" class="container">
            <h2 style="text-align:center; font-size: 2rem; margin-bottom: 3rem;">Everything You Need in One Place</h2>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div class="card" style="text-align:center;">
                    <i class="fas fa-calendar-alt fa-3x" style="color:var(--primary); margin-bottom: 1rem;"></i>
                    <h3>Discover Events</h3>
                    <p>Get a real-time feed of all campus happenings. From tech fests to art exhibitions, never miss out.</p>
                </div>
                <div class="card" style="text-align:center;">
                    <i class="fas fa-mouse-pointer fa-3x" style="color:var(--primary); margin-bottom: 1rem;"></i>
                    <h3>Seamless Registration</h3>
                    <p>Register for any event with a single click. Your spot is reserved instantly, with an email confirmation.</p>
                </div>
                <div class="card" style="text-align:center;">
                    <i class="fas fa-users fa-3x" style="color:var(--primary); margin-bottom: 1rem;"></i>
                    <h3>Admin Control Panel</h3>
                    <p>Organizers can create, manage, and track event attendance with powerful and intuitive tools.</p>
                </div>
            </div>
        </section>

        <section id="login" class="container">
            <div class="card login-box">
                <div class="login-form">
                    <h2>Get Started</h2>
                    <p>Login with your college credentials or register by entering a new password.</p>
                    <?php if(isset($_GET['error'])): ?>
                        <p style="color:var(--danger); margin:1rem 0;"><?php echo htmlspecialchars($_GET['error']); ?></p>
                    <?php endif; ?>
                    <form action="auth.php" method="POST" style="margin-top: 1.5rem;">
                         <div class="form-row">
                            <label for="email">College Email</label>
                            <input type="email" id="email" name="email" required placeholder="your.name@college.edu">
                        </div>
                        <div class="form-row">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                            <small>Admin pass: 'admin' | New students are created on first login.</small>
                        </div>
                        <button type="submit" name="login" class="btn">Login / Register</button>
                    </form>
                </div>
                <div>
                     <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTnbmqjYp-kknij75kag0UBDRpaGpTDkfHUgw&s" alt="Students at college" style="width:100%; border-radius:12px; height: 100%; object-fit: cover;">
                </div>
            </div>
        </section>
    </main>

</body>
</html>