String.prototype.parseDom = function(){
    let parent = document.createElement("div");
    parent.innerHTML = this;
    return parent.firstChild;
};

Number.prototype.parseTime = function(){
    let int = parseInt(this);
    let msec = (this - int).toFixed(2).substr(2);

    let hour = parseInt(int / 3600);
    let min = parseInt((int % 3600) / 60);
    let sec = int % 60;

    if(hour < 10) hour = "0" + hour;
    if(min < 10) min = "0" + min;
    if(sec < 10) sec = "0" + sec;

    return `${hour}:${min}:${sec}:${msec}`;
}

class App {
    constructor(){

        
        ////////////////
        //  DOM List  //
        ////////////////

        // 트랙
        this.track = document.querySelector("#track");
        
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