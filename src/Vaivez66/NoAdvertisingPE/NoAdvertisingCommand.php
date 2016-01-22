<?php

namespace Vaivez66\NoAdvertisingPE;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\utils\TextFormat as TF;

class NoAdvertisingCommand extends PluginBase implements CommandExecutor{

    public function __construct(NoAdvertising $plugin){
        $this->plugin = $plugin;
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        switch(strtolower($cmd->getName())){
            case "na":
                if($sender->hasPermission("no.advertising.pe")) {
                    if (isset($args[0])) {
                        switch ($args[0]) {
                            case "add":
                                if(isset($args[1])){
                                    return $this->plugin->addDomain($sender, $args[1]);
                                }
                                else{
                                    return false;
                                }
                                break;
                            case "remove":
                                if(isset($args[1])){
                                    return $this->plugin->removeDomain($sender, $args[1]);
                                }
                                else{
                                    return false;
                                }
                                break;
                            case "list":
                                return $this->plugin->listDomain($sender);
                                break;
                        }
                    }
                    else{
                        return false;
                    }
                }
                else{
                    $sender->sendMessage(TF::RED . "You do not have permission");
                    return true;
                }
                break;
        }
    }

}