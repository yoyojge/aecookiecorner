<?php

namespace App\Service;



class PanierService
{

     public function modifPanier( $sessionPanier, $id, $qtity) 
     {  
          
          
          // dd('service step');
          
          //on recupere la session "panier, si elle n'existe pas , on renvoie un tableau vide
          $panier  = $sessionPanier->get("panier", []);

          
          
          // dd($id, $qtity);

          //TODO: fonction verification que l'article existe
          

          //fonction gestion panier
          if(empty($panier[$id])){
               $panier[$id] = $qtity;
          }
          else{
               $panier[$id] += $qtity;
          }
          
          $sessionPanier->set("panier", $panier);
     }

}