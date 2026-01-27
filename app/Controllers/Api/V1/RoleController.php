<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;

class RoleController extends BaseController
{
    protected $roleModel;
    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $user = $this->request->user;

        $query = $this->roleModel;

        if ($user->user_role !== 'super_admin') {
            $query->where('is_active', 1);
        }

        return api_success($query->findAll());
    }

    public function show($id)
    {
        $query = $this->roleModel->find($id);

        if (!$query) {
            return api_not_found('Role not found');
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
            'role_name' => 'required|trim',
            'role_access' => 'required|is_unique[role.role_access]|trim',
        ];

        if (!$this->validateData($payload, $rules)) {
            return api_validation_error($this->validator->getErrors());
        }

        if (!$this->roleModel->save($payload)) {
            return api_error('Failed to create role');
        }

        return api_success(['message' => 'Role created successfully']);
    }

    public function update($id)
    {
        $payload = $this->request->getJSON(true);

        if (!$payload) {
            return api_error('Invalid JSON');
        }

        $rules = [
            'is_active'  => 'bool_is_active',
            'role_name' => function ($value) {
                if (trim($value) === '') {
                    return 'role_name is required';
                }
                return true;
            },
            'role_access' => function ($value) {
                if (trim($value) === '') {
                    return 'role_access is required';
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

        if (!$this->roleModel->update($id, $result['data'])) {
            return api_error('Failed to update role');
        }

        return api_success(['message' => 'Role updated successfully']);
    }

    public function delete($id)
    {
        $role = $this->roleModel->find($id);

        if ($role === null) {
            return api_not_found('Role not found');
        }

        if ($role['role_access'] === 'super_admin') {
            return api_error('Cannot delete super_admin role');
        }

        if (!$this->roleModel->delete($id)) {
            return api_error('Failed to delete role');
        }

        return api_success(['message' => 'Role deleted successfully']);
    }
}
