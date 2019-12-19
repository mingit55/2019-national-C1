class Track {
    constructor(id, track, app){
        this.id = id;
        this.app = app;
        this.clipList = [];
        this.cursorMove = false;
    
        this.root = track;
        this.html =  `<div>
                            <div class="cursor" draggable="false"></div>
                            <div class="list">
                                <div class="item">
                                    <div class="view-line"></div>
                                </div>
                            </div>
                        </div>`.parseDom();
        this.listHtml = this.html.querySelector(".list");
        this.cursor = this.html.querySelector(".cursor");
        this.width = 0;

        this.addEvent();
    }

    addEvent(){
        this.cursor.addEventListener("dragstart", e => {
            e.preventDefault();
            return false;
        });

        window.addEventListener("mousemove", e => {
            if(!this.cursorMove) return false;
            let x = e.clientX - this.app.contents.offsetLeft;
            x = x < 0 ? 0 : x > this.width ? this.width : x;

            this.seekCursor(x);
            this.app.viewport.seekVideo(x);
        });

        window.addEventListener("mouseup", e => {
            this.cursorMove = false;
        });

        this.cursor.addEventListener("mousedown", e => {
            if(e.which !== 1) this.cursorMove = false;
            else this.cursorMove = true;
        });

        
    }

    pushClip(clip){
        this.listHtml.append(clip.t_root);

        this.clipList.push(clip);
    }

    seekCursor(x){
        if(typeof x !== "number") return;
        this.cursor.style.left = x + "px";
    }
}