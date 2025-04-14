<?php

namespace App\Entity\Interfaces;

interface HasCreatedAtInterface
{
    public function getCreatedAt(): ?\DateTimeImmutable;
    public function setCreatedAt(?\DateTimeImmutable $createdAt): static;
}