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
use App\Service\Admin\ThemeService;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/theme', name: 'admin_theme_')]
class ThemeController extends AppAdminController
{
    /**
     * Point d'entrée sur la gestion des themes
     * @param ThemeService $themeService
     * @return Response
     */
    #[Route('/index', name: 'index')]
    public function index(ThemeService $themeService): Response
    {

        $tabThemes = $themeService->readThemes();

        $breadcrumb = [
            $this->translator->trans('admin_dashboard#Dashboard') => 'admin_dashboard_index',
            $this->translator->trans('admin_theme#Gestion des thèmes') => '',
        ];

        return $this->render('admin/theme/index.html.twig', [
            'breadcrumb' => $breadcrumb,
            'tabThemes' => $tabThemes,
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
        return $this->redirectToRoute('admin_theme_index');
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
        $theme = $this->request->getCurrentRequest()->files->get('theme-folder');
        if($theme != null && $theme->getClientOriginalExtension() == 'zip')
        {
            /** @var UploadedFile $theme */
            var_dump($theme);
            $name = $fileUploaderService->upload($theme, $fileUploaderService->getThemeTmpDirectory());

            $zip = new \ZipArchive();
            $res = $zip->open($fileUploaderService->getThemeTmpDirectory() . '/' . $name);
            if ($res === TRUE) {
                echo 'ok';
                $zip->extractTo($fileUploaderService->getThemeTmpDirectory() . '/' . 'open-zip');
                $zip->close();

                $filesystem = new Filesystem();
                $filesystem->mirror($fileUploaderService->getThemeTmpDirectory() . '/' . 'open-zip/theme-upload', $fileUploaderService->getThemeTemplateDirectory());
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
            'msg_error' => $msg_error
        ]);
    }
}
