#!/bin/bash

# Build RSA-keys if missing.
if [ ! -f mykey.pub ]; then
    openssl genrsa -out "${PWD}/mykey.pem" 1024
    openssl rsa -in "${PWD}/mykey.pem" -pubout > "${PWD}/mykey.pub"
    sed -i "/JWT_PRIV_KEY=/d" "${PWD}/.env"
    sed -i "/JWT_PUB_KEY=/d" "${PWD}/.env"
    echo "JWT_PRIV_KEY=${PWD}/mykey.pem" >> "${PWD}/.env"
    echo "JWT_PUB_KEY=${PWD}/mykey.pub" >> "${PWD}/.env"
    
fi