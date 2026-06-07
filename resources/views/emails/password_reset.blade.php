<!DOCTYPE html>
<html>
<head>
    <title>Recuperação de Senha - SyncMatch</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Olá, {{ $user->name }}!</h2>
    <p>Você solicitou a redefinição de senha para sua conta no SyncMatch.</p>
    
    <p>Clique no link abaixo para criar uma nova senha:</p>
    
    <p style="margin: 20px 0;">
        <a href="{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}" style="background-color: #7c3aed; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Redefinir Senha
        </a>
    </p>

    <p>Se você não solicitou a redefinição, apenas ignore este e-mail.</p>
    <p>Equipe SyncMatch</p>
</body>
</html>
