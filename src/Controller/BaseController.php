<?php

namespace App\Controller;

use App\Entity\Users;
use DateInterval;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validation;

/**
 * Class BaseController
 * @package App\Controller
 */
class BaseController extends AbstractController
{
    private $cache;
    protected $loggedUser;

    public function __construct()
    {
        /* Define Cache */
        $this->cache = new FilesystemAdapter();
    }

    /**
     * Created By Nahla Sameh
     * Check If current user of request is authorized
     * @param Request $request
     * @return bool
     */
    public function isAuthorized(Request $request)
    {
        $this->getLoggedUser($request);
        if ($this->loggedUser !== null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Created By Nahla Sameh
     * Send Email to user with otp code
     * @param MailerInterface $mailer
     * @param Users $user
     * @return bool
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendOtp(MailerInterface $mailer, Users $user)
    {
        try {
            /* Create Otp */
            $otp = $this->authenticateOtp($user->getId());

            /* Send Email to the user*/
            $email = (new Email())
                ->from('nodysameh@gmail.com')
                ->to($user->getEmail())
                ->subject('Meet The Team Login Confirm!')
                ->html('<p>Your Login secure Otp is : ' . $otp . '</p>');

            $mailer->send($email);

            return true;
        } catch (\Exception $exception) {
            /*If any thing went wrong, then return true*/
            return false;
        }

    }

    /**
     * Created By Nahla Sameh
     * @param $userId
     * @return int|mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function authenticateOtp($userId)
    {
        /* Init Otp */
        $otp = null;

        /* Check if user already has cached otp */
        $cachedOtp = $this->cache->getItem("userOtp_" . $userId);
        if (!$cachedOtp->isHit()) {
            /* if user hasn't cached otp then create one, then save it on cache  */
            $otp = $this->getRandomAlphanumeric();

            $cachedOtp->set($otp);
            $cachedOtp->expiresAfter(new DateInterval('PT20M')); // the otp will be cached for 20 minute
            $this->cache->save($cachedOtp);
        } else {
            /* if user already has cached otp, then return it*/
            $otp = $cachedOtp->get();
        }

        return $otp;
    }

    /**
     * Created By Nahla Sameh
     * Use Validator to check if otp has valid format
     * @param $otp
     * @return bool
     */
    function isValidOtp($otp)
    {
        /* Set Validation Constraints*/
        $validator = Validation::createValidator();
        $violations = $validator->validate($otp,
            [new NotBlank(), new Type("alnum")]
        );

        /* if Not Valid, return false*/
        if (count($violations) > 0) {
            return false;
        }

        /* if Valid, return true*/
        return true;
    }


    /**
     * Created By Nahla Sameh
     * Set Current user according to required params
     * @param Request $request
     * @param Users|null $user
     */
    function setLoggedUser(Request $request, Users $user = null)
    {
        /* Get Session object */
        $session = $request->getSession();
        /* Check the user object */
        if ($user === null) {
            /* If User is null, Then the request is to init logged user (logout) */
            $this->loggedUser = null;
            $session->remove('loggedUser');
            $session->clear();

        } else {
            /* If User isn't null, Then the request is to update logged user to be current user (authorized user) */
            $this->loggedUser = array(
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'fullname' => $user->getFullname()
            );

            /* Set logged user in session */
            $session->set('loggedUser', $this->loggedUser);
        }
    }

    /**
     * Created By Nahla Sameh
     * check if there is logged user in session
     * @param Request $request
     */
    function getLoggedUser(Request $request)
    {
        /* Get session*/
        $session = $request->getSession();

        /* check logged user from session */
        if ($session->get('loggedUser') !== null) {
            $this->loggedUser = $session->get('loggedUser');
        }
    }

    /**
     * Created By Nahla Sameh
     * Create random alphanumeric
     * @return false|string
     */
    function getRandomAlphanumeric(){
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($permitted_chars), 0, 5);
    }
}
