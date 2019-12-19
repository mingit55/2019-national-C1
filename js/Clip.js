class Clip {
    static activeColor = "rgb(255, 173, 96)";

    constructor(type, track){
        this.active = false;
        this.type = type;
        this.track = track;

        // 클립 UI
        this.clipStart = document.querySelector("#v-ui .clip-info .start");
        this.clipDuration = document.querySelector("#v-ui .clip-info .duration");

        // 클립 옵션
        this.clipColor = document.querySelector("#s-color");
        this.clipWidth = document.querySelector("#s-width");
        this.clipFsize = document.querySelector("#s-fsize");

        this.v_width = this.track.app.viewport.width;
        this.v_height = this.track.app.viewport.height;

        this.root = null;
        this.ctx = null;
        this.history = [];
        this.x = 0;
        this.y = 0;
        
        this.t_root =  `<div class="item">
                            <div class="view-line"></div>
                        </div>`.parseDom();

        if(type === App.PATH) {
            this.root = document.createElement("canvas");
            this.root.width = this.v_width;
            this.root.height = this.v_height;

            this.ctx = this.root.getContext('2d');
            this.ctx.strokeStyle = this.clipColor.value;
            this.ctx.lineWidth = this.clipWidth.value;
        }  
        else if(type === App.RECT) {
            this.root = document.createElement("div");

            let style = this.root.style;
            style.borderColor = this.clipColor.value;
        }
        else if(type === App.TEXT) {
            this.root = document.createElement("input");
            this.root.type = "text";

            let style = this.root.style;
            style.color = this.clipColor.value;
            style.fontSize = this.clipFsize.value;

            this.root.addEventListener("keydown", e => {
                let scrollWidth = e.target.scrollWidth;
                e.target.style.width = scrollWidth > this.v_width - this.x ? this.v_width - this.x + "px" : scrollWidth + "px";
            });
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
        
        span.style = this.root.style.cssText;

        parent.insertBefore(span, this.root);
        this.root.remove();

        this.root = span;
    }

    addEvent(){
        this.root.addEventListener("keydown", e => e.stopPropagation());

        this.t_root.addEventListener("click", () => {
            this.track.clipList.forEach(x => x.diselect());
            this.select();
        });
    }


    select(){
        this.active = true;
        this.root.classList.add("active");
        this.t_root.classList.add("active");

        if(this.type === App.PATH){
            const lineColor = this.ctx.strokeStyle;
            const lineWidth = this.ctx.lineWidth;

            this.ctx.clearRect(0, 0, this.v_width, this.v_height);

            this.ctx.strokeStyle = Clip.activeColor;
            this.ctx.lineWidth = lineWidth + 8;

            this.history.forEach((data, idx) => {
                const {x, y} = data;
                if(idx === 0) this.ctx.moveTo(x, y);
                else this.ctx.lineTo(x, y);
            });
            this.ctx.stroke();

            this.ctx.strokeStyle = lineColor;
            this.ctx.lineWidth = lineWidth;
            
            this.history.forEach((data, idx) => {
                const {x, y} = data;
                if(idx === 0) this.ctx.moveTo(x, y);
                else this.ctx.lineTo(x, y);
            });
            this.ctx.stroke();
        }
    }

    diselect(){
        this.active = false;
        this.root.classList.remove("active");
        this.t_root.classList.remove("active");

        if(this.type === App.PATH){
            this.ctx.clearRect(0, 0, this.v_width, this.v_height);
            
            this.history.forEach((data, idx) => {
                const {x, y} = data;
                if(idx === 0) this.ctx.moveTo(x, y);
                else this.ctx.lineTo(x, y);
            });
            this.ctx.stroke();
        }
    }
}