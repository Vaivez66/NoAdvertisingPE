<?php

namespace Vaivez66\NoAdvertisingPE;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class NoAdvertisingListener extends PluginBase implements Listener{

    public function __construct(NoAdvertising $plugin){
        $this->plugin = $plugin;
    }

    public function onChat(PlayerChatEvent $event){
        $p = $event->getPlayer();
        $msg = $event->getMessage();
        $domain = $this->plugin->getDomain();
        $type = $this->plugin->getType();
        $m = $this->plugin->getMsg();
        $m = str_replace("{player}", $p->getName(), $m);
        $m = $this->plugin->getFormat()->translate($m);
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

}
