<?php

namespace App\Http\Controllers\Auth;

use App\Models\Client;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle dynamic login.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request, $userType = null): JsonResponse
    {
        $userType = $userType ?? 'employee';

        $modelClass = match ($userType) {
            'client' => Client::class,
            'employee' => Employee::class,
            default => null,
        };


        if (!$modelClass) {
            return failResponse(__('api.invalid_user_type'));
        }

        $user = $this->authService->setGuard($userType)->setModel($modelClass)->attempt($request);

        if (isset($user['otp_required']) && $user['otp_required'] === true) {
            return successResponse(null, __('api.otp_sent'));
        }

        return successResponse(new LoginResource($user['user'], $user['token']), __('api.login_success'));
    }


}
