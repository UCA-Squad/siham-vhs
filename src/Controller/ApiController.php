<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Util\DossierAgentWebService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    private $apiLogger;

    public function __construct(LoggerInterface $logger)
    {
        $this->apiLogger = $logger;
    }

    /**
     * @Route("/user/{username}/email/perso", name="api_user_email", methods={"POST"})
     */
    public function userEmailPerso(Request $request, EntityManagerInterface $em, $username): Response {
        if ($request->headers->get('X-Auth-Token') !== $_ENV['API_TOKEN'])
            return new JsonResponse(['message' => 'Access denied'], Response::HTTP_FORBIDDEN);

        $agent = $em->getRepository(Agent::class)->findOneByUsername($username);
        if (!$agent) {
            return new JsonResponse(['message' => 'Not found'], Response::HTTP_NO_CONTENT);
        }

        $email = $request->get('email');
        if (empty($email))
            return new JsonResponse(['message' => 'Empty email'], Response::HTTP_NO_CONTENT);

        $dossierAgentWS = new DossierAgentWebService();
        $success = empty($agent->getMailPerso()) ?
            $dossierAgentWS->addEmailPersonal($agent->getMatricule(), $email) :
            $dossierAgentWS->updateEmailPersonal($agent->getMatricule(), $email);
        if ($success != 1)
            return new JsonResponse(['message' => 'SIHAM webservice does\'nt work'], Response::HTTP_INTERNAL_SERVER_ERROR);

        $agent->setMailPerso($email);
        $em->flush();

        $this->apiLogger->info('Updated agent ' . $agent->getMatricule(), ['username' => $agent->getUsername(), 'email' => $email]);


        return new JsonResponse(['message' => 'Updated agent'], Response::HTTP_OK);
    }

    /**
     * @Route("/user/{username}/mobile/perso", name="api_user_mobile", methods={"POST"})
     */
    public function userPhonePerso(Request $request, EntityManagerInterface $em, $username): Response {
        if ($request->headers->get('X-Auth-Token') !== $_ENV['API_TOKEN'])
            return new JsonResponse(['message' => 'Access denied'], Response::HTTP_FORBIDDEN);

        $agent = $em->getRepository(Agent::class)->findOneByUsername($username);
        if (!$agent) {
            return new JsonResponse(['message' => 'Not found'], Response::HTTP_NO_CONTENT);
        }

        $mobile = $request->get('mobile');
        if (empty($mobile))
            return new JsonResponse(['message' => 'Empty mobile'], Response::HTTP_NO_CONTENT);

        $dossierAgentWS = new DossierAgentWebService();
        $success = empty($agent->getPortablePerso()) ?
            $dossierAgentWS->addMobilePersonal($agent->getMatricule(), $mobile) :
            $dossierAgentWS->updateMobilePersonal($agent->getMatricule(), $mobile);
        if ($success != 1)
            return new JsonResponse(['message' => 'SIHAM webservice does\'nt work'], Response::HTTP_INTERNAL_SERVER_ERROR);

        $agent->setPortablePerso($mobile);
        $em->flush();

        $this->apiLogger->info('Updated agent ' . $agent->getMatricule(), ['username' => $agent->getUsername(), 'mobile' => $mobile]);


        return new JsonResponse(['message' => 'Updated agent'], Response::HTTP_OK);
    }
}
