<?php

function gen($length) {
    $chars = 'abcdefghijklmnopqrstuvwxyz';

    return substr( str_shuffle( $chars ), 0, $length );
}

function generateUID() {
    // ex: awe-msub-ore
    return gen(3) . '-' . gen(4) . '-' . gen(3);
}

function rand_string( $length ) {
    $chars = "+=-)(*&^%$#@!abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    return substr( str_shuffle( $chars ), 0, $length );
}

function generateInviteCode($length=5) {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    return substr( str_shuffle( $chars ), 0, $length );
}

function generateDiscountCode($length=8) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    return substr( str_shuffle( $chars ), 0, $length );
}

function createZip($files, $zipFile, $now)
{
    try {
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
            return ['status' => -1, 'message' => "Unable to open file."];
        }

        for($i=0; $i< count($files); $i++)
        {
            $file = $files[$i];
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $zip->addFile($file, ($now+$i).'.'.$extension);
        }

        $zip->close();
        return ['status' => 1, 'file' => $zipFile];
    } catch (\Exception $e) {
        return ['status' => '-1', 'message' => $e->getMessage()];
    }
}
function logError($error)
{
  $now = time();
  $today = date('Y-m-d', $now);
  $time = date('H:i:s', $now);
    
  $line = $error->getLine();
  $message = $error->getMessage();
  $errorFile = $error->getFile();
  $content = "[$time] $line: $message IN FILE $errorFile \r\n";

  file_put_contents(base_path("/logs/errors/$today.log"), $content, FILE_APPEND | LOCK_EX);
}

function unzipFiles($files, $path)
{
    $zip = new ZipArchive();
    if ($zip->open($files) === TRUE) {
        $zip->extractTo($path);
        $zip->close();
        return ['status' => 1];
    } else {
        return ['status' => -1, 'message' => 'failed to extract zip file'];
    }
}

function api_call($method, $url, $data=null, $withHeader=false)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    if ($withHeader) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Content-Length: ' . strlen($data)]);
    }
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    $out = curl_exec($ch);
    curl_close($ch);    
    if (!$out || $out == null) {
        return curl_error($ch);
    }
    return json_decode($out, true);
}