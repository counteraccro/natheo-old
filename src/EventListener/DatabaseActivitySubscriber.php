<?php
/**
 * Listener sur les actions Update/delete/persist
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin;
 */
namespace App\EventListener;

use App\Entity\Media\Folder;
use App\Service\Admin\System\FileService;
use App\Service\Admin\System\FileUploaderService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DatabaseActivitySubscriber implements EventSubscriberInterface
{
    /**
     * @var FileUploaderService
     */
    private FileUploaderService $fileUploaderService;

    private FileService $fileService;

    public function __construct(FileUploaderService $fileUploaderService, FileService $fileService)
    {
        $this->fileUploaderService = $fileUploaderService;
        $this->fileService = $fileService;
    }

    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself
    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('persist', $args);
    }

    /**
     * Avant la suppression
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->logActivity('remove', $args);

        $entity = $args->getObject();
        /**
         * Dans le cas d'un dossier
         */
        if ($entity instanceof Folder) {

            foreach($entity->getMedia() as $media)
            {
                // On supprime l'image associée à un média
                $this->fileService->delete($media->getPath(), $this->fileUploaderService->getMediathequeDirectory());
            }
        }
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->logActivity('update', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        /*$entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Product) {
            return;
        }*/

        // ... get the entity information and log it somehow
    }
}