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

        "app_environment" => "dev", #options:("dev", "prod", "stage")

        "session_driver" => "native",  #options:("native", "db", "redis")
        
        "encryption_scheme" => "mcrypt", #options:("mcrypt", "bcrypt")

        "app_paths" => array (

            'base' => __DIR__.'/../',
            
            'public' => __DIR__.'/../public',

            'storage' =>  __DIR__.'/../storage',

            'packages' => __DIR__.'/../packages',

            'views' => __DIR__.'/../views',

        ),

        "app_auth" => array (
 
             
        ),

        "app_cookies" => array(
             
            'secure' => false,

            'server_only' => true,

            'domain_factor' => '.collegemobile.net',

            'max_life' =>  24000
        ),

        "app_uploads" => array(

             'temp_upload_dir'=> NULL,

             'uploads_enabled'=> true,

             'can_extract_zip'=> true,

             'max_upload_size'=> 100000 /* 10MB */
        ),

        "app_mails_transfer" => array(

              'protocol' => "SMTP",
              
              'mail_server' => "mail.collegemobile.net",
              
              'username' => "********",
              
              'password' => "********"
        ),

        "image_processing_enabled" => false #options:(true, false)
    );

?>