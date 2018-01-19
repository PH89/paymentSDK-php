<?php
// # Klarna Guaranteed Invoice reserve transaction

// This example displays the usage of reserve method for payment method Klarna Guaranteed Invoice.

// ## Required objects

// To include the necessary files, we use the composer for PSR-4 autoloading.
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../inc/common.php';
require __DIR__ . '/../inc/config.php';
//Header design
require __DIR__ . '/../inc/header.php';

use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\InteractionResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\KlarnaInvoiceTransaction;
use Wirecard\PaymentSdk\TransactionService;

// ### Transaction related objects

// Use the amount object as amount which has to be paid by the consumer.
$amount = new Amount(2400, 'EUR');

// As soon as the transaction status changes, a server-to-server notification will get delivered to this URL.
//$notificationUrl = getUrl('notify.php');

// The order number
$orderNumber = '22098';

// ### Basket items
// A Basket contains one or more items.

// For each item you have to set some properties as described here.
// Required: name, price, quantity, article number, tax rate.
// Optional: description.
$item1 = new \Wirecard\PaymentSdk\Entity\Item('Item 1', new Amount(400, 'EUR'), 1);
// In contrast to the [basket example](../Features/basket.html),
// Klarna Guaranteed Invoice requires the **tax rate** and the ** article number**.
$item1->setArticleNumber('A1');
$item1->setTaxRate(10.0);

$item2 = new \Wirecard\PaymentSdk\Entity\Item('Item 2', new Amount(1000, 'EUR'), 2);
$item2->setArticleNumber('B2');
$item2->setTaxRate(20.0);

// Create a basket to store the items.
$basket = new \Wirecard\PaymentSdk\Entity\Basket();
$basket->add($item1);
$basket->add($item2);

// #### Account holder with address
// Klarna Guaranteed Invoice uses klarna developer testdata
$address = new \Wirecard\PaymentSdk\Entity\Address('DE', 'Neuss', 'Hellersbergstraße');
$address->setPostalCode('41460');
$address->setStreet2('Hellersbergstraße');

$accountHolder = new \Wirecard\PaymentSdk\Entity\AccountHolder();
$accountHolder->setFirstName('Testperson-de');
$accountHolder->setLastName('Approved');
$accountHolder->setEmail('youremail@email.com');
$accountHolder->setPhone('01522113356');
$accountHolder->setDateOfBirth(new \DateTime('1960-07-07'));
$accountHolder->setAddress($address);
$accountHolder->setGender('m');

// #### Shipping with address
// Shipping should not include birthdate and gender
$shipping = new \Wirecard\PaymentSdk\Entity\AccountHolder();
$shipping->setFirstName('Testperson-de');
$shipping->setLastName('Approved');
$shipping->setEmail('youremail@email.com');
$shipping->setPhone('01522113356');
$shipping->setAddress($address);

// ## Transaction

// The Klarna Guaranteed Invoice transaction holds all transaction relevant data for the reserve process.
$transaction = new KlarnaInvoiceTransaction();
$transaction->setAmount($amount);
$transaction->setBasket($basket);
$transaction->setOrderNumber($orderNumber);
$transaction->setShipping($shipping);
$transaction->setAccountHolder($accountHolder);
$transaction->setCountry('DE');
$transaction->setLocale('de');


// ### Transaction Service

// The service is used to execute the reserve operation itself. A response object is returned.
$transactionService = new TransactionService($config);
$response = $transactionService->reserve($transaction);

// ## Response handling

// The response of the service must be handled depending on it's class
// In case of an `InteractionResponse`, a browser interaction by the consumer is required
// in order to continue the reserve process. In this example we proceed with a header redirect
// to the given _redirectUrl_. IFrame integration using this URL is also possible.
if ($response instanceof SuccessResponse) {
	echo 'Reservation successfully completed.<br>';
	echo getTransactionLink($baseUrl, $response);
	?>
	<br>
	<form action="pay.php" method="post">
		<input type="hidden" name="parentTransactionId" value="<?= $response->getTransactionId() ?>"/>
		<div class="form-group">
			<label for="amount">Amount:</label>
			<input id="amount" name="amount" class="form-control"/>
		</div>
		<button type="submit" class="btn btn-primary">Request a new payment based on this reservation</button>
	</form>
	<?php
// In case of a failed transaction, a `FailureResponse` object is returned.
} elseif ($response instanceof InteractionResponse) {
    die("<meta http-equiv='refresh' content='0;url={$response->getRedirectUrl()}'>");

// The failure state is represented by a FailureResponse object.
// In this case the returned errors should be stored in your system.
} elseif ($response instanceof FailureResponse) {
// In our example we iterate over all errors and echo them out. You should display them as
// error, warning or information based on the given severity.
    foreach ($response->getStatusCollection() as $status) {
        /**
         * @var $status \Wirecard\PaymentSdk\Entity\Status
         */
        $severity = ucfirst($status->getSeverity());
        $code = $status->getCode();
        $description = $status->getDescription();
        echo sprintf('%s with code %s and message "%s" occurred.<br>', $severity, $code, $description);
    }
}
//Footer design
require __DIR__ . '/../inc/footer.php';