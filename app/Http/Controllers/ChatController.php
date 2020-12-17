<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Contact;
use Illuminate\Support\Facades\Auth;
use App\ChatRoom;
use App\Events\NewGroup;

class ChatController extends Controller
{
    public function getUserList()
    {
        $users = User::all();
        return response()->json([
            'users' => $users
        ],200);
    }

    public function getContactList()
    {   
        $userId = Auth::user()->id;
        //
        $contactUsers = Contact::where([
            [ 'userId', '=', $userId],
            [ 'contactUserId', '<>', null],
        ])->with('user')->orderBy('created_at','desc')->get();
        $chatGroups = Contact::where([
            [ 'userId', '=', $userId],
            [ 'roomId', '<>', null],
        ])->with('roomId.user')->orderBy('created_at','desc')->get();
        return response()->json([
            'contactUsers' => $contactUsers,
            'chatGroups' => $chatGroups,
        ],200);
    }
    
    public function postContactList(Request $request){
        $userId = Auth::user()->id;
        $contactUserId = $request->contactId;

        $contactList = Contact::where('userId', $userId)->where('contactUserId', $request->contactId)->first();
        // $contactList = Contact::where(function($query) {
        //     $query->where('userId', $userId);
        //     $query->where('contactUserId', $request->contactId);
        // })->get();

        if (isset($contactList)) {
            return response()->json([
                'addedToContactUser' => $contactUserId
            ], 409);
        }
        
        $contactInfo['userId'] = $userId;
        $contactInfo['contactUserId'] = $contactUserId;
        $contact = Contact::create($contactInfo);
        $contact = Contact::where('contactUserId',$contactUserId)->with('user')->get();
        return response()->json([
            'addedToContactUser' => $contact
        ],200);

    }

    public function addNewMessageCount(Request $request)
    {
        $updateItem = $request->new_msg_count;
        $userId = Auth::user()->id;
        $contactUserId = $updateItem['contactUserId'];
        if($contactUserId !== null){
            $newCount = $updateItem['new_msg_count'];
            Contact::where('userId', $userId)->where('contactUserId', $contactUserId)->update(array('new_msg_count' => $newCount ));
        }
        else{
            $roomId = $updateItem['roomId'];
            $newCount = $updateItem['new_msg_count'];
            Contact::where('userId', $userId)->where('roomId', $roomId)->update(array('new_msg_count' => $newCount ));
        }
    }

    public function leavegroup(Request $request)
    {
        $roomId = $request->roomId;
        $userId = Auth::user()->id;
        Contact::where([
            ['userId', '=', $userId],
            ['roomId', '=', $roomId]
        ])->delete();

        $chatRoomInfo = ChatRoom::where('id', $roomId)->first();
        $createdUserId = $chatRoomInfo['userId'];
        if($userId == $createdUserId){
            return response()->json([
                'roomId' => $chatRoomInfo->id,
                'msg' => 1
            ],200);
        }
        else{
            $invitedArr = $chatRoomInfo['invited'];
            // $invitedArr = $chatRoomInfo['invited'];
            foreach ($invitedArr as $key => $invitedUserId){
                if($invitedUserId == $userId){
                    unset($invitedArr[$key]);
                }
            }
            
            // if (($key = array_search($userId, $invitedArr)) !== false) {
            //     unset($invitedArr[$key]);
            // }
            if( sizeof($invitedArr) == 0){
                ChatRoom::where('id', $roomId)->delete();
            }
            else{
                $chatRoomInfo->invited = $invitedArr;
                $chatRoomInfo->save();
            }
            return response()->json([
                'msg' => 1,
                'roomId' => $chatRoomInfo->id,
            ],200);
        }
    }

    public function removeGroup(Request $request)
    {
        $roomId = $request->roomId;
        $userId = Auth::user()->id;

        $chatGroup = json_encode(array(
            "room_id" => array(
               "invited" => null,
            ),
            "roomId" => $roomId,
       ));

       $chatGroup = json_decode($chatGroup);
        //broadcast Event
        broadcast(new NewGroup($chatGroup))->toOthers();

        ChatRoom::where([
            ['id', '=', $roomId],
            ['userId', '=', $userId]
        ])->delete();

        return response()->json([
            'msg' => 1,
        ],200);
    }

    public function removeUser(Request $request)
    {
        $requestedRemoveUserId = $request->userId;
        $userId = Auth::user()->id;

        Contact::where([
            ['userId', '=', $userId],
            ['contactUserId', '=', $requestedRemoveUserId]
        ])->delete();

        return response()->json([
            'msg' => 1,
        ],200);
    }
}
