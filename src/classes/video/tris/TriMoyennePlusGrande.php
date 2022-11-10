<?php

namespace netvod\video\tris;

use netvod\db\ConnectionFactory;
use netvod\render\CatalogueRenderer;
use netvod\render\Renderer;
use netvod\video\catalogue\Catalogue;
use netvod\video\serie\Serie;
use \PDO;
class TriMoyennePlusGrande implements Tri
{

    public function trier(): string
    {
        $res = "";
        $catalogue = new Catalogue();
        $query = "select id,titre, avg(note) from commentaire
                    inner join serie on commentaire.serie_id = serie.id
                    group by id,titre
                    order by avg(note) desc";
        $st = ConnectionFactory::$db->prepare($query);
        $st->execute();
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $id = $row['id'];
            $titre = $row['titre'];
            $serie = new Serie(intval($id),$titre);
            $catalogue->ajouterSerie($serie);
        }
        $res .= (new CatalogueRenderer($catalogue))->render(Renderer::COMPACT);
        return $res;
    }
}