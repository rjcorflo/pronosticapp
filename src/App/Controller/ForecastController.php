<?php

namespace RJ\PronosticApp\App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RJ\PronosticApp\Model\Repository\CommunityRepositoryInterface;
use RJ\PronosticApp\Model\Repository\ForecastRepositoryInterface;
use RJ\PronosticApp\Model\Repository\MatchRepositoryInterface;
use RJ\PronosticApp\Util\General\MessageResult;

/**
 * Class ForecastController.
 *
 * Expose operations to update player forecast.
 *
 * @package RJ\PronosticApp\Controller
 */
class ForecastController extends BaseController
{
    /**
     * Save forecasts from user.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function saveForecasts(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $idCommunity
    ): ResponseInterface {
        $bodyData = $request->getParsedBody();

        $result = new MessageResult();

        $player = $request->getAttribute('player');

        /** @var CommunityRepositoryInterface $communityRepository */
        $communityRepository = $this->entityManager->getRepository(CommunityRepositoryInterface::class);
        $community = $communityRepository->getById($idCommunity);

        /** @var ForecastRepositoryInterface $forecastRepository */
        $forecastRepository = $this->entityManager->getRepository(ForecastRepositoryInterface::class);

        /** @var MatchRepositoryInterface $matchRepository */
        $matchRepository = $this->entityManager->getRepository(MatchRepositoryInterface::class);

        $this->entityManager->beginTransaction();
        try {
            foreach ($bodyData as $forecastData) {
                if ($this->checkForecastValidity($forecastData)) {
                    $match = $matchRepository->getById($forecastData['id_partido']);

                    $forecast = $forecastRepository->findOneOrCreate($player, $community, $match);

                    if ($forecast->getId() === 0) {
                        $forecast->setPlayer($player);
                        $forecast->setCommunity($community);
                        $forecast->setMatch($match);
                    }

                    $forecast->setLocalGoals($forecastData['goles_local']);
                    $forecast->setAwayGoals($forecastData['goles_visitante']);
                    $forecast->setRisk((bool)$forecastData['riesgo']);
                    $forecast->setLastModifiedDate(new \DateTime());

                    $forecastRepository->store($forecast);
                } else {
                    throw new \Exception('Error en validando alguno de los pronósticos');
                }
            }

            $result->setDescription('Guardado correcto');

            $resource = $this->resourceGenerator->createMessageResource($result);

            $response = $this->generateJsonCorrectResponse($response, $resource);
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            $response = $this->generateJsonErrorResponse($response, $e);
        }

        return $response;
    }

    /**
     * @param $forecast
     * @return bool
     */
    private function checkForecastValidity($forecast): bool
    {
        $isValid = isset($forecast['id_partido']) && isset($forecast['goles_local'])
            && isset($forecast['goles_visitante']) && isset($forecast['riesgo']);

        return $isValid;
    }
}
