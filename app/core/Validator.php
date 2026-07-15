<?php
class Validator
{
    private array $errors = [];

    public function validate(array $data, array $rules): array
    {
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $ruleList = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            foreach ($ruleList as $rule) {
                $params = [];

                if (str_contains($rule, ':')) {
                    $parts = explode(':', $rule);
                    $rule = $parts[0];
                    $params = explode(',', $parts[1]);
                }

                $methodName = 'rule' . ucfirst($rule);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($field, $value, $params);
                }
            }
        }

        return $this->errors;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        return !empty($this->errors) ? $this->errors[array_key_first($this->errors)][0] : null;
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    private function ruleRequired(string $field, $value, array $params): void
    {
        if ($value === null || $value === '' || (is_array($value) && empty($value))) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' is required.');
        }
    }

    private function ruleEmail(string $field, $value, array $params): void
    {
        if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'Please provide a valid email address.');
        }
    }

    private function ruleMin(string $field, $value, array $params): void
    {
        $min = (int) ($params[0] ?? 0);
        if ($value !== null && strlen((string) $value) < $min) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be at least {$min} characters.");
        }
    }

    private function ruleMax(string $field, $value, array $params): void
    {
        $max = (int) ($params[0] ?? 999);
        if ($value !== null && strlen((string) $value) > $max) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must not exceed {$max} characters.");
        }
    }

    private function ruleNumeric(string $field, $value, array $params): void
    {
        if ($value !== null && $value !== '' && !is_numeric($value)) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must be a number.');
        }
    }

    private function ruleConfirmed(string $field, $value, array $params): void
    {
        $confirmationField = $field . '_confirmation';
        if (isset($_POST[$confirmationField]) && $value !== $_POST[$confirmationField]) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' confirmation does not match.');
        }
    }

    private function ruleUnique(string $field, $value, array $params): void
    {
        if ($value !== null && $value !== '') {
            $table = $params[0] ?? $field;
            $column = $params[1] ?? $field;
            $excludeId = $params[2] ?? null;

            $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
            $bindings = ['value' => $value];

            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
                $bindings['exclude_id'] = $excludeId;
            }

            $result = Database::fetch($sql, $bindings);
            if ($result && $result->count > 0) {
                $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' already exists.');
            }
        }
    }

    private function ruleAlpha(string $field, $value, array $params): void
    {
        if ($value !== null && $value !== '' && !ctype_alpha(str_replace(' ', '', $value))) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must contain only letters.');
        }
    }

    private function ruleAlnum(string $field, $value, array $params): void
    {
        if ($value !== null && $value !== '' && !ctype_alnum(str_replace(' ', '', $value))) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' must contain only letters and numbers.');
        }
    }

    private function ruleUrl(string $field, $value, array $params): void
    {
        if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, 'Please provide a valid URL.');
        }
    }

    private function ruleIn(string $field, $value, array $params): void
    {
        if ($value !== null && $value !== '' && !in_array($value, $params)) {
            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' is invalid.');
        }
    }

    private function ruleRegex(string $field, $value, array $params): void
    {
        if ($value !== null && $value !== '' && isset($params[0])) {
            if (!preg_match($params[0], $value)) {
                $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . ' format is invalid.');
            }
        }
    }
}
