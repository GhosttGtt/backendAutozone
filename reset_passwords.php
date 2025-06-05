<?php
// reset_password.php
// Página para ingresar nueva contraseña usando el token de recuperación

// Validar que el token esté presente en la URL
$token = $_GET['token'] ?? '';
if (empty($token)) { ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Restablecer contraseña</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background: #f4f4f4;
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                align-content: center;
                justify-content: center;
                align-items: center;
                height: 100dvh;
            }

            .container {
                max-width: 220px;
                margin: 60px auto;
                background: #fff;
                padding: 30px;
                border-radius: 24px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .container img {
                display: block;
                margin: 0 auto 25px;
                /* width: 100px; */
                height: 20px;
                /* border-radius: 50%; */
            }

            h2 {
                text-align: center;
            }

            label {
                display: block;
                margin-top: 15px;
                font-size: 12px;
                font-weight: bold;
            }

            input[type="password"] {
                width: 100%;
                max-width: -webkit-fill-available;
                padding: 8px;
                margin-top: 5px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            input {
                max-width: -webkit-fill-available;
            }

            button {
                width: 100%;
                padding: 10px;
                margin-top: 20px;
                background: #7612d5;
                color: #fff;
                border: none;
                border-radius: 50px;
                font-size: 16px;
                cursor: pointer;
            }

            button:hover {
                background: #4f0893;
            }

            .msg {
                margin-top: 20px;
                text-align: center;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <img src="img/logoGris.png" alt="">
            <h4>Token incorrecto</h4>


        </div>

    </body>

    </html>


<?php exit;
} ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-content: center;
            justify-content: center;
            align-items: center;
            height: 100dvh;
        }

        .container {
            max-width: 220px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin: 15px
        }

        .container img {
            display: block;
            margin: 0 auto 25px;
            /* width: 100px; */
            height: 25px;
            /* border-radius: 50%; */
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        input[type="password"] {
            width: 100%;
            max-width: -webkit-fill-available;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: #7612d5;
            color: #fff;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #4f0893;
        }

        .msg {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="img/logoGris.png" alt="">
        <div style="text-align:center">
            <h4>Restablecer contraseña</h4>
        </div>
        <form id="resetForm">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="password">Nueva contraseña:</label>
            <input type="password" id="password" name="password" required>
            <label for="confirm">Confirmar contraseña:</label>
            <input type="password" id="confirm" name="confirm" required>
            <button type="submit">Cambiar contraseña</button>
        </form>
        <div class="msg" id="msg"></div>
    </div>
    <script>
        document.getElementById('resetForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm').value;
            const token = document.querySelector('input[name="token"]').value;
            const msg = document.getElementById('msg');
            msg.textContent = '';
            if (password.length < 6) {
                msg.textContent = 'La contraseña debe tener al menos 6 caracteres.';
                return;
            }
            if (password !== confirm) {
                msg.textContent = 'Las contraseñas no coinciden.';
                return;
            }
            const response = await fetch('api/password_update_client.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    token,
                    password
                })
            });
            const data = await response.json();
            msg.textContent = data.message;
            if (response.ok && data.success) {

                window.location.href = "https://www.alexcg.de/autozone/exito.html";
            }
        });
    </script>
</body>

</html>