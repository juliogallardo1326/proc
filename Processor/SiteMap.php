<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 9/17/14
 * Time: 8:15 AM
 */
namespace Processor;

use CPath\Autoloader;
use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Request\IRequest;
use CPath\Request\Request;
use CPath\Route\CPathMap;
use CPath\Route\IRouteMap;
use CPath\Route\IRouteMapper;
use CPath\Route\RouteBuilder;
use CPath\Route\RouteRenderer;

require_once(__DIR__ . '/../CPath/Autoloader.php');
Autoloader::addLoader(basename(__NAMESPACE__), __DIR__);

class SiteMap implements IRouteMap, IBuildable
{

    /**
     * Maps all routes to the route map. Returns true if the route prefix was matched
     * @param IRouteMapper $Map
     * @return bool if true the route prefix was matched, otherwise false
     * @build routes --disable 0
     * Note: Set --disable 1 or remove doc tag to stop code auto-generation on build for this method
     */
    function mapRoutes(IRouteMapper $Map) {
		return
			// @group Processor\Account\CreateAccount
			$Map->route('ANY /a/:id', 'Processor\\Account\\CreateAccount') ||
			$Map->route('ANY /manage/account/:id', 'Processor\\Account\\CreateAccount') ||

			// @group Processor\Account\ManageAccount
			$Map->route('ANY /a/:id', 'Processor\\Account\\ManageAccount') ||
			$Map->route('ANY /manage/account/:id', 'Processor\\Account\\ManageAccount') ||

			// @group Processor\Account\SearchAccounts
			$Map->route('ANY /accounts', 'Processor\\Account\\SearchAccounts') ||
			$Map->route('ANY /query/accounts', 'Processor\\Account\\SearchAccounts') ||

			// @group Processor\Applications\MerchantApplication
			$Map->route('ANY /applications/merchant', 'Processor\\Applications\\MerchantApplication') ||
			$Map->route('ANY /apply', 'Processor\\Applications\\MerchantApplication') ||

			// @group Processor\Applications\ResellerApplication
			$Map->route('ANY /applications/reseller', 'Processor\\Applications\\ResellerApplication') ||
			$Map->route('ANY /reseller', 'Processor\\Applications\\ResellerApplication') ||

			// @group Processor\Batch\BatchRefunds
			$Map->route('ANY /batch/subscriptions', 'Processor\\Batch\\BatchRefunds') ||

			// @group Processor\Batch\BatchSubscriptions
			$Map->route('ANY /batch/subscriptions', 'Processor\\Batch\\BatchSubscriptions') ||

			// @group Processor\Batch\BatchTransactions
			$Map->route('ANY /batch/transactions', 'Processor\\Batch\\BatchTransactions') ||

			// @group Processor\Integration\Integrate
			$Map->route('ANY /integrate', 'Processor\\Integration\\Integrate') ||

			// @group Processor\Invoice\CreateInvoice
			$Map->route('ANY /create/invoice/', 'Processor\\Invoice\\CreateInvoice') ||

			// @group Processor\Invoice\ManageInvoice
			$Map->route('ANY /i/:id', 'Processor\\Invoice\\ManageInvoice') ||
			$Map->route('ANY /invoice/:id', 'Processor\\Invoice\\ManageInvoice') ||
			$Map->route('ANY /manage/invoice/:id', 'Processor\\Invoice\\ManageInvoice') ||

			// @group Processor\Invoice\SearchInvoices
			$Map->route('ANY /invoices', 'Processor\\Invoice\\SearchInvoices') ||
			$Map->route('ANY /query/invoices', 'Processor\\Invoice\\SearchInvoices') ||

			// @group Processor\Report\RiskReport
			$Map->route('ANY /report/risk', 'Processor\\Report\\RiskReport') ||

			// @group Processor\SiteIndex
			$Map->route('ANY /', 'Processor\\SiteIndex') ||

			// @group Processor\Subscription\CreateSubscription
			$Map->route('ANY /create/subscription/', 'Processor\\Subscription\\CreateSubscription') ||

			// @group Processor\Subscription\ManageSubscription
			$Map->route('ANY /s/:id', 'Processor\\Subscription\\ManageSubscription') ||
			$Map->route('ANY /subscription/:id', 'Processor\\Subscription\\ManageSubscription') ||
			$Map->route('ANY /manage/subscription/:id', 'Processor\\Subscription\\ManageSubscription') ||

			// @group Processor\Subscription\SearchSubscriptions
			$Map->route('ANY /subscriptions', 'Processor\\Subscription\\SearchSubscriptions') ||
			$Map->route('ANY /query/subscriptions', 'Processor\\Subscription\\SearchSubscriptions') ||

			// @group Processor\Transaction\CreateTransaction
			$Map->route('ANY /create/transaction/', 'Processor\\Transaction\\CreateTransaction') ||

			// @group Processor\Transaction\ManageTransaction
			$Map->route('ANY /t/:id', 'Processor\\Transaction\\ManageTransaction') ||
			$Map->route('ANY /transaction/:id', 'Processor\\Transaction\\ManageTransaction') ||
			$Map->route('ANY /manage/transaction/:id', 'Processor\\Transaction\\ManageTransaction') ||

			// @group Processor\Transaction\SearchTransactions
			$Map->route('ANY /transactions', 'Processor\\Transaction\\SearchTransactions') ||
			$Map->route('ANY /query/transactions', 'Processor\\Transaction\\SearchTransactions') ||

			// @group Processor\Wallet\CreateWallet
			$Map->route('ANY /create/wallet/', 'Processor\\Wallet\\CreateWallet') ||

			// @group Processor\Wallet\ManageWallet
			$Map->route('ANY /w/:id', 'Processor\\Wallet\\ManageWallet') ||
			$Map->route('ANY /manage/wallet/:id', 'Processor\\Wallet\\ManageWallet') ||

			// @group Processor\Wallet\SearchWallets
			$Map->route('ANY /query/wallets', 'Processor\\Wallet\\SearchWallets') ||

			// @group Processor\Wallet\WalletRoute
			$Map->route('ANY /wallets', 'Processor\\Wallet\\WalletRoute') ||

			// @group __default_template
			$Map->route('ANY *', 'Processor\\Render\\DefaultTemplate') ||

			// @group _cpath
			$Map->route('ANY *', new CPathMap());
	}

    /**
     * Handle this request and render any content
     * @param IRequest $Request the IRequest inst for this render
     * @return bool returns true if the route was rendered, false if no route was matched
     */
    static function route(IRequest $Request=null) {
        if(!$Request)
            $Request = Request::create();

        $Renderer = new RouteRenderer($Request);
	    $Index = new SiteMap;
	    return $Renderer->renderRoutes($Index);
    }

	/**
	 * Handle this request and render any content
	 * @param IBuildRequest $Request the build request inst for this build session
	 * @return void
	 * @build --disable 0
	 * Note: Use doctag 'build' with '--disable 1' to have this IBuildable class skipped during a build
	 */
	static function handleBuildStatic(IBuildRequest $Request) {
		$RouteBuilder = new RouteBuilder($Request, new static, '_cpath');
		$RouteBuilder->writeRoute('ANY *', 'new CPathMap()');
	}
}