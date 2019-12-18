class Track {
    constructor(track, app){
        this.app = app;
        this.clipList = [];
    
        this.root = track;
        this.html =  `<div>
                            <div class="cursor"></div>
                            <div class="list">
                                <div class="item">
                                    <div class="view-line"></div>
                                </div>
                            </div>
                        </div>`.parseDom();

        this.root.innerHTML = "";
        this.root.prepend(this.html);

    }
}