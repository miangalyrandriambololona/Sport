<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FitSpace Admin — Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-danger">SUIVI GLOBAL DES RÉSERVATIONS</h2>
            <a href="<?= site_url('admin/creneaux') ?>" class="btn btn-sm btn-outline-dark mt-1">📅 Retour aux créneaux</a>
        </div>
        <a href="<?= site_url('logout') ?>" class="btn btn-outline-dark btn-sm">Déconnexion</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <table class="table align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Activité</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($reservations)): ?>
                    <?php foreach($reservations as $r): ?>
                    <tr>
                        <td>#<?= esc($r['id']) ?></td>
                        <td><strong><?= esc($r['user_email']) ?></strong></td>
                        <td><?= esc($r['ressource_nom']) ?></td>
                        <td><?= date('d/m/Y à H:i', strtotime($r['date_debut'])) ?></td>
                        <td>
                            <?php 
                            $s = trim(strtolower($r['statut']));
                            $s = str_replace(['é','è','ê'], 'e', $s);
                            $badge = 'bg-warning text-dark';
                            if ($s === 'confirmee') $badge = 'bg-success';
                            if (in_array($s, ['refusee', 'annulee'])) $badge = 'bg-danger';
                            ?>
                            <span class="badge <?= $badge ?>"><?= esc($r['statut']) ?></span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <?php if ($s === 'en attente'): ?>
                                    <form action="<?= site_url('admin/reservations/statut/'.$r['id'].'/confirmee') ?>" method="post" class="m-0">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-success px-3">Accepter</button>
                                    </form>
                                    <form action="<?= site_url('admin/reservations/statut/'.$r['id'].'/refusee') ?>" method="post" class="m-0">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Refuser</button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?= site_url('admin/reservations/statut/'.$r['id'].'/confirmee') ?>" method="post" class="m-0">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-link text-success">Ré-accepter</button>
                                    </form>
                                    <form action="<?= site_url('admin/reservations/statut/'.$r['id'].'/refusee') ?>" method="post" class="m-0">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-link text-danger">Refuser</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5">Aucune réservation.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>