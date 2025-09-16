<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'password'     => 'required|string|min:8|confirmed',
            'role'         => 'nullable|string|in:admin,pegawai',
            'position'     => 'nullable|string|max:500',
            'department'   => 'nullable|string|max:500',
            'phone_number' => 'nullable|string|max:20',  // Changed from 'phone' to 'phone_number'
            'address'      => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => $request->role ?? 'admin',
            'position'          => $request->position,
            'department'        => $request->department,
            'phone_number'      => $request->phone_number,  // Changed from 'phone' to 'phone_number'
            'address'           => $request->address,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'email'        => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password'     => 'nullable|string|min:8|confirmed',
            'role'         => 'nullable|string|in:admin,user',
            'position'     => 'nullable|string|max:500',
            'department'   => 'nullable|string|max:500',
            'phone_number' => 'nullable|string|max:20',  // Changed from 'phone' to 'phone_number'
            'address'      => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userData = [
            'name'         => $request->name,
            'email'        => $request->email,
            'role'         => $request->role ?? $user->role,
            'position'     => $request->position,
            'department'   => $request->department,
            'phone_number' => $request->phone_number,  // Changed from 'phone' to 'phone_number'
            'address'      => $request->address,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting current authenticated user
        if ($user && $user->id == $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(string $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "User berhasil {$status}!");
    }

    /**
     * Search users
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->latest()
            ->paginate(10)
            ->appends(['q' => $query]);

        return view('users.index', compact('users', 'query'));
    }

    /**
     * Export users to CSV
     */
    public function export()
    {
        $users = User::all();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Position', 'Phone Number', 'Created At']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->position,
                    $user->phone_number,  // Changed from 'phone' to 'phone_number'
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}