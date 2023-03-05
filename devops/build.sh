#!/bin/bash

# Build RSA-keys if missing.
if [ ! -f mykey.pub ]; then
    openssl genrsa -out mykey.pem 1024
    openssl rsa -in mykey.pem -pubout > mykey.pub
fi