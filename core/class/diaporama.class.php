<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class diaporama extends eqLogic {
	
    public static function templateWidget(){
		$return = array('info' => array('string' => array()));
		$return = array('action' => array('select' => array(), 'slider' => array()));
		$return['info']['string']['subText2'] = array('template' => 'album' );
		$return['info']['string']['alarmmusicalmusic'] = array('template' => 'alarmmusicalmusic', 'replace' => array("#hide_name#" => "hidden"));
		$return['info']['string']['title'] =    array('template' => 'title');
		$return['info']['string']['url'] =    array('template' => 'image');
		$return['info']['string']['interaction'] =    array('template' => 'cadre');
		$return['action']['message']['message'] =    array(
				'template' => 'message',
				'replace' => array("#_desktop_width_#" => "100","#_mobile_width_#" => "50", "#title_disable#" => "1", "#message_disable#" => "0")
		);
		$return['action']['select']['list'] =    array(
				'template' => 'table',
				'replace' => array("#_desktop_width_#" => "100","#_mobile_width_#" => "50", "#hide_name#" => "whidden")
		);		
		$return['action']['slider']['volume'] =    array(
				'template' => 'bouton',
				'replace' => array("#hide_name#" => "hidden", "#step#" => "10")
		);
		$return['info']['string']['state'] = array(
				'template' => 'tmplmultistate_diaporama',
				'replace' => array("#hide_name#" => "hidden", "#hide_state#" => "hidden", "#marge_gauche#" => "5px", "#marge_haut#" => "-15px"),
				'test' => array(
					array('operation' => "#value# == 'PLAYING'", 'state_light' => "<img src='plugins/diaporama/core/img/playing.png'  title ='" . __('Playing', __FILE__) . "'>",
							'state_dark' => "<img src='plugins/diaporama/core/img/playing.png' title ='" . __('En charge', __FILE__) . "'>"),
					array('operation' => "#value# != 'PLAYING'",'state_light' => "<img src='plugins/diaporama/core/img/paused.png' title ='" . __('En Pause', __FILE__) . "'>")
				)
			);
		$return['info']['string']['alarm'] = array(
				'template' => 'alarm',
				'replace' => array("#hide_name#" => "hidden", "#marge_gauche#" => "55px", "#marge_haut#" => "15px"),
				'test' => array(
					array('operation' => "#value# == ''", 
					'state_light' => "<img src='plugins/diaporama/core/img/Alarm-Clock-Icon-Off.png' title ='" . __('Playing', __FILE__) . "'>",
					'state_dark'  => "<img src='plugins/diaporama/core/img/Alarm-Clock-Icon-Off_dark.png' title ='" . __('En charge', __FILE__) . "'>"),
					array('operation' => "#value# != ''",
					'state_light' => "<img src='plugins/diaporama/core/img/Alarm-Clock-Icon-On.png' title ='" . __('En Pause', __FILE__) . "'>",
					'state_dark' =>  "<img src='plugins/diaporama/core/img/Alarm-Clock-Icon-On_dark.png' title ='" . __('En Pause', __FILE__) . "'>")
				)
			);
		$return['info']['string']['alarmmusical'] = array(
				'template' => 'alarm',
				'replace' => array("#hide_name#" => "hidden", "#marge_gauche#" => "55px", "#marge_haut#" => "15px"),
				'test' => array(
					array('operation' => "#value# == ''", 
					'state_light' => "<img src='plugins/diaporama/core/img/Alarm-Musical-Icon-Off.png' title ='" . __('Playing', __FILE__) . "'>",
					'state_dark'  => "<img src='plugins/diaporama/core/img/Alarm-Musical-Icon-Off_dark.png' title ='" . __('En charge', __FILE__) . "'>"),
					array('operation' => "#value# != ''",
					'state_light' => "<img src='plugins/diaporama/core/img/Alarm-Musical-Icon-On.png' title ='" . __('En Pause', __FILE__) . "'>",
					'state_dark' =>  "<img src='plugins/diaporama/core/img/Alarm-Musical-Icon-On_dark.png' title ='" . __('En Pause', __FILE__) . "'>")
				)
			);				
		$return['info']['string']['timer'] = array(
				'template' => 'alarm',
				'replace' => array("#hide_name#" => "hidden", "#marge_gauche#" => "55px", "#marge_haut#" => "15px"),
				'test' => array(
					array('operation' => "#value# == ''", 
					'state_light' => "<img src='plugins/diaporama/core/img/Alarm-Timer-Icon-Off.png' title ='" . __('Playing', __FILE__) . "'>",
					'state_dark'  => "<img src='plugins/diaporama/core/img/Alarm-Timer-Icon-Off_dark.png' title ='" . __('En charge', __FILE__) . "'>"),
					array('operation' => "#value# != ''",
					'state_light' => "<img src='plugins/diaporama/core/img/Alarm-Timer-Icon-On.png' title ='" . __('En Pause', __FILE__) . "'>",
					'state_dark' =>  "<img src='plugins/diaporama/core/img/Alarm-Timer-Icon-On_dark.png' title ='" . __('En Pause', __FILE__) . "'>")
				)
			);			
			$return['info']['string']['reminder'] = array(
				'template' => 'alarm',
				'replace' => array("#hide_name#" => "hidden", "#marge_gauche#" => "55px", "#marge_haut#" => "4px"),
				'test' => array(
					array('operation' => "#value# == ''", 
					'state_light' => "<img src='plugins/diaporama/core/img/Alarm-Reminder-Icon-Off.png' title ='" . __('Playing', __FILE__) . "'>",
					'state_dark'  => "<img src='plugins/diaporama/core/img/Alarm-Reminder-Icon-Off_dark.png' title ='" . __('En charge', __FILE__) . "'>"),
					array('operation' => "#value# != ''",
					'state_light' => "<img src='plugins/diaporama/core/img/Alarm-Reminder-Icon-On.png' title ='" . __('En Pause', __FILE__) . "'>",
					'state_dark' =>  "<img src='plugins/diaporama/core/img/Alarm-Reminder-Icon-On_dark.png' title ='" . __('En Pause', __FILE__) . "'>")
				)
			);	
	return $return;
	}	


	public static function callProxydiaporama($_url) {
		$url = 'http://' . config::byKey('internalAddr') . ':3456/' . trim($_url, '/') . '&apikey=' . jeedom::getApiKey('openzwave');
		$ch = curl_init();
		curl_setopt_array($ch, array(CURLOPT_URL => $url, CURLOPT_HEADER => false, CURLOPT_RETURNTRANSFER => true,));
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			$curl_error = curl_error($ch);
			curl_close($ch);
			throw new Exception(__('Echec de la requête http : ', __FILE__) . $url . ' Curl error : ' . $curl_error, 404);
		}
		curl_close($ch);
		return (is_json($result)) ? json_decode($result, true) : $result;
	}

	public static function deamon_info() {
		$return = array();
		$return['log'] = 'diaporama_node';
		$return['state'] = 'nok'; 
		// Regarder si diaporama.js est lancé
		$pid = trim(shell_exec('ps ax | grep "diaporama/resources/diaporama.js" | grep -v "grep" | wc -l'));
		if ($pid != '' && $pid != '0') $return['state'] = 'ok';
		// Regarder si le cookie existe :alexa-cookie.json
		$request = realpath(dirname(__FILE__) . '/../../resources/data/alexa-cookie.json');
		if (file_exists($request)) $return['launchable'] = 'ok';
		else {
			$return['launchable'] = 'nok';
			$return['launchable_message'] = "Cookie Amazon ABSENT ";
		}
		return $return;
	}

	public static function deamon_start($_debug = false) {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		log::add('diaporama', 'info', 'Lancement du démon diaporama');
		$url = network::getNetworkAccess('internal', 'proto:127.0.0.1:port:comp') . '/plugins/diaporama/core/api/jeediaporama.php?apikey=' . jeedom::getApiKey('diaporama');
		$log = $_debug ? '1' : '0';
		$sensor_path = realpath(dirname(__FILE__) . '/../../resources');
		$cmd = 'nice -n 19 nodejs ' . $sensor_path . '/diaporama.js ' . network::getNetworkAccess('internal') . ' ' . config::byKey('amazonserver', 'diaporama', 'amazon.fr') . ' ' . config::byKey('alexaserver', 'diaporama', 'alexa.amazon.fr').' '.jeedom::getApiKey('diaporama').' '.log::getLogLevel('diaporama');
		log::add('diaporama', 'debug', 'Lancement démon diaporama : ' . $cmd);
		$result = exec('nohup ' . $cmd . ' >> ' . log::getPathToLog('diaporama_node') . ' 2>&1 &');
		//$cmdStart='nohup ' . $cmd . ' | tee >(grep "WS-MQTT">>'.log::getPathToLog('diaporama_mqtt').') >(grep -v "WS-MQTT">>'. log::getPathToLog('diaporama_node') . ')';
		if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {
			log::add('diaporama', 'error', $result);
			return false;
		}
		$i = 0;
		while ($i < 30) {
			$deamon_info = self::deamon_info();
			if ($deamon_info['state'] == 'ok') break;
			sleep(1);
			$i++;
		}
		if ($i >= 30) {
			log::add('diaporama', 'error', 'Impossible de lancer le démon diaporama, vérifiez le port', 'unableStartDeamon');
			return false;
		}
		message::removeAll('diaporama', 'unableStartDeamon');
		log::add('diaporama', 'info', 'Démon diaporama lancé');
		return true;
	}

	public static function deamon_stop() {
		exec('kill $(ps aux | grep "/diaporama.js" | awk \'{print $2}\')');
		log::add('diaporama', 'info', 'Arrêt du service diaporama');
		$deamon_info = self::deamon_info();
		if ($deamon_info['state'] == 'ok') {
			sleep(1);
			exec('kill -9 $(ps aux | grep "/diaporama.js" | awk \'{print $2}\')');
		}
		$deamon_info = self::deamon_info();
		if ($deamon_info['state'] == 'ok') {
			sleep(1);
			exec('sudo kill -9 $(ps aux | grep "/diaporama.js" | awk \'{print $2}\')');
		}
	}
	
	public static function reinstallNodeJS() { // Reinstall NODEJS from scratch (to use if there is errors in dependancy install)
		$plugindiaporama = plugin::byId('diaporama');
		log::add('diaporama', 'info', 'Suppression du Code NodeJS');
		$cmd = system::getCmdSudo() . 'rm -rf ' . dirname(__FILE__) . '/../../resources/node_modules &>/dev/null';
		log::add('diaporama', 'info', 'Suppression de NodeJS');
		$cmd = system::getCmdSudo() . 'apt-get -y --purge autoremove npm';
		exec($cmd);
		$cmd = system::getCmdSudo() . 'apt-get -y --purge autoremove nodejs';
		exec($cmd);
		log::add('diaporama', 'info', 'Réinstallation des dependances');
		$plugindiaporama->dependancy_install(true);
		return true;
	}

	
	public static function deamonCookie_start($_debug = false) { //*********** Demon Cookie***************
		self::deamonCookie_stop();
		$deamon_info = self::deamon_info();
		log::add('diaporama_cookie', 'info', 'Lancement du démon cookie');
		$log = $_debug ? '1' : '0';
		$sensor_path = realpath(dirname(__FILE__) . '/../../resources');
		$cmd = "kill $(ps aux | grep 'initCookie.js' | awk '{print $2}')";	//Par sécurité, on Kill un éventuel précédent proessus initCookie.js
		log::add('diaporama', 'debug', '---- Kill initCookie.js: ' . $cmd);
		$cmd = 'nice -n 19 nodejs ' . $sensor_path . '/initCookie.js ' . config::byKey('internalAddr') . ' ' . config::byKey('amazonserver', 'diaporama', 'amazon.fr') . ' ' . config::byKey('alexaserver', 'diaporama', 'alexa.amazon.fr');
		log::add('diaporama', 'debug', '---- Lancement démon Alexa-API-Cookie sur port 3457 : ' . $cmd);
		$result = exec('nohup ' . $cmd . ' >> ' . log::getPathToLog('diaporama_cookie') . ' 2>&1 &');
		if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {
			log::add('diaporama', 'error', $result);
			return false;
		}
		message::removeAll('diaporama', 'unableStartDeamonCookie');
		log::add('diaporama_cookie', 'info', 'Démon cookie lancé');
		return true;
	}

	public static function deamonCookie_stop() {
		exec('kill $(ps aux | grep "/initCookie.js" | awk \'{print $2}\')');
		log::add('diaporama', 'info', 'Arrêt du service cookie');
		$deamon_info = self::deamon_info();
		if ($deamon_info['stateCookie'] == 'ok') {
			sleep(1);
			exec('kill -9 $(ps aux | grep "/initCookie.js" | awk \'{print $2}\')');
		}
	}

	public static function dependancy_info() {	//************Dépendances ***********
		$return = array();
		$return['log'] = 'diaporama_dep';
		$resources = realpath(dirname(__FILE__) . '/../../resources/');
		$packageJson=json_decode(file_get_contents($resources.'/package.json'),true);
		$state='ok';
		foreach($packageJson["dependencies"] as $dep => $ver){
			if(!file_exists($resources.'/node_modules/'.$dep.'/package.json')) {
				$state='nok';
			}
		}
		$return['progress_file'] = jeedom::getTmpFolder('diaporama') . '/dependance';
		//$return['state'] = is_dir($resources.'/node_modules') ? 'ok' : 'nok';
		$return['state']=$state;
		return $return;
	}
	
	public static function supprimeTouslesDevices() {
		event::add('jeedom::alert', array('level' => 'success', 'page' => 'diaporama', 'message' => __('Suppression en cours ...', __FILE__)));
		$plugin = plugin::byId('diaporama');
		$eqLogics = eqLogic::byType($plugin->getId());
		foreach ($eqLogics as $eqLogic)
			{
			$eqLogic->remove();
			}
		self::scanAmazonAlexa();
	}
		
	public static function cron($_eqlogic_id = null) {
		// Toutes les minutes, on cherche les players en lecture et on les actualise
		$dd= new Cron\CronExpression('* * * * *', new Cron\FieldFactory);
		$deamon_info = self::deamon_info();
		if ($dd->isDue() && $deamon_info['state'] == 'ok') {
			$plugin = plugin::byId('diaporama');
			$eqLogics = eqLogic::byType($plugin->getId());
			foreach($eqLogics as $eqLogic) {
				if ($eqLogic->getStatus('Playing')) {// On va chercher un Device en "Playing"
						log::add('diaporama', 'debug', 'Refresh automatique (CRON) de '.$eqLogic->getName());
						$eqLogic->refresh();
				}
			}
		}

		$d = new Cron\CronExpression('*/15 * * * *', new Cron\FieldFactory);
		$deamon_info = self::deamon_info();
		if ($d->isDue() && $deamon_info['state'] == 'ok') {
			//log::add('diaporama', 'debug', '---------------------------------------------DEBUT CRON-'.$autorefresh.'-----------------------');
			$json = file_get_contents("http://" . config::byKey('internalAddr') . ":3456/devices");
			$json = json_decode($json, true);
			$status=[];
			foreach ($json as $item) {
				if ($item['name'] == 'This Device') continue;
				
				$eq=eqLogic::byLogicalId($item['serial'],'diaporama');
				if(is_object($eq)) {
					log::add('diaporama','debug','updating online status of '.$item['name'].' to '.(($item['online'])?'true':'false'));
					$eq->setStatus('online', (($item['online'])?true:false));
				}
			}
			
			
			/*
			26/10/2019 Sigalou Désactivation du test 2060, devenu inutile et provoquant un souci avec mqtt
			$eqLogics = ($_eqlogic_id !== null) ? array(eqLogic::byId($_eqlogic_id)) : eqLogic::byType('diaporama', true);
			$test2060NOK=true;
			$hasOneReminderDevice=false;
			foreach ($eqLogics as $diaporama) {
				if($diaporama->hasCapaorFamilyorType("REMINDERS") && $diaporama->getStatus('online') == true) {
					$hasOneReminderDevice=true;
					log::add('diaporama', 'debug', '-----------------------------Test     Lancé sur *'.$diaporama->getName().'*------------------------');
					if ($test2060NOK && $diaporama->test2060()) {
						$test2060NOK=false;
					} else {
						break;	
					}


					//log::add('diaporama', 'debug', '---------------------------------------------FIN Boucle CRON------------------------');
					sleep(2);
				}
				else {
					log::add('diaporama', 'debug', '-----------------------------Test NON Lancé sur *'.$diaporama->getName().'*------------------------');
				}			
			}

			// On va tester si la connexion est active à l'aide d'un rappel en 2060 qu'on retire derrière.
			// $compteurNbTest2060OK correspond au nb de test qui on été OK, si =0 faut relancer le serveur
			if ($test2060NOK && $hasOneReminderDevice) {
				self::restartServeurPHP();
				//message::add('diaporama', 'Connexion close détectée dans le CRON, relance transparente du serveur '.date("Y-m-d H:i:s").' OK !');
				log::add('diaporama', 'debug', 'Connexion close détectée dans le CRON, relance transparente du serveur '.date("Y-m-d H:i:s").' OK !');
			}
			else {//pourra $etre supprimé quand stable
				if($hasOneReminderDevice) {
					log::add('diaporama', 'debug', 'Connexion close non détectée dans le CRON. Tout va bien.');
				} else {
					log::add('diaporama', 'debug', 'Aucun périphérique ne gère les rappels, on ne peut pas tester les connexions close.');
				}
			}*/
		}
		
		$c = new Cron\CronExpression('*/6 * * * *', new Cron\FieldFactory);
		if ($c->isDue() && $deamon_info['state'] == 'ok') {
		self::checkAuth();		
		}	
			
		$autorefreshRR = config::byKey('autorefresh', 'diaporama', '33 3 * * *');/* boucle qui relance la connexion au serveur*/
		$cc = new Cron\CronExpression($autorefreshRR, new Cron\FieldFactory);
		if ($cc->isDue() && $deamon_info['state'] == 'ok') {
		self::restartServeurPHP();		
		}
		
		$r = new Cron\CronExpression('*/15 * * * *', new Cron\FieldFactory);// boucle refresh
//		$r = new Cron\CronExpression('* * * * *', new Cron\FieldFactory);// boucle refresh
		if ($r->isDue() && $deamon_info['state'] == 'ok') {
			$eqLogics = ($_eqlogic_id !== null) ? array(eqLogic::byId($_eqlogic_id)) : eqLogic::byType('diaporama', true);
			foreach ($eqLogics as $diaporama) {
				//log::add('diaporama_node', 'debug', 'CRON Refresh: '.$diaporama->getName());
				$diaporama->refresh(); 				
				sleep(2);
			}	
		}
	//log::add('diaporama', 'debug', '---------------------------------------------FIN CRON------------------------');
	}

	public static function checkAuth() {
				$result = file_get_contents("http://" . config::byKey('internalAddr') . ":3456/checkAuth");
				$resultjson = json_decode($result, true);
				$value = $resultjson['authenticated'];	
		if ($value==1)	
			log::add('diaporama', 'debug', 'Résultat du checkAuth  OK ('.$value.')');
		else
		{
			log::add('diaporama', 'debug', 'Résultat du checkAuth NOK ('.$value.') ==> Relance Serveur');
			self::restartServeurPHP();
			message::add('diaporama', '(Beta Alexa-api) Authentification Amazon revalidée, tout va bien');
		}
	}

	public static function restartServeurPHP() {
		$json = file_get_contents("http://" . config::byKey('internalAddr') . ":3456/restart");
		sleep(2);
	}

	public static function forcerDefaultCmd($_id = null) {
		if (!is_null($_id)) { 
		$device = diaporama::byId($_id);
				if (is_object($device)) {
				$device->setStatus('forceUpdate',true);
				$device->save();
				}
		}		
	}

	public static function forcerDefaultAllCmd() {
		$plugin = plugin::byId('diaporama');
		$eqLogics = eqLogic::byType($plugin->getId());
			foreach ($eqLogics as $eqLogic)
			{
				$eqLogic->setStatus('forceUpdate',true);
				$eqLogic->save();  
			}		
	event::add('jeedom::alert', array('level' => 'success', 'page' => 'diaporama', 'message' => __('Mise à jour terminée', __FILE__)));
	}

	public static function scanAmazonAlexa() {
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != "ok") {
			event::add('jeedom::alert', array('level' => 'danger', 'page' => 'diaporama', 'message' => __('Cookie Amazon Absent, allez dans la Configuration du plugin', __FILE__),));
			return;
		}
		// --- Mise à jour des Amazon Echo
		event::add('jeedom::alert', array('level' => 'success', 'page' => 'diaporama', 'message' => __('Scan en cours...', __FILE__),));
		$json = file_get_contents("http://" . config::byKey('internalAddr') . ":3456/devices");
		$json = json_decode($json, true);
		$numDevices = 0;
		$numNewDevices = 0;
		foreach ($json as $item) {
			// Skip the special device named "This Device"
			if ($item['name'] == 'This Device') continue;
			// On teste s'il faut créer un autre Device Player
			if  ((config::byKey('utilisateurMultimedia', 'diaporama',0)!="0") && (in_array("AUDIO_PLAYER",$item['capabilities']))) {
					// Device PLAYLIST
					$device = diaporama::byLogicalId($item['serial']."_playlist", 'diaporama');
					if (!is_object($device)) {
						$device = self::createNewDevice($item['name']." PlayList", $item['serial']."_playlist");
						$device->setIsVisible(0);					
					}
					// Update device configuration
					$device->setConfiguration('device', $item['name']);
					$device->setConfiguration('type', $item['type']);
					$device->setConfiguration('devicetype', "PlayList");
					$device->setConfiguration('family', $item['family']);
					$device->setConfiguration('members', $item['members']);
					$device->setIsVisible(0);
					$device->setIsEnable(0);
					$device->setConfiguration('capabilities', $item['capabilities']);
					$device->save();
					$device->setStatus('online', (($item['online'])?true:false));
					// Device PLAYER
					$device = diaporama::byLogicalId($item['serial']."_player", 'diaporama');
						if (!is_object($device)) {
							$device = self::createNewDevice($item['name']." Player", $item['serial']."_player");
							$numNewDevices++;
							$device->setConfiguration('widgetPlayListEnable', 0);
						}
					// Update device configuration
					$device->setConfiguration('device', $item['name']);
					$device->setConfiguration('type', $item['type']);
					$device->setConfiguration('devicetype', "Player");
					$device->setConfiguration('family', $item['family']);
					$device->setConfiguration('members', $item['members']);
					$device->setConfiguration('capabilities', $item['capabilities']);
					$device->save();
					$device->setStatus('online', (($item['online'])?true:false));
					$numDevices++;
			}
			// Retireve the device (if already registered in Jeedom)
			$device = diaporama::byLogicalId($item['serial'], 'diaporama');
			if (!is_object($device)) {
				$device = self::createNewDevice($item['name'], $item['serial']);
				//$device->save();
				$numNewDevices++;
			}
			// Update device configuration
			$device->setConfiguration('device', $item['name']);
			$device->setConfiguration('type', $item['type']);
			$device->setConfiguration('devicetype', "Echo");
			$device->setConfiguration('family', $item['family']);
			$device->setConfiguration('members', $item['members']);
			$device->setConfiguration('capabilities', $item['capabilities']);
			$device->save();
			$device->setStatus('online', (($item['online'])?true:false)); //SetStatus doit être lancé après Save et Save après inutile
			$numDevices++;
		}
		
		if (config::byKey('utilisateurSmarthome', 'diaporama',0)!="0") {			
			// --- Mise à jour des SmartHome Devices
			$json = file_get_contents("http://" . config::byKey('internalAddr') . ":3456/smarthomeEntities");
			$json = json_decode($json, true);
			foreach ($json as $item) {
				// Retireve the device (if already registered in Jeedom)
				$device = diaporama::byLogicalId($item['id'], 'diaporama');
				if (!is_object($device)) {
					$device = self::createNewDevice($item['displayName'], $item['id']);
					$numNewDevices++;
				}
				// Update device configuration
				$device->setConfiguration('device', $item['displayName']);
				//$device->setConfiguration('type', $item['description']); a voir si on utilise ou pas descriotion
				$device->setConfiguration('type', $item['providerData']['deviceType']);
				$device->setConfiguration('devicetype', "Smarthome");
				$device->setConfiguration('family', $item['providerData']['categoryType']);
				//$device->setConfiguration('members', $item['members']);
				$device->setConfiguration('capabilities', $item['supportedProperties']);
				//On va mettre dispo, on traite plus tard.
				//$device->setStatus('online', (($item['online'])?true:false));
				$device->save();
				$device->setStatus('online', 'true');
				$numDevices++;
			}
		}
	event::add('jeedom::alert', array('level' => 'success', 'page' => 'diaporama', 'message' => __('Scan terminé. ' . $numDevices . ' équipements mis a jour dont ' . $numNewDevices . " ajouté(s). Appuyez sur F5 si votre écran ne s'est pas actualisé", __FILE__)));
	}

	private static function createNewDevice($deviceName, $deviceSerial) {
		$defaultRoom = intval(config::byKey('defaultParentObject','diaporama','',true));
		event::add('jeedom::alert', array('level' => 'success', 'page' => 'diaporama', 'message' => __('Ajout de "'.$deviceName.'"', __FILE__),));
		$newDevice = new diaporama();
		$newDevice->setName($deviceName);
		$newDevice->setLogicalId($deviceSerial);
		$newDevice->setEqType_name('diaporama');
		$newDevice->setIsVisible(1);
		if($defaultRoom) $newDevice->setObject_id($defaultRoom);
		// JUSTE pour SIGALOU pour aider au dev
		if (substr ($deviceName,0,7) == "Piscine")
			$newDevice->setObject_id('15');
		$newDevice->setDisplay('height', '500');
		$newDevice->setConfiguration('device', $deviceName);
		$newDevice->setConfiguration('serial', $deviceSerial);
		$newDevice->setIsEnable(1);
		return $newDevice;
	}

	public function hasCapaorFamilyorType($thisCapa) {
		
		// Si c'est la bonne famille, on dit OK tout de suite
		$family=$this->getConfiguration('family',"");	
		if($thisCapa == $family) return true; // ajouté pour filtrer sur la famille (pour les groupes par exemple)
		// Si c'est le bon type, on dit OK tout de suite
		$type=$this->getConfiguration('type',"");	
		if($thisCapa == $type) return true; // 
		$capa=$this->getConfiguration('capabilities',"");
		if(((gettype($capa) == "array" && in_array($thisCapa,$capa))) || ((gettype($capa) == "string" && strpos($capa, $thisCapa) !== false))) {
			if($thisCapa == "REMINDERS" && $type == "A15ERDAKK5HQQG") return false;
			return true;
		} else {
			return false;
		}
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

public function redimensionne_Photo($tirageSort,$maxWidth,$maxHeight, $arrondiPhoto)  {
	log::add('diaporama', 'debug', '**********************début redimensionne_Photo*'.$tirageSort.'/'.$maxWidth.'/'.$maxHeight.'/'.$arrondiPhoto.'**********************************');
    $fichiercomplet='/var/www/html/tmp/diaporama_'.$tirageSort.'.jpg';
    $fichier='/tmp/diaporama_'.$tirageSort.'.jpg';
	if (file_exists($fichiercomplet)) {
		log::add('diaporama', 'debug', '**********************file_exists:'.$fichiercomplet.'***********************************');
		# Passage des paramètres dans la table : imageinfo
		$imageinfo= getimagesize("$fichiercomplet");
		$iw=$imageinfo[0];
		$ih=$imageinfo[1];
		log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$iw:'.$iw.'->'.$maxWidth.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$ih:'.$ih.'->'.$maxHeight.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		# Paramètres : Largeur et Hauteur souhaiter $maxWidth, $maxHeight
		# Calcul des rapport de Largeur et de Hauteur
		$widthscale = $iw/$maxWidth;
		$heightscale = $ih/$maxHeight;
		$rapport = $ih/$widthscale;
		log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$widthscale:'.$widthscale.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$heightscale:'.$heightscale.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$rapport:'.$rapport.'('.$ih.'/'.$widthscale.')~~~~~~~~~~~~~~~~~~~~~~~~~');
		# Calul des rapports Largeur et Hauteur à afficher
		if($rapport < $maxHeight)
			{$nwidth = $maxWidth;}
		 else
			{$nwidth = $iw/$heightscale;}
		 if($rapport < $maxHeight)
			{$nheight = $rapport;}
		 else
			{$nheight = $maxHeight;}
		log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$nwidth:'.$nwidth.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		log::add('diaporama', 'debug', '~~~~~~~~~~~~~~~~~~~~~~$nheight:'.$nheight.'~~~~~~~~~~~~~~~~~~~~~~~~~');
		//$nheight="20";
		//$nwidth="50";
		
		return '<img height="'.$nheight.'" width="'.$nwidth.'" class="rien" style="height: '.$nheight.';width: '.$nwidth.';border-radius: '.$arrondiPhoto.';" src="'.$fichier.'" alt="image">';
	} else {
		log::add('diaporama', 'debug', '**********************file_exists PAS:'.$fichiercomplet.'***********************************');
		return "Le fichier $fichiercomplet n'existe pas.";
	}    
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
		
		
		if ($this->getConfiguration('stockageSamba')==1) {
			
			$sambaShare	= config::byKey('samba::backup::share')	;
			$dos=$sambaShare.$this->getConfiguration('dossierSambaDiaporama');
			log::add('diaporama', 'debug', '**********************1***********************************');

			$diapo=self::jpg_list($this->getConfiguration('dossierSambaDiaporama'));
			$nbPhotos=count($diapo);
			log::add('diaporama', 'debug', '**********************nbPhotos:'.$nbPhotos.'***********************************');
			//log::add('diaporama', 'debug', '**********************diapo:'.json_encode($diapo).'***********************************');
			if ($nbPhotosaGenerer<2 || $nbPhotosaGenerer>9) $nbPhotosaGenerer=1;
			for ($i = 1; $i <= $nbPhotosaGenerer; $i++) {
			while ($compteurparSecurite < 20 && in_array($tirageSort, $touteslesValeurs))
				{
				$tirageSort=mt_rand(0,$nbPhotos-1);
				$compteurparSecurite++;
				}
			array_push($touteslesValeurs, $tirageSort);
			$file = $diapo[$tirageSort];
			$newfile = '/var/www/html/tmp/diaporama_'.$tirageSort.'.jpg';
			log::add('diaporama', 'debug', '**********************file:'.$file.'***********************************');
			try {
				self::downloadCore($this->getConfiguration('dossierSambaDiaporama'), $file, $newfile);
				$image=self::redimensionne_Photo($tirageSort,$largeurPhoto,$hauteurPhoto, $arrondiPhoto);
				$this->checkAndUpdateCmd('photo'.$i, $image);			
			}
			catch(Exception $exc) {
				log::add('diaporama', 'error', __('Erreur pour ', __FILE__) . ' : ' . $exc->getMessage());
			}			
			}
			self::chmod777();
		}
		else {
			$dossierLocal=$this->getConfiguration('cheminDiaporama');
			if ($dossierLocal =="") $dossierLocal="/../images/"; // par défaut
			$dos=dirname(__FILE__).$dossierLocal; 
			$diapo=glob($dos.'*.jpg');
			$nbPhotos=count($diapo);
			if ($nbPhotosaGenerer<2 || $nbPhotosaGenerer>9) $nbPhotosaGenerer=1;
			for ($i = 1; $i <= $nbPhotosaGenerer; $i++) {
			while ($compteurparSecurite < 20 && in_array($tirageSort, $touteslesValeurs))
				{
				$tirageSort=mt_rand(0,$nbPhotos-1);
				$compteurparSecurite++;
				}
			array_push($touteslesValeurs, $tirageSort);
			$file = $diapo[$tirageSort];
		
			$newfile = '/var/www/html/tmp/diaporama_'.$tirageSort.'.jpg';
			if (!copy($file, $newfile)) log::add('diaporama', 'debug', 'Copie image '.$file.' en diaporama_'.$tirageSort.' NOK'); else log::add('diaporama', 'debug', 'Copie image '.$file.' en diaporama_'.$tirageSort.' OK');
			//$image='<img class="rien" style="height: '.$hauteurPhoto.';width: '.$largeurPhoto.';border-radius: '.$arrondiPhoto.';" src="tmp/diaporama_'.$tirageSort.'.jpg" alt="image">';
			$image=self::redimensionne_Photo($tirageSort,$largeurPhoto,$hauteurPhoto, $arrondiPhoto);
			$this->checkAndUpdateCmd('photo'.$i, $image);			
			}
		}
	}
		
	

	public function updateCmd ($forceUpdate, $LogicalId, $Type, $SubType, $RunWhenRefresh, $Name, $IsVisible, $title_disable, $setDisplayicon, $infoNameArray, $setTemplate_lien, $request, $infoName, $listValue, $Order, $Test) {
		if ($Test) {
			try {
				if (empty($Name)) $Name=$LogicalId;
				$cmd = $this->getCmd(null, $LogicalId);
				if ((!is_object($cmd)) || $forceUpdate) {
					if (!is_object($cmd)) $cmd = new diaporamaCmd();
					$cmd->setType($Type);
					$cmd->setLogicalId($LogicalId);
					$cmd->setSubType($SubType);
					$cmd->setEqLogic_id($this->getId());
					$cmd->setName($Name);
					$cmd->setIsVisible((($IsVisible)?1:0));
					if (!empty($setTemplate_lien)) {
						$cmd->setTemplate("dashboard", $setTemplate_lien);
						$cmd->setTemplate("mobile", $setTemplate_lien);
					}						
					if (!empty($setDisplayicon)) $cmd->setDisplay('icon', '<i class="'.$setDisplayicon.'"></i>');
					if (!empty($request)) $cmd->setConfiguration('request', $request);
					if (!empty($infoName)) $cmd->setConfiguration('infoName', $infoName);
					if (!empty($infoNameArray)) $cmd->setConfiguration('infoNameArray', $infoNameArray);
					if (!empty($listValue)) $cmd->setConfiguration('listValue', $listValue);
					$cmd->setConfiguration('RunWhenRefresh', $RunWhenRefresh);				
					$cmd->setDisplay('title_disable', $title_disable);
					$cmd->setOrder($Order);
					//cas particulier
						if (($LogicalId == 'speak') || ($LogicalId == 'announcement')){
						//$cmd->setDisplay('title_placeholder', 'Options');
						$cmd->setDisplay('message_placeholder', 'Phrase à faire lire par Alexa');
						}
						if (($LogicalId == 'reminder')){
						//$cmd->setDisplay('title_placeholder', 'Options');
						$cmd->setDisplay('message_placeholder', 'Texte du rappel');
						}						
						if (($LogicalId=='volumeinfo') || ($LogicalId=='volume')) {
						$cmd->setConfiguration('minValue', '0');
						$cmd->setConfiguration('maxValue', '100');
						$cmd->setDisplay('forceReturnLineBefore', true);
						}					
				}
				$cmd->save();
			}
			catch(Exception $exc) {
				log::add('diaporama', 'error', __('Erreur pour ', __FILE__) . ' : ' . $exc->getMessage());
			}
		} else {
		$cmd = $this->getCmd(null, $LogicalId);
			if (is_object($cmd)) {
				$cmd->remove();
			}
		}
	}




	public function postSave() {
		log::add('diaporama', 'debug', '**********************postSavee '.$this->getName().'***********************************');

$nbPhotosaGenerer=$this->getConfiguration('nbPhotosaGenerer');
if ($nbPhotosaGenerer<2 || $nbPhotosaGenerer>9) $nbPhotosaGenerer=1;

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
						$cmd->setOrder($i);
						//$cmd->setDisplay('icon', '<i class="loisir-musical7"></i>');
						$cmd->setDisplay('title_disable', 1);
					}
					$cmd->save();	
	}			
	
	/*
		log::add('diaporama', 'debug', '**********************postSave2 '.$this->getName().'***********************************');
			
					// chemin des photos
				$cmd = $this->getCmd(null, 'cheminDiaporamaValide');
				if (!is_object($cmd)) {
					$cmd = new diaporamaCmd();
					$cmd->setType('info');
					$cmd->setLogicalId('cheminDiaporamaValide');
					$cmd->setSubType('binary');
					$cmd->setEqLogic_id($this->getId());
					$cmd->setName('cheminDiaporamaValide');
					$cmd->setIsVisible(1);
					$cmd->setOrder(79);
					//$cmd->setDisplay('icon', '<i class="loisir-musical7"></i>');
					//$cmd->setDisplay('title_disable', 1);
				}
				$cmd->save();	
				
					// chemin des photos
				$cmd = $this->getCmd(null, 'cheminDiaporamaComplet');
				if (!is_object($cmd)) {
					$cmd = new diaporamaCmd();
					$cmd->setType('info');
					$cmd->setLogicalId('cheminDiaporamaComplet');
					$cmd->setSubType('string');
					$cmd->setEqLogic_id($this->getId());
					$cmd->setName('cheminDiaporamaComplet');
					$cmd->setIsVisible(1);
					$cmd->setOrder(79);
					//$cmd->setDisplay('icon', '<i class="loisir-musical7"></i>');
					//$cmd->setDisplay('title_disable', 1);
				}
				$cmd->save();	
				
					// nb de photos
				$cmd = $this->getCmd(null, 'nombrePhotos');
				if (!is_object($cmd)) {
					$cmd = new diaporamaCmd();
					$cmd->setType('info');
					$cmd->setLogicalId('nombrePhotos');
					$cmd->setSubType('numeric');
					$cmd->setEqLogic_id($this->getId());
					$cmd->setName('nombrePhotos');
					$cmd->setIsVisible(1);
					$cmd->setOrder(79);
					//$cmd->setDisplay('icon', '<i class="loisir-musical7"></i>');
					//$cmd->setDisplay('title_disable', 1);
				}
				$cmd->save();					
*/
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


		event::add('jeedom::alert', array('level' => 'success', 'page' => 'diaporama', 'message' => __('Mise à jour de "'.$this->getName().'"', __FILE__),));
		$this->refresh(); 

		if ($widgetPlayer) {
				$device_playlist=str_replace("_player", "", $this->getConfiguration('serial'))."_playlist"; //Nom du device de la playlist
				// Si la case "Activer le widget Playlist" est cochée, on rend le device _playlist visible sinon on le passe invisible		
				$eq=eqLogic::byLogicalId($device_playlist,'diaporama');
						if(is_object($eq)) {
							$eq->setIsVisible((($this->getConfiguration('widgetPlayListEnable'))?1:0));
							$eq->setIsEnable((($this->getConfiguration('widgetPlayListEnable'))?1:0));
							$eq->setObject_id($this->getObject_id()); // Attribue au widget Playlist la même pièce que son Player
							$eq->save();
						}
			}



		$this->setStatus('forceUpdate', false); //dans tous les cas, on repasse forceUpdate à false
	}

	public static function dependancy_install($verbose = "false") {
		if (file_exists(jeedom::getTmpFolder('diaporama') . '/dependance')) {
			return;
		}
		log::remove('diaporama_dep');
		$_debug = 0;
		if (log::getLogLevel('diaporama') == 100 || $verbose === "true" || $verbose === true) $_debug = 1;
		log::add('diaporama', 'info', 'Installation des dépendances : ');
		$resource_path = realpath(dirname(__FILE__) . '/../../resources');
		return array('script' => $resource_path . '/nodejs.sh ' . $resource_path . ' diaporama ' . $_debug, 'log' => log::getPathToLog('diaporama_dep'));
	}

	public function preUpdate() {
	}
	
	public function preRemove () {
		if ($this->getConfiguration('devicetype') == "Player") { // Si c'est un type Player, il faut supprimer le Device Playlist
			$device_playlist=str_replace("_player", "", $this->getConfiguration('serial'))."_playlist"; //Nom du device de la playlist
		$eq=eqLogic::byLogicalId($device_playlist,'diaporama');
				if(is_object($eq)) $eq->remove();
		}
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
		log::add('diaporama', 'debug', '**********************2***********************************');
		log::add('diaporama', 'debug', '**********************3'.$_dir.'***********************************');
		foreach (self::ls($_dir) as $file) {
			if (strpos($file['filename'],'.jpg') !== false) {
				$return[] = $file['filename'];
			}
		}
		return $return;
	}	
	public static function downloadCore($_dir= '', $_fileOrigine, $_fileDestination) {
		//$pathinfo = pathinfo($_path);
		log::add('diaporama', 'debug', '_dir>>>' . $_dir);
		//$cmd = 'cd ' . $_dir . ';';
		$cmd = repo_samba::makeSambaCommand('cd ' . $_dir . ';get '.$_fileOrigine.' '.$_fileDestination, 'backup');
		log::add('diaporama', 'debug', 'Commande>>>get '.$_fileOrigine.' '.$_fileDestination);
		com_shell::execute($cmd);
		log::add('diaporama', 'debug', 'get fait');
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
	
	
	public function preSave() {
		


$diapo = array();
$this->setConfiguration('localEtat', "nok"); 
$this->setConfiguration('sambaEtat', "nok"); 
	

if ($this->getConfiguration('stockageSamba')==1) {
	$sambaShare	= config::byKey('samba::backup::share')	;
	log::add('diaporama', 'debug', 'sambaShare->>>'.$sambaShare);
	log::add('diaporama', 'debug', 'dossierSambaDiaporama->>>'.$this->getConfiguration('dossierSambaDiaporama'));
	$dos=$sambaShare.$this->getConfiguration('dossierSambaDiaporama');
	$nbPhotos=self::lsjpg_count($this->getConfiguration('dossierSambaDiaporama'));
	$this->setConfiguration('sambaEtat', "ok"); 
	$this->setConfiguration('cheminDiaporamaComplet', $dos);
}
else {
	$this->setConfiguration('stockageLocal',1); // par défaut
	$dossierLocal=$this->getConfiguration('cheminDiaporama');
	if ($dossierLocal =="") $dossierLocal="/../images/"; // par défaut
//	log::add('diaporama', 'debug', 'dossier programmé->>>'.$dossierLocal);
	$dos=dirname(__FILE__).$dossierLocal; 
	$diapo=glob($dos.'*.jpg');
	$this->setConfiguration('cheminDiaporamaComplet', realpath($dos)); 
	$this->setConfiguration('localEtat', "ok"); 
	$nbPhotos=count($diapo);
}




	//log::add('diaporama', 'debug', 'Liste des photos:'.json_encode($diapo));
	$this->setConfiguration('nombrePhotos', $nbPhotos);
if ($nbPhotos==0) {
	$this->setConfiguration('cheminDiaporamaValide', "nok");
	$this->setConfiguration('localEtat', "nok"); 
	$this->setConfiguration('sambaEtat', "nok"); 
}
else
	$this->setConfiguration('cheminDiaporamaValide', "ok");

	
	
	/*
log::add('diaporama', 'debug', "sambaFolder:".$sambaFolder);
		$return = array();
		foreach (repo_samba::ls(config::byKey('samba::backup::folder')) as $file) {
			if (strpos($file['filename'],'.tar.gz') !== false) {
				$return[] = $file['filename'];
			}
		}
	*/
log::add('diaporama', 'debug', "rep:".json_encode($return));	
	
	
	
	
	
	
	
	
		
		
	}

// https://github.com/NextDom/NextDom/wiki/Ajout-d%27un-template-a-votre-plugin	
// https://jeedom.github.io/documentation/dev/fr_FR/widget_plugin	

  public function toHtml($_version = 'dashboard') {
	$replace = $this->preToHtml($_version);
	//log::add('diaporama_widget','debug','************Début génération Widget de '.$replace['#logicalId#']);  
	$typeWidget="diaporama";	
	if ((substr($replace['#logicalId#'], -7))=="_player") $typeWidget="diaporama_player";
	if ((substr($replace['#logicalId#'], -9))=="_playlist") $typeWidget="diaporama_playlist";
    if ($typeWidget!="diaporama_playlist") return parent::toHtml($_version);
	//log::add('diaporama_widget','debug',$typeWidget.'************Début génération Widget de '.$replace['#name#']);        
	if (!is_array($replace)) {
		return $replace;
	}
	$version = jeedom::versionAlias($_version);
	if ($this->getDisplay('hideOn' . $version) == 1) {
		return '';
	}
	foreach ($this->getCmd('info') as $cmd) {
		 	//log::add('diaporama_widget','debug',$typeWidget.'dans boucle génération Widget');        
            $replace['#' . $cmd->getLogicalId() . '_history#'] = '';
            $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
            $replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
            $replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
            if ($cmd->getLogicalId() == 'encours'){
                $replace['#thumbnail#'] = $cmd->getDisplay('icon');
            }
            if ($cmd->getIsHistorized() == 1) {
                $replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
            }
        }
	$replace['#height#'] = '800';
		if ($typeWidget=="diaporama_playlist") {
			if ("#playlistName#" != "") {
				$replace['#name_display#']='#playlistName#';
			}
		}
	//log::add('diaporama_widget','debug',$typeWidget.'***************************************************************************Fin génération Widget');        
	return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, $typeWidget, 'diaporama')));
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
		if ($this->getType() == 'action') {
			$eqLogic = $this->getEqLogic();
			$this->setConfiguration('value', 'http://' . config::byKey('internalAddr') . ':3456/' . $this->getConfiguration('request') . "&device=" . $eqLogic->getConfiguration('serial'));
		}
		$actionInfo = diaporamaCmd::byEqLogicIdCmdName($this->getEqLogic_id(), $this->getName());
		if (is_object($actionInfo)) $this->setId($actionInfo->getId());
		if (($this->getType() == 'action') && ($this->getConfiguration('infoName') != '')) {//Si c'est une action et que Commande info est renseigné
			$actionInfo = diaporamaCmd::byEqLogicIdCmdName($this->getEqLogic_id(), $this->getConfiguration('infoName'));
			if (!is_object($actionInfo)) {//C'est une commande qui n'existe pas
				$actionInfo = new diaporamaCmd();
				$actionInfo->setType('info');
				$actionInfo->setSubType('string');
				$actionInfo->setConfiguration('taskid', $this->getID());
				$actionInfo->setConfiguration('taskname', $this->getName());
			}
			$actionInfo->setName($this->getConfiguration('infoName'));
			$actionInfo->setEqLogic_id($this->getEqLogic_id());
			$actionInfo->save();
			$this->setConfiguration('infoId', $actionInfo->getId());
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


	private function buildRequest($_options = array()) {
		if ($this->getType() != 'action') return $this->getConfiguration('request');
		list($command, $arguments) = explode('?', $this->getConfiguration('request'), 2);
	log::add('diaporama', 'info', '----Command:*'.$command.'* Request:'.json_encode($_options));
		switch ($command) {
			case 'volume':
				$request = $this->build_ControledeSliderSelectMessage($_options, '50');
			break;
			case 'playlist':
			case 'routine':
				$request = $this->build_ControledeSliderSelectMessage($_options, "");
			break;			
			case 'playmusictrack':
				$request = $this->build_ControledeSliderSelectMessage($_options, "53bfa26d-f24c-4b13-97a8-8c3debdf06f0");
			break;				
			case 'speak':
			case 'announcement':
			case 'push':
				$request = $this->build_ControledeSliderSelectMessage($_options);
			break;
			case 'reminder':
			case 'alarm':
				$now=date("Y-m-d H:i:s", strtotime('+3 second'));
				$request = $this->build_ControleWhenTextRecurring($now, "Ceci est un essai", $_options);
			break;			
			case 'radio':
				$request = $this->build_ControledeSliderSelectMessage($_options, 's2960');
			break;
			case 'SmarthomeCommand':
				$request = $this->build_ControledeSliderSelectMessage();
			break;			
			case 'command':
				$request = $this->build_ControledeSliderSelectMessage($_options, 'pause');
			break;
			case 'whennextalarm':
			case 'whennextmusicalalarm':
			case 'musicalalarmmusicentity':
			case 'whennextreminderlabel':
			case 'whennextreminder':
				$request = $this->build_ControlePosition($_options);
			break;			
			case 'updateallalarms':
				$request = $this->build_ControleRien($_options);
			break;				
			case 'deleteallalarms':
				$request = $this->buildDeleteAllAlarmsRequest($_options);
			break;
			case 'deleteReminder':
				$request = $this->buildDeleteReminderRequest($_options);
			break;			
			case 'restart':
				$request = $this->buildRestartRequest($_options);
			break;				
			default:
				$request = '';
			break;
		}
		//log::add('diaporama_debug', 'debug', '----RequestFinale:'.$request);
		$request = scenarioExpression::setTags($request);
		if (trim($request) == '') throw new Exception(__('Commande inconnue ou requête vide : ', __FILE__) . print_r($this, true));
		$device=str_replace("_player", "", $this->getEqLogic()->getConfiguration('serial'));
		return 'http://' . config::byKey('internalAddr') . ':3456/' . $request . '&device=' . $device;
	}

	
	private function build_ControledeSliderSelectMessage($_options = array(), $default = "Ceci est un message de test") {
		$cmd=$this->getEqLogic()->getCmd(null, 'volumeinfo');
		if (is_object($cmd))
			$lastvolume=$cmd->execCmd();
		
		$request = $this->getConfiguration('request');
		//log::add('diaporama_node', 'info', '---->Request2:'.$request);
		//log::add('diaporama_node', 'debug', '---->getName:'.$this->getEqLogic()->getCmd(null, 'volumeinfo')->execCmd());
		if ((isset($_options['slider'])) && ($_options['slider'] == "")) $_options['slider'] = $default;
		if ((isset($_options['select'])) && ($_options['select'] == "")) $_options['select'] = $default;
		if ((isset($_options['message'])) && ($_options['message'] == "")) $_options['message'] = $default;
		// Si on est sur une commande qui utilise volume, on va remettre après execution le volume courant
		if (strstr($request, '&volume=')) $request = $request.'&lastvolume='.$lastvolume;
		$request = str_replace(array('#slider#', '#select#', '#message#', '#volume#'), 
		array($_options['slider'], $_options['select'], urlencode(self::decodeTexteAleatoire($_options['message'])), $_options['volume']), $request);
		//log::add('diaporama_node', 'info', '---->RequestFinale:'.$request);
		return $request;
	}	

	//private function trouveVolumeDevice() {
	//	$logical_id = $this->getEqLogic()->getCmd(null, 'volumeinfo')->getValue();
	//	$diaporama=diaporama::byLogicalId($logical_id, 'diaporama');getValue
	//}


	public static function decodeTexteAleatoire($_text) {
		$return = $_text;
		if (strpos($_text, '|') !== false && strpos($_text, '[') !== false && strpos($_text, ']') !== false) {
			$replies = interactDef::generateTextVariant($_text);
			$random = rand(0, count($replies) - 1);
			$return = $replies[$random];
		}
		preg_match_all('/{\((.*?)\) \?(.*?):(.*?)}/', $return, $matches, PREG_SET_ORDER, 0);
		$replace = array();
		if (is_array($matches) && count($matches) > 0) {
			foreach ($matches as $match) {
				if (count($match) != 4) {
					continue;
				}
				$replace[$match[0]] = (jeedom::evaluateExpression($match[1])) ? trim($match[2]) : trim($match[3]);
			}
		}
		return str_replace(array_keys($replace), $replace, $return);
	}




	private function build_ControleWhenTextRecurring($defaultWhen, $defaultText, $_options = array()) {
		$request = $this->getConfiguration('request');
		log::add('diaporama', 'debug', '----build_ControledeSliderSelectMessage RequestFinale:'.$request);
		log::add('diaporama', 'debug', '----build_ControledeSliderSelectMessage _optionsAVANT:'.json_encode($_options));
		if ((!isset($_options['sound'])) && (!isset($_options['message'])) && (!isset($_options['when']))) {
			if (isset($_options['select'])) { // On est dans le cas d'un son d'alarme envoyé depuis le widget
				$_options['sound']=urlencode($_options['select']);
				$_options['select']="";
			}
		}
		if ($_options['when'] == "") $_options['when'] = $defaultWhen;		
		if ($_options['message'] == "") $_options['message'] = $defaultText;	
		if ($_options['sound'] == "") $_options['sound'] = 'system_alerts_melodic_01';	
		$request = str_replace(array('#when#', '#message#', '#recurring#', '#sound#'), array(urlencode($_options['when']), urlencode($_options['message']), urlencode($_options['select']), $_options['sound']), $request);
		return $request;
	}
	
	private function build_ControlePosition($_options = array()) {
		$request = $this->getConfiguration('request');
		$request = str_replace('#position#', urlencode($_options['position']), $request);
		return $request;
	}
	
	private function build_ControleRien($_options = array()) {
		return $this->getConfiguration('request')."?truc=vide";
	}
	
	private function buildDeleteAllAlarmsRequest($_options = array()) {
		$request = $this->getConfiguration('request');
		if ($_options['type'] == "") $_options['type'] = "alarm";
		if ($_options['status'] == "") $_options['status'] = "ON";
		return str_replace(array('#type#', '#status#'), array($_options['type'], $_options['status']), $request);
	}
	
	private function builddeleteReminderRequest($_options = array()) {
		$request = $this->getConfiguration('request');
		if ($_options['id'] == "") $_options['id'] = "coucou";
		if ($_options['status'] == "") $_options['status'] = "ON";
		return str_replace(array('#id#', '#status#'), array($_options['id'], $_options['status']), $request);
	}	
		
	private function buildRestartRequest($_options = array()) {
		log::add('diaporama_debug', 'debug', '------buildRestartRequest---UTILISE QUAND ???--A simplifier--------------------------------------');
		$request = $this->getConfiguration('request')."?truc=vide";
		return str_replace('#volume#', $_options['slider'], $request);
	}
	
	public function getWidgetTemplateCode($_version = 'dashboard', $_noCustom = false) {
		if ($_version != 'scenario') return parent::getWidgetTemplateCode($_version, $_noCustom);
		list($command, $arguments) = explode('?', $this->getConfiguration('request'), 2);
		if (($command == 'speak') || ($command == 'announcement'))
			return getTemplate('core', 'scenario', 'cmd.speak.volume', 'diaporama');
		if ($command == 'reminder') 
			return getTemplate('core', 'scenario', 'cmd.reminder', 'diaporama');
		if ($command == 'deleteallalarms') 
			return getTemplate('core', 'scenario', 'cmd.deleteallalarms', 'diaporama');
		if ($command == 'command' && strpos($arguments, '#select#')) 
			return getTemplate('core', 'scenario', 'cmd.command', 'diaporama');
		if ($command == 'alarm') 
			return getTemplate('core', 'scenario', 'cmd.alarm', 'diaporama');
		return parent::getWidgetTemplateCode($_version, $_noCustom);
	}
}
/*
	public static function getKnownDeviceType() {
		// récupéré de https://github.com/Apollon77/ioBroker.alexa2/blob/master/main.js
		$knownDeviceType = array(
			('A10A33FOX2NUBK') => array( (TypeEcho) => 'Echo Spot', (commandSupport) => 'true', (icon) => 'spot'),
			('A12GXV8XMS007S') => array( (TypeEcho) => 'FireTV', (commandSupport) => 'false', (icon) => 'firetv'), 
			('A15ERDAKK5HQQG') => array( (TypeEcho) => 'Sonos', (commandSupport) => 'false', (icon) => 'sonos'),
			('A17LGWINFBUTZZ') => array( (TypeEcho) => 'Anker Roav Viva Alexa', (commandSupport) => 'false', (icon) => 'other'),
			('A18O6U1UQFJ0XK') => array( (TypeEcho) => 'Echo Plus 2.Gen', (commandSupport) => 'true', (icon) => 'echo_plus2'), 
			('A1DL2DVDQVK3Q') => array( (TypeEcho) => 'Apps', (commandSupport) => 'false', (icon) => 'other'), 
			('A1H0CMF1XM0ZP4') => array( (TypeEcho) => 'Echo Dot/Bose', (commandSupport) => 'false', (icon) => 'other'), 
			('A1J16TEDOYCZTN') => array( (TypeEcho) => 'Fire tab', (commandSupport) => 'true', (icon) => 'firetab'),
			('A1NL4BVLQ4L3N3') => array( (TypeEcho) => 'Echo Show', (commandSupport) => 'true', (icon) => 'echo_show'), 
			('A1RTAM01W29CUP') => array( (TypeEcho) => 'Windows App', (commandSupport) => 'false', (icon) => 'other'), 
			('A1X7HJX9QL16M5') => array( (TypeEcho) => 'Bespoken.io', (commandSupport) => 'false', (icon) => 'other'),
			('A21Z3CGI8UIP0F') => array( (TypeEcho) => 'Apps', (commandSupport) => 'false', (icon) => 'other'), 
			('A2825NDLA7WDZV') => array( (TypeEcho) => 'Apps', (commandSupport) => 'false', (icon) => 'other'), 
			('A2E0SNTXJVT7WK') => array( (TypeEcho) => 'Fire TV V1', (commandSupport) => 'false', (icon) => 'firetv'),
			('A2GFL5ZMWNE0PX') => array( (TypeEcho) => 'Fire TV', (commandSupport) => 'true', (icon) => 'firetv'), 
			('A2IVLV5VM2W81') => array( (TypeEcho) => 'Apps', (commandSupport) => 'false', (icon) => 'other'), 
			('A2L8KG0CT86ADW') => array( (TypeEcho) => 'RaspPi', (commandSupport) => 'false', (icon) => 'other'), 
			('A2LWARUGJLBYEW') => array( (TypeEcho) => 'Fire TV Stick V2', (commandSupport) => 'false', (icon) => 'firetv'), 
			('A2M35JJZWCQOMZ') => array( (TypeEcho) => 'Echo Plus', (commandSupport) => 'true', (icon) => 'echo'), 
			('A2M4YX06LWP8WI') => array( (TypeEcho) => 'Fire Tab', (commandSupport) => 'false', (icon) => 'firetab'), 
			('A2OSP3UA4VC85F') => array( (TypeEcho) => 'Sonos', (commandSupport) => 'true', (icon) => 'sonos'), 
			('A2T0P32DY3F7VB') => array( (TypeEcho) => 'echosim.io', (commandSupport) => 'false', (icon) => 'other'),
			('A2TF17PFR55MTB') => array( (TypeEcho) => 'Apps', (commandSupport) => 'false', (icon) => 'other'), 
			('A32DOYMUN6DTXA') => array( (TypeEcho) => 'Echo Dot 3.Gen', (commandSupport) => 'true', (icon) => 'echo_dot3'),
			('A37SHHQ3NUL7B5') => array( (TypeEcho) => 'Bose Homespeaker', (commandSupport) => 'false', (icon) => 'other'), 
			('A38BPK7OW001EX') => array( (TypeEcho) => 'Raspberry Alexa', (commandSupport) => 'false', (icon) => 'raspi'), 
			('A38EHHIB10L47V') => array( (TypeEcho) => 'Echo Dot', (commandSupport) => 'true', (icon) => 'echo_dot'), 
			('A3C9PE6TNYLTCH') => array( (TypeEcho) => 'Multiroom', (commandSupport) => 'true', (icon) => 'multiroom'), 
			('A3H674413M2EKB') => array( (TypeEcho) => 'echosim.io', (commandSupport) => 'false', (icon) => 'other'),
			('A3HF4YRA2L7XGC') => array( (TypeEcho) => 'Fire TV Cube', (commandSupport) => 'true', (icon) => 'other'), 
			('A3NPD82ABCPIDP') => array( (TypeEcho) => 'Sonos Beam', (commandSupport) => 'true', (icon) => 'sonos'), 
			('A3R9S4ZZECZ6YL') => array( (TypeEcho) => 'Fire Tab HD 10', (commandSupport) => 'true', (icon) => 'firetab'), 
			('A3S5BH2HU6VAYF') => array( (TypeEcho) => 'Echo Dot 2.Gen', (commandSupport) => 'true', (icon) => 'echo_dot'), 
			('A3SSG6GR8UU7SN') => array( (TypeEcho) => 'Echo Sub', (commandSupport) => 'true', (icon) => 'echo_sub'), 
			('A7WXQPH584YP') => array( (TypeEcho) => 'Echo 2.Gen', (commandSupport) => 'true', (icon) => 'echo2'), 
			('AB72C64C86AW2') => array( (TypeEcho) => 'Echo', (commandSupport) => 'true', (icon) => 'echo'), 
			('ADVBD696BHNV5') => array( (TypeEcho) => 'Fire TV Stick V1', (commandSupport) => 'false', (icon) => 'firetv'), 
			('AILBSA2LNTOYL') => array( (TypeEcho) => 'reverb App', (commandSupport) => 'false', (icon) => 'reverb'),
			('AVE5HX13UR5NO') => array( (TypeEcho) => 'Logitech Zero Touch', (commandSupport) => 'false', (icon) => 'other'), 
			('AWZZ5CVHX2CD') => array( (TypeEcho) => 'Echo Show 2.Gen', (commandSupport) => 'true', (icon) => 'echo_show2')
		);
		return $knownDeviceType;
	}
*/
