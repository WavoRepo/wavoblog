<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'key', 'value', 'section'];

    /**
     * Save Details
     *
     * @param array $details
     * @param integer $userId
     * @param string $section
     */
    public function saveDetails($details, $userId, $section)
    {
        foreach ($details as $key => $value) {
            $this->updateOrCreate(
                [
                    'key' => $key,
                    'user_id' => $userId
                ],
                [
                    'user_id' => $userId,
                    'key' => $key,
                    'value' => $value,
                    'section' => $section
                ]
            );
        }
    }
}
