<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Team;
use App\UserDetails;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Users as UserCollection;
use App\Http\Helpers\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class UserController extends Controller
{
    use ConfirmsPasswords;

    public function __construct( User $user, Storage $storage )
    {
        $this->user = $user;
        $this->storage = $storage;
    }

    /**
     * Get all resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new UserCollection($this->user->with($this->userDetails())->get());

    }

    /**
     * Get the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $userId)
    {
        return new UserResource($this->user->find($userId));
    }

    /**
     * Get the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
        // Return the logged-in user
        return new UserResource(Auth::user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserDetails $userDetails, $userId)
    {
        // Fetch the useer
        $user = $this->user->find($userId);
        $user->name = $request->name;

        // Details data
        $details = $request->all();

        // process profile image
        if ($request->hasFile('user_image')) {
            $this->storage->saveUserProfileImage($userId, $request->user_image);

            $user->profile_image = $this->storage->getPublicPath();
            $details['user_image'] = $this->storage->getPublicPath();
        }

        // Save in user table
        $user->save();

        //save user details
        $userDetails->saveDetails($details, $userId, 'user_profile');

        // Return data
        return new UserResource($user->load($this->userDetails()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
        try {
            $this->user->destroy($userId);
            return response()->json([
                'msg' => 'The user profile was deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Confirm the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function confirm(Request $request)
    {
        try {
            // Apply validation
            $request->validate($this->rules(), $this->validationErrorMessages());

            // Fetch the user model
            $user = $this->user->where('email', $request->email)->first();

            if(!$user) {
                return response()->json([
                    'error' => true,
                    'msg' => 'update failed, credentials do not match our records.',
                ]);
            }

            // Update then save
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'msg' => 'Password updated.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * A query function for the details of user with selected data
     */
    protected function userDetails()
    {
        return ['details' => function ($q) {
            return $q->select('user_id', 'key', 'value');
        }];
    }
}
