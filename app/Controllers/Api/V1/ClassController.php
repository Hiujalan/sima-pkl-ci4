<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;

class ClassController extends BaseController
{
    protected $classModel;
    public function __construct()
    {
        $this->classModel = new ClassModel();
    }

    public function index()
    {
        $user = $this->request->user;

        $query = $this->classModel;

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
            'class_name' => 'required|trim',
            'class_department ' => 'required|trim',
        ];

        if (!$this->validateData($payload, $rules)) {
            return api_validation_error($this->validator->getErrors());
        }

        $payload['class_id'] = Uuid::uuid4()->toString();

        if (!$this->classModel->save($payload)) {
            return api_error('Failed to create class');
        }

        return api_success(['message' => 'Class created successfully']);
    }

    public function update(string $uuid)
    {
        $payload = $this->request->getJSON(true);

        if (!$payload) {
            return api_error('Invalid JSON');
        }

        $rules = [
            'is_active'  => 'bool_is_active',
            'class_name' => function ($value) {
                if (trim($value) === '') {
                    return 'class_name is required';
                }
                return true;
            },
            'class_department' => function ($value) {
                if (trim($value) === '') {
                    return 'class_department is required';
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

        if (!$this->classModel->update($uuid, $result['data'])) {
            return api_error('Failed to update class');
        }

        return api_success(['message' => 'Class updated successfully']);
    }

    public function delete(string $uuid)
    {
        if ($this->classModel->find($uuid) === null) {
            return api_not_found('Class not found');
        }

        if (!$this->classModel->delete($uuid)) {
            return api_error('Failed to delete class');
        }

        return api_success(['message' => 'Class deleted successfully']);
    }
}
