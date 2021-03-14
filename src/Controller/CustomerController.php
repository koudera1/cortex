<?php


namespace App\Controller;



use App\Entity\Customer;
use App\Entity\LoyaltyCard;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use App\Repository\LoyaltyCardRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CustomerController
 * @package App\Controller
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/search", methods="GET", name="blog_search")
     */
    public function search(Request $request, CustomerRepository $customers, LoyaltyCardRepository $loyaltyCards): Response
    {
        $arr = [];
        $bool1 = $bool2 = false;
        if ($request->query->has('firstname')) {
            $arr['firstname'] = $request->query->get('firstname');
            $bool1 = true;
        }
        if ($request->query->has('lastname')) {
            $arr['lastname'] = $request->query->get('lastname');
            $bool2 = true;
        }
        if ($request->query->has('loyaltyCard')) {
            $arr['loyaltyCard'] = $request->query->get('loyaltyCard');
        } else {
            return $this->render('search.html.twig', [
                 'customers' => $customers->findBy($arr)
            ]);
        }

        return $this->render('search.html.twig', [
            'customers' => $loyaltyCards->searchByAttributes($arr, $bool1, $bool2)
            ]);
    }

    /**
     * Creates a new Customer entity.
     *
     * @Route("/new", methods="GET|POST")
     *
     */
    public function new(Request $request, MailerInterface $mailer, CustomerRepository $customers): Response
    {
        $customer = new Customer();
        $repository = $this->getDoctrine()->getRepository(LoyaltyCard::class);
        $loyaltyCard = $repository->findLowestLoyaltyCard("temporary");
        $loyaltyCard->setCustomer($customer);
        $customer->setRegistrationDate(new \DateTime());

        $form = $this->createForm(CustomerType::class, $customer)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dupl = $customers->findOneBy(['telephone' => $customer->getTelephone()]);
            if($dupl != null)
            {
                $dupl->setDuplicate(true);
                $customer->setDuplicate(true);
            }
            $dupl = $customers->findOneBy(
                [
                    'firstname' => $customer->getFirstname(),
                    'lastname' => $customer->getLastname(),
                    'address' => $customer->getAddress()
                ]
            );
            if($dupl != null)
            {
                $dupl->setDuplicate(true);
                $customer->setDuplicate(true);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            $email = (new Email())
                ->from('netopyr1997@centrum.cz')
                ->to($customer->getEmail())
                ->subject('You\'ve been successfully registered.')
                ->text('Hurray!');
            $mailer->send($email);

            $this->addFlash('success', 'Customer created successfully.');
        }

        return $this->render('registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/report", methods="GET")
     */
    public function report(Request $request, CustomerRepository $customers, LoyaltyCardRepository $loyaltyCards, PurchaseRepository $purchases): Response
    {
        return $this->render('report.html.twig',
            [
                'customers_count' => $customers->countCustomers(),
                'assigned_cards_count' => $loyaltyCards->countAssignedCards(),
                'most_spending_customers' => $purchases->findMostSpendingCustomers()
            ]);
    }

    /**
     * @Route("/customer/{id}/card")
     */
    public function assignBasicLoyaltyCard(Request $request, CustomerRepository $customers, LoyaltyCardRepository $loyaltyCards, $id)
    {
        $customer = $customers->findOneBy(['id' => $id]);
        $loyaltyCard = $loyaltyCards->findLowestLoyaltyCard("basic");
        $loyaltyCard->setCustomer($customer);
        //registrace požadavku na výrobu přiřazené základní věrnostní karty v samostatném
        //systému pro výrobu plastových karet s využitím API
    }
}