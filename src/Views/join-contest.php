<!-- Join Festival -->
<section id="join-festival">
<div class="inline">
    <div class="section-title">
        <h1>참가하기</h1>
    </div>
    <div class="flex justify-between align-start">
        <div class="btn-bar w-10 flex column">
            <button id="path-btn" class="btn my-05 pa-05">자유곡선</button>
            <button id="rect-btn" class="btn my-05 pa-05">사각형</button>
            <button id="text-btn" class="btn my-05 pa-05">텍스트</button>
            <button id="select-btn" class="btn my-05 pa-05">선택</button>
            <button id="play-btn" class="btn my-05 pa-05">재생</button>
            <button id="pause-btn" class="btn my-05 pa-05">정지</button>
            <button id="allDel-btn" class="btn my-05 pa-05">전체 삭제</button>
            <button id="selDel-btn" class="btn my-05 pa-05">선택 삭제</button>
            <button id="down-btn" class="btn my-05 pa-05">다운로드</button>
            <button id="join-btn" class="btn my-05 pa-05">참여하기</button>
        </div>
        <div id="contents" class="w-60">
            <div id="viewport">
                <video></video>
                <div class="empty-msg">
                    <p class="white text-center">동영상을 선택해주세요.</p>
                </div>
            </div>
            <div id="v-ui" class="flex justify-between my-2">
                <div class="video-info">
                    <span class="current">00:00:00:00</span>
                    <span>/</span>
                    <span class="duration">00:00:00:00</span>
                </div>
                <div class="clip-info">
                    시작 시간:
                    <span class="start">00:00:00:00</span>
                    &nbsp;&nbsp;
                    유지기간:
                    <span class="duration">00:00:00:00</span>
                </div>
            </div>
            <!-- 시간 표시줄 -->
            <div id="track">
            </div>
            <!-- 영화 표시줄 -->
            <div id="movie-line" class="w-100 flex justify-between mt-5">
                <img src="/images/movie1.jpg" alt="movies" class="item w-20" data-id="1">
                <img src="/images/movie2.jpg" alt="movies" class="item w-20" data-id="2">
                <img src="/images/movie3.jpg" alt="movies" class="item w-20" data-id="3">
                <img src="/images/movie4.jpg" alt="movies" class="item w-20" data-id="4">
            </div>
        </div>
        <div class="w-10 flex column">
            <div class="form-group my-1">
                <label for="s-color">색상</label><br>
                <select id="s-color">
                    <option value="gray">gray</option>
                    <option value="blue">blue</option>
                    <option value="green">green</option>
                    <option value="red">red</option>
                    <option value="yellow">yellow</option>
                </select>
            </div>
            <div class="form-group my-1">
                <label for="s-width">선 두께</label><br>
                <select id="s-width">
                    <option value="3">3px</option>
                    <option value="5">5px</option>
                    <option value="10">10px</option>
                </select>
            </div>
            <div class="form-group my-1">
                <label for="s-fsize">글자 크기</label><br>
                <select id="s-fsize">
                    <option value="16px">16px</option>
                    <option value="18px">18px</option>
                    <option value="24px">24px</option>
                    <option value="32px">32px</option>
                </select>
            </div>
        </div>
    </div>
</div>
</section>
<link rel="stylesheet" href="/css/join-f.css">
<script src="/js/Clip.js"></script>
<script src="/js/Track.js"></script>
<script src="/js/Viewport.js"></script>
<script src="/js/App.js"></script>

<script>
    document.querySelector("#join-btn").addEventListener("click", () => {
        let data = {
            mtype: app.viewport.playTrack.id,
            contents: app.parseHTML(),
        };

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/contest/join");
        xhr.send(JSON.stringify(data));

        xhr.onload = () => {
            let res = JSON.parse(xhr.responseText);
            alert(res.message);
            if(res.result) location.assign("/contest/list");
        };
    });
</script>