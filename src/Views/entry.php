<section id="entry">
    <div class="w-40 m-auto">
        <div class="section-title">
            <h1>출품신청</h1>
        </div>
        <form method="post" autocomplete="false" class="flex column">
            <div class="form-group">
                <span class="label">출품자 이름</span>
                <input type="text" readonly value="<?=user()->user_name?>" disabled>
            </div>
            <div class="form-group">
                <span class="label">영화제목</span>
                <input type="text" name="name">
            </div>
            <div class="form-group">
                <span class="label">러닝타임</span>
                <input type="number" name="duration" min="0">
            </div>
            <div class="form-group">
                <span class="label">제작년도</span>
                <input type="text" name="c_year" maxlength="4" placeholder="ex: 2019">
            </div>
            <div class="form-group">
                <span class="label">분류</span>
                <select name="type">
                    <option value="극영화">극영화</option>
                    <option value="다큐멘터리">다큐멘터리</option>
                    <option value="애니메이션">애니메이션</option>
                    <option value="기타">기타</option>
                </select>
            </div>
            <button class="button mt-3">출품하기</button>
        </form>
    </div>
</section>