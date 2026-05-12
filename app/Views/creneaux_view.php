<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FitSpace — Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-family: 'Syne', sans-serif; font-weight: 800;">CRÉNEAUX DISPONIBLES</h2>
        <a href="<?= site_url('logout') ?>" class="btn btn-outline-dark btn-sm">Déconnexion</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <table class="table align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Activité / Salle</th>
                    <th>Date & Heure</th>
                    <th>Places</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($creneaux)): ?>
                    <?php foreach($creneaux as $c): ?>
                    <tr>
                        <td><strong><?= esc($c['ressource_nom']) ?></strong></td>
                        <td><?= date('d/m/Y à H:i', strtotime($c['date_debut'])) ?></td>
                        <td>
                            <span class="badge bg-light text-dark border"><?= esc($c['places_dispo']) ?> restantes</span>
                        </td>
                        <td>
                            <?php if($c['places_dispo'] > 0): ?>
                                <a href="<?= site_url('reserver/'.$c['id']) ?>" class="btn btn-primary btn-sm px-4">Réserver</a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>Complet</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center py-5 text-muted">Aucun créneau actif pour le moment.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>