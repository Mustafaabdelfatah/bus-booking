<?php
namespace App\Services\Auth;

use App\Models\Client;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\InvalidOtpException;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\SendOtpNotification;
use Illuminate\Support\Facades\Notification;
use App\Exceptions\InvalidEmailAndPasswordCombinationException;

class AuthService
{
    private string $model;
    private string $guard;

    /**
     * @throws InvalidEmailAndPasswordCombinationException
     */
    public function attempt(Request $request): array
    {
        $query = $this->getModel()->query();

        $user = $this->getGuard() === 'client' ? $this->findClient($query, $request) : $this->findEmployee($query, $request);

        if ($this->getGuard() === 'client') {
            $this->generateAndSaveOtp($user);
            return ['user' => $user, 'otp_required' => true];
        }
        $this->setLastLogin($user);
        return [
            'user' => $user,
            'token' => $user->createToken($this->getGuard())->plainTextToken,
        ];
    }

    /**
     * Find a client by email or phone.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\InvalidEmailAndPasswordCombinationException
     */
    protected function findClient($query, Request $request): Model
    {
        $user = $query->where('email', $request->email)->orWhere('phone', $request->phone)
            ->first();

        if (!$user) {
            throw new InvalidEmailAndPasswordCombinationException();
        }

        return $user;
    }

    /**
     * Find an employee by email.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\InvalidEmailAndPasswordCombinationException
     */
    protected function findEmployee($query, Request $request): Model
    {
        $user = $query->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new InvalidEmailAndPasswordCombinationException();
        }

        return $user;
    }

    /**
     * Generates a one-time password (OTP) for the given client and updates the client's record with the OTP
     * and its expiration time. Logs the OTP for debugging purposes.
     *
     * @param \App\Models\Client $client The client for whom the OTP is being generated.
     */
    public function generateAndSaveOtp($client)
    {
        $client->update([
            'otp' => '123456',
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // Notification::send($user, new SendOtpNotification($otp));

        \Log::info("OTP for client {$client->email}");
    }

    /**
     * Verify OTP and generate a new token
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \App\Exceptions\InvalidOtpException
     */
    public function verifyOtp(Request $request): array
    {
        $client = Client::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if (!$client || $client->otp !== $request->otp || $client->otp_expires_at->isPast()) {
            throw new InvalidOtpException(__('api.invalid_otp'));
        }

        $client->update(['otp' => null, 'otp_expires_at' => null]);

        $this->setLastLogin($client);
        $token = $client->createToken('client_token')->plainTextToken;

        return [
            'message' => __('api.login_successful'),
            'user' => $client,
            'token' => $token,
        ];
    }

    /**
     * Register a new user and return the user and its token.
     *
     * @param Request $request
     * @return array
     */
    public function register(Request $request): array
    {
        $user = $this->getModel()->create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'is_active' => true,
        ]);

        $this->setLastLogin($user);

        return [
            'user' => $user,
            'token' => $user->createToken($this->getGuard())->plainTextToken,
        ];
    }
    /**
     * @param  String $model
     * @return $this
     */
    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return new $this->model();
    }

    /**
     * @param  String $guard
     * @return $this
     */
    public function setGuard(string $guard): self
    {
        $this->guard = $guard;
        return $this;
    }

    /**
     * @return String
     */
    public function getGuard(): string
    {
        return $this->guard;
    }

    /**
     * @param  $user
     * @return bool
     */
    public function setLastLogin($user): bool
    {
        return $user->update(['last_login' => now()]);
    }
}