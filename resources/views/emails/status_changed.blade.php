<!DOCTYPE html>
<html>
<head>
    <title>Atualização da sua candidatura - SyncMatch</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Olá, {{ $application->user->name }}!</h2>
    <p>O status da sua candidatura para a vaga de <strong>{{ $application->job->title }}</strong> na empresa <strong>{{ $application->job->company->name }}</strong> foi atualizado.</p>
    
    <p>Novo status: <strong style="text-transform: uppercase; color: #7c3aed;">{{ str_replace('_', ' ', $application->status) }}</strong></p>
    
    <p>Acesse o painel do candidato no <a href="{{ route('home') }}">SyncMatch</a> para mais detalhes.</p>
    
    <p>Boa sorte!</p>
    <p>Equipe SyncMatch</p>
</body>
</html>
