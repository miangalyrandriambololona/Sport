<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodSwipe — Ajouter un plat</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
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

    <div class="addfood-body">
        <!-- Aperçu -->
        <div class="preview-wrap">
            <div class="preview-label">Aperçu</div>
            <div class="preview-card">
                <div class="preview-img" id="previewImg">🍽️</div>
                <div class="food-card-info">
                    <div class="food-card-name" id="previewName">Nom du plat</div>
                    <div class="food-card-rating">⭐ <span id="previewRating">-</span></div>
                    <div class="food-card-meta">
                        <span class="badge category" id="previewCat">Catégorie</span>
                        <span class="badge time" id="previewTime">⏱ --</span>
                        <span class="badge cal" id="previewCal">🔥 -- kcal</span>
                    </div>
                    <div class="food-card-desc" id="previewDesc">Description...</div>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form class="addfood-form" action="<?= base_url('add-food/save') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <?php if(session()->getFlashdata('error')): ?>
                <p class="form-error visible"><?= session()->getFlashdata('error') ?></p>
            <?php endif; ?>
            <?php if(session()->getFlashdata('success')): ?>
                <p class="form-success visible"><?= session()->getFlashdata('success') ?></p>
            <?php endif; ?>

            <div class="form-group">
                <label>Photo</label>
                <input type="file" name="image" accept="image/*" onchange="previewImage(this)">
            </div>

            <div class="form-group">
                <label>Emoji</label>
                <input type="text" name="emoji" placeholder="🍕" maxlength="2" value="🍽️" oninput="syncPreview()">
            </div>

            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="name" required oninput="syncPreview()">
            </div>

            <div class="form-group">
                <label>Catégorie *</label>
                <select name="category" required onchange="syncPreview()">
                    <option value="">-- Choisir --</option>
                    <option>Français</option><option>Italien</option><option>Japonais</option>
                    <option>Mexicain</option><option>Indien</option><option>Thaïlandais</option>
                    <option>Américain</option><option>Oriental</option><option>Maghrébin</option>
                    <option>Hawaïen</option><option>Dessert</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Temps (min) *</label>
                    <input type="number" name="time_min" required oninput="syncPreview()">
                </div>
                <div class="form-group">
                    <label>Calories *</label>
                    <input type="number" name="calories" required oninput="syncPreview()">
                </div>
            </div>

            <div class="form-group">
                <label>Note /5 *</label>
                <input type="range" name="rating" min="1" max="5" step="0.1" value="4" oninput="syncPreview()">
                <span id="ratingValue">4.0</span>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" oninput="syncPreview()"></textarea>
            </div>

            <button type="submit" class="btn-primary">Ajouter le plat ✅</button>
        </form>
    </div>

    <div class="bottom-nav">
        <a href="<?= base_url('home') ?>">🔥 Découvrir</a>
        <a href="<?= base_url('add-food') ?>" class="active">➕ Ajouter</a>
        <a href="<?= base_url('stats') ?>">📊 Mes stats</a>
    </div>
</div>

<script>
function logout() {
    window.location.href = '<?= base_url('logout') ?>';
}

function syncPreview() {
    document.getElementById('previewName').textContent = document.querySelector('[name="name"]').value || 'Nom du plat';
    document.getElementById('previewCat').textContent = document.querySelector('[name="category"]').value || 'Catégorie';
    document.getElementById('previewTime').textContent = '⏱ ' + (document.querySelector('[name="time_min"]').value || '--') + ' min';
    document.getElementById('previewCal').textContent = '🔥 ' + (document.querySelector('[name="calories"]').value || '--') + ' kcal';
    let rating = document.querySelector('[name="rating"]').value;
    document.getElementById('previewRating').textContent = rating;
    document.getElementById('ratingValue').textContent = rating;
    document.getElementById('previewDesc').textContent = document.querySelector('[name="description"]').value || 'Description...';
    
    let emoji = document.querySelector('[name="emoji"]').value;
    if (emoji && document.getElementById('previewImg').innerHTML !== emoji && !document.getElementById('previewImg').querySelector('img')) {
        document.getElementById('previewImg').innerHTML = emoji;
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Initialisation
document.querySelectorAll('input, select, textarea').forEach(el => el.addEventListener('input', syncPreview));
syncPreview();
</script>

<style>
.preview-img { width: 100%; height: 160px; background: linear-gradient(135deg, #FF6B6B22, #FF6B6B55); display: flex; align-items: center; justify-content: center; font-size: 4rem; border-radius: 16px; overflow: hidden; }
.form-success { background: #4CAF50; color: white; padding: 12px; border-radius: 12px; margin-bottom: 16px; text-align: center; }
</style>

</body>
</html>