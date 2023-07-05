<?php

namespace App\Service;

use App\Entity\User;



use \Mailjet\Resources;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailJetService extends AbstractController {    


     public function mailJetSendTest() 
     {           
          $MJ_APIKEY_PUBLIC =  $this->getParameter('app.mailJetkey');
          $MJ_APIKEY_PRIVATE =  $this->getParameter('app.mailJetsecretkey');

          $mj = new \Mailjet\Client($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE,true,['version' => 'v3.1']);

          $body = [
          'Messages' => [
               [
                    'From' => [
                         'Email' => "info@bdboom.fr",
                         'Name' => "BDboom"
                    ],
                    'To' => [
                         [
                         'Email' => "johann.griffe.pro@gmail.com",
                         'Name' => "yoyo"
                         ]
                    ],
                    'Subject' => "Bienvenu sur BDboom",
                    'TextPart' => "Bienvenu sur BDboom",
                    'HTMLPart' => "<h3>Bonjour, Bienvenu sur BDboom,</h3><br />
                    <br />
                    ",  
               ]
          ]
          ];

          $response = $mj->post(Resources::$Email, ['body' => $body]);
          // $response->success() && var_dump($response->getData());
          // dd($response);
     }





     public function mailJetMailConfInscription($token, $user) 
     {
          // Use your saved credentials, specify that you are using Send API v3.1
           # Please add your access key here cle renseignées dans .env et config/service.yaml         
          $MJ_APIKEY_PUBLIC =  $this->getParameter('app.mailJetkey');
          
          # Please add your secret key here
          $MJ_APIKEY_PRIVATE =  $this->getParameter('app.mailJetsecretkey');

          $mj = new \Mailjet\Client($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE,true,['version' => 'v3.1']);

          $body = [
               'Messages' => [
                    [
                         'From' => [
                              'Email' => "info@bdboom.fr",
                              'Name' => "A&E Cookie Corner"
                         ],
                         'To' => [
                              [
                                   'Email' => $user->getEmail(),
                                   'Name' => $user->getPrenomUser()." ".$user->getNomUser()
                              ]
                         ],
                         'TemplateID'=> 4932841,
                         'TemplateLanguage' => true,
                         'Subject' => 'Bienvenu sur A&E Cookie Corner.',
                         // 'Variables' => json_decode('{
                         //           "urlConf": "http://bdboom.test/confirmationInscription?token="'.$user->getToken().'
                         //      }', true)
                         'Variables' => [
                              // 'urlConf' => $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/confirmationInscription?token='.$user->getTokenUser().''
                              'urlConf' => 'https://127.0.0.1:8000/confirmationInscription?token='.$user->getTokenUser().''
                         ]
                    ]
                         
               ]
          
          ];
          
          $response = $mj->post(Resources::$Email, ['body' => $body]);
          // dd($response);
          $response->success() && var_dump($response->getData());
     }









     public function mailJetMailMdpOublie($token, $user) 
     {
          // Use your saved credentials, specify that you are using Send API v3.1
           # Please add your access key here cle renseignées dans .env et config/service.yaml         
          $MJ_APIKEY_PUBLIC =  $this->getParameter('app.mailJetkey');
          
          # Please add your secret key here
          $MJ_APIKEY_PRIVATE =  $this->getParameter('app.mailJetsecretkey');

          $mj = new \Mailjet\Client($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE,true,['version' => 'v3.1']);

          $body = [
               'Messages' => [
                    [
                         'From' => [
                              'Email' => "info@bdboom.fr",
                              'Name' => "A&E Cookie Corner"
                         ],
                         'To' => [
                              [
                                   'Email' => $user->getEmail(),
                                   'Name' => $user->getPrenomUser()." ".$user->getNomUser()
                              ]
                         ],
                         'TemplateID'=> 4934207,
                         'TemplateLanguage' => true,
                         'Subject' => 'Bienvenu sur A&E Cookie Corner.',
                         // 'Variables' => json_decode('{
                         //           "urlConf": "http://bdboom.test/confirmationInscription?token="'.$user->getToken().'
                         //      }', true)
                         'Variables' => [
                              // 'urlConf' => $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/confirmationInscription?token='.$user->getTokenUser().''
                              'urlConf' => 'https://127.0.0.1:8000/motdepasseoublieSetMDP?token='.$user->getTokenUser().''
                         ]
                    ]
                         
               ]
          
          ];
          
          $response = $mj->post(Resources::$Email, ['body' => $body]);
          // dd($response);
          $response->success() && var_dump($response->getData());
          // $response->success();
     }



}