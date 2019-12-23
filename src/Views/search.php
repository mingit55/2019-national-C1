<style>
    form {
        display: flex;
        align-items: center;
    }

    .form-group { padding: 0; }

    form input, form select {
        width: 200px;
        height: 35px;
        margin-right: 10px;
    }

    form button {
        width: 100px;
        height: 35px;
    }
</style>
<section>
    <div class="inline">
        <div class="section-title">
            <h1>상영작검색</h1>
        </div>
        <p class="bold f-12 mb-1">검색결과: <?=count($movies)?>/<?=$totalCnt?></p>
        <form method="GET" autocomplete="false">
            <input type="text" name="keyword" placeholder="검색어를 입력하세요..">
            <select name="type">
                <option value="">분류</option>
                <option value="극영화">극영화</option>
                <option value="다큐멘터리">다큐멘터리</option>
                <option value="애니메이션">애니메이션</option>
                <option value="기타">기타</option>
            </select>
            <button type="submit">검색하기</button>
        </form>
        <table class="myTable mt-2">
            <thead>
                <th>출품자</th>
                <th>영화제목</th>
                <th>러닝타임</th>
                <th>제작년도</th>
                <th>분류</th>
            </thead>
            <tbody>
                <?php foreach($movies as $movie):?>
                    <tr>
                        <td><?=$movie->user_name?>(<?=$movie->user_id?>)</td>
                        <td><?=$movie->name?></td>
                        <td><?=parseTime($movie->duration)?></td>
                        <td><?=$movie->c_year?>년</td>
                        <td><?=$movie->type?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</section>