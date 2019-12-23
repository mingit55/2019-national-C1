<style>
.head .item {
    width: calc(100% / 6 - 10px);
    height: 50px;
    line-height: 50px;
    box-shadow: 0 0 5px 2px #00000010;
}

.list {
    box-shadow: 0 0 5px 2px #00000010;
    margin: 10px 0;
}

.list .item {
    width: calc(100% / 6 - 10px);
    height: 50px;
    line-height: 50px;
    text-align: center;
}


</style>

<section>
    <div class="inline">
        <div class="section-title">
            <h1>상영일정</h1>
        </div>
        <div class="f-15 bold"><?=$date?></div>

        <div class="head w-100 flex align-center justify-between mt-4">
            <div class="item main-background white bold f-12 text-center">상영시간</div>
            <div class="item main-background white bold f-12 text-center">출품자</div>
            <div class="item main-background white bold f-12 text-center">영화제목</div>
            <div class="item main-background white bold f-12 text-center">러닝타임</div>
            <div class="item main-background white bold f-12 text-center">제작년도</div>
            <div class="item main-background white bold f-12 text-center">분류</div>
        </div>
        <?php foreach($movies as $movie):?>
        <div class="list w-100 flex stack align-start justify-between">
            <div class="item text-center f-11"><?=parseTime2($movie->startTime)?>~<?=parseTime2($movie->endTime)?></div>
            <div class="item text-center f-11"><?=$movie->user_name?>(<?=$movie->user_id?>)</div>
            <div class="item text-center f-11"><?=$movie->name?></div>
            <div class="item text-center f-11"><?=parseTime($movie->duration)?></div>
            <div class="item text-center f-11"><?=$movie->c_year?>년</div>
            <div class="item text-center f-11"><?=$movie->type?></div>
        </div>
        <?php endforeach;?>
    </div>
</section>