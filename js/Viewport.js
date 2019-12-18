class Viewport {
    constructor(viewport, app){
        this.app = app;
        this.playId = null;

        this.root = viewport;
        this.emptyMsg = this.root.querySelector(".empty-msg");
        this.video = this.root.querySelector("video");
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
    }
}