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
    static PATH = 0;
    static RECT = 1;
    static TEXT = 2;
    static SELECT = 3;

    constructor(){
        this.status = null;
        
        ////////////////
        //  DOM List  //
        ////////////////


        // Wrap
        this.contents = document.querySelector("#contents");

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

        document.querySelector("#path-btn").addEventListener("click", e => this.changeStatus(e.target, App.PATH));
        
        document.querySelector("#rect-btn").addEventListener("click", e => this.changeStatus(e.target, App.RECT));

        document.querySelector("#text-btn").addEventListener("click", e => this.changeStatus(e.target, App.TEXT));

        document.querySelector("#select-btn").addEventListener("click", e => this.changeStatus(e.target, App.SELECT));
        
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
                let track = this.trackList.find(x => x.id === id);
                
                if(!track){
                    track = new Track(id, this.track, this);
                    this.trackList.push(track);   
                }

                this.viewport.setVideo(track);

                this.track.innerHTML = "";
                this.track.prepend(track.html);
                track.width = track.html.offsetWidth;
            });
        });
    }

    changeStatus(target, status){
        this.status = status;

        const exist = document.querySelector("#join-festival .btn-bar .btn.active");
        if(exist) exist.classList.remove("active");

        target.classList.add("active");
    }
}


window.addEventListener("load", () => {
    const app = new App();
});