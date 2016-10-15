<?php   

  /*!------------------------------------------------------
    ! 
    ! This is the config section for all ENV related settings
    !
    !
    !
    !
    ! *
    ! *
    ! *
    ! *
    ! *
    ! *
    --------------------------------------------------------*/


    return array (

        "app_environment" => "dev", #options: ("dev", "prod")

        "session_driver" => "native",  #options: ("native", "redis")

        "cache_driver" => "memcached", #options: ("memcached", "apc")

        "mail_driver" => "", #options: ()

        "custom_session_cookie_name" => "_collegemobile", # any alpha-numeric combination of chars
        
        "encryption_scheme" => "mcrypt", #options: ("mcrypt", "bcrypt")

        "app_paths" => array (

            'base' => __DIR__.'/../',
            
            'public' => __DIR__.'/../public',

            'storage' =>  __DIR__.'/../storage',

            'packages' => __DIR__.'/../packages',

            'views' => __DIR__.'/../views',

        ),

        "app_auth" => array (
 
            'auth_model' => 'User'            
        ),

        "app_cookies" => array(
             
            'secure' => false, # {HTTP / HTTPS}

            'server_only' => true, # {httpOnly} flag 

            'domain_factor' => '.collegemobile.net',

            'max_life' =>  246000
        ),

        "app_uploads" => array(

             'temp_upload_dir'=> NULL,

             'uploads_enabled'=> true, #options: (false, true)

             'upload_target' => '#local', #option: ('#s3', '#cloudinary', '#local')

             'can_extract_zip'=> true, #options: (false, true)

             'max_upload_size'=> 100000 /* 10MB */
        ),

        "app_mails" => array(

              'protocol' => "SMTP",
              
              'mail_server' => "mail.collegemobile.net",
              
              'username' => "********",
              
              'password' => "********"
        ),

        "app_connections" => array(

            "raw_tcp_sockets_enabled" => false, #options: (false, true)

            "disallow_ajax" => false #options: (false, true)
        ),

        "image_processing_enabled" => false #options: (true, false)
    );

?>