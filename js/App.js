Date.prototype.parseStr = function(){
    return `${this.getFullYear()}`.substr(2) + (this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : this.getMonth() + 1) + (this.getDate() < 10 ? "0" + this.getDate() : this.getDate());
}

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

function offset(target){
    let left = target.offsetLeft;
    let top = target.offsetTop;

    let parent = target.offsetParent;
    if(parent){
        do {
            left += parent.offsetLeft;
            top += parent.offsetTop;
            parent = parent.offsetParent;
        } while(parent);
    }

    return {left: left, top: top};
}

function fitOffset(target, pageX, pageY, fit = true){
    let os = offset(target);
    let ox = pageX - os.left;
    let oy = pageY - os.top; 

    if(fit){
        ox = ox < 0 ? 0 : ox > target.offsetWidth ? target.offsetWidth : ox;
        oy = oy < 0 ? 0 : oy > target.offsetHeight ? target.offsetHeight : oy;  
    }

    return {x: ox, y: oy};
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

        document.querySelector("#path-btn").addEventListener("click", e => this.viewport.playTrack === null ? alert("비디오를 선택해 주세요!") : this.changeStatus(e.target, App.PATH));
        
        document.querySelector("#rect-btn").addEventListener("click", e => this.viewport.playTrack === null ? alert("비디오를 선택해 주세요!") : this.changeStatus(e.target, App.RECT));

        document.querySelector("#text-btn").addEventListener("click", e => this.viewport.playTrack === null ? alert("비디오를 선택해 주세요!") : this.changeStatus(e.target, App.TEXT));

        document.querySelector("#select-btn").addEventListener("click", e => this.viewport.playTrack === null ? alert("비디오를 선택해 주세요!") : this.changeStatus(e.target, App.SELECT));
        
        document.querySelector("#play-btn").addEventListener("click", () => this.viewport.playTrack === null ? alert("비디오를 선택해 주세요!") : this.viewport.playVideo());
        
        document.querySelector("#pause-btn").addEventListener("click", () => this.viewport.playTrack === null ? alert("비디오를 선택해 주세요!") : this.viewport.pauseVideo());

        document.querySelector("#allDel-btn").addEventListener("click", () => this.viewport.playTrack === null ? alert("비디오를 선택해 주세요!") : this.viewport.playTrack.reset());

        document.querySelector("#selDel-btn").addEventListener("click", () => this.viewport.playTrack === null ? alert("비디오를 선택해 주세요!") : this.viewport.playTrack.removeSelection())

        document.querySelector("#down-btn").addEventListener("click", () => this.viewport.playTrack === null ? alert("비디오를 선택해 주세요!") : this.download())


        ////////////////
        // Video List //
        ////////////////

        document.querySelectorAll("#movie-line img").forEach(movie => {
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

        if(status === App.SELECT) this.viewport.playTrack.enableClips();
        else this.viewport.playTrack.disableClips();
    }

    download(){
        let viewList = [];
        this.viewport.playTrack.clipList.forEach(x => {
            x.diselect();
            let view = null;

            if(x.type === App.PATH){
                view = document.createElement("img");
                view.width = this.viewport.width;
                view.height = this.viewport.height;
                view.src = x.root.toDataURL();
                view.style = x.root.style.cssText;
            }
            else {
                view = document.createElement(x.root.nodeName);
                view.style = x.root.style.cssText;
                view.innerText = x.root.innerText;
            }

            view.classList.add("clip");
            view.style.position = "absolute";
            view.style.pointerEvents = "none";
            view.style.left = view.x + "px";
            view.style.top = view.y + "px";

            view.dataset.start = x.startTime;
            view.dataset.duration = x.duration;
            viewList.push(view.outerHTML);
        });

        let contents = `<!doctype html> 
                        <head>
                            <title>BIFF 부산국제영화제</title>
                        </head>
                        <body>
                            <div id="viewport" style="position: relative; width: ${this.viewport.width}px; height: ${this.viewport.height}px; background-color: #000;">
                                <video src="${this.viewport.video.src}" controls style="position: absolute; left: 0; top: 0; width: 100%; height: 100%;"></video>`;

        viewList.forEach(view => {
            contents += view;
        });

        contents +=         `</div>
                            <script>
                                const video = document.querySelector("#viewport > video");
                                const clipList = document.querySelectorAll("#viewport .clip");
                                
                                function frame(){
                                    requestAnimationFrame(frame);

                                    clipList.forEach(x => {
                                        let start = parseInt(x.dataset.start);
                                        let duration = parseInt(x.dataset.duration);
                                        if(start <= video.currentTime && video.currentTime <= start + duration){
                                            x.style.visibility = "visible";
                                        }
                                        else {
                                            x.style.visibility = "hidden";
                                        }
                                    });
                                }
                                requestAnimationFrame(frame);

                            </script>
                        </body>
                        </html>`;
        let blob = new Blob([contents], {type: "text/html; charset=utf8"});

        let now = new Date();

        let downBtn = document.createElement("a");
        downBtn.href = URL.createObjectURL(blob);
        downBtn.download = "movie-["+ now.parseStr() +"].html";
        
        document.body.append(downBtn);
        downBtn.click();
        downBtn.remove();
    }
}


window.addEventListener("load", () => {
    const app = new App();
});