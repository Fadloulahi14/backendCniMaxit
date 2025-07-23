<?php
namespace App\core;


 abstract class AbstractController {

 protected $baseLayout = "base";
 
   abstract public function create();
   
   abstract public function store();
   abstract public function edit();
   abstract public function show();
   abstract public function delete();
   abstract public function index();
  

    public function renderIndex(string $view='', array $data=[]){
        // header( "");
        extract($data);
        ob_start();
        require_once '../template/'.$view;
       
        $contentForLayout = ob_get_clean();
        require_once '../template/layout/'.$this->baseLayout.'.layout.php';
       



       
 
   }



}
