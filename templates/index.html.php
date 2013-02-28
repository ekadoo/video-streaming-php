<html>
  <head>
    <title>Video Streaming Test</title>
    <script type="text/javascript" src="<?php echo BASE_PATH ?>/js/jwplayer/jwplayer.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  </head>
  <body>
    <ul>
       <?php foreach($this->data['files'] as $file) { ?>
         <li><a href="<?php echo BASE_PATH ?>/video/<?php echo $file ?>"><?php echo $file ?></a></li>
       <?php } ?>
    </ul>
   <div id="container" style="background-color: #000000; width: 640px;height: 360px;">
     <div id="video-stream"></div>
   </div>
   <script type="text/javascript">
     function reloadPlayer() {
       jwplayer().remove();
       getPlayer();
     }

     function getPlayer() {
       $.get("<?php echo BASE_PATH ?>/urls/<?php echo $this->data['name'] ?>")
        .done(function(data) {
          jwplayer("video-stream").setup({
            playlist: [{
              sources: [{
                file: data["rtmp"],
              }, {
                file: data["http"]
              }]
            }],
            height: 360,
            width: 640,
            autostart: true,
            primary: "flash",
            events: {
              onComplete: reloadPlayer
            }
          });
        })
    }

    $(document).ready(function() {
      getPlayer();
    });
   </script>
  </body>
</html>
