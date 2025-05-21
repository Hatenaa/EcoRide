<?php

namespace Ecoride\Class;

use Parsedown;

class LegalNotice
{
    private $file = __DIR__ . '/../includes/legal_notice.md';

    /**
     * Enregistre du texte (au format markdown) dans le fichier legal_notice.md.
     * Utilisé quand l’utilisateur valide le formulaire (POST).
     */

    public function save($markedown) {
        file_put_contents($this->file, $markedown);
    }

    /**
     * Si le fichier n’existe pas → retourne une chaîne vide.
     * Sinon → retourne le contenu brut (markdown).
     */

    public function getRaw() {
        if (!file_exists($this->file)) return '';
        return file_get_contents($this->file);
    }

    /**
     * On créer un objet Parsedown.
     * Puis, on le transforme en texte markdown récupérer dans getRaw() en HTML.
     * Ensuite, on l'utilise pour afficher proprement le texte sur le site dans la page legal.php.
     * @return string Le contenu HTML converti à partir du markdown.
     */

    public function getHtml() {
        $parsedown = new Parsedown();
        return $parsedown->text($this->getRaw());
    }


}