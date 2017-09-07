<?php

/**
 * This class provides simple SOAP client for Europen VAT numbers validation.
*/
class EuVatValidation {
    
        /**
         * Address of the SOAP application server.
         */
        private $address;

        /**
         * Instance of soap client.
         */
        private $soap_client;
    
        /**
        * Creates validator class and prepares inner soap client.
        * @param  $address  string  Address of SOAP server.
        * @throws SoapFault if the url can not be loaded.
        * @throws Exception if the address parameter of this constructor is not valid address.
        * @throws Exception if the SoapClient class does not exist.
        */
        public function __construct( $address = "http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl" ) {
    
            // Validate entered URL
            if ( filter_var( $address, FILTER_VALIDATE_URL ) ) {
                $this->address = $address;
            } else {
                throw new Exception( "Address of SOAP API is not valid URL." );
            }
    
            // Check if SoapClient exists
            if ( !class_exists( SoapClient ) ) {
                throw new Exception( "Soap library is not installed or enabled." );
            }
    
            // Create SOAP client
            $this->soap_client = new SoapClient( $this->address);
        }
    
        // For debugging purposes only
        public function getFunctions() {
            return $this->soap_client->__getFunctions();
        }
    
        /**
         * Check if entered country code and VAT number combination is valid.
         * @param $country_code string  Two character identifier of country. Eg. CZ for Czech Republic.
         * @param $vat_number string    VAT number itself.
         * @return boolean True if VAT number is valid, false otherwise.
         */
        public function check( $country_code, $vat_number ) {
    
            $response = $this->soap_client->checkVat( array(
                "countryCode" => $country_code,
                "vatNumber" => $vat_number
            ));
    
            return $response->valid;
            
        }
    
        /**
         * Returns all information about the trader along with the validity flag.
         * @param $country_code string  Two character identifier of country. Eg. CZ for Czech Republic.
         * @param $vat_number string    VAT number itself.
         * @return array Associative array. Keys:
         *  - valid         boolean True when the VAT number is valid, false otherwise
         *  - countryCode   string  Country code of VAT number
         *  - vatNumber     string
         *  - traderAddress string  Addres of holder of the VAT number
         *  - requestDate   string  Date and time of this check
         */
        public function info( $country_code, $vat_number ) {
    
            $response = $this->soap_client->checkVatApprox( array(
                "countryCode" => $country_code,
                "vatNumber" => $vat_number,
            ));
    
            $result = array(
                "valid" => $response->valid,
                "countryCode" => $response->countryCode,
                "vatNumber" => $response->vatNumber,
                "traderAddress" => $response->traderAddress,
                "requestDate" => $response->requestDate,
            );
    
            return $result;
    
        }
    
    }

?>