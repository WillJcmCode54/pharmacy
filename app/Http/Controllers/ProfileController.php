<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
          
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

        $request->user()->number_id = $number_id;
        $request->user()->phone = $phone;

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
