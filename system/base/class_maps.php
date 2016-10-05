<?php

$compDir = basename(__DIR__);
$compDir = "/" .  $compDir;


return array(

     /* Core */

     "\\Providers\\Core\\App" => $compDir . "/providers/Core/App",
	"\\Providers\\Core\\HTTPResolver" => $compDir . "/providers/Core/HTTPResolver",
     "\\Providers\\Core\\SessionManager" => $compDir . "/providers/Core/SessionManager",
     "\\Providers\\Core\\RequestManager" => $compDir . "/providers/Core/RequestManager",
     "\\Providers\\Core\\QueryBuilder" => $compDir . "/providers/Core/QueryBuilder",
     
     /* Services */

     "\\Providers\\Services\\DBService" => $compDir . "/providers/Services/DBService",
     "\\Providers\\Services\\EnvService" => $compDir . "/providers/Services/EnvService",
	"\\Providers\\Services\\NativeSessionService" => $compDir . "/providers/Services/NativeSessionService",
     "\\Providers\\Services\\RedisSessionSerivce" => $compDir . "/providers/Services/RedisSessionSerivce",
	"\\Providers\\Services\\CacheService" => $compDir . "/providers/Services/CacheService",

     /* Tools */

     "\\Providers\\Tools\\ArgvInput" => $compDir . "/providers/Tools/ArgvInput",
     "\\Providers\\Tools\\Encrypter" => $compDir . "/providers/Tools/Encrypter",
     "\\Providers\\Tools\\ArgvOutput" => $compDir . "/providers/Tools/ArgvOutput",
     "\\Providers\\Tools\\Console" => $compDir . "/providers/Tools/Console",
     "\\Providers\\Tools\\InputFilter" => $compDir . "/providers/Tools/InputFilter",
     "\\Providers\\Tools\\TemplateRunner" => $compDir . "/providers/Tools/TemplateRunner",
        
     /* Policies */

     "\\Contracts\\Policies\\CacheAccessInterface" => $compDir . "/contracts/policies/Cache/CacheAccessInterface",
     "\\Contracts\\Policies\\DBAccessInterface" => $compDir . "/contracts/policies/DB/DBAccessInterface",
     "\\Contracts\\Policies\\SessionAccessInterface" => $compDir . "/contracts/policies/Session/SessionAccessInterface",
		
     /* Components */

     "Router" => $compDir . "/components/Router",
	"System" => $compDir . "/components/System",
     "Session" => $compDir . "/components/Session",
     "Auth" => $compDir . "/components/Auth",
     "File" => $compDir . "/components/File",
     "Request" => $compDir . "/components/Request",
     "Validator" => $compDir . "/components/Validator",
     "Response" => $compDir . "/components/Response",
     "Logger" => $compDir . "/components/Logger",

     /* Models */

     "Model" => $compDir . "/../../models/Model",
     "User" => $compDir . "/../../models/User",
     "LearnerData" => $compDir . "/../../models/LearnerData",

     /* Controllers */

     "Controller" => $compDir . "/../../controllers/Controller",
     "Login" => $compDir . "/../../controllers/Login",
     "Register" => $compDir . "/../../controllers/Register",
     "Scorm" => $compDir . "/../../controllers/Scorm",
     "Learn" => $compDir . "/../../controllers/Learn",
     "Teach" => $compDir . "/../../controllers/Teach"
	 

);


?>