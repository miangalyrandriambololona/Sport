<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FoodSwipe — Inscription</title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>" />
</head>
<body>

<div class="auth-page">
  <div class="auth-card">

    <div class="auth-logo">
      <span class="logo-icon">🥘</span>
      <h1>Créer un compte</h1>
      <p>Rejoignez la communauté des gourmands</p>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
      <?php foreach(session()->getFlashdata('errors') as $error): ?>
        <p class="form-error visible"><?= $error ?></p>
      <?php endforeach; ?>
    <?php endif; ?>

    <form action="<?= base_url('register/do') ?>" method="post">
        <?= csrf_field() ?>
      <div class="form-group">
        <label>Prénom &amp; Nom</label>
        <input type="text" name="name" placeholder="Jean Dupont" value="<?= old('name') ?>" required />
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="vous@exemple.com" value="<?= old('email') ?>" required />
      </div>

      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" placeholder="8 caractères minimum" required />
      </div>

      <div class="form-group">
        <label>Confirmer le mot de passe</label>
        <input type="password" name="confirm_password" placeholder="••••••••" required />
      </div>

      <button type="submit" class="btn-primary">Créer mon compte 🎉</button>
    </form>

    <div class="auth-switch">
      Déjà un compte ? <a href="<?= base_url('login') ?>">Se connecter</a>
    </div>

  </div>
</div>

</body>
</html>