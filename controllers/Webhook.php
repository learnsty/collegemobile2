<?php

class Webhook extends Controller { 

       protected $params;

       public function __construct($params){

            parent::__construct($params);
       }

       // @Override

       public  function index($models){

           # code ...
       }

       public function git_payload($models){

            $base_repo_path = "https://raw.githubusercontent.com/learnsty/collegemobile/";

            $base_project_path = $env['app.path.base'];

            $secret = $env['app.key'];

            $payload = Request::input()->getFields();

            $signature = Request::rawHeader('X-Hub-Signature');
			
			      if(!$signature){
               
                 return Response::text("Somethings' not Right!", 401);
            }

            list($algos, $hash) = explode('=', $signature);

            $verified = Helpers::verifyHmac($algos,(json_encode($payload)), $secret, $hash); 

            if(!$verfied){
               
                return Response::text("Somethings' not Right!", 401);
            }

            $commits = $payload['commits'][0]; // git commit details

            foreach($commits['modified'] as $filepath){
                $full_repo_path = $base_repo_path . $filepath;
                $full_project_path = $base_project_path . $filepath;
                $fetch = File::readChunk($full_repo_path);
				        File::write($full_project_path, $fetch, TRUE);
            }

            Response::text(NULL, 204);
                     
       }

}

?>