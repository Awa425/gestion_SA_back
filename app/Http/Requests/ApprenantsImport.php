<?php
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;

class ApprenantsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $validator = Validator::make($rows->toArray(), $this->rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $rows->map(function ($row) {
            return $this->transform($row);
        });
    }

    public function rules(): array
    {
        return [
            '*.nom' => ['required', 'string', 'max:255'],
            '*.prenom' => ['required', 'string', 'max:255'],
            '*.email' => ['required', 'email', 'max:255', 'unique:apprenants,email'],
            '*.password' => ['required', 'string', 'max:255'],
            '*.date_naissance' => ['required', 'date'],
            '*.lieu_naissance' => ['required', 'string', 'max:255'],
            '*.user_id' => ['required', 'integer', 'exists:users,id'],
            '*.is_active' => ['required', 'boolean'],
        ];
    }

    protected function transform(array $row): array
    {
        return [
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'password' => $row['password'],
            'date_naissance' => $row['date_naissance'],
            'lieu_naissance' => $row['lieu_naissance'],
            'user_id' => $row['user_id'],
            'is_active' => $row['is_active'],
        ];
    }
}
