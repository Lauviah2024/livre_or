<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Cartes Postales – Partage en Image</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f7fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .section-title {
      color: #003399;
      font-weight: bold;
      margin-bottom: 1rem;
    }
    .carte-postale {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
      background-color: #fff;
      display: flex;
      flex-direction: row;
      align-items: stretch;
      min-height: 100%;
      position: relative;
    }
    .slider-container, .carte-texte {
      flex: 1;
    }
    .carte-texte {
      padding: 1rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .auteur {
      font-size: 1.1rem;
      font-weight: 600;
      color: #2c3e50;
    }
    .club {
      font-size: 0.9rem;
      color: #777;
    }
    .message {
      font-size: 0.85rem;
      color: #444;
      margin: 1rem 0;
      line-height: 1.4;
    }
    .carousel, .carousel-inner, .carousel-item, .carousel-item img {
      height: 100%;
    }
    .carousel-item img {
      object-fit: cover;
      width: 100%;
      border-right: 1px solid #ccc;
    }
    @media (max-width: 768px) {
      .carte-postale {
        flex-direction: column;
      }
      .carousel-item img {
        border-right: none;
        border-bottom: 1px solid #ccc;
        height: 250px;
      }
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="text-center mb-5">
    <h3 class="section-title">Bienvenue sur le Livre d'Or du District</h3>
    <p class="mb-3">Partagez vos plus beaux souvenirs et messages avec la communauté.</p>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">
      <i class="bi bi-pencil-square"></i> Laisser un message
    </button>
  </div>

  <h3 class="section-title text-center">Ils ont apprécié !</h3>
  <p class="text-center">Découvrez les messages et cartes postales partagés par nos membres.</p>




















<div class="container py-5">
  <div class="row g-4 mt-2">
    <?php foreach($cards as $card): ?>
      <div class="col-md-6">
        <div class="carte-postale">
          <div class="slider-container">
            <!-- Affiche la carte personnalisée générée dynamiquement -->
            <img src="<?= base_url('livre-dor/card/' . $card['livre_or_id']) ?>"
                 alt="Carte personnalisée"
                 class="img-fluid w-100"
                 style="max-width:100%;height:auto;">
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Modal Formulaire -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModalLabel">Laisser un message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if (isset($validation)): ?>
          <div class="alert alert-danger"><?php echo $validation->listErrors(); ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url() ?>livre-dor/submit" enctype="multipart/form-data">
          <div class="row">
            <div class="mb-3 col-4">
              <label class="form-label">Nom et Prénoms</label>
              <input type="text" name="livre_or_name" class="form-control" required placeholder="Entrer votre nom et prénoms">
            </div>
            <div class="mb-3 col-4">
              <label class="form-label">Nom du Club</label>
              <input type="text" name="livre_or_club_name" class="form-control" required placeholder="Entrer le nom de votre club">
            </div>
            <div class="mb-3 col-4">
              <label class="form-label">Nom de la ville</label>
              <input type="text" name="livre_or_city" class="form-control" required placeholder="Entrer le nom de votre ville">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Votre message (maximum 500 caractères)</label>
            <textarea name="livre_or_message" class="form-control" required placeholder="Votre message..." maxlength="500" id="livre_or_message"></textarea>
            <small id="charCount" class="text-muted">0 / 500</small>

          </div>
          <div class="mb-3">
            <label class="form-label">Téléchargez votre carte</label>
            <div class="row">
              <div class="col-6">
                <input type="file" name="livre_or_image" class="form-control">
              </div>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary" style="background-color: #003399 !important;">Soumettre mon message</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<!-- <script>
  window.onload = function() {
      window.location.href = "<?= base_url('livre-dor/card/' . $card['livre_or_id']) ?>?download=1";
  };
</script> -->

<script>
function capitalizeWords(str) {
    return str.replace(/\w\S*/g, function(txt){
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}

['livre_or_name', 'livre_or_club_name', 'livre_or_city'].forEach(function(field) {
    const input = document.querySelector('input[name="'+field+'"]');
    if (input) {
        input.addEventListener('blur', function() {
            input.value = capitalizeWords(input.value);
        });
    }
});
</script>

<script>
const textarea = document.getElementById('livre_or_message');
const charCount = document.getElementById('charCount');
textarea.addEventListener('input', function() {
    charCount.textContent = textarea.value.length + ' / 500';
});
</script>

<script>
  window.addEventListener('DOMContentLoaded', function() {
      const params = new URLSearchParams(window.location.search);
      if(params.has('download_card')) {
          const cardId = params.get('download_card');
          // Crée un lien invisible pour forcer le téléchargement
          const link = document.createElement('a');
          link.href = "<?= base_url('livre-dor/card/') ?>" + cardId + "?download=1";
          link.download = "carte_" + cardId + ".png";
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
          // Nettoie l'URL pour éviter de retélécharger au refresh
          window.history.replaceState({}, document.title, window.location.pathname);
      }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>