<?php

namespace App\Helpers\Fabric\HullerMSP;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;

class ReadAsset{
    
    public function __construct(){
        
    }
    
    public function public($assetId){
        
        $peer = 'cd '.env("FABRIC_HOME").'/varion && export PATH='.env("FABRIC_HOME").'/varion/../bin:$PATH && export FABRIC_CFG_PATH='.env("FABRIC_HOME").'/varion/../config/';
        
        //set environment
        $env = $peer.' && export CORE_PEER_TLS_ENABLED=true';
        $env .= ' && export CORE_PEER_LOCALMSPID="HullerMSP"';
        $env .= ' && export CORE_PEER_TLS_ROOTCERT_FILE='.env("FABRIC_HOME").'/varion/organizations/peerOrganizations/huller.varion.com/peers/peer0.huller.varion.com/tls/ca.crt';
        $env .= ' && export CORE_PEER_MSPCONFIGPATH='.env("FABRIC_HOME").'/varion/organizations/peerOrganizations/huller.varion.com/users/Admin@huller.varion.com/msp';
        $env .= ' && export CORE_PEER_ADDRESS=localhost:9051';

        
        $readAsset = $env." && peer chaincode query -C varion -n private -c '{\"function\":\"ReadAsset\",\"Args\":[\"$assetId\"]}'";
        $result = shell_exec($readAsset);

        return json_decode($result, true);
    }
    
    public function private($assetId, $msp){
        
        $peer = 'cd '.env("FABRIC_HOME").'/varion && export PATH='.env("FABRIC_HOME").'/varion/../bin:$PATH && export FABRIC_CFG_PATH='.env("FABRIC_HOME").'/varion/../config/';
        
        //set environment
        $env = $peer.' && export CORE_PEER_TLS_ENABLED=true';
        $env .= ' && export CORE_PEER_LOCALMSPID="HullerMSP"';
        $env .= ' && export CORE_PEER_TLS_ROOTCERT_FILE='.env("FABRIC_HOME").'/varion/organizations/peerOrganizations/huller.varion.com/peers/peer0.huller.varion.com/tls/ca.crt';
        $env .= ' && export CORE_PEER_MSPCONFIGPATH='.env("FABRIC_HOME").'/varion/organizations/peerOrganizations/huller.varion.com/users/Admin@huller.varion.com/msp';
        $env .= ' && export CORE_PEER_ADDRESS=localhost:9051';
        
        $readAsset = $env." && peer chaincode query -C varion -n private -c '{\"function\":\"ReadAssetPrivateDetails\",\"Args\":[\"$mspPrivateCollection\",\"$assetId\"]}'";
        $result = shell_exec($readAsset);

        return json_decode($result, true);
    }
}