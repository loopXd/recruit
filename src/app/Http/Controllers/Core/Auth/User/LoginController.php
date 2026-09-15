<?php

namespace App\Http\Controllers\Core\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Auth\User\LoginRequest as Request;
use App\Services\Core\Auth\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Hooks\User\CustomRoute;

class LoginController extends Controller
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        if ($this->requirements->isSupported() && $this->permission->isSupported()) {
            return redirect()->route('app.environment');
        }
        return view('install.index');
    }

    public function show()
    {
        return view('auth.login',
            [
                'email' => '',
                'password' => '',
                'demo' => [],
            ]);
    }

    /**
     * Realiza o login e retorna a URL de destino.
     *
     * @param Request $request
     * @return string|JsonResponse
     */
    public function login(Request $request): string|JsonResponse
    {
        try {
            $this->service->login();

            $route = CustomRoute::new(true)->handle();
            $route = count($route) ? $route : home_route();
            $referer = $request->headers->get('referer') ?? '';
            parse_str(parse_url($referer, PHP_URL_QUERY) ?? '', $params);

            if (! empty($params['redirect'])) {
                return route('public.jobPost.show_pob_post', [
                    'job_slug' => $params['redirect'],
                ]);
            }

            return route(
                $route['route_name'],
                $route['route_params']
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e instanceof ModelNotFoundException
                    ? trans('default.resource_not_found', ['resource' => trans('default.user')])
                    : $e->getMessage()
            ], 400);
        }
    }

    public function logOut(): RedirectResponse
    {
        session()->flush();
        auth()->logout();
        session()->flush();

        return redirect()->route('users.login.index');
    }
}
