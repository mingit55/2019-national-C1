<section>
    <div class="inline">
        <div class="section-title">
            <h1>영화티저 상세보기</h1>
        </div>
        <div class="flex">
            <div class="w-60">
                <?=$article->contents?>
            </div>
            <div class="w-40 flex column flex-start pa-4">
                <p class="f-13">제목: <?=getMName($article->mtype)?></p>
                <p class="f-13 mt-1">평점: <?= $article->s_count > 0 ? number_format($article->s_total/ $article->s_count, 2) : 0?></p>
                <hr class="my-3">
                <p class="f-15 bold mb-1">이 영상의 점수는 몇 점인가요?</p>
                <p class="mb-2">(1~10점까지 입력할 수 있습니다.)</p>
                <form action="/contest/score" method="post" class="flex">
                    <input type="hidden" name="id" value="<?=$article->id?>">
                    <input type="number" name="score" min="1" max="10" value="<?=$scoreData ? $scoreData->grade : "5"?>" style="width: 50px; height: 35px; padding: 0 5px; margin-right: 10px;">
                    <button type="submit" style="width: 60px; height: 35px;">확인</button>
                </form>
            </div>
        </div>
    </div>
</section>