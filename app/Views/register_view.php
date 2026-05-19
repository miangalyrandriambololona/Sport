<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FitSpace — Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=Syne:wght@800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1a1a2e; --accent: #e94560; }
        body { background-color: #f7f7fa; font-family: 'DM Sans', sans-serif; color: var(--primary); }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        h2 { font-family: 'Syne', sans-serif; font-weight: 800; }
        .btn-main { background: var(--primary); color: white; border-radius: 10px; padding: 12px; transition: 0.3s; }
        .btn-main:hover { background: var(--accent); color: white; }
    </style>
</head>
<body>
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="text-center mb-4">
                <h2 class="display-6">FITSPACE</h2>
                <p class="text-muted">Créer un compte rapidement</p>
            </div>
            <div class="card p-4">
                <form action="<?= site_url('register_action') ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-main w-100 fw-bold">CRÉER MON COMPTE</button>
                </form>
                <div class="mt-4 text-center">
                    <a href="<?= site_url('login') ?>" class="small text-muted text-decoration-none">Déjà inscrit ? Se connecter</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>