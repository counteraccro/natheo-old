<?php
/**
 * Controller qui va gérer les médialib
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin;
 */
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Entity\Media\Folder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/media', name: 'admin_media_')]
class MediaLibController extends AppController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_media#Médiathèque') => '',
        ];

        $folders = $this->getDoctrine()->getRepository(Folder::class)->findAll();

        /** @var Folder $folder */
        foreach($folders as $folder)
        {
            if($folder->getParent() != null)
            {
                continue;
            }

            echo $folder->getName() . '<br />';
            if($folder->getChildren()->count() > 0)
            {
                foreach($folder->getChildren() as $child)
                {
                    echo '---- ' . $child->getName() . '<br />';
                }
            }
        }

        return $this->render('admin/media_lib/index.html.twig', [
            'breadcrumb' => $breadcrumb,
        ]);
    }
}
