<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\{Message, UserRequest};
use DB;
use Auth;
use Carbon\Carbon;
use Exception;

class MessageService
{
    protected $success;
    protected $failure;
    protected $obj;
    protected $alphabet;

    public function __construct($obj)
    {
        $this->obj = $obj;
        $this->success = Response::HTTP_OK;
        $this->failure = Response::HTTP_BAD_REQUEST;
    }

    public function chatHistory($request)
    {
        $validator = Validator::make($request->all(), [
            "user_request_id" => "required|integer|exists:user_requests,id",
        ]);
        if ($validator->fails()) {
            return prepareApiResponse($validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }
        try {
            $data = Message::where('local_message_id', $request->local_message_id)->where(function ($q) {
                $q->where('sender_id', Auth::id())->orWhere('receiver_id', Auth::id());
            })->select('id', 'local_message_id', 'user_request_id', 'sender_id', 'receiver_id', 'attachment', 'message', 'type', 'is_read', 'created_at')->orderBy('id', 'DESC')->paginate(10);

            $data->append(['sender_name', 'receiver_name', 'file_url', 'sender_image', 'receiver_image', 'message_status']);

            if (count($data) > 0) {
                Message::where('user_request_id', $request->user_request_id)->where('receiver_id', Auth::id())->where('local_message_id', $request->local_message_id)->update(['is_read' => '1']);
                $message = trans("messages.success");
            } else {
                $message = trans("messages.noRecordFound");
            }
            return prepareApiResponse($message, $this->success, $data);
        } catch (Exception $ex) {
            return prepareApiResponse($ex->getMessage(), $this->success);
        }
    }

    public function sendMessage($request)
    {
        $validator = Validator::make($request->all(), [
            "user_request_id" => "required|integer|exists:user_requests,id",
            "type" => "required"
        ]);
        if ($validator->fails()) {
            return prepareApiResponse($validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }
        $sender_id = Auth::id();
        $receiver_id = $request->user_id;

        try {
            $data = [];
            if ($request->type == 'text') {
                $data[0]['user_request_id'] = $request->user_request_id;
                $data[0]['sender_id'] = $sender_id;
                $data[0]['receiver_id'] = $receiver_id;
                $data[0]['attachment'] = null;
                $data[0]['message'] = $request->message;
                $data[0]['type'] = 'text';
            } else {
                if ($request->hasFile('attachment')) {
                    foreach ($request->attachment as $key => $file) {
                        $path = uploadFiles($file, 'document', 'public/message', 1);
                        $data[$key]['user_request_id'] = $request->user_request_id;
                        $data[$key]['sender_id'] = $sender_id;
                        $data[$key]['receiver_id'] = $receiver_id;
                        $data[$key]['attachment'] = $path;
                        $data[$key]['message'] = null;
                        $data[$key]['type'] = $request->type;
                    }
                }
            }
            $local_message_id = $request->local_message_id;
            if($request->local_message_id == '' || !isset($request->local_message_id)) {
                $local_message_id = generateUniqueCodeForMessage();
            }

            $result = [];
            foreach ($data as $key => $item) {
                $message = new Message();
                $message->local_message_id = $local_message_id;
                $message->user_request_id = $item['user_request_id'];
                $message->sender_id = $item['sender_id'];
                $message->receiver_id = $item['receiver_id'];
                $message->attachment = $item['attachment'];
                $message->message = $item['message'];
                $message->type = $item['type'];
                $message->save();
                $result[] = $message->id;
            }
            $response = Message::whereIn('id', $result)->get(['id', 'local_message_id', 'user_request_id', 'sender_id', 'receiver_id', 'attachment', 'message', 'type','created_at']);
            $response->append(['sender_name', 'receiver_name', 'file_url', 'sender_image', 'receiver_image', 'message_status']);
            if ($message) {
                return prepareApiResponse(trans("messages.messageSaved"), $this->success, $response);
            }
        } catch (Exception $ex) {
            return prepareApiResponse($ex->getMessage(), $this->success);
        }
    }

    public function deleteMessage($request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required|integer|exists:messages,id",
        ]);
        if ($validator->fails()) {
            return prepareApiResponse($validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }
        try {
            $message = Message::find($request->id);
            $message->delete();
            return prepareApiResponse(trans("messages.messageDeleted"), $this->success, $message);
        } catch (Exception $ex) {
            return prepareApiResponse($ex->getMessage(), $this->success);
        }
    }

    public function messageListing()
    {
        try {
            $data = Message::select('local_message_id', 'user_request_id', 'created_at', 'updated_at', DB::raw('MAX(id) AS id'))
            ->with('UserRequest')
            ->where(function ($q) {
                $q->where('sender_id', Auth::id())->orWhere('receiver_id', Auth::id());
            })
            ->groupBy('local_message_id')
            ->orderBy('id', 'DESC')
            ->paginate(10);
            $data->getCollection()->transform(function ($value) {
                return [
                    'id' => $value->id,
                    'local_message_id' => $value->local_message_id,
                    'request_id' => $value->user_request_id,
                    'treatment_title' => $value->treatment,
                    'posted_on' => ($value->UserRequest != null) ? $value->UserRequest->posted_on != null ? $value->UserRequest->posted_on : 'N/A': 'N/A',
                    'user_request_id' => ($value->UserRequest != null) ? $value->UserRequest->unique_code:'N/A',
                    'created_at' => Carbon::parse($value->updated_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($value->updated_at)->format('Y-m-d H:i:s'),
                    'count' => Message::where('local_message_id', $value->local_message_id)->where('receiver_id', Auth::id())->where('is_read','0')->count(),
                ];
            });
            return prepareApiResponse(trans("messages.messageListing"), $this->success, $data);
        } catch (Exception $ex) {
            return prepareApiResponse($ex->getMessage(), $this->success);
        }
    }
}