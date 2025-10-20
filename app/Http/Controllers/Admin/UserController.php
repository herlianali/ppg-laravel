<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function mahasiswa()
    {
        $users = User::where('role', 'mahasiswa')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,verifikator,mahasiswa',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
            'address'  => $request->address,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|',
            'role'  => 'required|in:admin,verifikator,mahasiswa',
        ]);

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'role'    => $request->role,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }

    // Import Users from Excel/CSV
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));

            return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    // Download Template
    public function downloadTemplate()
    {
        $templatePath = storage_path('app/templates/user_import_template.xlsx');

        if (!file_exists($templatePath)) {
            // Create template if doesn't exist
            $this->createTemplate();
        }

        return response()->download($templatePath, 'user_import_template.xlsx');
    }

    private function createTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'nama');
        $sheet->setCellValue('B1', 'email');
        $sheet->setCellValue('C1', 'password');
        $sheet->setCellValue('D1', 'role');
        $sheet->setCellValue('E1', 'telepon');
        $sheet->setCellValue('F1', 'alamat');

        // Set example data
        $sheet->setCellValue('A2', 'John Doe');
        $sheet->setCellValue('B2', 'john@example.com');
        $sheet->setCellValue('C2', 'password123');
        $sheet->setCellValue('D2', 'mahasiswa');
        $sheet->setCellValue('E2', '08123456789');
        $sheet->setCellValue('F2', 'Jl. Contoh No. 123');

        // Style headers
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Auto size columns
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save(storage_path('app/templates/user_import_template.xlsx'));
    }
}
