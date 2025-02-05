<?php

namespace App\Twig;

use App\Controller\WalletController;
use App\Repository\WalletRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class WalletExtension extends AbstractExtension
{
    public function __construct(
        private Security $security,
        private WalletRepository $walletRepository
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('user_wallets', [$this, 'getUserWallets']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('format_balance', [$this, 'formatBalance']),
        ];
    }

    public function formatBalance(string $balance, string $currency): string
    {
        $value = floatval($balance);
        
        // For SPACE-COINS, show 8 decimal places
        if ($currency === 'SPACE-COINS') {
            return number_format($value, 8, '.', ',');
        }
        
        // For regular currencies (USD, EUR), show 2 decimal places
        return number_format($value, 2, '.', ',');
    }

    public function getUserWallets(): array
    {
        $user = $this->security->getUser();
        return WalletController::getUserWallets($user, $this->walletRepository);
    }
}
