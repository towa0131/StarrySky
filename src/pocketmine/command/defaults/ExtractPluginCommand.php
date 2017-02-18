<?php
/*
 * DevTools plugin for PocketMine-MP
 * Copyright (C) 2014 PocketMine Team <https://github.com/PocketMine/DevTools>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/
namespace pocketmine\command\defaults;
use pocketmine\command\CommandSender;
<<<<<<< HEAD
use pocketmine\event\TranslationContainer;
use pocketmine\plugin\PharPluginLoader;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\MainLogger;

=======
use pocketmine\plugin\PharPluginLoader;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
>>>>>>> 8e282abf28fe92715de3f5aee3f9feadc8fcada1
class ExtractPluginCommand extends VanillaCommand{
    public function __construct($name){
        parent::__construct(
            $name,
            "%pocketmine.command.extract.plugin.description",
            "%pocketmine.command.extract.plugin.usage",
            ["ep"]
        );
        $this->setPermission("pocketmine.command.extractplugin");
    }
    public function execute(CommandSender $sender, $commandLabel, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) === 0){
            //$this->logger->info($this->getLanguage()->translateString("pocketmine.upnp.port.remove"));
            $sender->sendMessage(TextFormat::RED . "Usage: ".$this->usageMessage);
            return true;
        }
        $pluginName = trim(implode(" ", $args));
        if($pluginName === "" or !(($plugin = Server::getInstance()->getPluginManager()->getPlugin($pluginName)) instanceof Plugin)){
<<<<<<< HEAD
            $sender->sendMessage("%pocketmine.command.extract.plugin.invalid.name");
=======
            $sender->sendMessage(TextFormat::RED . "%pocketmine.command.extract.plugin.invalid.name.");
>>>>>>> 8e282abf28fe92715de3f5aee3f9feadc8fcada1
            return true;
        }
        $description = $plugin->getDescription();
        if(!($plugin->getPluginLoader() instanceof PharPluginLoader)){
<<<<<<< HEAD
            $sender->sendMessage(new TranslationContainer("pocketmine.command.extract.plugin.notphar",[$description->getName()]));
=======
            $sender->sendMessage(TextFormat::RED . "%pocketmine.command.extract.plugin.notphar",[$description->getName()]);
>>>>>>> 8e282abf28fe92715de3f5aee3f9feadc8fcada1
            return true;
        }
        $folderPath = Server::getInstance()->getPluginPath().DIRECTORY_SEPARATOR . "DevTools" . DIRECTORY_SEPARATOR . $description->getName()."_v".$description->getVersion()."/";
        if(file_exists($folderPath)){
            $sender->sendMessage("%pocketmine.command.extract.plugin.already");
        }else{
            @mkdir($folderPath);
        }
        $reflection = new \ReflectionClass("pocketmine\\plugin\\PluginBase");
        $file = $reflection->getProperty("file");
        $file->setAccessible(true);
        $pharPath = str_replace("\\", "/", rtrim($file->getValue($plugin), "\\/"));
        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($pharPath)) as $fInfo){
            $path = $fInfo->getPathname();
            @mkdir(dirname($folderPath . str_replace($pharPath, "", $path)), 0755, true);
            file_put_contents($folderPath . str_replace($pharPath, "", $path), file_get_contents($path));
        }
        //$sender->sendMessage("[DevTools] Source plugin ".$description->getName() ." v".$description->getVersion()." has been created on ".$folderPath);
<<<<<<< HEAD
	$extractmsg = new TranslationContainer("pocketmine.command.extract.plugin.outfile",[$description->getName(), $description->getVersion(), $folderPath]);
        $sender->sendMessage($extractmsg);
=======
        $sender->sendMessage("%pocketmine.command.extract.plugin.outfile",[$description->getName(),$description->getVersion(),$folderPath]);
>>>>>>> 8e282abf28fe92715de3f5aee3f9feadc8fcada1
        return true;
    }
}