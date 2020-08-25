<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EmailLoginType;
use App\Form\OtpLoginType;
use App\Repository\UsersRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Validation;

/**
 * Class SecurityController
 * @package App\Controller\Team
 */
class SecurityController extends BaseController
{
    /**
     * Created By Nahla sameh
     * This function display login form
     * When form submit it send otp email for the user
     * Then redirect to confirmOtp page
     * @Route("/", name="login", methods={"GET","POST"})
     * @param Request $request
     * @param UsersRepository $usersRepository
     * @param MailerInterface $mailer
     * @return Response
     */
    public function login(Request $request, UsersRepository $usersRepository, MailerInterface $mailer): Response
    {
        /* Check if Authorized user, then redirect to colleague list*/
        if ($this->isAuthorized($request)) {
            return $this->redirectToRoute('colleague_index');
        }
        /* Create login form */
        $form = $this->createForm(EmailLoginType::class, new Users());
        $form->handleRequest($request);

        /* check if login form submited and isValid */

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /* Fetch user by email*/
                $user = $usersRepository->findOneBy(['email' => $form->getData()->getEmail()]);
                if ($user !== null) { /* user exist*/

                    /* send message contain otp code to user email*/
                    if ($this->sendOtp($mailer, $user)) {
                        /* redirect to otp form for confirmation */
                        return $this->confirmOtp($request, $usersRepository, $form->getData()->getEmail());
                    } else {
                        /* if something went wrong with email sending, then render login form with errors*/
                        $form->addError(new FormError('Sorry, Something went wrong!'));
                        return $this->render('security/login.html.twig', [
                            'loginForm' => $form->createView(),
                        ]);
                    }
                } else {
                    /* if user email not exist, then render login form with errors*/
                    $form->addError(new FormError('Your email not registered'));
                    return $this->render('security/login.html.twig', [
                        'loginForm' => $form->createView(),
                    ]);
                }
            } else {
                /* if user email not valid, then render login form with errors*/
                $form->addError(new FormError('Your entered invalid email'));
                return $this->render('security/login.html.twig', [
                    'loginForm' => $form->createView(),
                ]);
            }
        }

        /* render login form view*/
        return $this->render('security/login.html.twig', [
            'loginForm' => $form->createView(),
        ]);
    }

    /**
     * Created By Nahla sameh
     * This function display otpForm
     * when submit it check if otp is correct
     * then authenticate user
     * @Route("/confirmOtp", name="confirmOtp", methods={"POST"})
     * @param Request $request
     * @param UsersRepository $usersRepository
     * @param null $email
     * @return Response
     */
    public function confirmOtp(Request $request, UsersRepository $usersRepository, $email = null): Response
    {
        /* Create OtpForm */
        if ($email !== null) { /* If email not null , when if called from login() */
            $form = $this->createForm(OtpLoginType::class, ['email' => $email]);

        } else {
            $form = $this->createForm(OtpLoginType::class);
        }
        $form->handleRequest($request);

        /* Check if the form submitted and is Valid*/
        if ($form->isSubmitted()) {

            if($this->isValidOtp($form->getData()['otp'])) {
                /* Get user by email*/
                $user = $usersRepository->findOneBy(['email' => $form->getData()['email']]);

                /* authenticate Otp */
                if ($this->authenticateOtp($user->getId()) === $form->getData()['otp']) {
                    /* If Otp is correct,then authenticate the user */
                    $this->setLoggedUser($request, $user);

                    /* redirect to colleague list */
                    return $this->redirectToRoute('colleague_index');
                } else {
                    /* If otp isn't correct,then Render View of Otp Form with errors*/
                    $form->addError(new FormError('Your entered wrong otp'));
                    return $this->render('security/login.html.twig', [
                        'loginForm' => $form->createView(),
                    ]);
                }
            }else{
                /* If otp isn't invalid format,then Render View of Otp Form with errors*/
                $form->addError(new FormError('Your entered Invalid otp'));
                return $this->render('security/login.html.twig', [
                    'loginForm' => $form->createView(),
                ]);
            }
        }

        /* Render otp form view*/
        return $this->render('security/login.html.twig', [
            'message' => 'Please Check your email for otp.',
            'loginForm' => $form->createView(),
        ]);
    }

    /**
     * Created By Nahla Sameh
     * logout
     * @Route("/logout", name="logout", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request): Response
    {
        /* Set Logged user to be null*/
        $this->setLoggedUser($request);

        /*Redirect to login page*/
        return $this->redirectToRoute('login');
    }

}
