<section id="add_schedule">
    <div class="w-40 m-auto">
        <div class="section-title">
            <h1>상영 일정 등록</h1>
        </div>
        <form method="post">
            <div class="form-group">
                <span class="label">상영일정</span>
                <input type="text" name="s_datetime" placeholder="[년/월/일/시/분] 형식으로 입력하세요. (ex: 2019/1/1/13/53)">
            </div>
            <div class="form-group">
                <span class="label">출품작</span>
                <select name="mid">
                    <?php foreach($freeMovies as $movie):?>
                        <option value="<?=$movie->id?>"><?=$movie->name?> (<?=parseTime($movie->duration)?>)</option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="button mt-3">등록하기</button>
        </form>
    </div>
</section>