<?php include("./inclus/header.php"); ?>
<?php include('./inclus/navbar.php') ?>

<div class="m-0 p-0">
    <div class="container-fluid text-light p-5" style="height: 450px; background: linear-gradient(
            rgba(0,0,0,0.6),
            rgba(0,0,0,0.4)
        ), url('./images/im (3).jpg'); ">
        <div class="mt-5 w-50  align-items-end">
            <div id="mission-box" class="m-0 row p-1">
                <div class="h1"><strong>Agir pour le développement communautaire</strong></div>
                <div class="">Notre organisation a pour mission de vous donner satsifaction.</div>
            </div>
            <div class="m-0 p-2">
                
                    <a href="#activites" class="btn btn-primary m-1">Découvrir nos activités</a>
                
            
                    <a class="btn btn-success m-1">Nous contacter</a>
                
            </div>
        </div>
    </div>
    <div class="container justify-content-center p-2">
        <?php include("./inclus/engagement.php"); ?>

        <?php include("./inclus/activites.php"); ?>
        
        <?php include("./inclus/galerie.php"); ?>
    </div>

</div>

<?php include("./inclus/footer.php"); ?>