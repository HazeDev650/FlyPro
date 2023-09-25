<?php

namespace Terpz710\FlyPlus\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Terpz710\FlyPlus\Main;
use pocketmine\utils\Config;

class FlyCommand extends Command {

    private $plugin;
    private $config;

    public function __construct(Main $plugin, Config $config) {
        parent::__construct("fly", "Toggle flying");
        $this->plugin = $plugin;
        $this->config = $config;
        $this->setPermission("flyplugin.fly");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {

        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used by players.");
            return true;
        }

        if (!$sender->hasPermission("flyplugin.fly")) {
            $sender->sendMessage("You don't have permission to use this command.");
            return true;
        }

        if (empty($args)) {
            if ($sender->getAllowFlight()) {
                $sender->setAllowFlight(false);
                $sender->sendMessage($this->config->get("fly_message_off", "You have landed."));
                $this->sendFlyTitle($sender, "fly_title_off", "fly_subtitle_off");
            } else {
                $sender->setAllowFlight(true);
                $sender->sendMessage($this->config->get("fly_message_on", "You are now flying!"));
                $this->sendFlyTitle($sender, "fly_title_on", "fly_subtitle_on");
            }
        } elseif (count($args) === 1) {
            $subcommand = strtolower($args[0]);
            if ($subcommand === "on") {
                $sender->setAllowFlight(true);
                $sender->sendMessage($this->config->get("fly_message_on", "You are now flying!"));
                $this->sendFlyTitle($sender, "fly_title_on", "fly_subtitle_on");
            } elseif ($subcommand === "off") {
                $sender->setAllowFlight(false);
                $sender->sendMessage($this->config->get("fly_message_off", "You have landed."));
                $this->sendFlyTitle($sender, "fly_title_off", "fly_subtitle_off");
            } else {
                $sender->sendMessage("Usage: /fly [on|off]");
            }
        } else {
            $sender->sendMessage("Usage: /fly [on|off]");
        }

        return true;
    }

    private function sendFlyTitle(Player $player, $titleKey, $subtitleKey) {
        $title = $this->config->get($titleKey, "Fly Mode");
        $subtitle = $this->config->get($subtitleKey, "Toggle your flight");
        $fadeIn = $this->config->get("fly_title_fade_in", 10);
        $stay = $this->config->get("fly_title_stay", 40);
        $fadeOut = $this->config->get("fly_title_fade_out", 10);
        $player->sendTitle($title, $subtitle, $fadeIn, $stay, $fadeOut);
    }
}
