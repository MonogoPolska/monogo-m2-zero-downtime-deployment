<?php

namespace Monogo\ZeroDowntimeDeployment\Plugin\Module\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\FrontController;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Module\Plugin\DbStatusValidator;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

/**
 * Class DbStatusValidatorPlugin
 *
 * @category Monogo
 * @package  Monogo\ZeroDowntimeDeployment
 * @license  MIT
 * @author   Paweł Detka <pawel.detka@monogo.pl>
 */
class DbStatusValidatorPlugin
{
    const CONFIG_MODULE_ENABLED = 'zerodowntimedeployment/general/enabled';

    const CONFIG_LOGGER_ENABLED = 'zerodowntimedeployment/general/logger_enabled';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * DbStatusValidatorPlugin constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface      $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Override Magento plugin
     *
     * @param DbStatusValidator $subject
     * @param callable          $proceed
     * @param FrontController   $originalSubject
     * @param RequestInterface  $request
     *
     * @return void
     */
    public function aroundBeforeDispatch(
        DbStatusValidator $subject,
        callable $proceed,
        FrontController $originalSubject,
        RequestInterface $request
    ) {
        if (!$this->getConfig(self::CONFIG_MODULE_ENABLED)) {
            return $proceed($originalSubject, $request);
        }

        if ($this->getConfig(self::CONFIG_LOGGER_ENABLED)) {
            try {
                $proceed($originalSubject, $request);
            } catch (\Exception $e) {
                $this->logger->alert('ZeroDowntimeDeployment: ' . $e->getMessage());
            }
        }
    }

    /**
     * Get Store Config by key
     *
     * @param string $configPath
     * @param int    $storeId
     *
     * @return mixed
     */
    protected function getConfig($configPath, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
