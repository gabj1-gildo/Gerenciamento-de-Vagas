@extends('errors.layout')

@section('title', 'Erro Interno do Servidor')
@section('code', '500')
@section('message', 'Ops! Algo deu errado do nosso lado. Isso geralmente acontece devido a uma falha de conexão (como tentar enviar um e-mail com credenciais inválidas) ou um erro inesperado no sistema. Por favor, tente novamente mais tarde.')
