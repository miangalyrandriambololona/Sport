<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodSwipe — Mes stats</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<div class="app-page">
    <!-- Top Bar -->
    <div class="topbar">
        <span class="topbar-logo"><span>🍽️</span>FoodSwipe</span>
        <div class="topbar-actions">
            <a href="<?= base_url('stats') ?>">📊</a>
            <a href="#" onclick="logout()">🚪</a>
        </div>
    </div>

    <!-- Stats Header -->
    <div class="stats-header">
        <h2>Mes statistiques 📊</h2>
        <p>Vos plats préférés en un coup d'œil</p>
    </div>

    <!-- Stats Body -->
    <div class="stats-body">

        <!-- KPIs -->
        <div class="kpi-row">
            <div class="kpi-card">
                <span class="kpi-icon">❤️</span>
                <div class="kpi-value"><?= $likedCount ?></div>
                <div class="kpi-label">Aimés</div>
            </div>
            <div class="kpi-card">
                <span class="kpi-icon">👀</span>
                <div class="kpi-value"><?= $seenCount ?></div>
                <div class="kpi-label">Vus</div>
            </div>
            <div class="kpi-card">
                <span class="kpi-icon">⭐</span>
                <div class="kpi-value"><?= $superCount ?></div>
                <div class="kpi-label">Super Like</div>
            </div>
        </div>

        <!-- Donut (Taux d'appréciation) -->
        <div class="section-title">📈 Taux d'appréciation</div>
        <div class="donut-wrap">
            <svg viewBox="0 0 80 80" width="90" height="90" style="flex-shrink:0">
                <circle cx="40" cy="40" r="30" fill="none" stroke="#F0F0F0" stroke-width="12"/>
                <circle cx="40" cy="40" r="30" fill="none" stroke="url(#grad)" stroke-width="12"
                        stroke-dasharray="<?= ($likeRate / 100) * 188.5 ?> 188.5" stroke-linecap="round"
                        transform="rotate(-90 40 40)"/>
                <defs>
                    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#FF6B6B"/>
                        <stop offset="100%" stop-color="#FF8E53"/>
                    </linearGradient>
                </defs>
                <text x="40" y="44" text-anchor="middle" font-size="14" font-weight="800" fill="#2D3748"><?= $likeRate ?>%</text>
            </svg>
            <div class="donut-legend">
                <div class="legend-item">
                    <div class="legend-dot" style="background:#FF6B6B"></div>Aimés
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#EEE"></div>Passés
                </div>
                <div style="font-size:12px;color:var(--muted);margin-top:4px">
                    <?= $likedCount ?> aimé(s) sur <?= $seenCount ?> vus
                </div>
            </div>
        </div>

        <!-- Category Bar Chart -->
        <div class="section-title">🥗 Répartition par catégorie</div>
        <div class="bar-chart" id="category-chart">
            <?php if(empty($categoryStats)): ?>
                <p class="empty-placeholder">Aucune donnée encore</p>
            <?php else: ?>
                <?php $max = max(array_column($categoryStats, 'count')); ?>
                <?php foreach($categoryStats as $cat): ?>
                    <div class="bar-row">
                        <div class="bar-label"><?= esc($cat['category']) ?></div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width:<?= ($cat['count'] / $max) * 100 ?>%; background:<?= getColor($cat['category']) ?>"></div>
                        </div>
                        <div class="bar-count"><?= $cat['count'] ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Liked List -->
        <div class="section-title">💖 Plats aimés</div>
        <div class="liked-list" id="liked-list">
            <?php if(empty($likedMeals)): ?>
                <div class="empty-placeholder">Vous n'avez encore aimé aucun plat 🍽️</div>
            <?php else: ?>
                <?php foreach($likedMeals as $meal): ?>
                    <div class="liked-item">
                        <div class="liked-item-thumb">
                            <?php if($meal['image']): ?>
                                <img src="<?= base_url($meal['image']) ?>" alt="<?= esc($meal['name']) ?>" onerror="this.style.display='none'">
                            <?php endif; ?>
                            <span class="liked-item-emoji" <?= $meal['image'] ? 'style="display:none"' : '' ?>><?= $meal['emoji'] ?></span>
                        </div>
                        <div class="liked-item-info">
                            <div class="liked-item-name"><?= esc($meal['name']) ?></div>
                            <div class="liked-item-cat">
                                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:<?= getColor($meal['category']) ?>;margin-right:4px"></span>
                                <?= esc($meal['category']) ?> · ⏱ <?= $meal['time_min'] ?> min · 🔥 <?= $meal['calories'] ?> kcal
                            </div>
                        </div>
                        <div class="liked-item-heart"><?= $meal['action'] === 'super' ? '⭐' : '❤️' ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <!-- Bottom Nav -->
    <div class="bottom-nav">
        <a href="<?= base_url('home') ?>">
            <span class="nav-icon">🔥</span>Découvrir
        </a>
        <a href="<?= base_url('add-food') ?>">
            <span class="nav-icon">➕</span>Ajouter
        </a>
        <a href="<?= base_url('stats') ?>" class="active">
            <span class="nav-icon">📊</span>Mes stats
        </a>
    </div>
</div>

<script>
function logout() {
    window.location.href = '<?= base_url('logout') ?>';
}
</script>

<?php
// Fonction helper pour les couleurs des catégories
function getColor($category) {
    $colors = [
        'Français' => '#FF6B6B',
        'Italien' => '#FF8E53',
        'Japonais' => '#FFC371',
        'Mexicain' => '#4ECDC4',
        'Indien' => '#45B7D1',
        'Thaïlandais' => '#96CEB4',
        'Américain' => '#DDA0DD',
        'Oriental' => '#FF69B4',
        'Maghrébin' => '#20B2AA',
        'Hawaïen' => '#9370DB',
        'Dessert' => '#F08080',
    ];
    return $colors[$category] ?? '#3CB371';
}
?>

</body>
</html>