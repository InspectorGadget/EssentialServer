<?php

namespace EssentialServer;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\utils\Config;

/**
	* All rights reserved RTGNetworkkk
	* GitHub: https://github.com/RTGNetworkkk
	* Website: https://rtgnetwork.tk
	* This repo is lisenced!
*/

class Main extends PluginBase implements Listener {

	public function onEnable() {
	
		//if(!is_dir($this->getDataFolder())) {
			//@mkdir($this->getDataFolder());
			//@mkdir($this->getDataFolder() . "players/");
		//}
		
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->warning("
		* Starting EssentialServer!
		* Version: 1.0.2 Alpha
		* Author: InspectorGadget
		* GitHub: <github.com/RTGNetworkkk>
		");
		$this->getConfig()->getAll();
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		switch (strtolower($cmd->getName())) {
		
		case "ops":
			if($sender->hasPermission("essentialops")) {
			
				// OP find open
				foreach($this->getServer()->getOnlinePlayers() as $p) {
					if($p->isOp()) {
						$n = $p->getName();
						$sender->sendMessage(TF::GREEN . "OP: \n$n");
					}
					else {
						$sender->sendMessage("No one is OP!");
					}
				}
				// OP find close
			}
		 	else {
				$noperm = $this->getConfig()->get("no-perm");
				$sender->sendMessage($noperm);
			}
			return true;
		break;
		
		case "mypos":
		if($sender instanceof Player) {
			if($sender->hasPermission("essentialpos")) {
				$x = $sender->getX();
				$y = $sender->getY();
				$z = $sender->getZ();
				
				$sender->sendMessage("Your Pos:\nX: $x \nY: $y \nZ: $z");
			}
			else {
				$noperm = $this->getConfig()->get("no-perm");
				$sender->sendMessage($noperm);
			}
			// Ended Pos cmd
		}
		else {
			$sender->sendMessage("only in game!");
		}
			return true;
		break;
		
		case "ipfind":
		if($sender instanceof Player) {
			if($sender->hasPermission("essentialipfind")) {
				if(isset($args[0])) {
					$player = $args[0];
					$pl = $this->getServer()->getPlayer($player);
					if($player === null) {
						$sender->sendMessage("$player not available!");
					}
					else {
					
						//$config = new Config($this->getDataFolder() . "players/" . strtolower($pl->getName() . ".txt", Config::ENUM));
						$ip = $this->getServer()->getPlayer($player)->getAddress();
						//$p = $this->getServer()->getPlayer($player);
						//$array = $config->get("ips", []);
						//$array[] = $p->getAddress();
						//$config->set("ips", $array);
						
						$sender->sendMessage("-- IP for $player --");
						$sender->sendMessage("-- Current IP --");
						$sender->sendMessage(TF::RED . " > $ip");
					}
				}
				else {
					$sender->sendMessage("Error: Invalid Args!");
				}
			}
		}
		else {
			$sender->sendMessage("Only in game!");
		}
			return true;
		break;
		
		case "clearinv":
		if($sender->hasPermission("essentialclearinv")) {
			if(isset($args[0])) {
				$name = $args[0];
				$player = $this->getServer()->getPlayer($name);
				if($player->isOnline()) {
					$player->getInventory()->clearAll();
					$sender->sendMessage("You have cleared $name's Inventory!");
				}
				else {
					$sender->sendMessage("$name is not online!");
				}
			}
			else {
				$sender->sendMessage("/clearinv [name]");
			}
		}
		else {
			$sender->sendMessage(TF::RED . "You have no permission!");
		}
			return true;
		break;
		
		case "locfind":
		if($sender->hasPermission("essentiallocfinder")) {
			if(isset($args[0])) {
				$name = $args[0];
				$player = $this->getServer()->getPlayer($name);
				if($player->isOnline()) {
					$x = $player->getX();
					$y = $player->getY();
					$z = $player->getZ();
					$level = $player->getLevel()->getFolderName();
					$sender->sendMessage("$name's Pos:\nX: $x \nY: $y \nZ: $z");
					$sender->sendMessage("$name's Level:\n - $level");
				}
				else {
					$sender->sendMessage("$name is not Online!");
				}
			}
			else {
				$sender->sendMessage("/findloc <name>");
			}
		}
		else {
			$sender->sendMessage("You have no permission to use this command!");
		}
			return true;
		break;
		
		
		case "nick":
			if($sender->hasPermission("essentialnick")) {
				if(isset($args[0])) {
					switch(strtolower($args[0])) {
						case "off"
							if(isset($args[1])) {
								switch(strtolower($args[1])) {
									$args[1] = $player;
									$p = $this->getServer()->getPlayer($args[1]);
									$n = $p->getName();
									
									if($p instanceof Player) {
										
										$p->setDisplayName($n);
										$p->setNameTag($n);
										$p->sendMessage("Your nick has been reset!");
										
									}
									else {
										$sender->sendMessage("$args[1] is not available!");
									}
								}
							}
							else {
								$sender->setDisplayName($sender->getName());
								$sender->setNameTag($sender->getName());
								$sender->sendMessage("You have turned off your Nick!");
							}
							return true;
						break;
						
						case "set":
							if(isset($args[0])) {
								$nick = $args[0];
								
								if($sender instanceof Player) {
								
									$sender->setDisplayName("*". $nick);
									$sender->setNameTag("*". $nick);
									$sender->sendMessage("Your name has been changed to $nick");
								
								}
								else {
									$sender->sendMessage("You are not a Player!");
								}
								
								if(isset($args[1])) {
									$p = $args[1];
									$pl = $this->getServer()->getPlayer($p);
									
									if($pl instanceof Player) {
									
										$pl->setNameTag("*". $nick);
										$pl->setDisplayName("*". $nick);
										$pl->sendMessage("You nick has been changed to $nick");
										$sender->sendMessage("You have changed $p's nick to $nick!");
									
									}
									else {
										$sender->sendMessage("$p isnt a Player!");
									}
								}
							}
							return true;
						break;
					}
				}
			}
			else {
				$sender->sendMessage(TF::RED . "You have no permission to use this command!");
			}
			return true;
		break;
		// FOR Switch!
		}
		return true;
		// Close switch
	}
	
	public function onJoin(PlayerJoinEvent $e) {
		$p = $e->getPlayer();
		$n = $p->getName();
		$nick = $p->getDisplayName();
		$nic = $p->getNameTag();
		
		if($n !== $nick && $n !== $nick) {
			$this->getLogger()->info("$n joined with a Nick! Resetting!");
			$p->setDisplayName($n);
			$p->setNameTag($n);
		}
	}
	
	public function onDisable() {
		$this->getConfig()->save();
	}
}
