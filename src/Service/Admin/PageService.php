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
    const DEFAULT_NAME_PAGE_TITLE = 'page-default-value-to-delete';
    const DEFAULT_NAME_NAVIGATION_TITLE = 'page-default-value-to-delete';

    const BASE_TOP_CONTENT_FOOTER = 'base-top-content-footer';
    const BASE_TOP_LEFT_CONTENT_FOOTER = 'base-top-left-content-footer';
    const BASE_TOP_CONTENT_RIGHT_FOOTER = 'base-top-content-right-footer';


    /**
     * Tableau de référence pour le choix des templates
     * @var array|array[]
     */
    private array $tabRefTemplate = [
        1 => ['id' => 1, 'base' => self::BASE_TOP_CONTENT_FOOTER],
        2 => ['id' => 2, 'base' => self::BASE_TOP_LEFT_CONTENT_FOOTER],
        3 => ['id' => 3, 'base' => self::BASE_TOP_CONTENT_RIGHT_FOOTER]
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
}