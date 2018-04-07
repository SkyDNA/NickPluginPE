<?php

namespace StuckDexter;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerKickEvent;


class Nick extends PluginBase implements Listener{

public $prefix = "§7[§5Nick§7] ";
public $nicked = [];
public $purePerms = null;

public function onEnable(){
@mkdir($this->getDataFolder());
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
if(empty($config->get("Nicks"))){
$config->set("Nicks", array("Peter", "Gamer123", "StuckDexter", "zSkyDna", "zPeloTuti", "Gqme182", "yueLoli", "BlockofFire", "SkyZoneMC", "FeuerEule82636", "DerManger6263", "AugenRoller93625", "GruppenFace7272", "SindBeiBabo", "DerDòner6252"));
$config->set("NickGroup", "Nicked");
$config->save();
}
$this->purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
$this->getLogger()->info($this->prefix."§awurde aktiviert");
}
public function onQuit(PlayerQuitEvent $event){
$player = $event->getPlayer();
if(in_array($player->getName(), $this->nicked)){
$player->setDisplayName($sender->getName());
$this->purePerms->getUserDataMgr()->setGroup($player, $this->nicked[$player->getName()], null);
$fskin = $player->getSkinData();
$player->setSkin($fskin, "Standard_Custom");
$player->despawnFromAll();
$player->spawnToAll();
unset($this->nicked[array_search($player->getName(), $this->nicked)]);
}
}
public function onKick(PlayerKickEvent $event){
$player = $event->getPlayer();
if(in_array($player->getName(), $this->nicked)){
$player->setDisplayName($sender->getName());
$this->purePerms->getUserDataMgr()->setGroup($player, $this->nicked[$player->getName()], null);
$fskin = $player->getSkinData();
$player->setSkin($fskin, "Standard_Custom");
$player->despawnFromAll();
$player->spawnToAll();
unset($this->nicked[array_search($player->getName(), $this->nicked)]);
}
}
public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
if(strtolower($cmd->getName()) == "nick"){
if($sender instanceof Player){
if($sender->hasPermission("nick.use")){
if(!in_array($sender->getName(), $this->nicked)){
$r = rand(0, count($config->get("Nicks"))-1);
$nick = $config->get("Nicks")[$r];
$this->nicked[] = $sender->getName();
$this->nicked[$sender->getName()] = $this->purePerms->getUserDataMgr()->getGroup($sender);
$this->purePerms->getUserDataMgr()->setGroup($sender, $this->purePerms->getGroup($config->get("NickGroup")), null);
$sender->setDisplayName($nick);
$fskin = $this->getServer()->getOnlinePlayers()[array_rand($this->getServer()->getOnlinePlayers())]->getSkinData();
$sender->setSkin($fskin, "Standard_Custom");
$sender->despawnFromAll();
$sender->spawnToAll();
$sender->sendMessage($this->prefix."§aDu wurdest genickt!");
}else{
$sender->setDisplayName($sender->getName());
$this->purePerms->getUserDataMgr()->setGroup($sender, $this->nicked[$sender->getName()], null);
$fskin = $sender->getSkinData();
$sender->setSkin($fskin, "Standard_Custom");
$sender->despawnFromAll();
$sender->spawnToAll();
unset($this->nicked[array_search($sender->getName(), $this->nicked)]);
$sender->sendMessage($this->prefix."§aDu wurdest entnickt");
}
}else{
$sender->sendMessage("§cDu darfst den Command nicht benutzen");
}
}else{
$this->getLogger()->info($this->prefix."§cDas kann nur ein Spieler!");
}
}
}
}

