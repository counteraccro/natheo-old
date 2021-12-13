<?php
/**
 * Service pour gérer les pages
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin
 */

namespace App\Service\Admin;

use App\Entity\Admin\Page\Page;
use App\Entity\Admin\Page\PageTranslation;
use App\Service\AppService;

class PageService extends AppService
{
    const SESSION_KEY_SELECT_TEMPLATE = 'session_select_template';

    const SESSION_KEY_OBJ_PAGE = 'session_obj_page';

    const SESSION_KEY_CURRENT_LOCAl_PAGE = 'session_key_current_local_page';

    /**
     * Tableau de référence pour le choix des templates
     * @var array|array[]
     */
    private array $tabRefTemplate = [
        1 => ['id' => 1, 'base' => 'base-top-content-footer'],
        2 => ['id' => 2, 'base' => 'base-top-left-content-footer'],
        3 => ['id' => 3, 'base' => 'base-top-content-right-footer']
    ];

    private int $defaultTheme = 1;

    /**
     * Met à jour le template selectionné en session
     * @param int $id
     * @return void
     */
    public function setSelectedTemplate(int $id = 0)
    {
        $selectTemplate = $this->tabRefTemplate[$this->defaultTheme];
        if (isset($this->tabRefTemplate[$id])) {
            $selectTemplate = $this->tabRefTemplate[$id];
        }
        $this->request->getSession()->set(self::SESSION_KEY_SELECT_TEMPLATE, $selectTemplate);
    }

    /**
     * Retourne le template selectionné
     * Si la session est vide, renvoi le template par défaut
     * @return array
     */
    public function getSelectedTemplate(): array
    {
        return $this->request->getSession()->get(self::SESSION_KEY_SELECT_TEMPLATE, $this->tabRefTemplate[$this->defaultTheme]);
    }

    /**
     * Retourne l'objet courant de la page en cours d'édition
     * @return Page|null
     */
    public function getCurrentObjPage(): Page|null
    {
        return $this->request->getSession()->get(self::SESSION_KEY_OBJ_PAGE);
    }

    /**
     * Set un objet page courant en fonction de la langue et retourne la page courante mise à jour
     * @param Page|null $page
     * @param string $language
     * @return Page
     */
    public function setCurrentObjPage(string $language, Page $page = null): Page
    {

        $page = $this->getCurrentObjPage();
        if ($page == null) {
            $page = new Page();
        }

        /** @var PageTranslation $pageTranslation */
        $is_language = false;
        foreach ($page->getPageTranslations() as $pageTranslation) {
            if ($pageTranslation->getLanguage() === $language) {
                $is_language = true;
            }
        }

        if (!$is_language) {
            $pageTranslation = new PageTranslation();
            $pageTranslation->setLanguage($language);
            $page->addPageTranslation($pageTranslation);
        }
        $this->request->getSession()->set(self::SESSION_KEY_OBJ_PAGE, $page);
        return $page;
    }

    /**
     * Supprime la page courant en session
     * @return void
     */
    public function removeCurrentObjPage()
    {
        $this->request->getSession()->remove(self::SESSION_KEY_OBJ_PAGE);
    }
}