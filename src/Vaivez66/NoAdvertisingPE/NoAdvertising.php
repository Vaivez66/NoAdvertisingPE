<?php

namespace Vaivez66\NoAdvertisingPE;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

class NoAdvertising extends PluginBase{

    public $cfg;
    private $format;

    public function onEnable(){
	$this->saveDefaultConfig();
	$this->format = new NoAdvertisingFormat($this);
	$this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML, array());
	$this->getServer()->getLogger()->info(TF::GREEN . "NoAdvertisingPE is ready!");
	$this->getServer()->getPluginManager()->registerEvents(new NoAdvertisingListener($this), $this);
	$this->getCommand("na")->setExecutor(new NoAdvertisingCommand($this));
    }

    /**
     * @return array
     */

    public function getDomain(){
	$domain = (array) $this->cfg->get("domain");
	return $domain;
    }

    /**
     * @return array
     */

    public function getAllowedDomain(){
	$allowed = (array) $this->cfg->get("allowed.domain");
	return $allowed;
    }

    /**
     * @return mixed
     */

    public function getType(){
	return $this->cfg->get("type");
    }

    /**
     * @return mixed
     */

    public function getMsg(){
	return $this->cfg->get("message");
    }

    /**
     * @return bool
     */

    public function detectSign(){
	return $this->cfg->get('detect.sign') === true;
    }

    /**
     * @return array
     */

    public function getSignLines(){
	return (array) $this->cfg->get('lines');
    }

    /**
     * @return array
     */

    public function getBlockedCmd(){
	return (array) $this->cfg->get('blocked.cmd');
    }

    /**
     * @param $p
     * @param $name
     * @return bool
     */

    public function addDomain($p, $name){
	$domain = $this->getDomain();
	if(in_array($name, $domain)){
	    $p->sendMessage(TF::RED . "That domain already exist!");
	    return false;
	}
	$domain[] = $name;
	$this->cfg->set("domain", $domain);
	$this->cfg->save();
	$p->sendMessage(TF::GREEN . "Successfully added " . $name . " into config");
	return true;
    }
    
    /**
     * @param $p
     * @param $name
     * @return bool
     */

    public function removeDomain($p, $name){
    	$domain = $this->getDomain();
    	$key = array_search($name, $domain);
    	if($key === false){
    	    $p->sendMessage(TF::RED . "That domain does not exist!");
    	    return false;
    	}
    	unset($domain[$key]);
    	$this->cfg->set("domain", array_values($domain));
    	$this->cfg->save();
    	$p->sendMessage(TF::GREEN . "Successfully removed " . $name . " from config");
    	return true;
    }

    /**
     * @param $p
     * @return bool
     */

    public function listDomain($p){
	$domain = implode("\n" . TF::YELLOW . "- ", $this->getDomain());
	$p->sendMessage(TF::YELLOW . "Available domain:");
	$p->sendMessage(TF::YELLOW . "- " . $domain);
	return true;
    }

    /**
     * @param $m
     */

    public function broadcastMsg($m){
	foreach($this->getServer()->getOnlinePlayers() as $p){
	    $p->sendMessage($m);
	}
    }

    /**
     * @return mixed
     */

    public function getFormat(){
	return $this->format;
    }
	
    public function onDisable(){
	$this->getServer()->getLogger()->info(TF::RED . "NoAdvertisingPE was disabled!");
    }

}
