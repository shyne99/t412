<?php
require_once __DIR__ . '/' . 'utils.class.php';
$t411 = new Utils;
$state = $t411->getCredentials();
$reponse = $t411->getSeries();

if(isset($_POST['name']) && !empty($_POST['name'])) {
  $t411->addSerie($_POST['name'], $_POST['saison'], $_POST['current'], $_POST['last'], $_POST['langue']);
  header('Refresh: 0');
}
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <title>t412 | Mes séries</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/navbar.css">
  </head>
  <body>

  <div class="container">
<?php require_once 'navbar.php'; ?>

    <ol class="breadcrumb">
      <li><a href="/">Torrents</a></li>
      <li><a href="/suivi/">Mes séries</a></li>
    </ol>

<?php if (empty($state->user)) { ?>

    <div class="jumbotron">
      <h1 style="color:red">Erreur</h1>
      <p>Veuillez indiquer vos <a href="/seedbox/">identifants transmission</a> pour profiter de ce service.</p>
    </div>

<?php } else { ?>

    <div class="jumbotron">
      <h1 style="color:green">Mes séries</h1>
      <p>En avant-première mondiale, cet outil vous permet de suivre des séries et <b>d'automatiser leur téléchargement </b>(une <a href="/seedbox/">seedbox</a> est nécessaire). Lors de chaque téléchargement, un mail de confirmation vous sera envoyé à l'adresse <i><?php echo $t411->dechiffrer($state->email); ?></i>.</p>
      <p>Les vérifications sont effectuées <b>une fois par heure</b>.</p>
    </div>

    <div>
      <h1><small>Ajouter une série</small></h1>
    </div>

    <div class="row">
      <form action="/suivi/" method="post">
        <div class="col-xs-6 col-md-3 col-lg-3 pa">
          <input type="text" class="form-control" name="name" id="name" placeholder="Nom (ex: NCIS)" required>
        </div>

        <div class="col-xs-6 col-md-2 col-lg-1 pa">
          <input type="text" class="form-control" name="saison" id="season" maxlength="2" placeholder="Saison" required>
        </div>

        <div class="col-xs-6 col-md-3 col-lg-2 pa">
          <input type="text" class="form-control" name="current" id="current" maxlength="2" data-toggle="popover" data-trigger="hover" data-placement="auto top" title="Épisode actuel" data-content="Exemple: 1. Le suivi commencera à partir de cet épisode." placeholder="Épisode actuel (ex: 7)" required>
        </div>

        <div class="col-xs-6 col-md-3 col-lg-2 pa">
          <input type="text" class="form-control" name="last" id="last" maxlength="2" data-toggle="popover" data-trigger="hover" data-placement="auto top" title="Dernier épisode" data-content="Exemple: 24. Le dernier épisode de la saison." placeholder="Nombre d'ép. (ex: 24)" required>
        </div>

        <div class="col-xs-6 col-md-3 col-lg-2">
          <select name="langue" id="langage" class="form-control">
            <option value="1210">VFF</option>
            <option value="1209">VO</option>
            <option value="1212">Multi (VO/VFF)</option>
            <option value="1216">VOST/FR</option>
           </select>
        </div>

        <div class="col-xs-6 col-md-3 col-lg-2">
          <button class="btn btn-primary btn-block" id="add" type="submit"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Ajouter</button>
        </div>
      </form>
    </div>

    <div>
      <h1><small>Mes séries</small></h1>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th class="textcentered">Nom</th>
            <th class="textcentered">Saison</th>
            <th class="textcentered">Status</th>
            <th class="textcentered">Épisode actuel</th>
            <th class="textcentered">Dernier épisode</th>
            <th class="textcentered">Langue</th>
            <th class="textcentered">Action</th>
          </tr>
        </thead>
        <tbody>

<?php foreach ($reponse as $value) { ?>
          <tr>
            <td nowrap class="textcentered"><?php echo $value->name;?></td>
            <td class="textcentered"><?php echo $value->saison; ?></td>
            <td class="textcentered"><?php echo $value->current == $value->last ? '<span style="color:green">Terminé</span>' : 'En cours'; ?></td>
            <td nowrap class="textcentered"><?php echo $value->current;?></td>
            <td nowrap class="textcentered"><?php echo $value->last;?></td>
            <td nowrap class="textcentered"><?php echo $t411->getLanguage($value->language);?></td>
            <td class="textcentered"><a href="/delete/<?php echo $value->id;?>"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Supprimer</a></td>
          </tr>
<?php } ?>
        </tbody>
      </table>
    </div>
<?php } ?>
  </div>
  <script src="/js/jquery.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script>$('#current').popover();</script>
  <script>$('#last').popover();</script>
  </body>
</html>
