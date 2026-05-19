<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FitSpace — Mes Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Syne:wght@800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f7f7fa; font-family: 'DM Sans', sans-serif; }
        h2 { font-family: 'Syne', sans-serif; font-weight: 800; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-uppercase m-0">MES RÉSERVATIONS</h2>
            <a href="<?= site_url('creneaux') ?>" class="text-decoration-none text-muted small">← Retour aux créneaux disponibles</a>
        </div>
        <a href="<?= site_url('logout') ?>" class="btn btn-outline-dark btn-sm px-3">Déconnexion</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm overflow-hidden">
        <table class="table align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-4">Activité / Salle</th>
                    <th>Date & Heure de début</th>
                    <th>Statut de la demande</th>
                    <th class="pe-4 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($reservations)): ?>
                    <?php foreach($reservations as $r): ?>
                    <tr>
                        <td class="ps-4"><strong><?= esc($r['ressource_nom']) ?></strong></td>
                        <td><?= date('d/m/Y à H:i', strtotime($r['date_debut'])) ?></td>
                        <td>
                            <?php 
                            // Nettoyage de sécurité pour éviter les conflits d'accents avec SQLite
                            $checkStatus = trim(strtolower($r['statut'])); 
                            
                            $badgeColor = 'bg-warning text-dark'; 
                            if($checkStatus === 'confirmée' || $checkStatus === 'confirmee') $badgeColor = 'bg-success';
                            if($checkStatus === 'annulee' || $checkStatus === 'refusée' || $checkStatus === 'refusee') $badgeColor = 'bg-danger';
                            ?>
                            <span class="badge <?= $badgeColor ?> text-uppercase px-3 py-2 small"><?= esc($r['statut']) ?></span>
                        </td>
                        <td class="pe-4 text-end">
                            <?php if($checkStatus === 'en attente'): ?>
                                <a href="<?= site_url('annuler/'.$r['id']) ?>" class="btn btn-sm btn-outline-danger px-3" onclick="return confirm('Annuler définitivement cette demande de réservation ?')">Annuler</a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-light text-muted border-0" disabled>Aucune action possible</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <p class="mb-0">Vous n'avez pas encore effectué de demande de réservation.</p>
                            <a href="<?= site_url('creneaux') ?>" class="btn btn-sm btn-primary mt-2">Découvrir les cours</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>