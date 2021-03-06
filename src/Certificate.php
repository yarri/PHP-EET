<?php
/**
 * This file is part of the PHP-EET package.
 *
 * (c) Filip Sedivy <mail@filipsedivy.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT
 * @author Filip Sedivy <mail@filipsedivy.cz>
 */

namespace FilipSedivy\EET;

use FilipSedivy\EET\Exceptions\CertificateException;

/**
 * Parsování PKCS#12 a uchování X.509 certifikátu
 *
 * @version 1.0.2
*/
class Certificate
{
    /** @var string */
    private $pkey;

    /** @var string */
    private $cert;


    /**
     * Certificate constructor
     *
     * @param   string  $certificate  Path of certificate
     * @param   string  $password     Certificate password
     * @throws  CertificateException
     */
    public function __construct($certificate, $password)
    {
        if(!file_exists($certificate))
        {
            throw new CertificateException("Certificate was not found");
        }

        $certs = [];
        $pkcs12 = file_get_contents($certificate);

        if (!extension_loaded('openssl') || !function_exists('openssl_pkcs12_read'))
        {
            throw new CertificateException("OpenSSL extension is not available.");
        }

        $openSSL = openssl_pkcs12_read($pkcs12, $certs, $password);
        if(!$openSSL)
        {
            throw new CertificateException("The certificate has failed to export.");
        }

        $this->pkey = $certs['pkey'];
        $this->cert = $certs['cert'];
    }


    /**
     *
     * @return string
     */
    public function getPrivateKey(){
        return $this->pkey;
    }


    /**
     *
     * @return string
     */
    public function getCert(){
        return $this->cert;
    }
}
