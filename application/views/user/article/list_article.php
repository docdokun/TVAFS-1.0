<script type="text/javascript">
    var id_carnet_voyage = '<?php echo $id_carnet_voyage; ?>'
</script>

<legend>Gestion articles du carnet : </legend>
<script type="text/javascript" src = "<?php echo asset_url(''); ?>js/article.js" ></script>
<a data-toggle="modal" data-target="#popUp" class="buttonRetourBoUtilisateur retourListCarnet"> 
    <span class="placementGlyphicon">
        <span class="glyphicon glyphicon-arrow-left"></span>
    </span>
    Retour
</a>
<div class="content_article">
    <div class="messageAlerteCarnet">
        <div id="alertType"></div>
    </div>

    <a class="buttonAjouterBoUtilisateur buttonAjouterArticle"> 
        <span class="placementGlyphicon">
            <span class="glyphicon glyphicon-plus"></span>
        </span>
        Ajouter Article
    </a>
    <br><br/>
    <div class="divTable">
        <?php
        if ($articles) {
            ?>
            <div class="table-responsive">
                <table class="table-article table">
                    <tr>
                        <th  class="tdPetit">#</th>
                        <th>Titre</th>
                        <th class="tdMoyen">Editer</th>
                        <th class="tdPetit">Supprimer</th>
                    </tr>
                    <?php
                    $i = 0;
                    foreach ($articles as $article) {
                        $i++;
                        ?>
                        <tr>
                            <td  class="tdPetit"> 
                                <?php echo $i; ?>
                            </td>
                            <td>
                                <span id="<?php echo $article->id; ?>"><?php echo $article->titre; ?></span>
                            </td>

                            <td class="tdPetit">
                                <a href="#" class="buttonEditArticle" data-id="<?php echo $article->id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                            </td>
                            <td class="tdPetit">
                                <a class="deleteArticle" data-id="<?php echo $article->id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
                            </td>

                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            <?php
        } else {
            ?>
            <br/><br/>
            <div class="showText">
                Il n'y a pas d'article
            </div>
            <?php
        }
        ?>
    </div>
</div>