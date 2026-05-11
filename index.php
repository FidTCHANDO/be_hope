<?php include("./inclus/header.php"); ?>
<?php include('./inclus/navbar.php') ?>

<div class="m-0 p-0">
    <section class="hero container-fluid text-light p-5">
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
    </section>
    <div class="container-fluid w-75 mx-auto justify-content-center p-2">
        <?php include("./inclus/engagement.php"); ?>

        <?php include("./inclus/recent_activities.php"); ?>
        
        <?php include("./inclus/galerie.php"); ?>

        <?php include("./inclus/partenaires.php"); ?>
    </div>

</div>


<?php include("./inclus/footer.php"); ?>
<script>
    $(document).ready(function(){
        $('#navitem1').addClass("active");
    });

</script>