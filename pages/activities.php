<?php include("../inclus/header.php"); ?>



<?php include_once("../inclus/navbar.php") ?>

    <section class="hero p-5 text-white">
        <div class="container text-center">
            <h1 class="display-5 fw-bold">Nos Activités</h1>
            <p>Découvrez les actions menées par notre organisation</p>
        </div>
    </section>

    <div class="m-0 p-3" style="min-height: 600px;">
            <!-- FILTRES -->
            <div class="card p-3 mb-4 shadow-sm">
                <div class="row g-2">

                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Rechercher une activité...">
                    </div>

                    <div class="col-md-3">
                        <select class="form-select">
                            <option>Toutes les années</option>
                            <option>2026</option>
                            <option>2025</option>
                            <option>2024</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select class="form-select">
                            <option>Tous les lieux</option>
                            <option>Cotonou</option>
                            <option>Parakou</option>
                            <option>Abomey</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-success w-100">Filtrer</button>
                    </div>

                </div>
            </div>

            <!-- LISTE ACTIVITÉS -->
            <div class="row">

                <!-- CARD 1 -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="/images/acti/annie-spratt-0cgpyigyIkM-unsplash.jpg" class="card-img-top">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Campagne de sensibilisation VIH</h5>
                            <p class="text-muted">Cotonou | 12 Mars 2026</p>
                            <p class="card-text">Sensibilisation des populations sur les méthodes de prévention du VIH.</p>
                            <a href="#" class="btn btn-success mt-auto">Voir détails</a>
                        </div>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="/images/acti/annie-spratt-cVEOh_JJmEE-unsplash.jpg" class="card-img-top">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Formation des agents communautaires</h5>
                            <p class="text-muted">Parakou | 20 Février 2026</p>
                            <p class="card-text">Renforcement des capacités des agents de santé sur le terrain.</p>
                            <a href="#" class="btn btn-success mt-auto">Voir détails</a>
                        </div>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="/images/acti/emmanuel-ikwuegbu-VC6MGt9ZoBA-unsplash.jpg" class="card-img-top">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Distribution de kits sanitaires</h5>
                            <p class="text-muted">Abomey | 05 Janvier 2026</p>
                            <p class="card-text">Distribution de matériel sanitaire aux populations vulnérables.</p>
                            <a href="#" class="btn btn-success mt-auto">Voir détails</a>
                        </div>
                    </div>
                </div>

                <!-- CARD 4 -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1526256262350-7da7584cf5eb" class="card-img-top">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Campagne de vaccination</h5>
                            <p class="text-muted">Porto-Novo | 15 Avril 2026</p>
                            <p class="card-text">Vaccination des enfants contre les maladies évitables.</p>
                            <a href="#" class="btn btn-success mt-auto">Voir détails</a>
                        </div>
                    </div>
                </div>

                <!-- CARD 5 -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="/images/acti/dugba-cauley-hushie-mRhim8C5ReI-unsplash.jpg" class="card-img-top">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Atelier sur l’éducation</h5>
                            <p class="text-muted">Bohicon | 10 Mars 2026</p>
                            <p class="card-text">Formation sur les méthodes pédagogiques modernes.</p>
                            <a href="#" class="btn btn-success mt-auto">Voir détails</a>
                        </div>
                    </div>
                </div>

                <!-- CARD 6 -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="/images/acti/dayne-topkin-xTmqoidRoKQ-unsplash.jpg" class="card-img-top">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Sensibilisation hygiène</h5>
                            <p class="text-muted">Dassa | 28 Février 2026</p>
                            <p class="card-text">Promotion des bonnes pratiques d’hygiène dans les écoles.</p>
                            <a href="#" class="btn btn-success mt-auto">Voir détails</a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- PAGINATION -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link">Précédent</a></li>
                    <li class="page-item active"><a class="page-link">1</a></li>
                    <li class="page-item"><a class="page-link">2</a></li>
                    <li class="page-item"><a class="page-link">3</a></li>
                    <li class="page-item"><a class="page-link">Suivant</a></li>
                </ul>
            </nav>
    </div>

<?php include("../inclus/footer.php"); ?>
<script>
    $('#navitem3').addClass("active");
</script>