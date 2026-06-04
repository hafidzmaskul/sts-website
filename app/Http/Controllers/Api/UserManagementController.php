<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewStaffUserCredentials;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the child users (staff).
     */
    public function index(Request $request)
    {
        $this->authorizeHeadAccount();

        $users = $request->user()->children()->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Store a newly created staff user.
     */
    public function store(Request $request)
    {
        $this->authorizeHeadAccount();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
        ]);

        $generatedPassword = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($generatedPassword),
            'parent_id' => $request->user()->id,
        ]);

        // Assign same role as parent (Head Account)
        $parentRole = $request->user()->roles->first()->name;
        if ($parentRole) {
            $user->assignRole($parentRole);
        }

        // Create Customer record linked to same company
        $parentCustomer = $request->user()->customer;
        if ($parentCustomer && $parentCustomer->company_id) {
            // Split name into first and last name
            $nameParts = explode(' ', $request->name, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            \App\Models\Customer::create([
                'user_id' => $user->id,
                'company_id' => $parentCustomer->company_id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $request->email,
                'account_level' => 'staff',
                'status_review' => 'approved',
            ]);
        }

        // Send credentials via email
        try {
            Mail::to($request->email)->send(new NewStaffUserCredentials($user, $generatedPassword));
        } catch (\Exception $e) {
            // Log error but don't fail the request significantly
            // Log::error('Failed to send credentials email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Staff user created successfully.',
            'data' => $user,
        ], 201);
    }

    /**
     * Display the specified staff user.
     */
    public function show(Request $request, $id)
    {
        $this->authorizeHeadAccount();

        $user = $request->user()->children()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Update the specified staff user.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeHeadAccount();

        $user = $request->user()->children()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Staff user updated successfully.',
            'data' => $user,
        ]);
    }

    /**
     * Remove the specified staff user from storage.
     */
    public function destroy(Request $request, $id)
    {
        $this->authorizeHeadAccount();

        $user = $request->user()->children()->findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Staff user deleted successfully.',
        ]);
    }

    private function authorizeHeadAccount()
    {
        if (! auth()->user()->hasAnyRole(['trade account', 'credit facilities account'])) {
            abort(403, 'Unauthorized. Only Head Accounts can manage users.');
        }
    }
}
