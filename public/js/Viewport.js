class Viewport {
    constructor(viewport, app){
        this.app = app;
        this.playTrack = null;
        this.clipBuffer = null;

        this.root = viewport;
        this.width = viewport.offsetWidth;
        this.height = viewport.offsetHeight;

        this.emptyMsg = this.root.querySelector(".empty-msg");
        this.video = this.root.querySelector("video");

        // 비디오 UI
        this.videoTime = document.querySelector("#v-ui .video-info .current");
        this.videoDuration = document.querySelector("#v-ui .video-info .duration");

        this.addEvent();

        requestAnimationFrame(() => {
            this.frame();
        });
    }

    addEvent(){
        this.video.addEventListener("dragstart", e => {
            e.preventDefault();
            return false;
        });

        this.root.addEventListener("mousedown", e => {
            if(e.which !== 1) return false;
            if(this.video.src === "") return false;
            if(this.app.status === null) return false;

            if(this.clipBuffer !== null && this.clipBuffer.root.nodeName === "INPUT"){
                if(this.clipBuffer.root.value.trim() === ""){
                    this.playTrack.removeClip(this.clipBuffer);
                }
                else {
                    this.clipBuffer.setText();
                }
                this.clipBuffer = null;
                return;
            }

            this.pauseVideo();

            // 선택
            if(this.app.status === App.SELECT){
                let flag = false;

                const clength = this.playTrack.clipList.length;

                for(let i = clength - 1; i >= 0; i-- ){
                    let clip = this.playTrack.clipList[i];
                    const os = offset(clip.root);
                    const fitOs = fitOffset(clip.root, e.pageX, e.pageY, false);
                    
                    if(clip.type === App.PATH){                        
                        let color = clip.ctx.getImageData(fitOs.x, fitOs.y, 1, 1).data[3];
                        if(!flag && color) {
                            clip.clipClick = true;
                            clip.cx = fitOs.x;
                            clip.cy = fitOs.y;

                            clip.select();
                            
                            this.clipBuffer = clip;
                            flag = true;
                        }
                        else clip.diselect();
                    }
                    else {
                        const minX = os.left;
                        const maxX = os.left + clip.root.offsetWidth;

                        const minY = os.top;
                        const maxY = os.top + clip.root.offsetHeight;
                        
                        if(!flag && minX <= e.pageX && e.pageX <= maxX && minY <= e.pageY && e.pageY <= maxY){
                            clip.clipClick = true;
                            clip.cx = fitOs.x;
                            clip.cy = fitOs.y;

                            clip.select();
                            this.clipBuffer = clip;
                            flag = true;
                        }
                        else clip.diselect();
                    }
                }
            }
            
            // 자유곡선, 사각형, 텍스트
            else {
                const clip = this.clipBuffer = new Clip(this.playTrack.clipIndex++, this.app.status, this.playTrack);
                this.playTrack.pushClip(clip);
                this.root.append(clip.root);

                let style = clip.root.style;
                if(clip.type === App.PATH){
                    clip.ctx.beginPath();
                    clip.ctx.moveTo(e.offsetX, e.offsetY);
                }
                else if(clip.type === App.RECT){
                    style.left = e.offsetX + "px";
                    style.top = e.offsetY + "px";
                    
                    clip.x = e.offsetX;
                    clip.y = e.offsetY;
                }
                else if(clip.type === App.TEXT){
                    style.left = e.offsetX + "px";
                    style.top = e.offsetY + "px";

                    clip.x = e.offsetX;
                    clip.y = e.offsetY;
                }
            }
        });

        this.root.addEventListener("mousemove", e => {
            if(e.which !== 1) return false;
            if(this.clipBuffer === null) return false;
            if(this.app.status !== App.PATH && this.app.status !== App.RECT && this.app.status !== App.TEXT) return false;

            const clip = this.clipBuffer;

            let x = e.offsetX;
            let y = e.offsetY;
            

            if(clip.type === App.PATH){
                clip.ctx.lineTo(x, y);
                clip.history.push({x: x, y: y});
                this.clipBuffer.ctx.stroke();
            }
            else if(clip.type === App.RECT){
                let style = clip.root.style;

                if(clip.x < x && clip.y < y) {
                    style.left = clip.x + "px"
                    style.top = clip.y + "px";
                    style.width = x - clip.x + "px";
                    style.height = y - clip.y + "px";
                }
                else if(clip.x > x && clip.y > y){
                    style.left = x + "px";
                    style.top = y + "px";
                    style.width = clip.x - x + "px";
                    style.height = clip.y - y + "px";
                }
                else if(clip.x < x && clip.y > y){
                    style.left = clip.x + "px";
                    style.top = y + "px";
                    style.width = x - clip.x + "px";
                    style.height = clip.y - y + "px";
                }
                else if(clip.x > x && clip.y < y){
                    style.left = x + "px";
                    style.top = clip.y + "px";
                    style.width = clip.x - x + "px";
                    style.height = y - clip.y + "px";
                }
            }
        });

        window.addEventListener("mouseup", e => {
            if(e.which !== 1) return false;
            if(this.clipBuffer === null) return false;
            if(this.app.status !== App.PATH && this.app.status !== App.RECT && this.app.status !== App.TEXT) return false;

            if(this.clipBuffer.type === App.RECT){
                let style = this.clipBuffer.root.style;
                style.backgroundColor = style.borderColor;
                style.borderColor = "transparent";
                this.clipBuffer = null;
            }
            else if(this.clipBuffer.type === App.TEXT) {
                this.clipBuffer.root.focus();
            }
            else {
                this.clipBuffer = null;
            }
        });
    }


    frame(){
        const {currentTime, duration} = this.video;

        requestAnimationFrame(() => {
            this.frame();
        });

        if(this.playTrack !== null){
            if(!this.playTrack.cursorMove) {
                let x = currentTime * this.playTrack.width / duration;
                this.playTrack.seekCursor(x);
            }

            this.videoTime.innerText = currentTime.parseTime();

            this.playTrack.clipList.forEach(clip => {
                let exist = document.querySelector("#clip-"+clip.id);
                if(clip.startTime <= this.video.currentTime && this.video.currentTime <= clip.startTime + clip.duration){
                    if(!exist) this.root.append(clip.root);
                }
                else if(exist) {
                    clip.root.remove();
                }
            });
        }
    }

    seekVideo(x){
        this.video.currentTime = this.video.duration * x / this.playTrack.width;
    }


    playVideo(){
        this.video.play();
    }

    pauseVideo(){
        this.video.pause();
    }

    setVideo(track){ 
        if(this.playTrack !== null)
            this.playTrack.clipList.forEach(x => x.root.remove());

        this.playTrack = track;
        this.emptyMsg.remove();
        this.video.src = `movies/movie${track.id}.mp4`;

        this.video.oncanplay = () => {
            this.playTrack.videoDuration = this.video.duration;
            this.videoDuration.innerText = this.video.duration.parseTime();
        }
    }
}