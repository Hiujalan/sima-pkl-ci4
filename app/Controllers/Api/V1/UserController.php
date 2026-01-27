<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;

class UserController extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new UsersModel();
    }

    public function index()
    {
        $user = $this->request->user;

        $query = $this->userModel;

        if ($user->user_role !== 'super_admin') {
            $query->where('is_active', 1);
        }

        return api_success($query->findAll());
    }

    public function show(string $uuid)
    {
        $query = $this->userModel->find($uuid);

        if (!$query) {
            return api_not_found('User not found');
        }

        return api_success($query);
    }

    public function create()
    {
        $payload = $this->request->getJSON(true);

        if (!$payload) {
            return api_error('Invalid JSON');
        }

        $rules = [
            'user_name' => 'required|valid_email|is_unique[users.user_name]|trim',
            'password' => 'required|min_length[5]|trim',
            'user_role' => 'required|is_integer',
        ];

        if (!$this->validateData($payload, $rules)) {
            return api_validation_error($this->validator->getErrors());
        }

        $payload['user_id'] = Uuid::uuid4()->toString();
        $payload['password'] = password_hash($payload['password'], PASSWORD_BCRYPT);

        if (!$this->userModel->save($payload)) {
            return api_error('Failed to create user');
        }

        return api_success(['message' => 'User created successfully']);
    }

    public function update(string $uuid)
    {
        $payload = $this->request->getJSON(true);

        if (!$payload) {
            return api_error('Invalid JSON');
        }

        $rules = [
            'user_name' => function ($value) use ($uuid, $payload) {
                if (array_key_exists('user_name', $payload)) {
                    if (trim($value) === '') {
                        return 'Email is required';
                    }

                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return 'Email format is invalid';
                    }

                    $exists = $this->userModel
                        ->where('user_name', $value)
                        ->where('user_id !=', $uuid)
                        ->countAllResults();

                    if ($exists > 0) {
                        return 'Email already taken';
                    }
                }

                return true;
            },

            'password' => function ($value) use ($payload) {
                if (array_key_exists('password', $payload) && trim($value) !== '') {
                    if (strlen($value) < 5) {
                        return 'Password min 5 chars';
                    }
                }
                return true;
            },

            'user_role' => function ($value) use ($payload) {
                if (array_key_exists('user_role', $payload) && !ctype_digit((string) $value)) {
                    return 'Role must be integer';
                }
                return true;
            },

            'is_active' => function ($value) use ($payload) {
                if (array_key_exists('is_active', $payload) && !in_array($value, ['0', '1', 0, 1], true)) {
                    return 'Invalid active status';
                }
                return true;
            },
        ];

        $result = map_and_validate_fields($payload, $rules);

        if ($result['error']) {
            return api_validation_error($result['errors']);
        }

        if (empty($result['data'])) {
            return api_validation_error([
                'message' => 'No updatable fields provided'
            ]);
        }

        if (array_key_exists('password', $payload)) {
            $result['data']['password'] = isset($result['data']['password']) ? password_hash($result['data']['password'], PASSWORD_BCRYPT) : null;
        }

        if (!$this->userModel->update($uuid, $result['data'])) {
            return api_error('Failed to update user');
        }

        return api_success(['message' => 'User updated successfully']);
    }

    public function delete(string $uuid)
    {
        if ($this->userModel->find($uuid) === null) {
            return api_not_found('User not found');
        }

        if (!$this->userModel->delete($uuid)) {
            return api_error('Failed to delete user');
        }

        return api_success(['message' => 'User deleted successfully']);
    }
}
