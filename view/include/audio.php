<div class="row main-video" style="padding: 10px;" id="mvideo">
    <div class="col-xs-12 col-sm-12 col-lg-2 firstC"></div>
    <div class="col-xs-12 col-sm-12 col-lg-8 secC">
        <div id="videoContainer">
        <?php
            $waveSurferEnabled = YouPHPTubePlugin::getObjectDataIfEnabled("CustomizeAdvanced");
            if($waveSurferEnabled==false){
                $waveSurferEnabled = true;
            } else {
                $waveSurferEnabled = $waveSurferEnabled->EnableWavesurfer;
            }
            if($video['type']!="audio"){
                $waveSurferEnabled = false;
            }
            $poster = $global['webSiteRootURL']."view/img/recorder.gif";
            if(file_exists($global['systemRootPath']."videos/".$video['filename'].".jpg")){
               $poster = $global['webSiteRootURL']."videos/".$video['filename'].".jpg"; 
            }
        ?>
        <audio controls class="center-block video-js vjs-default-skin " <?php if($waveSurferEnabled==false){ ?> autoplay data-setup='{"controls": true}' <?php } ?> id="mainAudio" poster="<?php echo $poster; ?>">
            <?php
            $ext = "";
	if($video['type']=="audio"){ 
            if(file_exists($global['systemRootPath']."videos/".$video['filename'].".ogg")){ 
                    $ext = ".ogg";
                } else {
                    $ext = ".mp3";
                }
	}
        if($waveSurferEnabled==false){
           if($video['type']=="audio"){ 
                // usual audio-type
                $sourceLink = $global['webSiteRootURL']; ?>videos/<?php echo $video['filename'].$ext;
           } else {  
                // linkVideo-type
                $sourceLink = $video['videoLink']; 
             } ?>
                <source src="<?php echo $sourceLink;?>" /> 
                <a href="<?php echo $sourceLink; ?>">horse</a>     
        <?php } ?>
        </audio>
        </div>
    </div>
    <script>
        <?php $_GET['isMediaPlaySite'] = $video['id']; ?>
        var mediaId = <?php echo $video['id']; ?>;
        $(document).ready(function () {
            
            $(".vjs-big-play-button").hide();
            $(".vjs-control-bar").css("opacity: 1; visibility: visible;");
            <?php 
            if($video['type']=="linkAudio"){
                echo '$("time.duration").hide();';
            }
            if($waveSurferEnabled){ ?>
            player = videojs('mainAudio', {
                controls: true,
                autoplay: true,
                fluid: false,
                loop: false,
                width: 600,
                height: 300,
                plugins: {
                    wavesurfer: {
                        <?php if($video['type']=="audio"){ ?>
                        src: '<?php echo $global['webSiteRootURL'] . "videos/" . $video['filename'].$ext; ?>',
                        <?php } else { ?>
                        src: '<?php echo $video['videoLink']; ?>',
                        <?php }  ?>
                        msDisplayMax: 10,
                        debug: false,
                        waveColor: 'green',
                        progressColor: 'white',
                        cursorColor: 'blue',
                        hideScrollbar: true
                    }
                }
            }, function(){
                // print version information at startup
                videojs.log('Using video.js', videojs.VERSION,'with videojs-wavesurfer', videojs.getPluginVersion('wavesurfer'));
            });
            <?php } else { ?>
                player = videojs('mainAudio');
            <?php } ?>
            // error handling
            player.on('error', function(error) {
                console.warn('VideoJS-ERROR:', error);
            });
            player.on('loadedmetadata', function() {
                fullDuration = player.duration();
            });
            player.ready(function () {
            <?php
                if ($config->getAutoplay()) {
                    echo "setTimeout(function () { if(typeof player === 'undefined'){ player = videojs('mainAudio');}player.play();}, 150);";
                } else {
            ?>
                if (Cookies.get('autoplay') && Cookies.get('autoplay') !== 'false') {
                    setTimeout(function () { if(typeof player === 'undefined'){ player = videojs('mainAudio');} player.play();}, 150);                    
                }
            <?php } ?>
                this.on('ended', function () {
                    console.log("Finish Audio");
    <?php // if autoplay play next video
    if (!empty($autoPlayVideo)) { ?>
        if (Cookies.get('autoplay') && Cookies.get('autoplay') !== 'false') {
            document.location = '<?php echo $autoPlayVideo['url']; ?>';
        }
        <?php } ?>
                });
        }); });
    </script>
    <div class="col-xs-12 col-sm-12 col-lg-2"></div>
</div><!--/row-->
