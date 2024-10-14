<?php

namespace App\Helpers\Fabric\FarmerMSP;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;

class CreateAsset{
    
    public function __construct(){
        
    }
    
    public static function prosess($asset){
        
        $peer = 'cd '.env("FABRIC_HOME").'/varion && export PATH='.env("FABRIC_HOME").'/varion/../bin:$PATH && export FABRIC_CFG_PATH='.env("FABRIC_HOME").'/varion/../config/';

        // 1. As FarmerMSP
        //    a. set environment
        $env = $peer.' && export CORE_PEER_TLS_ENABLED=true';
        $env .= ' && export CORE_PEER_LOCALMSPID="FarmerMSP"';
        $env .= ' && export CORE_PEER_TLS_ROOTCERT_FILE='.env("FABRIC_HOME").'/varion/organizations/peerOrganizations/farmer.varion.com/peers/peer0.farmer.varion.com/tls/ca.crt';
        $env .= ' && export CORE_PEER_MSPCONFIGPATH='.env("FABRIC_HOME").'/varion/organizations/peerOrganizations/farmer.varion.com/users/Admin@farmer.varion.com/msp';
        $env .= ' && export CORE_PEER_ADDRESS=localhost:7051';

        $channel = "varion";
        $chainCode = "farmer_private";

        //    b. create asset
        /*$base64Asset = base64_encode('{"assetID":"asset1","farmerId":"1234567","location":"Aceh","latitude":"94.14131","longitude":"103.14131","itemType":"Asalan SW","description":"Coffee Asalan SW","transactionDate":"2024-09-30","receiptNumber":"RN091334232342","farmerName":"Mas Muda","farmerNik":"NIK00000011111","poNumber":"PONUMBER231322423423","poDate":"2024-09-29","expectedShippingDate":"2024-10-01","itemQuantity":1000,"itemUnitPrice":150000,"floatingRate":3.5,"itemMaxQuantity":2000,"totalPrice":1200000}');*/
        $base64Asset = base64_encode($asset);

        $createAsset = $env.' && peer chaincode invoke -o localhost:7050 --ordererTLSHostnameOverride orderer.varion.com --tls --cafile "'.env("FABRIC_HOME").'/varion/organizations/ordererOrganizations/varion.com/orderers/orderer.varion.com/msp/tlscacerts/tlsca.varion.com-cert.pem" -C '.$channel.' -n '.$chainCode.' -c \'{"function":"CreateAsset","Args":[]}\' --transient "{\"asset_properties\":\"'.$base64Asset.'\"}"';
        return shell_exec($createAsset);
    }
}