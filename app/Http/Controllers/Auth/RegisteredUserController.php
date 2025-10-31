<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'fname' => ['required', 'string', 'max:255'],
                'lname' => ['nullable', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20'],
                'image' => ['nullable', 'image'],
                'birthdate' => ['required', 'date'],
                'sex' => ['required', 'string', 'max:1'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'phone' => $request->phone,
                'image' => $request->image,
                'birthdate' => $request->birthdate,
                'sex' => $request->sex,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            // Auth::login($user);

            return redirect(route('welcome', absolute: false));
        } catch (\Throwable $th) {
            throw new \Exception('Registration failed: ' . $th->getMessage());
        }
    }
}
