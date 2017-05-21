<?php

namespace RJ\PronosticApp\WebResource\Fractal\Transformer;

use League\Container\ContainerInterface;
use League\Fractal\TransformerAbstract;
use RJ\PronosticApp\Model\Entity\PlayerInterface;

class PlayerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [
        'comunidades'
    ];

    /**
     * @var \League\Container\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function transform(PlayerInterface $player)
    {
        $item = [
            'id' => $player->getId(),
            'nickname' => $player->getNickname(),
            'email' => $player->getEmail(),
            'nombre' => $player->getFirstName(),
            'apellidos' => $player->getLastName()
        ];

        return $item;
    }

    /**
     * Include Comunidades
     *
     * @param PlayerInterface $player
     * @return \League\Fractal\Resource\Collection
     */
    public function includeComunidades(PlayerInterface $player)
    {
        $communities = $player->getPlayerCommunities();

        return $this->collection($communities, $this->container->get(CommunityTransformer::class));
    }
}
