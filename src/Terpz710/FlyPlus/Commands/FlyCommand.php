<?php

namespace Terpz710\FlyPlus\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Terpz710\FlyPlus\Main;

class FlyCommand extends Command {

    public function __construct() {
        parent::__construct("fly", "Toggle flying");
        $this->setPermission("flyplus.fly");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {

        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used by players.");
            return true;
        }

        $plugin = Main::getInstance();
        $config = $plugin->getConfig();

        if (!$sender->hasPermission("flyplus.fly")) {
            $sender->sendMessage("You don't have permission to use this command.");
            return true;
        }

        if (empty($args)) {
            if ($sender->getAllowFlight()) {
                $sender->setAllowFlight(false);
                $sender->sendMessage($config->get("fly_message_off", "You have landed."));
                $this->sendFlyTitle($sender, "fly_title_off", "fly_subtitle_off");
            } else {
                $sender->setAllowFlight(true);
                $sender->sendMessage($config->get("fly_message_on", "You are now flying!"));
                $this->sendFlyTitle($sender, "fly_title_on", "fly_subtitle_on");
            }
        } elseif (count($args) === 1) {
            $subcommand = strtolower($args[0]);
            if ($subcommand === "on") {
                $sender->setAllowFlight(true);
                $sender->sendMessage($config->get("fly_message_on", "You are now flying!"));
                $this->sendFlyTitle($sender, "fly_title_on", "fly_subtitle_on");
            } elseif ($subcommand === "off") {
                $sender->setAllowFlight(false);
                $sender->sendMessage($config->get("fly_message_off", "You have landed."));
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
        $config = Main::getInstance()->getConfig();
        $title = $config->get($titleKey, "Fly Mode");
        $subtitle = $config->get($subtitleKey, "Toggle your flight");
        $fadeIn = $config->get("fly_title_fade_in", 10);
        $stay = $config->get("fly_title_stay", 40);
        $fadeOut = $config->get("fly_title_fade_out", 10);
        $player->sendTitle($title, $subtitle, $fadeIn, $stay, $fadeOut);
    }
}
