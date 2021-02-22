<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
class diaporama extends eqLogic { 
	public static function cron($_eqlogic_id = null) {
		$eqLogics = ($_eqlogic_id !== null) ? array(eqLogic::byId($_eqlogic_id)) : eqLogic::byType('diaporama', true);
		foreach ($eqLogics as $diaporama) {
			$autorefresh = $diaporama->getConfiguration('autorefresh','00 22 01 01 3 2020');
			if ($autorefresh != '') {
				try {
					//log::add('diaporama', 'debug', __('Expression cron valide pour ', __FILE__) . $diaporama->getHumanName() . ' : ' . $autorefresh);
					$c = new Cron\CronExpression($autorefresh, new Cron\FieldFactory);
					if ($c->isDue()) {
						$diaporama->refresh();
					}
				} catch (Exception $exc) {
					log::add('diaporama', 'error', __('Expression cron non valide pour ', __FILE__) . $diaporama->getHumanName() . ' : ' . $autorefresh);
				}
			}
		}
	}	
	public static function enregistreAlbumFB($Id, $Albums) {
		$Albums=json_decode($Albums);
		$device = eqLogic::byId($Id);
		if (is_object($device)) {
			// Enregistrement dans Configuration du device en cours ($this)
			$device->setConfiguration('arrayAlbumsFacebook', $Albums);
			log::add('diaporama', 'debug', 'On enregistre : '.json_encode($Albums).' dans plugin/device('.$Id.')/config/arrayAlbumsFacebook');
			$device->save();
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
		//event::add('jeedom::alert', array('level' => 'warning', 'page' => 'diaporama', 'message' => __('dossiertmp:'.sys_get_temp_dir().' ', __FILE__)));
			$dossierPlugin=realpath(dirname(__FILE__).'/../../');
			$dossierTMP = $dossierPlugin.'/tmp';
			$fichier=$dossierTMP.'/diaporama_'.$this->getId()."_".$tirageSort.'_resize.jpg';
			$fichierpourHTML='plugins/diaporama/tmp/diaporama_'.$this->getId()."_".$tirageSort.'_resize.jpg';
		
		if (!file_exists($fichier)) {	
			$fichier=$dossierTMP.'/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
			$fichierpourHTML='plugins/diaporama/tmp/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
		}
		if (file_exists($fichier)) {
		//log::add('diaporama', 'debug', '-->existe : $fichier :'.$$fichier);
			# Passage des paramètres dans la table : imageinfo
			$imageinfo= getimagesize("$fichier");
			$iw=$imageinfo[0];
			$ih=$imageinfo[1];
			# Paramètres : Largeur et Hauteur souhaiter $maxWidth, $maxHeight
			# Calcul des rapport de Largeur et de Hauteur
			$widthscale = $iw/$maxWidth;
			$heightscale = $ih/$maxHeight;
			$rapport = $ih/$widthscale;
			# Calul des rapports Largeur et Hauteur à afficher
			if($rapport < $maxHeight)
				{$nwidth = $maxWidth;}
			 else
				{$nwidth = round($iw/$heightscale);}
			 if($rapport < $maxHeight)
				{$nheight = $rapport;}
			 else
				{$nheight = $maxHeight;}
			$decalerAdroite="";
			if ($centrerLargeur) {
				$decalage=round(($maxWidth-$nwidth)/2);
				if ($decalage > 1)
					$decalerAdroite="position: relative; left: ".$decalage."px;";
			}
			log::add('diaporama', 'debug', '--> Image '.$iw.'x'.$ih.' redimensée en '.$nwidth.'x'.$nheight);
			return '<img height="'.$nheight.'" width="'.$nwidth.'" class="rien" style="'.$decalerAdroite.'height: '.$nheight.';width: '.$nwidth.';border-radius: '.$arrondiPhoto.';" src="'.$fichierpourHTML.'" alt="image">';
		} else {
			log::add('diaporama', 'debug', "**********************Ce fichier n'existe pas : ".$fichier.'***********************************');
			return "Le fichier $fichier n'existe pas.";
		}    
	}
	public function redimensionne_PhotoFacebook($source,$Width,$Height,$maxWidth,$maxHeight, $arrondiPhoto, $centrerLargeur)  {
		$iw=$Width;
		$ih=$Height;
		# Paramètres : Largeur et Hauteur souhaiter $maxWidth, $maxHeight
		# Calcul des rapport de Largeur et de Hauteur
		$widthscale = $iw/$maxWidth;
		$heightscale = $ih/$maxHeight;
		$rapport = $ih/$widthscale;
		# Calul des rapports Largeur et Hauteur à afficher
		if($rapport < $maxHeight)
			{$nwidth = $maxWidth;}
		 else
			{$nwidth = round($iw/$heightscale);}
		 if($rapport < $maxHeight)
			{$nheight = $rapport;}
		 else
			{$nheight = $maxHeight;}
		$decalerAdroite="";
		if ($centrerLargeur) {
			$decalage=round(($maxWidth-$nwidth)/2);
			if ($decalage > 1)
				$decalerAdroite="position: relative; left: ".$decalage."px;";
		log::add('diaporama', 'debug', '--> Image '.$iw.'x'.$ih.' redimensée en '.$nwidth.'x'.$nheight);
		}
		return '<img height="'.$nheight.'" width="'.$nwidth.'" class="rien" style="'.$decalerAdroite.'height: '.$nheight.';width: '.$nwidth.';border-radius: '.$arrondiPhoto.';" src="'.$source.'" alt="image">';
	}
	
	public function redimensionne_Photo_ET_Exif($tirageSort,$maxWidth,$maxHeight, $arrondiPhoto, $centrerLargeur, $_indexPhoto, $_device, $qualite=-1, $_autoriserDateFichier=false)  {

		//event::add('jeedom::alert', array('level' => 'warning', 'page' => 'diaporama', 'message' => __('dossiertmp:'.sys_get_temp_dir().' ', __FILE__)));
			$dossierPlugin=realpath(dirname(__FILE__).'/../../');
			$dossierTMP = $dossierPlugin.'/tmp';
			$fichier=$dossierTMP.'/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
			$fichierpourHTML='plugins/diaporama/tmp/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
			$fichiercompletResize=$dossierTMP.'/diaporama_'.$this->getId()."_".$tirageSort.'_resize.jpg';

		/*
		if (!file_exists($fichier)) {	
			$fichier=$dossierTMP.'/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
			$fichierpourHTML='plugins/diaporama/tmp/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
		}*/
		if (file_exists($fichier)) {
		//log::add('diaporama', 'debug', '-->existe : $fichier :'.$$fichier);
			$exif = exif_read_data($fichier, 'EXIF');
			log::add('diaporama', 'debug', '--> Récupération données Exif :'.json_encode($exif));
			$intDate=0;
			// Date
			if     (($_autoriserDateFichier)&&(strtotime($exif['FileDateTime']))) $intDate=strtotime($exif['FileDateTime']);
			elseif (strtotime($exif['DateTimeOriginal'])) $intDate=strtotime($exif['DateTimeOriginal']);
			elseif (strtotime($exif['DateTimeDigitized'])) $intDate=strtotime($exif['DateTimeDigitized']);
			elseif (strtotime($exif['DateTimeDigitized'])) $intDate=strtotime($exif['DateTimeDigitized']);
			elseif (strtotime($exif['GPSDateStamp'])) $intDate=strtotime($exif['GPSDateStamp']);
			//else $intDate=$exif['FileDateTime'];
			if ($intDate!=0) {
				$formatDateHeure = config::byKey('formatDateHeure', 'diaporama', "d-m-Y H:i:s");
				if (($formatDateHeure =="") || ($formatDateHeure =="0")) $formatDateHeure="d-m-Y H:i:s"; // visiblement ça met 0 si non rempli
				//log::add('diaporama', 'debug', '--> formatDateHeure: '.$formatDateHeure);
				$_device->checkAndUpdateCmd('date'.$_indexPhoto, date($formatDateHeure, $intDate));
				log::add('diaporama', 'debug', '--> Date&Heure récupérées: '.date($formatDateHeure, $intDate));
			}
			else {
				$_device->checkAndUpdateCmd('date'.$_indexPhoto, "");
				log::add('diaporama', 'debug', '--> Date&Heure non récupérées');
			}
			// Calcul dimension 
			$imageinfo= getimagesize("$fichier");
			$iw=$imageinfo[0];
			$ih=$imageinfo[1];
			# Paramètres : Largeur et Hauteur souhaiter $maxWidth, $maxHeight
			# Calcul des rapport de Largeur et de Hauteur
			$widthscale = $iw/$maxWidth;
			$heightscale = $ih/$maxHeight;
			$rapport = $ih/$widthscale;
			# Calul des rapports Largeur et Hauteur à afficher
			if($rapport < $maxHeight)
				{$nwidth = $maxWidth;}
			 else
				{$nwidth = round($iw/$heightscale);}
			 if($rapport < $maxHeight)
				{$nheight = $rapport;}
			 else
				{$nheight = $maxHeight;}
			
			
			// Création de la ressource pour la nouvelle image
			$dest = imagecreatetruecolor($nwidth, $nheight);
			$photoaTraiter = ImageCreateFromJpeg($fichier);
			
			// On Retourne si besoin
			if ((!empty($exif['Orientation'])) && (config::byKey('rotate', 'diaporama', '0'))) {
                switch($exif['Orientation']) {
                case 8:
                    $photoaTraiter = imagerotate($photoaTraiter,90,0);
					$tmp=$nwidth;
					$nwidth=$nheight;
					$nheight=$tmp;
                    break;
                case 3:
                    $photoaTraiter = imagerotate($photoaTraiter,180,0);
                    break;
                case 6:
                    $photoaTraiter = imagerotate($photoaTraiter,-90,0);
					$tmp=$nwidth;
					$nwidth=$nheight;
					$nheight=$tmp;                    
					break;
                }

            } 

			
			
			// Création de l'image redimentionnée
			if(imagecopyresampled($dest, $photoaTraiter, 0, 0, 0, 0, $nwidth, $nheight, $iw, $ih)) {	

					imagejpeg($photoaTraiter,$fichiercompletResize,$qualite);
					log::add('diaporama', 'debug', '--> Image '.$iw.'x'.$ih.' redimensée en '.$nwidth.'x'.$nheight.' avec qualité '.$qualite.'/100');

			} else {
			log::add('diaporama', 'debug', "--> Souci lors du Resize"); }

			// Localisation	
			$siteGPS="";
			$APIGoogleMaps = config::byKey('APIGoogleMaps', 'diaporama', '0');
			if ($APIGoogleMaps !="" && is_array($exif['GPSLatitude'])) {
				$requete="https://maps.googleapis.com/maps/api/geocode/json?latlng=".self::DMSversDD($exif['GPSLatitudeRef'],$exif['GPSLatitude']).",".self::DMSversDD($exif['GPSLongitudeRef'],$exif['GPSLongitude'])."&key=".$APIGoogleMaps;
				log::add('diaporama', 'debug', '--> Requete Web: '."https://maps.googleapis.com/maps/api/geocode/json?latlng=".self::DMSversDD($exif['GPSLatitudeRef'],$exif['GPSLatitude']).",".self::DMSversDD($exif['GPSLongitudeRef'],$exif['GPSLongitude'])."&key=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");
				$recupereJson=file_get_contents($requete);
				$json = json_decode($recupereJson,true);
				if ($json['error_message'] != "")
					$siteGPS=$json['error_message'];
				else
					$siteGPS=strstr($json['plus_code']['compound_code'], ' ');
				log::add('diaporama', 'debug', '--> Adresse trouvée: '.$siteGPS);
			} else {
			log::add('diaporama', 'debug', "--> Pas de coodonnées GPS de détectées (ou pas de clé Google Maps configurée)"); }
			$_device->checkAndUpdateCmd('site'.$_indexPhoto, $siteGPS); 		
		
		
		
		

			
			$decalerAdroite="";
			if ($centrerLargeur) {
				$decalage=round(($maxWidth-$nwidth)/2);
				if ($decalage > 1)
					$decalerAdroite="position: relative; left: ".$decalage."px;";
			}
			
			try {
			
			if (file_exists($fichiercompletResize)) {
				$fichierpourHTML='plugins/diaporama/tmp/diaporama_'.$this->getId()."_".$tirageSort.'_resize.jpg';
				unlink($fichier);
			//log::add('diaporama', 'debug', "**********************Le fichier resize a été généré ".$fichiercompletResize.'***********************************');
			}
			//else 
			//log::add('diaporama', 'debug', "**********************Le fichier resize n'a pas été généré ".$fichiercompletResize.'***********************************');
	
			ImageDestroy($fichier); 
			ImageDestroy($photoaTraiter); 
			}
			catch(Exception $exc) {
			log::add('diaporama', 'debug', "**********************Erreur lors de la suppression de ".$fichier.'***********************************');
			}
			
			//log::add('diaporama', 'debug', "**********************Return renvoi le fichier :  ".$fichierpourHTML.'***********************************');
			log::add('diaporama', 'debug', '--> Affichage de  '.$fichierpourHTML);
			return '<img height="'.$nheight.'" width="'.$nwidth.'" class="rien" style="'.$decalerAdroite.'height: '.$nheight.';width: '.$nwidth.';border-radius: '.$arrondiPhoto.';" src="'.$fichierpourHTML.'" alt="image">';
		} else {
			log::add('diaporama', 'debug', "**********************Ce fichier n'existe pas : ".$fichier.'***********************************');
			return "Le fichier $fichier n'existe pas.";
		}    
	}
	
	/*
	public function infosExif($tirageSort, $_indexPhoto, $_device, $_autoriserDateFichier=false)  {
		
			$dossierPlugin=realpath(dirname(__FILE__).'/../../');
			$dossierTMP = $dossierPlugin.'/tmp';
			$fichier=$dossierTMP.'/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
			$fichiercompletResize=$dossierTMP.'/diaporama_'.$this->getId()."_".$tirageSort.'_resize.jpg';
			$fichierpourHTML='plugins/diaporama/tmp/diaporama_'.$this->getId()."_".$tirageSort.'_resize.jpg';

		if (file_exists($fichier)) {
			$exif = exif_read_data($fichier, 'EXIF');
			log::add('diaporama', 'debug', '--> Récupération données Exif :'.json_encode($exif));
			$intDate=0;
			if     (($_autoriserDateFichier)&&(strtotime($exif['FileDateTime']))) $intDate=strtotime($exif['FileDateTime']);
			elseif (strtotime($exif['DateTimeOriginal'])) $intDate=strtotime($exif['DateTimeOriginal']);
			elseif (strtotime($exif['DateTimeDigitized'])) $intDate=strtotime($exif['DateTimeDigitized']);
			elseif (strtotime($exif['DateTimeDigitized'])) $intDate=strtotime($exif['DateTimeDigitized']);
			elseif (strtotime($exif['GPSDateStamp'])) $intDate=strtotime($exif['GPSDateStamp']);
			//else $intDate=$exif['FileDateTime'];
			if ($intDate!=0) {
				$formatDateHeure = config::byKey('formatDateHeure', 'diaporama', '0');
				if ($formatDateHeure =="") $formatDateHeure="d-m-Y H:i:s";
				$_device->checkAndUpdateCmd('date'.$_indexPhoto, date($formatDateHeure, $intDate));
				log::add('diaporama', 'debug', '--> Date&Heure récupérées: '.date($formatDateHeure, $intDate));
			}
			else {
				$_device->checkAndUpdateCmd('date'.$_indexPhoto, "");
				log::add('diaporama', 'debug', '--> Date&Heure non récupérées');
			}
				
			//log::add('diaporama', 'debug', '--> Orientation récupérée: '.$exif['GPSLatitude']);
			if (config::byKey('rotate', 'diaporama', '0')) {
				$photoaTraiter = ImageCreateFromJpeg($fichier);
				switch ($exif['Orientation']) {
					case "6":
						imagejpeg(imagerotate($photoaTraiter, 270, 0),$fichiercompletResize);
						break;
					case "8":
						imagejpeg(imagerotate($photoaTraiter, 90, 0),$fichiercompletResize);
						break;
					case "3":
						imagejpeg(imagerotate($photoaTraiter, 180, 0),$fichiercompletResize);
						break;
				}	
			}
			$siteGPS="";
			$APIGoogleMaps = config::byKey('APIGoogleMaps', 'diaporama', '0');
			if ($APIGoogleMaps !="" && is_array($exif['GPSLatitude'])) {
				$requete="https://maps.googleapis.com/maps/api/geocode/json?latlng=".self::DMSversDD($exif['GPSLatitudeRef'],$exif['GPSLatitude']).",".self::DMSversDD($exif['GPSLongitudeRef'],$exif['GPSLongitude'])."&key=".$APIGoogleMaps;
				log::add('diaporama', 'debug', '--> Requete Web: '."https://maps.googleapis.com/maps/api/geocode/json?latlng=".self::DMSversDD($exif['GPSLatitudeRef'],$exif['GPSLatitude']).",".self::DMSversDD($exif['GPSLongitudeRef'],$exif['GPSLongitude'])."&key=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");
				$recupereJson=file_get_contents($requete);
				$json = json_decode($recupereJson,true);
				if ($json['error_message'] != "")
					$siteGPS=$json['error_message'];
				else
					$siteGPS=strstr($json['plus_code']['compound_code'], ' ');
				log::add('diaporama', 'debug', '--> Adresse trouvée: '.$siteGPS);
			} else {
			log::add('diaporama', 'debug', "--> Pas de coodonnées GPS de détectées (ou pas de clé Google Maps configurée)"); }
			$_device->checkAndUpdateCmd('site'.$_indexPhoto, $siteGPS); 
		} else {
			log::add('diaporama', 'debug', "**********************Ce fichier n'existe pas : ".$fichier.'***********************************');
		} 
	}*/
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
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~Refresh, dossier tmp :'.jeedom::getTmpFolder('diaporama').'~~~~~~~~~~~~~~~~~~~~~~~~~');
		$largeurPhoto=$this->getConfiguration('largeurPhoto');
		$hauteurPhoto=$this->getConfiguration('hauteurPhoto');
		$arrondiPhoto=$this->getConfiguration('arrondiPhoto');
		$qualitePhoto=$this->getConfiguration('qualitePhoto');
		if ($largeurPhoto =="") $largeurPhoto="250";
		if ($hauteurPhoto =="") $hauteurPhoto="250";		
		if ($arrondiPhoto =="") $arrondiPhoto="30%";		
		if ($qualitePhoto =="") $arrondiPhoto="-1";		
		$tirageSort="999";//999 pour boucler dans tirageSort
		$touteslesValeurs= array($tirageSort);
		$nbPhotosaGenerer=$this->getConfiguration('nbPhotosaGenerer');
		$centrerLargeur=$this->getConfiguration('centrerLargeur');
		//log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$centrerLargeur:'.$centrerLargeur.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		$formatDateHeure = config::byKey('formatDateHeure', 'diaporama', '0');
		if (($formatDateHeure =="") || ($formatDateHeure =="0")) $formatDateHeure="d-m-Y H:i:s"; // visiblement ça met 0 si non rempli
		if ($nbPhotosaGenerer<1 || $nbPhotosaGenerer>9) $nbPhotosaGenerer=2;
		if ($this->getConfiguration('stockageSamba')==1) {
			$sambaShare	= config::byKey('samba::backup::share')	;
			$dos=$sambaShare.$this->getConfiguration('dossierSambaDiaporama');
			//log::add('diaporama', 'debug', '**********************1***********************************');
			$diapo=self::jpg_list($this->getConfiguration('dossierSambaDiaporama'));
			//log::add('diaporama', 'debug', '**********************diapo:'.json_encode($diapo).'***********************************');
			//log::add('diaporama', 'debug', '**********************diapo1:'.$diapo['filename'].'***********************************');
			$nbPhotos=count($diapo);
			log::add('diaporama', 'debug', '----------------------------------------------------------------------------');
			log::add('diaporama', 'debug', 'Dans le dossier '.$dos.', il y a '.$nbPhotos.' photos');
			for ($i = 1; $i <= $nbPhotosaGenerer; $i++) {
			while ($compteurparSecurite < 20 && in_array($tirageSort, $touteslesValeurs))
				{
				$tirageSort=mt_rand(0,$nbPhotos-1);
				$compteurparSecurite++;
				}
			array_push($touteslesValeurs, $tirageSort);
			$file = $diapo[$tirageSort];
			//if (!($file)) log::add('diaporama', 'error', "Le fichier ".$diapo[$tirageSort]." introuvable !!!");
			
			$dossierPlugin=realpath(dirname(__FILE__).'/../../');
			$dossierTMP = $dossierPlugin.'/tmp';
			if (!file_exists($dossierTMP)) {	
				if (mkdir($dossierTMP, 0775)) 
					log::add('diaporama', 'debug', "Dossier temporaire ".$dossierTMP." créé avec succès");
				else
					log::add('diaporama', 'error', "Dossier temporaire ".$dossierTMP." non créé !!!");
			}
			
			$newfile = $dossierTMP.'/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
		
			//$newfile = '/var/www/html/tmp/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
			log::add('diaporama', 'debug', 'Fichier sélectionné au hasard:'.$file.' copié dans '.$this->getConfiguration('dossierSambaDiaporama').' en '.$newfile);
			try {
				self::downloadCore($this->getConfiguration('dossierSambaDiaporama'), $file, $newfile);
				$image=self::redimensionne_Photo_ET_Exif($tirageSort,$largeurPhoto,$hauteurPhoto, $arrondiPhoto, $centrerLargeur,$i,$this, $qualitePhoto);
				$this->checkAndUpdateCmd('photo'.$i, $image);	
			}
			catch(Exception $exc) {
				log::add('diaporama', 'error', __('Erreur pour ', __FILE__) . ' : ' . $exc->getMessage());
			}			
			}
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
			for ($i = 1; $i <= $nbPhotosaGenerer; $i++) {
				$tirageSort=mt_rand(0,$CompteAlbums-1);
				$compteurPourTrouverAlbum=0;
				foreach ($Albums as $key2 => $value2) {
					if (($Albums[$key2][1] == '1') && ($compteurPourTrouverAlbum==$tirageSort)) {$idAlbumChoisi=$value2[0]; break;}
					if ($Albums[$key2][1] == '1') $compteurPourTrouverAlbum++;
				}	
				$albumsFacebook = config::byKey('albumsFacebook', 'diaporama', '0');
				foreach ($albumsFacebook as $value) {
					if ($value['id'] == $idAlbumChoisi) { $nbdePhotosdansAlbum=$value['count']; break;}
				}		
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
						if ($tirageSort<=$countdata) {
								// c'est ok, la photo est dans $json['data']
							$idphotoChoisie=$data[$indexPhoto]['id'];
							log::add('diaporama', 'debug', 'ID de la photo choisie : '.$idphotoChoisie);
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
					$this->checkAndUpdateCmd('date'.$i, date($formatDateHeure,  strtotime($json['created_time'])));				
					$this->checkAndUpdateCmd('site'.$i, $json['place']['name']);		
					$this->checkAndUpdateCmd('pays'.$i, $json['place']['location']['country']);		
					$this->checkAndUpdateCmd('ville'.$i, $json['place']['location']['city']);		
					$this->checkAndUpdateCmd('album'.$i, $json['album']['name']);		
					$image=self::redimensionne_PhotoFacebook($json['images']['0']['source'],$json['images']['0']['width'],$json['images']['0']['height'],$largeurPhoto,$hauteurPhoto, $arrondiPhoto, $centrerLargeur);
					$this->checkAndUpdateCmd('photo'.$i, $image);		
				} else {
					log::add('diaporama', 'debug', "*********************** Souci de récupération des infos de la photo");
				}		
			}
		}
		else {
			$dossierLocal=$this->getConfiguration('cheminDiaporama');
			if ($dossierLocal =="") $dossierLocal="/../images/"; // par défaut
			$dos=dirname(__FILE__).$dossierLocal; 
			$diapo=glob($dos.'*.jpg');
			$nbPhotos=count($diapo);
			for ($i = 1; $i <= $nbPhotosaGenerer; $i++) {
			while ($compteurparSecurite < 20 && in_array($tirageSort, $touteslesValeurs))
				{
				$tirageSort=mt_rand(0,$nbPhotos-1);
				$compteurparSecurite++;
				}
			array_push($touteslesValeurs, $tirageSort);
			$file = realpath($diapo[$tirageSort]);
			if (!($file)) log::add('diaporama', 'error', "Le fichier ".$diapo[$tirageSort]." introuvable !!!");
			
			$dossierPlugin=realpath(dirname(__FILE__).'/../../');
			$dossierTMP = $dossierPlugin.'/tmp';
			if (!file_exists($dossierTMP)) {	
				if (mkdir($dossierTMP, 0775)) 
					log::add('diaporama', 'debug', "Dossier temporaire ".$dossierTMP." créé avec succès");
				else
					log::add('diaporama', 'error', "Dossier temporaire ".$dossierTMP." non créé !!!");
			}
			
			$newfile = $dossierTMP.'/diaporama_'.$this->getId()."_".$tirageSort.'.jpg';
			if (!copy($file, $newfile)) log::add('diaporama', 'debug', 'Copie image '.$file.' en '.$newfile.' NOK'); else log::add('diaporama', 'debug', 'Copie image '.$file.' en '.$newfile.' OK');
			$image=self::redimensionne_Photo_ET_Exif($tirageSort,$largeurPhoto,$hauteurPhoto, $arrondiPhoto, $centrerLargeur,$i,$this, $qualitePhoto);
			$this->checkAndUpdateCmd('photo'.$i, $image);			
			}
		}
	}
	public function postSave() {
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
			$cmd->setOrder($i*6);
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
			$cmd->setOrder($i*6+1);
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
			$cmd->setOrder($i*6+2);
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
			$cmd->setOrder($i*6+3);
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
			$cmd->setOrder($i*6+4);
			//$cmd->setDisplay('icon', '<i class="loisir-musical7"></i>');
			$cmd->setDisplay('title_disable', 1);
		}
		$cmd->save();					
		
		$cmd = $this->getCmd(null, 'album'.$i);
		if (!is_object($cmd)) {
			$cmd = new diaporamaCmd();
			$cmd->setType('info');
			$cmd->setLogicalId('album'.$i);
			$cmd->setSubType('string');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setName('Album '.$i);
			$cmd->setIsVisible(1);
			$cmd->setOrder($i*6+5);
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
	}
	public function preUpdate() {
	}
	public function preRemove () {
	}
	/*public static function lsjpg($_dir = '', $_type = 'backup') {
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
		return array_reverse($result);
	}*/
	// functions de samba.repo.php repris et simplifié
	public static function lsjpg_count($_dir = '', $_type = 'backup') {
		//$cmd = repo_samba::makeSambaCommand('cd ' . $_dir . ';ls *.jpg -U', $_type);
		//log::add('diaporama', 'debug', '>>>>>>>>>>>>>>>>>>>>>'.json_encode($file));
		//log::add('diaporama', 'debug', '>>>>>>>>>>>>>>>>>>>>>'.json_encode($file));
		return count(self::jpg_list($_dir,$_type));
	}	
	/*public static function jpg_list($_dir = '') {
		$return = array();
		foreach (self::ls($_dir) as $file) {
			if (stripos($file['filename'],'.jpg') !== false) {
				$return[] = $file['filename'];
			}
		}
		return $return;
	}	*/
	public static function downloadCore($_dir= '', $_fileOrigine, $_fileDestination) {
		$cmd = repo_samba::makeSambaCommand('cd ' . $_dir . ';get \"'.$_fileOrigine.'\" '.$_fileDestination, 'backup');
		com_shell::execute($cmd);
		return;
	}
	public static function jpg_list($_dir = '', $_type = 'backup') {
		$cmd = repo_samba::makeSambaCommand('cd ' . $_dir . ';ls *.* -U', $_type);
		$result = explode("\n", com_shell::execute($cmd));
		$return = array();
		for ($i = 0; $i < count($result) - 2; $i++) {
			$LigneATraiter=substr($result[$i],-1*(strlen($result[$i])-2));
			if (!(stristr($LigneATraiter, '.jpg'))) continue; // on enlève tout ce qui n'est pas un fichier (ou un dossier)
			$fichierFind=stristr($LigneATraiter, '.jpg', true).substr(stristr($LigneATraiter, '.jpg'),0,4);
			//log::add('diaporama', 'debug', 'Fichier JPG trouvé >>'.$fichierFind.'<<');
			$return[] = $fichierFind;
		}
		//usort($return, 'repo_samba::sortByDatetime');
		//log::add('diaporama', 'debug', '>>>>>>>>>>>>>>>>>>>>>return:'.json_encode($return));
		//return array_reverse($return);
		return $return;
	}	
	/**
	 * Fonction qui permet de redimensionner une image en conservant les proportions
	 * @param  string  $image_path Chemin de l'image
	 * @param  string  $image_dest Chemin de destination de l'image redimentionnée (si vide remplace l'image envoyée)
	 * @param  integer $max_size   Taille maximale en pixels
	 * @param  integer $qualite    Qualité de l'image entre 0 et 100
	 * @param  string  $type       'auto' => prend le coté le plus grand
	 *                             'width' => prend la largeur en référence
	 *                             'height' => prend la hauteur en référence
	 * @return string              'success' => redimentionnement effectué avec succès
	 *                             'wrong_path' => le chemin du fichier est incorrect
	 *                             'no_img' => le fichier n'est pas une image
	 *                             'resize_error' => le redimensionnement a échoué
	 */
	function resize_img($image_path,$image_dest,$max_size = 300,$qualite = 100,$type = 'auto'){

	  // Vérification que le fichier existe
	  if(!file_exists($image_path)):
		return 'wrong_path';
	  endif;

	  if($image_dest == ""):
		$image_dest = $image_path;
	  endif;
	  // Extensions et mimes autorisés
	  $extensions = array('jpg','jpeg','png','gif');
	  $mimes = array('image/jpeg','image/gif','image/png');

	  // Récupération de l'extension de l'image
	  $tab_ext = explode('.', $image_path);
	  $extension  = strtolower($tab_ext[count($tab_ext)-1]);

	  // Récupération des informations de l'image
	  $image_data = getimagesize($image_path);

	  // Si c'est une image envoyé alors son extension est .tmp et on doit d'abord la copier avant de la redimentionner
	  if($extension == 'tmp' && in_array($image_data['mime'],$mimes)):
		copy($image_path,$image_dest);
		$image_path = $image_dest;

		$tab_ext = explode('.', $image_path);
		$extension  = strtolower($tab_ext[count($tab_ext)-1]);
	  endif;

	  // Test si l'extension est autorisée
	  if (in_array($extension,$extensions) && in_array($image_data['mime'],$mimes)):
		
		// On stocke les dimensions dans des variables
		$img_width = $image_data[0];
		$img_height = $image_data[1];

		// On vérifie quel coté est le plus grand
		if($img_width >= $img_height && $type != "height"):

		  // Calcul des nouvelles dimensions à partir de la largeur
		  if($max_size >= $img_width):
			return 'no_need_to_resize';
		  endif;

		  $new_width = $max_size;
		  $reduction = ( ($new_width * 100) / $img_width );
		  $new_height = round(( ($img_height * $reduction )/100 ),0);

		else:

		  // Calcul des nouvelles dimensions à partir de la hauteur
		  if($max_size >= $img_height):
			return 'no_need_to_resize';
		  endif;

		  $new_height = $max_size;
		  $reduction = ( ($new_height * 100) / $img_height );
		  $new_width = round(( ($img_width * $reduction )/100 ),0);

		endif;

		// Création de la ressource pour la nouvelle image
		$dest = imagecreatetruecolor($new_width, $new_height);

		// En fonction de l'extension on prépare l'iamge
		switch($extension){
		  case 'jpg':
		  case 'jpeg':
			$src = imagecreatefromjpeg($image_path); // Pour les jpg et jpeg
		  break;

		  case 'png':
			$src = imagecreatefrompng($image_path); // Pour les png
		  break;

		  case 'gif':
			$src = imagecreatefromgif($image_path); // Pour les gif
		  break;
		}

		// Création de l'image redimentionnée
		if(imagecopyresampled($dest, $src, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height)):

		  // On remplace l'image en fonction de l'extension
		  switch($extension){
			case 'jpg':
			case 'jpeg':
			  imagejpeg($dest , $image_dest, $qualite); // Pour les jpg et jpeg
			break;

			case 'png':
			  imagepng($dest , $image_dest, $qualite); // Pour les png
			break;

			case 'gif':
			  imagegif($dest , $image_dest, $qualite); // Pour les gif
			break;
		  }

		  return 'success';
		  
		else:
		  return 'resize_error';
		endif;

	  else:
		return 'no_img';
	  endif;
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
		// Controle si 	nbPhotosaGenerer n'est pas vide
		$nbPhotosaGenerer=$this->getConfiguration('nbPhotosaGenerer');
		if ($nbPhotosaGenerer<1 || $nbPhotosaGenerer>9) 
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
			$this->setConfiguration('sambaEtat', "nok"); 		
			$this->setConfiguration('localEtat', "nok"); 
			$this->setConfiguration('cheminDiaporamaMessage', "");
			$TokenFacebook = config::byKey('TokenFacebook', 'diaporama', '0');
			$requete="https://graph.facebook.com/v5.0/me?access_token=".$TokenFacebook;
			log::add('diaporama', 'debug', 'On teste le compte Facebook avec la requète : '.$requete.'***********************************');
			$recupereJson=file_get_contents($requete);
			if(empty($recupereJson)) {
				log::add('diaporama', 'debug', 'Facebook : SOUCI de Token !!! il faut le REGENERER');
				$this->setConfiguration('facebookEtat', "nok");
				$this->setConfiguration('cheminDiaporamaComplet', "<B>Facebook : SOUCI de Token !!! il faut le REGENERER</B>"); 
				}
			if ($recupereJson=file_get_contents($requete, true)) {
				$json = json_decode($recupereJson,true);
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
						$this->setConfiguration('nombrePhotos', $compteur." (".$totalPhotosCochees." sélectionnées)");
					}
				}
				else {
					log::add('diaporama', 'debug', '******* Souci dans la requète JSON '.$recupereJson);
					log::add('diaporama', 'debug', '******* Souci dans la requète JSON ');
					$this->setConfiguration('cheminDiaporamaValide', "nok");
					$this->setConfiguration('facebookEtat', "nok");
					$this->setConfiguration('derniereMAJ', date("d-m-Y H:i:s"));	
					$this->setConfiguration('nombrePhotos', "");			
					$this->setConfiguration('cheminDiaporamaComplet', "Facebook : Error"); 
				}
			}
		}
	}
}
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
	}
}