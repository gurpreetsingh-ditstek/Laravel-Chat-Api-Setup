<?php

use Illuminate\Support\Facades\Storage;
use App\Models\Message;

function prepareApiResponse($message, $code, $data = array())
{
    return array("message" => $message, "status" => $code, "data" => $data);
}

function uploadFiles($data, $file_name = "image", $path = "public/photos", $type = 0)
{
    if ($type) { //In case where direct file coming
        $path = Storage::putFile($path, $data);
    } else {
        $path = Storage::putFile($path, $data->file($file_name));
    }

    return $path;
}

function generateUniqueCodeForMessage()
{
    $code = rand(10000, 99999);
    $countForExistingCode = Message::where("local_message_id", $code)->count();
    if ($countForExistingCode) {
        generateUniqueCodeForMessage();
    }
    return $code;
}

?>