<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class diaporama extends eqLogic {
	
		
public static function cron($_eqlogic_id = null) {

}

public static function enregistreAlbumFB($Id, $Albums) {
	
	
	

	
	
$Albums=json_decode($Albums);
		//log::add('diaporama', 'debug', "Lancement de enregistreAlbumFB ".$Id . " / ".$Albums);				
		//log::add('diaporama', 'debug', "Lancement de enregistreAlbumFB ".$Id . " / ".$Albums[0][0]);				
		//log::add('diaporama', 'debug', "Lancement de enregistreAlbumFB ".$Id . " / ".$Albums[0][1]);				
		//log::add('diaporama', 'debug', "Lancement de enregistreAlbumFB ".$Id . " / Hello {$Albums['10215997270540369']}!");				

	$device = eqLogic::byId($Id);
	if (is_object($device)) {
					
					// Enregistrement dans Configuration du device en cours ($this)
					$device->setConfiguration('arrayAlbumsFacebook', $Albums);
					log::add('diaporama', 'debug', 'On enregistre : '.json_encode($Albums).' dans plugin/device('.$Id.')/config/arrayAlbumsFacebook');
					$device->save();
		
		//----------------------------------------------------------------------------------------------------------------------------------------
		
		//$tousLesAlbums=config::byKey('albumsFacebook', 'diaporama', '0');
					//foreach ($Albums as $key2 => $value2) {
				//							log::add('diaporama', 'debug', 'id:'.$Albums[$key2][0].'  value:'.$Albums[$key2][1]);
				//							log::add('diaporama', 'debug', 'value2:'.$value2);
					//}			
											//log::add('diaporama', 'debug', 'Albums:'.json_encode($Albums));
					
					//foreach ($tousLesAlbums as $key => $value) {
				//str_replace('"', '', $value['id'])
				//$key = array_search('"'.$value['id'].'"', $Albums);
				//$key3 = array_search('10215997270540369', $tousLesAlbums);
											//log::add('diaporama', 'debug', 'tout:'.json_encode($value));
											//log::add('diaporama', 'debug', 'id:'.$value['id']);
											//log::add('diaporama', 'debug', 'key:'.$key3);
											//log::add('diaporama', 'debug', 'name:'.json_encode($value['name']));
											//log::add('diaporama', 'debug', 'count:'.json_encode($value['count']));
											//log::add('diaporama', 'debug', 'created_time:'.json_encode($value['created_time']));
			
											//echo '<br><br><br>********'.json_encode($value).'<br />';
											//echo 'id:'.$value['id'].'<br />';
											//echo 'name:'.$value['name'].'<br />';
											//log::add('diaporama', 'debug', 'count:'.$value['count']);
											
											//echo 'count:'.$value['count'].'<br />';
											//echo 'created_time:'.$value['created_time'].'/'.date('d-m-Y', strtotime($value['created_time']));'<br />';
											//echo '<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="albumsFacebook" data-l3key="albumfb_'.$value['id'].'" />' . $value['name']. ' ('.$value['count'].' photos, créé le '. date('d-m-Y', strtotime($value['created_time'])).')<br>';

										//	}		
		//log::add('diaporama', 'debug', '$tousLesAlbums->>>'.json_encode($tousLesAlbums));
		
/*		$diapo = array();
		$device->setConfiguration('localEtat', "nok"); 
		$sambaShare	= config::byKey('samba::backup::share')	;
		log::add('diaporama', 'debug', 'sambaShare->>>'.$sambaShare);
		log::add('diaporama', 'debug', 'dossierSambaDiaporama->>>'.$device->getConfiguration('dossierSambaDiaporama'));
		$dos=$sambaShare.$device->getConfiguration('dossierSambaDiaporama');
			try {
				$nbPhotos=self::lsjpg_count($device->getConfiguration('dossierSambaDiaporama'));
				$device->setConfiguration('cheminDiaporamaMessage', "");
			}
			catch(Exception $exc) {
				//log::add('diaporama', 'error', __('Erreur pour ', __FILE__) . ' : ' . $exc->getMessage());
				$device->setConfiguration('cheminDiaporamaMessage', $exc->getMessage());
				$nbPhotos=0;
			}		
		$device->setConfiguration('sambaEtat', "ok"); 
		log::add('diaporama', 'debug', "sambaEtat:ok");				
		$device->setConfiguration('cheminDiaporamaComplet', $dos);
		$device->setConfiguration('nombrePhotos', $nbPhotos);
		$device->setConfiguration('derniereMAJ', date("d-m-Y H:i:s"));
			if ($nbPhotos==0) {
				$device->setConfiguration('cheminDiaporamaValide', "nok");
				$device->setConfiguration('localEtat', "nok"); 
				$device->setConfiguration('sambaEtat', "nok"); 
			}
			else {
				$device->setConfiguration('cheminDiaporamaValide', "ok");
			}
		$device->save();*/
	} else
	event::add('jeedom::alert', array('level' => 'warning', 'page' => 'diaporama', 'message' => __('Device id:'.$Id.' introuvable', __FILE__)));

	}

public static function scanLienPhotos($Id) {

	$device = eqLogic::byId($Id);
	if (is_object($device)) {
		$diapo = array();
		$device->setConfiguration('localEtat', "nok"); 
		$sambaShare	= config::byKey('samba::backup::share')	;
		log::add('diaporama', 'debug', 'sambaShare->>>'.$sambaShare);
		log::add('diaporama', 'debug', 'dossierSambaDiaporama->>>'.$device->getConfiguration('dossierSambaDiaporama'));
		$dos=$sambaShare.$device->getConfiguration('dossierSambaDiaporama');
			try {
				$nbPhotos=self::lsjpg_count($device->getConfiguration('dossierSambaDiaporama'));
				$device->setConfiguration('cheminDiaporamaMessage', "");
			}
			catch(Exception $exc) {
				//log::add('diaporama', 'error', __('Erreur pour ', __FILE__) . ' : ' . $exc->getMessage());
				$device->setConfiguration('cheminDiaporamaMessage', $exc->getMessage());
				$nbPhotos=0;
			}		
		$device->setConfiguration('sambaEtat', "ok"); 
		log::add('diaporama', 'debug', "sambaEtat:ok");				
		$device->setConfiguration('cheminDiaporamaComplet', $dos);
		$device->setConfiguration('nombrePhotos', $nbPhotos);
		$device->setConfiguration('derniereMAJ', date("d-m-Y H:i:s"));
			if ($nbPhotos==0) {
				$device->setConfiguration('cheminDiaporamaValide', "nok");
				$device->setConfiguration('localEtat', "nok"); 
				$device->setConfiguration('sambaEtat', "nok"); 
			}
			else {
				$device->setConfiguration('cheminDiaporamaValide', "ok");
			}
		$device->save();
	} else
	event::add('jeedom::alert', array('level' => 'warning', 'page' => 'diaporama', 'message' => __('Device id:'.$Id.' introuvable', __FILE__)));

	}

	
	public function sortBy($field, &$array, $direction = 'asc') {
		usort($array, create_function('$a, $b', '
		$a = $a["' . $field . '"];
		$b = $b["' . $field . '"];
		if ($a == $b) return 0;
		$direction = strtolower(trim($direction));
		return ($a ' . ($direction == 'desc' ? '>' : '<') . ' $b) ? -1 : 1;
    	'));
		return true;
	}

public function redimensionne_Photo($tirageSort,$maxWidth,$maxHeight, $arrondiPhoto, $centrerLargeur)  {
	
	
	//log::add('diaporama', 'debug', '**********************début redimensionne_Photo*'.$tirageSort.'/'.$maxWidth.'/'.$maxHeight.'/'.$arrondiPhoto.'**********************************');
    $fichier='/tmp/diaporama_'.$this->getId()."_".$tirageSort.'_rotate.jpg';
    $fichiercomplet='/var/www/html'.$fichier;
	
	if (!file_exists($fichiercomplet)) {	
		$fichier='/tmp/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
		$fichiercomplet='/var/www/html'.$fichier;
	}

	if (file_exists($fichiercomplet)) {
		//log::add('diaporama', 'debug', '**********************file_exists:'.$fichiercomplet.'***********************************');
		# Passage des paramètres dans la table : imageinfo
		$imageinfo= getimagesize("$fichiercomplet");
		$iw=$imageinfo[0];
		$ih=$imageinfo[1];
		# Paramètres : Largeur et Hauteur souhaiter $maxWidth, $maxHeight
		# Calcul des rapport de Largeur et de Hauteur
		$widthscale = $iw/$maxWidth;
		$heightscale = $ih/$maxHeight;
		$rapport = $ih/$widthscale;
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$widthscale:'.$widthscale.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$heightscale:'.$heightscale.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$rapport:'.$rapport.'('.$ih.'/'.$widthscale.')~~~~~~~~~~~~~~~~~~~~~~~~~');
		# Calul des rapports Largeur et Hauteur à afficher
		if($rapport < $maxHeight)
			{$nwidth = $maxWidth;}
		 else
			{$nwidth = round($iw/$heightscale);}
		 if($rapport < $maxHeight)
			{$nheight = $rapport;}
		 else
			{$nheight = $maxHeight;}
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$nwidth:'.$nwidth.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$centrerLargeur:'.$centrerLargeur.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		//$nheight="20";
		//$nwidth="50";
		$decalerAdroite="";
		if ($centrerLargeur) {
			$decalage=round(($maxWidth-$nwidth)/2);
			if ($decalage > 1)
				$decalerAdroite="position: relative; left: ".$decalage."px;";
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$maxWidth:'.$maxWidth.'->'.$nwidth.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		log::add('diaporama', 'debug', '--> Image '.$iw.'x'.$ih.' redimensée en '.$nwidth.'x'.$nheight);
		}
		return '<img height="'.$nheight.'" width="'.$nwidth.'" class="rien" style="'.$decalerAdroite.'height: '.$nheight.';width: '.$nwidth.';border-radius: '.$arrondiPhoto.';" src="'.$fichier.'" alt="image">';
	} else {
		log::add('diaporama', 'debug', '**********************file_exists PAS:'.$fichiercomplet.'***********************************');
		return "Le fichier $fichiercomplet n'existe pas.";
	}    
}

public function redimensionne_PhotoFacebook($source,$Width,$Height,$maxWidth,$maxHeight, $arrondiPhoto, $centrerLargeur)  {
	
	
		//log::add('diaporama', 'debug', '**********************file_exists:'.$fichiercomplet.'***********************************');
		$iw=$Width;
		$ih=$Height;
		
		# Paramètres : Largeur et Hauteur souhaiter $maxWidth, $maxHeight
		# Calcul des rapport de Largeur et de Hauteur
		$widthscale = $iw/$maxWidth;
		$heightscale = $ih/$maxHeight;
		$rapport = $ih/$widthscale;
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$widthscale:'.$widthscale.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$heightscale:'.$heightscale.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$rapport:'.$rapport.'('.$ih.'/'.$widthscale.')~~~~~~~~~~~~~~~~~~~~~~~~~');
		# Calul des rapports Largeur et Hauteur à afficher
		if($rapport < $maxHeight)
			{$nwidth = $maxWidth;}
		 else
			{$nwidth = round($iw/$heightscale);}
		 if($rapport < $maxHeight)
			{$nheight = $rapport;}
		 else
			{$nheight = $maxHeight;}
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$nwidth:'.$nwidth.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$centrerLargeur:'.$centrerLargeur.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		//$nheight="20";
		//$nwidth="50";
		$decalerAdroite="";
		if ($centrerLargeur) {
			$decalage=round(($maxWidth-$nwidth)/2);
			if ($decalage > 1)
				$decalerAdroite="position: relative; left: ".$decalage."px;";
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$maxWidth:'.$maxWidth.'->'.$nwidth.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		log::add('diaporama', 'debug', '--> Image '.$iw.'x'.$ih.' redimensée en '.$nwidth.'x'.$nheight);
		}
		return '<img height="'.$nheight.'" width="'.$nwidth.'" class="rien" style="'.$decalerAdroite.'height: '.$nheight.';width: '.$nwidth.';border-radius: '.$arrondiPhoto.';" src="'.$source.'" alt="image">';
   
}

public function infosExif($tirageSort, $_indexPhoto, $_device)  {
	
    $fichier='/tmp/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
    $fichiercomplet='/var/www/html'.$fichier;
    $fichiercompletRotate='/var/www/html/tmp/diaporama_'.$this->getId()."_".$tirageSort.'_rotate.jpg';
	if (file_exists($fichiercomplet)) {
		$exif = exif_read_data($fichiercomplet, 'EXIF');
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$exif:'.json_encode($exif).'~~~~~~~~~~~~~~~~~~~~~~~~~');

		$intDate=0;
		if     (strtotime($exif['FileDateTime'])) $intDate=strtotime($exif['FileDateTime']);
		elseif (strtotime($exif['DateTimeOriginal'])) $intDate=strtotime($exif['DateTimeOriginal']);
		elseif (strtotime($exif['DateTimeDigitized'])) $intDate=strtotime($exif['DateTimeDigitized']);
		elseif (strtotime($exif['DateTimeDigitized'])) $intDate=strtotime($exif['DateTimeDigitized']);
		elseif (strtotime($exif['GPSDateStamp'])) $intDate=strtotime($exif['GPSDateStamp']);
		else $intDate=$exif['FileDateTime'];
		
		$formatDateHeure = config::byKey('formatDateHeure', 'diaporama', '0');
		if ($formatDateHeure =="") $formatDateHeure="d-m-Y H:i:s";
		$_device->checkAndUpdateCmd('date'.$_indexPhoto, date($formatDateHeure, $intDate));
		log::add('diaporama', 'debug', '--> Date&Heure récupérés: '.date($formatDateHeure, $intDate));
		//log::add('diaporama', 'debug', '--> Orientation récupérée: '.$exif['GPSLatitude']);
		if (config::byKey('rotate', 'diaporama', '0')) {
			$photoaTraiter = ImageCreateFromJpeg($fichiercomplet);
			switch ($exif['Orientation']) {
				case "6":
					imagejpeg(imagerotate($photoaTraiter, 270, 0),$fichiercompletRotate);
					break;
				case "8":
					imagejpeg(imagerotate($photoaTraiter, 90, 0),$fichiercompletRotate);
					break;
				case "3":
					imagejpeg(imagerotate($photoaTraiter, 180, 0),$fichiercompletRotate);
					break;
			}	
		}
		$siteGPS="";
		$APIGoogleMaps = config::byKey('APIGoogleMaps', 'diaporama', '0');
		if ($APIGoogleMaps !="" && is_array($exif['GPSLatitude'])) {
			//log::add('diaporama', 'debug', '--> TEST: '.$exif['GPSLatitude']);
			// GPS - GPS - GPS
			//$_device->checkAndUpdateCmd('orientation'.$_indexPhoto, $exif['GPSLatitudeRef']); 
			//$_device->checkAndUpdateCmd('orientation'.$_indexPhoto, $exif['GPSLongitudeRef']); //E donne +, W donne -, N donne +, S donne -
			//$_device->checkAndUpdateCmd('orientation'.$_indexPhoto, $exif['GPSLatitude']); //:["45\/1","48\/1","54\/1"]
			//$_device->checkAndUpdateCmd('orientation'.$_indexPhoto, $exif['GPSLongitude']); //:["45\/1","48\/1","54\/1"]
			//log::add('diaporama', 'debug', '--> GPSLatitude0: '.self::recupGPS($exif['GPSLatitude'][0]));
			//log::add('diaporama', 'debug', '--> GPSLatitude1: '.self::recupGPS($exif['GPSLatitude'][1]));
			//log::add('diaporama', 'debug', '--> GPSLatitude2: '.self::recupGPS($exif['GPSLatitude'][2]));
			//log::add('diaporama', 'debug', '--> GPSLongitude0: '.self::recupGPS($exif['GPSLongitude'][0]));
			//log::add('diaporama', 'debug', '--> GPSLongitude1: '.self::recupGPS($exif['GPSLongitude'][1]));
			//log::add('diaporama', 'debug', '--> GPSLongitude2: '.self::recupGPS($exif['GPSLongitude'][2]));
			//log::add('diaporama', 'debug', '--> DDDDDDDDDDDDDD: '.self::DMStoDD($exif['GPSLatitude']));

			// https://www.coordonnees-gps.fr/
			//log::add('diaporama', 'debug', '--> Latitude: '.self::DMSversDD($exif['GPSLatitude']));
			//log::add('diaporama', 'debug', '--> Longitude: '.self::DMSversDD($exif['GPSLongitude']));
			$requete="https://maps.googleapis.com/maps/api/geocode/json?latlng=".self::DMSversDD($exif['GPSLatitudeRef'],$exif['GPSLatitude']).",".self::DMSversDD($exif['GPSLongitudeRef'],$exif['GPSLongitude'])."&key=".$APIGoogleMaps;
			log::add('diaporama', 'debug', '--> Requete Web: '."https://maps.googleapis.com/maps/api/geocode/json?latlng=".self::DMSversDD($exif['GPSLatitudeRef'],$exif['GPSLatitude']).",".self::DMSversDD($exif['GPSLongitudeRef'],$exif['GPSLongitude'])."&key=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");
			$recupereJson=file_get_contents($requete);
			$json = json_decode($recupereJson,true);
			if ($json['error_message'] != "")
				$siteGPS=$json['error_message'];
			else
				$siteGPS=strstr($json['plus_code']['compound_code'], ' ');
			//$json = json_decode(array_values($recupereJson,true));
			//log::add('diaporama', 'debug', '--> pays: '.json_encode($json));
			//log::add('diaporama', 'debug', '--> pays: '.json_encode($json['plus_code']));
			//log::add('diaporama', 'debug', '--> pays: '.json_encode($json[0]['compound_code']));
			//log::add('diaporama', 'debug', '--> pays: '.$json['results']['0']['address_composents']['5']['long_name']);
			//log::add('diaporama', 'debug', '--> pays: '.json_encode($json));
			//log::add('diaporama', 'debug', '--> pays: '.json_encode($json['plus_code']));
			//log::add('diaporama', 'debug', '--> pays: '.json_encode($json[0]['compound_code']));
			//log::add('diaporama', 'debug', '--> pays: '.$json['results']['0']['address_composents']['5']['long_name']);
			//log::add('diaporama', 'debug', '--> adresse: '.json_encode($json['plus_code']['compound_code']));
			//log::add('diaporama', 'debug', '--> adresse: '.$json['plus_code']['compound_code']);
			log::add('diaporama', 'debug', '--> Adresse trouvée: '.$siteGPS);
		} else {
		log::add('diaporama', 'debug', "--> Pas de coodonnées GPS de détectées (ou pas de clé Google Maps configurée)"); }
$_device->checkAndUpdateCmd('site'.$_indexPhoto, $siteGPS); 
		
	}
		
}

public function DMSversDD($WouS, $arrayGPS) {
	if ($WouS=="W" || $WouS=="S") $negatif=-1; else $negatif=1;
	$nombre=(floatval(str_replace("/1", "", self::recupGPS($arrayGPS[0]))))+((floatval(str_replace("/1", "", self::recupGPS($arrayGPS[2]))) /60 + floatval(str_replace("/1", "", self::recupGPS($arrayGPS[1]))))/60);
	$nombre=$nombre*$negatif;
return $nombre;
}

public function recupGPS($chaineGPS) {
return intval(strstr($chaineGPS, '/', true))/intval(str_replace("/", "", strstr($chaineGPS, '/')));
}

	public function refresh() {
			$largeurPhoto=$this->getConfiguration('largeurPhoto');
			$hauteurPhoto=$this->getConfiguration('hauteurPhoto');
			$arrondiPhoto=$this->getConfiguration('arrondiPhoto');
			if ($largeurPhoto =="") $largeurPhoto="250";
			if ($hauteurPhoto =="") $hauteurPhoto="250";		
			if ($arrondiPhoto =="") $arrondiPhoto="30%";		
			$tirageSort="999";//999 pour boucler dans tirageSort
			$touteslesValeurs= array($tirageSort);
			$nbPhotosaGenerer=$this->getConfiguration('nbPhotosaGenerer');
			$centrerLargeur=$this->getConfiguration('centrerLargeur');
			//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$centrerLargeur:'.$centrerLargeur.'~~~~~~~~~~~~~~~~~~~~~~~~~');
			$formatDateHeure = config::byKey('formatDateHeure', 'diaporama', '0');
			if ($formatDateHeure =="") $formatDateHeure="d-m-Y H:i:s";
		
		
		if ($this->getConfiguration('stockageSamba')==1) {
			
			$sambaShare	= config::byKey('samba::backup::share')	;
			$dos=$sambaShare.$this->getConfiguration('dossierSambaDiaporama');
			//log::add('diaporama', 'debug', '**********************1***********************************');

			$diapo=self::jpg_list($this->getConfiguration('dossierSambaDiaporama'));
			//log::add('diaporama', 'debug', '**********************diapo:'.json_encode($diapo).'***********************************');
			$nbPhotos=count($diapo);
			log::add('diaporama', 'debug', '----------------------------------------------------------------------------');
			log::add('diaporama', 'debug', 'Dans le dossier '.$dos.', il y a '.$nbPhotos.' photos');
			if ($nbPhotosaGenerer<2 || $nbPhotosaGenerer>9) $nbPhotosaGenerer=2;
			for ($i = 1; $i <= $nbPhotosaGenerer; $i++) {
			while ($compteurparSecurite < 20 && in_array($tirageSort, $touteslesValeurs))
				{
				$tirageSort=mt_rand(0,$nbPhotos-1);
				$compteurparSecurite++;
				}
			array_push($touteslesValeurs, $tirageSort);
			$file = $diapo[$tirageSort];
			$newfile = '/var/www/html/tmp/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
			log::add('diaporama', 'debug', 'Fichier sélectionné au hasard:'.$file.' copié dans '.$this->getConfiguration('dossierSambaDiaporama').' en '.$newfile);
			try {
				self::downloadCore($this->getConfiguration('dossierSambaDiaporama'), $file, $newfile);
				self::infosExif($tirageSort,$i,$this);
				$image=self::redimensionne_Photo($tirageSort,$largeurPhoto,$hauteurPhoto, $arrondiPhoto, $centrerLargeur);
				$this->checkAndUpdateCmd('photo'.$i, $image);	
			}
			catch(Exception $exc) {
				log::add('diaporama', 'error', __('Erreur pour ', __FILE__) . ' : ' . $exc->getMessage());
			}			
			}
			self::chmod777();
		}
		elseif ($this->getConfiguration('stockageFacebook')==1) {
			log::add('diaporama', 'debug', '**********************Refresh Facebook***********************************');
		
		// on va tirer au sort l'album photo
		$Albums=$this->getConfiguration('arrayAlbumsFacebook');
		//log::add('diaporama', 'debug', 'Albums : '.json_encode($Albums).'***********************************');
		$CompteAlbums=0;
		foreach ($Albums as $key2 => $value2) {
			if ($Albums[$key2][1] == '1') $CompteAlbums++;
		}	
		//log::add('diaporama', 'debug', 'CompteAlbums : '.$CompteAlbums.'***********************************');
		$tirageSort=mt_rand(0,$CompteAlbums-1);
		//log::add('diaporama', 'debug', 'tirageSort : '.$tirageSort.'***********************************');
		$compteurPourTrouverAlbum=0;
		foreach ($Albums as $key2 => $value2) {
		//log::add('diaporama', 'debug', 'key2 : '.$value2[0].'***********************************');
		//log::add('diaporama', 'debug', 'compteurPourTrouverAlbum : '.$compteurPourTrouverAlbum.' <-> tirageSort : '.$tirageSort.'***********************************');
			if (($Albums[$key2][1] == '1') && ($compteurPourTrouverAlbum==$tirageSort)) {$idAlbumChoisi=$value2[0]; break;}
			if ($Albums[$key2][1] == '1') $compteurPourTrouverAlbum++;
		
		}	
		//log::add('diaporama', 'debug', 'idAlbumChoisi : '.$idAlbumChoisi.'***********************************');
		$albumsFacebook = config::byKey('albumsFacebook', 'diaporama', '0');
		//log::add('diaporama', 'debug', 'albumsFacebook : '.json_encode($albumsFacebook).'***********************************');
		foreach ($albumsFacebook as $value) {
		//log::add('diaporama', 'debug', '$value[id] : '.$value['id'].'***********************************');
		if ($value['id'] == $idAlbumChoisi) { $nbdePhotosdansAlbum=$value['count']; break;}
		}		
		//log::add('diaporama', 'debug', 'nbdePhotosdansAlbum : '.$nbdePhotosdansAlbum.'***********************************');
		$tirageSort=mt_rand(0,$nbdePhotosdansAlbum-1);
		log::add('diaporama', 'debug', "On a tiré au sort la ".$tirageSort."ème photo de l'album ".$idAlbumChoisi." qui en compte ".$nbdePhotosdansAlbum);
		$TokenFacebook = config::byKey('TokenFacebook', 'diaporama', '0');
		$requete="https://graph.facebook.com/v5.0/".$idAlbumChoisi."/photos?fields=height%2Cwidth&limit=100&access_token=".$TokenFacebook;
		log::add('diaporama', 'debug', 'On cherche la photo avec la requète : '.$requete);
		$onaTrouvePhoto = false;
		$countdata=0;
		while (!$onaTrouvePhoto) {
			if ($recupereJson=file_get_contents($requete, true)) {
			$json = json_decode($recupereJson,true);
			$data=$json['data'];
			$indexPhoto=$tirageSort-1-$countdata;
			$countdata=$countdata+count($data);
			$paging=$json['paging'];
			//log::add('diaporama', 'debug', 'Résultat de la requète : '.json_encode($data).'***********************************');
			//log::add('diaporama', 'debug', 'Nombre de résultats (avec les boucles précédentes): '.$countdata.'***********************************');
				if ($tirageSort<=$countdata) {
						// c'est ok, la photo est dans $json['data']
					//log::add('diaporama', 'debug', 'indexPhoto : '.$indexPhoto.'***********************************');
					$idphotoChoisie=$data[$indexPhoto]['id'];
					log::add('diaporama', 'debug', 'ID de la photo choisie : '.$idphotoChoisie);
					//log::add('diaporama', 'debug', 'height de la photo choisie : '.$data[$indexPhoto]['height'].'***********************************');
					//log::add('diaporama', 'debug', 'width de la photo choisie : '.$data[$indexPhoto]['width'].'***********************************');
					$onaTrouvePhoto = true;
					} else {
					log::add('diaporama', 'debug', 'On recherche la photo avec la requète : '.json_encode($paging['next']));
					$requete=$paging['next'];
					}
			} else {
			log::add('diaporama', 'debug', "*********************** Souci de récupération de l'ID de la photo");
			$onaTrouvePhoto = true; // on sort	
			}
		}
		$requete="https://graph.facebook.com/v5.0/".$idphotoChoisie."?fields=event%2Calbum%2Calt_text_custom%2Cbackdated_time%2Cbackdated_time_granularity%2Ccreated_time%2Cheight%2Cname%2Cname_tags%2Cpage_story_id%2Cplace%2Cwidth%2Cimages&access_token=".$TokenFacebook;
		log::add('diaporama', 'debug', 'On cherche les infos sur la photo avec la requète : '.$requete);
		if ($recupereJson=file_get_contents($requete, true)) {
					$json = json_decode($recupereJson,true);
					log::add('diaporama', 'debug', "Nom de l'album : ".$json['album']['name']);
					log::add('diaporama', 'debug', "Place : ".$json['place']['name']);
					log::add('diaporama', 'debug', "City : ".$json['place']['location']['city']);
					log::add('diaporama', 'debug', "Country : ".$json['place']['location']['country']);
					log::add('diaporama', 'debug', "Date : ".$json['created_time']);
					log::add('diaporama', 'debug', "Height : ".$json['images']['0']['height']);
					log::add('diaporama', 'debug', "Width : ".$json['images']['0']['width']);
					log::add('diaporama', 'debug', "Source : ".$json['images']['0']['source']);
		//$image= '<img height="'.$nheight.'" width="'.$nwidth.'" class="rien" style="'.$decalerAdroite.'height: '.$nheight.';width: '.$nwidth.';border-radius: '.$arrondiPhoto.';" src="'.$fichier.'" alt="image">';
		//$image= '<img class="rien" src="'.$json['images']['0']['source'].'" alt="image">';	
		
		
					//log::add('diaporama', 'debug', "Date récupérée :".strtotime($json['created_time']));
					//log::add('diaporama', 'debug', "Format Date :".$formatDateHeure);
		
		
		$i="1";
		$this->checkAndUpdateCmd('date'.$i, date($formatDateHeure,  strtotime($json['created_time'])));				
		$this->checkAndUpdateCmd('site'.$i, $json['place']['name']);		
		$image=self::redimensionne_PhotoFacebook($json['images']['0']['source'],$json['images']['0']['width'],$json['images']['0']['height'],$largeurPhoto,$hauteurPhoto, $arrondiPhoto, $centrerLargeur);

		$this->checkAndUpdateCmd('photo'.$i, $image);		
		} else {
					log::add('diaporama', 'debug', "*********************** Souci de récupération des infos de la photo");
					}		
		
		/*
		// on va compter le nb de photos des albums cochés 
		// Lecture de arrayAlbumsFacebook dans configuration du device en cours ($device)
		$Albums=$this->getConfiguration('arrayAlbumsFacebook');
		$totalPhotosCochees=0;
		$albumsFacebook = config::byKey('albumsFacebook', 'diaporama', '0');
		foreach ($albumsFacebook as $value) {
			foreach ($Albums as $key2 => $value2) {
				if (($value['id'] == $Albums[$key2][0]) && $Albums[$key2][1] == '1') $totalPhotosCochees=$totalPhotosCochees+$value['count'];
			}	
		}
		$tirageSort=mt_rand(0,$totalPhotosCochees-1);
			log::add('diaporama', 'debug', '**********************$totalPhotosCochees:'.$totalPhotosCochees.'***********************************');
			log::add('diaporama', 'debug', '**********************$tirageSort:'.$tirageSort.'***********************************');
		$TokenFacebook = config::byKey('TokenFacebook', 'diaporama', '0');
		$requete="https://graph.facebook.com/v5.0/10215939768022842?fields=event%2Calbum%2Calt_text_custom%2Cbackdated_time%2Cbackdated_time_granularity%2Ccreated_time%2Cheight%2Cname%2Cname_tags%2Cpage_story_id%2Cplace%2Cwidth%2Cimages&access_token=".$TokenFacebook;
		log::add('diaporama', 'debug', 'On récupère les infos de la photo Facebook avec la requète : '.$requete.'***********************************');
		
		if ($recupereJson=file_get_contents($requete, true)) {
		$json = json_decode($recupereJson,true);
		*/


		}
		else {
			$dossierLocal=$this->getConfiguration('cheminDiaporama');
			if ($dossierLocal =="") $dossierLocal="/../images/"; // par défaut
			$dos=dirname(__FILE__).$dossierLocal; 
			$diapo=glob($dos.'*.jpg');
			$nbPhotos=count($diapo);
			if ($nbPhotosaGenerer<2 || $nbPhotosaGenerer>9) $nbPhotosaGenerer=2;
			for ($i = 1; $i <= $nbPhotosaGenerer; $i++) {
			while ($compteurparSecurite < 20 && in_array($tirageSort, $touteslesValeurs))
				{
				$tirageSort=mt_rand(0,$nbPhotos-1);
				$compteurparSecurite++;
				}
			array_push($touteslesValeurs, $tirageSort);
			$file = $diapo[$tirageSort];
		
			$newfile = '/var/www/html/tmp/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
			if (!copy($file, $newfile)) log::add('diaporama', 'debug', 'Copie image '.$file.' en diaporama_'.$this->getId()."_".$tirageSort.'.jpg NOK'); else log::add('diaporama', 'debug', 'Copie image '.$file.' en diaporama_'.$this->getId()."_".$tirageSort.'.jpg OK');
			//$image='<img class="rien" style="height: '.$hauteurPhoto.';width: '.$largeurPhoto.';border-radius: '.$arrondiPhoto.';" src="tmp/diaporama_'.$tirageSort.'.jpg" alt="image">';
			$image=self::redimensionne_Photo($tirageSort,$largeurPhoto,$hauteurPhoto, $arrondiPhoto, $centrerLargeur);
			$this->checkAndUpdateCmd('photo'.$i, $image);			
			}
		}
	}
		
	public function postSave() {
	//	log::add('diaporama', 'debug', '**********************début postSave '.$this->getName().'***********************************');


	$nbPhotosaGenerer=$this->getConfiguration('nbPhotosaGenerer');
	for ($i = 1; $i <= $nbPhotosaGenerer; $i++) {
					$cmd = $this->getCmd(null, 'photo'.$i);
					if (!is_object($cmd)) {
						$cmd = new diaporamaCmd();
						$cmd->setType('info');
						$cmd->setLogicalId('photo'.$i);
						$cmd->setSubType('string');
						$cmd->setEqLogic_id($this->getId());
						$cmd->setName('Photo '.$i);
						$cmd->setIsVisible(1);
						$cmd->setOrder($i*5);
						//$cmd->setDisplay('icon', '<i class="loisir-musical7"></i>');
						$cmd->setDisplay('title_disable', 1);
					}
					$cmd->save();	
					$cmd = $this->getCmd(null, 'date'.$i);
					if (!is_object($cmd)) {
						$cmd = new diaporamaCmd();
						$cmd->setType('info');
						$cmd->setLogicalId('date'.$i);
						$cmd->setSubType('string');
						$cmd->setEqLogic_id($this->getId());
						$cmd->setName('Date '.$i);
						$cmd->setIsVisible(1);
						$cmd->setOrder($i*5+1);
						//$cmd->setDisplay('icon', '<i class="loisir-musical7"></i>');
						$cmd->setDisplay('title_disable', 1);
					}
					$cmd->save();		
					
					$cmd = $this->getCmd(null, 'site'.$i);
					if (!is_object($cmd)) {
						$cmd = new diaporamaCmd();
						$cmd->setType('info');
						$cmd->setLogicalId('site'.$i);
						$cmd->setSubType('string');
						$cmd->setEqLogic_id($this->getId());
						$cmd->setName('Site '.$i);
						$cmd->setIsVisible(1);
						$cmd->setOrder($i*5+2);
						//$cmd->setDisplay('icon', '<i class="loisir-musical7"></i>');
						$cmd->setDisplay('title_disable', 1);
					}
					$cmd->save();						
					
					$cmd = $this->getCmd(null, 'ville'.$i);
					if (!is_object($cmd)) {
						$cmd = new diaporamaCmd();
						$cmd->setType('info');
						$cmd->setLogicalId('ville'.$i);
						$cmd->setSubType('string');
						$cmd->setEqLogic_id($this->getId());
						$cmd->setName('Ville '.$i);
						$cmd->setIsVisible(1);
						$cmd->setOrder($i*5+3);
						//$cmd->setDisplay('icon', '<i class="loisir-musical7"></i>');
						$cmd->setDisplay('title_disable', 1);
					}
					$cmd->save();							
						
					$cmd = $this->getCmd(null, 'pays'.$i);
					if (!is_object($cmd)) {
						$cmd = new diaporamaCmd();
						$cmd->setType('info');
						$cmd->setLogicalId('pays'.$i);
						$cmd->setSubType('string');
						$cmd->setEqLogic_id($this->getId());
						$cmd->setName('Pays '.$i);
						$cmd->setIsVisible(1);
						$cmd->setOrder($i*5+3);
						//$cmd->setDisplay('icon', '<i class="loisir-musical7"></i>');
						$cmd->setDisplay('title_disable', 1);
					}
					$cmd->save();					
					
					}			
	
	
				//Commande Refresh
				$createRefreshCmd = true;
				$refresh = $this->getCmd(null, 'refresh');
				if (!is_object($refresh)) {
					$refresh = cmd::byEqLogicIdCmdName($this->getId(), __('Rafraichir', __FILE__));
					if (is_object($refresh)) {
						$createRefreshCmd = false;
					}
				}
				if ($createRefreshCmd) {
					if (!is_object($refresh)) {
						$refresh = new diaporamaCmd();
						$refresh->setLogicalId('refresh');
						$refresh->setIsVisible(1);
						$refresh->setDisplay('icon', '<i class="fa fa-sync"></i>');
						$refresh->setName(__('Refresh', __FILE__));
					}
					$refresh->setType('action');
					$refresh->setSubType('other');
					$refresh->setEqLogic_id($this->getId());
					$refresh->save();
				}

		$this->setStatus('forceUpdate', false); //dans tous les cas, on repasse forceUpdate à false
		
	//	log::add('diaporama', 'debug', '**********************fin postSave '.$this->getName().'***********************************');

		
	}

	
	public function preUpdate() {
	}
	
	public function preRemove () {

	}
	
	
		public static function lsjpg($_dir = '', $_type = 'backup') {
		$cmd = repo_samba::makeSambaCommand('cd ' . $_dir . ';ls *.jpg', $_type);
		$result = explode("\n", com_shell::execute($cmd));
		$return = array();
		for ($i = 2; $i < count($result) - 2; $i++) {
			$line = array();
			foreach (explode(" ", $result[$i]) as $value) {
				if (trim($value) == '') {
					continue;
				}
				$line[] = $value;
			}
			$file_info = array();
			log::add('diaporama', 'debug', 'filename->>>'.$line[0]);
			$file_info['filename'] = $line[0];
			$file_info['size'] = $line[2];
			$file_info['datetime'] = date('Y-m-d H:i:s', strtotime($line[5] . ' ' . $line[4] . ' ' . $line[7] . ' ' . $line[6]));
			$return[] = $file_info;
		}
		//usort($return, 'repo_samba::sortByDatetime');
		//return array_reverse($return);
		return array_reverse($result);
	}
	
	// functions de samba.repo.php repris et simplifié
	public static function lsjpg_count($_dir = '', $_type = 'backup') {
		$cmd = repo_samba::makeSambaCommand('cd ' . $_dir . ';ls *.jpg -U', $_type);
		return count(explode("\n", com_shell::execute($cmd)))-4;
	}	
	
	public static function jpg_list($_dir = '') {
		$return = array();
		//log::add('diaporama', 'debug', '**********************2***********************************');
		//log::add('diaporama', 'debug', '**********************3'.$_dir.'***********************************');
		foreach (self::ls($_dir) as $file) {
			if (stripos($file['filename'],'.jpg') !== false) {
				$return[] = $file['filename'];
			}
		}
		return $return;
	}	
	public static function downloadCore($_dir= '', $_fileOrigine, $_fileDestination) {
		//$pathinfo = pathinfo($_path);
		//log::add('diaporama', 'debug', '_dir>>>' . $_dir);
		//$cmd = 'cd ' . $_dir . ';';
		$cmd = repo_samba::makeSambaCommand('cd ' . $_dir . ';get '.$_fileOrigine.' '.$_fileDestination, 'backup');
		//log::add('diaporama', 'debug', 'Commande>>>get '.$_fileOrigine.' '.$_fileDestination);
		com_shell::execute($cmd);
		//log::add('diaporama', 'debug', 'get fait');
		return;
	}
	
	public static function chmod777() {
		com_shell::execute(system::getCmdSudo() . 'chmod 777 -R /var/www/html/tmp/' );
		//return;
	}
	
	public static function ls($_dir = '', $_type = 'backup') {
		$cmd = repo_samba::makeSambaCommand('cd ' . $_dir . ';ls *.JPG -U', $_type);
		$result = explode("\n", com_shell::execute($cmd));
		$return = array();
		for ($i = 2; $i < count($result) - 2; $i++) {
			$line = array();
			foreach (explode(" ", $result[$i]) as $value) {
				if (trim($value) == '') {
					continue;
				}
				$line[] = $value;
			}
			$file_info = array();
			$file_info['filename'] = $line[0];
			$file_info['size'] = $line[2];
			$file_info['datetime'] = date('Y-m-d H:i:s', strtotime($line[5] . ' ' . $line[4] . ' ' . $line[7] . ' ' . $line[6]));
			$return[] = $file_info;
		}
		//usort($return, 'repo_samba::sortByDatetime');
		return array_reverse($return);
	}	

public static function Utf8_ansi($valor='') {

    $utf8_ansi2 = array(
    "\u00c0" =>"À",
    "\u00c1" =>"Á",
    "\u00c2" =>"Â",
    "\u00c3" =>"Ã",
    "\u00c4" =>"Ä",
    "\u00c5" =>"Å",
    "\u00c6" =>"Æ",
    "\u00c7" =>"Ç",
    "\u00c8" =>"È",
    "\u00c9" =>"É",
    "\u00ca" =>"Ê",
    "\u00cb" =>"Ë",
    "\u00cc" =>"Ì",
    "\u00cd" =>"Í",
    "\u00ce" =>"Î",
    "\u00cf" =>"Ï",
    "\u00d1" =>"Ñ",
    "\u00d2" =>"Ò",
    "\u00d3" =>"Ó",
    "\u00d4" =>"Ô",
    "\u00d5" =>"Õ",
    "\u00d6" =>"Ö",
    "\u00d8" =>"Ø",
    "\u00d9" =>"Ù",
    "\u00da" =>"Ú",
    "\u00db" =>"Û",
    "\u00dc" =>"Ü",
    "\u00dd" =>"Ý",
    "\u00df" =>"ß",
    "\u00e0" =>"à",
    "\u00e1" =>"á",
    "\u00e2" =>"â",
    "\u00e3" =>"ã",
    "\u00e4" =>"ä",
    "\u00e5" =>"å",
    "\u00e6" =>"æ",
    "\u00e7" =>"ç",
    "\u00e8" =>"è",
    "\u00e9" =>"é",
    "\u00ea" =>"ê",
    "\u00eb" =>"ë",
    "\u00ec" =>"ì",
    "\u00ed" =>"í",
    "\u00ee" =>"î",
    "\u00ef" =>"ï",
    "\u00f0" =>"ð",
    "\u00f1" =>"ñ",
    "\u00f2" =>"ò",
    "\u00f3" =>"ó",
    "\u00f4" =>"ô",
    "\u00f5" =>"õ",
    "\u00f6" =>"ö",
    "\u00f8" =>"ø",
    "\u00f9" =>"ù",
    "\u00fa" =>"ú",
    "\u00fb" =>"û",
    "\u00fc" =>"ü",
    "\u00fd" =>"ý",
    "\u00ff" =>"ÿ");

    return strtr($valor, $utf8_ansi2);      

}	
	
public function preSave() {
		//log::add('diaporama', 'debug', '**********************1***********************************');

		// Controle si 	nbPhotosaGenerer n'est pas vide
		$nbPhotosaGenerer=$this->getConfiguration('nbPhotosaGenerer');
		if ($nbPhotosaGenerer<2 || $nbPhotosaGenerer>9) 
			{$nbPhotosaGenerer=2;
			$this->setConfiguration('nbPhotosaGenerer',"2");
		}
		
		
$this->setConfiguration('cheminDiaporamaValide', "question"); 
				
	$diapo = array();
	
	if (($this->getConfiguration('stockageSamba')!=1) && ($this->getConfiguration('stockageFacebook')!=1)) {
		// On est sur le mode Stockage LOCAL
		$this->setConfiguration('stockageLocal',1); // par défaut
		$this->setConfiguration('sambaEtat', "nok"); 		
		$this->setConfiguration('facebookEtat', "nok"); 
		$this->setConfiguration('cheminDiaporamaMessage', "");
		$dossierLocal=$this->getConfiguration('cheminDiaporama');
		if ($dossierLocal =="") $dossierLocal="/../images/"; // par défaut
		$dos=dirname(__FILE__).$dossierLocal; 
		$diapo=glob($dos.'*.jpg');
		$this->setConfiguration('cheminDiaporamaComplet', realpath($dos)); 
		$this->setConfiguration('localEtat', "ok"); 
		$nbPhotos=count($diapo);
		$this->setConfiguration('nombrePhotos', $nbPhotos);
		$this->setConfiguration('derniereMAJ', date("d-m-Y H:i:s"));
		if ($nbPhotos==0) {
			$this->setConfiguration('cheminDiaporamaValide', "nok");
			$this->setConfiguration('localEtat', "nok"); 
		}
		else $this->setConfiguration('cheminDiaporamaValide', "ok");
	} elseif ($this->getConfiguration('stockageSamba')==1)	{
		// On est sur le mode Stockage SAMBA
			if ($this->getConfiguration('sambaEtat') != "ok") {
				if ($this->getConfiguration('cheminDiaporamaMessage') == "") {
				$this->setConfiguration('cheminDiaporamaValide', "question");
				$this->setConfiguration('localEtat', "nok"); 
				$this->setConfiguration('sambaEtat', "nok"); 	
				$this->setConfiguration('facebookEtat', "nok"); 	
				$this->setConfiguration('nombrePhotos', "");
				$this->setConfiguration('derniereMAJ', " ");
				$this->setConfiguration('cheminDiaporamaComplet', "");
				} else {				
				//$this->setConfiguration('cheminDiaporamaValide', "question");
				//$this->setConfiguration('localEtat', "nok"); 
				$this->setConfiguration('sambaEtat', "nok"); 	
				$this->setConfiguration('cheminDiaporamaValide', "nok");
				//$this->setConfiguration('facebookEtat', "nok"); 	
				//$this->setConfiguration('nombrePhotos', "");
				//$this->setConfiguration('derniereMAJ', " ");
				//$this->setConfiguration('cheminDiaporamaComplet', "");
				}
			}
			else {
				$this->setConfiguration('cheminDiaporamaValide', "ok");
			}
			
	} else {
		// On est sur le mode Recupération FACEBOOK
		//$this->setConfiguration('stockageFacebook',1); 
		$this->setConfiguration('sambaEtat', "nok"); 		
		$this->setConfiguration('localEtat', "nok"); 
		$this->setConfiguration('cheminDiaporamaMessage', "");
		//$this->setConfiguration('cheminDiaporamaMessage', "tester facebook");
		//log::add('diaporama', 'debug', '**********************tester facebook***********************************');
		$TokenFacebook = config::byKey('TokenFacebook', 'diaporama', '0');
		$requete="https://graph.facebook.com/v5.0/me?access_token=".$TokenFacebook;
		log::add('diaporama', 'debug', 'On teste le compte Facebook avec la requète : '.$requete.'***********************************');
		
		if ($recupereJson=file_get_contents($requete, true)) {
		$json = json_decode($recupereJson,true);
		//log::add('diaporama', 'debug', '**********************json:'.self::Utf8_ansi($recupereJson).'***********************************');
		//log::add('diaporama', 'debug', 'Id détecté :'.$json['id']);
		//log::add('diaporama', 'debug', 'Name détecté :'.self::Utf8_ansi($json['name']));
			$this->setConfiguration('cheminDiaporamaValide', "ok");
			$this->setConfiguration('facebookEtat', "ok"); 		
			$this->setConfiguration('cheminDiaporamaComplet', "Facebook : Page ".self::Utf8_ansi($json['name'])); 
			$this->setConfiguration('derniereMAJ', date("d-m-Y H:i:s"));
		$requete="https://graph.facebook.com/v5.0/me/albums?fields=count%2Cname%2Ccreated_time&access_token=".$TokenFacebook;	
		log::add('diaporama', 'debug', 'On teste les albums photos Facebook avec la requète : '.$requete.'***********************************');
			if ($recupereJson=file_get_contents($requete, true)) {
					$json = json_decode($recupereJson,true);
					$compteur=0;
					foreach($json['data'] as $item)
					{
						$compteur=$compteur+$item['count'];
				//log::add('diaporama', 'debug', 'Nombre détecté :'.$item['count']);
					}
					
					// Enregistrement dans Configuration du device en cours ($this)
					//$SAVEarrayAlbumFacebook=$json['data'];
					//$this->setConfiguration('arrayAlbumsFacebook', $SAVEarrayAlbumFacebook);
					//log::add('diaporama', 'debug', 'On enregistre : '.json_encode($SAVEarrayAlbumFacebook).' dans plugin/device/config/arrayAlbumsFacebook');
					
				//$albumsFacebook = config::byKey('albumsFacebook', 'diaporama', '0'); semble pas utile c'est pour récupérer l'album dans les variables de config
			
					// Enregistrement dans la Config du Plugin
					config::save('albumsFacebook', $json['data'], 'diaporama');
					log::add('diaporama', 'debug', 'On enregistre : '.json_encode($json['data']).' dans plugin/config/albumsFacebook');

		// on va compter le nb de photos des albums cochés 
		// Lecture de arrayAlbumsFacebook dans configuration du device en cours ($device)
		$Albums=$this->getConfiguration('arrayAlbumsFacebook');
		$totalPhotosCochees=0;
		foreach ($json['data'] as $value) {
			foreach ($Albums as $key2 => $value2) {
				if (($value['id'] == $Albums[$key2][0]) && $Albums[$key2][1] == '1') $totalPhotosCochees=$totalPhotosCochees+$value['count'];
			}	
		}

//EAANMyZAmx6BoBANIhYdY7d34TnqixnpycyewYY0LY0hVZAXm39SpeH8KIpR4SZBTdErVOpOHu9fKum9fxYZCKpLORyEFrzmMZCK05RLrZBRXBneQNha9ZC6LZBlPXzxeEDHphgXu2KgL2ygpZBbMw7Es5mJi0sUGkLn3y1IrteeM9wAZDZD
					
					
				//log::add('diaporama', 'debug', 'Total détecté :'.$compteur);
				//log::add('diaporama', 'debug', 'Json :'.json_encode($json['data']));
				$this->setConfiguration('nombrePhotos', $compteur." (".$totalPhotosCochees." sélectionnées)");
			}
		}
		else {
		log::add('diaporama', 'debug', '******* Souci dans la requète JSON ');
			$this->setConfiguration('cheminDiaporamaValide', "nok");
			$this->setConfiguration('facebookEtat', "nok");
			$this->setConfiguration('derniereMAJ', date("d-m-Y H:i:s"));	
			$this->setConfiguration('nombrePhotos', "");			
			$this->setConfiguration('cheminDiaporamaComplet', "Facebook : Error"); 
		}
		
		

		
		
		
		
		
		
		
		
		
		
		
		
	}

		//log::add('diaporama', 'debug', '**********************2***********************************');
	
}

// https://github.com/NextDom/NextDom/wiki/Ajout-d%27un-template-a-votre-plugin	
// https://jeedom.github.io/documentation/dev/fr_FR/widget_plugin	

}

//----------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------

class diaporamaCmd extends cmd {

	public function dontRemoveCmd() {
		if ($this->getLogicalId() == 'refresh') {
			return true;
		}
		return false;
	}
	
	public function postSave() {

	}
	
	
	
	
	public function preSave() {
		if ($this->getLogicalId() == 'refresh') {
			return;
		}
		
	}

	public function execute($_options = null) {
		if ($this->getLogicalId() == 'refresh') {
			$this->getEqLogic()->refresh();
			return;
		}
		$request = $this->buildRequest($_options);
		log::add('diaporama', 'info', 'Request : ' . $request);//Request : http://192.168.0.21:3456/volume?value=50&device=G090LF118173117U
		$request_http = new com_http($request);
		$request_http->setAllowEmptyReponse(true);//Autorise les réponses vides
		if ($this->getConfiguration('noSslCheck') == 1) $request_http->setNoSslCheck(true);
		if ($this->getConfiguration('doNotReportHttpError') == 1) $request_http->setNoReportError(true);
		if (isset($_options['speedAndNoErrorReport']) && $_options['speedAndNoErrorReport'] == true) {// option non activée 
			$request_http->setNoReportError(true);
			$request_http->exec(0.1, 1);
			return;
		}
		$result = $request_http->exec($this->getConfiguration('timeout', 3), $this->getConfiguration('maxHttpRetry', 3));//Time out à 3s 3 essais
		if (!$result) throw new Exception(__('Serveur injoignable', __FILE__));
		// On traite la valeur de resultat (dans le cas de whennextalarm par exemple)
		$resultjson = json_decode($result, true);
		//log::add('diaporama', 'info', 'resultjson:'.json_encode($resultjson));
					// Ici, on va traiter une commande qui n'a pas été executée correctement (erreur type "Connexion Close")
					if (($value =="Connexion Close") || ($detail =="Unauthorized")){
						$value = $resultjson['value'];
						$detail = $resultjson['detail'];
						log::add('diaporama', 'debug', '**On traite '.$value.$detail.' Connexion Close** dans la Class');
						sleep(6);
							if (ob_get_length()) {
							ob_end_flush();
							flush();
							}	
						log::add('diaporama', 'debug', '**On relance '.$request);
						$result = $request_http->exec($this->getConfiguration('timeout', 2), $this->getConfiguration('maxHttpRetry', 3));
						if (!result) throw new Exception(__('Serveur injoignable', __FILE__));
						$jsonResult = json_decode($json, true);
						if (!empty($jsonResult)) throw new Exception(__('Echec de l\'execution: ', __FILE__) . '(' . $jsonResult['title'] . ') ' . $jsonResult['detail']);
						$resultjson = json_decode($result, true);
						$value = $resultjson['value'];
					}
		
				
		if (($this->getType() == 'action') && (is_array($this->getConfiguration('infoNameArray')))) {
			foreach ($this->getConfiguration('infoNameArray') as $LogicalIdCmd) {
				$cmd=$this->getEqLogic()->getCmd(null, $LogicalIdCmd);
				if (is_object($cmd)) { 
					$this->getEqLogic()->checkAndUpdateCmd($LogicalIdCmd, $resultjson[0][$LogicalIdCmd]);					
					//log::add('diaporama', 'info', $LogicalIdCmd.' prévu dans infoNameArray de '.$this->getName().' trouvé ! '.$resultjson[0]['whennextmusicalalarminfo'].' OK !');
				} else {
					log::add('diaporama', 'warning', $LogicalIdCmd.' prévu dans infoNameArray de '.$this->getName().' mais non trouvé ! donc ignoré');
				} 
			}
		} 
		elseif (($this->getType() == 'action') && ($this->getConfiguration('infoName') != '')) {
			// Boucle non testée !!
				$LogicalIdCmd=$this->getConfiguration('infoName');
				$cmd=$this->getEqLogic()->getCmd(null, $LogicalIdCmd);
				if (is_object($cmd)) { 
					$this->getEqLogic()->checkAndUpdateCmd($LogicalIdCmd, $resultjson[$LogicalIdCmd]);
				} else {
					log::add('diaporama', 'warning', $LogicalIdCmd.' prévu dans infoName de '.$this->getName().' mais non trouvé ! donc ignoré');
				} 
		}
		return true;
	}



}
