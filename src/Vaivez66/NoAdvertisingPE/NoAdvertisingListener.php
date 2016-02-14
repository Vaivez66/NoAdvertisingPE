<?php

namespace Vaivez66\NoAdvertisingPE;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\utils\TextFormat as TF;

class NoAdvertisingListener extends PluginBase implements Listener{

    public function __construct(NoAdvertising $plugin){
        $this->plugin = $plugin;
    }

    public function onChat(PlayerChatEvent $event){
        $p = $event->getPlayer();
        $msg = $event->getMessage();
        $domain = $this->plugin->getDomain();
        $allowed = $this->plugin->getAllowedDomain();
        $type = $this->plugin->getType();
        $m = $this->plugin->getMsg();
        $m = str_replace("{player}", $p->getName(), $m);
        $m = $this->plugin->getFormat()->translate($m);
        if($p->hasPermission('no.advertising.pe.bypass')){
            return;
        }
        foreach($allowed as $a){
            if((preg_match("/^{$a}/i", $msg)) || (stripos($msg, $a) == true)){
                return;
            }
        }
        foreach($domain as $d){
            if((preg_match("/^{$d}/i", $msg)) || (stripos($msg, $d) == true)){
                switch($type){
                    case "broadcast":
                        $event->setCancelled(true);
                        $this->plugin->broadcastMsg($m);
                        break;
                    case "block":
                        $event->setCancelled(true);
                        $p->sendMessage($m);
                        break;
                    case "kick":
                        $event->setCancelled(true);
                        $p->kick($m, true);
                }
            }
        }
    }

    public function onSign(SignChangeEvent $event){
        if($this->plugin->detectSign()){
            $lines = $event->getLines();
            $p = $event->getPlayer();
            $sign = $this->plugin->getSignLines();
            if($p->hasPermission('no.advertising.pe.bypass')){
                return;
            }
            foreach($lines as $line){
                foreach($this->plugin->getAllowedDomain() as $a){
                    if((preg_match("/^{$a}/i", $line)) || (stripos($line, $a) == true)){
                        return;
                    }
                }
                foreach($this->plugin->getDomain() as $d){
                    if((preg_match("/^{$d}/i", $line)) || (stripos($line, $d) == true)) {
                        for ($i = 0; $i <= 3; $i++) {
                            $event->setLine($i, $sign[$i]);
                        }
                        $p->sendMessage(TF::RED . 'Do not try to advertising, ' . $p->getName());
                    }
                }
            }
        }
    }

}
