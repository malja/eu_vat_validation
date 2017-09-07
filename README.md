# European VAT number validation
This project contains a PHP class for validation of VAT numbers. 

## Usage
```php
try {
    $validator = new EuVatValidation;
} catch( Exception $e) {
    // SoapClient class does not exist
} catch( SoapFault $e) {
    // Unable to contact server
}

// Check if this combination is valid
print_r( $validator->check("cz", "01234567") ); // Prints out "false"

// Shows array with additional info about trader with this VAT number
print_r( $validator->info( "sk", "<some_valid_vat_number>") );
```

## Requirements
PHP and `SoapClient` ([manual](http://php.net/manual/en/class.soapclient.php)) is required.

## Legal notice
Script uses European Commission website for validation. See [this](http://ec.europa.eu/taxation_customs/vies/viesdisc.do) page for rules of usage.
