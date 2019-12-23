<style>
    .list .item {
        width: calc( 100% / 5 - 50px);
        margin-right: 10px;
        height: 300px;
        position: relative;
        box-shadow: 0 0 10px 3px #00000030;
        cursor: pointer;
    }
    .list .item:nth-child(5n) {
        margin-right: 0;
    }

    .list .item img {
        width: 100%;
        height: 100%;
    }

    .list .item .info {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px;
        background-color: #ffffffcc;
        text-align: center;
        font-weight: bold;
        line-height: 25px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
</style>
<section>
    <div class="inline">
        <div class="section-title center">
            <h1>영화티저 콘테스트</h1>
        </div>
        <div class="list flex stack align-start">
            <?php foreach($articles as $article): ?>
                <a class="item" href="/contest/view?id=<?=$article->id?>">
                    <img src="/images/movie<?=$article->mtype?>.jpg" alt="cover image" height="300">
                    <div class="info">
                        <p>제목: <?=getMName($article->mtype)?></p>
                        <p>평점: <?= $article->s_count > 0 ? number_format($article->s_total/ $article->s_count, 2) : "없음"?></p>
                    </div>
                </a>
            <?php endforeach;?>
        </div>
    </div>
</section>