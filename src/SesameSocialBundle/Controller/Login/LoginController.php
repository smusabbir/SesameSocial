<?php

namespace SesameSocialBundle\Controller\Login;

use Doctrine\ORM\EntityManagerInterface;
use SesameSocialBundle\Entity\Account;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SesameSocialBundle\SesameSocialBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SesameSocialBundle\Model\UserLogin;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;


/**
 * Login Controller
 *
 * @author
 *
 *
 */


class LoginController extends Controller
{
    /**
     * @Route("/", methods={"GET"}, name="main")
     *
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('login/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,]);
    }

    /**
     * @Route("/login", name="login")
     */

    public function loginAction(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        $data       = file_get_contents("php://input", true);
        $decoded    = json_decode($data, true);
        $httpClient = new Client();
        $serializer = SerializerBuilder::create()->build();
        $user       = new UserLogin($httpClient, $serializer, $decoded['emailaddress'],$decoded['password'], NULL);
        $jsondata   = $user->getAuthentication();
        $jsondata_decoded = json_decode($jsondata, true);
        $isAuthenticated  = !isset($jsondata_decoded['error'])?(($jsondata_decoded['status'] === 'ok')? true : false):false;
        $apiKey     = false;
        if($isAuthenticated)
        {
            $repository = $this->getDoctrine()->getRepository(Account::class);
            $account    = $repository->findOneByEmail($decoded['emailaddress']);
            if($account)
             {
               $apiKey  = $account->getapiKey();
             }


        }

        $Response = new JsonResponse(array('isAuthenticated' => $isAuthenticated, 'apikey' => $apiKey));

        if(isset($decoded["rememberme"]))
        {
            $hour = time() + 3600 * 24 * 30;
            $val  = $apiKey;
            setcookie('remember-me', $val, $hour);
        }

        return $Response;

    }

    /**
     * @Route("/account")
     */

    public function accountAction(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        $data = file_get_contents("php://input", true);
        $decoded = json_decode($data, true);
        $httpClient = new Client();
        $serializer = SerializerBuilder::create()->build();
        $user = new UserLogin($httpClient, $serializer, NULL, NULL, $decoded['apikey']);
        $jsonAccountdata = $user->getAccountinfo();
        $jsonAccountdata_decoded = json_decode( $jsonAccountdata, true);
        $accountId = isset($jsonAccountdata_decoded[0]['id'])? $jsonAccountdata_decoded[0]['id']: NULL;
        $jsonProjectdata = $user->getProjectinfo($accountId);
        $jsonProjectdata_decoded = json_decode($jsonProjectdata, true);
        $projectId = isset($jsonProjectdata_decoded['projects'][0]['id'])? $jsonProjectdata_decoded['projects'][0]['id']: NULL;
        $Response = new JsonResponse(array('accountId' => $accountId, 'projectId' =>$projectId));
        return $Response;

    }


}
