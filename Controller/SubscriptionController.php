<?php

namespace Indexed\RestBundle\Controller;

use Doctrine\ORM\EntityManager;
use Indexed\RestBundle\Entity\RestLog;
use Indexed\SubscriptionBundle\Entity\Company;
use Indexed\SubscriptionBundle\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/rest/subscription")
 */
class SubscriptionController extends Controller
{
    /**
     * @Route("/cancel/{id}", name="indexed_subscription_rest_cancel")
     */
    public function indexAction($id = 0, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $response = [];

        $restLog = new RestLog();
        $restLog->setResource($request->getPathInfo());
        $restLog->setMethod($request->getMethod());

        try {
            $json = $request->getContent();
            $data = json_decode($json);

            if (!is_object($data)) {
                throw new \Exception('Not a valid json object');
            }

            $restLog->setRequest(json_encode($data));

            if (empty($data->key)) {
                throw new \Exception('No key set');
            }

            /** @var Company $company */
            $company = $em->getRepository('IndexedSubscriptionBundle:Company')->findOneByApiKey($data->key);

            if (!empty($company)) {

                $restLog->setCompany($company);

                /** @var Subscription $subscription */
                $subscription = $em->getRepository('IndexedSubscriptionBundle:Subscription')->find($id);

                if (!empty($subscription)) {
                    $restLog->setSubscription($subscription);
                } else {
                    throw new \Exception('No subscription found');
                }

                /*
                 * Security check
                 */
                if ($subscription->getUser()->getCompanyId() == $company->getId()) {

                    $restLog->setUser($subscription->getUser());

                    if ($subscription->getActive()) {
                        $this->get('indexed.payment.stripe')->unsubscribe($subscription);
                        $response['message'] = 'Subscription cancelled';
                    } else {
                        $response['message'] = 'Subscription is not active';
                    }

                } else {
                    throw new \Exception('Not allowed to view this subscription');
                }
            } else {
                throw new \Exception('Key not found');
            }

        } catch (\Exception $e) {

            $response = [
                'error'=>$e->getMessage(),
            ];

            $restLog->setError(true);
        }

        $response = json_encode($response);

        $restLog->setResponse($response);

        $em->persist($restLog);
        $em->flush();

        $responseHttp = new Response();
        $responseHttp->setContent($response);
        $responseHttp->headers->set('Content-Type', 'application/json');

        return $responseHttp;
    }
}