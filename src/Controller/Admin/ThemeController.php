<?php
/**
 * Controller pour la gestion des thèmes
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Controller\Admin
 */
namespace App\Controller\Admin;

use App\Entity\Admin\Theme;
use App\Service\Admin\System\FileUploaderService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/theme', name: 'admin_theme_')]
class ThemeController extends AppAdminController
{
    /**
     * Point d'entrée sur la gestion des themes
     * @return Response
     */
    #[Route('/index', name: 'index')]
    public function index(): Response
    {

        $tabThemes = $this->themeService->readThemes();
        $default_theme = $this->themeService->getDefaultTheme();

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_theme#Gestion des thèmes') => '',
        ];

        return $this->render('admin/theme/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'tabThemes' => $tabThemes,
            'default_theme' => $default_theme
        ]);
    }

    /**
     * Permet de selectionner un thème
     * @param Theme $theme
     * @return RedirectResponse
     */
    #[Route('/select/{id}', name: 'select')]
    public function select(Theme $theme): RedirectResponse
    {
        $this->getDoctrine()->getRepository(Theme::class)->selectTheme($theme->getId());
        $this->themeService->setCurrentThemeInSession();
        return $this->redirectToRoute('admin_theme_index');
    }

    /**
     * Permet de supprimer un thème
     * @param Theme $theme
     * @param int $confirm
     * @return Response
     */
    #[Route('/delete/{id}/{confirm}', name: 'ajax_delete')]
    public function delete(Theme $theme, int $confirm = 0): Response
    {
        if($confirm == 1)
        {

            $this->themeService->deleteTheme($theme);
            return $this->json([
                'msg' => $this->translator->trans('admin_theme#Thème supprimé avec succès'),
            ]);
        }

        return $this->render('admin/theme/ajax/ajax-modal-delete-theme.html.twig', [
            'theme' => $theme,
        ]);
    }

    /**
     * Permet de voir les données d'un thème
     * @param Theme $theme
     * @return Response
     */
    #[Route('/see/{id}', name: 'see')]
    public function see(Theme $theme): Response
    {
        $path = $this->themeService->getThemeDirectory() . '/' . $theme->getFolderRef();

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_theme#Gestion des thèmes') => 'admin_theme_index',
            $this->translator->trans('admin_theme#Détail du thème') . ' ' . $theme->getName() => ''
        ];

        return $this->render('admin/theme/see.html.twig', [
            'breadcrumb' => $breadcrumb,
            'theme' => $theme,
            'path' => $path
        ]);
    }

    /**
     * Permet d'ajouter un nouveau thème au CMS
     */
    #[Route('/upload-theme', name: 'upload')]
    public function uploadTheme(FileUploaderService $fileUploaderService): Response
    {
        $msg_error = '';
        $installError = [];
        $theme = $this->request->getCurrentRequest()->files->get('theme-folder');
        if($theme != null && $theme->getClientOriginalExtension() == 'zip')
        {
            /** @var UploadedFile $theme */
            $name = $fileUploaderService->upload($theme, $this->themeService->getThemeTmpDirectory());
            $installError = $this->themeService->installNewTheme($name, $theme->getClientOriginalName());
            var_dump($installError);
            if($installError['success'] === true)
            {
                echo "ici";
                if(empty($installError['msg']['warning']))
                {
                    $this->addFlash('success', $this->translator->trans('admin_theme#Thème ajouté avec succès'));
                }
                else {

                    $warningMsg = '';
                    foreach($installError['msg']['warning'] as $warning)
                    {
                        $warningMsg .= '-' . $warning . '<br />';
                    }

                    if($warningMsg != "")
                    {
                        $warningMsg = " : <br />" . $warningMsg;
                    }

                    $this->addFlash('warning', $this->translator->trans('admin_theme#Thème ajouté avec succès mais des warnings sont remontés') . $warningMsg);
                }
                return $this->redirectToRoute('admin_theme_index');
            }

        }
        elseif ($theme != null && $theme->getClientOriginalExtension() != 'zip')
        {
            $msg_error = $this->translator->trans('admin_theme#Votre thème doit être un fichier .zip');
        }

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_theme#Gestion des thèmes') => 'admin_theme_index',
            $this->translator->trans('admin_theme#Ajouter un thème') => ''
        ];

        return $this->render('admin/theme/upload-theme.html.twig', [
            'breadcrumb' => $breadcrumb,
            'msg_error' => $msg_error,
            'install_error' => $installError,
        ]);
    }
}
