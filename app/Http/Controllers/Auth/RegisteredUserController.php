<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (($request->number_id[0] != "V" || $request->number_id[0] != "E" || $request->number_id[0] != "J" )) {
            $pre_value = preg_replace("/[^0-9-.]/", "", $request->number_id);
            $number_id = "V".$pre_value;
        } else {
            $legal = $request->number_id[0];
            $pre_value = preg_replace("/[^0-9-.]/", "", $request->number_id);
            $number_id = $legal."".$pre_value;
        }        
        
        $phone = ($request->user()->phone);
        $phone = (($phone[0]) != "+") ? "+58".$request->user()->phone : $request->user()->phone;

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $phone,
            'number_id' => $number_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
