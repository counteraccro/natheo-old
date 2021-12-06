<?php
/**
 * Gestion des tags
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Module
 */
namespace App\Service\Module;

use App\Entity\Modules\Tag;
use App\Service\AppService;

class TagService extends AppService
{
    const SESSION_KEY_TMP_TAG = 'session_tag_tmp_save';

    /**
     * Stock un tag dans le tableau des tags temporaire
     * @param Tag $tag
     */
    public function addTmptag(Tag $tag)
    {
        $tabTmp = $this->request->getSession()->get(self::SESSION_KEY_TMP_TAG, []);

        $tabTmp[$tag->getId()] = $tag;
        $this->request->getSession()->set(self::SESSION_KEY_TMP_TAG, $tabTmp);
    }

    /**
     * Retourne le contenu du tableau de tag temporaire
     */
    public function readTmpTag()
    {
        return $this->request->getSession()->get(self::SESSION_KEY_TMP_TAG, []);
    }

    /**
     * Supprime un tag du tableau de temporaire de tag
     * @param Tag $tag
     */
    public function deleteTmpTag(Tag $tag)
    {
        $tabTmp = $this->request->getSession()->get(self::SESSION_KEY_TMP_TAG, []);

        unset($tabTmp[$tag->getId()]);
        $this->request->getSession()->set(self::SESSION_KEY_TMP_TAG, $tabTmp);
    }

    /**
     * Réinitialise le tableau temporaire de tag
     */
    public function resetTmpTag()
    {
        $this->request->getSession()->set(self::SESSION_KEY_TMP_TAG, []);
    }
}