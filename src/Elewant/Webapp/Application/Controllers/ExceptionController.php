<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController as OriginalExceptionController;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Twig\Environment;
use Twig_Error_Loader as LoaderError;
use Twig_Error_Runtime as RuntimeError;
use Twig_Error_Syntax as SyntaxError;

final class ExceptionController extends OriginalExceptionController
{
    /**
     * @param Environment $twig
     * @param bool        $debug
     */
    public function __construct(Environment $twig, $debug)
    {
        parent::__construct($twig, $debug);
    }

    /**
     * @param Request                   $request
     * @param FlattenException          $exception
     * @param DebugLoggerInterface|null $logger
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function showAction(
        Request $request,
        FlattenException $exception,
        ?DebugLoggerInterface $logger = null
    ): Response {
        if ($this->debug) {
            return parent::showAction($request, $exception, $logger);
        }

        $code    = $exception->getStatusCode();
        $message = $exception->getMessage();

        return new Response(
            $this->twig->render('Exception/error.html.twig', ['code' => $code, 'message' => $message]),
            $code
        );
    }
}
