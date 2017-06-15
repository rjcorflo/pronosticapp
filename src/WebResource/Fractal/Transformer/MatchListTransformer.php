<?php

namespace RJ\PronosticApp\WebResource\Fractal\Transformer;

use League\Fractal\TransformerAbstract;
use RJ\PronosticApp\WebResource\Fractal\Resource\MatchListResource;

/**
 * Class MatchListTransformer.
 *
 * @package RJ\PronosticApp\WebResource\Fractal\Transformer
 */
class MatchListTransformer extends TransformerAbstract
{
    /**
     * @param  MatchListResource $matchList
     * @return array
     */
    public function transform(MatchListResource $matchList)
    {
        $item = [
            'fecha_actual' => $matchList->getActualDate()->format('Y-m-d H:i:s'),
            'partidos' => []
        ];

        foreach ($matchList->getMatches() as $match) {
            $item['partidos'][] = $match->getId();
        }

        return $item;
    }
}
