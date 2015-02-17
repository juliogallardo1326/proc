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
			// @group Processor\Account\AccountHome
			$Map->route('ANY /home', 'Processor\\Account\\AccountHome', 272, 'My Account') ||

			// @group Processor\Account\CreateAccount
			$Map->route('ANY /create/account/', 'Processor\\Account\\CreateAccount') ||
			$Map->route('ANY /accounts', 'Processor\\Account\\CreateAccount', 272, 'Accounts') ||
			$Map->route('ANY /register', 'Processor\\Account\\CreateAccount', 288, 'Register') ||

			// @group Processor\Account\Login
			$Map->route('ANY /login/', 'Processor\\Account\\Login', 288, 'Login') ||

			// @group Processor\Account\Logout
			$Map->route('ANY /logout/', 'Processor\\Account\\Logout', 272, 'Logout') ||

			// @group Processor\Account\ManageAccount
			$Map->route('ANY /a/:id', 'Processor\\Account\\ManageAccount') ||
			$Map->route('ANY /account/:id', 'Processor\\Account\\ManageAccount') ||
			$Map->route('ANY /manage/account/:id', 'Processor\\Account\\ManageAccount') ||

			// @group Processor\Account\SearchAccounts
			$Map->route('ANY /accounts', 'Processor\\Account\\SearchAccounts') ||
			$Map->route('ANY /search/accounts', 'Processor\\Account\\SearchAccounts') ||

			// @group Processor\Integration\Integrate
			$Map->route('ANY /integrate', 'Processor\\Integration\\Integrate', 272, 'Integration') ||

			// @group Processor\Invoice\CreateInvoice
			$Map->route('ANY /create/invoice/', 'Processor\\Invoice\\CreateInvoice') ||

			// @group Processor\Invoice\ManageInvoice
			$Map->route('ANY /i/:id', 'Processor\\Invoice\\ManageInvoice') ||
			$Map->route('ANY /invoice/:id', 'Processor\\Invoice\\ManageInvoice') ||
			$Map->route('ANY /manage/invoice/:id', 'Processor\\Invoice\\ManageInvoice') ||

			// @group Processor\Invoice\SearchInvoices
			$Map->route('ANY /invoices', 'Processor\\Invoice\\SearchInvoices') ||
			$Map->route('ANY /search/invoices', 'Processor\\Invoice\\SearchInvoices') ||

			// @group Processor\OrderForm\OrderForm
			$Map->route('ANY /order/:id', 'Processor\\OrderForm\\OrderForm') ||

			// @group Processor\PaymentSource\CreatePaymentSource
			$Map->route('ANY /create/payment-source/', 'Processor\\PaymentSource\\CreatePaymentSource') ||
			$Map->route('ANY /payment-sources', 'Processor\\PaymentSource\\CreatePaymentSource', 272, 'Payment Source') ||

			// @group Processor\PaymentSource\ManagePaymentSource
			$Map->route('ANY /ps/:id', 'Processor\\PaymentSource\\ManagePaymentSource') ||
			$Map->route('ANY /payment-source/:id', 'Processor\\PaymentSource\\ManagePaymentSource') ||
			$Map->route('ANY /manage/payment-source/:id', 'Processor\\PaymentSource\\ManagePaymentSource') ||

			// @group Processor\PaymentSource\SearchPaymentSources
			$Map->route('ANY /payment-sources', 'Processor\\PaymentSource\\SearchPaymentSources') ||
			$Map->route('ANY /search/payment-sources', 'Processor\\PaymentSource\\SearchPaymentSources') ||

			// @group Processor\Product\CreateProduct
			$Map->route('ANY /create/product/', 'Processor\\Product\\CreateProduct') ||
			$Map->route('ANY /products', 'Processor\\Product\\CreateProduct', 272, 'Products') ||

			// @group Processor\Product\ManageProduct
			$Map->route('ANY /p/:id', 'Processor\\Product\\ManageProduct') ||
			$Map->route('ANY /product/:id', 'Processor\\Product\\ManageProduct') ||
			$Map->route('ANY /manage/product/:id', 'Processor\\Product\\ManageProduct') ||

			// @group Processor\Product\SearchProducts
			$Map->route('ANY /products', 'Processor\\Product\\SearchProducts') ||
			$Map->route('ANY /search/products', 'Processor\\Product\\SearchProducts') ||

			// @group Processor\Profit\SearchProfit
			$Map->route('ANY /profit', 'Processor\\Profit\\SearchProfit', 272, 'Profit') ||
			$Map->route('ANY /search/profit', 'Processor\\Profit\\SearchProfit') ||

			// @group Processor\Report\RiskReport
			$Map->route('ANY /report/risk', 'Processor\\Report\\RiskReport') ||

			// @group Processor\SiteIndex
			$Map->route('ANY /', 'Processor\\SiteIndex') ||

			// @group Processor\Subscription\BatchSubscriptions
			$Map->route('ANY /batch/subscriptions', 'Processor\\Subscription\\BatchSubscriptions') ||

			// @group Processor\Subscription\CreateSubscription
			$Map->route('ANY /create/subscription/', 'Processor\\Subscription\\CreateSubscription') ||

			// @group Processor\Subscription\ManageSubscription
			$Map->route('ANY /s/:id', 'Processor\\Subscription\\ManageSubscription') ||
			$Map->route('ANY /subscription/:id', 'Processor\\Subscription\\ManageSubscription') ||
			$Map->route('ANY /manage/subscription/:id', 'Processor\\Subscription\\ManageSubscription') ||

			// @group Processor\Subscription\SearchSubscriptions
			$Map->route('ANY /subscriptions', 'Processor\\Subscription\\SearchSubscriptions') ||
			$Map->route('ANY /search/subscriptions', 'Processor\\Subscription\\SearchSubscriptions') ||

			// @group Processor\Transaction\BatchRefunds
			$Map->route('ANY /batch/subscriptions', 'Processor\\Transaction\\BatchRefunds') ||

			// @group Processor\Transaction\BatchTransactions
			$Map->route('ANY /batch/transactions', 'Processor\\Transaction\\BatchTransactions') ||

			// @group Processor\Transaction\CreateTransaction
			$Map->route('ANY /create/transaction/', 'Processor\\Transaction\\CreateTransaction') ||
			$Map->route('ANY /transactions', 'Processor\\Transaction\\CreateTransaction', 272, 'Transactions') ||

			// @group Processor\Transaction\ManageTransaction
			$Map->route('ANY /t/:id', 'Processor\\Transaction\\ManageTransaction') ||
			$Map->route('ANY /transaction/:id', 'Processor\\Transaction\\ManageTransaction') ||
			$Map->route('ANY /manage/transaction/:id', 'Processor\\Transaction\\ManageTransaction') ||

			// @group Processor\Transaction\SearchTransactions
			$Map->route('ANY /transactions', 'Processor\\Transaction\\SearchTransactions') ||
			$Map->route('ANY /search/transactions', 'Processor\\Transaction\\SearchTransactions') ||

			// @group Processor\Wallet\CreateWallet
			$Map->route('ANY /create/wallet', 'Processor\\Wallet\\CreateWallet') ||
			$Map->route('ANY /wallets', 'Processor\\Wallet\\CreateWallet', 272, 'Wallets') ||

			// @group Processor\Wallet\ManageWallet
			$Map->route('ANY /w/:id', 'Processor\\Wallet\\ManageWallet') ||
			$Map->route('ANY /manage/wallet/:id', 'Processor\\Wallet\\ManageWallet') ||

			// @group Processor\Wallet\SearchWallets
			$Map->route('ANY /search/wallets', 'Processor\\Wallet\\SearchWallets') ||
			$Map->route('ANY /wallets', 'Processor\\Wallet\\SearchWallets', 272, 'Wallets') ||

			// @group __blank_template
			$Map->route('ANY /order/:id', 'Processor\\Render\\BlankTemplate') ||
			$Map->route('ANY /order', 'Processor\\Render\\BlankTemplate') ||

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