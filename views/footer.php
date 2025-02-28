<?php
$json = file_get_contents(__DIR__ . '/../data/models/information.json');
$data = json_decode($json, true);
?>

<footer>
    <div class="footer-content">
        <p>SFabc</p>
        <p><?= htmlspecialchars($data['adresse'] ?? 'Adresse non renseignée') ?></p>
        <p><?= htmlspecialchars($data['tel'] ?? 'Téléphone non renseigné') ?></p>
        <p><?= htmlspecialchars($data['email'] ?? 'Email non renseigné') ?></p>
        <div class="social-icons">
            <a href="<?= htmlspecialchars($data['instagram'] ?? '#') ?>"><img src="/static/images/insta.png" alt="Instagram"></a>
            <a href="<?= htmlspecialchars($data['facebook'] ?? '#') ?>"><img src="/static/images/fb.png" alt="Facebook"></a>
        </div>
    </div>
    <p class="footer-note">IUT d'Orléans - Claire Deneau, Jean-Marc Jorite, Thomas Brossier</p>
</footer>
