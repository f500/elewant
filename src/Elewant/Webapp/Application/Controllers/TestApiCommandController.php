<?php

/**
 * Leaving this intact, as this file was copied verbatim from the Prooph symfony example.
 * Big big thanks for their fantastic work!
 * prooph (http://getprooph.org/)
 *
 * @see       https://github.com/prooph/proophessor-do-symfony for the canonical source repository
 */

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\CommandDispatchException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * This controller is only used in the develop and test environments.
 */
final class TestApiCommandController
{
    private const NAME_ATTRIBUTE = 'prooph_command_name';

    private CommandBus $commandBus;

    private MessageFactory $messageFactory;

    private LoggerInterface $logger;

    public function __construct(CommandBus $commandBus, MessageFactory $messageFactory, LoggerInterface $logger)
    {
        $this->commandBus = $commandBus;
        $this->messageFactory = $messageFactory;
        $this->logger = $logger;
    }

    public function postAction(Request $request): Response
    {
        $commandName = $request->attributes->get(self::NAME_ATTRIBUTE);

        if ($commandName === null) {
            return JsonResponse::create(
                [
                    'message' => sprintf(
                        'Command name attribute ("%s") was not found in request.',
                        self::NAME_ATTRIBUTE
                    ),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        try {
            $payload = $this->getPayloadFromRequest($request);
        } catch (Throwable $error) {
            return JsonResponse::create(['message' => $error->getMessage()], $error->getCode());
        }

        $command = $this->messageFactory->createMessageFromArray($commandName, ['payload' => $payload]);

        try {
            $this->commandBus->dispatch($command);
        } catch (CommandDispatchException $ex) {
            $message = $ex->getMessage();

            if ($ex->getPrevious() !== null) {
                $message = $ex->getPrevious()->getMessage();
            }

            $this->logger->error($message);

            return JsonResponse::create(['message' => $message], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Throwable $error) {
            $this->logger->error($error->getMessage());

            return JsonResponse::create(['message' => $error->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return JsonResponse::create(null, Response::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     * @return mixed[]
     * @throws RuntimeException
     */
    private function getPayloadFromRequest(Request $request): array
    {
        $payload = json_decode((string) $request->getContent(), true);

        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                throw new RuntimeException('Invalid JSON, maximum stack depth exceeded.', 400);
            case JSON_ERROR_UTF8:
                throw new RuntimeException('Malformed UTF-8 characters, possibly incorrectly encoded.', 400);
            case JSON_ERROR_SYNTAX:
            case JSON_ERROR_CTRL_CHAR:
            case JSON_ERROR_STATE_MISMATCH:
                throw new RuntimeException('Invalid JSON.', 400);
        }

        return $payload ?? [];
    }
}
