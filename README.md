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

// CREDITORS aka COMPANIES
$billecta->getCreditors();

// MiSC
$billecta->setCreditorPublicId($creditor_public_id);

// DEBTORS aka CUSTOMERS
$billecta->createDebtor($debtor);

$billecta->createDebtors($debtors);

$billecta->getDebtor($debtor_public_id);

$billecta->getAllDebtors();

$billecta->deleteDebtor($debtor_public_id);

$billecta->updateDebtor($debtor);

$billecta->getDebtorByExternalId($external_id);

$billecta->getDebtorEvents($debtor_public_id);

```
