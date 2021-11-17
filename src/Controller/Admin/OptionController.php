<?php
/**
 * Controller qui va génrer les options
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin;
 */

namespace App\Controller\Admin;

use App\Service\Admin\System\OptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/option', name: 'admin_option_')]
class OptionController extends AppAdminController
{
    /**
     * Index pour les options
     * @return Response
     */
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_global_option#Options globales') => '',
        ];


        return $this->render('admin/option/index.html.twig', [
            'breadcrumb' => $breadcrumb,
        ]);
    }

    /**
     * Permet de sauvegarder une option
     */
    #[Route('/save', name: 'ajax_save')]
    public function saveOption(): JsonResponse
    {
        $data = $this->request->getCurrentRequest()->request->all();
        $result = $this->optionService->UpdateByKey($data['key'], $data['value']);
        if ($result) {

            if($data['key'] == OptionService::GO_ADM_THEME_ADMIN)
            {
                $this->session->set(OptionService::KEY_SESSION_THEME_ADMIN, $data['value']);
            }

            $return = ['success' => true, 'msg' => $this->translator->trans('admin_global_option#Option mise à jour avec succès')];
        } else {
            $return = ['success' => false, 'msg' => $this->translator->trans('admin_global_option#Une erreur interne est survenue')];
        }
        return $this->json($return);
    }
}
