class Viewport {
    constructor(viewport, app){
        this.app = app;
        this.playId = null;

        this.root = viewport;
        this.emptyMsg = this.root.querySelector(".empty-msg");
        this.video = this.root.querySelector("video");

        // 비디오 UI
        this.videoTime = document.querySelector("#v-ui .video-info .current");
        this.videoDuration = document.querySelector("#v-ui .video-info .duration");

        requestAnimationFrame(() => {
            this.frame();
        });
    }

    frame(){
        requestAnimationFrame(() => {
            this.frame();
        });

        this.videoTime.innerText = this.video.currentTime.parseTime();
    }


    playVideo(){
        this.video.play();
    }

    pauseVideo(){
        this.video.pause();
    }

    setVideo(id){ 
        this.playId = id;
        this.emptyMsg.remove();
        this.video.src = `movies/movie${id}.mp4`;

        this.video.oncanplay = () => {
            this.videoDuration.innerText = this.video.duration.parseTime();
        }
        
        
    }
}