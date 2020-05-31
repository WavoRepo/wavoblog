<?php
namespace App\Http\Traits;

trait ProfileImage
{
    /**
    * [saveProfileImage description]
    * @param [type] $request [description]
    * @param [type] $user [description]
    * @param [type] $userDetails [description]
    */
    protected function saveProfileImage($request, $storage, $user, $userDetails)
    {
        if ($request->hasFile('user_image')) {
            $storage->saveUserProfileImage($user->id, $request->user_image);

            $userDetails->saveDetails([
                    'user_image' => $storage->getPublicPath()
                ],
                $user->id,
                'user_profile'
            );

            $user->update([
                'profile_image' => $storage->getPublicPath()
            ]);
        }
    }
}
