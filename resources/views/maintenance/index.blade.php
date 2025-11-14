<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Website Under Maintenance</title>
    <link rel="stylesheet" href="{{ asset('css/bootstraps/bootstrap.min.css') }}">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #a18cd1, #fbc2eb, #a18cd1, #fbc2eb);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        @keyframes lightning {
            0%,
            95%,
            100% {
                background-color: transparent;
            }

            96% {
                background-color: rgba(255, 255, 255, 0.3);
            }

            97% {
                background-color: transparent;
            }
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            animation: lightning 4s infinite;
        }

        .maintenance-container {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            background-color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1.5s ease-in-out;
            max-width: 90%;
            width: 400px;
        }

        .maintenance-container img.logo {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
            animation: zoomIn 1s ease-in-out;
        }

        .maintenance-container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #dc3545;
            animation: bounce 1s infinite alternate;
        }

        .maintenance-container p {
            font-size: 1.2rem;
            color: #6c757d;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(-10px);
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @media (max-width: 576px) {
            .maintenance-container h1 {
                font-size: 2rem;
            }

            .maintenance-container p {
                font-size: 1rem;
            }

            .maintenance-container img.logo {
                width: 80px;
            }
        }

        .rain {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }

        .drop {
            position: absolute;
            bottom: 100%;
            width: 2px;
            height: 20px;
            background: linear-gradient(to bottom, #aeeeee, #b0c4de);
            z-index: -1;
            animation: fall linear infinite;
        }

        @keyframes fall {
            to {
                transform: translateY(110vh);
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <div class="rain" id="rain"></div>
    <div class="maintenance-container">
        <img src="{{ asset('images/logo.png') }}" alt="Website Logo" class="logo">
        <h1>🚧 Under Maintenance</h1>
        <p>We are currently working on the site.<br>Please check back soon!</p>
    </div>
    <script>
        const rainContainer = document.getElementById('rain');
        for (let i = 0; i < 100; i++) {
            const drop = document.createElement('div');
            drop.className = 'drop';
            drop.style.left = `${Math.random() * 100}%`;
            drop.style.animationDuration = `${0.5 + Math.random() * 1.5}s`;
            drop.style.animationDelay = `${Math.random() * 5}s`;
            rainContainer.appendChild(drop);
        }
    </script>
</body>

</html>
