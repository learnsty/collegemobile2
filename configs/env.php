<?php   

  /*!------------------------------------------------------
    ! 
    ! This is the config section for all ENV related settings
    !
    !
    !
    !
    ! * Vyke (c) Copyright 2016
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/


    return array (

        "app_environment" => "dev", #options: ("dev", "prod")

        "session_driver" => "native",  #options: ("native", "redis")

        "custom_session_cookie_name" => "LEARNSTY_SESS_ID", # ...
        
        "encryption_scheme" => "mcrypt", #options: ("mcrypt", "bcrypt")

        "app_paths" => array (

            'base' => __DIR__ . '/../',
            
            'public' => __DIR__ . '/../public',

            'storage' =>  __DIR__ . '/../storage',

            'packages' => __DIR__ . '/../packages',

            'views' => __DIR__ . '/../views'

        ),

        "app_cache" => array(

             'cache_driver' => "memcached", #options: ("memcached", "apc")

             'host' => '127.0.0.1',

             'port' => 11211

        ),     

        "app_auth" => array (
 
            'auth_model' => 'User',

            'jwt_enabled' => true,

            'throttle_enabled' => true,

            'guest_routes' => array( // These routes can be accessed only if the user is not logged in.
                 '/',
                 '/account/activate',
                 '/account/forgotpassword',
                 '/account/resetpassword',
                 '/login',
                 '/register/@category'
            )          
        ),

        "app_cookies" => array(
             
            'secure' => false, # {HTTP / HTTPS}

            'server_only' => true, # {httpOnly} flag 

            'domain_factor' => '.collegemobile.net', # {domain} value

            'max_life' =>  246000 # {expires} value
        ),

        "app_uploads" => array(

            'temp_upload_dir'=> NULL,

            'uploads_enabled'=> true, #options: (false, true)

            'upload_target' => array( #option: ('#s3', '#cloudinary', '#local')
                    
                'disk_id'=> "#local",

                'secret_key' => "nil",

                'region_name' => "nil",

                'bucket_name' => "nil"
            ), 

            'can_extract_zip'=> true, #options: (false, true)

            'max_upload_size'=> 100000 /* 10MB */
        ),

        "app_mails" => array(

              'mail_driver' => "native", #options: ()

              'protocol' => "SMTP", #options: ("SMTP", "SMTP=POP3")

              'encryption' => true,

              'encryption_type' => 'tls',
              
              'mail_server' => "smtp.mailgun.org",
              
              'username' => "********",
              
              'password' => "********",

              'port' => 587
        ),

        "app_connections" => array(

            "raw_tcp_sockets_enabled" => false, #options: (false, true)

            "text_event_stream_enabled" => true,

            "disallow_ajax" => false #options: (false, true)
        ),

        "image_processing_enabled" => false #options: (true, false)

    );

?>