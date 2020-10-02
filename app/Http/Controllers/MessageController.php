<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Events\NewMessage;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::where(function($query) {
            $query->where('from', request('from'));
            $query->where('to', request('to'));
        })->orWhere(function($query) {
            $query->where('from', request('to'));
            $query->where('to', request('from'));
        })->get();

        return response()->json([
            'messages'=> $messages->load('from','to')
        ],200);
    }

    public function store(){
        request()->validate([
            'from' => 'required',
            'to' => 'required',
            'text' => 'required',
        ]);
        $data = request(['from','to','text']);
        $message = Message::create($data);

        //broadcast Event
        broadcast(new NewMessage($message->load('from')))->toOthers();

        return response()->json([
            'message' => $message->load('from')
        ], 201);
    }

    public function storeChatSendImage(Request $request)
    {   
        request()->validate([
            'from' => 'required',
            'to' => 'required',
            'file' => 'required|mimes:jpeg,png,jpg,bmp|max:4096',
        ]);
        $file = $request->file;
        $randomNum = mt_rand(1000000000, 9999999999);
        $chatSendImageName = $randomNum.time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/chatItem/images'),$chatSendImageName);
        
        $path = '/uploads/chatItem/images/'.$chatSendImageName;
        $messageImage['from'] = $request->from;
        $messageImage['to'] = $request->to;
        $messageImage['image'] = $path;

        $message = Message::create($messageImage);

        broadcast(new NewMessage($message->load('from')))->toOthers();

        return response()->json([
            'message' => $message->load('from')
        ], 201);
    }

    public function storeChatSendVoice(Request $request)
    {   
        request()->validate([
            'from' => 'required',
            'to' => 'required',
            'voice' => 'required',
        ]);

        $randomNum = mt_rand(1000000000, 9999999999);
        $chatSendVoiceName = $randomNum.time().'.wav';
        $request->voice->move(public_path('uploads/chatItem/voices'),$chatSendVoiceName);

        $path = '/uploads/chatItem/voices/'.$chatSendVoiceName;

        $messageVoice['from'] = $request->from;
        $messageVoice['to'] = $request->to;
        $messageVoice['voice'] = $path;

        $message = Message::create($messageVoice);

        broadcast(new NewMessage($message->load('from')))->toOthers();

        return response()->json([
            'message' => $message->load('from')
        ], 201);
    }
    public function storeChatSendVideo(Request $request)
    {   
        request()->validate([
            'from' => 'required',
            'to' => 'required',
            'file' => 'required|mimes:mp4|max:524288',
        ]);
        $file = $request->file;
        $randomNum = mt_rand(1000000000, 9999999999);
        $chatSendVideoName = $randomNum.time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/chatItem/videos'),$chatSendVideoName);
        
        $path = '/uploads/chatItem/videos/'.$chatSendVideoName;

        $messageVideo['from'] = $request->from;
        $messageVideo['to'] = $request->to;
        $messageVideo['video'] = $path;

        $message = Message::create($messageVideo);

        broadcast(new NewMessage($message->load('from')))->toOthers();

        return response()->json([
            'message' => $message->load('from')
        ], 201);
    }

    public function storeChatSendFile(Request $request)
    {   
        request()->validate([
            'from' => 'required',
            'to' => 'required',
        ]);

        //if file is video or image...
            //get file extention
        $extentionOfFile = $request->file->extension();
        if( 
            $extentionOfFile == "png" || 
            $extentionOfFile == "jpg" || 
            $extentionOfFile == "jpeg"){

                $file = $request->file;
                $randomNum = mt_rand(1000000000, 9999999999);
                $chatSendImageName = $randomNum.time().'.'.$request->file->extension();
                $request->file->move(public_path('uploads/chatItem/images'),$chatSendImageName);
                
                $path = '/uploads/chatItem/images/'.$chatSendImageName;
                $messageImage['from'] = $request->from;
                $messageImage['to'] = $request->to;
                $messageImage['image'] = $path;

                $message = Message::create($messageImage);

                broadcast(new NewMessage($message->load('from')))->toOthers();

                return response()->json([
                    'message' => $message->load('from')
                ], 201);

            }

        elseif( $extentionOfFile == "mp4"){
            $file = $request->file;
            $randomNum = mt_rand(1000000000, 9999999999);
            $chatSendVideoName = $randomNum.time().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/chatItem/videos'),$chatSendVideoName);
            
            $path = '/uploads/chatItem/videos/'.$chatSendVideoName;
            $messageVideo['from'] = $request->from;
            $messageVideo['to'] = $request->to;
            $messageVideo['video'] = $path;

            $message = Message::create($messageVideo);

            broadcast(new NewMessage($message->load('from')))->toOthers();

            return response()->json([
                'message' => $message->load('from')
            ], 201);
        }
        else{
            $file = $request->file;
            $fileSize = $file->getSize();
            $fileName = $file->getClientOriginalName();
            $randomNum = mt_rand(1000000000, 9999999999);
            $chatSendFileName = $randomNum.time().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/chatItem/files'),$chatSendFileName);
            
            $filePath = '/uploads/chatItem/files/'.$chatSendFileName;

            $fileInfo = array();
            $fileInfo['name'] = $fileName;
            $fileInfo['size'] = $fileSize;
            $fileInfo['path'] = $filePath;
            $fileInfo = json_encode($fileInfo);

            // $fileField = implode();

            $messageFile['from'] = $request->from;
            $messageFile['to'] = $request->to;
            $messageFile['file'] = $fileInfo;

            $message = Message::create($messageFile);

            broadcast(new NewMessage($message->load('from')))->toOthers();

            return response()->json([
                'message' => $message->load('from')
            ], 201);
        }
    }

    public function storeChatSendMap(Request $request)
    {
        $mapInfo = array();
        $mapInfo['lng'] = $request->lng;
        $mapInfo['lat'] = $request->lat;
        $mapInfo['zoom'] = $request->zoom;
        $mapInfo['address'] = $request->address;
        $mapInfo = json_encode($mapInfo);

        $messageMap['from'] = $request->from;
        $messageMap['to'] = $request->to;
        $messageMap['map'] = $mapInfo;

        $message = Message::create($messageMap);

        broadcast(new NewMessage($message->load('from')))->toOthers();

        return response()->json([
            'message' => $message->load('from')
        ], 201);
    }
}
