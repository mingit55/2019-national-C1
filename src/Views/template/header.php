<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BIFF 부산국제영화제</title>
    <link rel="stylesheet" href="/css/origin.css">
</head>
<body>
    <input type="checkbox" id="on-mobile-nav" hidden>
    <header>
        <div class="inline flex justify-between align-center">
            <a id="logo" href="/">
                <img src="/images/Llogo.png" alt="부산국제영화제" height="50">
            </a>
            <div class="main-nav flex align-center">
                <a href="/" class="active">부산국제영화제</a>
                <div class="nav-more more-1 flex align-center">
                    <a href="/biff/intro">개최개요</a>
                    <a href="/biff/info">행사안내</a>
                </div>

                <a href="/entry">출품신청</a>
                <a href="#">상영일정</a>
                <a href="#">상영작검색</a>
                <a href="#">이벤트</a>
                <div class="nav-more more-2 flex align-center">
                    <a href="#">영화티저 콘테스트</a>
                    <a href="join-f.html">콘테스트 참여하기</a>
                </div>
            </div>
            <div class="sub-nav flex align-center">
                <?php if(!user()) :?>
                    <a href="/users/login">로그인</a>
                    <a href="/users/register">회원가입</a>
                <?php else :?>
                    <a href="/users/logout">로그아웃</a>
                <?php endif;?>
            </div>

            <label for="on-mobile-nav"></label>
        </div>
    </header>
    <div id="mobile-nav">
        <ul class="mt-5 pt-5 px-3">
            <li>
                <a href="/">부산국제영화제</a>
                <ul>
                    <li><a href="/biff/intro">개최개요</a></li>
                    <li><a href="/biff/info">행사안내</a></li>
                </ul>
            </li>
            <li><a href="/entry">출품신청</a></li>
            <li><a href="#">상영일정</a></li>
            <li><a href="#">상영작검색</a></li>
            <li>
                <a href="#">이벤트</a>
                <ul>
                    <li><a href="#">영화티저 콘테스트</a></li>
                    <li><a href="join-f.html">콘테스트 참가하기</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <section id="visual">
        <div class="images">
            <div class="slide1"></div>
            <div class="slide2"></div>
            <div class="slide3"></div>
            <div class="slide1"></div>
        </div>
        <div class="inline">
            <div class="texts">
                <div class="text">
                    <h5 class="sub">2019년을 장식할 최고의 무대</h5>
                    <h2 class="main">부산국제영화제</h2>
                </div>
                <div class="text">
                    <h5 class="sub">2020년을 장식할 최고의 무대</h5>
                    <h2 class="main">부산국제영화제</h2>
                </div>
                <div class="text">
                    <h5 class="sub">2021년을 장식할 최고의 무대</h5>
                    <h2 class="main">부산국제영화제</h2>
                </div>
            </div>
        </div>
    </section>