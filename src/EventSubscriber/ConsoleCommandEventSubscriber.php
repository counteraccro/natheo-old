<?php
/**
 * Event qui va intercepter une commande
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\EventSubscriber;
 */
namespace App\EventSubscriber;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConsoleCommandEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[ArrayShape([ConsoleEvents::COMMAND => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => 'onCommand',
        ];
    }

    /**
     * @param ConsoleCommandEvent $event
     * @throws Exception
     * Écoute des commandes
     */
    public function onCommand(ConsoleCommandEvent $event)
    {
        // Si la commande est fixtures:load
        if ($event->getCommand()->getName() === 'doctrine:fixtures:load') {
            $this->runCustomTruncate();
        }
    }

    /**
     * Permet de supprimer le temps de l'exécution de la commande le control des contraintes d"intégrités
     * @throws Exception
     */
    private function runCustomTruncate()
    {
        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');

        $purger = new ORMPurger($this->entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');
    }
}