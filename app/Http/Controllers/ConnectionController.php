<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Response;
use DB;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $getSuggestions = $request->get('get_suggestions');
            $getSentRequests = $request->get('get_sent_requests');
            $getReceivedRequests = $request->get('get_received_requests');
            $getConnection = $request->get('get_connection');
            $responseArray = ['success' => true];
            //------------ Get users list of suggestion  ------------
            if (isset($getSuggestions)) {
                //------------ Get users id of alreay sent, Received and connected requests  ------------
                $userConnectionIds = User::getUserIds([1, 2], $userId, $userId);
                $userSuggestionList = User::select('id', 'name', 'email')->WhereNotIn('id', $userConnectionIds)
                    ->orderby('name')->paginate(10);
                $responseArray['userSuggestionList'] = $userSuggestionList;
            }
            //------------ Get Users list of sent request  ------------
            if (isset($getSentRequests)) {
                $sentRequestUserIds = User::getUserIds([1], $userId);
                $userSentRequestList = User::select('id', 'name', 'email')->WhereIn('id', $sentRequestUserIds)
                    ->where('id', '<>', $userId)->orderby('name')->paginate(2);
                $responseArray['userSentRequestList'] = $userSentRequestList;
            }
            //------------ Get Users list of Received request  ------------
            if (isset($getReceivedRequests)) {
                $receivedRequestUserIds = User::getUserIds([1], '', $userId);
                $userReceivedRequestList = User::select('id', 'name', 'email')->WhereIn('id', $receivedRequestUserIds)
                    ->where('id', '<>', $userId)->orderby('name')->paginate(2);
                $responseArray['userReceivedRequestList'] = $userReceivedRequestList;
            }

            //------------ Get Users list of connections  ------------
            if (isset($getConnection)) {
                $connectedUserIds = User::getUserIds([2], $userId, $userId);
                $userConnectionList = User::leftJoin('connections', function ($joinCondition) use ($connectedUserIds) {
                    $joinCondition->on('requester_user_id', '=', 'users.id')->where('status', '=', 2)
                        ->whereIn('requester_user_id', $connectedUserIds);
                    $joinCondition->orOn('recipient_user_id', '=', 'users.id')->where('status', '=', 2)
                        ->whereIn('recipient_user_id', $connectedUserIds);
                })->select('users.id', 'name', 'email',
                    DB::raw('count(connections.id) as commonconnections')
                )->where('users.id', '<>', $userId)
                    ->whereIn('users.id', $connectedUserIds)
                    ->groupBy('users.id')->paginate(5);
                $responseArray['userConnectionList'] = $userConnectionList;
            }
        } catch (\Exception $e) {
            Log::info('Error occured in Method:index of controller:ConnectionController' . $e->getLine() . " With error $e");
            $responseArray = ['success' => false, 'userList' => []];
        }
        $response = Response::json($responseArray, 200);
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
