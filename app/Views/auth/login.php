<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodSwipe — Connexion</title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>" />
</head>
<body>

<div class="auth-page">
  <div class="auth-card">

    <div class="auth-logo">
      <span class="logo-icon">🍽️</span>
      <h1>FoodSwipe</h1>
      <p>Swipez. Savourez. Régalez-vous.</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
      <p class="form-error visible"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('login/do') ?>" method="post">
        <?= csrf_field() ?>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="vous@exemple.com" autocomplete="email" required />
      </div>

      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required />
      </div>

      <button type="submit" class="btn-primary">Se connecter 🍴</button>
    </form>

    <div class="auth-switch">
      Pas encore de compte ? <a href="<?= base_url('register') ?>">S'inscrire</a>
    </div>

  </div>
</div>

</body>
</html>