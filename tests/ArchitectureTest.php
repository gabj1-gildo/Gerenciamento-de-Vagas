<?php

/**
 * Testes de Arquitetura (Arch Testing) utilizando Pest PHP.
 */

test('todas as classes de Strategy obrigatoriamente implementam a AuthorizationStrategyInterface')
    ->expect('App\Patterns\Strategy')
    ->classes()
    ->toImplement('App\Patterns\Strategy\AuthorizationStrategyInterface');

test('nenhuma classe do projeto utiliza funções de debug ou configuração em produção')
    ->expect(['dd', 'dump', 'env', 'ray'])
    ->not->toBeUsed();

test('nenhum controller utiliza a Facade DB diretamente')
    ->expect('App\Http\Controllers')
    ->not->toUse('Illuminate\Support\Facades\DB');
