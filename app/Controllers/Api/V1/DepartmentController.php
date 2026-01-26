<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;

class DepartmentController extends BaseController
{
    protected $departmentModel;
    public function __construct()
    {
        $this->departmentModel = new DepartmentModel();
    }

    public function index()
    {
        $user = $this->request->user;

        $query = $this->departmentModel;

        if ($user->user_role !== 'super_admin') {
            $query->where('is_active', 1);
        }

        return api_success($query->findAll());
    }

    public function create()
    {
        $payload = $this->request->getJSON(true);

        if (!$payload) {
            return api_error('Invalid JSON');
        }

        $rules = [
            'department_name' => 'required|is_unique[department.department_name]|trim',
        ];

        if (!$this->validateData($payload, $rules)) {
            return api_validation_error($this->validator->getErrors());
        }

        $payload['department_id'] = Uuid::uuid4()->toString();

        if (!$this->departmentModel->save($payload)) {
            return api_error('Failed to create department');
        }

        return api_success(['message' => 'Department created successfully']);
    }

    public function update(string $uuid)
    {
        $payload = $this->request->getJSON(true);

        if (!$payload) {
            return api_error('Invalid JSON');
        }

        $rules = [
            'is_active'  => 'bool_is_active',
            'department_name' => function ($value) {
                if (trim($value) === '') {
                    return 'department_name is required';
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

        if (!$this->departmentModel->update($uuid, $result['data'])) {
            return api_error('Failed to update department');
        }

        return api_success(['message' => 'Department updated successfully']);
    }

    public function delete(string $uuid)
    {
        if ($this->departmentModel->find($uuid) === null) {
            return api_not_found('Department not found');
        }

        if (!$this->departmentModel->delete($uuid)) {
            return api_error('Failed to delete department');
        }

        return api_success(['message' => 'Department deleted successfully']);
    }
}
