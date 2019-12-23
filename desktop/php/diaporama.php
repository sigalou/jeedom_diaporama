<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

// Obtenir l'identifiant du plugin
$plugin = plugin::byId('diaporama');
// Charger le javascript
sendVarToJS('eqType', $plugin->getId());
//sendVarToJS('serveurtest', 'lionel dans diaporama.php');

// Accéder aux données du plugin
$eqLogics = eqLogic::byType($plugin->getId());
$logicalIdToHumanReadable = array();
foreach ($eqLogics as $eqLogic)
{
  $logicalIdToHumanReadable[$eqLogic->getLogicalId()] = $eqLogic->getHumanName(true, false);
}
?>

<script>
var logicalIdToHumanReadable = <?php echo json_encode($logicalIdToHumanReadable); ?>

function printEqLogic(data)
{

// On masque la ligne "Activer le widget Playlist" si c'est pas un player
var str=data.logicalId
  if (str.substring(str.length - 6, str.length) != "player")
	$('#widgetPlayListEnable').parent().hide();
	else
	$('#widgetPlayListEnable').parent().show();
 

  //if (data.configuration.family === undefined)
  //{
//	 $('#family').hide(); //ajouté, masque Famille si c'est vide
 // }	
  
	// Traitement de Multiroom sur les infos du device
  $('#multiroom-members').empty();
  if (data.configuration.members === undefined)
  {
     //$('#multiroom-members').append('Configuration incomplete.'); //supprimé
	 $('#multiroom-members').parent().hide(); //ajouté
     return;
  }
  if (data.configuration.members.length === 0)
  {
    $('#multiroom-members').parent().hide();
    return;
  }
  var html = '<ul style="list-style-type: none;">';
  for (var i in data.configuration.members)
  {
    var logicalId = data.configuration.members[i];
    if (logicalId in logicalIdToHumanReadable)
      html += '<li style="margin-top: 5px;">' + logicalIdToHumanReadable[logicalId] + '</li>';
    else
      html += '<li style="margin-top: 5px;"><span class="label label-default" style="text-shadow : none;"><i>(Non configuré)</i></span> ' + logicalId + '</li>';
  }
  html += '</ul>';
  $('#multiroom-members').parent().show();
  $('#multiroom-members').append(html);
}
</script>

<!-- Container global (Ligne bootstrap) -->
<div class="row row-overflow">
  <!-- Container des listes de commandes / éléments -->
  <div class="col-xs-12 eqLogicThumbnailDisplay">
    <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
    <div class="eqLogicThumbnailContainer">
		<!-- + -->
      <div class="cursor eqLogicAction logoPrimary" data-action="add">
			<i class="fas fa-plus-circle" style="font-size : 5em;color:#a15bf7;"></i>
			<br />
			<span style="color:#a15bf7">{{Ajouter}}</span>
		</div>

		<!-- Bouton d accès à la configuration -->
		<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
			<i class="fas fa-wrench" style="font-size : 5em;color:#a15bf7;"></i>
			<br />
			<span style="color:#a15bf7">{{Configuration}}</span>
		</div>

    </div>
    <!-- Début de la liste des objets -->
    <legend><i class="fas fa-table"></i> {{Mes Diaporamas}}</legend>
	<div class="input-group" style="margin-bottom:5px;">
		<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
		<div class="input-group-btn">
			<a id="bt_resetEqlogicSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
		</div>
	</div>	
    <!-- Container de la liste -->
	<div class="panel">
		<div class="panel-body">
			<div class="eqLogicThumbnailContainer prem">
<?php
foreach($eqLogics as $eqLogic) {

	if (($eqLogic->getConfiguration('devicetype') != "Smarthome") && ($eqLogic->getConfiguration('devicetype') != "Player") && ($eqLogic->getConfiguration('devicetype') != "PlayList")) {

		$opacity = ($eqLogic->getIsEnable()) ? '' : ' disableCard';
		echo '<div class="eqLogicDisplayCard cursor prem '.$opacity.'" data-eqLogic_id="'.$eqLogic->getId().'" >';
		echo '<img class="lazy" src="'.$plugin->getPathImgIcon().'" style="min-height:75px !important;" />';
		echo "<br />";
		echo '<span class="name">'.$eqLogic->getHumanName(true, true).'</span>';
		echo '</div>';
	}
}
?>
			</div>
		</div>
    </div>


	
  </div>
  <!-- Container du panneau de contrôle -->
  <div class="col-lg-12 eqLogic" style="display: none;">
    <!-- Bouton sauvegarder -->
    <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
    <!-- Bouton Supprimer -->
    <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
    <!-- Bouton configuration avancée -->
    <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a>
   <!-- Liste des onglets -->
    <ul class="nav nav-tabs" role="tablist">
      <!-- Bouton de retour -->
      <li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
      <!-- Onglet "Equipement" -->
      <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
      <!-- Onglet "Commandes" -->
      <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
    </ul>
    <!-- Container du contenu des onglets -->
    <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
      <div role="tabpanel" class="tab-pane active" id="eqlogictab">
        <br/>
        <div class="row">
          <div class="col-sm-6">
            <form name="formulaire" class="form-horizontal">
              <fieldset>
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Nom de l'équipement Jeedom}}</label>
                  <div class="col-sm-8">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement Amazon}}"/>
                  </div>
                </div>
                <!-- Onglet "Objet Parent" -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Objet parent}}</label>
                  <div class="col-sm-6">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;"/>
                    <select class="eqLogicAttr form-control" data-l1key="object_id">
                    <option value="">{{Aucun}}</option>
<?php
foreach (jeeObject::all() as $object)
    echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
?>
                    </select>
                  </div>
                </div>
				<!-- Onglet "Device Playlist" -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Option</label>
                  <div class="col-sm-8" id="widgetPlayListEnable">
                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="widgetPlayListEnable" />{{Activer le widget Playlist}}</label>
                  </div>
	</div>
                <!-- Catégorie" -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Catégorie}}</label>
                  <div class="col-sm-8">
<?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value)
{
    echo '<label class="checkbox-inline">';
    echo '  <input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
    echo '</label>';
}
?>
                  </div>
                </div>
                <!-- Onglet "Active Visible" -->
                <div class="form-group">
                  <label class="col-sm-4 control-label"></label>
                  <div class="col-sm-8">
                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                  </div>
                </div>
			<br><br> 
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Largeur des images}}</label>
                  <div class="col-sm-2">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="largeurPhoto" placeholder="{{250}}"/>
                  </div>
                </div>
							<div class="form-group">
                  <label class="col-sm-4 control-label"></label>
                  <div class="col-sm-8">
				<input type="checkbox" name='caseLocal' onclick="setTimeout(function(){CaseCocheeLocal()},500)" style="position:relative;top:2px;" class="eqLogicAttr" title="Les photos sont stockées sur la même machine que celle de Jeedom" data-l1key="configuration" data-l2key="centrerLargeur"/> {{Centrer sur la largeur}}
                  </div>
                </div>
				
				
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Hauteur des images}}</label>
                  <div class="col-sm-2">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="hauteurPhoto" placeholder="{{250}}"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Angles arrondis}}</label>
                  <div class="col-sm-2">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="arrondiPhoto" placeholder="{{30%}}"/>
                  </div>
                </div>				<div class="form-group">
                <label class="col-sm-4 control-label">{{Nombre de photos à générer<br>(2 par défaut)}}</label>
                <div class="col-sm-6">
                    <select class="eqLogicAttr form-control" data-l1key='configuration' data-l2key='nbPhotosaGenerer'>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                        <option value='6'>6</option>
                        <option value='7'>7</option>
                        <option value='8'>8</option>                    
                        <option value='9'>9</option>				
						</select>
                </div>
            </div>
			
			<br><br> 
			<div class="form-group">
                  <label class="col-sm-4 control-label"></label>
                  <div class="col-sm-8">
				<input type="checkbox" name='caseLocal' onclick="setTimeout(function(){CaseCocheeLocal()},500)" style="position:relative;top:2px;" class="eqLogicAttr" title="Les photos sont stockées sur la même machine que celle de Jeedom" data-l1key="configuration" data-l2key="stockageLocal"/> {{Stockage des photos en local}}
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Chemin local des photos}}</label>
                  <div class="col-sm-8">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cheminDiaporama" placeholder="{{/../images/}}"/>
                  </div>
                </div>	

<?php

// Ne marche pas
//<input type="checkbox" class="cmdAttr tooltips" title="Spécifie" data-l1key="configuration" data-l2key="type_calcul_tendance"/> {{Journée entière}}<br/></span>'


//log::add('diaporama', 'debug', "test:".$eqLogic->getConfiguration('cheminDiaporamaValide'));
// Pour SAMBA
$sambaActif	= config::byKey('samba::enable')	;
log::add('diaporama', 'debug', "sambaActif:".$sambaActif);				
if ($sambaActif)
{
	//echo 'Samba est actif';
	
$sambaIP	= config::byKey('samba::backup::ip')	;
log::add('diaporama', 'debug', "sambaIP:".$sambaIP);

$sambaUsername	= config::byKey('samba::backup::username')	;
log::add('diaporama', 'debug', "Username:".$sambaUsername);

$sambaPassword	= config::byKey('samba::backup::password')	;
log::add('diaporama', 'debug', "sambaPassword:".$sambaPassword);

$sambaShare	= config::byKey('samba::backup::share')	;
log::add('diaporama', 'debug', "sambaShare:".$sambaShare);

$sambaFolder	= config::byKey('samba::backup::folder')	;



/*
log::add('diaporama', 'debug', "sambaFolder:".$sambaFolder);
		$return = array();
		foreach (repo_samba::ls(config::byKey('samba::backup::folder')) as $file) {
			if (strpos($file['filename'],'.tar.gz') !== false) {
				$return[] = $file['filename'];
			}
		}
	*/
//log::add('diaporama', 'debug', "rep:".json_encode($return));	
	
	?>


			<br><br>
						<div class="form-group">
                  <label class="col-sm-4 control-label"></label>
                  <div class="col-sm-8">
				<input type="checkbox" name='caseSamba' onclick="setTimeout(function(){CaseCocheeSamba()},500)" style="position:relative;top:2px;" class="eqLogicAttr" title="Les photos sont accessibles via Samba" data-l1key="configuration" data-l2key="stockageSamba"/> {{Stockage des photos sur le réseau via Samba}}
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Chemin Samba des photos}}</label>
                  <div class="col-sm-8"><?php echo $sambaShare?>
                    <input type="text" name="inputSamba" class="eqLogicAttr form-control" style="width: 50%" data-l1key="configuration" data-l2key="dossierSambaDiaporama" placeholder="{{/mesPhotos}}"/>
                  </div>
                </div>	
				<br>



<?php	
	

	
}
else
{

	?><br><br>						<div class="form-group">
                  <label class="col-sm-4 control-label"></label>
                  <div class="col-sm-8">
				<input type="checkbox" name='caseSamba' onclick="setTimeout(function(){CaseCocheeSamba()},500)" style="position:relative;top:2px;" class="eqLogicAttr" title="Les photos sont accessibles via Samba" data-l1key="configuration" data-l2key="stockageSamba"/> {{Stockage des photos en réseau via Samba}}
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Chemin Samba des photos}}</label>
                  <div class="col-sm-8">
                    <b>Samba</b> est inactif dans la configuration de Jeedom, donc impossible d'utiliser un chemin Samba
                  </div>
                </div>				
<?php
} ?>
              </fieldset>
			
            </form>
			
          </div>
		  
<!--		  
<div class="cursor" id="bt_media" data-l1key="logicalId" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
	<center>
	<i class="fa loisir-musical7" style="font-size : 6em;color:#767676;"></i>
	</center>
<span style="font-size : 1.1em;position:relative; top : 25px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Info Média}}</center></span>
</div>
	 	Castré par Nebz et HadesDT   
<div class="cursor" id="bt_test" data-l1key="logicalId" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
<center>
<i class="fa loisir-musical7" style="font-size : 6em;color:#767676;"></i>
</center>
<span style="font-size : 1.1em;position:relative; top : 25px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Test}}</center></span>
</div>
-->
<?php

	//if ($eqLogic->getConfiguration('devicetype')!="Smarthome")
	//{
		
		//echo ">>".$eqLogic->getConfiguration('cheminDiaporamaValide');
		
?>
          <div class="col-sm-6 alert-<?php
		  
				 if ($eqLogic->getConfiguration('cheminDiaporamaValide')=="ok")
				  echo "success";
				elseif ($eqLogic->getConfiguration('cheminDiaporamaValide')=="nok")
				  echo "danger";		  
				else
				  echo "warning";		  
	  
		  ?> ">
            <br><br><form class="form-horizontal">
              <fieldset>
      
	
				<span style="display:none" class="eqLogicAttr" data-l1key="configuration" data-l2key="cheminDiaporamaValide"></span>
				<span style="display:none" class="eqLogicAttr" data-l1key="configuration" data-l2key="localEtat"></span>
				<span style="display:none" class="eqLogicAttr" data-l1key="configuration" data-l2key="sambaEtat"></span>
				<div class="form-group">
				  <label class="col-sm-4 control-label">{{Lien au dossier Photos}}</label>
                      <img style="max-height : 40px;float:left;margin:0 10px 0 10px;" src="core/img/no_image.gif"  id="img_device" class="img-responsive" title="Etat de la connexion au dossier Photos" onerror="this.src='plugins/diaporama/images/question.png'"/>
					  <img style="max-height : 40px;float:left;margin:0 10px 0 10px;" src="core/img/no_image.gif"  id="img_local" class="img-responsive" title="Etat de la connexion au dossier Local" onerror="this.src='plugins/diaporama/images/question.png'"/>
					  <img style="max-height : 40px;float:left;margin:0 10px 0 10px;" src="core/img/no_image.gif"  id="img_samba" class="img-responsive" title="Etat de la connexion au dossier via Samba" onerror="this.src='plugins/diaporama/images/question.png'"/>				</div>
				
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Les photos sont sur}}</label>
                  <div class="col-sm-8">
                      <span style="position:relative;top:+5px;" class="eqLogicAttr" data-l1key="configuration" data-l2key="cheminDiaporamaComplet"></span>
                  </div>
                </div>
                <div class="form-group" id="family">
                  <label class="col-sm-4 control-label">{{Nombre de photos}}</label>
                  <div class="col-sm-8">
                      <span style="position:relative;top:+5px;left:+5px;" class="eqLogicAttr" data-l1key="configuration" data-l2key="nombrePhotos"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Dernière mise à jour de l'état du lien}}</label>
                  <div class="col-sm-8">
                      <span style="position:relative;top:+5px;" class="eqLogicAttr" data-l1key="configuration" data-l2key="derniereMAJ"></span>
                  </div>
                </div><div class="form-group">
                  <label class="col-sm-4 control-label">{{}}</label>
                  <div class="col-sm-8">
                      <span style="position:relative;top:+5px;" class="eqLogicAttr" data-l1key="configuration" data-l2key="cheminDiaporamaMessage"></span>
                  </div>
                </div><br><?php
		  if ($eqLogic->getConfiguration('stockageSamba')=="1")
			  echo '<center><a id="bt_testLienPhotos" class="btn btn-default pull-center"><i class="far fa-check-circle"></i> {{Tester le lien vers le dossier des photos}}</a></center>';
             ?> </fieldset>
            </form>
				

<br>
          </div>
		  
        </div>
      </div>

      <div role="tabpanel" class="tab-pane" id="commandtab">
        

        <table id="table_cmd" class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th style="width: 40px;">#</th>
              <th style="width: 200px;">{{Nom}}</th>
              <th style="width: 150px;">{{Type}}</th>
              <th style="width: 300px;">{{Commande & Variable}}</th>
              <th style="width: 40px;">{{Min}}</th>
              <th style="width: 40px;">{{Max}}</th>
              <th style="width: 150px;">{{Paramètres}}</th>
              <th style="width: 100px;"></th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
		

		
		<form class="form-horizontal">
          <fieldset>
            <div class="form-actions">
              <a class="btn btn-success btn-sm cmdAction" id="bt_addespeasyAction"><i class="fa fa-plus-circle"></i> {{Ajouter une commande action}}</a>
            </div>
          </fieldset>
        </form>
		
      </div>






    </div>
  </div>
</div>
<?php include_file('desktop', 'diaporama', 'js', 'diaporama'); ?>
<?php include_file('desktop', 'diaporama', 'css', 'diaporama'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
<script>
$('#in_searchEqlogic').off('keyup').keyup(function () {
  var search = $(this).value().toLowerCase();
  search = search.normalize('NFD').replace(/[\u0300-\u036f]/g, "")
  if(search == ''){
    $('.eqLogicDisplayCard.prem').show();
    $('.eqLogicThumbnailContainer.prem').packery();
    return;
  }
  $('.eqLogicDisplayCard.prem').hide();
  $('.eqLogicDisplayCard.prem .name').each(function(){
    var text = $(this).text().toLowerCase();
    text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, "")
    if(text.indexOf(search) >= 0){
      $(this).closest('.eqLogicDisplayCard.prem').show();
    }
  });
  $('.eqLogicThumbnailContainer.prem').packery();
});
</script>
