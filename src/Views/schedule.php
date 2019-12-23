<style>
    #calender {
        width: 100%;
    }

    #calender .head {
        display: flex;
        width: 100%;
        height: 40px;
    }

    #calender .head .item {
        width: calc(100% / 7);
        height: 40px;
        background-color: rgb(235, 154, 46);
        color: #fff;
        text-align: center;
        line-height: 40px;
    }

    #calender .body {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        padding: 5px;
    }

    #calender .body .day {
        position: relative;
        width: calc(100% / 7 - 10px);
        height: 100px;
        margin: 5px;
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
        line-height: 100px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        cursor: pointer;
    }

    #calender .body .day.empty {
        border: 0;
    }

    #calender .body .day .no {
        position: absolute;
        line-height: initial;
        left: 10px;
        top: 10px;
    }
</style>

<section id="schedule">
    <div class="inline w-60">
        <div class="section-title center">
            <h1>상영일정</h1>
        </div>
        <div class="flex justify-between align-center">
            <button class="bold f-15 pa-1" onclick="prevDate()">&lt;</button>
            <div class="f-20 bold text-center">
                <span id="v-year" class="f-20 bold"><?=$year?></span>년
                <span id="v-month" class="f-20 bold"><?=$month?></span>월
            </div>
            <button class="bold f-15 pa-1" onclick="nextDate()">&gt;</button>
        </div>
        <div id="calender" class="my-3">
            <div class="head">
                <div class="item">일</div>
                <div class="item">월</div>
                <div class="item">화</div>
                <div class="item">수</div>
                <div class="item">목</div>
                <div class="item">금</div>
                <div class="item">토</div>
            </div>
            <div class="body">
            </div>
        </div>
        <a href="/schedules/add" class="button ml-1">상영일정등록</a>
    </div>    
</section>

<script>
    const v_year = document.querySelector("#v-year");
    const v_month = document.querySelector("#v-month");
    const year = parseInt(v_year.innerText);
    const month = parseInt(v_month.innerText);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/schedules/get" + location.search);
    xhr.send();
    
    xhr.onload = () => {
        let data = JSON.parse(xhr.responseText);

        let date = new Date(`${v_year.innerText}-${v_month.innerText}-1`);
        let startDay = date.getDay();

        date.setMonth(month);
        date.setDate(0);
        let endDate = date.getDate();

        const c_body = document.querySelector("#calender .body");
        for(let i = 0; i < startDay; i++){
            let empty = document.createElement("div");
            empty.classList.add("day");
            empty.classList.add("empty");
            c_body.append(empty);
        }
        for(let i = 1; i <= endDate; i++){
            let findEvent = data.filter(x => (new Date(x.startTime).getDate() === i));

            let day = document.createElement("a");
            day.href = "#";
            day.classList.add("day");
            day.innerHTML = `<span class="no">${i}</span>`;

            findEvent.forEach(x => {
                day.innerHTML += x.name + "<br>";
            });
            

            c_body.append(day);
        }
    };

    function prevDate(){
        if(month !== 1) location.assign(`/schedules?date=${year}-${month - 1}`);
        else location.assign(`/schedules?date=${year - 1}-12`);
    }

    function nextDate(){
        if(month !== 12) location.assign(`/schedules?date=${year}-${month + 1}`);
        else location.assign(`/schedules?date=${year + 1}-1`);
    }
</script>