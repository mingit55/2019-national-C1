class Track {
    constructor(trackLine, app){
        this.app = app;
    
        this.root = trackLine;
        this.html =  `<div>
                            <div class="cursor"></div>
                            <div class="list">
                                <div class="item">
                                    <div class="view-line"></div>
                                </div>
                            </div>
                        </div>`.parseDom();
        console.log(dom);
    }
}