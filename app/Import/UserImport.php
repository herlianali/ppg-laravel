<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\Rule;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        // Clean the data
        $name = $row['nama'] ?? $row['name'] ?? null;
        $email = $row['email'] ?? null;
        $password = $row['password'] ?? 'password123';
        $role = $row['role'] ?? 'mahasiswa';
        $phone = $row['telepon'] ?? $row['phone'] ?? null;
        $address = $row['alamat'] ?? $row['address'] ?? null;

        // Check if user already exists
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            return null; // Skip existing users
        }

        return new User([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
            'role'     => strtolower($role),
            'phone'    => $phone,
            'address'  => $address,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
            ],
            '*.nama' => 'required|string|max:255',
            '*.name' => 'sometimes|string|max:255',
            '*.role' => 'required|in:admin,verifikator,mahasiswa',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.email.required' => 'Email wajib diisi',
            '*.email.email' => 'Format email tidak valid',
            '*.email.unique' => 'Email :input sudah terdaftar',
            '*.nama.required' => 'Nama wajib diisi',
            '*.role.required' => 'Role wajib diisi',
            '*.role.in' => 'Role harus admin, verifikator, atau mahasiswa',
        ];
    }

    public function prepareForValidation($data)
    {
        // Map alternative column names
        if (isset($data['name']) && !isset($data['nama'])) {
            $data['nama'] = $data['name'];
        }
        if (isset($data['phone']) && !isset($data['telepon'])) {
            $data['telepon'] = $data['phone'];
        }
        if (isset($data['address']) && !isset($data['alamat'])) {
            $data['alamat'] = $data['address'];
        }

        return $data;
    }
}
