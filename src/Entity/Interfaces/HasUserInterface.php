<?php

namespace App\Entity\Interfaces;

use App\Entity\User;

interface HasUserInterface
{
    public function getUser(): ?User;
    public function setUser(?User $user): static;
}