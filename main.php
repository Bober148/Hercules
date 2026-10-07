<?php
session_start();
?>



<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Hercules — школьный фитнес-клуб</title>
  <link rel="stylesheet" href="style.css" />
  <script src="main.js" defer></script>
</head>
<body>

<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand" href="main.php">
      <span class="brand__mark">H</span>
      <span class="brand__name">Hercules</span>
    </a>

    <nav class="nav">
      <a href="main.php">Главная</a>
      <a href="schedule.php">Расписание</a>
      <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true): ?>
          <a href="profile.php">Профиль</a>
          <a href="logout.php">Выйти</a>
      <?php else: ?>
          <a href="login.html">Войти</a>
      <?php endif; ?>
    </nav>

  </div>
</header>



<main>
  <section class="hero" id="hero">
    <div class="container hero__grid">
      <div class="hero__text">
        <div class="badge">Школьный клуб • Тренировки • Здоровье</div>
        <h2>Тренируйся в команде. Следи за прогрессом. Не пропускай занятия.</h2>
        <p>
          Hercules — платформа для участников школьного фитнес-клуба:
          регистрация, расписание, посещаемость и личные рекорды — в одном месте.
        </p>

        <div class="hero__cta">
          <a class="btn btn--primary" href="registration.html">Начать</a>
          <a class="btn btn--light" href="#features">Смотреть возможности</a>
        </div>

        <div class="stats">
          <div class="stat">
            <div class="stat__num">1</div>
            <div class="stat__label">аккаунт для каждого</div>
          </div>
          <div class="stat">
            <div class="stat__num">1</div>
            <div class="stat__label">страница расписания</div>
          </div>
          <div class="stat">
            <div class="stat__num">0</div>
            <div class="stat__label">пропущенных объявлений</div>
          </div>
        </div>
      </div>

      <div class="hero__card">
        <h3>Быстрые действия</h3>

        <div class="quick">
          <div class="quick__item">
            <div class="quick__icon">📅</div>
            <div>
              <div class="quick__title">Расписание</div>
              <div class="quick__desc">Все тренировки на одной странице</div>
            </div>
          </div>

          <div class="quick__item">
            <div class="quick__icon">✅</div>
            <div>
              <div class="quick__title">Посещаемость</div>
              <div class="quick__desc">Отмечай и смотри посещения</div>
            </div>
          </div>

          <div class="quick__item">
            <div class="quick__icon">🏆</div>
            <div>
              <div class="quick__title">PR</div>
              <div class="quick__desc">Храни личные рекорды</div>
            </div>
          </div>
        </div>

        <a class="btn btn--primary btn--full" href="registration.html">Создать аккаунт</a>
        <div class="muted">Без базы данных — пока демо-версия интерфейса.</div>
      </div>
    </div>
  </section>
  
  <section class="slider-section" id="slider">
  <div class="container">
    <h2 class="slider-title">Спорт — это дисциплина, не настроение</h2>

    <div class="slider" aria-label="Motivation slider">
      <button class="slider-btn prev" type="button" aria-label="Previous">‹</button>

      <div class="slider-track" id="motivationTrack">
        <article class="slide" data-quote="Становись сильнее каждый день. Сравнивай себя только со вчерашним.">
          <img src="image.png" alt="David" />
          <div class="slide-overlay">
            <div class="slide-badge">Hercules</div>
            <div class="slide-quote">Становись сильнее каждый день. Сравнивай себя только со вчерашним.</div>
          </div>
        </article>
      </div>

      <button class="slider-btn next" type="button" aria-label="Next">›</button>
    </div>

    <div class="slider-dots" id="motivationDots" aria-label="Slider dots"></div>
  </div>
</section>


  <section class="section" id="features">
    <div class="container">
      <div class="section__head">
        <h2>Что даёт платформа</h2>
        <p>Минимум лишнего. Максимум структуры.</p>
      </div>

      <div class="grid">
        <div class="card">
          <div class="card__top">
            <span class="chip">Участник</span>
          </div>
          <h3>Личный кабинет</h3>
          <p>Имя, телефон, PR и базовая информация — аккуратно и без спама.</p>
        </div>

        <div class="card">
          <div class="card__top">
            <span class="chip">Расписание</span>
          </div>
          <h3>Понятное расписание</h3>
          <p>Тренировки и лекции в одном списке: дата, время, тренер, тип занятия.</p>
        </div>

        <div class="card">
          <div class="card__top">
            <span class="chip">Контроль</span>
          </div>
          <h3>Посещаемость</h3>
          <p>Организатор отмечает присутствие — у каждого занятия есть запись.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--alt" id="about">
    <div class="container about">
      <div>
        <h2>О клубе Hercules</h2>
        <p>
          Сообщество учеников, которые тренируются вместе, улучшают здоровье и
          изучают базовую спортивную теорию: питание, восстановление, добавки.
        </p>
        <p class="muted">
          Цель сайта — убрать хаос из чатов и сделать работу клуба организованной.
        </p>
      </div>

      <div class="panel" id="schedule">
        <h3>Пример блока расписания</h3>
        <div class="list">
          <div class="list__row">
            <div class="list__tag">Пн</div>
            <div class="list__main">
              <div class="list__title">Силовая тренировка</div>
              <div class="list__sub">16:30 • Зал • Тренер: Yerdaulet</div>
            </div>
          </div>
          <div class="list__row">
            <div class="list__tag">Ср</div>
            <div class="list__main">
              <div class="list__title">Техника упражнений</div>
              <div class="list__sub">16:30 • Зал • Тренер: Erdaulet</div>
            </div>
          </div>
          <div class="list__row">
            <div class="list__tag">Пт</div>
            <div class="list__main">
              <div class="list__title">Лекция: питание и добавки</div>
              <div class="list__sub">15:40 • Кабинет • Спикер: Trainer</div>
            </div>
          </div>
    </div>
  </section>
</main>

<footer class="footer" id="contacts" >
  <div class="container footer__inner">
    <div class="footer__left">
      <div class="footer__brand">Hercules</div>
      <div class="muted">Школьный фитнес-клуб • 2026</div>
    </div>
    <div class="footer__right muted">
      Контакты: организатор клуба • Instagram / Telegram
    </div>

  </body>