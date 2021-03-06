<?php
namespace VirtualCard\Schema\Vendor\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Result
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    private $reference;
    
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Luhn()
     */
    private $cardNumber;
    
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="3", min="3")
     */
    private $cvc;
    
    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }
    
    /**
     * @param string|null $reference
     * @return Result
     */
    public function setReference(?string $reference): Result
    {
        $this->reference = $reference;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }
    
    /**
     * @param string|null $cardNumber
     * @return Result
     */
    public function setCardNumber(?string $cardNumber): Result
    {
        $this->cardNumber = $cardNumber;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getCvc(): ?string
    {
        return $this->cvc;
    }
    
    /**
     * @param string|null $cvc
     * @return Result
     */
    public function setCvc(?string $cvc): Result
    {
        $this->cvc = $cvc;
        
        return $this;
    }
}