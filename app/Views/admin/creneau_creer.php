<?php
/**
 * @var array $ressources Liste des ressources injectée par le AdminController
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FitSpace Admin — Nouveau Créneau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center mb-4">
                <h3 class="fw-bold mb-0">Créer un nouveau créneau</h3>
            </div>
            
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card p-4 border-0 shadow-sm">
                <form action="<?= site_url('admin/creneaux/enregistrer') ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Ressource concernée</label>
                        <select name="ressource_id" class="form-select" required>
                            <option value="" disabled selected>Choisir un cours ou une salle...</option>
                            <?php if(!empty($ressources)): ?>
                                <?php foreach($ressources as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= esc($r['nom']) ?> (Capacité max : <?= $r['capacite'] ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Date & heure de début</label>
                        <input type="datetime-local" name="date_debut" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Date & heure de fin</label>
                        <input type="datetime-local" name="date_fin" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= site_url('admin/creneaux') ?>" class="btn btn-light border">Annuler</a>
                        <button type="submit" class="btn btn-danger px-4">Publier le créneau</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>