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
            $getCommonConnection = $request->get('get_common_connection');
            $responseArray = ['success' => true];
            //------------ Get users list of suggestion  ------------
            if (isset($getSuggestions)) {
                //------------ Get users id of alreay sent, Received and connected requests  ------------
                $userConnectionIds = User::getUserIds([0, 1, 2], $userId, $userId);
                $userSuggestionList = User::select('id', 'name', 'email')->WhereNotIn('id', $userConnectionIds)
                    ->orderby('name')->paginate(10);
                $responseArray['userSuggestionList'] = $userSuggestionList;
            }
            //------------ Get Users list of sent request  ------------
            if (isset($getSentRequests)) {
                $sentRequestUserIds = User::getUserIds([0], $userId);
                $userSentRequestList = User::select('id', 'name', 'email')->WhereIn('id', $sentRequestUserIds)
                    ->where('id', '<>', $userId)->orderby('name')->paginate(10);
                $responseArray['userSentRequestList'] = $userSentRequestList;
            }
            //------------ Get Users list of Received request  ------------
            if (isset($getReceivedRequests)) {
                $receivedRequestUserIds = User::getUserIds([1], '', $userId);
                $userReceivedRequestList = User::select('id', 'name', 'email')->WhereIn('id', $receivedRequestUserIds)
                    ->where('id', '<>', $userId)->orderby('name')->paginate(10);
                $responseArray['userReceivedRequestList'] = $userReceivedRequestList;
            }

            //------------ Get Users list of connections  ------------
            if (isset($getConnection)) {
                $connectedUserIds = User::getUserIds([2], $userId, $userId);
                $userConnectionList = User::select('id', 'name', 'email')->where('users.id', '<>', $userId)
                    ->whereIn('id', $connectedUserIds)->paginate(10);
                $dataArray = $userConnectionList->items();
                foreach ($dataArray as $data_array) {
                    $getCommonConnectionUserIds = User::getCommonConnectionUserIds($userId, $data_array->id, $connectedUserIds);
                    $data_array->commonconnections = count($getCommonConnectionUserIds);
                }

                $responseArray['userConnectionList'] = $userConnectionList;
            }
            //------------ Get common connected Users list  ------------
            if (isset($getCommonConnection)) {
                $connectedUserIds = User::getUserIds([2], $userId, $userId);

                $connectionOfUserId = $request->get('connected_user_id');
                //------- Get list of connected users of common connection user -------
                $getCommonConnectionUserIds = User::getCommonConnectionUserIds($userId, $connectionOfUserId, $connectedUserIds);

                $userCommonConnectionList = User::select('users.id', 'name', 'email')
                    ->whereNotin('users.id', [$userId, $connectionOfUserId])
                    ->whereIn('users.id', $getCommonConnectionUserIds)
                    ->paginate(10);
                $responseArray['userCommonConnectionList'] = $userCommonConnectionList;
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
    //--------------------- Create connection request ---------------------
    public function create(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $recipientUserId = $request->get('recipient_user_id');
            $newRequest = [
                'requester_user_id' => $userId,
                'recipient_user_id' => $recipientUserId
            ];
            Connection::create($newRequest);
            $responseArray = ['success' => true, 'message' => 'Connection Request sent successfully.'];
        } catch (\Exception $e) {
            Log::info('Error occured in Method:create of controller:ConnectionController' . $e->getLine() . " With error $e");
            $responseArray = ['success' => false, 'error' => 'Connection Request not sent, please try again.'];
        }
        $response = Response::json($responseArray, 200);
        return $response;
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
        try {
            $userId = $request->user()->id;
            Connection::where('requester_user_id', $id)->where('recipient_user_id', $userId)
                ->update([
                    'status' => 2
                ]);
            $responseArray = ['success' => true, 'message' => 'Request updated successfully.'];
        } catch (\Exception $e) {
            Log::info('Error occured in Method:update of controller:ConnectionController' . $e->getLine() . " With error $e");
            $responseArray = ['success' => false, 'error' => 'Request not updated, please try again.'];
        }
        $response = Response::json($responseArray, 200);
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        try {
            $userId = $request->user()->id;
            $isRemoveAlreadyConnected = $request->get('is_already_connected');
            //------------------ Remove sent/connected Request Query  ------------------
            $deleteQuery = Connection::Where(function ($query) use ($userId, $id) {
                $query->where('requester_user_id', $userId)->where('recipient_user_id', $id);
            });
            if (isset($isRemoveAlreadyConnected)) {
                $deleteQuery->orWhere(function ($query) use ($userId, $id) {
                    $query->where('requester_user_id', $id)->where('recipient_user_id', $userId);
                });
            }
            $deleteQuery->delete();
            $responseArray = ['success' => true, 'message' => 'Request removed successfully.'];
        } catch (\Exception $e) {
            Log::info('Error occured in Method:destroy of controller:ConnectionController' . $e->getLine() . " With error $e");
            $responseArray = ['success' => false, 'error' => 'Request not removed, please try again.'];
        }
        $response = Response::json($responseArray, 200);
        return $response;
    }

}
