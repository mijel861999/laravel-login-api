<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
</head>
<body>
    <p>Hola,</p>
    
    <p>Recibiste este correo porque solicitaste un restablecimiento de contraseña. Haz clic en el siguiente enlace para continuar:</p>

    <a href="{{ url('password/reset', $token) }}">Restablecer Contraseña</a>

    <p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este correo.</p>

    <p>Gracias,</p>
    <p>Tu aplicación</p>
</body>
</html>