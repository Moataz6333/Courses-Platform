<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserLoginRequest;
use App\Http\Requests\Api\UserRegiterationRequest;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function register(UserRegiterationRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        switch ($user->role) {
            case 'student':
                Student::create([
                    'user_id' => $user->id
                ]);

                return response()->json([
                    'message' => 'Student is registered in successfully!',
                    'user' => $user->load('student'),
                    'token' => $token
                ], 200);
                break;

            case 'teacher':
                Teacher::create([
                    'user_id' => $user->id,
                    'phone' => $request->phone,
                    'national_id' => $request->national_id,
                ]);
                return response()->json([
                    'message' => 'Teacher is registered in successfully!',
                    'user' => $user->load('teacher'),
                    'url' => url('/'),
                    'token' => $token
                ], 200);
                break;

            default:
                abort(404);
                break;
        }
    }
    public function login(UserLoginRequest $request)
    {
        $credentials = request()->only(['email', 'password']);

        if (Auth::attempt($credentials, request()->filled('remember'))) {

            $user = Auth::user();

            // Generate a token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            switch ($user->role) {
                case 'student':
                    Student::create([
                        'user_id' => $user->id
                    ]);

                    return response()->json([
                        'message' => 'Student is logged in successfully!',
                        'user' => $user->load('student'),
                        'token' => $token
                    ], 200);
                    break;

                case 'teacher':
                    Teacher::create([
                        'user_id' => $user->id
                    ]);
                    return response()->json([
                        'message' => 'Teacher is logged in successfully!',
                        'user' => $user->load('teacher'),
                        'url' => url('/'),
                        'token' => $token
                    ], 200);
                    break;

                default:
                    abort(404);
                    break;
            }
        }

        return response()->json([
            'message' => 'Invalid email or password',
        ], 401);
    }
    public function me()
    {
        return response()->json([
            'user' => Auth::user()
        ], 200);
    }
    // google
    public function redirectToGoogle()
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()
        ]);
    }
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Find or create the user
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(8)), // Random password
                    'role' => 'student',
                    'gender' => 'male'
                ]
            );
             Student::firstOrCreate([
                    'user_id' => $user->id
                ]);

            // Generate token (if using Sanctum or Passport)
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                    'message' => 'Student is registered in successfully!',
                    'user' => $user->load('student'),
                    'token' => $token
                ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication failed', 'message' => $e->getMessage()], 500);
        }
    }
}
