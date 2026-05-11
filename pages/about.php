<?php include_once("../inclus/header.php") ?>
<?php include_once("../inclus/navbar.php") ?>

    <section class="hero about p-5 text-white">
        <div class="container text-center">
            <h1 class="display-5 fw-bold">A propos de nous</h1>
            <p>Qui sommes-nous ?</p>
        </div>
    </section>

<div class="m-0 p-3" style="min-height: 600px;">
    <div class="justify-content-center d-flex my-2">
        <hr class="flex-grow-1">
        <span class="h2 text-primary mx-2"><strong>Introduction</strong></span>
        <hr class="flex-grow-1">
    </div>
    <div id="intro" class="border rounded-3 m-auto p-2 border-light-subtle bg-body-tertiary text-left">
        <div class="p-3 h5">
            <i class="bi bi-quote fw-bold display-6"></i>
            <p>
            L'ONG est né d'une conviction simple « chaque personne porte en lui un potentiel que 
            l'éducation peut révéler ». Lors de nos rencontres avec certaines couches de la population, 
            nous avons vu trop d'espoirs freinés par le manque de soutien ou d'espérance, de ressources.
            Le nom « Be Hope » exprime une injonction à la fois douce et forte ; qui inspire à être 
            l'espérance ou à agir pour un avenir meilleur. 
            </p>
            <p>
                <ul>
                    <li>
                        « <strong>Be Hope</strong> » ambitionne de travailler au quotidien pour 
                        transformer l'espoir en moyens concrets. Nous croyons que l'espoir devient 
                        réussite lorsqu'il est soutenu par l'action, la bienveillance et la rigueur pédagogique.
                    </li>
                    <li>
                        « <strong>Be Hope</strong> » n'est pas seulement un nom : c'est un appel à se lever avec 
                        l'autre pour apprendre, grandir et réussir.
                    </li>
                </ul>
            </p>
        </div>
    </div>

    <div class="row p-1">
        <div class="col-md-6">
            <!-- SIGNIFICATION -->
            <div class="justify-content-center d-flex my-2">
                <hr class="flex-grow-1">
                <span class="h2 text-primary mx-2"><strong>Signification <i class="bi bi-blockquote-left"></i></strong></span>
                <hr class="flex-grow-1">
            </div>

            <div id="" class="m-auto p-2 border rounded-3  border-light-subtle bg-body-tertiary text-left">
                <div class="p-3 row" style="min-height: 150px;">
                    <div class="">
                        « Be » : présence, agir, être aux cotés
                    </div>
                    <div class="">
                        « Hope » : espoir, confiance en l'avenir 
                    </div>
                    <div class="">
                        « Be Hope » : inciter chacun à devenir porteur d'espérance et d'opportunités
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- SLOGAN -->
            <div class="justify-content-center d-flex my-2">
                <hr class="flex-grow-1">
                <span class="h2 text-success mx-2"><strong>Slogan <i class="bi bi-mic-fill"></i> </strong></span>
                <hr class="flex-grow-1">
            </div>

            <div id="" class="border rounded-3 m-auto p-2 border-light-subtle bg-body-tertiary text-left">
                <div class="p-3 row text-center">
                    <div class="col " style="min-height: 120px;">
                        « Be Hope » ; Agir pour un avenir meilleur ou Agir pour donner de l'espoir
                    </div>
                </div>
            </div>
        </div>
    </div>
        

    <div class="row p-1">
        <div class="col-md-6">
            <!-- VALEUR -->
            <div class="justify-content-center d-flex my-2">
                <hr class="flex-grow-1">
                <span class="h2 text-danger mx-2"><strong>Valeur <i class="bi bi-brightness-high"></i> </strong></span>
                <hr class="flex-grow-1">
            </div>
            <div id="intro" class="border rounded-3 m-auto p-2 border-light-subtle bg-body-tertiary text-left">
                <div class="p-3 row">
                    <div class="col align-items-center" style="min-height: 160px;">
                        <ul>
                            <li><strong>Bienveillance :</strong> créer un environnement sécurisant et stimulant</li>
                            <li><strong>Inclusion : </strong>offrir des chances égales à tous, sans distinction</li>
                            <li><strong>Participation :</strong> implication active des bénéficiaires dans toutes les étapes de nos projets</li>
                            <li><strong>Excellence :</strong> dans chacune de nos interventions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- NOS ACTIONS -->
            <div class="justify-content-center d-flex my-2">
                <hr class="flex-grow-1">
                <span class="h2 text-dark mx-2"><strong>Nos actions <i class="bi bi-grid-3x3-gap-fill"></i> </strong></span>
                <hr class="flex-grow-1">
            </div>
        
            <div id="intro" class="border rounded-3 m-auto p-2 border-light-subtle bg-body-tertiary text-left">
                <div class="p-3 row">
                    <div class="col h-100" style="min-height: 160px;">
                        <ul>
                            <li>Accompagnement pour accroitre les résultats scolaires</li>
                            <li>Aider chaque jeune à identifier ses talents et dons, 
                            puis développer ses compétences par des parcours de découverte, 
                            mentorat et formations ciblées</li>
                            <li>Renforcement des capacités des acteurs locales</li>
                            <li>Mobilisation communautaire & accès aux ressources</li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<?php include("../inclus/footer.php"); ?>
<script>
    $(document).ready(function(){
        $('#navitem2').addClass("active");
    });
</script>