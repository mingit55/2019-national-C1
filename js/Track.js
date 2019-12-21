class Track {
    constructor(id, track, app){
        this.id = id;
        this.app = app;
        this.clipList = [];
        this.cursorMove = false;
        this.clipIndex = 1;

        this.dragClip = null;

        this.videoDuration = null;
    
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
            if(e.which !== 1) return false;
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
        this.listHtml.prepend(clip.t_root);

        this.clipList.push(clip);
    }

    removeClip(clip){
        let idx = this.clipList.findIndex(x => x === clip);
        this.clipList.splice(idx, 1);

        clip.root.remove();
        clip.t_root.remove();
    }

    seekCursor(x){
        if(typeof x !== "number") return;
        this.cursor.style.left = x + "px";
    }

    disableClips(){
        this.clipList.forEach(x => x.root.style.pointerEvents = "none");
    }

    enableClips(){
        this.clipList.forEach(x => x.root.style.pointerEvents = "all");
    }

    reset(){
        this.clipList.forEach(clip => {
            clip.root.remove();
            clip.t_root.remove();
        });
        this.clipList = [];
        this.clipIndex = 1;
    }

    removeSelection(){
        let findIdx = this.clipList.findIndex(x => x.active);
        
        if(findIdx < 0) return false;
        
        let rm = this.clipList[findIdx];
        rm.root.remove();
        rm.t_root.remove();

        this.clipList.splice(findIdx, 1);
    }

    dragStart(clip){
        this.dragClip = clip;
    }

    swapClip(dropped){
        console.log(this.dragClip.t_root, dropped);
        let dropClip = this.clipList.find(x => x.id == dropped.dataset.id);

        if(this.dragClip === dropClip) return false;
        if(this.dragClip === null) return false;
    
        // 아이디 변경
        let temp = dropClip.id;
        dropClip.id = this.dragClip.id;
        this.dragClip.id = temp;

        // id와 연관되는 값 변경
        dropClip.root.style.zIndex = dropClip.id;
        dropClip.root.id = "clip-"+dropClip.id;
        dropClip.t_root.dataset.id = dropClip.id;

        this.dragClip.root.style.zIndex = this.dragClip.id;
        this.dragClip.root.id = "clip-"+this.dragClip.id;
        this.dragClip.t_root.dataset.id = this.dragClip.id;


        // DOM 위치 변경
        let d_next = dropped.nextElementSibling;
        if(this.dragClip.t_root === d_next) d_next = dropped;

        this.listHtml.insertBefore(dropped, this.dragClip.t_root);
        this.listHtml.insertBefore(this.dragClip.t_root, d_next);


        // 배열 내 위치 변경
        this.clipList[dropClip.id - 1] = dropClip;
        this.clipList[this.dragClip.id - 1] = this.dragClip;

        this.dragClip = null;
    }
}