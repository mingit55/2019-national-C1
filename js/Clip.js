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
            const {offsetWidth, offsetHeight} = this.track.app.viewport.root;

            this.root = document.createElement("canvas");
            this.root.width = offsetWidth;
            this.root.height = offsetHeight;

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
        }

        this.root.classList.add("clip");

        this.addEvent();
    }

    addEvent(){
        
    }
}