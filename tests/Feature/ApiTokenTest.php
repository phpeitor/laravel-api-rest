<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureApiToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ApiTokenTest extends TestCase
{
    public function test_api_rejects_a_request_without_a_bearer_token(): void
    {
        config(['services.api_token' => 'expected-test-token']);

        $this->getJson('/api/v2/clientes')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Token Bearer ausente o inválido.');
    }

    public function test_api_rejects_an_invalid_bearer_token(): void
    {
        config(['services.api_token' => 'expected-test-token']);

        $this->withToken('wrong-token')
            ->getJson('/api/v2/clientes')
            ->assertUnauthorized()
            ->assertHeader('WWW-Authenticate', 'Bearer');
    }

    public function test_api_fails_closed_when_no_server_token_is_configured(): void
    {
        config(['services.api_token' => '']);

        $this->getJson('/api/v2/clientes')
            ->assertServiceUnavailable()
            ->assertJsonPath('message', 'La autenticación de la API no está configurada.');
    }

    public function test_valid_bearer_token_is_allowed_through_the_middleware(): void
    {
        config(['services.api_token' => 'expected-test-token']);
        $request = Request::create('/api/v2/clientes', 'GET', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer expected-test-token',
        ]);

        $response = (new EnsureApiToken())->handle(
            $request,
            fn () => new Response('authorized', Response::HTTP_OK),
        );

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('authorized', $response->getContent());
    }
}
