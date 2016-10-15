<!DOCTYPE html>
<html lang="en">
<head>
   <title>Ooops! - CollegeMobile Application Error</title>
   <meta charset="utf-8">
   <style type="text/css">
      body, html{
      	 font-size:100%;
      	 width:100%;
         height:100%;
         background-color:black;
         color:white;
      }
   </style>
</head>
<body class="error-card">
   <h1 style="font-size:120%;" class="error-title">Error: <span><?php echo $err; ?></span></h1>
   <div class="error-details">
   <p style="font-size:96%;">
      <?php echo $msg; ?>
   </p>
   <em style="font-family:Arial;">
      on <?php echo $line; ?> in <a href="<?php echo $file; ?>"><?php echo $file; ?></a>
   </em>
   </div>
</body>
</html>