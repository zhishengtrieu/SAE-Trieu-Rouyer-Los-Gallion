<?php
declare(strict_types=1);

namespace netvod\action;

use netvod\db\ConnectionFactory;

class DisplayProfilAction extends Action
{

    public function execute(): string
    {
        ConnectionFactory::makeConnection();
        $res = "";

        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            $email = $user->email;
            if ($this->http_method == 'POST') {
                if (($_POST['nom']!="") || ($_POST['prenom']!="") ||($_POST['pref']!="")) {
                    $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
                    $ui = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
                    $id = filter_var($_POST['pref'], FILTER_SANITIZE_STRING);
                    if ($nom  != "") {
                        $sql = ("update user set nom=? where email=?");
                        $st = ConnectionFactory::$db->prepare($sql);
                        $st->bindParam(1, $nom);
                        $st->bindParam(2, $email);
                        $st->execute();
                        //on met a jour l'objet user
                        $user->nom = $nom;
                        $res = "Changement profil effecuté";
                    }
                
                    if ($ui != "") {
                        $sqll = ("update user set prenom=? where email=?");
                        $st = ConnectionFactory::$db->prepare($sqll);
                        $st->bindParam(1, $ui);
                        $st->bindParam(2, $email);
                        $st->execute();
                        //on met a jour l'objet user
                        $user->prenom = $ui;
                        $res = "Changement profil effecuté";
                    }
                
                    if ($id != "") {
                        $sqlo = ("select id_genre from genre where libelle_genre=?");
                        $st = ConnectionFactory::$db->prepare($sqlo);
                        $st->bindParam(1, $id);
                        $st->execute();
                        $row = $st->fetch();
                        $ids = $row["id_genre"];

                        $sqla = ("update user set id_genre=? where email=?");
                        $st = ConnectionFactory::$db->prepare($sqla);
                        $st->bindParam(1, $ids);
                        $st->bindParam(2, $email);
                        $st->execute();
                        $res = "Changement profil effecuté";
                    }
                    
                }else{
                    $res="Veuillez au minimum rentrer une information" ;
                }
                
                //on met l'objet user a jour dans la session
                $_SESSION['user'] = serialize($user);

            } else {
                $res = <<<END
                <form action="?action=display-profil" method="POST">
                <input type="text" name="nom" placeholder="Nom" value="$user->nom">
                <input type="text" name="prenom" placeholder="Prenom" value="$user->prenom">
                <select name='pref'>
                    <option value="">Selectionner un genre préféré</option>
                    <option value="action">Action</option>
                    <option value="thriller">Thriller</option>
                    <option value="anime">Anime</option>
                    <option value="comedie">Comedie</option>
                    <option value="romance">Romance</option>
                    <option value="horreur">Horreur</option>
                </select>
                <input type="submit" value="valider">
                </form>
                <form action="?action=logout" method="POST">
                    <input type="submit" value="Se déconnecter">
                </form>
                END;
                
            }
        }else{
            $res="Veuillez-vous connecter !";
        }
        return $res;
    }
}