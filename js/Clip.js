class Clip {
    constructor(track){
        this.track = track;

        // 클립 UI
        this.clipStart = document.querySelector("#v-ui .clip-info .start");
        this.clipDuration = document.querySelector("#v-ui .clip-info .duration");
    }
}