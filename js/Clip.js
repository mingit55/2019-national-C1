class Clip {
    constructor(type, track){
        this.type = type;
        this.track = track;

        // 클립 UI
        this.clipStart = document.querySelector("#v-ui .clip-info .start");
        this.clipDuration = document.querySelector("#v-ui .clip-info .duration");

        // 클립 옵션
        this.clipColor = document.querySelector("#s-color");
        this.clipWidth = document.querySelector("#s-width");
        this.clipFsize = document.querySelector("#s-fsize");

        this.root = null;
        this.ctx = null;
        this.history = [];
        this.x = 0;
        this.y = 0;
        
        this.t_root =  `<div class="item">
                            <div class="view-line"></div>
                        </div>`.parseDom();

        if(type === App.PATH) {
            const {width, height} = this.track.app.viewport;

            this.root = document.createElement("canvas");
            this.root.width = width;
            this.root.height = height;

            this.ctx = this.root.getContext('2d');
            this.ctx.strokeStyle = this.clipColor.value;
            this.ctx.lineWidth = this.clipWidth.value;
        }  
        else if(type === App.RECT) {
            this.root = document.createElement("div");

            let style = this.root.style;
            style.background = this.clipColor.value;
        }
        else if(type === App.TEXT) {
            this.root = document.createElement("input");
            this.root.type = "text";

            let style = this.root.style;
            style.color = this.clipColor.value;
            style.fontSize = this.clipFsize.value;
        }

        this.root.classList.add("clip");

        this.addEvent();
    }

    setText(){
        let text = this.root.value;
        let parent = this.root.parentElement;
        
        let span = document.createElement("span");
        span.classList.add("clip");
        span.innerText = text;
        span.style.left = this.x + "px";
        span.style.top = this.y + "px";
        span.style.color = this.root.style.color;
        span.style.fontSize = this.root.style.fontSize;

        parent.insertBefore(span, this.root);
        this.root.remove();

        this.root = span;
    }

    addEvent(){
        this.root.addEventListener("keydown", e => e.stopPropagation());
    }
}