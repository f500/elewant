<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController as OriginalExceptionController;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Twig\Environment;

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

    public function showAction(Request $request, FlattenException $exception, ?DebugLoggerInterface $logger = null)
    {
        if ($this->debug) {
            return parent::showAction($request, $exception, $logger);
        }

        $code = $exception->getStatusCode();
        $message = $exception->getMessage();

        return new Response(
            $this->twig->render('Exception/error.html.twig', ['code' => $code, 'message' => $message]),
            $code
        );
    }
}
