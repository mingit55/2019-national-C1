String.prototype.parseDom = function(){
    let parent = document.createElement("div");
    parent.innerHTML = this;
    return parent.firstChild;
};

// Number.prototype.parseStr = function(){
//     let hour = this / 3600;
//     let min = (this % 3600) / 60;
//     let sec = this % 60;
// }

class App {
    constructor(){

        
        ////////////////
        //  DOM List  //
        ////////////////

        // 트랙
        this.track = document.querySelector("#track");

        // 비디오 UI
        this.videoTime = document.querySelector("#v-ui .video-info .current");
        this.videoDuration = document.querySelector("#v-ui .video-info .duration");
        this.clipStart = document.querySelector("#v-ui .clip-info .start");
        this.clipDuration = document.querySelector("#v-ui .clip-info .duration");

        
        this.init();
        this.addEvent();
    }

    init(){
        this.viewport = new Viewport(document.querySelector("#viewport"), this);
        this.trackList = [];
    }

    addEvent(){

        //////////////
        // Tool Bar //
        //////////////

        document.querySelector("#path-btn")
        
        document.querySelector("#rect-btn")

        document.querySelector("#text-btn")

        document.querySelector("#select-btn");
        
        document.querySelector("#play-btn").addEventListener("click", () => this.viewport.playVideo());
        
        document.querySelector("#pause-btn").addEventListener("click", () => this.viewport.pauseVideo());

        document.querySelector("#alldel-btn");

        document.querySelector("#seldel-btn");

        document.querySelector("#down-btn");


        ////////////////
        // Video List //
        ////////////////

        document.querySelectorAll("#movie-line").forEach(movie => {
            movie.addEventListener("click", e => {
                let id = e.target.dataset.id;
                this.trackList.push( new Track(this.track) );
                this.viewport.setVideo(id);
            });
        });
    }
}


window.addEventListener("load", () => {
    const app = new App();
});