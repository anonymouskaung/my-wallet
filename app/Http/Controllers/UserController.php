<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function editName(Request $request) {
        $user = auth()->user();
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:users,name,' . $user->id, // the name column must be unique in users table except by $user->id
            ] // e.g. 'unique:users,name,1'
        ], [
            'name.unique' => 'This username already in use by another account.',
        ]);
        if($user) {
            $user->name = $request->name;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $request->name,
            ],
        ]);
    }
    public function editPhoto(Request $request) {
        if(!$request->hasFile('editPhoto')) {
            return response()->json(['error' => true], 422);
        }
        $file = $request->file('editPhoto');
        if(!$file->isValid()) {
            return response()->json(['error' => true], 422);
        }
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if(!in_array($file->getMimeType(), $allowedTypes)) {
            return response()->json(['typeError' => true], 415);
        }
        $fileName = uniqid('profile_', true) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/profiles'), $fileName);
        $user = auth()->user();
        if($user) {
            $user->photo = $fileName;
            $user->save(); 
        } else {
            return response()->json(['error' => true]);
        }
        return response()->json([
            'photoUrl' => asset('images/profiles/' . $fileName)
        ]);
    }
    public function changePassword(Request $request) {
            $request->validate([
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
                ],
            ], [
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
                'password.min' => 'Password must be at least 8 characters.',
            ]);
        $user = auth()->user();
        if($user) {
            $user->password = $request->password;
            $user->save();
        }

        return response()->json(['success' => true]);
    }
    public function addPhone(Request $request) {
        $user = auth()->user();
        $request->validate([
            'recoverPhone' => [
                'required',
                'string',
                'unique:users,phone,' . $user->id,
            ]
        ], [
            'recoverPhone.unique' => 'This phone number already in use by another account.',
        ]);
        if($user) {
            $user->phone = $request->recoverPhone;
            $user->save();
        }
        return response()->json([
            'success' => true, 
            'data' => [
                'phone' => $request->recoverPhone,
            ],
        ]);
    }
    public function addEmail(Request $request) {
        $user = auth()->user();
        $request->validate([
            'recoverEmail' => [
                'required',
                'string',
                'email',
                'unique:users,email,' . $user->id,
            ],
            ], [
                'recoverEmail.required' => 'Email is required.',
                'recoverEmail.email' => 'Please enter a valid email.',
                'recoverEmail.unique' => 'This email already in use by another account.',
            ]);
        if($user) {
            $user->email = $request->recoverEmail;
            $user->save(); 
        }
        return response()->json([
            'success' => true,
            'data' => [
                'email' => $request->recoverEmail,
            ],
        ]);
    }
    public function confirmPassword(Request $request) {
        $user = auth()->user();
        if( Hash::check($request->password, $user->password) ) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([ 'success' => false ]);
        }
    }
}
