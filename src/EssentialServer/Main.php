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

class Main extends PluginBase implements Listener {

	public function onEnable() {
	
		if(!is_dir($this->getDataFolder())) {
			@mkdir($this->getDataFolder());
		}
		
		
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->warning("
		* Starting EssentialServer!
		* Version: 1.0.1 Alpha
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
				foreach($this->getServer()->getOps()->getAll() as $p) {
					if($sender->isOnline()) {
						$n = $this->getServer()->getPlayer($p)->getName();
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
		// over! closed command
		
		case "hub":
			if($sender->hasPermission("essentialhub")) {
				if($sender instanceof Player) {
					$sender->teleport($this->getServer()->getDefaultLevel()->getSpawnLocation());
					$sender->sendMessage("You have been sent to dafault world Spawn!");
				}
				else {
					$sender->sendMessage("You're not in-game or a player");
				}
			}
			else {
				$sender->sendMessage(TF::RED . "You have no permission to use this command!");
			}
			return true;
		break;
		
		case "lastpos":
			if($sender->hasPermission("essentiallastpos")) {
				
				if($sender instanceof Player) {
				
					$pos = $sender->getLastPosition();
					$sender->teleport($pos);
					$sender->removeLastPosition();
					$sender->sendMessage("You have teleported to your last pos!");
				}
				else {
					$sender->sendMessage("You are not a Player!");
				}
			}
			else {
				$sender->sendMessage(TF::RED . "You have no permission to use this command!");
			}
			return true;
		break;
		
		case "afk":
		if($sender->hasPermission("essentialafk")) {
			if(isset($args[0])) {
				if($args[0] === "on") {
					$sender->hidePlayer($sender->getName());
					$sender->sendMessage("You are now AFK!");
				}
				
				if($args[0] === "off") {
					$sender->showPlayer($sender->getName());
					$sender->sendMessage("You are no longer AFK!");
				}
			}
			else {
				$sender->sendMessage("/afk <on | off>");
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
	
	public function onDisable() {
		$this->getConfig()->save();
	}
}
