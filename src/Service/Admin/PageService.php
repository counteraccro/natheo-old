<?php
/**
 * Service pour gérer les pages
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin
 */
namespace App\Service\Admin;

use App\Service\AppService;

class PageService extends AppService
{
    const SESSION_KEY_SELECT_TEMPLATE = 'session_select_template';

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
    Public function setSelectedTemplate(int $id = 0)
    {
        $selectTemplate = $this->tabRefTemplate[$this->defaultTheme];
        if(isset($this->tabRefTemplate[$id]))
        {
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