<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;

class LoginController extends Controller
{
    public function __construct(
        protected LoginService $loginService
    ) {}

    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $this->loginService->login($request);

        return redirect()->intended(route('dashboard'));
    }
}
