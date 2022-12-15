<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Services\MessageService;

class MessageController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new MessageService(new Message);
    }

    public function chatHistory(Request $request)
    {
        $response = $this->service->chatHistory($request);
        return response()->send($response['message'], $response['status'], $response['data']);
    }

    public function sendMessage(Request $request)
    {
        $response = $this->service->sendMessage($request);
        return response()->send($response['message'], $response['status'], $response['data']);
    }

    public function deleteMessage(Request $request)
    {
        $response = $this->service->deleteMessage($request);
        return response()->send($response['message'], $response['status'], $response['data']);
    }

    public function messageListing(Request $request)
    {
        $response = $this->service->messageListing($request);
        return response()->send($response['message'], $response['status'], $response['data']);
    }
}
