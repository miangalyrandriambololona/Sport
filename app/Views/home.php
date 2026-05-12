<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>FoodSwipe — Découvrir</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <style>
        .loading { text-align: center; padding: 60px; font-size: 1.5rem; color: #888; }
        .food-card {
            position: absolute;
            width: 100%;
            max-width: 340px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 35px -10px rgba(0,0,0,0.2);
            overflow: hidden;
            cursor: grab;
            transition: transform 0.3s;
            will-change: transform;
        }
        .food-card:active { cursor: grabbing; }
        .food-card-img {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .food-card-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .food-card-emoji {
            font-size: 5rem;
        }
        .stamp {
            position: absolute;
            top: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 40px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
            z-index: 10;
        }
        .stamp-like {
            right: 20px;
            color: #4CAF50;
            border: 2px solid #4CAF50;
            transform: rotate(15deg);
        }
        .stamp-nope {
            left: 20px;
            color: #F44336;
            border: 2px solid #F44336;
            transform: rotate(-15deg);
        }
        .food-card-info {
            padding: 16px;
        }
        .food-card-top {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 8px;
        }
        .food-card-name {
            font-size: 1.3rem;
            font-weight: bold;
        }
        .food-card-rating {
            color: #FFA000;
            font-weight: bold;
        }
        .food-card-meta {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            background: #f0f0f0;
        }
        .badge.category {
            background: #FF6B6B20;
            color: #FF6B6B;
        }
        .food-card-desc {
            font-size: 0.85rem;
            color: #666;
            line-height: 1.4;
        }
        .card-stack {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 500px;
        }
        .action-btns {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 20px;
            margin-top: 20px;
        }
        .action-btn {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            border: none;
            font-size: 1.8rem;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .action-btn:active { transform: scale(0.95); }
        .btn-skip { background: white; color: #F44336; border: 2px solid #F44336; }
        .btn-super { background: linear-gradient(135deg, #FFD700, #FFA500); color: white; }
        .btn-like { background: white; color: #4CAF50; border: 2px solid #4CAF50; }
        @keyframes swipeRightAnim {
            0% { transform: translateX(0) rotate(0deg); opacity: 1; }
            100% { transform: translateX(500px) rotate(30deg); opacity: 0; }
        }
        @keyframes swipeLeftAnim {
            0% { transform: translateX(0) rotate(0deg); opacity: 1; }
            100% { transform: translateX(-500px) rotate(-30deg); opacity: 0; }
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state .emoji { font-size: 4rem; }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            color: white;
            padding: 12px 24px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 16px;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            padding: 16px;
            background: white;
            border-bottom: 1px solid #eee;
        }
        .topbar-logo { font-weight: bold; font-size: 1.2rem; }
        .topbar-actions a {
            text-decoration: none;
            margin-left: 12px;
            font-size: 1.2rem;
        }
        .bottom-nav {
            display: flex;
            justify-content: space-around;
            padding: 12px;
            background: white;
            border-top: 1px solid #eee;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
        .bottom-nav a {
            text-decoration: none;
            color: #888;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 40px;
        }
        .bottom-nav a.active {
            background: #FF6B6B20;
            color: #FF6B6B;
        }
        .app-page {
            max-width: 500px;
            margin: 0 auto;
            background: #f9f9f9;
            min-height: 100vh;
            padding-bottom: 70px;
        }
        .card-area {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="app-page">
    <div class="topbar">
        <span class="topbar-logo"><span>🍽️</span>FoodSwipe</span>
        <div class="topbar-actions">
            <a href="<?= base_url('stats') ?>">📊</a>
            <a href="#" onclick="logout()">🚪</a>
        </div>
    </div>

    <div class="card-area">
        <div class="card-stack" id="card-stack">
            <div class="loading">🍔 Chargement...</div>
        </div>
        <div class="empty-state" id="empty-state" style="display:none">
            <div class="emoji">🍀</div>
            <h2>C'est tout !</h2>
            <p>Vous avez vu tous les plats.</p>
            <a href="<?= base_url('add-food') ?>" class="btn-primary">Ajouter un plat ➕</a>
        </div>
    </div>

    <div class="action-btns" id="action-btns">
        <button class="action-btn btn-skip" onclick="swipeLeft()">✕</button>
        <button class="action-btn btn-super" onclick="superLike()">⭐</button>
        <button class="action-btn btn-like" onclick="swipeRight()">♥</button>
    </div>

    <div class="bottom-nav">
        <a href="<?= base_url('home') ?>" class="active">🔥 Découvrir</a>
        <a href="<?= base_url('add-food') ?>">➕ Ajouter</a>
        <a href="<?= base_url('stats') ?>">📊 Mes stats</a>
    </div>
</div>

<script>
const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
let currentMeal = null;
let isLoading = false;

function logout() {
    window.location.href = BASE_URL + '/logout';
}

async function loadNextMeal() {
    if (isLoading) return;
    isLoading = true;

    try {
        const response = await fetch(BASE_URL + '/swipe/next');
        const data = await response.json();

        if (data.empty) {
            document.getElementById('card-stack').innerHTML = '';
            document.getElementById('empty-state').style.display = 'block';
            document.getElementById('action-btns').style.display = 'none';
            isLoading = false;
            return;
        }

        document.getElementById('empty-state').style.display = 'none';
        document.getElementById('action-btns').style.display = 'flex';
        currentMeal = data.meal;
        renderCard(currentMeal);

    } catch (error) {
        console.error('Erreur:', error);
    }

    isLoading = false;
}

function renderCard(meal) {
    const stack = document.getElementById('card-stack');
    stack.innerHTML = '';

    const colors = ['#FF6B6B', '#FF8E53', '#FFC371', '#4ECDC4', '#45B7D1', '#96CEB4'];
    const color = colors[(meal.category?.length || 0) % colors.length];

    const card = document.createElement('div');
    card.className = 'food-card';
    card.dataset.id = meal.id;
    card.innerHTML = `
        <div class="food-card-img" style="background: linear-gradient(135deg, ${color}22, ${color}55)">
            ${meal.image_url ? `<img src="${meal.image_url}" class="food-card-photo" onerror="this.style.display='none';this.parentElement.querySelector('.food-card-emoji').style.display='flex'">` : ''}
            <span class="food-card-emoji" style="${meal.image_url ? 'display:none' : 'display:block'}">${meal.emoji || '🍽️'}</span>
        </div>
        <div class="stamp stamp-like">❤️ J'aime</div>
        <div class="stamp stamp-nope">👎 Nope</div>
        <div class="food-card-info">
            <div class="food-card-top">
                <div class="food-card-name">${escapeHtml(meal.name)}</div>
                <div class="food-card-rating">⭐ ${meal.rating || '4.0'}</div>
            </div>
            <div class="food-card-meta">
                <span class="badge category">${escapeHtml(meal.category)}</span>
                <span class="badge time">⏱ ${meal.time_min || 0} min</span>
                <span class="badge cal">🔥 ${meal.calories || 0} kcal</span>
            </div>
            <div class="food-card-desc">${escapeHtml(meal.description || 'Un délicieux plat à découvrir !')}</div>
        </div>
    `;

    stack.appendChild(card);
    attachDragEvents(card, meal);
}

function attachDragEvents(card, meal) {
    let startX = 0, startY = 0, currentX = 0, currentY = 0, dragging = false;
    const likeStamp = card.querySelector('.stamp-like');
    const nopeStamp = card.querySelector('.stamp-nope');

    function onStart(e) {
        if (isLoading) return;
        e.preventDefault();
        dragging = true;
        startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
        startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
        card.style.transition = 'none';
        card.style.cursor = 'grabbing';
    }

    function onMove(e) {
        if (!dragging) return;
        e.preventDefault();

        const clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        const clientY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;

        currentX = clientX - startX;
        currentY = clientY - startY;

        const rotation = currentX / 15;
        card.style.transform = `translateX(${currentX}px) translateY(${currentY * 0.1}px) rotate(${rotation}deg)`;

        const pct = Math.min(Math.abs(currentX) / 100, 1);
        if (currentX > 30) {
            likeStamp.style.opacity = pct;
            nopeStamp.style.opacity = 0;
        } else if (currentX < -30) {
            likeStamp.style.opacity = 0;
            nopeStamp.style.opacity = pct;
        } else {
            likeStamp.style.opacity = 0;
            nopeStamp.style.opacity = 0;
        }
    }

    function onEnd(e) {
        if (!dragging) return;
        dragging = false;
        card.style.cursor = 'grab';

        let action = null;
        if (currentX > 120)                        action = 'like';
        else if (currentX < -120)                  action = 'skip';
        else if (currentX > 60 && currentX <= 120) action = 'super';

        if (action) {
            performSwipe(action, meal);
        } else {
            card.style.transition = 'transform 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1)';
            card.style.transform = '';
            setTimeout(() => {
                likeStamp.style.opacity = 0;
                nopeStamp.style.opacity = 0;
            }, 300);
        }

        currentX = 0;
        currentY = 0;
    }

    card.addEventListener('mousedown', onStart);
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseup', onEnd);
    card.addEventListener('touchstart', onStart, { passive: false });
    window.addEventListener('touchmove', onMove, { passive: false });
    window.addEventListener('touchend', onEnd);
}

async function performSwipe(action, meal) {
    const card = document.querySelector('.food-card');
    if (!card || isLoading) return;

    isLoading = true;

    const direction = (action === 'like' || action === 'super') ? 500 : -500;
    const rotation  = (action === 'like' || action === 'super') ? 30 : -30;
    card.style.transition = 'transform 0.3s ease-out';
    card.style.transform = `translateX(${direction}px) rotate(${rotation}deg)`;

    try {
        const response = await fetch(BASE_URL + '/swipe/do', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ meal_id: meal.id, action: action })
        });

        if (!response.ok) {
            console.error('Erreur serveur:', response.status);
        }
    } catch (error) {
        console.error('Erreur réseau:', error);
    }

    setTimeout(() => {
        isLoading = false;
        loadNextMeal();
    }, 300);
}

function swipeRight() { if (currentMeal && !isLoading) performSwipe('like',  currentMeal); }
function swipeLeft()  { if (currentMeal && !isLoading) performSwipe('skip',  currentMeal); }
function superLike()  { if (currentMeal && !isLoading) performSwipe('super', currentMeal); }

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m]));
}

loadNextMeal();
</script>

</body>
</html>