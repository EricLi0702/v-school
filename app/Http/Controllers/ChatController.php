<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Contact;
use Illuminate\Support\Facades\Auth;

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
        $contactUsers = Contact::where('userId', $userId)->with('user')->orderBy('created_at','desc')->get();
        return response()->json([
            'contactUsers' => $contactUsers
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
        $newCount = $updateItem['new_msg_count'];
        Contact::where('userId', $userId)->where('contactUserId', $contactUserId)->update(array('new_msg_count' => $newCount ));
    }
}
