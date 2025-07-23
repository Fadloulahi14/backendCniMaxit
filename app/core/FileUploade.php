<?php

class FileUploader{
    public function __construct(){

    }

    public function upload($file, $uploadDir){
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $extension;
        $uploadPath = $uploadDir . $fileName;
    }
}