<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //--------------- Get specific user ids ---------------
    public static function getUserIds($connectionStatus = [0], $requestSenderUserId = '', $RequestReceivedUserId = '')
    {
        try {
            $userConnectionListQuery = Connection::select('requester_user_id', 'recipient_user_id')
                ->whereIn('status', $connectionStatus);
            //----------- Query to get list of request sender and receiver  user id -----------
            if (isset($requestSenderUserId) && (isset($RequestReceivedUserId))) {
                $userConnectionListQuery = $userConnectionListQuery->where(function ($queryCondition) use ($requestSenderUserId, $RequestReceivedUserId) {
                    $queryCondition->where('requester_user_id', '=', $requestSenderUserId)->orwhere('recipient_user_id', '=', $RequestReceivedUserId);
                });
            } elseif (isset($requestSenderUserId)) {
                //----------- Query to get list of request sent to user id -----------
                $userConnectionListQuery = $userConnectionListQuery->where('requester_user_id', '=', $requestSenderUserId);
            } elseif (isset($RequestReceivedUserId)) {
                //----------- Query to get list of request received from user id -----------
                $userConnectionListQuery = $userConnectionListQuery->where('recipient_user_id', '=', $RequestReceivedUserId);
            } else {
                //----------- Query to get list of request received from user id -----------
                $userConnectionListQuery = $userConnectionListQuery->where('recipient_user_id', '=', 0);
            }

            $userConnectionList = $userConnectionListQuery->distinct('requester_user_id', 'recipient_user_id')
                ->get()->toArray();

            $requesterUserIds = array_column($userConnectionList, 'requester_user_id');
            $recipientUserId = array_column($userConnectionList, 'recipient_user_id');
            //Merge users ids and get unique values
            $userConnectionList = array_merge($requesterUserIds, $recipientUserId);
            $userConnectionIds = array_values(array_unique($userConnectionList));

        } catch (\Exception $e) {
            $userConnectionIds = [];
        }
        return $userConnectionIds;
    }

    //--------------- Get common connection user ids ---------------
    public static function getCommonConnectionUserIds($firstUserId = 0, $secondUserId = 0, $connectedUserIds = [])
    {
        try {
            $connectedUserConnectionIds = User::getUserIds([2], $secondUserId, $secondUserId);
            $commonConnectionUsersIds = array_values(array_intersect($connectedUserIds, $connectedUserConnectionIds));
            //--------------- Remove Login user  id ---------------
            $posOfValue = array_search($firstUserId, array_values($commonConnectionUsersIds));
            unset($commonConnectionUsersIds[$posOfValue]);
            $commonConnectionUsersIds = array_values($commonConnectionUsersIds);
            //---------------  Remove user own id ---------------
            $posOfValue = array_search($secondUserId, $commonConnectionUsersIds);
            unset($commonConnectionUsersIds[$posOfValue]);
            $commonConnectionUsersIds = array_values($commonConnectionUsersIds);
        } catch (\Exception $e) {
            $commonConnectionUsersIds = [];
        }
        return $commonConnectionUsersIds;
    }
}
