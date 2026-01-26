<?php

if (!function_exists('map_and_validate_fields')) {
    function map_and_validate_fields(array $payload, array $rules): array
    {
        $data = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            if (!array_key_exists($field, $payload)) {
                continue;
            }

            $value = $payload[$field];

            // Custom validator (closure)
            if (is_callable($rule)) {
                $result = $rule($value);

                if ($result !== true) {
                    $errors[$field] = $result;
                    continue;
                }

                $data[$field] = is_string($value) ? trim($value) : $value;
                continue;
            }

            // Rule: bool01
            if ($rule === 'bool_is_active') {
                if (!in_array($value, [0, 1, '0', '1'], true)) {
                    $errors[$field] = "$field must be 0 or 1";
                    continue;
                }

                $data[$field] = (int) $value;
            }
        }

        return [
            'error'  => !empty($errors),
            'errors' => $errors,
            'data'   => $data,
        ];
    }
}
