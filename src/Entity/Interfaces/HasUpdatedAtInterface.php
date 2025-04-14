<?php

namespace App\Entity\Interfaces;

interface HasUpdatedAtInterface
{
    public function getUpdatedAt(): ?\DateTimeImmutable;
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static;
}