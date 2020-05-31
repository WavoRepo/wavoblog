<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use App\UserDetails;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Users as UserCollection;
use App\Http\Helpers\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ConfirmsPasswords;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Traits\ProfileImage;

class UserController extends Controller
{
    use ProfileImage, ConfirmsPasswords;

    /**
     * Get all resource in storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return new UserCollection($user->with('details')->get());
    }

    /**
     * Get the specified resource in storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Get the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function active()
    {
        // Return the logged-in user
        return new UserResource(auth()->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserDetails $userDetails
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, Storage $storage, UserDetails $userDetails, User $user)
    {
        // Update the useer table
        $user->update($request->validated());

        //save user details
        $userDetails->saveDetails($request->all(), $user->id, 'user_profile');

        // process profile image
        $this->saveProfileImage($request, $storage, $user, $userDetails);

        // Return data
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'msg' => 'The user profile was deleted successfully.'
        ]);
    }

    /**
     * Confirm the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function confirm(Request $request, User $user)
    {
        // Apply validation
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Fetch the user model
        $user = $user->where('email', $request->email)->firstOrFail();

        // Update then save
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'msg' => 'Password was successfully updated.',
        ]);
    }
}
