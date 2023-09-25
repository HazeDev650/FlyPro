<?php

namespace Terpz710\FlyPlus;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Terpz710\FlyPlus\Commands\Fly;

class Main extends PluginBase {

    /** @var Config */
    private $config;

    public function onEnable(): void {
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $this->getServer()->getCommandMap()->register("Fly", new FlyCommand($this, $this->config));
    }
}