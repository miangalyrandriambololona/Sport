<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FitSpace Admin — Créneaux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-danger" style="font-family: sans-serif;">BACK-OFFICE : GESTION DES CRÉNEAUX</h2>
            <div class="btn-group mt-1">
                <a href="<?= site_url('admin/reservations') ?>" class="btn btn-sm btn-outline-dark">📋 Liste des Réservations</a>
                <a href="<?= site_url('admin/creneaux/creer') ?>" class="btn btn-sm btn-primary">➕ Créer un Créneau</a>
            </div>
        </div>
        <a href="<?= site_url('logout') ?>" class="btn btn-outline-dark btn-sm">Déconnexion Admin</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <table class="table align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Activité / Salle</th>
                    <th>Date & Heure Début</th>
                    <th>Date & Heure Fin</th>
                    <th>Places Restantes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($creneaux)): ?>
                    <?php foreach($creneaux as $c): ?>
                    <tr>
                        <td><strong><?= esc($c['ressource_nom']) ?></strong></td>
                        <td><?= date('d/m/Y à H:i', strtotime($c['date_debut'])) ?></td>
                        <td><?= date('d/m/Y à H:i', strtotime($c['date_fin'])) ?></td>
                        <td>
                            <span class="badge bg-light text-dark border"><?= esc($c['places_dispo']) ?> places</span>
                        </td>
                        <td>
                            <a href="<?= site_url('admin/creneaux/supprimer/'.$c['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer définitivement ce créneau ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted">Aucun créneau n'est actuellement configuré.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>