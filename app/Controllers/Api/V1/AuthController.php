<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Libraries\JwtService;
use App\Models\RefreshTokenModel;
use App\Models\UsersModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $users;
    protected $refreshTokens;
    protected $jwt;
    public function __construct()
    {
        $this->users = new UsersModel();
        $this->refreshTokens = new RefreshTokenModel();
        $this->jwt = new JwtService();
    }

    public function login()
    {
        if (!$this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required',
        ])) {
            return api_validation_error($this->validator->getErrors());
        }

        $user = $this->users->where('user_name', $this->request->getPost('email'))
            ->first();

        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return api_unauthorized('Invalid credentials');
        }

        $accessToken = $this->jwt->generate([
            'sub' => $user['user_id'],
            'user_name' => $user['user_name'],
            'role' => $user['user_role'] ?? null,
        ]);

        $refreshToken = bin2hex(random_bytes(40));

        $this->refreshTokens->insert([
            'user_id' => $user['user_id'],
            'token_hash' => hash('sha256', $refreshToken),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+7 days')),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return api_success([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => [
                'user_id' => $user['user_id'],
                'user_name' => $user['user_name'],
                'user_role ' => $user['user_role'],
            ],
        ], 'Login successful');
    }

    public function refreshToken()
    {
        $refreshToken = $this->request->getPost('refresh_token');

        if (!$refreshToken) {
            return api_validation_error(['refresh_token' => 'Refresh token is required']);
        }

        $hash = hash('sha256', $refreshToken);

        $row = $this->refreshTokens
            ->where('token_hash', $hash)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$row) {
            return api_unauthorized('Invalid or expired refresh token');
        }

        $user = $this->users->find($row['user_id']);

        if (!$user) {
            return api_unauthorized('User not found');
        }

        $this->refreshTokens->delete($row['id']);

        $newRefreshToken = bin2hex(random_bytes(40));

        $this->refreshTokens->insert([
            'user_id' => $user['user_id'],
            'token_hash' => hash('sha256', $newRefreshToken),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+7 days')),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $newAccessToken = $this->jwt->generate([
            'user_id' => $user['user_id'],
            'user_name' => $user['user_name'],
            'user_role' => $user['user_role'] ?? null,
        ]);

        return api_success([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
        ], 'Token refreshed');
    }

    public function me()
    {
        return api_success($this->request->user);
    }

    public function logout()
    {
        $refreshToken = $this->request->getPost('refresh_token');

        if (!$refreshToken) {
            return api_validation_error(['refresh_token' => 'Refresh token is required']);
        }

        $hash = hash('sha256', $refreshToken);

        $this->refreshTokens
            ->where('token_hash', $hash)
            ->delete();

        return api_success(null, 'Logged out successfully');
    }
}
