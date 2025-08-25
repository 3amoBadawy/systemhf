<?php

namespace Tests\Feature\Smoke;

use Tests\TestCase;

class RoutesSmokeTest extends TestCase
{
    /**
     * Test all public GET routes return successful responses (no 5xx errors)
     */
    public function test_all_public_routes_return_successful_responses(): void
    {
        $publicRoutes = [
            '/login',
            '/media/gallery',
            '/media',
        ];

        foreach ($publicRoutes as $route) {
            $response = $this->get($route);

            // Public routes should return 200 or 302 (redirects are acceptable)
            $this->assertTrue(
                in_array($response->getStatusCode(), [200, 302]),
                "Route {$route} returned status {$response->getStatusCode()}"
            );
        }
    }

    /**
     * Test that protected routes redirect to login (302) instead of throwing 5xx errors
     */
    public function test_protected_routes_redirect_to_login(): void
    {
        $protectedRoutes = [
            '/dashboard',
            '/customers',
            '/products',
            '/invoices',
            '/payments',
            '/branches',
            '/employees',
            '/roles',
            '/permissions',
            '/system-settings',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);

            // Protected routes should redirect to login (302)
            $this->assertEquals(
                302,
                $response->getStatusCode(),
                "Route {$route} should redirect to login, got status {$response->getStatusCode()}"
            );
        }
    }

    /**
     * Test that fallback route redirects to login
     */
    public function test_fallback_route_redirects_to_login(): void
    {
        $response = $this->get('/non-existent-route');

        $this->assertEquals(302, $response->getStatusCode());
    }
}
