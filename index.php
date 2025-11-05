<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome - Space Theme</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        body {
            background: radial-gradient(circle at top, #0b0c2a, #000000);
            overflow: hidden;
        }
        /* Star background animation */
        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            animation: twinkle 2s infinite alternate;
        }
        @keyframes twinkle {
            from {opacity: 0.2;}
            to {opacity: 1;}
        }
        /* Floating card animation */
        .floating {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0% {transform: translateY(0);}
            50% {transform: translateY(-15px);}
            100% {transform: translateY(0);}
        }
        /* Neon buttons */
        .neon-btn {
            background: linear-gradient(90deg, #7f00ff, #e100ff);
            box-shadow: 0 0 10px #7f00ff, 0 0 20px #e100ff;
            transition: 0.3s;
        }
        .neon-btn:hover {
            box-shadow: 0 0 20px #7f00ff, 0 0 40px #e100ff;
            transform: scale(1.05);
        }
    </style>
</head>
<body class="relative flex items-center justify-center h-screen">

    <!-- Generate stars -->
    <script>
        for(let i=0; i<100; i++){
            const star = document.createElement('div');
            star.className = 'star';
            star.style.top = Math.random()*100 + '%';
            star.style.left = Math.random()*100 + '%';
            star.style.width = star.style.height = Math.random()*2 + 1 + 'px';
            star.style.animationDuration = 1 + Math.random()*2 + 's';
            document.body.appendChild(star);
        }
    </script>

    <!-- Card -->
    <div class="floating bg-gradient-to-r from-indigo-900 via-purple-900 to-indigo-800 p-10 rounded-3xl shadow-2xl w-96 text-center text-white backdrop-blur-sm">
        <h1 class="text-3xl font-bold mb-8 neon-text">🚀 Welcome to Space Portal</h1>
        <div class="flex flex-col gap-5">
            <a href="register.php" class="neon-btn text-white py-3 px-6 rounded-xl font-bold flex justify-center items-center gap-2">
                <i class="fas fa-user-plus"></i> Register
            </a>
            <a href="login.php" class="neon-btn text-white py-3 px-6 rounded-xl font-bold flex justify-center items-center gap-2">
                <i class="fas fa-right-to-bracket"></i> Login
            </a>
        </div>
    </div>

</body>
</html>
