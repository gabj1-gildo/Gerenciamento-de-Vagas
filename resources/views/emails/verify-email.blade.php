<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifique seu E-mail</title>
    <style>
        body {
            font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            margin: 0;
            padding: 40px 20px;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #fb7185, #f43f5e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
            margin: 0;
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin-top: 0;
            margin-bottom: 16px;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 24px;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.35);
            transition: all 0.3s ease;
        }
        .footer {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
            font-size: 13px;
            color: #64748b;
        }
        .footer a {
            color: #f43f5e;
            text-decoration: none;
        }
        .break-word {
            word-break: break-all;
            font-size: 12px;
            color: #475569;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="logo">SyncMatch</span>
        </div>
        
        <h1>Confirme seu endereço de e-mail</h1>
        
        <p>Olá, <strong>{{ $user->name }}</strong>!</p>
        
        <p>Agradecemos por se cadastrar na nossa plataforma de gerenciamento de vagas. Para garantir a segurança da sua conta e liberar o acesso completo ao SyncMatch, por favor clique no botão abaixo para verificar seu endereço de e-mail:</p>
        
        <div class="btn-container">
            <a href="{{ $verificationUrl }}" class="btn" target="_blank">Verificar E-mail</a>
        </div>
        
        <p>Se você não criou esta conta, por favor ignore este e-mail.</p>
        
        <div class="footer">
            Se estiver tendo problemas para clicar no botão "Verificar E-mail", copie e cole a URL abaixo no seu navegador:<br>
            <div class="break-word">{{ $verificationUrl }}</div>
            <br>
            © {{ date('Y') }} SyncMatch. Todos os direitos reservados.
        </div>
    </div>
</body>
</html>
