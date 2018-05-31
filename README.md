# billecta-api-php-client
A PHP client library for accessing Billecta API.

Link to the Billecta API
[https://app.billecta.com/docs/v1/index](https://app.billecta.com/docs/v1/index)

# Install

`composer require elitan/billecta-api-php-client`

Next, you need a api secret (SecureToken). This is a bit of a hassle. Read more here under SecureToken:
[https://app.billecta.com/docs/v1/StartAPI#authentication](https://app.billecta.com/docs/v1/StartAPI#authentication)

# Example

```
<?php

require_once __DIR__ . '/vendor/autoload.php';

$base_uri = 'https://apitest.billecta.com';
$api_secret = 'api secret';

$billecta = new \Billecta\Billecta($base_uri, $api_secret);

// get all companies
$companies = $billecta->getCreditors();

var_dump($companies);

// set what company we are working with
$billecta->setCreditorPublicId('creditor_public_id');

// create debtor (customer)
$debtor = Array(
	'Name' => 'Joe Doe'
);
$customer = $billecta->createDebtor($debtor);

var_dump($customer);
```

# Functions

```


// MiSC
$billecta->setCreditorPublicId($creditor_public_id);

// CREDITORS aka COMPANIES
$billecta->getCreditors();

// DEBTORS aka CUSTOMERS
$billecta->createDebtor($debtor);

$billecta->createDebtors($debtors);

$billecta->getDebtor($debtor_public_id);

$billecta->getAllDebtors();

$billecta->deleteDebtor($debtor_public_id);

$billecta->updateDebtor($debtor);

$billecta->getDebtorByExternalId($external_id);

$billecta->getDebtorEvents($debtor_public_id);


// PRODUCTS

$billecta->createProduct($product);

$billecta->updateProduct($product);

$billecta->deleteProduct($product_public_id);

$billecta->getProduct($product_public_id);

$billecta->getAllProducts();

$billecta->getProductByExternalId($external_id);


// INVOICES

$billecta->createInvoice($invoice);

$billecta->updateInvoice($invoice, $invoice_public_id);

$billecta->getInvoice($invoice_public_id);

// Retreives all drafts/attested and unpaid invoices.
$billecta->getOpenInvoices();

// Retreives all invoices that have a closed/full payment date between the specified from and to dates.
$billecta->getClosedInvoices($from_date, $to_date);

$billecta->getOpenInvoicesByDebtor($debtor_public_id);

$billecta->getClosedInvoicesByDebtor($debtor_public_id, $from_date, $to_date);

```
