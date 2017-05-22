<?php

namespace RJ\PronosticApp\Model\Entity;

interface CommunityInterface
{
    /**
     * @return int
     */
    public function getId() : int;

    /**
     * @param string $communityName
     */
    public function setCommunityName(string $communityName) : void;

    /**
     * @return string
     */
    public function getCommunityName() : string;

    /**
     * @param string $password
     */
    public function setPassword(string $password) : void;

    /**
     * @return string
     */
    public function getPassword() : string;

    /**
     * @param bool $isPrivate
     */
    public function setPrivate(bool $isPrivate) : void;

    /**
     * @return bool
     */
    public function isPrivate() : bool;

    /**
     * @param \DateTime $date
     */
    public function setCreationDate(\DateTime $date) : void;

    /**
     * @return \DateTime
     */
    public function getCreationDate() : \DateTime;

    /**
     * @param \RJ\PronosticApp\Model\Entity\PlayerInterface $player
     */
    public function addAdmin(PlayerInterface $player) : void;

    /**
     * @param \RJ\PronosticApp\Model\Entity\PlayerInterface $player
     */
    public function removeAdmin(PlayerInterface $player) : void;

    /**
     * @return PlayerInterface[] List of administrators.
     */
    public function getAdmins() : array;

    /**
     * @param ImageInterface $image
     */
    public function setImage(ImageInterface $image) : void;

    /**
     * @return \RJ\PronosticApp\Model\Entity\ImageInterface
     */
    public function getImage() : ImageInterface;

    /**
     * @param string $color
     */
    public function setColor(string $color) : void;

    /**
     * @return string
     */
    public function getColor() : string;

    /**
     * Get the list of players from the community.
     * @return PlayerInterface[] List of player from this community.
     */
    public function getPlayers() : array;

    /**
     * Add a player to community.
     * @param PlayerInterface $player
     */
    public function addPlayer(PlayerInterface $player) : void;

    /**
     * Remove a player from the community.
     * @param PlayerInterface $player
     */
    public function removePlayer(PlayerInterface $player) : void;
}
